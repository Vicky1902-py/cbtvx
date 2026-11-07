<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;

// Halaman Login & Otentikasi
Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Group Peserta Ujian (Siswa)
Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/konfirmasi/{id}', [SiswaController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('/mulai-ujian/{id}', [SiswaController::class, 'mulaiUjian'])->name('mulai');
    Route::get('/ujian/{id}', [SiswaController::class, 'kerjakan'])->name('ujian');
    Route::post('/ujian/{id}/simpan-jawaban', [SiswaController::class, 'simpanJawaban'])->name('simpan');
    Route::post('/ujian/{id}/selesai', [SiswaController::class, 'selesaiUjian'])->name('selesai');
});

// Group Administrator & Proktor
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');

    // Siswa CRUD & Import/Export
    Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
    Route::post('/siswa', [AdminController::class, 'simpanSiswa'])->name('siswa.simpan');
    Route::post('/siswa/{id}/update', [AdminController::class, 'updateSiswa'])->name('siswa.update');
    Route::post('/siswa/{id}/hapus', [AdminController::class, 'hapusSiswa'])->name('siswa.hapus');
    Route::post('/siswa/import-xlsx', [AdminController::class, 'importSiswaXlsx'])->name('siswa.import_xlsx');
    Route::post('/siswa/upload-foto-zip', [AdminController::class, 'uploadFotoZip'])->name('siswa.upload_foto_zip');
    Route::get('/siswa/export-xlsx', [AdminController::class, 'exportSiswaXlsx'])->name('siswa.export_xlsx');
    Route::get('/siswa/template-xlsx', [AdminController::class, 'downloadTemplateSiswaXlsx'])->name('siswa.template_xlsx');

    // Kelas CRUD
    Route::get('/kelas', [AdminController::class, 'kelas'])->name('kelas');
    Route::post('/kelas', [AdminController::class, 'simpanKelas'])->name('kelas.simpan');
    Route::post('/kelas/{id}/update', [AdminController::class, 'updateKelas'])->name('kelas.update');
    Route::post('/kelas/{id}/hapus', [AdminController::class, 'hapusKelas'])->name('kelas.hapus');

    // Mata Pelajaran CRUD & Import/Export
    Route::get('/mapel', [AdminController::class, 'mapel'])->name('mapel');
    Route::post('/mapel', [AdminController::class, 'simpanMapel'])->name('mapel.simpan');
    Route::post('/mapel/{id}/update', [AdminController::class, 'updateMapel'])->name('mapel.update');
    Route::post('/mapel/{id}/hapus', [AdminController::class, 'hapusMapel'])->name('mapel.hapus');
    Route::get('/mapel/template-xlsx', [AdminController::class, 'downloadTemplateMapelXlsx'])->name('mapel.template_xlsx');
    Route::post('/mapel/import-xlsx', [AdminController::class, 'importMapelXlsx'])->name('mapel.import_xlsx');
    Route::get('/mapel/export-xlsx', [AdminController::class, 'exportMapelXlsx'])->name('mapel.export_xlsx');

    // User CRUD
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'simpanUser'])->name('users.simpan');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/hapus', [AdminController::class, 'hapusUser'])->name('users.hapus');

    // Bank Soal & Butir Soal (Roadmap Preset 25 & 40)
    Route::get('/bank-soal', [AdminController::class, 'bankSoal'])->name('bank_soal');
    Route::post('/bank-soal', [AdminController::class, 'simpanBankSoal'])->name('bank_soal.simpan');
    Route::post('/bank-soal/{id}/update', [AdminController::class, 'updateBankSoal'])->name('bank_soal.update');
    Route::get('/bank-soal/{id}', [AdminController::class, 'detailBankSoal'])->name('bank_soal.detail');
    Route::post('/bank-soal/{id}/butir', [AdminController::class, 'simpanButirSoal'])->name('bank_soal.butir.simpan');
    Route::post('/butir-soal/{id}/update', [AdminController::class, 'updateButirSoal'])->name('bank_soal.butir.update');
    Route::post('/butir-soal/{id}/hapus', [AdminController::class, 'hapusButirSoal'])->name('bank_soal.butir.hapus');
    Route::post('/bank-soal/{id}/hapus', [AdminController::class, 'hapusBankSoal'])->name('bank_soal.hapus');
    Route::get('/bank-soal/{id}/template-xlsx', [AdminController::class, 'downloadTemplateSoalXlsx'])->name('bank_soal.template_xlsx');
    Route::post('/bank-soal/{id}/import-xlsx', [AdminController::class, 'importSoalXlsx'])->name('bank_soal.import_xlsx');
    Route::post('/bank-soal/{id}/import-docx', [AdminController::class, 'importSoalDocx'])->name('bank_soal.import_docx');
    Route::get('/bank-soal/{id}/export-xlsx', [AdminController::class, 'exportSoalXlsx'])->name('bank_soal.export_xlsx');
    Route::get('/bank-soal/{id}/pratinjau', [AdminController::class, 'pratinjauBankSoal'])->name('bank_soal.pratinjau');

    // Jadwal Ujian CRUD & Kontrol Proktor
    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal', [AdminController::class, 'simpanJadwal'])->name('jadwal.simpan');
    Route::post('/jadwal/{id}/update', [AdminController::class, 'updateJadwal'])->name('jadwal.update');
    Route::post('/jadwal/{id}/hapus', [AdminController::class, 'hapusJadwal'])->name('jadwal.hapus');
    Route::post('/jadwal/{id}/generate-token', [AdminController::class, 'generateToken'])->name('generate_token');
    Route::post('/jadwal/{id}/force-finish', [AdminController::class, 'forceFinishAll'])->name('force_finish');

    // Reset Sesi
    Route::post('/reset-login/{id}', [AdminController::class, 'resetLogin'])->name('reset_login');
    Route::post('/reset-all-logins', [AdminController::class, 'resetAllLogins'])->name('reset_all_logins');

    // Rekap Nilai
    Route::get('/jadwal/{id}/rekap-nilai', [AdminController::class, 'rekapNilai'])->name('rekap_nilai');
    Route::get('/jadwal/{id}/export-nilai', [AdminController::class, 'exportRekapNilaiXlsx'])->name('export_nilai');

    // Dokumen Cetak, Daftar Hadir & Berita Acara
    Route::get('/cetak-kartu', [AdminController::class, 'cetakKartu'])->name('cetak_kartu');

    // Daftar Hadir Ujian (Index, Form Isi Per-Kelas/Sesi, Simpan, Cetak A4)
    Route::get('/daftar-hadir', [AdminController::class, 'daftarHadirIndex'])->name('daftar_hadir');
    Route::get('/daftar-hadir/{id}/isi', [AdminController::class, 'formDaftarHadir'])->name('daftar_hadir.form');
    Route::post('/daftar-hadir/{id}/simpan', [AdminController::class, 'simpanDaftarHadir'])->name('daftar_hadir.simpan');
    Route::get('/cetak-daftar-hadir/{id}', [AdminController::class, 'cetakDaftarHadir'])->name('cetak_daftar_hadir');

    // Berita Acara Ujian (Index, Form Isi Per-Kelas/Sesi, Simpan, Cetak A4)
    Route::get('/berita-acara', [AdminController::class, 'beritaAcaraIndex'])->name('berita_acara');
    Route::get('/berita-acara/{id}/isi', [AdminController::class, 'formBeritaAcara'])->name('berita_acara.form');
    Route::post('/berita-acara/{id}/simpan', [AdminController::class, 'simpanBeritaAcara'])->name('berita_acara.simpan');
    Route::get('/jadwal/{id}/berita-acara', [AdminController::class, 'formBeritaAcara']);
    Route::post('/jadwal/{id}/berita-acara', [AdminController::class, 'simpanBeritaAcara']);
    Route::get('/cetak-berita-acara/{id}', [AdminController::class, 'cetakBeritaAcara'])->name('cetak_berita_acara');

    // Petugas & Pengawas Ujian
    Route::get('/petugas-ujian', [AdminController::class, 'petugasUjian'])->name('petugas_ujian');
    Route::post('/petugas-ujian', [AdminController::class, 'simpanPetugasUjian'])->name('petugas_ujian.simpan');
    Route::post('/petugas-ujian/{id}/update', [AdminController::class, 'updatePetugasUjian'])->name('petugas_ujian.update');
    Route::post('/petugas-ujian/{id}/hapus', [AdminController::class, 'hapusPetugasUjian'])->name('petugas_ujian.hapus');
    Route::get('/cetak-kartu-petugas', [AdminController::class, 'cetakKartuPetugas'])->name('cetak_kartu_petugas');

    // Modul Laporan & Analisis (Nilai, Analisis Butir Soal, Monitor & Reset Siswa)
    Route::get('/laporan/nilai', [AdminController::class, 'laporanNilai'])->name('laporan.nilai');
    Route::get('/laporan/nilai/cetak', [AdminController::class, 'cetakLaporanNilai'])->name('laporan.nilai.cetak');
    Route::get('/laporan/nilai/export-xlsx', [AdminController::class, 'exportLaporanNilaiXlsx'])->name('laporan.nilai.export_xlsx');

    Route::get('/laporan/analisis-soal', [AdminController::class, 'analisisButirSoal'])->name('laporan.analisis_soal');
    Route::get('/laporan/analisis-soal/cetak', [AdminController::class, 'cetakAnalisisSoal'])->name('laporan.analisis_soal.cetak');
    Route::get('/laporan/analisis-soal/export-xlsx', [AdminController::class, 'exportAnalisisSoalXlsx'])->name('laporan.analisis_soal.export_xlsx');

    Route::get('/laporan/reset-ujian', [AdminController::class, 'monitorResetUjian'])->name('laporan.reset_ujian');
    Route::post('/reset-ujian/{pesertaId}/tambah-waktu', [AdminController::class, 'tambahWaktuPeserta'])->name('reset_ujian.tambah_waktu');
    Route::post('/reset-ujian/{pesertaId}/reset-tes', [AdminController::class, 'resetTesPeserta'])->name('reset_ujian.reset_tes');
    Route::post('/reset-ujian/{pesertaId}/force-finish', [AdminController::class, 'forceFinishPeserta'])->name('reset_ujian.force_finish');

    // Backup & Pemulihan (Database & Bank Soal)
    Route::get('/backup', [AdminController::class, 'backupIndex'])->name('backup');
    Route::post('/backup/database', [AdminController::class, 'backupDatabase'])->name('backup.database');
    Route::get('/backup/database/unduh/{filename}', [AdminController::class, 'downloadBackupDatabase'])->name('backup.database.unduh');
    Route::post('/backup/database/hapus/{filename}', [AdminController::class, 'hapusBackupDatabase'])->name('backup.database.hapus');
    Route::get('/backup/bank-soal/{id}/export-json', [AdminController::class, 'exportBankSoalJson'])->name('backup.bank_soal.export_json');
    Route::post('/backup/bank-soal/import-json', [AdminController::class, 'importBankSoalJson'])->name('backup.bank_soal.import_json');

    // Pengaturan
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [AdminController::class, 'simpanPengaturan'])->name('pengaturan.simpan');
});

// Route Migrasi Otomatis & Reset Database
Route::get('/system-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
        return response('<h3>Migrasi dan seeding database berhasil!</h3><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre><br><a href="/login">Kembali ke Halaman Login</a>', 200);
    } catch (\Throwable $e) {
        return response('<h3>Gagal migrasi database:</h3><p>' . $e->getMessage() . '</p>', 500);
    }
});
