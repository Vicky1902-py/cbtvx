<?php

namespace App\Console\Commands;

use App\Models\JadwalUjian;
use App\Models\UjianPeserta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CbtMonitorCommand extends Command
{
    protected $signature = 'cbt:monitor
        {--check-http : Uji koneksi HTTP ke server CBT lokal}
        {--url=http://127.0.0.1:8000/up : URL health-check target}
        {--heal : Jalankan pemulihan aman otomatis (WAL passive checkpoint & cek folder)}
        {--reset-offset : Reset posisi pemindaian laravel.log ke akhir berkas saat ini}
        {--json : Tampilkan hasil pemeriksaan dalam format JSON}';

    protected $description = 'Memantau kesehatan aplikasi CBT saat ujian (HTTP, SQLite WAL, log error, & telemetri peserta) tanpa mengganggu sesi berjalan.';

    public function handle(): int
    {
        $doHeal = (bool) $this->option('heal');
        $checkHttp = (bool) $this->option('check-http');
        $asJson = (bool) $this->option('json');
        $issues = [];
        $healedActions = [];
        $httpDown = false;

        // 1. Pastikan direktori penting tersedia & bisa ditulis
        $requiredDirs = [
            storage_path('framework/sessions'),
            storage_path('logs'),
            public_path('uploads/soal'),
        ];
        foreach ($requiredDirs as $dir) {
            if (!File::isDirectory($dir)) {
                if ($doHeal) {
                    File::makeDirectory($dir, 0755, true);
                    $healedActions[] = "Membuat direktori yang hilang: {$dir}";
                } else {
                    $issues[] = "Direktori penting tidak ditemukan: {$dir}";
                }
            }
        }

        // 2. Cek Kesehatan Database & SQLite WAL
        $dbOk = true;
        $dbIntegrity = 'skipped';
        $walSizeBytes = 0;
        $driver = DB::getDriverName();

        try {
            DB::select('SELECT 1');
            if ($driver === 'sqlite') {
                $dbPath = DB::connection()->getDatabaseName();
                $dbIntegrity = (string) (DB::selectOne('PRAGMA integrity_check')->integrity_check ?? 'ok');
                if (strtolower($dbIntegrity) !== 'ok') {
                    $dbOk = false;
                    $issues[] = "SQLite integrity_check bermasalah: {$dbIntegrity}";
                }

                if ($dbPath && $dbPath !== ':memory:' && file_exists($dbPath . '-wal')) {
                    $walSizeBytes = (int) filesize($dbPath . '-wal');
                    if ($doHeal && $walSizeBytes > 10 * 1024 * 1024) {
                        DB::statement('PRAGMA wal_checkpoint(PASSIVE)');
                        $healedActions[] = 'Menjalankan WAL PASSIVE checkpoint (' . round($walSizeBytes / 1048576, 2) . ' MB)';
                        clearstatcache(true, $dbPath . '-wal');
                        $walSizeBytes = file_exists($dbPath . '-wal') ? (int) filesize($dbPath . '-wal') : 0;
                    }
                }
            }
        } catch (\Throwable $e) {
            $dbOk = false;
            $issues[] = 'Koneksi database gagal: ' . $e->getMessage();
        }

        // 3. Cek Telemetri Ujian
        $telemetry = [
            'jadwal_aktif' => 0,
            'peserta_berlangsung' => 0,
            'peserta_selesai' => 0,
            'peserta_pelanggaran' => 0,
        ];
        if ($dbOk) {
            try {
                $telemetry['jadwal_aktif'] = JadwalUjian::where('status', 'aktif')->count();
                $telemetry['peserta_berlangsung'] = UjianPeserta::where('status', 'berlangsung')->count();
                $telemetry['peserta_selesai'] = UjianPeserta::where('status', 'selesai')->count();
                $telemetry['peserta_pelanggaran'] = UjianPeserta::where('jumlah_pelanggaran', '>', 0)->count();
            } catch (\Throwable $e) {
                $issues[] = 'Gagal membaca telemetri ujian: ' . $e->getMessage();
            }
        }

        // 4. Cek Sisa Ruang Disk
        $diskFreeMb = round((@disk_free_space(base_path()) ?: 0) / 1048576, 1);
        if ($diskFreeMb > 0 && $diskFreeMb < 500) {
            $issues[] = "Sisa ruang disk server menipis ({$diskFreeMb} MB tersisa).";
        }

        // 5. Pindai Error Baru di storage/logs/laravel.log
        $logPath = storage_path('logs/laravel.log');
        $offsetFile = storage_path('app/monitor_log_offset.txt');
        $newErrors = [];

        if (file_exists($logPath)) {
            $fileSize = (int) filesize($logPath);
            if ($this->option('reset-offset')) {
                File::put($offsetFile, (string) $fileSize);
            } else {
                $lastOffset = file_exists($offsetFile) ? (int) trim((string) @file_get_contents($offsetFile)) : 0;
                if ($lastOffset > $fileSize || $lastOffset < 0) {
                    $lastOffset = 0;
                }

                if ($fileSize > $lastOffset) {
                    $fh = @fopen($logPath, 'rb');
                    if ($fh) {
                        fseek($fh, $lastOffset);
                        $chunk = fread($fh, min(1048576, $fileSize - $lastOffset));
                        fclose($fh);

                        if ($chunk !== false && $chunk !== '') {
                            foreach (preg_split('/\r\n|\r|\n/', $chunk) as $line) {
                                if (preg_match('/\.(ERROR|CRITICAL|EMERGENCY|ALERT):|database is locked/i', $line)) {
                                    $newErrors[] = mb_substr(trim($line), 0, 260);
                                }
                            }
                        }
                        File::put($offsetFile, (string) $fileSize);
                    }
                }
            }
        }

        if (count($newErrors) > 0) {
            $issues[] = 'Terdeteksi ' . count($newErrors) . ' error baru pada laravel.log.';
        }

        // 6. Cek HTTP Server (opsional via --check-http)
        $httpStatus = null;
        $httpLatencyMs = null;
        if ($checkHttp) {
            $url = (string) $this->option('url');
            $ctx = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 3,
                    'ignore_errors' => true,
                ],
            ]);
            $t0 = microtime(true);
            $body = @file_get_contents($url, false, $ctx);
            $httpLatencyMs = round((microtime(true) - $t0) * 1000, 1);

            if ($body === false) {
                $httpDown = true;
                $httpStatus = 'UNREACHABLE';
                $issues[] = "Server HTTP tidak merespons pada {$url}";
            } else {
                $statusLine = $http_response_header[0] ?? 'HTTP/1.1 200 OK';
                $httpStatus = $statusLine;
                if (!preg_match('/\s(200|302)\s/', $statusLine)) {
                    $issues[] = "Status HTTP tidak normal: {$statusLine}";
                }
            }
        }

        $overallStatus = $httpDown ? 'SERVER_DOWN' : (count($issues) > 0 ? 'WARNING' : 'HEALTHY');

        $payload = [
            'timestamp' => now()->toDateTimeString(),
            'status' => $overallStatus,
            'database' => [
                'ok' => $dbOk,
                'driver' => $driver,
                'integrity' => $dbIntegrity,
                'wal_kb' => round($walSizeBytes / 1024, 1),
            ],
            'http' => $checkHttp ? [
                'ok' => !$httpDown,
                'status' => $httpStatus,
                'latency_ms' => $httpLatencyMs,
            ] : null,
            'disk_free_mb' => $diskFreeMb,
            'telemetry' => $telemetry,
            'new_error_count' => count($newErrors),
            'new_errors' => array_slice($newErrors, -5),
            'healed_actions' => $healedActions,
            'issues' => $issues,
        ];

        if ($asJson) {
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->info("[{$payload['timestamp']}] STATUS: {$overallStatus}");
            $this->line("  DB ({$driver}): " . ($dbOk ? 'OK' : 'ERROR') . " | WAL: {$payload['database']['wal_kb']} KB | Disk Bebas: {$diskFreeMb} MB");
            if ($checkHttp) {
                $this->line("  HTTP: {$httpStatus} ({$httpLatencyMs} ms)");
            }
            $this->line("  Ujian Aktif: {$telemetry['jadwal_aktif']} | Sedang Tes: {$telemetry['peserta_berlangsung']} | Selesai: {$telemetry['peserta_selesai']} | Pelanggaran: {$telemetry['peserta_pelanggaran']}");
            foreach ($healedActions as $act) {
                $this->comment("  [AUTO-HEAL] {$act}");
            }
            foreach ($issues as $iss) {
                $this->error("  [ISSUE] {$iss}");
            }
            foreach (array_slice($newErrors, -3) as $errLine) {
                $this->warn("  -> {$errLine}");
            }
        }

        if ($httpDown) {
            return 2;
        }
        return count($issues) > 0 ? 1 : 0;
    }
}
