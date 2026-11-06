<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterSekolah;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\BankSoal;
use App\Models\JadwalUjian;
use App\Models\UjianPeserta;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Administrator / Proktor
     */
    public function dashboard()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalBankSoal = BankSoal::count();
        $totalJadwal = JadwalUjian::count();

        // Peserta Ujian Aktif Realtime
        $pesertaAktif = UjianPeserta::with(['siswa.kelas', 'jadwalUjian.bankSoal.mataPelajaran'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.dashboard', compact(
            'sekolah',
            'totalSiswa',
            'totalKelas',
            'totalBankSoal',
            'totalJadwal',
            'pesertaAktif'
        ));
    }

    /**
     * Master Data Siswa
     */
    public function siswa()
    {
        $sekolah = MasterSekolah::first();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $siswaList = Siswa::with('kelas', 'user')->orderBy('nama_lengkap')->paginate(25);

        return view('admin.siswa', compact('sekolah', 'kelasList', 'siswaList'));
    }

    /**
     * Bank Soal
     */
    public function bankSoal()
    {
        $sekolah = MasterSekolah::first();
        $bankList = BankSoal::with(['mataPelajaran', 'butirSoal'])->latest()->get();

        return view('admin.bank_soal', compact('sekolah', 'bankList'));
    }

    /**
     * Jadwal Ujian
     */
    public function jadwal()
    {
        $sekolah = MasterSekolah::first();
        $jadwalList = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas'])->latest()->get();

        return view('admin.jadwal', compact('sekolah', 'jadwalList'));
    }

    /**
     * Reset Login Siswa
     */
    public function resetLogin($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $siswa->update([
            'status_login' => 'offline',
            'ip_address' => null,
        ]);

        // Jika ada sesi ujian yang terkunci, reset agar bisa masuk kembali
        $peserta = UjianPeserta::where('siswa_id', $siswaId)
            ->where('status', 'berlangsung')
            ->first();

        if ($peserta) {
            $peserta->update(['ip_pengerjaan' => null]);
        }

        return back()->with('success', 'Sesi login siswa ' . $siswa->nama_lengkap . ' berhasil direset.');
    }

    /**
     * Cetak Kartu Peserta Ujian Resmi A4
     */
    public function cetakKartu(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $kelasId = $request->input('kelas_id');

        $query = Siswa::with('kelas', 'user');
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
        $daftarSiswa = $query->orderBy('nama_lengkap')->get();

        return view('admin.cetak_kartu', compact('sekolah', 'daftarSiswa'));
    }

    /**
     * Cetak Berita Acara Ujian Resmi A4
     */
    public function cetakBeritaAcara($jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'peserta.siswa.kelas'])->findOrFail($jadwalId);

        return view('admin.cetak_berita_acara', compact('sekolah', 'jadwal'));
    }

    /**
     * Pengaturan Sekolah & TTD Kepala Sekolah
     */
    public function pengaturan()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        return view('admin.pengaturan', compact('sekolah'));
    }

    /**
     * Simpan Pengaturan Sekolah
     */
    public function simpanPengaturan(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();

        $data = $request->validate([
            'nama_sekolah' => 'required|string',
            'npsn' => 'required|string',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
            'provinsi' => 'required|string',
            'kepala_sekolah' => 'required|string',
            'nip_kepala_sekolah' => 'nullable|string',
            'tahun_ajaran_aktif' => 'required|string',
            'semester_aktif' => 'required|string',
        ]);

        if ($request->hasFile('ttd_kepala_sekolah')) {
            $file = $request->file('ttd_kepala_sekolah');
            $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['ttd_kepala_sekolah'] = 'images/' . $filename;
        }

        $sekolah->fill($data);
        $sekolah->save();

        return back()->with('success', 'Pengaturan sekolah dan identitas kepala sekolah berhasil diperbarui.');
    }
}
