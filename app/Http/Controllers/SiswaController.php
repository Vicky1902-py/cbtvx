<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalUjian;
use App\Models\UjianPeserta;
use App\Models\UjianJawaban;
use App\Models\SoalButir;
use App\Models\MasterSekolah;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Dashboard Peserta Ujian
     */
    public function dashboard()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        // Ambil jadwal ujian yang aktif dan sesuai kelas siswa atau untuk semua kelas
        $jadwalAktif = JadwalUjian::with(['bankSoal.mataPelajaran'])
            ->where('status', 'aktif')
            ->where(function ($q) use ($siswa) {
                $q->whereHas('kelas', function ($k) use ($siswa) {
                    $k->where('kelas.id', $siswa->kelas_id);
                })->orDoesntHave('kelas');
            })
            ->get();

        // Ambil riwayat ujian siswa
        $riwayatUjian = UjianPeserta::where('siswa_id', $siswa->id)
            ->with(['jadwalUjian.bankSoal.mataPelajaran'])
            ->get()
            ->keyBy('jadwal_ujian_id');

        $sekolah = MasterSekolah::first() ?? new MasterSekolah();

        return view('siswa.dashboard', compact('sekolah', 'siswa', 'jadwalAktif', 'riwayatUjian'));
    }

    /**
     * Halaman Konfirmasi Tes & Masukkan Token
     */
    public function konfirmasi($id)
    {
        $siswa = Auth::user()->siswa;
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran'])->findOrFail($id);

        // Verifikasi isolasi kelas: Siswa hanya dapat mengikuti ujian yang ditugaskan ke kelasnya
        if ($jadwal->kelas()->exists() && !$jadwal->kelas()->where('kelas.id', $siswa->kelas_id)->exists()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian ini bukan untuk kelas Anda.');
        }

        $peserta = UjianPeserta::where('jadwal_ujian_id', $jadwal->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($peserta && $peserta->status === 'selesai') {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda telah menyelesaikan ujian ini.');
        }

        return view('siswa.konfirmasi', compact('siswa', 'jadwal', 'peserta'));
    }

    /**
     * Validasi Token dan Mulai Ujian
     */
    public function mulaiUjian(Request $request, $id)
    {
        $siswa = Auth::user()->siswa;
        $jadwal = JadwalUjian::with('bankSoal.butirSoal')->findOrFail($id);

        // Verifikasi isolasi kelas: Siswa hanya dapat memulai ujian yang ditugaskan ke kelasnya
        if ($jadwal->kelas()->exists() && !$jadwal->kelas()->where('kelas.id', $siswa->kelas_id)->exists()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian ini bukan untuk kelas Anda.');
        }

        $tokenInput = strtoupper(trim($request->input('token', '')));

        if ($tokenInput !== strtoupper(trim($jadwal->token_ujian))) {
            return back()->withErrors(['token' => 'Token ujian tidak valid. Silahkan tanyakan kepada pengawas / proktor.']);
        }

        $peserta = UjianPeserta::firstOrCreate(
            [
                'jadwal_ujian_id' => $jadwal->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'waktu_mulai' => now(),
                'sisa_detik' => $jadwal->durasi_menit * 60,
                'status' => 'berlangsung',
                'ip_pengerjaan' => $request->ip(),
            ]
        );

        if ($peserta->status === 'selesai') {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian ini sudah Anda selesaikan.');
        }

        if ($peserta->status === 'belum') {
            $peserta->update([
                'status' => 'berlangsung',
                'waktu_mulai' => now(),
                'ip_pengerjaan' => $request->ip(),
            ]);
        }

        $siswa->update(['status_login' => 'sedang_tes']);

        return redirect()->route('siswa.ujian', ['id' => $jadwal->id]);
    }

    /**
     * Tampilan Pengerjaan Ujian (Standar ANBK Pusmendik)
     */
    public function kerjakan($id)
    {
        $siswa = Auth::user()->siswa;
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'bankSoal.butirSoal'])->findOrFail($id);

        if ($jadwal->kelas()->exists() && !$jadwal->kelas()->where('kelas.id', $siswa->kelas_id)->exists()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian ini bukan untuk kelas Anda.');
        }

        $peserta = UjianPeserta::where('jadwal_ujian_id', $jadwal->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($peserta->status === 'selesai') {
            return redirect()->route('siswa.dashboard')->with('success', 'Ujian telah selesai.');
        }

        // Ambil daftar soal (sembunyikan kunci jawaban & rubrik penilaian agar 100% aman dari inspect browser siswa)
        $soalList = $jadwal->bankSoal->butirSoal->makeHidden(['kunci_jawaban', 'rubrik_penilaian']);

        // Ambil seluruh jawaban yang sudah tersimpan
        $jawabanList = UjianJawaban::where('ujian_peserta_id', $peserta->id)
            ->get()
            ->keyBy('soal_butir_id');

        return view('siswa.ujian', compact('siswa', 'jadwal', 'peserta', 'soalList', 'jawabanList'));
    }

    /**
     * Simpan Jawaban Butir Soal (AJAX / Form)
     */
    public function simpanJawaban(Request $request, $id)
    {
        $siswa = Auth::user()->siswa;
        $jadwal = JadwalUjian::findOrFail($id);

        $peserta = UjianPeserta::where('jadwal_ujian_id', $jadwal->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($peserta->status === 'selesai') {
            return response()->json(['status' => 'error', 'message' => 'Ujian telah selesai'], 403);
        }

        $soalId = $request->input('soal_id');
        $jawaban = $request->input('jawaban');
        $ragu = $request->boolean('ragu_ragu');
        $sisaDetik = $request->input('sisa_detik');

        if ($sisaDetik !== null) {
            $peserta->update(['sisa_detik' => max(0, (int)$sisaDetik)]);
        }

        $soal = SoalButir::findOrFail($soalId);

        if ($soal->jenis_soal === 'uraian') {
            $isBenar = null;
            $nilai = 0; // Dinilai manual oleh guru penguji/proktor
        } else {
            $cleanSiswa = strtoupper(trim((string)$jawaban));
            $cleanKunci = strtoupper(trim((string)$soal->kunci_jawaban));
            // Dukungan Benar/Salah (B = BENAR, S = SALAH)
            if ($soal->jenis_soal === 'benar_salah') {
                if ($cleanSiswa === 'B') $cleanSiswa = 'BENAR';
                if ($cleanSiswa === 'S') $cleanSiswa = 'SALAH';
                if ($cleanKunci === 'B') $cleanKunci = 'BENAR';
                if ($cleanKunci === 'S') $cleanKunci = 'SALAH';
            }
            $isBenar = ($cleanSiswa === $cleanKunci);
            $nilai = $isBenar ? (float)$soal->bobot : 0;
        }

        $ujianJawaban = UjianJawaban::updateOrCreate(
            [
                'ujian_peserta_id' => $peserta->id,
                'soal_butir_id' => $soal->id,
            ],
            [
                'jawaban_siswa' => $jawaban,
                'ragu_ragu' => $ragu,
                'is_benar' => $isBenar,
                'nilai_diperoleh' => $nilai,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Tersimpan otomatis',
            'data' => [
                'soal_id' => $soal->id,
                'jawaban' => $jawaban,
                'ragu_ragu' => $ragu,
            ]
        ]);
    }

    /**
     * Catat Pelanggaran Ujian (Pindah Tab / Keluar Fullscreen)
     */
    public function catatPelanggaran(Request $request, $id)
    {
        $siswa = Auth::user()->siswa;
        $peserta = UjianPeserta::where('jadwal_ujian_id', $id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($peserta && $peserta->status === 'berlangsung') {
            $peserta->increment('jumlah_pelanggaran');
            return response()->json([
                'status' => 'success',
                'pelanggaran' => $peserta->jumlah_pelanggaran,
            ]);
        }

        return response()->json(['status' => 'ignored']);
    }

    /**
     * Selesai Ujian & Hitung Nilai Otomatis
     */
    public function selesaiUjian(Request $request, $id)
    {
        $siswa = Auth::user()->siswa;
        $jadwal = JadwalUjian::with('bankSoal.butirSoal')->findOrFail($id);

        $peserta = UjianPeserta::where('jadwal_ujian_id', $jadwal->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        $daftarSoal = $jadwal->bankSoal->butirSoal;
        $totalBenar = 0;
        $totalSalah = 0;
        $totalKosong = 0;
        $totalNilai = 0.0;

        foreach ($daftarSoal as $soal) {
            $jwb = UjianJawaban::where('ujian_peserta_id', $peserta->id)
                ->where('soal_butir_id', $soal->id)
                ->first();

            if (!$jwb || empty($jwb->jawaban_siswa)) {
                $totalKosong++;
            } else if (strtoupper(trim($jwb->jawaban_siswa)) === strtoupper(trim($soal->kunci_jawaban))) {
                $totalBenar++;
                $totalNilai += (float)$soal->bobot;
            } else {
                $totalSalah++;
            }
        }

        $peserta->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'sisa_detik' => 0,
            'jawaban_benar' => $totalBenar,
            'jawaban_salah' => $totalSalah,
            'jawaban_kosong' => $totalKosong,
            'nilai_pg' => $totalNilai,
            'total_nilai' => min(100.0, $totalNilai),
        ]);

        $siswa->update(['status_login' => 'selesai']);

        return redirect()->route('siswa.dashboard')->with('success', 'Ujian berhasil diselesaikan! Nilai akhir Anda: ' . number_format($totalNilai, 1));
    }
}
