<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            ->take(25)
            ->get();

        // Data Grafik Donat: Status Peserta Ujian
        $countSelesai = UjianPeserta::where('status', 'selesai')->count();
        $countBerlangsung = UjianPeserta::where('status', 'berlangsung')->count();
        $countBelum = max(0, $totalSiswa - ($countSelesai + $countBerlangsung));

        // Data Grafik Batang: Rata-Rata Nilai per Kelas
        $kelasList = Kelas::with(['siswa.ujianPeserta'])->get();
        $chartKelasLabels = [];
        $chartKelasAvg = [];
        $chartKelasHighest = [];

        foreach ($kelasList as $kelas) {
            $chartKelasLabels[] = $kelas->nama_kelas;
            $allScores = [];

            foreach ($kelas->siswa as $s) {
                foreach ($s->ujianPeserta as $up) {
                    if ($up->status === 'selesai') {
                        $allScores[] = (float) $up->total_nilai;
                    }
                }
            }

            if (count($allScores) > 0) {
                $chartKelasAvg[] = round(array_sum($allScores) / count($allScores), 1);
                $chartKelasHighest[] = round(max($allScores), 1);
            } else {
                $chartKelasAvg[] = 0;
                $chartKelasHighest[] = 0;
            }
        }

        // Metrik Pemantau Server Realtime
        $memUsage = memory_get_usage(true);
        $memUsageMB = round($memUsage / 1024 / 1024, 2);
        $memLimit = ini_get('memory_limit') ?: '512M';

        $basePath = base_path();
        $diskFree = @disk_free_space($basePath) ?: (50 * 1024 * 1024 * 1024);
        $diskTotal = @disk_total_space($basePath) ?: (100 * 1024 * 1024 * 1024);
        $diskUsed = max(0, $diskTotal - $diskFree);
        $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 1) : 0;
        $diskFreeGB = round($diskFree / 1024 / 1024 / 1024, 1);
        $diskTotalGB = round($diskTotal / 1024 / 1024 / 1024, 1);

        // Pengukuran Latensi Database & Respon Server (ms)
        $startDb = microtime(true);
        try {
            DB::select('SELECT 1');
            $dbLatencyMs = round((microtime(true) - $startDb) * 1000, 2);
            $dbStatus = 'Online (Koneksi Optimal)';
        } catch (\Throwable $e) {
            $dbLatencyMs = 999;
            $dbStatus = 'Gangguan Koneksi';
        }

        $serverStats = [
            'ram_used_mb' => $memUsageMB,
            'ram_limit' => $memLimit,
            'disk_percent' => $diskPercent,
            'disk_free_gb' => $diskFreeGB,
            'disk_total_gb' => $diskTotalGB,
            'db_latency_ms' => $dbLatencyMs,
            'db_status' => $dbStatus,
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'LiteSpeed / Apache Web Server',
        ];

        return view('admin.dashboard', compact(
            'sekolah',
            'totalSiswa',
            'totalKelas',
            'totalBankSoal',
            'totalJadwal',
            'pesertaAktif',
            'countSelesai',
            'countBerlangsung',
            'countBelum',
            'chartKelasLabels',
            'chartKelasAvg',
            'chartKelasHighest',
            'serverStats'
        ));
    }

    /**
     * Master Data Siswa
     */
    public function siswa()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $siswaList = Siswa::with('kelas', 'user')->orderBy('nama_lengkap')->paginate(25);

        return view('admin.siswa', compact('sekolah', 'kelasList', 'siswaList'));
    }

    /**
     * Download Template Siswa CSV
     */
    public function downloadTemplateSiswa()
    {
        $csvHeader = "nis,nisn,nama_lengkap,jenis_kelamin,kode_kelas,sesi_ujian,ruangan_ujian,nomor_peserta\n";
        $csvContent = "22101,0054819201,Joko Susanto,L,XI-MM,Sesi 1,Lab Komputer 1,26-24-05-01-001-8\n" .
                      "22102,0054819202,Maria Fatima Berek,P,XI-MM,Sesi 1,Lab Komputer 1,26-24-05-01-002-7\n" .
                      "21101,0054819203,Yehezkiel Nalle,L,XII-TKJ,Sesi 2,Lab Komputer 2,26-24-05-01-003-6\n";

        return response($csvHeader . $csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_data_siswa_cbt.csv"',
        ]);
    }

    /**
     * Bank Soal
     */
    public function bankSoal()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $bankList = BankSoal::with(['mataPelajaran', 'butirSoal'])->latest()->get();

        return view('admin.bank_soal', compact('sekolah', 'bankList'));
    }

    /**
     * Jadwal Ujian
     */
    public function jadwal()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas', 'peserta'])->latest()->get();

        return view('admin.jadwal', compact('sekolah', 'jadwalList'));
    }

    /**
     * Generate Token Baru Otomatis
     */
    public function generateToken($jadwalId)
    {
        $jadwal = JadwalUjian::findOrFail($jadwalId);
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $newToken = substr(str_shuffle($characters), 0, 5);

        $jadwal->update(['token_ujian' => $newToken]);

        return back()->with('success', 'Token asesmen berhasil diperbarui menjadi: ' . $newToken);
    }

    /**
     * Paksa Selesai Semua Peserta Ujian (Force Finish)
     */
    public function forceFinishAll($jadwalId)
    {
        $jadwal = JadwalUjian::with('bankSoal.butirSoal')->findOrFail($jadwalId);
        $pesertaList = UjianPeserta::where('jadwal_ujian_id', $jadwalId)
            ->where('status', 'berlangsung')
            ->get();

        $daftarSoal = $jadwal->bankSoal->butirSoal;

        foreach ($pesertaList as $p) {
            $totalNilai = 0.0;
            $totalBenar = 0;
            $totalSalah = 0;
            $totalKosong = 0;

            foreach ($daftarSoal as $soal) {
                $jwb = \App\Models\UjianJawaban::where('ujian_peserta_id', $p->id)
                    ->where('soal_butir_id', $soal->id)
                    ->first();

                if (!$jwb || empty($jwb->jawaban_siswa)) {
                    $totalKosong++;
                } elseif (strtoupper(trim($jwb->jawaban_siswa)) === strtoupper(trim($soal->kunci_jawaban))) {
                    $totalBenar++;
                    $totalNilai += (float) $soal->bobot;
                } else {
                    $totalSalah++;
                }
            }

            $p->update([
                'status' => 'selesai',
                'waktu_selesai' => now(),
                'sisa_detik' => 0,
                'jawaban_benar' => $totalBenar,
                'jawaban_salah' => $totalSalah,
                'jawaban_kosong' => $totalKosong,
                'total_nilai' => min(100.0, $totalNilai),
            ]);

            if ($p->siswa) {
                $p->siswa->update(['status_login' => 'selesai']);
            }
        }

        return back()->with('success', 'Berhasil memaksa selesai ' . $pesertaList->count() . ' peserta ujian.');
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

        $peserta = UjianPeserta::where('siswa_id', $siswaId)
            ->where('status', 'berlangsung')
            ->first();

        if ($peserta) {
            $peserta->update(['ip_pengerjaan' => null]);
        }

        return back()->with('success', 'Sesi login siswa ' . $siswa->nama_lengkap . ' berhasil direset.');
    }

    /**
     * Reset Semua Siswa Terkunci
     */
    public function resetAllLogins()
    {
        Siswa::whereIn('status_login', ['online', 'sedang_tes'])->update([
            'status_login' => 'offline',
            'ip_address' => null,
        ]);

        UjianPeserta::where('status', 'berlangsung')->update(['ip_pengerjaan' => null]);

        return back()->with('success', 'Seluruh sesi siswa yang terkunci berhasil direset.');
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
