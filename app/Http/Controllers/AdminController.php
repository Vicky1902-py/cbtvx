<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
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

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoFile = $request->file('foto');
            $fotoName = $data['nisn'] . '.' . $fotoFile->getClientOriginalExtension();
            $targetDir = public_path('storage/foto_siswa');
            if (!\Illuminate\Support\Facades\File::exists($targetDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true);
            }
            $fotoFile->move($targetDir, $fotoName);
            $fotoPath = 'storage/foto_siswa/' . $fotoName;
        }

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
            'foto' => $fotoPath,
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $updateData = [
            'nisn' => $data['nisn'],
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'kelas_id' => $data['kelas_id'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'sesi_ujian' => $data['sesi_ujian'],
            'ruangan_ujian' => $data['ruangan_ujian'],
            'nomor_peserta' => $data['nomor_peserta'],
        ];

        if ($request->hasFile('foto')) {
            $fotoFile = $request->file('foto');
            $fotoName = $data['nisn'] . '.' . $fotoFile->getClientOriginalExtension();
            $targetDir = public_path('storage/foto_siswa');
            if (!\Illuminate\Support\Facades\File::exists($targetDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true);
            }
            $fotoFile->move($targetDir, $fotoName);
            $updateData['foto'] = 'storage/foto_siswa/' . $fotoName;
        }

        $siswa->update($updateData);

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

    public function uploadFotoZip(Request $request)
    {
        $request->validate([
            'file_zip' => 'required|file|mimes:zip|max:51200',
        ]);

        $zipFile = $request->file('file_zip');
        $zip = new \ZipArchive();

        if ($zip->open($zipFile->getRealPath()) !== true) {
            return back()->with('error', 'Gagal membuka berkas ZIP. Pastikan berkas adalah arsip .zip yang valid.');
        }

        $targetDir = public_path('storage/foto_siswa');
        if (!\Illuminate\Support\Facades\File::exists($targetDir)) {
            \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true);
        }

        $totalExtracted = 0;
        $matchedCount = 0;
        $unmatchedList = [];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            if (str_ends_with($filename, '/') || str_contains($filename, '__MACOSX')) {
                continue;
            }

            $baseName = basename($filename);
            $extension = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions)) {
                continue;
            }

            $nisn = pathinfo($baseName, PATHINFO_FILENAME);
            $cleanFileName = $nisn . '.' . $extension;
            $destinationPath = $targetDir . DIRECTORY_SEPARATOR . $cleanFileName;

            $stream = $zip->getStream($filename);
            if ($stream) {
                file_put_contents($destinationPath, stream_get_contents($stream));
                fclose($stream);
                $totalExtracted++;

                $siswa = Siswa::where('nisn', $nisn)->first();
                if ($siswa) {
                    $siswa->update([
                        'foto' => 'storage/foto_siswa/' . $cleanFileName,
                    ]);
                    $matchedCount++;
                } else {
                    $unmatchedList[] = $nisn;
                }
            }
        }

        $zip->close();

        $msg = "Proses upload foto massal selesai! Berhasil mengekstrak {$totalExtracted} foto, dan {$matchedCount} foto berhasil dicocokkan dengan data siswa.";
        if (count($unmatchedList) > 0) {
            $sampleUnmatched = array_slice($unmatchedList, 0, 5);
            $msg .= " (Catatan: Ada " . count($unmatchedList) . " foto yang NISN-nya tidak ditemukan di data siswa: " . implode(', ', $sampleUnmatched) . (count($unmatchedList) > 5 ? '...' : '') . ")";
        }

        return redirect()->route('admin.siswa')->with('success', $msg);
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
            'kode_mapel' => 'required|string|max:50|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:150',
            'kelompok' => 'required|string|max:100',
            'jurusan' => 'required|string|max:150',
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
            'kode_mapel' => 'required|string|max:50|unique:mata_pelajaran,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:150',
            'kelompok' => 'required|string|max:100',
            'jurusan' => 'required|string|max:150',
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
        $guruList = User::whereIn('role', ['admin', 'guru'])->orderBy('name')->get();

        $query = BankSoal::with(['mataPelajaran.guru', 'guru', 'butirSoal']);

        if ($request->filled('mapel_id')) {
            $query->where('mata_pelajaran_id', $request->mapel_id);
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $bankList = $query->latest()->get();

        return view('admin.bank_soal', compact('sekolah', 'mapelList', 'bankList', 'guruList'));
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

    public function updateBankSoal(Request $request, $id)
    {
        $bank = BankSoal::findOrFail($id);

        $data = $request->validate([
            'kode_bank' => 'required|string|max:50|unique:bank_soal,kode_bank,' . $bank->id,
            'nama_bank' => 'nullable|string|max:150',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'nullable|exists:users,id',
            'tingkat' => 'required|in:Semua,10,11,12',
            'standar_preset' => 'required|in:25,40,kustom',
            'kkm' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:aktif,draft,arsip',
        ]);

        $mapel = MataPelajaran::findOrFail($data['mata_pelajaran_id']);

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
            $jumlahPg = $bank->jumlah_soal_pg;
            $bobotPg = $bank->bobot_pg;
            $jumlahBs = $bank->jumlah_soal_bs ?? 0;
            $bobotBs = $bank->bobot_bs ?? 0;
            $jumlahUraian = $bank->jumlah_soal_uraian;
            $bobotUraian = $bank->bobot_uraian;
        }

        $namaBank = $data['nama_bank'] ?: ($mapel->nama_mapel . ' (' . $mapel->jurusan . ')');

        $bank->update([
            'kode_bank' => $data['kode_bank'],
            'nama_bank' => $namaBank,
            'mata_pelajaran_id' => $mapel->id,
            'guru_id' => $data['guru_id'] ?? ($mapel->guru_id ?? $bank->guru_id),
            'tingkat' => $data['tingkat'],
            'standar_bobot' => $standarBobot,
            'jumlah_soal_pg' => $jumlahPg,
            'jumlah_soal_bs' => $jumlahBs,
            'jumlah_soal_uraian' => $jumlahUraian,
            'bobot_pg' => $bobotPg,
            'bobot_bs' => $bobotBs,
            'bobot_uraian' => $bobotUraian,
            'kkm' => $data['kkm'],
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Paket Bank Soal ' . $bank->kode_bank . ' berhasil diperbarui.');
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

        $mapelList = MataPelajaran::with('guru')->orderBy('nama_mapel')->get();
        $guruList = User::whereIn('role', ['admin', 'guru'])->orderBy('name')->get();

        return view('admin.bank_soal_detail', compact(
            'sekolah', 'bank', 'soalList', 'filterJenis',
            'countPg', 'countBs', 'countUraian',
            'bobotPgCurrent', 'bobotBsCurrent', 'bobotUraianCurrent', 'totalBobotCurrent',
            'nextNomor', 'saranBobotPg', 'saranBobotBs', 'saranBobotUraian',
            'mapelList', 'guruList'
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

    public function downloadTemplateSoalXlsx(Request $request, $bankId)
    {
        $bank = BankSoal::with('mataPelajaran')->findOrFail($bankId);

        $preset = $request->query('preset');
        if (!in_array($preset, ['preset_25', 'preset_40', '25_soal', '40_soal'])) {
            $preset = ($bank->standar_bobot === '40_soal') ? 'preset_40' : 'preset_25';
        }

        $is40 = ($preset === 'preset_40' || $preset === '40_soal');
        $totalRows = $is40 ? 40 : 25;
        $bobotBs = $is40 ? 1.0 : 4.0;
        $bobotPg = 2.0;
        $bobotUraian = $is40 ? 5.0 : 10.0;
        $presetTitle = $is40 ? 'PRESET STANDAR 40 NOMOR (10 B/S x 1 + 20 PG x 2 + 10 URAIAN x 5 = 100 POIN)' : 'PRESET STANDAR 25 NOMOR (5 B/S x 4 + 15 PG x 2 + 5 URAIAN x 10 = 100 POIN)';

        $samples = $is40 ? $this->getSampleQuestions40($bobotBs, $bobotPg, $bobotUraian) : $this->getSampleQuestions25($bobotBs, $bobotPg, $bobotUraian);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($is40 ? 'Template 40 Soal' : 'Template 25 Soal');

        // 1. BANNER HEADER RESMI SEKOLAH
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'SMK NEGERI 1 KUPANG BARAT - SISTEM CBT ASESMEN TERSTANDAR');
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F2B48');
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:K1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // 2. IDENTITAS PAKET SOAL
        $sheet->mergeCells('A2:K2');
        $mapelNama = $bank->mataPelajaran ? $bank->mataPelajaran->nama_mapel : 'Semua Mapel';
        $sheet->setCellValue('A2', 'PAKET: ' . strtoupper($bank->kode_bank) . ' | MAPEL: ' . strtoupper($mapelNama) . ' | ' . $presetTitle);
        $sheet->getStyle('A2:K2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E293B');
        $sheet->getStyle('A2:K2')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A2:K2')->getFont()->setSize(9.5)->setBold(true);
        $sheet->getStyle('A2:K2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(22);

        // 3. PETUNJUK PENGISIAN
        $sheet->mergeCells('A3:K3');
        $sheet->setCellValue('A3', 'PETUNJUK: Isi baris soal di bawah. Jangan ubah urutan kolom A s/d K. Kolom B (Tipe Soal) pilih PG, BENAR_SALAH, atau URAIAN. Rumus KaTeX didukung (contoh $x^2$ atau $\\frac{a}{b}$).');
        $sheet->getStyle('A3:K3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
        $sheet->getStyle('A3:K3')->getFont()->getColor()->setARGB('FF334155');
        $sheet->getStyle('A3:K3')->getFont()->setSize(8.5)->setItalic(true);
        $sheet->getStyle('A3:K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // 4. PEMISAH KOSONG
        $sheet->getRowDimension(4)->setRowHeight(8);

        // 5. HEADER TABEL UTAMA (BARIS 5)
        $headers = [
            'A5' => 'No',
            'B5' => 'Tipe Soal (PG/B_S/URAIAN)',
            'C5' => 'Pertanyaan Soal (Mendukung KaTeX $x^2$)',
            'D5' => 'Pilihan A',
            'E5' => 'Pilihan B',
            'F5' => 'Pilihan C',
            'G5' => 'Pilihan D',
            'H5' => 'Pilihan E',
            'I5' => 'Kunci Jawaban',
            'J5' => 'Rubrik Jawaban Uraian',
            'K5' => 'Bobot Poin',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sheet->getStyle('A5:K5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0284C7');
        $sheet->getStyle('A5:K5')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A5:K5')->getFont()->setBold(true)->setSize(9.5);
        $sheet->getStyle('A5:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(5)->setRowHeight(26);

        // LEBAR KOLOM PROPORSIONAL
        $widths = [
            'A' => 6,
            'B' => 18,
            'C' => 45,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 15,
            'J' => 35,
            'K' => 12,
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // 6. TULISKAN BARIS DATA BUTIR SOAL SECARA PRESISI
        $rowIdx = 6;
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

            // Perataan sel
            $sheet->getStyle('A' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('K' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            // Zebra striping baris genap
            if ($rowIdx % 2 === 0) {
                $sheet->getStyle('A' . $rowIdx . ':K' . $rowIdx)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }

            // Dropdown validasi tipe soal
            $validationJenis = $sheet->getCell('B' . $rowIdx)->getDataValidation();
            $validationJenis->setType(DataValidation::TYPE_LIST);
            $validationJenis->setAllowBlank(false);
            $validationJenis->setShowDropDown(true);
            $validationJenis->setFormula1('"PG,BENAR_SALAH,URAIAN"');

            // Dropdown validasi kunci jawaban
            $validationKunci = $sheet->getCell('I' . $rowIdx)->getDataValidation();
            $validationKunci->setType(DataValidation::TYPE_LIST);
            $validationKunci->setAllowBlank(true);
            $validationKunci->setShowDropDown(true);
            $validationKunci->setFormula1('"A,B,C,D,E,BENAR,SALAH"');

            $sheet->getRowDimension($rowIdx)->setRowHeight(34);
            $rowIdx++;
        }

        $lastRow = $rowIdx - 1;

        // Wrap text pada kolom pertanyaan dan opsi
        $sheet->getStyle('C6:H' . $lastRow)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('J6:J' . $lastRow)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);

        // Border tipis pada seluruh tabel
        $sheet->getStyle('A5:K' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFCBD5E1');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Soal_' . Str::slug($bank->kode_bank) . '_' . ($is40 ? 'Preset_40_Nomor' : 'Preset_25_Nomor') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Bank Soal Sampel Preset 25 Nomor (100 Poin Penuh)
     */
    public function getSampleQuestions25($bobotBs, $bobotPg, $bobotUraian): array
    {
        return [
            // 5 BENAR / SALAH (Bobot masing-masing 4.0 pt = 20 pt)
            [1, 'BENAR_SALAH', 'Topologi jaringan tipe Star menggunakan satu perangkat konsentrator (seperti Switch atau Hub) sebagai pusat distribusi seluruh transmisi data antar-komputer client.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [2, 'BENAR_SALAH', 'Alamat IP versi 4 (IPv4) memiliki panjang total 128 bit yang terbagi ke dalam 8 blok heksadesimal.', 'Benar', 'Salah', '', '', '', 'SALAH', 'IPv4 memiliki panjang 32 bit yang terbagi dalam 4 oktet desimal, sedangkan 128 bit adalah panjang IPv6.', $bobotBs],
            [3, 'BENAR_SALAH', 'Pada instalasi penerangan listrik rumah tangga, saklar pemutus arus selalu wajib dipasang pada penghantar fasa (bertegangan), bukan pada penghantar netral.', 'Benar', 'Salah', '', '', '', 'BENAR', 'Memutus fasa menjamin keamanan dari sengatan listrik saat lampu atau fiting diperbaiki.', $bobotBs],
            [4, 'BENAR_SALAH', 'Hukum Ohm menyatakan bahwa kuat arus listrik yang mengalir berbanding terbalik dengan beda potensial dan berbanding lurus dengan nilai hambatan ($I = \\frac{R}{V}$).', 'Benar', 'Salah', '', '', '', 'SALAH', 'Rumus hukum Ohm yang benar adalah $I = \\frac{V}{R}$, di mana arus berbanding lurus dengan tegangan V.', $bobotBs],
            [5, 'BENAR_SALAH', 'Proses pendinginan cepat (quenching) pada perlakuan panas baja karbon bertujuan untuk membentuk fasa struktur mikro Martensit yang memiliki tingkat kekerasan tinggi.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],

            // 15 PILIHAN GANDA (Bobot masing-masing 2.0 pt = 30 pt)
            [6, 'PG', 'Protokol jaringan yang bertugas memetakan alamat IP logis menjadi alamat fisik perangkat keras (MAC Address) pada jaringan lokal adalah...', 'ARP (Address Resolution Protocol)', 'DNS (Domain Name System)', 'DHCP Server', 'ICMP', 'RARP', 'A', '', $bobotPg],
            [7, 'PG', 'Dalam arsitektur referensi 7 OSI Layer, perangkat router beroperasi pada lapisan...', 'Physical Layer (Layer 1)', 'Data Link Layer (Layer 2)', 'Network Layer (Layer 3)', 'Transport Layer (Layer 4)', 'Application Layer (Layer 7)', 'C', '', $bobotPg],
            [8, 'PG', 'Perhitungan daya aktif ($P$) pada sistem kelistrikan arus bolak-balik (AC) satu fasa dengan faktor daya $\\cos(\\phi)$ dirumuskan...', '$P = V \\times I$', '$P = V \\times I \\times \\cos(\\phi)$', '$P = V^2 \\times R$', '$P = \\frac{V \\times I}{\\cos(\\phi)}$', '$P = \\sqrt{3} \\times V \\times I$', 'B', '', $bobotPg],
            [9, 'PG', 'Komponen pada sistem pengapian konvensional kendaraan bermotor yang berfungsi menyerap percikan bunga api pada kontak platina adalah...', 'Busi pemantik', 'Kondensor (Kapasitor)', 'Koil pengapian', 'Distributor rotor', 'Alternator', 'B', '', $bobotPg],
            [10, 'PG', 'Pada gambar teknik konstruksi bangunan (DPIB), garis strip-titik-strip tipis ($-\\cdot-\\cdot-$) standar ISO digunakan untuk menunjukkan...', 'Garis sumbu atau as simetri', 'Garis kontur dinding tampak', 'Garis potongan bidang', 'Garis ukuran dan dimensi', 'Garis arsiran material', 'A', '', $bobotPg],
            [11, 'PG', 'Sebuah resistor karbon memiliki 4 gelang warna: Cokelat, Hitam, Merah, Emas. Nilai resistansi nominal dan nilai toleransinya adalah...', '$100\\,\\Omega \\pm 5\\%$', '$1.000\\,\\Omega \\pm 10\\%$', '$10.000\\,\\Omega \\pm 5\\%$', '$1.000\\,\\Omega \\pm 5\\%$ ($1\\,\\text{k}\\Omega$)', '$10\\,\\Omega \\pm 5\\%$', 'D', '', $bobotPg],
            [12, 'PG', 'Skala gambar $1 : 50$ pada gambar denah bangunan bermakna bahwa setiap jarak $1\\text{ cm}$ pada kertas gambar mewakili ukuran sesungguhnya di lapangan sebesar...', '$5\\text{ cm}$', '$50\\text{ cm}$ ($0,5\\text{ meter}$)', '$500\\text{ cm}$ ($5\\text{ meter}$)', '$50\\text{ meter}$', '$5\\text{ meter}$', 'B', '', $bobotPg],
            [13, 'PG', 'Rumus luas penampang ($A$) pondasi tiang pancang berbentuk lingkaran dengan diameter $d$ dirumuskan secara matematis dengan...', '$A = \\pi \\times d$', '$A = 2 \\times \\pi \\times d$', '$A = \\frac{\\pi \\times d^2}{4}$', '$A = \\pi \\times d^2$', '$A = \\frac{\\pi \\times d}{2}$', 'C', '', $bobotPg],
            [14, 'PG', 'Alat ukur presisi otomotif yang paling tepat digunakan untuk mengukur keolengan (run-out) piringan rem cakram roda mobil adalah...', 'Jangka Sorong (Vernier Caliper)', 'Mikrometer Luar', 'Dial Indicator (Dial Gauge)', 'Silinder Bore Gauge', 'Feeler Gauge', 'C', '', $bobotPg],
            [15, 'PG', 'Kecepatan potong mesin bubut ($C_s$ dalam meter/menit) dengan diameter benda kerja $d\\text{ (mm)}$ dan putaran spindel $n\\text{ (rpm)}$ dirumuskan...', '$C_s = \\frac{\\pi \\times d \\times n}{1000}$', '$C_s = \\pi \\times d \\times n$', '$C_s = \\frac{1000}{\\pi \\times d \\times n}$', '$C_s = \\frac{\\pi \\times d}{n \\times 1000}$', '$C_s = \\frac{d \\times n}{1000}$', 'A', '', $bobotPg],
            [16, 'PG', 'Kawat elektroda las busur manual (SMAW) dengan kode spesifikasi AWS E6013 menunjukkan nilai kuat tarik minimum deposit las sebesar...', '$6.000\\text{ psi}$', '$60.000\\text{ psi}$', '$130.000\\text{ psi}$', '$600.000\\text{ psi}$', '$13.000\\text{ psi}$', 'B', '', $bobotPg],
            [17, 'PG', 'Fungsi utama dari Master Cylinder pada sistem pengereman hidrolis kendaraan adalah...', 'Mengurangi gesekan pada roda', 'Menyerap panas dari tromol rem', 'Menambah debit aliran udara', 'Mengubah dorongan pedal mekanis menjadi tekanan fluida hidrolik', 'Mendinginkan sirkulasi minyak rem', 'D', '', $bobotPg],
            [18, 'PG', 'Subnet mask standar untuk alamat jaringan IPv4 kelas C dengan notasi prefix CIDR /24 adalah...', '255.255.255.0', '255.255.0.0', '255.0.0.0', '255.255.255.128', '255.255.255.192', 'A', '', $bobotPg],
            [19, 'PG', 'Berdasarkan Teorema Pythagoras, panjang sisi miring hipotenusa ($c$) pada segitiga siku-siku dengan sisi penyiku $a$ dan $b$ dirumuskan...', '$c = \\sqrt{a^2 + b^2}$', '$c = a^2 + b^2$', '$c = \\sqrt{a^2 - b^2}$', '$c = \\sqrt{a + b}$', '$c = \\frac{a^2 + b^2}{2}$', 'A', '', $bobotPg],
            [20, 'PG', 'Pada motor bensin 4-langkah (4-stroke), langkah kerja di mana katup masuk dan buang sama-sama tertutup dan torak bergerak dari TMB ke TMA adalah...', 'Langkah Isap (Intake Stroke)', 'Langkah Kompresi (Compression Stroke)', 'Langkah Usaha (Power Stroke)', 'Langkah Buang (Exhaust Stroke)', 'Langkah Overlapping', 'B', '', $bobotPg],

            // 5 URAIAN / ESAI (Bobot masing-masing 10.0 pt = 50 pt)
            [21, 'URAIAN', 'Jelaskan perbedaan mendasar antara kabel jaringan jenis UTP (Unshielded Twisted Pair) dan STP (Shielded Twisted Pair) serta sebutkan situasi penggunaan keduanya!', '', '', '', '', '', '', 'Kunci Jawaban: Kabel UTP tidak memiliki lapisan pelindung aluminium foil (shielding) dan digunakan pada area perkantoran tertutup/indoor. Kabel STP memiliki lapisan pelindung logam foil untuk meredam gangguan interferensi elektromagnetik dan cocok untuk area industri/outdoor bertegangan tinggi.', $bobotUraian],
            [22, 'URAIAN', 'Sebuah pemanas listrik dihubungkan pada tegangan sumber $V = 220\\text{ Volt}$. Jika hambatan kawat pemanas adalah $R = 44\\,\\Omega$, hitunglah kuat arus listrik ($I$) dan daya listrik ($P$) yang diserap kawat menggunakan rumus $I = \\frac{V}{R}$ dan $P = V \\times I$!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Kuat arus $I = \\frac{220}{44} = 5\\text{ Ampere}$. 2) Daya listrik $P = 220 \\times 5 = 1.100\\text{ Watt}$. Bobot skor penuh (10 pt) jika mencantumkan rumus, langkah pengerjaan, dan satuan dengan benar.', $bobotUraian],
            [23, 'URAIAN', 'Sebutkan 4 (empat) Alat Pelindung Diri (APD) utama yang wajib dikenakan oleh seorang juru las saat melaksanakan pengelasan SMAW di bengkel beserta fungsinya!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Helm/Topeng Las: melindungi mata dan wajah dari sinar radiasi UV/IR dan percikan terak. 2) Sarung Tangan Kulit: melindungi tangan dari panas dan sengatan listrik. 3) Apron Kulit: melindungi dada dan tubuh dari percikan api las. 4) Safety Shoes: melindungi kaki dari kejatuhan benda panas/berat.', $bobotUraian],
            [24, 'URAIAN', 'Uraikan urutan langkah kerja penyetelan celah katup (valve clearance) pada mesin bensin 4 silinder segaris dengan Firing Order (FO) 1-3-4-2!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Putar poros engkol hingga tanda timing di puli lurus dengan angka 0 pada posisi TOP 1. 2) Periksa dan setel katup bebas silinder 1 menggunakan feeler gauge sesuai buku manual. 3) Kencangkan mur pengunci sambil menahan baut penyetel. 4) Putar poros engkol 360 derajat ke posisi TOP 4. 5) Setel celah katup bebas silinder 4.', $bobotUraian],
            [25, 'URAIAN', 'Jelaskan apa yang dimaksud dengan selimut beton (concrete cover) pada konstruksi beton bertulang (DPIB) dan mengapa ketebalannya minimal harus 3 cm pada pekerjaan pondasi!', '', '', '', '', '', '', 'Kunci Jawaban: Selimut beton adalah jarak antara permukaan luar tulangan baja dengan permukaan terluar beton. Pada pondasi, ketebalan minimal 3-5 cm diperlukan untuk melindungi tulangan baja dari kontak air tanah, garam mineral, dan kelembaban agar tidak mengalami korosi (karat) serta menjamin ketahanan terhadap api.', $bobotUraian],
        ];
    }

    /**
     * Bank Soal Sampel Preset 40 Nomor (100 Poin Penuh)
     */
    public function getSampleQuestions40($bobotBs, $bobotPg, $bobotUraian): array
    {
        return [
            // 10 BENAR / SALAH (Bobot masing-masing 1.0 pt = 10 pt)
            [1, 'BENAR_SALAH', 'Topologi jaringan tipe Star menggunakan satu perangkat konsentrator (seperti Switch atau Hub) sebagai pusat distribusi seluruh transmisi data.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [2, 'BENAR_SALAH', 'Alamat IP versi 4 (IPv4) memiliki panjang total 128 bit yang terbagi ke dalam 8 blok heksadesimal.', 'Benar', 'Salah', '', '', '', 'SALAH', 'IPv4 memiliki panjang 32 bit, sedangkan 128 bit adalah panjang IPv6.', $bobotBs],
            [3, 'BENAR_SALAH', 'Pada instalasi penerangan listrik rumah tangga, saklar pemutus arus selalu wajib dipasang pada penghantar fasa, bukan netral.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [4, 'BENAR_SALAH', 'Hukum Ohm menyatakan bahwa kuat arus listrik yang mengalir berbanding terbalik dengan beda potensial ($I = \\frac{R}{V}$).', 'Benar', 'Salah', '', '', '', 'SALAH', 'Rumus hukum Ohm yang benar adalah $I = \\frac{V}{R}$.', $bobotBs],
            [5, 'BENAR_SALAH', 'Proses pendinginan cepat (quenching) pada baja karbon bertujuan untuk membentuk fasa Martensit yang keras.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [6, 'BENAR_SALAH', 'Protokol DHCP berfungsi untuk mendistribusikan konfigurasi IP address secara otomatis kepada komputer client dalam jaringan.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [7, 'BENAR_SALAH', 'Kabel coaxial memiliki ketahanan terhadap interferensi elektromagnetik yang lebih tinggi dibandingkan kabel fiber optik.', 'Benar', 'Salah', '', '', '', 'SALAH', 'Kabel fiber optik kebal interferensi elektromagnetik karena mentransmisikan berkas cahaya.', $bobotBs],
            [8, 'BENAR_SALAH', 'Transformator tipe step-up berfungsi menaikkan tegangan listrik arus bolak-balik (AC) dengan menurunkan kuat arusnya secara seimbang.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],
            [9, 'BENAR_SALAH', 'Pada mesin diesel 4-langkah, bahan bakar solar dinyalakan oleh percikan bunga api tegangan tinggi dari busi pemantik.', 'Benar', 'Salah', '', '', '', 'SALAH', 'Mesin diesel menyalakan bahan bakar melalui kompresi udara bertekanan dan suhu tinggi tanpa busi.', $bobotBs],
            [10, 'BENAR_SALAH', 'Simbol pengelasan jenis sudut (fillet weld) pada gambar teknik dilambangkan dengan bentuk segitiga siku-siku pada garis referensi.', 'Benar', 'Salah', '', '', '', 'BENAR', '', $bobotBs],

            // 20 PILIHAN GANDA (Bobot masing-masing 2.0 pt = 40 pt)
            [11, 'PG', 'Protokol jaringan yang bertugas memetakan alamat IP logis menjadi alamat fisik perangkat keras (MAC Address) pada jaringan lokal adalah...', 'ARP (Address Resolution Protocol)', 'DNS (Domain Name System)', 'DHCP Server', 'ICMP', 'RARP', 'A', '', $bobotPg],
            [12, 'PG', 'Dalam arsitektur referensi 7 OSI Layer, perangkat router beroperasi pada lapisan...', 'Physical Layer (Layer 1)', 'Data Link Layer (Layer 2)', 'Network Layer (Layer 3)', 'Transport Layer (Layer 4)', 'Application Layer (Layer 7)', 'C', '', $bobotPg],
            [13, 'PG', 'Perhitungan daya aktif ($P$) pada sistem kelistrikan AC satu fasa dengan faktor daya $\\cos(\\phi)$ dirumuskan...', '$P = V \\times I$', '$P = V \\times I \\times \\cos(\\phi)$', '$P = V^2 \\times R$', '$P = \\frac{V \\times I}{\\cos(\\phi)}$', '$P = \\sqrt{3} \\times V \\times I$', 'B', '', $bobotPg],
            [14, 'PG', 'Komponen pada sistem pengapian konvensional kendaraan bermotor yang berfungsi menyerap percikan bunga api pada kontak platina adalah...', 'Busi pemantik', 'Kondensor (Kapasitor)', 'Koil pengapian', 'Distributor rotor', 'Alternator', 'B', '', $bobotPg],
            [15, 'PG', 'Pada gambar teknik konstruksi bangunan (DPIB), garis strip-titik-strip tipis ($-\\cdot-\\cdot-$) standar ISO digunakan untuk menunjukkan...', 'Garis sumbu atau as simetri', 'Garis kontur dinding tampak', 'Garis potongan bidang', 'Garis ukuran dan dimensi', 'Garis arsiran material', 'A', '', $bobotPg],
            [16, 'PG', 'Sebuah resistor karbon memiliki 4 gelang warna: Cokelat, Hitam, Merah, Emas. Nilai resistansi nominal dan nilai toleransinya adalah...', '$100\\,\\Omega \\pm 5\\%$', '$1.000\\,\\Omega \\pm 10\\%$', '$10.000\\,\\Omega \\pm 5\\%$', '$1.000\\,\\Omega \\pm 5\\%$ ($1\\,\\text{k}\\Omega$)', '$10\\,\\Omega \\pm 5\\%$', 'D', '', $bobotPg],
            [17, 'PG', 'Skala gambar $1 : 50$ pada gambar denah bangunan bermakna bahwa setiap jarak $1\\text{ cm}$ pada kertas gambar mewakili ukuran sesungguhnya sebesar...', '$5\\text{ cm}$', '$50\\text{ cm}$ ($0,5\\text{ meter}$)', '$500\\text{ cm}$ ($5\\text{ meter}$)', '$50\\text{ meter}$', '$5\\text{ meter}$', 'B', '', $bobotPg],
            [18, 'PG', 'Rumus luas penampang ($A$) pondasi tiang pancang berbentuk lingkaran dengan diameter $d$ dirumuskan secara matematis dengan...', '$A = \\pi \\times d$', '$A = 2 \\times \\pi \\times d$', '$A = \\frac{\\pi \\times d^2}{4}$', '$A = \\pi \\times d^2$', '$A = \\frac{\\pi \\times d}{2}$', 'C', '', $bobotPg],
            [19, 'PG', 'Alat ukur presisi otomotif yang paling tepat digunakan untuk mengukur keolengan (run-out) piringan rem cakram roda mobil adalah...', 'Jangka Sorong (Vernier Caliper)', 'Mikrometer Luar', 'Dial Indicator (Dial Gauge)', 'Silinder Bore Gauge', 'Feeler Gauge', 'C', '', $bobotPg],
            [20, 'PG', 'Kecepatan potong mesin bubut ($C_s$ dalam meter/menit) dengan diameter benda kerja $d\\text{ (mm)}$ dan putaran spindel $n\\text{ (rpm)}$ dirumuskan...', '$C_s = \\frac{\\pi \\times d \\times n}{1000}$', '$C_s = \\pi \\times d \\times n$', '$C_s = \\frac{1000}{\\pi \\times d \\times n}$', '$C_s = \\frac{\\pi \\times d}{n \\times 1000}$', '$C_s = \\frac{d \\times n}{1000}$', 'A', '', $bobotPg],
            [21, 'PG', 'Kawat elektroda las busur manual (SMAW) dengan kode spesifikasi AWS E6013 menunjukkan nilai kuat tarik minimum deposit las sebesar...', '$6.000\\text{ psi}$', '$60.000\\text{ psi}$', '$130.000\\text{ psi}$', '$600.000\\text{ psi}$', '$13.000\\text{ psi}$', 'B', '', $bobotPg],
            [22, 'PG', 'Fungsi utama dari Master Cylinder pada sistem pengereman hidrolis kendaraan adalah...', 'Mengurangi gesekan pada roda', 'Menyerap panas dari tromol rem', 'Menambah debit aliran udara', 'Mengubah dorongan pedal mekanis menjadi tekanan fluida hidrolik', 'Mendinginkan sirkulasi minyak rem', 'D', '', $bobotPg],
            [23, 'PG', 'Subnet mask standar untuk alamat jaringan IPv4 kelas C dengan notasi prefix CIDR /24 adalah...', '255.255.255.0', '255.255.0.0', '255.0.0.0', '255.255.255.128', '255.255.255.192', 'A', '', $bobotPg],
            [24, 'PG', 'Berdasarkan Teorema Pythagoras, panjang sisi miring hipotenusa ($c$) pada segitiga siku-siku dengan sisi penyiku $a$ dan $b$ dirumuskan...', '$c = \\sqrt{a^2 + b^2}$', '$c = a^2 + b^2$', '$c = \\sqrt{a^2 - b^2}$', '$c = \\sqrt{a + b}$', '$c = \\frac{a^2 + b^2}{2}$', 'A', '', $bobotPg],
            [25, 'PG', 'Pada motor bensin 4-langkah (4-stroke), langkah kerja di mana katup masuk dan buang sama-sama tertutup dan torak bergerak dari TMB ke TMA adalah...', 'Langkah Isap (Intake Stroke)', 'Langkah Kompresi (Compression Stroke)', 'Langkah Usaha (Power Stroke)', 'Langkah Buang (Exhaust Stroke)', 'Langkah Overlapping', 'B', '', $bobotPg],
            [26, 'PG', 'Perangkat keras yang berfungsi menyediakan daya listrik cadangan seketika saat listrik PLN padam pada ruang server adalah...', 'UPS (Uninterruptible Power Supply)', 'Stabilizer Analog', 'Genset Manual', 'Power Supply Unit (PSU)', 'Inverter Non-Sinusoidal', 'A', '', $bobotPg],
            [27, 'PG', 'Satuan pengukuran fluks cahaya (luminous flux) yang dipancarkan oleh lampu penerangan dalam Sistem Internasional (SI) adalah...', 'Candela (cd)', 'Lumen (lm)', 'Lux (lx)', 'Watt (W)', 'Foot-candle', 'B', '', $bobotPg],
            [28, 'PG', 'Perbandingan volume campuran spesi plesteran kedap air (trasraam) pada pasangan bata di atas sloof bangunan adalah...', '1 Semen : 8 Pasir', '1 Semen : 5 Pasir', '1 Semen : 2 Pasir', '1 Semen : 6 Pasir', '1 Semen : 10 Pasir', 'C', '', $bobotPg],
            [29, 'PG', 'Minyak pelumas roda gigi transmisi manual mobil umumnya menggunakan standar viskositas SAE sebesar...', 'SAE 10W-40', 'SAE 5W-30', 'SAE 0W-20', 'SAE 80W-90', 'SAE 20W-50', 'D', '', $bobotPg],
            [30, 'PG', 'Pada mesin bubut konvensional, ujung mata pahat bubut wajib dipasang tepat setinggi...', 'Di atas sumbu senter benda kerja 5 mm', 'Tepat setinggi garis sumbu senter mesin', 'Di bawah sumbu senter benda kerja 3 mm', 'Bebas sesuai ketebalan pahat', 'Miring 45 derajat terhadap meja', 'B', '', $bobotPg],

            // 10 URAIAN / ESAI (Bobot masing-masing 5.0 pt = 50 pt)
            [31, 'URAIAN', 'Jelaskan perbedaan mendasar antara kabel jaringan jenis UTP (Unshielded Twisted Pair) dan STP (Shielded Twisted Pair) serta sebutkan situasi penggunaan keduanya!', '', '', '', '', '', '', 'Kunci Jawaban: UTP tanpa pelindung foil untuk perkantoran indoor. STP memiliki pelindung logam foil untuk meredam interferensi elektromagnetik dan cocok untuk lingkungan industri/outdoor.', $bobotUraian],
            [32, 'URAIAN', 'Sebuah pemanas listrik dihubungkan pada tegangan sumber $V = 220\\text{ Volt}$. Jika hambatan kawat adalah $R = 44\\,\\Omega$, hitunglah kuat arus listrik ($I$) dan daya listrik ($P$)!', '', '', '', '', '', '', 'Kunci Jawaban: 1) $I = \\frac{220}{44} = 5\\text{ Ampere}$. 2) $P = 220 \\times 5 = 1.100\\text{ Watt}$.', $bobotUraian],
            [33, 'URAIAN', 'Sebutkan 4 (empat) Alat Pelindung Diri (APD) utama yang wajib dikenakan saat melaksanakan pengelasan SMAW di bengkel beserta fungsinya!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Helm las: lindungi mata dari sinar UV/IR. 2) Sarung tangan kulit: lindungi tangan dari panas/setrum. 3) Apron kulit: lindungi dada dari percikan api. 4) Safety shoes: lindungi kaki.', $bobotUraian],
            [34, 'URAIAN', 'Uraikan urutan langkah kerja penyetelan celah katup pada mesin bensin 4 silinder FO 1-3-4-2!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Putar poros engkol ke tanda TOP 1. 2) Setel katup bebas silinder 1 dengan feeler gauge. 3) Kencangkan mur pengunci. 4) Putar engkol 360 derajat ke TOP 4. 5) Setel katup bebas silinder 4.', $bobotUraian],
            [35, 'URAIAN', 'Jelaskan apa yang dimaksud dengan selimut beton (concrete cover) dan mengapa ketebalannya minimal harus 3 cm pada pondasi!', '', '', '', '', '', '', 'Kunci Jawaban: Selimut beton adalah jarak antara permukaan luar tulangan baja dan permukaan luar beton untuk melindungi baja tulangan dari karat/korosi akibat kelembaban air tanah.', $bobotUraian],
            [36, 'URAIAN', 'Jelaskan prinsip kerja proteksi saklar otomatis MCB (Miniature Circuit Breaker) terhadap gangguan arus lebih (overload) dan arus hubung singkat (short circuit)!', '', '', '', '', '', '', 'Kunci Jawaban: 1) Beban lebih (overload) diamankan dengan elemen bimetal yang melengkung saat panas. 2) Hubung singkat (short circuit) diamankan seketika dengan kumparan elektromagnetik (solenoid trip).', $bobotUraian],
            [37, 'URAIAN', 'Sebutkan urutan standar susunan warna kabel UTP untuk pengkabelan standar TIA/EIA-568B dari nomor pin 1 sampai pin 8!', '', '', '', '', '', '', 'Kunci Jawaban: Pin 1: Putih-Oranye, Pin 2: Oranye, Pin 3: Putih-Hijau, Pin 4: Biru, Pin 5: Putih-Biru, Pin 6: Hijau, Pin 7: Putih-Cokelat, Pin 8: Cokelat.', $bobotUraian],
            [38, 'URAIAN', 'Jelaskan fungsi komponen Differential (Gardan) pada kendaraan roda empat saat mobil sedang berbelok ke arah kanan atau kiri!', '', '', '', '', '', '', 'Kunci Jawaban: Membedakan putaran roda kiri dan roda kanan saat membelok (roda sisi luar berputar lebih cepat dari roda sisi dalam) agar kendaraan tidak tergelincir atau slip.', $bobotUraian],
            [39, 'URAIAN', 'Jelaskan perbedaan mendasar antara posisi sambungan las tumpul (butt joint) dan sambungan las tumpang (lap joint)!', '', '', '', '', '', '', 'Kunci Jawaban: Sambungan tumpul (butt joint) menyatukan dua permukaan benda kerja yang sejajar pada bidang datar yang sama. Sambungan tumpang (lap joint) menyatukan dua pelat yang saling bertumpukan atau saling menindih.', $bobotUraian],
            [40, 'URAIAN', 'Jelaskan apa yang dimaksud dengan As-Built Drawing dalam manajemen proyek konstruksi bangunan gedung (DPIB)!', '', '', '', '', '', '', 'Kunci Jawaban: As-Built Drawing adalah gambar akhir rekaman hasil pelaksanaan konstruksi yang dibuat oleh kontraktor setelah pekerjaan selesai, mencatat seluruh penyesuaian dan perubahan nyata yang terjadi di lapangan.', $bobotUraian],
        ];
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
                // Lewati baris judul / banner / header (di mana kolom A bukan angka nomor urut 1, 2, 3...)
                $rawA = trim((string)($row['A'] ?? ''));
                if (!is_numeric($rawA) || (int)$rawA <= 0) {
                    continue;
                }
                $nomorUrut = (int)$rawA;

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

    /**
     * Halaman Utama Menu: Daftar Hadir Asesmen (Filter per Jadwal, Kelas, Sesi)
     */
    public function daftarHadirIndex(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas'])->orderByDesc('id')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $selectedJadwalId = $request->query('jadwal_id', $jadwalList->first()?->id);
        $selectedKelasId = $request->query('kelas_id');
        $selectedSesi = $request->query('sesi');

        $activeJadwal = $jadwalList->firstWhere('id', $selectedJadwalId);

        $sesiRows = [];
        if ($activeJadwal) {
            $targetKelas = $activeJadwal->kelas;
            if ($selectedKelasId) {
                $targetKelas = $targetKelas->where('id', $selectedKelasId);
            }

            foreach ($targetKelas as $kls) {
                $availableSessions = ['Sesi 1', 'Sesi 2', 'Sesi 3'];
                if ($selectedSesi) {
                    $availableSessions = array_filter($availableSessions, function ($s) use ($selectedSesi) {
                        return strcasecmp(trim($s), trim($selectedSesi)) === 0 || strcasecmp(trim(str_replace('Sesi ', '', $s)), trim($selectedSesi)) === 0;
                    });
                }

                foreach ($availableSessions as $sesiName) {
                    $cleanSesi = strtolower(str_replace(' ', '', $sesiName));
                    $siswaCount = Siswa::where('kelas_id', $kls->id)
                        ->where(function ($q) use ($cleanSesi) {
                            $q->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])
                              ->orWhereNull('sesi_ujian')
                              ->orWhere('sesi_ujian', '');
                        })
                        ->count();

                    $ba = BeritaAcara::where('jadwal_ujian_id', $activeJadwal->id)
                        ->where('kelas_id', $kls->id)
                        ->where(function ($q) use ($sesiName) {
                            $q->where('sesi', $sesiName)
                              ->orWhere('sesi', str_replace('Sesi ', '', $sesiName));
                        })
                        ->first();

                    if (!$ba) {
                        $baFallback = BeritaAcara::where('jadwal_ujian_id', $activeJadwal->id)->first();
                    } else {
                        $baFallback = $ba;
                    }

                    $sesiRows[] = [
                        'jadwal' => $activeJadwal,
                        'kelas' => $kls,
                        'sesi' => $sesiName,
                        'total_siswa' => $siswaCount,
                        'berita_acara' => $ba,
                        'proktor' => $ba ? $ba->nama_proktor : ($baFallback?->nama_proktor ?? 'Izak Robinson Koroh, S.Pd.,Gr'),
                        'pengawas' => $ba ? $ba->nama_pengawas : ($baFallback?->nama_pengawas ?? 'Mike Amin Imanuel Modok'),
                        'hadir' => $ba ? $ba->jumlah_peserta_hadir : $siswaCount,
                        'tidak_hadir' => $ba ? $ba->jumlah_peserta_tidak_hadir : 0,
                        'status' => $ba ? 'Tersimpan' : 'Siap Diisi',
                    ];
                }
            }
        }

        return view('admin.daftar_hadir', compact(
            'sekolah', 'jadwalList', 'kelasList', 'selectedJadwalId', 'selectedKelasId', 'selectedSesi', 'activeJadwal', 'sesiRows'
        ));
    }

    /**
     * Form Isi Daftar Hadir Per-Kelas dan Per-Sesi
     */
    public function formDaftarHadir(Request $request, $jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas'])->findOrFail($jadwalId);

        $kelasId = $request->query('kelas_id', $jadwal->kelas->first()?->id);
        $sesi = $request->query('sesi', 'Sesi 1');
        $sesiFormatted = stripos($sesi, 'Sesi') === false ? 'Sesi ' . $sesi : $sesi;
        $sesiNumeric = str_replace(['Sesi ', 'sesi '], '', $sesiFormatted);

        $kelas = Kelas::find($kelasId) ?? $jadwal->kelas->first();

        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)
            ->where(function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->orWhereNull('kelas_id');
            })
            ->where(function ($q) use ($sesiFormatted, $sesiNumeric) {
                $q->where('sesi', $sesiFormatted)->orWhere('sesi', $sesiNumeric);
            })
            ->first();

        $guruList = User::whereIn('role', ['guru', 'proktor', 'admin'])->orderBy('name')->get();

        $cleanSesi = strtolower(str_replace(' ', '', $sesiFormatted));
        $siswaQuery = Siswa::with('user', 'kelas');
        if ($kelas) {
            $siswaQuery->where('kelas_id', $kelas->id);
        }
        $matchSesi = (clone $siswaQuery)->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])->count();
        if ($matchSesi > 0) {
            $siswaQuery->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi]);
        }
        $siswaList = $siswaQuery->orderBy('nama_lengkap')->get();

        return view('admin.daftar_hadir_form', compact(
            'sekolah', 'jadwal', 'kelas', 'sesiFormatted', 'beritaAcara', 'guruList', 'siswaList'
        ));
    }

    /**
     * Simpan Presensi & Data Daftar Hadir
     */
    public function simpanDaftarHadir(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::findOrFail($jadwalId);

        $data = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'sesi' => 'required|string|max:30',
            'ruangan' => 'nullable|string|max:50',
            'id_server' => 'nullable|string|max:80',
            'kode_kabupaten' => 'nullable|string|max:30',
            'kode_sekolah' => 'nullable|string|max:30',
            'nama_tes' => 'nullable|string|max:150',
            'hari_tanggal' => 'required|string|max:100',
            'waktu_mulai' => 'nullable|string|max:30',
            'waktu_selesai' => 'nullable|string|max:30',
            'proktor_id' => 'nullable|exists:users,id',
            'nama_proktor' => 'required|string|max:150',
            'nip_proktor' => 'nullable|string|max:40',
            'pengawas_id' => 'nullable|exists:users,id',
            'nama_pengawas' => 'required|string|max:150',
            'nip_pengawas' => 'nullable|string|max:40',
            'catatan_pelaksanaan' => 'nullable|string',
            'kehadiran' => 'nullable|array',
            'keterangan_siswa' => 'nullable|array',
        ]);

        $kelasId = $data['kelas_id'];
        $sesi = $data['sesi'];
        $kehadiran = $request->input('kehadiran', []);
        $keteranganSiswa = $request->input('keterangan_siswa', []);

        $cleanSesi = strtolower(str_replace(' ', '', $sesi));
        $siswaQuery = Siswa::with('user')->where('kelas_id', $kelasId);
        $matchSesi = (clone $siswaQuery)->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])->count();
        if ($matchSesi > 0) {
            $siswaQuery->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi]);
        }
        $siswaList = $siswaQuery->get();

        $seharusnya = $siswaList->count();
        $hadir = 0;
        $tidakHadir = 0;
        $tidakHadirList = [];
        $presensiData = [];

        foreach ($siswaList as $s) {
            $status = $kehadiran[$s->id] ?? 'hadir';
            $ket = $keteranganSiswa[$s->id] ?? '';
            $presensiData[$s->id] = [
                'status' => $status,
                'keterangan' => $ket,
                'username' => $s->user->username ?? $s->nomor_peserta ?? $s->nisn,
                'nama' => $s->nama_lengkap,
            ];

            if ($status === 'hadir') {
                $hadir++;
            } else {
                $tidakHadir++;
                $usr = $s->user->username ?? $s->nomor_peserta ?? $s->nisn;
                $tidakHadirList[] = $usr . ($ket ? " ($ket)" : '');
            }
        }

        $beritaAcara = BeritaAcara::updateOrCreate(
            [
                'jadwal_ujian_id' => $jadwalId,
                'kelas_id' => $kelasId,
                'sesi' => $sesi,
            ],
            [
                'nomor_surat' => $request->input('nomor_surat', '421.5/024/SMKN1.KB/' . date('Y')),
                'nama_tes' => $request->input('nama_tes', $jadwal->nama_ujian),
                'hari_tanggal' => $data['hari_tanggal'],
                'waktu_mulai' => $data['waktu_mulai'] ?? '08:00',
                'waktu_selesai' => $data['waktu_selesai'] ?? '09:45',
                'ruangan' => $data['ruangan'] ?? 'Lab Komputer 1',
                'id_server' => $data['id_server'] ?? 'K24020010-AS4K',
                'kode_kabupaten' => $data['kode_kabupaten'] ?? '02',
                'kode_sekolah' => $data['kode_sekolah'] ?? '0010',
                'proktor_id' => $data['proktor_id'] ?? null,
                'nama_proktor' => $data['nama_proktor'],
                'nip_proktor' => $data['nip_proktor'] ?? null,
                'pengawas_id' => $data['pengawas_id'] ?? null,
                'nama_pengawas' => $data['nama_pengawas'],
                'nip_pengawas' => $data['nip_pengawas'] ?? null,
                'penanggung_jawab_nama' => $request->input('penanggung_jawab_nama', $jadwal->bankSoal->user->name ?? 'Apolos Kofan, S.Pd.,MM'),
                'penanggung_jawab_nip' => $request->input('penanggung_jawab_nip', '196704062006041009'),
                'jumlah_peserta_seharusnya' => $seharusnya,
                'jumlah_peserta_hadir' => $hadir,
                'jumlah_peserta_tidak_hadir' => $tidakHadir,
                'daftar_tidak_hadir' => count($tidakHadirList) > 0 ? implode(', ', $tidakHadirList) : '-',
                'catatan_pelaksanaan' => $data['catatan_pelaksanaan'] ?? 'UJIAN BERJALAN AMAN DAN LANCAR',
                'presensi_data' => $presensiData,
            ]
        );

        return redirect()->route('admin.daftar_hadir.form', ['id' => $jadwalId, 'kelas_id' => $kelasId, 'sesi' => $sesi])
            ->with('success', 'Daftar Hadir Asesmen berhasil disimpan. Data rekapitulasi kehadiran otomatis tersinkron.');
    }

    /**
     * Halaman Utama Menu: Berita Acara Asesmen (Filter per Jadwal, Kelas, Sesi)
     */
    public function beritaAcaraIndex(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas'])->orderByDesc('id')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $selectedJadwalId = $request->query('jadwal_id', $jadwalList->first()?->id);
        $selectedKelasId = $request->query('kelas_id');
        $selectedSesi = $request->query('sesi');

        $activeJadwal = $jadwalList->firstWhere('id', $selectedJadwalId);

        $sesiRows = [];
        if ($activeJadwal) {
            $targetKelas = $activeJadwal->kelas;
            if ($selectedKelasId) {
                $targetKelas = $targetKelas->where('id', $selectedKelasId);
            }

            foreach ($targetKelas as $kls) {
                $availableSessions = ['Sesi 1', 'Sesi 2', 'Sesi 3'];
                if ($selectedSesi) {
                    $availableSessions = array_filter($availableSessions, function ($s) use ($selectedSesi) {
                        return strcasecmp(trim($s), trim($selectedSesi)) === 0 || strcasecmp(trim(str_replace('Sesi ', '', $s)), trim($selectedSesi)) === 0;
                    });
                }

                foreach ($availableSessions as $sesiName) {
                    $cleanSesi = strtolower(str_replace(' ', '', $sesiName));
                    $siswaCount = Siswa::where('kelas_id', $kls->id)
                        ->where(function ($q) use ($cleanSesi) {
                            $q->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])
                              ->orWhereNull('sesi_ujian')
                              ->orWhere('sesi_ujian', '');
                        })
                        ->count();

                    $ba = BeritaAcara::where('jadwal_ujian_id', $activeJadwal->id)
                        ->where('kelas_id', $kls->id)
                        ->where(function ($q) use ($sesiName) {
                            $q->where('sesi', $sesiName)
                              ->orWhere('sesi', str_replace('Sesi ', '', $sesiName));
                        })
                        ->first();

                    if (!$ba) {
                        $baFallback = BeritaAcara::where('jadwal_ujian_id', $activeJadwal->id)->first();
                    } else {
                        $baFallback = $ba;
                    }

                    $sesiRows[] = [
                        'jadwal' => $activeJadwal,
                        'kelas' => $kls,
                        'sesi' => $sesiName,
                        'total_siswa' => $siswaCount,
                        'berita_acara' => $ba,
                        'nomor_surat' => $ba ? $ba->nomor_surat : ('421.5/024/SMKN1.KB/' . date('Y')),
                        'ruangan' => $ba ? $ba->ruangan : 'Lab Komputer 1',
                        'id_server' => $ba ? $ba->id_server : 'K24020010-AS4K',
                        'proktor' => $ba ? $ba->nama_proktor : ($baFallback?->nama_proktor ?? 'Izak Robinson Koroh, S.Pd.,Gr'),
                        'pengawas' => $ba ? $ba->nama_pengawas : ($baFallback?->nama_pengawas ?? 'Mike Amin Imanuel Modok'),
                        'penanggung_jawab' => $ba ? $ba->penanggung_jawab_nama : $sekolah->kepala_sekolah,
                        'hadir' => $ba ? $ba->jumlah_peserta_hadir : $siswaCount,
                        'tidak_hadir' => $ba ? $ba->jumlah_peserta_tidak_hadir : 0,
                        'status' => $ba ? 'Lengkap' : 'Belum Dibuat',
                    ];
                }
            }
        }

        return view('admin.berita_acara', compact(
            'sekolah', 'jadwalList', 'kelasList', 'selectedJadwalId', 'selectedKelasId', 'selectedSesi', 'activeJadwal', 'sesiRows'
        ));
    }

    /**
     * Form Isi Berita Acara (Support Per-Kelas & Per-Sesi)
     */
    public function formBeritaAcara(Request $request, $jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas'])->findOrFail($jadwalId);

        $kelasId = $request->query('kelas_id', $jadwal->kelas->first()?->id);
        $sesi = $request->query('sesi', 'Sesi 1');
        $sesiFormatted = stripos($sesi, 'Sesi') === false ? 'Sesi ' . $sesi : $sesi;
        $sesiNumeric = str_replace(['Sesi ', 'sesi '], '', $sesiFormatted);

        $kelas = Kelas::find($kelasId) ?? $jadwal->kelas->first();

        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)
            ->where(function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->orWhereNull('kelas_id');
            })
            ->where(function ($q) use ($sesiFormatted, $sesiNumeric) {
                $q->where('sesi', $sesiFormatted)->orWhere('sesi', $sesiNumeric);
            })
            ->first();

        $guruList = User::whereIn('role', ['guru', 'proktor', 'admin'])->orderBy('name')->get();

        $cleanSesi = strtolower(str_replace(' ', '', $sesiFormatted));
        $siswaQuery = Siswa::where('kelas_id', $kelasId);
        $matchSesi = (clone $siswaQuery)->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])->count();
        if ($matchSesi > 0) {
            $siswaQuery->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi]);
        }
        $totalPeserta = $siswaQuery->count();

        return view('admin.berita_acara_form', compact(
            'sekolah', 'jadwal', 'kelas', 'sesiFormatted', 'beritaAcara', 'guruList', 'totalPeserta'
        ));
    }

    /**
     * Simpan Berita Acara (Support Per-Kelas & Per-Sesi)
     */
    public function simpanBeritaAcara(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::findOrFail($jadwalId);

        $data = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'nomor_surat' => 'required|string|max:100',
            'nama_tes' => 'nullable|string|max:150',
            'hari_tanggal' => 'required|string|max:100',
            'waktu_mulai' => 'nullable|string|max:30',
            'waktu_selesai' => 'nullable|string|max:30',
            'ruangan' => 'nullable|string|max:50',
            'id_server' => 'nullable|string|max:80',
            'kode_kabupaten' => 'nullable|string|max:30',
            'kode_sekolah' => 'nullable|string|max:30',
            'sesi' => 'nullable|string|max:30',
            'proktor_id' => 'nullable|exists:users,id',
            'nama_proktor' => 'required|string|max:150',
            'nip_proktor' => 'nullable|string|max:40',
            'pengawas_id' => 'nullable|exists:users,id',
            'nama_pengawas' => 'required|string|max:150',
            'nip_pengawas' => 'nullable|string|max:40',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
            'penanggung_jawab_nama' => 'required|string|max:150',
            'penanggung_jawab_nip' => 'nullable|string|max:50',
            'jumlah_peserta_seharusnya' => 'required|integer|min:0',
            'jumlah_peserta_hadir' => 'required|integer|min:0',
            'jumlah_peserta_tidak_hadir' => 'nullable|integer|min:0',
            'daftar_tidak_hadir' => 'nullable|string',
            'catatan_pelaksanaan' => 'nullable|string',
        ]);

        $kelasId = $data['kelas_id'] ?? null;
        $sesi = $data['sesi'] ?? 'Sesi 1';

        $beritaAcara = BeritaAcara::updateOrCreate(
            [
                'jadwal_ujian_id' => $jadwalId,
                'kelas_id' => $kelasId,
                'sesi' => $sesi,
            ],
            $data
        );

        return redirect()->route('admin.berita_acara.form', ['id' => $jadwalId, 'kelas_id' => $kelasId, 'sesi' => $sesi])
            ->with('success', 'Berita Acara Asesmen berhasil disimpan. Anda dapat langsung mencetak lembar dokumen A4.');
    }

    /**
     * Cetak Berita Acara Format A4 Presisi Pusmendik Kemendikdasmen (BA_LAB_1_BINDO_S1.pdf)
     */
    public function cetakBeritaAcara(Request $request, $jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas.siswa'])->findOrFail($jadwalId);

        $kelasId = $request->query('kelas_id', $jadwal->kelas->first()?->id);
        $sesi = $request->query('sesi', 'Sesi 1');
        $sesiFormatted = stripos($sesi, 'Sesi') === false ? 'Sesi ' . $sesi : $sesi;
        $sesiNumeric = str_replace(['Sesi ', 'sesi '], '', $sesiFormatted);

        $kelas = Kelas::find($kelasId) ?? $jadwal->kelas->first();

        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)
            ->where(function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->orWhereNull('kelas_id');
            })
            ->where(function ($q) use ($sesiFormatted, $sesiNumeric) {
                $q->where('sesi', $sesiFormatted)->orWhere('sesi', $sesiNumeric);
            })
            ->first();

        if (!$beritaAcara) {
            $cleanSesi = strtolower(str_replace(' ', '', $sesiFormatted));
            $siswaQuery = Siswa::where('kelas_id', $kelasId);
            $matchSesi = (clone $siswaQuery)->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])->count();
            if ($matchSesi > 0) {
                $siswaQuery->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi]);
            }
            $totalPeserta = $siswaQuery->count();

            $proktor = User::where('role', 'proktor')->first() ?? User::where('username', '199002192020121003')->first();
            $pengawas = User::where('role', 'guru')->first() ?? User::where('username', '8843778679130052')->first();

            $beritaAcara = new BeritaAcara([
                'jadwal_ujian_id' => $jadwalId,
                'kelas_id' => $kelasId,
                'nomor_surat' => '421.5/024/SMKN1.KB/' . date('Y'),
                'nama_tes' => $jadwal->nama_ujian,
                'hari_tanggal' => Carbon::now()->isoFormat('dddd, D MMMM Y'),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '09:45',
                'ruangan' => 'TKA 1',
                'id_server' => 'K24020010-AS4K',
                'kode_kabupaten' => '02',
                'kode_sekolah' => '0010',
                'sesi' => $sesiNumeric,
                'nama_proktor' => $proktor ? $proktor->name : 'Izak Robinson Koroh, S.Pd.,Gr',
                'nip_proktor' => $proktor ? ($proktor->nip ?: $proktor->username) : '199002192020121003',
                'nama_pengawas' => $pengawas ? $pengawas->name : 'Mike Amin Imanuel Modok',
                'nip_pengawas' => $pengawas ? ($pengawas->nip ?: $pengawas->username) : '8843778679130052',
                'penanggung_jawab_nama' => $sekolah->kepala_sekolah ?: 'Apolos Kofan, S.Pd.,MM',
                'penanggung_jawab_nip' => $sekolah->nip_kepala_sekolah ?: '196704062006041009',
                'jumlah_peserta_seharusnya' => $totalPeserta ?: 18,
                'jumlah_peserta_hadir' => $totalPeserta ?: 18,
                'jumlah_peserta_tidak_hadir' => 0,
                'daftar_tidak_hadir' => '-',
                'catatan_pelaksanaan' => 'UJIAN BERJALAN AMAN DAN LANCAR',
            ]);
        }

        return view('admin.cetak_berita_acara', compact('sekolah', 'jadwal', 'kelas', 'beritaAcara'));
    }

    /**
     * Cetak Daftar Hadir Format A4 Presisi Pusmendik Kemendikdasmen (DH_HARI_1_BINDO.pdf)
     */
    public function cetakDaftarHadir(Request $request, $jadwalId)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwal = JadwalUjian::with(['bankSoal.mataPelajaran', 'kelas.siswa'])->findOrFail($jadwalId);

        $kelasId = $request->query('kelas_id', $jadwal->kelas->first()?->id);
        $sesi = $request->query('sesi', 'Sesi 1');
        $sesiFormatted = stripos($sesi, 'Sesi') === false ? 'Sesi ' . $sesi : $sesi;
        $sesiNumeric = str_replace(['Sesi ', 'sesi '], '', $sesiFormatted);

        $kelas = Kelas::find($kelasId) ?? $jadwal->kelas->first();

        $beritaAcara = BeritaAcara::where('jadwal_ujian_id', $jadwalId)
            ->where(function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->orWhereNull('kelas_id');
            })
            ->where(function ($q) use ($sesiFormatted, $sesiNumeric) {
                $q->where('sesi', $sesiFormatted)->orWhere('sesi', $sesiNumeric);
            })
            ->first();

        $cleanSesi = strtolower(str_replace(' ', '', $sesiFormatted));
        $siswaQuery = Siswa::with('user', 'kelas');
        if ($kelas) {
            $siswaQuery->where('kelas_id', $kelas->id);
        }
        $matchSesi = (clone $siswaQuery)->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi])->count();
        if ($matchSesi > 0) {
            $siswaQuery->whereRaw("LOWER(REPLACE(sesi_ujian, ' ', '')) = ?", [$cleanSesi]);
        }
        $siswaList = $siswaQuery->orderBy('nama_lengkap')->get();

        return view('admin.cetak_daftar_hadir', compact('sekolah', 'jadwal', 'kelas', 'sesiFormatted', 'sesiNumeric', 'siswaList', 'beritaAcara'));
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

    public function updatePetugasUjian(Request $request, $id)
    {
        $petugas = PetugasUjian::findOrFail($id);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_ujian_id' => 'nullable|exists:jadwal_ujian,id',
            'jenis_petugas' => 'required|in:proktor,pengawas',
            'ruangan' => 'required|string|max:50',
            'sesi' => 'required|string|max:30',
            'nomor_sk' => 'nullable|string|max:100',
        ]);

        $petugas->update($data);

        if ($data['jenis_petugas'] === 'proktor') {
            User::where('id', $data['user_id'])->update(['role' => 'proktor']);
        }

        return back()->with('success', 'Penugasan petugas (' . ucfirst($data['jenis_petugas']) . ') berhasil diperbarui.');
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

    // ==========================================
    // MODUL LAPORAN & REKAP NILAI (FASE 8)
    // ==========================================

    public function laporanNilai(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with('bankSoal.mataPelajaran')->orderBy('id', 'desc')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $selectedJadwalId = $request->input('jadwal_id', $jadwalList->first()?->id);
        $selectedKelasId = $request->input('kelas_id');
        $statusKelulusan = $request->input('status_kelulusan'); // 'semua', 'tuntas', 'belum_tuntas'

        $selectedJadwal = $selectedJadwalId ? JadwalUjian::with('bankSoal.mataPelajaran')->find($selectedJadwalId) : null;
        $kkm = $selectedJadwal?->bankSoal?->kkm ?? 75;

        $pesertaQuery = UjianPeserta::with(['siswa.kelas', 'jadwalUjian.bankSoal.mataPelajaran'])
            ->where('jadwal_ujian_id', $selectedJadwalId);

        if ($selectedKelasId) {
            $pesertaQuery->whereHas('siswa', function ($q) use ($selectedKelasId) {
                $q->where('kelas_id', $selectedKelasId);
            });
        }

        $allPeserta = $pesertaQuery->orderBy('total_nilai', 'desc')->get();

        // Filter status kelulusan jika dipilih
        if ($statusKelulusan === 'tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai >= $kkm);
        } elseif ($statusKelulusan === 'belum_tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai < $kkm);
        } else {
            $pesertaList = $allPeserta;
        }

        // Statistik
        $totalPeserta = $allPeserta->count();
        $nilaiTertinggi = $totalPeserta > 0 ? $allPeserta->max('total_nilai') : 0;
        $nilaiTerendah = $totalPeserta > 0 ? $allPeserta->min('total_nilai') : 0;
        $nilaiRataRata = $totalPeserta > 0 ? round($allPeserta->avg('total_nilai'), 2) : 0;
        $jumlahTuntas = $allPeserta->filter(fn($p) => $p->total_nilai >= $kkm)->count();
        $persenTuntas = $totalPeserta > 0 ? round(($jumlahTuntas / $totalPeserta) * 100, 1) : 0;

        $stats = [
            'total' => $totalPeserta,
            'tertinggi' => $nilaiTertinggi,
            'terendah' => $nilaiTerendah,
            'rata_rata' => $nilaiRataRata,
            'tuntas' => $jumlahTuntas,
            'persen_tuntas' => $persenTuntas,
            'kkm' => $kkm,
        ];

        return view('admin.laporan_nilai', compact(
            'sekolah',
            'jadwalList',
            'kelasList',
            'selectedJadwalId',
            'selectedKelasId',
            'statusKelulusan',
            'selectedJadwal',
            'pesertaList',
            'stats'
        ));
    }

    public function cetakLaporanNilai(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalId = $request->input('jadwal_id');
        $kelasId = $request->input('kelas_id');
        $statusKelulusan = $request->input('status_kelulusan');

        $jadwal = JadwalUjian::with('bankSoal.mataPelajaran')->findOrFail($jadwalId);
        $kkm = $jadwal->bankSoal->kkm ?? 75;

        $query = UjianPeserta::with(['siswa.kelas'])
            ->where('jadwal_ujian_id', $jadwalId);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $allPeserta = $query->orderBy('total_nilai', 'desc')->get();

        if ($statusKelulusan === 'tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai >= $kkm);
        } elseif ($statusKelulusan === 'belum_tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai < $kkm);
        } else {
            $pesertaList = $allPeserta;
        }

        $filterKelas = $kelasId ? Kelas::find($kelasId) : null;

        $totalPeserta = $allPeserta->count();
        $nilaiTertinggi = $totalPeserta > 0 ? $allPeserta->max('total_nilai') : 0;
        $nilaiTerendah = $totalPeserta > 0 ? $allPeserta->min('total_nilai') : 0;
        $nilaiRataRata = $totalPeserta > 0 ? round($allPeserta->avg('total_nilai'), 2) : 0;
        $jumlahTuntas = $allPeserta->filter(fn($p) => $p->total_nilai >= $kkm)->count();
        $persenTuntas = $totalPeserta > 0 ? round(($jumlahTuntas / $totalPeserta) * 100, 1) : 0;

        $stats = [
            'total' => $totalPeserta,
            'tertinggi' => $nilaiTertinggi,
            'terendah' => $nilaiTerendah,
            'rata_rata' => $nilaiRataRata,
            'tuntas' => $jumlahTuntas,
            'persen_tuntas' => $persenTuntas,
            'kkm' => $kkm,
        ];

        return view('admin.cetak_laporan_nilai', compact('sekolah', 'jadwal', 'filterKelas', 'pesertaList', 'stats'));
    }

    public function exportLaporanNilaiXlsx(Request $request)
    {
        $jadwalId = $request->input('jadwal_id');
        $kelasId = $request->input('kelas_id');
        $statusKelulusan = $request->input('status_kelulusan');

        $jadwal = JadwalUjian::with('bankSoal.mataPelajaran')->findOrFail($jadwalId);
        $kkm = $jadwal->bankSoal->kkm ?? 75;

        $query = UjianPeserta::with(['siswa.kelas'])
            ->where('jadwal_ujian_id', $jadwalId);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $allPeserta = $query->orderBy('total_nilai', 'desc')->get();

        if ($statusKelulusan === 'tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai >= $kkm);
        } elseif ($statusKelulusan === 'belum_tuntas') {
            $pesertaList = $allPeserta->filter(fn($p) => $p->total_nilai < $kkm);
        } else {
            $pesertaList = $allPeserta;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Nilai Ujian');

        $headers = [
            'A1' => 'Peringkat',
            'B1' => 'Nomor Peserta',
            'C1' => 'NISN',
            'D1' => 'Nama Siswa',
            'E1' => 'Kelas / Rombel',
            'F1' => 'Benar',
            'G1' => 'Salah',
            'H1' => 'Kosong',
            'I1' => 'Skor PG/BS',
            'J1' => 'Skor Uraian',
            'K1' => 'Nilai Akhir',
            'L1' => 'KKM',
            'M1' => 'Status Kelulusan'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($pesertaList as $idx => $p) {
            $status = $p->total_nilai >= $kkm ? 'TUNTAS' : 'BELUM TUNTAS';

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
            $sheet->setCellValue('L' . $rowIdx, $kkm);
            $sheet->setCellValue('M' . $rowIdx, $status);
            $rowIdx++;
        }

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Daftar_Nilai_' . Str::slug($jadwal->nama_ujian) . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    // ==========================================
    // ANALISIS BUTIR SOAL (ITEM ANALYSIS)
    // ==========================================

    public function analisisButirSoal(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with('bankSoal.mataPelajaran')->orderBy('id', 'desc')->get();
        $selectedJadwalId = $request->input('jadwal_id', $jadwalList->first()?->id);

        $selectedJadwal = $selectedJadwalId ? JadwalUjian::with(['bankSoal.soalButir'])->find($selectedJadwalId) : null;
        $analisisItems = [];
        $totalPeserta = 0;

        if ($selectedJadwal && $selectedJadwal->bankSoal) {
            $pesertaList = UjianPeserta::where('jadwal_ujian_id', $selectedJadwalId)
                ->whereIn('status', ['selesai', 'berlangsung'])
                ->orderBy('total_nilai', 'desc')
                ->get();

            $totalPeserta = $pesertaList->count();
            $soalList = $selectedJadwal->bankSoal->soalButir()->orderBy('nomor_urut')->get();

            // Bagi kelompok atas dan bawah untuk Daya Pembeda (Top 30% vs Bottom 30%)
            $kCount = max(1, (int) round($totalPeserta * 0.3));
            $kelompokAtasIds = $pesertaList->take($kCount)->pluck('id')->toArray();
            $kelompokBawahIds = $pesertaList->reverse()->take($kCount)->pluck('id')->toArray();

            foreach ($soalList as $soal) {
                $jawabans = UjianJawaban::whereIn('ujian_peserta_id', $pesertaList->pluck('id'))
                    ->where('soal_butir_id', $soal->id)
                    ->get();

                $benarCount = $jawabans->where('is_benar', true)->count();
                $salahCount = $jawabans->where('is_benar', false)->whereNotNull('jawaban_siswa')->count();
                $kosongCount = $totalPeserta - ($benarCount + $salahCount);

                // Frekuensi Opsi (Distraktor Analysis)
                $opsiCount = [
                    'A' => $jawabans->where('jawaban_siswa', 'A')->count(),
                    'B' => $jawabans->where('jawaban_siswa', 'B')->count(),
                    'C' => $jawabans->where('jawaban_siswa', 'C')->count(),
                    'D' => $jawabans->where('jawaban_siswa', 'D')->count(),
                    'E' => $jawabans->where('jawaban_siswa', 'E')->count(),
                ];

                // Tingkat Kesukaran P = Benar / Total Peserta
                $pIndex = $totalPeserta > 0 ? round($benarCount / $totalPeserta, 2) : 0;
                if ($pIndex >= 0.70) {
                    $kategoriKesukaran = 'Mudah';
                    $badgeKesukaran = 'badge-success';
                } elseif ($pIndex >= 0.30) {
                    $kategoriKesukaran = 'Sedang';
                    $badgeKesukaran = 'badge-info';
                } else {
                    $kategoriKesukaran = 'Sukar';
                    $badgeKesukaran = 'badge-danger';
                }

                // Daya Pembeda D = (Benar Atas - Benar Bawah) / N Kelompok
                $benarAtas = $jawabans->whereIn('ujian_peserta_id', $kelompokAtasIds)->where('is_benar', true)->count();
                $benarBawah = $jawabans->whereIn('ujian_peserta_id', $kelompokBawahIds)->where('is_benar', true)->count();
                $dIndex = $kCount > 0 ? round(($benarAtas - $benarBawah) / $kCount, 2) : 0;

                if ($dIndex >= 0.40) {
                    $kategoriPembeda = 'Sangat Baik';
                    $rekomendasi = 'Diterima (Sangat Baik)';
                    $badgePembeda = 'badge-success';
                } elseif ($dIndex >= 0.30) {
                    $kategoriPembeda = 'Baik';
                    $rekomendasi = 'Diterima';
                    $badgePembeda = 'badge-info';
                } elseif ($dIndex >= 0.20) {
                    $kategoriPembeda = 'Cukup';
                    $rekomendasi = 'Perlu Revisi';
                    $badgePembeda = 'badge-warning';
                } else {
                    $kategoriPembeda = 'Kurang / Jelek';
                    $rekomendasi = 'Ditolak / Dibuang';
                    $badgePembeda = 'badge-danger';
                }

                $analisisItems[] = [
                    'soal' => $soal,
                    'benar' => $benarCount,
                    'salah' => $salahCount,
                    'kosong' => max(0, $kosongCount),
                    'opsi' => $opsiCount,
                    'p_index' => $pIndex,
                    'kategori_kesukaran' => $kategoriKesukaran,
                    'badge_kesukaran' => $badgeKesukaran,
                    'd_index' => $dIndex,
                    'kategori_pembeda' => $kategoriPembeda,
                    'badge_pembeda' => $badgePembeda,
                    'rekomendasi' => $rekomendasi,
                ];
            }
        }

        return view('admin.analisis_soal', compact(
            'sekolah',
            'jadwalList',
            'selectedJadwalId',
            'selectedJadwal',
            'analisisItems',
            'totalPeserta'
        ));
    }

    public function cetakAnalisisSoal(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalId = $request->input('jadwal_id');
        $jadwal = JadwalUjian::with(['bankSoal.soalButir', 'bankSoal.mataPelajaran'])->findOrFail($jadwalId);

        $pesertaList = UjianPeserta::where('jadwal_ujian_id', $jadwalId)
            ->whereIn('status', ['selesai', 'berlangsung'])
            ->orderBy('total_nilai', 'desc')
            ->get();

        $totalPeserta = $pesertaList->count();
        $soalList = $jadwal->bankSoal->soalButir()->orderBy('nomor_urut')->get();

        $kCount = max(1, (int) round($totalPeserta * 0.3));
        $kelompokAtasIds = $pesertaList->take($kCount)->pluck('id')->toArray();
        $kelompokBawahIds = $pesertaList->reverse()->take($kCount)->pluck('id')->toArray();

        $analisisItems = [];
        foreach ($soalList as $soal) {
            $jawabans = UjianJawaban::whereIn('ujian_peserta_id', $pesertaList->pluck('id'))
                ->where('soal_butir_id', $soal->id)
                ->get();

            $benarCount = $jawabans->where('is_benar', true)->count();
            $salahCount = $jawabans->where('is_benar', false)->whereNotNull('jawaban_siswa')->count();
            $kosongCount = max(0, $totalPeserta - ($benarCount + $salahCount));

            $pIndex = $totalPeserta > 0 ? round($benarCount / $totalPeserta, 2) : 0;
            $kategoriKesukaran = $pIndex >= 0.70 ? 'Mudah' : ($pIndex >= 0.30 ? 'Sedang' : 'Sukar');

            $benarAtas = $jawabans->whereIn('ujian_peserta_id', $kelompokAtasIds)->where('is_benar', true)->count();
            $benarBawah = $jawabans->whereIn('ujian_peserta_id', $kelompokBawahIds)->where('is_benar', true)->count();
            $dIndex = $kCount > 0 ? round(($benarAtas - $benarBawah) / $kCount, 2) : 0;
            $rekomendasi = $dIndex >= 0.40 ? 'Diterima (Sangat Baik)' : ($dIndex >= 0.30 ? 'Diterima' : ($dIndex >= 0.20 ? 'Perlu Revisi' : 'Dibuang / Diganti'));

            $analisisItems[] = [
                'nomor' => $soal->nomor_urut,
                'tipe' => strtoupper($soal->tipe_soal),
                'kunci' => $soal->kunci_jawaban,
                'benar' => $benarCount,
                'salah' => $salahCount,
                'kosong' => $kosongCount,
                'p_index' => $pIndex,
                'kesukaran' => $kategoriKesukaran,
                'd_index' => $dIndex,
                'rekomendasi' => $rekomendasi,
            ];
        }

        return view('admin.cetak_analisis_soal', compact('sekolah', 'jadwal', 'totalPeserta', 'analisisItems'));
    }

    public function exportAnalisisSoalXlsx(Request $request)
    {
        $jadwalId = $request->input('jadwal_id');
        $jadwal = JadwalUjian::with(['bankSoal.soalButir', 'bankSoal.mataPelajaran'])->findOrFail($jadwalId);

        $pesertaList = UjianPeserta::where('jadwal_ujian_id', $jadwalId)
            ->whereIn('status', ['selesai', 'berlangsung'])
            ->orderBy('total_nilai', 'desc')
            ->get();

        $totalPeserta = $pesertaList->count();
        $soalList = $jadwal->bankSoal->soalButir()->orderBy('nomor_urut')->get();

        $kCount = max(1, (int) round($totalPeserta * 0.3));
        $kelompokAtasIds = $pesertaList->take($kCount)->pluck('id')->toArray();
        $kelompokBawahIds = $pesertaList->reverse()->take($kCount)->pluck('id')->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Analisis Butir Soal');

        $headers = [
            'A1' => 'No. Butir',
            'B1' => 'Tipe Soal',
            'C1' => 'Kunci',
            'D1' => 'Benar (N)',
            'E1' => 'Salah (N)',
            'F1' => 'Kosong (N)',
            'G1' => 'Tingkat Kesukaran (P)',
            'H1' => 'Kategori Kesukaran',
            'I1' => 'Daya Pembeda (D)',
            'J1' => 'Rekomendasi Butir'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $rowIdx = 2;
        foreach ($soalList as $soal) {
            $jawabans = UjianJawaban::whereIn('ujian_peserta_id', $pesertaList->pluck('id'))
                ->where('soal_butir_id', $soal->id)
                ->get();

            $benar = $jawabans->where('is_benar', true)->count();
            $salah = $jawabans->where('is_benar', false)->whereNotNull('jawaban_siswa')->count();
            $kosong = max(0, $totalPeserta - ($benar + $salah));

            $pIndex = $totalPeserta > 0 ? round($benar / $totalPeserta, 2) : 0;
            $kategoriKesukaran = $pIndex >= 0.70 ? 'Mudah' : ($pIndex >= 0.30 ? 'Sedang' : 'Sukar');

            $benarAtas = $jawabans->whereIn('ujian_peserta_id', $kelompokAtasIds)->where('is_benar', true)->count();
            $benarBawah = $jawabans->whereIn('ujian_peserta_id', $kelompokBawahIds)->where('is_benar', true)->count();
            $dIndex = $kCount > 0 ? round(($benarAtas - $benarBawah) / $kCount, 2) : 0;
            $rekomendasi = $dIndex >= 0.40 ? 'Diterima (Sangat Baik)' : ($dIndex >= 0.30 ? 'Diterima' : ($dIndex >= 0.20 ? 'Perlu Revisi' : 'Dibuang / Diganti'));

            $sheet->setCellValue('A' . $rowIdx, $soal->nomor_urut);
            $sheet->setCellValue('B' . $rowIdx, strtoupper($soal->tipe_soal));
            $sheet->setCellValue('C' . $rowIdx, $soal->kunci_jawaban);
            $sheet->setCellValue('D' . $rowIdx, $benar);
            $sheet->setCellValue('E' . $rowIdx, $salah);
            $sheet->setCellValue('F' . $rowIdx, $kosong);
            $sheet->setCellValue('G' . $rowIdx, $pIndex);
            $sheet->setCellValue('H' . $rowIdx, $kategoriKesukaran);
            $sheet->setCellValue('I' . $rowIdx, $dIndex);
            $sheet->setCellValue('J' . $rowIdx, $rekomendasi);
            $rowIdx++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Analisis_Soal_' . Str::slug($jadwal->nama_ujian) . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    // ==========================================
    // MONITOR & PUSAT RESET UJIAN SISWA
    // ==========================================

    public function monitorResetUjian(Request $request)
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $jadwalList = JadwalUjian::with('bankSoal.mataPelajaran')->orderBy('id', 'desc')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $selectedJadwalId = $request->input('jadwal_id', $jadwalList->first()?->id);
        $selectedKelasId = $request->input('kelas_id');
        $statusFilter = $request->input('status'); // 'belum', 'berlangsung', 'selesai'

        $selectedJadwal = $selectedJadwalId ? JadwalUjian::with('bankSoal')->find($selectedJadwalId) : null;

        $pesertaQuery = UjianPeserta::with(['siswa.kelas', 'jadwalUjian.bankSoal'])
            ->where('jadwal_ujian_id', $selectedJadwalId);

        if ($selectedKelasId) {
            $pesertaQuery->whereHas('siswa', function ($q) use ($selectedKelasId) {
                $q->where('kelas_id', $selectedKelasId);
            });
        }

        if ($statusFilter) {
            $pesertaQuery->where('status', $statusFilter);
        }

        $pesertaList = $pesertaQuery->orderBy('status', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.monitor_reset_ujian', compact(
            'sekolah',
            'jadwalList',
            'kelasList',
            'selectedJadwalId',
            'selectedKelasId',
            'statusFilter',
            'selectedJadwal',
            'pesertaList'
        ));
    }

    public function tambahWaktuPeserta(Request $request, $pesertaId)
    {
        $peserta = UjianPeserta::with('siswa')->findOrFail($pesertaId);
        $menit = (int) $request->input('menit', 15);
        $tambahanDetik = $menit * 60;

        $peserta->sisa_detik = max(0, $peserta->sisa_detik) + $tambahanDetik;
        $peserta->save();

        return back()->with('success', "Waktu ujian peserta {$peserta->siswa->nama_lengkap} berhasil ditambah {$menit} menit.");
    }

    public function resetTesPeserta($pesertaId)
    {
        $peserta = UjianPeserta::with(['siswa', 'jadwalUjian'])->findOrFail($pesertaId);

        // Hapus seluruh jawaban peserta
        UjianJawaban::where('ujian_peserta_id', $pesertaId)->delete();

        // Kembalikan peserta ke kondisi awal sebelum ujian
        $durasiDetik = ($peserta->jadwalUjian->durasi_menit ?? 60) * 60;
        $peserta->update([
            'status' => 'belum',
            'waktu_mulai' => null,
            'waktu_selesai' => null,
            'sisa_detik' => $durasiDetik,
            'nilai_pg' => 0.00,
            'nilai_uraian' => 0.00,
            'total_nilai' => 0.00,
            'jawaban_benar' => 0,
            'jawaban_salah' => 0,
            'jawaban_kosong' => 0,
            'ip_pengerjaan' => null,
        ]);

        // Reset status login siswa
        $peserta->siswa->update([
            'status_login' => 'offline',
            'ip_address' => null,
        ]);

        return back()->with('success', "Ujian peserta {$peserta->siswa->nama_lengkap} berhasil di-reset total. Siswa dapat login dan mengerjakan ulang dari awal.");
    }

    public function forceFinishPeserta($pesertaId)
    {
        $peserta = UjianPeserta::with(['siswa', 'jadwalUjian.bankSoal'])->findOrFail($pesertaId);

        // Hitung nilai akhir otomatis
        $soalList = SoalButir::where('bank_soal_id', $peserta->jadwalUjian->bank_soal_id)->get();
        $jawabans = UjianJawaban::where('ujian_peserta_id', $pesertaId)->get();

        $benar = 0;
        $salah = 0;
        $kosong = 0;
        $nilaiPg = 0.0;

        foreach ($soalList as $s) {
            $jw = $jawabans->firstWhere('soal_butir_id', $s->id);
            if (!$jw || empty($jw->jawaban_siswa)) {
                $kosong++;
            } elseif ($jw->is_benar) {
                $benar++;
                $nilaiPg += (float) $s->bobot_nilai;
            } else {
                $salah++;
            }
        }

        $peserta->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'jawaban_benar' => $benar,
            'jawaban_salah' => $salah,
            'jawaban_kosong' => $kosong,
            'nilai_pg' => $nilaiPg,
            'total_nilai' => $nilaiPg + (float)$peserta->nilai_uraian,
        ]);

        $peserta->siswa->update(['status_login' => 'offline']);

        return back()->with('success', "Ujian peserta {$peserta->siswa->nama_lengkap} berhasil diselesaikan paksa oleh Admin.");
    }

    // ==========================================
    // BACKUP DATABASE DAN BACKUP BANK SOAL (FASE 9)
    // ==========================================

    public function backupIndex()
    {
        $sekolah = MasterSekolah::first() ?? new MasterSekolah();
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = File::files($backupDir);
        $backupList = [];

        foreach ($files as $file) {
            $backupList[] = [
                'name' => $file->getFilename(),
                'size' => round($file->getSize() / 1024, 2) . ' KB',
                'date' => date('d-m-Y H:i:s', $file->getMTime()),
            ];
        }

        // Urutkan backup terbaru di atas
        usort($backupList, fn($a, $b) => strcmp($b['date'], $a['date']));

        $bankSoalList = BankSoal::with('mataPelajaran')->withCount('soalButir')->get();

        return view('admin.backup', compact('sekolah', 'backupList', 'bankSoalList'));
    }

    public function backupDatabase()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'cbt_database_' . date('Y-m-d_His') . '.sql';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Generate dump SQL dari seluruh tabel
        $tables = [
            'master_sekolah',
            'kelas',
            'mata_pelajaran',
            'users',
            'siswa',
            'bank_soal',
            'soal_butir',
            'jadwal_ujian',
            'jadwal_ujian_kelas',
            'ujian_peserta',
            'ujian_jawaban',
            'petugas_ujian',
            'berita_acara',
            'daftar_hadir',
        ];

        $sqlContent = "-- CBT SMKN 1 Kupang Barat Database Backup\n";
        $sqlContent .= "-- Tanggal Backup: " . date('Y-m-d H:i:s') . "\n";
        $sqlContent .= "-- Dikeluarkan oleh: Administrator (Vicky Koroh, S.Pd., Gr.)\n\n";

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $rows = DB::table($table)->get();
            $sqlContent .= "-- Struktur & Data Tabel: `{$table}`\n";

            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $keys = array_map(fn($k) => "`{$k}`", array_keys($rowArray));
                    $values = array_map(function ($val) {
                        if (is_null($val)) return 'NULL';
                        return "'" . addslashes((string) $val) . "'";
                    }, array_values($rowArray));

                    $sqlContent .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
            }
            $sqlContent .= "\n";
        }

        File::put($filePath, $sqlContent);

        return back()->with('success', "Cadangan database berhasil dibuat dengan nama {$filename} (" . round(filesize($filePath) / 1024, 2) . " KB).");
    }

    public function downloadBackupDatabase($filename)
    {
        $cleanName = basename($filename);
        $filePath = storage_path('app/backups/' . $cleanName);

        if (!File::exists($filePath)) {
            return back()->with('error', 'Berkas cadangan tidak ditemukan di server.');
        }

        return response()->download($filePath, $cleanName);
    }

    public function hapusBackupDatabase($filename)
    {
        $cleanName = basename($filename);
        $filePath = storage_path('app/backups/' . $cleanName);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return back()->with('success', "Berkas cadangan {$cleanName} berhasil dihapus.");
        }

        return back()->with('error', 'Berkas cadangan tidak ditemukan.');
    }

    public function exportBankSoalJson($id)
    {
        $bank = BankSoal::with(['mataPelajaran', 'soalButir'])->findOrFail($id);

        $exportData = [
            'app' => 'CBT SMKN 1 Kupang Barat',
            'version' => '2.0',
            'exported_at' => now()->toIso8601String(),
            'bank_soal' => [
                'kode_bank' => $bank->kode_bank,
                'nama_bank' => $bank->nama_bank,
                'kkm' => $bank->kkm,
                'standar_preset' => $bank->standar_preset,
                'keterangan' => $bank->keterangan,
                'acak_soal' => $bank->acak_soal,
                'acak_opsi' => $bank->acak_opsi,
                'mapel' => [
                    'nama_mapel' => $bank->mataPelajaran->nama_mapel ?? null,
                    'kode_mapel' => $bank->mataPelajaran->kode_mapel ?? null,
                    'kelompok' => $bank->mataPelajaran->kelompok ?? null,
                    'jurusan' => $bank->mataPelajaran->jurusan ?? null,
                ],
            ],
            'butir_soal' => $bank->soalButir->map(function ($s) {
                return [
                    'nomor_urut' => $s->nomor_urut,
                    'tipe_soal' => $s->tipe_soal,
                    'pertanyaan' => $s->pertanyaan,
                    'gambar' => $s->gambar,
                    'pilihan_a' => $s->pilihan_a,
                    'pilihan_b' => $s->pilihan_b,
                    'pilihan_c' => $s->pilihan_c,
                    'pilihan_d' => $s->pilihan_d,
                    'pilihan_e' => $s->pilihan_e,
                    'kunci_jawaban' => $s->kunci_jawaban,
                    'bobot_nilai' => $s->bobot_nilai,
                    'pembahasan' => $s->pembahasan,
                ];
            })->toArray(),
        ];

        $jsonContent = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fileName = 'BankSoal_' . Str::slug($bank->kode_bank ?: $bank->nama_bank) . '_' . date('Ymd_His') . '.json';

        return response($jsonContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function importBankSoalJson(Request $request)
    {
        $request->validate([
            'file_json' => 'required|file|max:20480', // max 20MB
        ]);

        $content = file_get_contents($request->file('file_json')->getRealPath());
        $data = json_decode($content, true);

        if (!is_array($data) || !isset($data['bank_soal'])) {
            return back()->with('error', 'Format berkas JSON tidak valid atau bukan berkas cadangan Bank Soal resmi CBT.');
        }

        $bsInfo = $data['bank_soal'];
        $mapelInfo = $bsInfo['mapel'] ?? [];

        // Cari atau buat mata pelajaran terkait
        $mapel = null;
        if (!empty($mapelInfo['nama_mapel'])) {
            $mapel = MataPelajaran::where('nama_mapel', $mapelInfo['nama_mapel'])->first();
            if (!$mapel) {
                $mapel = MataPelajaran::create([
                    'nama_mapel' => $mapelInfo['nama_mapel'],
                    'kode_mapel' => $mapelInfo['kode_mapel'] ?? Str::slug($mapelInfo['nama_mapel']),
                    'kelompok' => $mapelInfo['kelompok'] ?? 'Umum',
                    'jurusan' => $mapelInfo['jurusan'] ?? 'Semua',
                ]);
            }
        }

        if (!$mapel) {
            $mapel = MataPelajaran::first();
        }

        // Generate kode bank unik jika kode sudah ada
        $baseKode = $bsInfo['kode_bank'] ?: 'BS-RESTORE';
        $uniqueKode = $baseKode;
        $counter = 1;
        while (BankSoal::where('kode_bank', $uniqueKode)->exists()) {
            $uniqueKode = $baseKode . '-RESTORE-' . $counter++;
        }

        $newBank = BankSoal::create([
            'mata_pelajaran_id' => $mapel?->id ?? 1,
            'guru_id' => auth()->id() ?? 1,
            'kode_bank' => $uniqueKode,
            'nama_bank' => ($bsInfo['nama_bank'] ?? 'Bank Soal Restore') . ' (Restored)',
            'kkm' => $bsInfo['kkm'] ?? 75,
            'standar_preset' => $bsInfo['standar_preset'] ?? 40,
            'keterangan' => $bsInfo['keterangan'] ?? 'Dipulihkan dari berkas cadangan JSON',
            'acak_soal' => $bsInfo['acak_soal'] ?? true,
            'acak_opsi' => $bsInfo['acak_opsi'] ?? true,
            'status' => 'aktif',
        ]);

        $butirCount = 0;
        if (isset($data['butir_soal']) && is_array($data['butir_soal'])) {
            foreach ($data['butir_soal'] as $item) {
                SoalButir::create([
                    'bank_soal_id' => $newBank->id,
                    'nomor_urut' => $item['nomor_urut'] ?? ($butirCount + 1),
                    'jenis_soal' => $item['jenis_soal'] ?? ($item['tipe_soal'] ?? 'pg'),
                    'konten_soal' => $item['konten_soal'] ?? ($item['pertanyaan'] ?? ''),
                    'opsi_a' => $item['opsi_a'] ?? ($item['pilihan_a'] ?? null),
                    'opsi_b' => $item['opsi_b'] ?? ($item['pilihan_b'] ?? null),
                    'opsi_c' => $item['opsi_c'] ?? ($item['pilihan_c'] ?? null),
                    'opsi_d' => $item['opsi_d'] ?? ($item['pilihan_d'] ?? null),
                    'opsi_e' => $item['opsi_e'] ?? ($item['pilihan_e'] ?? null),
                    'kunci_jawaban' => $item['kunci_jawaban'] ?? 'A',
                    'bobot' => $item['bobot'] ?? ($item['bobot_nilai'] ?? 2.5),
                ]);
                $butirCount++;
            }
        }

        return back()->with('success', "Bank Soal '{$newBank->nama_bank}' berhasil dipulihkan dengan {$butirCount} butir soal lengkap!");
    }
}
