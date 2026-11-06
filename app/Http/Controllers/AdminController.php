<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use App\Models\MasterSekolah;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\BankSoal;
use App\Models\SoalButir;
use App\Models\JadwalUjian;
use App\Models\UjianPeserta;
use App\Models\UjianJawaban;
use App\Models\BeritaAcara;
use App\Models\PetugasUjian;

class AdminController extends Controller
{
    /**
     * Dashboard Utama & Monitoring Realtime
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

    // ==========================================
    // DATA SISWA (CRUD + IMPORT/EKSPOR XLSX)
    // ==========================================

    public function siswa(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        $query = Siswa::with('kelas', 'user');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama_lengkap', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%")
                  ->orWhere('nis', 'like', "%{$q}%")
                  ->orWhere('nomor_peserta', 'like', "%{$q}%");
            });
        }

        $siswaList = $query->orderBy('nama_lengkap')->paginate(25)->withQueryString();

        return view('admin.siswa', compact('sekolah', 'kelasList', 'siswaList'));
    }

    public function simpanSiswa(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'nis' => 'nullable|string|max:30',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'sesi_ujian' => 'required|string',
            'ruangan_ujian' => 'required|string',
            'nomor_peserta' => 'nullable|string|max:40|unique:siswa,nomor_peserta',
            'password' => 'nullable|string|min:4',
        ]);

        $username = trim($data['nisn']);
        $password = !empty($data['password']) ? $data['password'] : '12345';

        $user = User::create([
            'name' => $data['nama_lengkap'],
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'nisn' => $data['nisn'],
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'kelas_id' => $data['kelas_id'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'sesi_ujian' => $data['sesi_ujian'],
            'ruangan_ujian' => $data['ruangan_ujian'],
            'nomor_peserta' => $data['nomor_peserta'] ?: ('26-24-05-' . str_pad($user->id, 5, '0', STR_PAD_LEFT)),
            'status_login' => 'offline',
        ]);

        return back()->with('success', 'Siswa ' . $data['nama_lengkap'] . ' berhasil ditambahkan.');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nis' => 'nullable|string|max:30',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'sesi_ujian' => 'required|string',
            'ruangan_ujian' => 'required|string',
            'nomor_peserta' => 'nullable|string|max:40|unique:siswa,nomor_peserta,' . $siswa->id,
            'password' => 'nullable|string|min:4',
        ]);

        $siswa->update([
            'nisn' => $data['nisn'],
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'kelas_id' => $data['kelas_id'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'sesi_ujian' => $data['sesi_ujian'],
            'ruangan_ujian' => $data['ruangan_ujian'],
            'nomor_peserta' => $data['nomor_peserta'],
        ]);

        $userData = [
            'name' => $data['nama_lengkap'],
            'username' => $data['nisn'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $siswa->user()->update($userData);

        return back()->with('success', 'Data siswa ' . $siswa->nama_lengkap . ' berhasil diperbarui.');
    }

    public function hapusSiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;

        $nama = $siswa->nama_lengkap;
        $siswa->delete();
        if ($user) {
            $user->delete();
        }

        return back()->with('success', 'Data siswa ' . $nama . ' berhasil dihapus.');
    }

    public function importSiswaXlsx(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_excel.required' => 'Pilih berkas Excel (.xlsx / .csv) untuk diunggah.',
            'file_excel.mimes' => 'Format berkas harus berupa .xlsx, .xls, atau .csv.',
        ]);

        $file = $request->file('file_excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Header berada di baris 1: A=NIS, B=NISN, C=Nama Lengkap, D=JK, E=Kode Kelas, F=Sesi, G=Ruang, H=No Peserta, I=Password
        $totalImported = 0;
        $totalSkipped = 0;

        foreach ($rows as $index => $row) {
            if ($index == 1) continue; // Skip header

            $nisn = trim((string)($row['B'] ?? ''));
            $nama = trim((string)($row['C'] ?? ''));

            if (empty($nisn) || empty($nama)) {
                $totalSkipped++;
                continue;
            }

            $nis = trim((string)($row['A'] ?? ''));
            $jk = strtoupper(trim((string)($row['D'] ?? 'L')));
            if (!in_array($jk, ['L', 'P'])) $jk = 'L';

            $kodeKelas = trim((string)($row['E'] ?? ''));
            $kelas = Kelas::where('kode_kelas', $kodeKelas)->orWhere('nama_kelas', $kodeKelas)->first();
            $kelasId = $kelas ? $kelas->id : (Kelas::first()->id ?? null);

            $sesi = trim((string)($row['F'] ?? 'Sesi 1')) ?: 'Sesi 1';
            $ruang = trim((string)($row['G'] ?? 'Lab Komputer 1')) ?: 'Lab Komputer 1';
            $noPes = trim((string)($row['H'] ?? '')) ?: ('26-24-05-' . $nisn);
            $pass = trim((string)($row['I'] ?? '')) ?: '12345';

            // Update or Create User
            $user = User::updateOrCreate(
                ['username' => $nisn],
                [
                    'name' => $nama,
                    'password' => Hash::make($pass),
                    'role' => 'siswa',
                    'status' => 'aktif',
                ]
            );

            // Update or Create Siswa
            Siswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $jk,
                    'kelas_id' => $kelasId,
                    'sesi_ujian' => $sesi,
                    'ruangan_ujian' => $ruang,
                    'nomor_peserta' => $noPes,
                    'status_login' => 'offline',
                ]
            );

            $totalImported++;
        }

        return back()->with('success', "Proses impor selesai: {$totalImported} siswa berhasil diimpor/diperbarui, {$totalSkipped} baris kosong dilewati.");
    }

    public function downloadTemplateSiswaXlsx()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Data Siswa');

        // Header
        $headers = [
            'A1' => 'NIS',
            'B1' => 'NISN (Wajib)',
            'C1' => 'Nama Lengkap (Wajib)',
            'D1' => 'Jenis Kelamin (L/P)',
            'E1' => 'Kode Kelas (Contoh: XI-MM)',
            'F1' => 'Sesi Ujian (Contoh: Sesi 1)',
            'G1' => 'Ruangan (Contoh: Lab Komputer 1)',
            'H1' => 'Nomor Peserta Ujian',
            'I1' => 'Kata Sandi / Password (Default: 12345)'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Contoh Data Nyata
        $examples = [
            ['22101', '0054819201', 'Joko Susanto', 'L', 'XI-MM', 'Sesi 1', 'Lab Komputer 1', '26-24-05-01-001-8', '12345'],
            ['22102', '0054819202', 'Maria Fatima Berek', 'P', 'XI-MM', 'Sesi 1', 'Lab Komputer 1', '26-24-05-01-002-7', '12345'],
            ['21101', '0054819203', 'Yehezkiel Nalle', 'L', 'XII-TKJ', 'Sesi 2', 'Lab Komputer 2', '26-24-05-01-003-6', '12345'],
        ];

        $rowIdx = 2;
        foreach ($examples as $row) {
            $sheet->setCellValue('A' . $rowIdx, $row[0]);
            $sheet->setCellValueExplicit('B' . $rowIdx, $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowIdx, $row[2]);
            $sheet->setCellValue('D' . $rowIdx, $row[3]);
            $sheet->setCellValue('E' . $rowIdx, $row[4]);
            $sheet->setCellValue('F' . $rowIdx, $row[5]);
            $sheet->setCellValue('G' . $rowIdx, $row[6]);
            $sheet->setCellValue('H' . $rowIdx, $row[7]);
            $sheet->setCellValueExplicit('I' . $rowIdx, $row[8], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $rowIdx++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Impor_Siswa_CBT_SMKN1KB.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function exportSiswaXlsx()
    {
        $siswaList = Siswa::with('kelas', 'user')->orderBy('nama_lengkap')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa CBT');

        $headers = ['A1' => 'No.', 'B1' => 'Nomor Peserta', 'C1' => 'NIS', 'D1' => 'NISN', 'E1' => 'Nama Lengkap', 'F1' => 'JK', 'G1' => 'Kelas / Rombel', 'H1' => 'Sesi', 'I1' => 'Ruangan', 'J1' => 'Status Login'];
        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($siswaList as $idx => $s) {
            $sheet->setCellValue('A' . $rowIdx, $idx + 1);
            $sheet->setCellValue('B' . $rowIdx, $s->nomor_peserta);
            $sheet->setCellValue('C' . $rowIdx, $s->nis);
            $sheet->setCellValueExplicit('D' . $rowIdx, $s->nisn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowIdx, $s->nama_lengkap);
            $sheet->setCellValue('F' . $rowIdx, $s->jenis_kelamin);
            $sheet->setCellValue('G' . $rowIdx, $s->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('H' . $rowIdx, $s->sesi_ujian);
            $sheet->setCellValue('I' . $rowIdx, $s->ruangan_ujian);
            $sheet->setCellValue('J' . $rowIdx, strtoupper($s->status_login));
            $rowIdx++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data_Siswa_CBT_SMKN1_Kupang_Barat_' . date('Ymd_His') . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    // ==========================================
    // DATA KELAS (CRUD)
    // ==========================================

    public function kelas()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $kelasList = Kelas::withCount('siswa')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.kelas', compact('sekolah', 'kelasList'));
    }

    public function simpanKelas(Request $request)
    {
        $data = $request->validate([
            'kode_kelas' => 'required|string|max:30|unique:kelas,kode_kelas',
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:100',
        ]);

        Kelas::create($data);
        return back()->with('success', 'Rombel Kelas ' . $data['nama_kelas'] . ' berhasil ditambahkan.');
    }

    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $data = $request->validate([
            'kode_kelas' => 'required|string|max:30|unique:kelas,kode_kelas,' . $kelas->id,
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:100',
        ]);

        $kelas->update($data);
        return back()->with('success', 'Rombel Kelas ' . $kelas->nama_kelas . ' berhasil diperbarui.');
    }

    public function hapusKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $nama = $kelas->nama_kelas;
        $kelas->delete();
        return back()->with('success', 'Kelas ' . $nama . ' berhasil dihapus.');
    }

    // ==========================================
    // DATA MATA PELAJARAN (CRUD & IMPORT/EXPORT XLSX)
    // ==========================================

    public function mapel(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $guruList = User::whereIn('role', ['guru', 'admin', 'proktor'])->orderBy('name')->get();

        $query = MataPelajaran::with(['guru'])->withCount('bankSoal');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('kode_mapel', 'like', "%{$q}%")
                  ->orWhere('nama_mapel', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }

        $mapelList = $query->orderBy('nama_mapel')->paginate(15)->withQueryString();

        return view('admin.mapel', compact('sekolah', 'mapelList', 'guruList'));
    }

    public function simpanMapel(Request $request)
    {
        $data = $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
            'kelompok' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'tingkat' => 'required|in:Semua,10,11,12',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        MataPelajaran::create($data);
        return back()->with('success', 'Mata Pelajaran ' . $data['nama_mapel'] . ' (' . $data['jurusan'] . ') berhasil ditambahkan.');
    }

    public function updateMapel(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $data = $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajaran,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:100',
            'kelompok' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'tingkat' => 'required|in:Semua,10,11,12',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel->update($data);
        return back()->with('success', 'Mata Pelajaran ' . $mapel->nama_mapel . ' berhasil diperbarui.');
    }

    public function hapusMapel($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $nama = $mapel->nama_mapel;
        $mapel->delete();
        return back()->with('success', 'Mata Pelajaran ' . $nama . ' berhasil dihapus.');
    }

    public function downloadTemplateMapelXlsx()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Mapel');

        $headers = [
            'A1' => 'Kode Mapel (Wajib/Unik)',
            'B1' => 'Nama Mata Pelajaran (Wajib)',
            'C1' => 'Kelompok (Umum/Kejuruan)',
            'D1' => 'Jurusan Sasaran',
            'E1' => 'Tingkat (Semua/10/11/12)',
            'F1' => 'NIP atau Username Guru Pengampu',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $samples = [
            ['MTK-DPIB-X', 'Matematika Terapan (DPIB)', 'Muatan Umum (A)', 'Desain Pemodelan dan Informasi Bangunan', '10', '0945774675230212'],
            ['BIN-X', 'Bahasa Indonesia', 'Muatan Umum (A)', 'Semua Jurusan', '10', '7339765666131093'],
            ['C2-DPIB-X', 'Dasar-dasar Gambar Teknik dan Statika Bangunan', 'Dasar Program Keahlian (C2)', 'Desain Pemodelan dan Informasi Bangunan', '10', '199204052020121007'],
            ['C3-TITL-XI', 'Instalasi Penerangan dan Tenaga Listrik Bangunan', 'Konsentrasi Keahlian / Produktif (C3)', 'Teknik Instalasi Tenaga Listrik', '11', '198603262022211009'],
            ['TKA-SIMDIG', 'Simulasi dan Komunikasi Digital', 'Dasar Program Keahlian (C2)', 'Semua Jurusan', '10', 'admin'],
        ];

        $rowIdx = 2;
        foreach ($samples as $row) {
            $sheet->setCellValue('A' . $rowIdx, $row[0]);
            $sheet->setCellValue('B' . $rowIdx, $row[1]);
            $sheet->setCellValue('C' . $rowIdx, $row[2]);
            $sheet->setCellValue('D' . $rowIdx, $row[3]);
            $sheet->setCellValue('E' . $rowIdx, $row[4]);
            $sheet->setCellValue('F' . $rowIdx, $row[5]);
            $rowIdx++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Mata_Pelajaran_SMK.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importMapelXlsx(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file_excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $totalImported = 0;
        $totalSkipped = 0;

        foreach ($rows as $index => $row) {
            if ($index == 1) continue; // Skip header

            $kode = trim((string)($row['A'] ?? ''));
            $nama = trim((string)($row['B'] ?? ''));

            if (empty($kode) || empty($nama)) {
                $totalSkipped++;
                continue;
            }

            $kelompok = trim((string)($row['C'] ?? 'Muatan Umum (A)'));
            if (empty($kelompok)) $kelompok = 'Muatan Umum (A)';

            $jurusan = trim((string)($row['D'] ?? 'Semua Jurusan'));
            if (empty($jurusan)) $jurusan = 'Semua Jurusan';

            $tingkat = trim((string)($row['E'] ?? 'Semua'));
            if (!in_array($tingkat, ['Semua', '10', '11', '12'])) $tingkat = 'Semua';

            $guruId = null;
            $guruRef = trim((string)($row['F'] ?? ''));
            if (!empty($guruRef)) {
                $guruUser = User::where('nip', $guruRef)
                    ->orWhere('username', $guruRef)
                    ->orWhere('name', 'like', "%{$guruRef}%")
                    ->first();
                if ($guruUser) {
                    $guruId = $guruUser->id;
                }
            }

            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $kode],
                [
                    'nama_mapel' => $nama,
                    'kelompok' => $kelompok,
                    'jurusan' => $jurusan,
                    'tingkat' => $tingkat,
                    'guru_id' => $guruId,
                ]
            );

            $totalImported++;
        }

        return back()->with('success', "Impor berhasil: {$totalImported} mata pelajaran disimpan/diperbarui, {$totalSkipped} baris kosong dilewati.");
    }

    public function exportMapelXlsx()
    {
        $mapels = MataPelajaran::with(['guru'])->withCount('bankSoal')->orderBy('nama_mapel')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Mata Pelajaran');

        $headers = [
            'A1' => 'No',
            'B1' => 'Kode Mapel',
            'C1' => 'Nama Mata Pelajaran',
            'D1' => 'Kelompok Mapel',
            'E1' => 'Jurusan Sasaran',
            'F1' => 'Tingkat',
            'G1' => 'Guru Pengampu',
            'H1' => 'NIP / NUPTK Guru',
            'I1' => 'Jumlah Paket Bank Soal',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($mapels as $idx => $m) {
            $sheet->setCellValue('A' . $rowIdx, $idx + 1);
            $sheet->setCellValue('B' . $rowIdx, $m->kode_mapel);
            $sheet->setCellValue('C' . $rowIdx, $m->nama_mapel);
            $sheet->setCellValue('D' . $rowIdx, $m->kelompok);
            $sheet->setCellValue('E' . $rowIdx, $m->jurusan);
            $sheet->setCellValue('F' . $rowIdx, $m->tingkat);
            $sheet->setCellValue('G' . $rowIdx, $m->guru ? $m->guru->name : '-');
            $sheet->setCellValue('H' . $rowIdx, $m->guru ? ($m->guru->nip ?: ($m->guru->nuptk ?: '-')) : '-');
            $sheet->setCellValue('I' . $rowIdx, $m->bank_soal_count);
            $rowIdx++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Daftar_Mata_Pelajaran_SMKN1_Kupang_Barat_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ==========================================
    // DATA USER & GURU (CRUD)
    // ==========================================

    public function users(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $query = User::whereIn('role', ['admin', 'guru', 'proktor']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('username', 'like', "%{$q}%")
                  ->orWhere('nip', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $userList = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.users', compact('sekolah', 'userList'));
    }

    public function simpanUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'nip' => 'nullable|string|max:40',
            'nuptk' => 'nullable|string|max:40',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,guru,proktor',
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nip' => $data['nip'] ?? null,
            'nuptk' => $data['nuptk'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Pengguna ' . $data['name'] . ' (' . ucfirst($data['role']) . ') berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'nip' => 'nullable|string|max:40',
            'nuptk' => 'nullable|string|max:40',
            'password' => 'nullable|string|min:4',
            'role' => 'required|in:admin,guru,proktor',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nip' => $data['nip'] ?? null,
            'nuptk' => $data['nuptk'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Data pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    public function hapusUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $nama = $user->name;
        $user->delete();
        return back()->with('success', 'Pengguna ' . $nama . ' berhasil dihapus.');
    }

    // ==========================================
    // BANK SOAL & BUTIR SOAL (PRESET 25 & 40)
    // ==========================================

    public function bankSoal(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $mapelList = MataPelajaran::with('guru')->orderBy('nama_mapel')->get();

        $query = BankSoal::with(['mataPelajaran.guru', 'guru', 'butirSoal']);

        if ($request->filled('mapel_id')) {
            $query->where('mata_pelajaran_id', $request->mapel_id);
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $bankList = $query->latest()->get();

        return view('admin.bank_soal', compact('sekolah', 'mapelList', 'bankList'));
    }

    public function simpanBankSoal(Request $request)
    {
        $data = $request->validate([
            'kode_bank' => 'required|string|max:50|unique:bank_soal,kode_bank',
            'nama_bank' => 'nullable|string|max:150',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tingkat' => 'required|in:Semua,10,11,12',
            'standar_preset' => 'required|in:25,40,kustom',
            'kkm' => 'required|numeric|min:0|max:100',
        ]);

        $mapel = MataPelajaran::findOrFail($data['mata_pelajaran_id']);

        // Hitung bobot otomatis sesuai Roadmap:
        // Standar 25 nomor: Benar/Salah 5 x 4 = 20; PG 15 x 2 = 30; Uraian 5 x 10 = 50. Total = 100
        // Standar 40 nomor: Benar/Salah 10 x 1 = 10; PG 20 x 2 = 40; Uraian 10 x 5 = 50. Total = 100
        if ($data['standar_preset'] === '25') {
            $standarBobot = '25_soal';
            $jumlahPg = 15;
            $bobotPg = 30.00;
            $jumlahBs = 5;
            $bobotBs = 20.00;
            $jumlahUraian = 5;
            $bobotUraian = 50.00;
        } elseif ($data['standar_preset'] === '40') {
            $standarBobot = '40_soal';
            $jumlahPg = 20;
            $bobotPg = 40.00;
            $jumlahBs = 10;
            $bobotBs = 10.00;
            $jumlahUraian = 10;
            $bobotUraian = 50.00;
        } else {
            $standarBobot = 'kustom';
            $jumlahPg = 25;
            $bobotPg = 100.00;
            $jumlahBs = 0;
            $bobotBs = 0.00;
            $jumlahUraian = 0;
            $bobotUraian = 0.00;
        }

        $namaBank = $data['nama_bank'] ?: ($mapel->nama_mapel . ' (' . $mapel->jurusan . ')');

        $bank = BankSoal::create([
            'kode_bank' => $data['kode_bank'],
            'nama_bank' => $namaBank,
            'mata_pelajaran_id' => $mapel->id,
            'guru_id' => $mapel->guru_id ?? auth()->id(),
            'tingkat' => $data['tingkat'],
            'standar_bobot' => $standarBobot,
            'jumlah_soal_pg' => $jumlahPg,
            'jumlah_soal_bs' => $jumlahBs,
            'jumlah_soal_uraian' => $jumlahUraian,
            'bobot_pg' => $bobotPg,
            'bobot_bs' => $bobotBs,
            'bobot_uraian' => $bobotUraian,
            'kkm' => $data['kkm'],
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.bank_soal.detail', $bank->id)->with('success', 'Paket Bank Soal ' . $bank->kode_bank . ' berhasil dibuat. Silahkan kelola butir soal.');
    }

    public function detailBankSoal(Request $request, $id)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $bank = BankSoal::with(['mataPelajaran.guru', 'guru', 'butirSoal'])->findOrFail($id);

        $filterJenis = $request->query('jenis', 'semua');
        $soalQuery = $bank->butirSoal();
        if ($filterJenis !== 'semua') {
            $soalQuery->where('jenis_soal', $filterJenis);
        }
        $soalList = $soalQuery->orderBy('nomor_urut')->get();

        // Hitung statistik butir soal saat ini
        $allSoal = $bank->butirSoal;
        $countPg = $allSoal->where('jenis_soal', 'pg')->count();
        $countBs = $allSoal->where('jenis_soal', 'benar_salah')->count();
        $countUraian = $allSoal->where('jenis_soal', 'uraian')->count();

        $bobotPgCurrent = (float) $allSoal->where('jenis_soal', 'pg')->sum('bobot');
        $bobotBsCurrent = (float) $allSoal->where('jenis_soal', 'benar_salah')->sum('bobot');
        $bobotUraianCurrent = (float) $allSoal->where('jenis_soal', 'uraian')->sum('bobot');
        $totalBobotCurrent = (float) $allSoal->sum('bobot');

        // Nomor urut berikutnya yang disarankan
        $maxNomor = $allSoal->max('nomor_urut') ?? 0;
        $nextNomor = $maxNomor + 1;

        // Default bobot saran berdasarkan preset
        if ($bank->standar_bobot === '25_soal') {
            $saranBobotPg = 2.0;
            $saranBobotBs = 4.0;
            $saranBobotUraian = 10.0;
        } elseif ($bank->standar_bobot === '40_soal') {
            $saranBobotPg = 2.0;
            $saranBobotBs = 1.0;
            $saranBobotUraian = 5.0;
        } else {
            $saranBobotPg = 4.0;
            $saranBobotBs = 2.0;
            $saranBobotUraian = 10.0;
        }

        return view('admin.bank_soal_detail', compact(
            'sekolah', 'bank', 'soalList', 'filterJenis',
            'countPg', 'countBs', 'countUraian',
            'bobotPgCurrent', 'bobotBsCurrent', 'bobotUraianCurrent', 'totalBobotCurrent',
            'nextNomor', 'saranBobotPg', 'saranBobotBs', 'saranBobotUraian'
        ));
    }

    public function simpanButirSoal(Request $request, $bankId)
    {
        $bank = BankSoal::findOrFail($bankId);

        $data = $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'jenis_soal' => 'required|in:pg,benar_salah,uraian',
            'konten_soal' => 'required|string',
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'opsi_e' => 'nullable|string',
            'kunci_jawaban' => 'nullable|string',
            'bobot' => 'required|numeric|min:0',
            'rubrik_uraian' => 'nullable|string',
            'gambar_soal' => 'nullable|image|max:5120',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $createData = [
            'bank_soal_id' => $bank->id,
            'nomor_urut' => $data['nomor_urut'],
            'jenis_soal' => $data['jenis_soal'],
            'konten_soal' => $data['konten_soal'],
            'opsi_a' => $data['opsi_a'] ?? null,
            'opsi_b' => $data['opsi_b'] ?? null,
            'opsi_c' => $data['opsi_c'] ?? null,
            'opsi_d' => $data['opsi_d'] ?? null,
            'opsi_e' => $data['opsi_e'] ?? null,
            'kunci_jawaban' => strtoupper(trim($data['kunci_jawaban'] ?? '')),
            'bobot' => $data['bobot'],
            'rubrik_uraian' => $data['rubrik_uraian'] ?? null,
        ];

        if ($request->hasFile('gambar_soal')) {
            $destDir = public_path('uploads/soal');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $img = $request->file('gambar_soal');
            $imgName = 'img_' . time() . '_' . Str::random(6) . '.' . $img->getClientOriginalExtension();
            $img->move($destDir, $imgName);
            $createData['gambar_soal'] = 'uploads/soal/' . $imgName;
        }

        if ($request->hasFile('audio_file')) {
            $destDir = public_path('uploads/soal');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $audio = $request->file('audio_file');
            $audioName = 'audio_' . time() . '_' . Str::random(6) . '.' . $audio->getClientOriginalExtension();
            $audio->move($destDir, $audioName);
            $createData['audio_file'] = 'uploads/soal/' . $audioName;
        }

        SoalButir::create($createData);

        return back()->with('success', 'Butir soal nomor ' . $data['nomor_urut'] . ' berhasil ditambahkan.');
    }

    public function updateButirSoal(Request $request, $id)
    {
        $soal = SoalButir::findOrFail($id);

        $data = $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'jenis_soal' => 'required|in:pg,benar_salah,uraian',
            'konten_soal' => 'required|string',
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'opsi_e' => 'nullable|string',
            'kunci_jawaban' => 'nullable|string',
            'bobot' => 'required|numeric|min:0',
            'rubrik_uraian' => 'nullable|string',
            'gambar_soal' => 'nullable|image|max:5120',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $updateData = [
            'nomor_urut' => $data['nomor_urut'],
            'jenis_soal' => $data['jenis_soal'],
            'konten_soal' => $data['konten_soal'],
            'opsi_a' => $data['opsi_a'] ?? null,
            'opsi_b' => $data['opsi_b'] ?? null,
            'opsi_c' => $data['opsi_c'] ?? null,
            'opsi_d' => $data['opsi_d'] ?? null,
            'opsi_e' => $data['opsi_e'] ?? null,
            'kunci_jawaban' => strtoupper(trim($data['kunci_jawaban'] ?? '')),
            'bobot' => $data['bobot'],
            'rubrik_uraian' => $data['rubrik_uraian'] ?? null,
        ];

        if ($request->hasFile('gambar_soal')) {
            $destDir = public_path('uploads/soal');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $img = $request->file('gambar_soal');
            $imgName = 'img_' . time() . '_' . Str::random(6) . '.' . $img->getClientOriginalExtension();
            $img->move($destDir, $imgName);
            $updateData['gambar_soal'] = 'uploads/soal/' . $imgName;
        }

        if ($request->hasFile('audio_file')) {
            $destDir = public_path('uploads/soal');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $audio = $request->file('audio_file');
            $audioName = 'audio_' . time() . '_' . Str::random(6) . '.' . $audio->getClientOriginalExtension();
            $audio->move($destDir, $audioName);
            $updateData['audio_file'] = 'uploads/soal/' . $audioName;
        }

        $soal->update($updateData);

        return back()->with('success', 'Butir soal nomor ' . $soal->nomor_urut . ' berhasil diperbarui.');
    }

    public function hapusButirSoal($soalId)
    {
        $soal = SoalButir::findOrFail($soalId);
        $no = $soal->nomor_urut;
        $soal->delete();

        return back()->with('success', 'Butir soal nomor ' . $no . ' berhasil dihapus.');
    }

    public function hapusBankSoal($id)
    {
        $bank = BankSoal::findOrFail($id);
        $kode = $bank->kode_bank;
        $bank->delete();

        return back()->with('success', 'Bank Soal ' . $kode . ' berhasil dihapus.');
    }

    public function downloadTemplateSoalXlsx($bankId)
    {
        $bank = BankSoal::with('mataPelajaran')->findOrFail($bankId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Butir Soal');

        $headers = [
            'A1' => 'No Urut',
            'B1' => 'Jenis Soal (PG / BENAR_SALAH / URAIAN)',
            'C1' => 'Konten Pertanyaan (Dapat memakai rumus KaTeX $x^2$)',
            'D1' => 'Pilihan A',
            'E1' => 'Pilihan B',
            'F1' => 'Pilihan C',
            'G1' => 'Pilihan D',
            'H1' => 'Pilihan E',
            'I1' => 'Kunci Jawaban (A-E atau BENAR/SALAH)',
            'J1' => 'Rubrik Kunci Uraian',
            'K1' => 'Bobot Poin (Opsional/Otomatis Sesuai Preset)',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $bobotPg = ($bank->standar_bobot === '25_soal') ? 2.0 : (($bank->standar_bobot === '40_soal') ? 2.0 : 4.0);
        $bobotBs = ($bank->standar_bobot === '25_soal') ? 4.0 : (($bank->standar_bobot === '40_soal') ? 1.0 : 2.0);
        $bobotUraian = ($bank->standar_bobot === '25_soal') ? 10.0 : (($bank->standar_bobot === '40_soal') ? 5.0 : 10.0);

        $samples = [
            [
                1, 'PG',
                'Protokol jaringan yang bertugas menerjemahkan alamat IP menjadi nama domain (seperti smkn1kupangbarat.sch.id) adalah...',
                'DHCP', 'DNS', 'FTP', 'HTTP', 'SMTP',
                'B', '', $bobotPg
            ],
            [
                2, 'PG',
                'Perangkat keras yang berfungsi menghubungkan dua jaringan yang berbeda segmen IP adalah...',
                'Switch Layer 2', 'Hub', 'Router', 'Repeater', 'Modem',
                'C', '', $bobotPg
            ],
            [
                3, 'BENAR_SALAH',
                'Topologi jaringan tipe Star menggunakan satu kabel utama tunggal sebagai tulang punggung tempat semua komputer terhubung.',
                'Benar', 'Salah', '', '', '',
                'SALAH', '', $bobotBs
            ],
            [
                4, 'BENAR_SALAH',
                'Kabel fiber optik mentransmisikan data menggunakan sinyal gelombang cahaya dan kebal terhadap interferensi elektromagnetik.',
                'Benar', 'Salah', '', '', '',
                'BENAR', '', $bobotBs
            ],
            [
                5, 'URAIAN',
                'Jelaskan perbedaan mendasar antara kabel jaringan jenis UTP (Unshielded Twisted Pair) dan STP (Shielded Twisted Pair) serta sebutkan situasi penggunaan keduanya!',
                '', '', '', '', '',
                '', 'Kabel UTP tidak memiliki pelindung aluminium foil sedangkan kabel STP memiliki pelindung pelat foil untuk mencegah gangguan elektromagnetik pada area industri.', $bobotUraian
            ],
        ];

        $rowIdx = 2;
        foreach ($samples as $s) {
            $sheet->setCellValue('A' . $rowIdx, $s[0]);
            $sheet->setCellValue('B' . $rowIdx, $s[1]);
            $sheet->setCellValue('C' . $rowIdx, $s[2]);
            $sheet->setCellValue('D' . $rowIdx, $s[3]);
            $sheet->setCellValue('E' . $rowIdx, $s[4]);
            $sheet->setCellValue('F' . $rowIdx, $s[5]);
            $sheet->setCellValue('G' . $rowIdx, $s[6]);
            $sheet->setCellValue('H' . $rowIdx, $s[7]);
            $sheet->setCellValue('I' . $rowIdx, $s[8]);
            $sheet->setCellValue('J' . $rowIdx, $s[9]);
            $sheet->setCellValue('K' . $rowIdx, $s[10]);
            $rowIdx++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Soal_' . Str::slug($bank->kode_bank) . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importSoalXlsx(Request $request, $bankId)
    {
        $bank = BankSoal::findOrFail($bankId);

        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file_excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $bobotPg = ($bank->standar_bobot === '25_soal') ? 2.0 : (($bank->standar_bobot === '40_soal') ? 2.0 : 4.0);
        $bobotBs = ($bank->standar_bobot === '25_soal') ? 4.0 : (($bank->standar_bobot === '40_soal') ? 1.0 : 2.0);
        $bobotUraian = ($bank->standar_bobot === '25_soal') ? 10.0 : (($bank->standar_bobot === '40_soal') ? 5.0 : 10.0);

        $totalImported = 0;
        $totalSkipped = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                if ($index == 1) continue;

                $konten = trim((string)($row['C'] ?? ''));
                if (empty($konten)) {
                    $totalSkipped++;
                    continue;
                }

                $rawJenis = strtoupper(trim((string)($row['B'] ?? 'PG')));
                if (str_contains($rawJenis, 'BENAR') || str_contains($rawJenis, 'SALAH') || $rawJenis === 'BS' || $rawJenis === 'B/S') {
                    $jenis = 'benar_salah';
                } elseif (str_contains($rawJenis, 'URAIAN') || str_contains($rawJenis, 'ESAI') || str_contains($rawJenis, 'ESSAY')) {
                    $jenis = 'uraian';
                } else {
                    $jenis = 'pg';
                }

                $nomorUrut = (int)($row['A'] ?? 0);
                if ($nomorUrut <= 0) {
                    $nomorUrut = ($bank->butirSoal()->max('nomor_urut') ?? 0) + 1;
                }

                $opsiA = trim((string)($row['D'] ?? ''));
                $opsiB = trim((string)($row['E'] ?? ''));
                $opsiC = trim((string)($row['F'] ?? ''));
                $opsiD = trim((string)($row['G'] ?? ''));
                $opsiE = trim((string)($row['H'] ?? ''));

                if ($jenis === 'benar_salah') {
                    if (empty($opsiA)) $opsiA = 'Benar';
                    if (empty($opsiB)) $opsiB = 'Salah';
                }

                $kunci = strtoupper(trim((string)($row['I'] ?? '')));
                $rubrik = trim((string)($row['J'] ?? ''));

                $bobot = (float)($row['K'] ?? 0);
                if ($bobot <= 0) {
                    if ($jenis === 'benar_salah') $bobot = $bobotBs;
                    elseif ($jenis === 'uraian') $bobot = $bobotUraian;
                    else $bobot = $bobotPg;
                }

                SoalButir::updateOrCreate(
                    [
                        'bank_soal_id' => $bank->id,
                        'nomor_urut' => $nomorUrut,
                    ],
                    [
                        'jenis_soal' => $jenis,
                        'konten_soal' => $konten,
                        'opsi_a' => $opsiA ?: null,
                        'opsi_b' => $opsiB ?: null,
                        'opsi_c' => $opsiC ?: null,
                        'opsi_d' => $opsiD ?: null,
                        'opsi_e' => $opsiE ?: null,
                        'kunci_jawaban' => $kunci ?: null,
                        'rubrik_uraian' => $rubrik ?: null,
                        'bobot' => $bobot,
                    ]
                );

                $totalImported++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal impor soal dari XLSX: ' . $e->getMessage());
        }

        return back()->with('success', "Impor selesai: {$totalImported} butir soal berhasil diimpor ke paket {$bank->kode_bank}. ({$totalSkipped} baris kosong dilewati)");
    }

    public function importSoalDocx(Request $request, $bankId)
    {
        $bank = BankSoal::findOrFail($bankId);

        $request->validate([
            'file_docx' => 'required|file|max:15360',
        ]);

        $file = $request->file('file_docx');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'docx') {
            return back()->with('error', 'Berkas harus memiliki format Microsoft Word .docx');
        }

        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) !== true) {
            return back()->with('error', 'Tidak dapat mengekstrak berkas .docx. Pastikan berkas tidak rusak atau terenkripsi password.');
        }

        $xmlIndex = $zip->locateName('word/document.xml');
        if ($xmlIndex === false) {
            $zip->close();
            return back()->with('error', 'Format struktur internal dokumen Word tidak valid (tidak ditemukan word/document.xml).');
        }

        $xmlData = $zip->getFromIndex($xmlIndex);
        $zip->close();

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($xmlData);
        libxml_clear_errors();

        $paragraphs = $dom->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'p');
        $lines = [];
        foreach ($paragraphs as $p) {
            $lineText = '';
            $runs = $p->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 't');
            foreach ($runs as $r) {
                $lineText .= $r->nodeValue;
            }
            $lineText = trim($lineText);
            if (!empty($lineText)) {
                $lines[] = $lineText;
            }
        }

        if (empty($lines)) {
            return back()->with('error', 'Dokumen Word kosong atau tidak ditemukan baris pertanyaan teks.');
        }

        $bobotPg = ($bank->standar_bobot === '25_soal') ? 2.0 : (($bank->standar_bobot === '40_soal') ? 2.0 : 4.0);
        $bobotBs = ($bank->standar_bobot === '25_soal') ? 4.0 : (($bank->standar_bobot === '40_soal') ? 1.0 : 2.0);
        $bobotUraian = ($bank->standar_bobot === '25_soal') ? 10.0 : (($bank->standar_bobot === '40_soal') ? 5.0 : 10.0);

        $parsedQuestions = [];
        $currentQuestion = null;

        $saveCurrent = function () use (&$parsedQuestions, &$currentQuestion, $bank, $bobotPg, $bobotBs, $bobotUraian) {
            if (!$currentQuestion || empty($currentQuestion['konten'])) return;

            if (empty($currentQuestion['jenis'])) {
                if (!empty($currentQuestion['opsi_c']) || !empty($currentQuestion['opsi_d']) || !empty($currentQuestion['opsi_e'])) {
                    $currentQuestion['jenis'] = 'pg';
                } elseif (!empty($currentQuestion['opsi_a']) && !empty($currentQuestion['opsi_b']) && empty($currentQuestion['opsi_c'])) {
                    $currentQuestion['jenis'] = 'benar_salah';
                } elseif (in_array(strtoupper($currentQuestion['kunci'] ?? ''), ['BENAR', 'SALAH', 'B', 'S'])) {
                    $currentQuestion['jenis'] = 'benar_salah';
                } else {
                    $currentQuestion['jenis'] = 'uraian';
                }
            }

            if ($currentQuestion['jenis'] === 'benar_salah') {
                if (empty($currentQuestion['opsi_a'])) $currentQuestion['opsi_a'] = 'Benar';
                if (empty($currentQuestion['opsi_b'])) $currentQuestion['opsi_b'] = 'Salah';
                if ($currentQuestion['kunci'] === 'B') $currentQuestion['kunci'] = 'BENAR';
                if ($currentQuestion['kunci'] === 'S') $currentQuestion['kunci'] = 'SALAH';
                if (empty($currentQuestion['bobot'])) $currentQuestion['bobot'] = $bobotBs;
            } elseif ($currentQuestion['jenis'] === 'uraian') {
                if (empty($currentQuestion['bobot'])) $currentQuestion['bobot'] = $bobotUraian;
            } else {
                if (empty($currentQuestion['bobot'])) $currentQuestion['bobot'] = $bobotPg;
            }

            $parsedQuestions[] = $currentQuestion;
            $currentQuestion = null;
        };

        foreach ($lines as $line) {
            if (preg_match('/^(?:No\.?\s*)?(\d+)[\.\)\:\-\]]\s*(.*)$/i', $line, $matches)) {
                $saveCurrent();
                $no = (int)$matches[1];
                $konten = trim($matches[2]);

                $jenis = null;
                if (preg_match('/^\[(B\/S|BENAR\/SALAH|BENAR SALAH)\]\s*(.*)$/i', $konten, $jm)) {
                    $jenis = 'benar_salah';
                    $konten = trim($jm[2]);
                } elseif (preg_match('/^\[(URAIAN|ESAI|ESSAY)\]\s*(.*)$/i', $konten, $jm)) {
                    $jenis = 'uraian';
                    $konten = trim($jm[2]);
                } elseif (preg_match('/^\[(PG|PILIHAN GANDA)\]\s*(.*)$/i', $konten, $jm)) {
                    $jenis = 'pg';
                    $konten = trim($jm[2]);
                }

                $currentQuestion = [
                    'nomor' => $no,
                    'konten' => $konten,
                    'jenis' => $jenis,
                    'opsi_a' => '',
                    'opsi_b' => '',
                    'opsi_c' => '',
                    'opsi_d' => '',
                    'opsi_e' => '',
                    'kunci' => '',
                    'rubrik' => '',
                    'bobot' => null,
                ];
                continue;
            }

            if (!$currentQuestion) continue;

            if (preg_match('/^(?:KUNCI(?:\s*JAWABAN)?|JAWABAN|ANSWER|KUNCI)\s*[\:\=]\s*([A-E]|BENAR|SALAH|B|S)(.*)$/i', $line, $km)) {
                $currentQuestion['kunci'] = strtoupper(trim($km[1]));
                continue;
            }

            if (preg_match('/^(?:RUBRIK|KUNCI URAIAN|PEMBAHASAN)\s*[\:\=]\s*(.*)$/i', $line, $rm)) {
                $currentQuestion['rubrik'] = trim($rm[1]);
                $currentQuestion['jenis'] = 'uraian';
                continue;
            }

            if (preg_match('/^[A\x{0041}][\.\)\:\-]\s*(.*)$/u', $line, $om)) {
                $currentQuestion['opsi_a'] = trim($om[1]);
                continue;
            }
            if (preg_match('/^[B\x{0042}][\.\)\:\-]\s*(.*)$/u', $line, $om)) {
                $currentQuestion['opsi_b'] = trim($om[1]);
                continue;
            }
            if (preg_match('/^[C\x{0043}][\.\)\:\-]\s*(.*)$/u', $line, $om)) {
                $currentQuestion['opsi_c'] = trim($om[1]);
                continue;
            }
            if (preg_match('/^[D\x{0044}][\.\)\:\-]\s*(.*)$/u', $line, $om)) {
                $currentQuestion['opsi_d'] = trim($om[1]);
                continue;
            }
            if (preg_match('/^[E\x{0045}][\.\)\:\-]\s*(.*)$/u', $line, $om)) {
                $currentQuestion['opsi_e'] = trim($om[1]);
                continue;
            }

            if (empty($currentQuestion['opsi_a'])) {
                $currentQuestion['konten'] .= "\n" . $line;
            }
        }
        $saveCurrent();

        if (empty($parsedQuestions)) {
            return back()->with('error', 'Tidak berhasil mengekstrak nomor soal dari berkas Word. Pastikan format nomor soal menggunakan penomoran standar seperti "1. ", "2. ", dst.');
        }

        DB::beginTransaction();
        try {
            foreach ($parsedQuestions as $q) {
                SoalButir::updateOrCreate(
                    [
                        'bank_soal_id' => $bank->id,
                        'nomor_urut' => $q['nomor'],
                    ],
                    [
                        'jenis_soal' => $q['jenis'],
                        'konten_soal' => $q['konten'],
                        'opsi_a' => $q['opsi_a'] ?: null,
                        'opsi_b' => $q['opsi_b'] ?: null,
                        'opsi_c' => $q['opsi_c'] ?: null,
                        'opsi_d' => $q['opsi_d'] ?: null,
                        'opsi_e' => $q['opsi_e'] ?: null,
                        'kunci_jawaban' => $q['kunci'] ?: null,
                        'rubrik_uraian' => $q['rubrik'] ?: null,
                        'bobot' => $q['bobot'],
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan butir soal DOCX: ' . $e->getMessage());
        }

        $total = count($parsedQuestions);
        return back()->with('success', "Import Word (.docx) berhasil: {$total} butir soal terdeteksi dan tersimpan ke paket {$bank->kode_bank}!");
    }

    public function exportSoalXlsx($bankId)
    {
        $bank = BankSoal::with(['mataPelajaran', 'butirSoal'])->findOrFail($bankId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Butir Soal');

        $headers = [
            'A1' => 'No Urut',
            'B1' => 'Jenis Soal',
            'C1' => 'Konten Soal',
            'D1' => 'Pilihan A',
            'E1' => 'Pilihan B',
            'F1' => 'Pilihan C',
            'G1' => 'Pilihan D',
            'H1' => 'Pilihan E',
            'I1' => 'Kunci Jawaban',
            'J1' => 'Rubrik Uraian',
            'K1' => 'Bobot Poin',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($bank->butirSoal as $s) {
            $sheet->setCellValue('A' . $rowIdx, $s->nomor_urut);
            $sheet->setCellValue('B' . $rowIdx, strtoupper($s->jenis_soal));
            $sheet->setCellValue('C' . $rowIdx, $s->konten_soal);
            $sheet->setCellValue('D' . $rowIdx, $s->opsi_a);
            $sheet->setCellValue('E' . $rowIdx, $s->opsi_b);
            $sheet->setCellValue('F' . $rowIdx, $s->opsi_c);
            $sheet->setCellValue('G' . $rowIdx, $s->opsi_d);
            $sheet->setCellValue('H' . $rowIdx, $s->opsi_e);
            $sheet->setCellValue('I' . $rowIdx, $s->kunci_jawaban);
            $sheet->setCellValue('J' . $rowIdx, $s->rubrik_uraian);
            $sheet->setCellValue('K' . $rowIdx, $s->bobot);
            $rowIdx++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Ekspor_Soal_' . Str::slug($bank->kode_bank) . '_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pratinjauBankSoal($id)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $bank = BankSoal::with(['mataPelajaran.guru', 'guru', 'butirSoal'])->findOrFail($id);
        $soalList = $bank->butirSoal;

        return view('admin.bank_soal_pratinjau', compact('sekolah', 'bank', 'soalList'));
    }

    // ==========================================
    // JADWAL & TOKEN UJIAN (CRUD + PRESET)
    // ==========================================

    public function jadwal()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $bankList = BankSoal::with('mataPelajaran')->where('status', 'aktif')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $jadwalList = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas', 'peserta'])->latest()->get();

        return view('admin.jadwal', compact('sekolah', 'bankList', 'kelasList', 'jadwalList'));
    }

    public function simpanJadwal(Request $request)
    {
        $data = $request->validate([
            'bank_soal_id' => 'required|exists:bank_soal,id',
            'nama_ujian' => 'required|string|max:150',
            'jenis_ujian' => 'required|in:TKA,ANBK,UTS,UAS,UH,TRYOUT',
            'token_ujian' => 'required|string|max:10',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'durasi_menit' => 'required|integer|min:5|max:360',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $jadwal = JadwalUjian::create([
            'bank_soal_id' => $data['bank_soal_id'],
            'nama_ujian' => $data['nama_ujian'],
            'jenis_ujian' => $data['jenis_ujian'],
            'token_ujian' => strtoupper(trim($data['token_ujian'])),
            'waktu_mulai' => $data['waktu_mulai'],
            'waktu_selesai' => $data['waktu_selesai'],
            'durasi_menit' => $data['durasi_menit'],
            'acak_soal' => $request->boolean('acak_soal', true),
            'acak_opsi' => $request->boolean('acak_opsi', true),
            'tampilkan_nilai' => $request->boolean('tampilkan_nilai', true),
            'status' => 'aktif',
        ]);

        $jadwal->kelas()->sync($data['kelas_ids']);

        return back()->with('success', 'Jadwal Ujian ' . $jadwal->nama_ujian . ' berhasil diaktifkan dengan token: ' . $jadwal->token_ujian);
    }

    public function updateJadwal(Request $request, $id)
    {
        $jadwal = JadwalUjian::findOrFail($id);

        $data = $request->validate([
            'bank_soal_id' => 'required|exists:bank_soal,id',
            'nama_ujian' => 'required|string|max:150',
            'jenis_ujian' => 'required|in:TKA,ANBK,UTS,UAS,UH,TRYOUT',
            'token_ujian' => 'required|string|max:10',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'durasi_menit' => 'required|integer|min:5|max:360',
            'status' => 'required|in:aktif,draft,selesai',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $jadwal->update([
            'bank_soal_id' => $data['bank_soal_id'],
            'nama_ujian' => $data['nama_ujian'],
            'jenis_ujian' => $data['jenis_ujian'],
            'token_ujian' => strtoupper(trim($data['token_ujian'])),
            'waktu_mulai' => $data['waktu_mulai'],
            'waktu_selesai' => $data['waktu_selesai'],
            'durasi_menit' => $data['durasi_menit'],
            'acak_soal' => $request->boolean('acak_soal'),
            'acak_opsi' => $request->boolean('acak_opsi'),
            'tampilkan_nilai' => $request->boolean('tampilkan_nilai'),
            'status' => $data['status'],
        ]);

        $jadwal->kelas()->sync($data['kelas_ids']);

        return back()->with('success', 'Jadwal Ujian ' . $jadwal->nama_ujian . ' berhasil diperbarui.');
    }

    public function hapusJadwal($id)
    {
        $jadwal = JadwalUjian::findOrFail($id);
        $nama = $jadwal->nama_ujian;
        $jadwal->delete();

        return back()->with('success', 'Jadwal Ujian ' . $nama . ' berhasil dihapus.');
    }

    public function generateToken($jadwalId)
    {
        $jadwal = JadwalUjian::findOrFail($jadwalId);
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $newToken = substr(str_shuffle($characters), 0, 5);

        $jadwal->update(['token_ujian' => $newToken]);

        return back()->with('success', 'Token asesmen berhasil diperbarui menjadi: ' . $newToken);
    }

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
                $jwb = UjianJawaban::where('ujian_peserta_id', $p->id)
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

    public function resetAllLogins()
    {
        Siswa::whereIn('status_login', ['online', 'sedang_tes'])->update([
            'status_login' => 'offline',
            'ip_address' => null,
        ]);

        UjianPeserta::where('status', 'berlangsung')->update(['ip_pengerjaan' => null]);

        return back()->with('success', 'Seluruh sesi siswa yang terkunci berhasil direset.');
    }

    // ==========================================
    // KOREKSI URAIAN & REKAP NILAI (FASE 6)
    // ==========================================

    public function rekapNilai($jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran'])->findOrFail($jadwalId);
        
        $pesertaList = UjianPeserta::with(['siswa.kelas'])
            ->where('jadwal_ujian_id', $jadwalId)
            ->orderBy('total_nilai', 'desc')
            ->get();

        return view('admin.rekap_nilai', compact('sekolah', 'jadwal', 'pesertaList'));
    }

    public function exportRekapNilaiXlsx($jadwalId)
    {
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran'])->findOrFail($jadwalId);
        $pesertaList = UjianPeserta::with(['siswa.kelas'])
            ->where('jadwal_ujian_id', $jadwalId)
            ->orderBy('total_nilai', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Nilai Ujian');

        $headers = ['A1' => 'Peringkat', 'B1' => 'Nomor Peserta', 'C1' => 'NISN', 'D1' => 'Nama Siswa', 'E1' => 'Kelas / Rombel', 'F1' => 'Benar', 'G1' => 'Salah', 'H1' => 'Kosong', 'I1' => 'Skor PG/BS', 'J1' => 'Skor Uraian', 'K1' => 'Nilai Akhir', 'L1' => 'Keterangan KKM'];
        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($pesertaList as $idx => $p) {
            $kkm = $jadwal->bankSoal->kkm ?? 75;
            $lulus = $p->total_nilai >= $kkm ? 'TUNTAS' : 'BELUM TUNTAS';

            $sheet->setCellValue('A' . $rowIdx, $idx + 1);
            $sheet->setCellValue('B' . $rowIdx, $p->siswa->nomor_peserta ?? '-');
            $sheet->setCellValueExplicit('C' . $rowIdx, $p->siswa->nisn ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $rowIdx, $p->siswa->nama_lengkap ?? '-');
            $sheet->setCellValue('E' . $rowIdx, $p->siswa->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('F' . $rowIdx, $p->jawaban_benar);
            $sheet->setCellValue('G' . $rowIdx, $p->jawaban_salah);
            $sheet->setCellValue('H' . $rowIdx, $p->jawaban_kosong);
            $sheet->setCellValue('I' . $rowIdx, $p->nilai_pg);
            $sheet->setCellValue('J' . $rowIdx, $p->nilai_uraian);
            $sheet->setCellValue('K' . $rowIdx, $p->total_nilai);
            $sheet->setCellValue('L' . $rowIdx, $lulus);
            $rowIdx++;
        }

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Rekap_Nilai_' . Str::slug($jadwal->nama_ujian) . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    // ==========================================
    // DOKUMEN CETAK RESMI DINAS A4 (FASE 7)
    // ==========================================

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

    public function formBeritaAcara($jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas.siswa'])->findOrFail($jadwalId);
        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)->first();
        $guruList = User::whereIn('role', ['guru', 'proktor', 'admin'])->orderBy('name')->get();
        $totalPeserta = Siswa::whereIn('kelas_id', $jadwal->kelas->pluck('id'))->count();

        return view('admin.berita_acara_form', compact('sekolah', 'jadwal', 'beritaAcara', 'guruList', 'totalPeserta'));
    }

    public function simpanBeritaAcara(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::findOrFail($jadwalId);

        $data = $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'hari_tanggal' => 'required|string|max:100',
            'waktu_mulai' => 'nullable|string|max:30',
            'waktu_selesai' => 'nullable|string|max:30',
            'ruangan' => 'nullable|string|max:50',
            'sesi' => 'nullable|string|max:30',
            'proktor_id' => 'nullable|exists:users,id',
            'nama_proktor' => 'required|string|max:150',
            'nip_proktor' => 'nullable|string|max:40',
            'pengawas_id' => 'nullable|exists:users,id',
            'nama_pengawas' => 'required|string|max:150',
            'nip_pengawas' => 'nullable|string|max:40',
            'jumlah_peserta_seharusnya' => 'required|integer|min:0',
            'jumlah_peserta_hadir' => 'required|integer|min:0',
            'jumlah_peserta_tidak_hadir' => 'nullable|integer|min:0',
            'daftar_tidak_hadir' => 'nullable|string',
            'catatan_pelaksanaan' => 'nullable|string',
        ]);

        $data['jadwal_ujian_id'] = $jadwalId;

        BeritaAcara::updateOrCreate(
            ['jadwal_ujian_id' => $jadwalId],
            $data
        );

        return redirect()->route('admin.berita_acara.form', $jadwalId)->with('success', 'Berita Acara Ujian berhasil disimpan. Anda dapat langsung mencetak lembar dokumen.');
    }

    public function cetakBeritaAcara($jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'peserta.siswa.kelas', 'kelas.siswa'])->findOrFail($jadwalId);
        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)->first();

        // Jika belum pernah disimpan, buat objek default agar lembar cetak langsung valid
        if (!$beritaAcara) {
            $totalPeserta = Siswa::whereIn('kelas_id', $jadwal->kelas->pluck('id'))->count();
            $proktor = User::where('role', 'proktor')->first() ?? User::where('username', '199002192020121003')->first();
            $pengawas = User::where('role', 'guru')->first() ?? User::where('username', '198911042024212044')->first();

            $beritaAcara = new BeritaAcara([
                'nomor_surat' => '421.5/024/SMKN1.KB/' . date('Y'),
                'hari_tanggal' => Carbon::now()->isoFormat('dddd, D MMMM Y'),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '09:30',
                'ruangan' => 'Lab Komputer 1',
                'sesi' => 'Sesi 1',
                'nama_proktor' => $proktor ? $proktor->name : 'Izak Robinson Koroh, S.Pd',
                'nip_proktor' => $proktor ? ($proktor->nip ?: $proktor->username) : '199002192020121003',
                'nama_pengawas' => $pengawas ? $pengawas->name : 'Potifera Buan, S.Pd',
                'nip_pengawas' => $pengawas ? ($pengawas->nip ?: $pengawas->username) : '198911042024212044',
                'jumlah_peserta_seharusnya' => $totalPeserta ?: 89,
                'jumlah_peserta_hadir' => $totalPeserta ?: 89,
                'jumlah_peserta_tidak_hadir' => 0,
                'daftar_tidak_hadir' => '-',
                'catatan_pelaksanaan' => 'Pelaksanaan asesmen CBT berjalan tertib, aman, dan lancar sesuai standar teknis Pusmendik/ANBK.',
            ]);
        }

        return view('admin.cetak_berita_acara', compact('sekolah', 'jadwal', 'beritaAcara'));
    }

    public function cetakDaftarHadir($jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas.siswa'])->findOrFail($jadwalId);
        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)->first();

        // Ambil seluruh siswa dari rombel kelas yang ditugaskan
        $kelasIds = $jadwal->kelas->pluck('id');
        $siswaList = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.cetak_daftar_hadir', compact('sekolah', 'jadwal', 'siswaList', 'beritaAcara'));
    }

    public function petugasUjian(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $petugasList = PetugasUjian::with(['user', 'jadwal'])->orderBy('jenis_petugas')->get();
        $guruList = User::whereIn('role', ['guru', 'proktor', 'admin'])->orderBy('name')->get();
        $jadwalList = JadwalUjian::all();

        return view('admin.petugas_ujian', compact('sekolah', 'petugasList', 'guruList', 'jadwalList'));
    }

    public function simpanPetugasUjian(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_ujian_id' => 'nullable|exists:jadwal_ujian,id',
            'jenis_petugas' => 'required|in:proktor,pengawas',
            'ruangan' => 'required|string|max:50',
            'sesi' => 'required|string|max:30',
            'nomor_sk' => 'nullable|string|max:100',
        ]);

        PetugasUjian::create($data);

        // Jika ditugaskan sebagai proktor, pastikan role user terupdate
        if ($data['jenis_petugas'] === 'proktor') {
            User::where('id', $data['user_id'])->update(['role' => 'proktor']);
        }

        return back()->with('success', 'Petugas Ujian (' . ucfirst($data['jenis_petugas']) . ') berhasil ditambahkan.');
    }

    public function hapusPetugasUjian($id)
    {
        $petugas = PetugasUjian::findOrFail($id);
        $petugas->delete();

        return back()->with('success', 'Penugasan petugas berhasil dihapus.');
    }

    public function cetakKartuPetugas(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $query = PetugasUjian::with(['user', 'jadwal']);

        if ($request->filled('petugas_id')) {
            $query->where('id', $request->petugas_id);
        }

        $daftarPetugas = $query->get();

        // Jika belum ada penugasan manual, buat preview dari proktor dan guru default
        if ($daftarPetugas->isEmpty()) {
            $proktor = User::where('role', 'proktor')->first() ?? User::where('username', '199002192020121003')->first();
            $pengawas = User::where('role', 'guru')->first() ?? User::where('username', '198911042024212044')->first();

            $daftarPetugas = collect();
            if ($proktor) {
                $p1 = new PetugasUjian([
                    'user_id' => $proktor->id,
                    'jenis_petugas' => 'proktor',
                    'ruangan' => 'Lab Komputer 1',
                    'sesi' => 'Sesi 1',
                    'nomor_sk' => '421.5/012/SMKN1.KB/2026',
                ]);
                $p1->setRelation('user', $proktor);
                $daftarPetugas->push($p1);
            }
            if ($pengawas) {
                $p2 = new PetugasUjian([
                    'user_id' => $pengawas->id,
                    'jenis_petugas' => 'pengawas',
                    'ruangan' => 'Lab Komputer 1',
                    'sesi' => 'Sesi 1',
                    'nomor_sk' => '421.5/012/SMKN1.KB/2026',
                ]);
                $p2->setRelation('user', $pengawas);
                $daftarPetugas->push($p2);
            }
        }

        return view('admin.cetak_kartu_petugas', compact('sekolah', 'daftarPetugas'));
    }

    public function pengaturan()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        return view('admin.pengaturan', compact('sekolah'));
    }

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
            'dinas_pendidikan' => 'nullable|string',
            'kepala_sekolah' => 'required|string',
            'nip_kepala_sekolah' => 'nullable|string',
            'tahun_ajaran_aktif' => 'required|string',
            'semester_aktif' => 'required|string',
            'ttd_kepala_sekolah' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:4096',
            'logo_pemprov' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:4096',
            'logo_sekolah' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:4096',
        ]);

        if ($request->hasFile('ttd_kepala_sekolah')) {
            $file = $request->file('ttd_kepala_sekolah');
            $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['ttd_kepala_sekolah'] = 'images/' . $filename;
        }

        if ($request->hasFile('logo_pemprov')) {
            $file = $request->file('logo_pemprov');
            $filename = 'logo_pemprov_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['logo_pemprov'] = 'images/' . $filename;
        }

        if ($request->hasFile('logo_sekolah')) {
            $file = $request->file('logo_sekolah');
            $filename = 'logo_sekolah_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['logo_sekolah'] = 'images/' . $filename;
        }

        $sekolah->fill($data);
        $sekolah->save();

        return back()->with('success', 'Pengaturan sekolah, logo kop dinas, dan tanda tangan kepala sekolah berhasil diperbarui.');
    }
}
