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
    // DATA MATA PELAJARAN (CRUD)
    // ==========================================

    public function mapel()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $mapelList = MataPelajaran::withCount('bankSoal')->orderBy('nama_mapel')->get();
        return view('admin.mapel', compact('sekolah', 'mapelList'));
    }

    public function simpanMapel(Request $request)
    {
        $data = $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
        ]);

        MataPelajaran::create($data);
        return back()->with('success', 'Mata Pelajaran ' . $data['nama_mapel'] . ' berhasil ditambahkan.');
    }

    public function updateMapel(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $data = $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajaran,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:100',
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

    public function bankSoal()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $bankList = BankSoal::with(['mataPelajaran', 'butirSoal'])->latest()->get();

        return view('admin.bank_soal', compact('sekolah', 'mapelList', 'bankList'));
    }

    public function simpanBankSoal(Request $request)
    {
        $data = $request->validate([
            'kode_bank' => 'required|string|max:50|unique:bank_soal,kode_bank',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tingkat' => 'required|in:Semua,10,11,12',
            'standar_preset' => 'required|in:25,40,kustom',
            'kkm' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung bobot otomatis sesuai Roadmap:
        // Standar 25 nomor: Benar/Salah 5 x 4 = 20; PG 15 x 2 = 30; Uraian 5 x 10 = 50. Total = 100
        // Standar 40 nomor: Benar/Salah 10 x 1 = 10; PG 20 x 2 = 40; Uraian 10 x 5 = 50. Total = 100
        if ($data['standar_preset'] === '25') {
            $jumlahPg = 20; // 15 PG + 5 B/S
            $jumlahUraian = 5;
            $bobotPg = 50.0;
            $bobotUraian = 50.0;
        } elseif ($data['standar_preset'] === '40') {
            $jumlahPg = 30; // 20 PG + 10 B/S
            $jumlahUraian = 10;
            $bobotPg = 50.0;
            $bobotUraian = 50.0;
        } else {
            $jumlahPg = 25;
            $jumlahUraian = 0;
            $bobotPg = 100.0;
            $bobotUraian = 0.0;
        }

        $bank = BankSoal::create([
            'kode_bank' => $data['kode_bank'],
            'mata_pelajaran_id' => $data['mata_pelajaran_id'],
            'guru_id' => auth()->id(),
            'tingkat' => $data['tingkat'],
            'jumlah_soal_pg' => $jumlahPg,
            'jumlah_soal_uraian' => $jumlahUraian,
            'bobot_pg' => $bobotPg,
            'bobot_uraian' => $bobotUraian,
            'kkm' => $data['kkm'],
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.bank_soal.detail', $bank->id)->with('success', 'Paket Bank Soal ' . $bank->kode_bank . ' berhasil dibuat. Silahkan kelola butir soal.');
    }

    public function detailBankSoal($id)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $bank = BankSoal::with(['mataPelajaran', 'butirSoal'])->findOrFail($id);

        return view('admin.bank_soal_detail', compact('sekolah', 'bank'));
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
        ]);

        SoalButir::create([
            'bank_soal_id' => $bank->id,
            'nomor_urut' => $data['nomor_urut'],
            'jenis_soal' => $data['jenis_soal'],
            'konten_soal' => $data['konten_soal'],
            'opsi_a' => $data['opsi_a'],
            'opsi_b' => $data['opsi_b'],
            'opsi_c' => $data['opsi_c'],
            'opsi_d' => $data['opsi_d'],
            'opsi_e' => $data['opsi_e'],
            'kunci_jawaban' => strtoupper(trim($data['kunci_jawaban'] ?? '')),
            'bobot' => $data['bobot'],
        ]);

        return back()->with('success', 'Butir soal nomor ' . $data['nomor_urut'] . ' berhasil ditambahkan.');
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
