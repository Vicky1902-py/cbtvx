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
    Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
    Route::get('/bank-soal', [AdminController::class, 'bankSoal'])->name('bank_soal');
    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('jadwal');
    Route::post('/reset-login/{id}', [AdminController::class, 'resetLogin'])->name('reset_login');
    Route::get('/cetak-kartu', [AdminController::class, 'cetakKartu'])->name('cetak_kartu');
    Route::get('/cetak-berita-acara/{id}', [AdminController::class, 'cetakBeritaAcara'])->name('cetak_berita_acara');
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
