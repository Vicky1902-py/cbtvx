@echo off
title PENJAGA OTOMATIS CBT (STANDBY) - SERVER LAB 1
color 0a

echo ==============================================================================
echo         PENJAGA & PEMANTAU OTOMATIS CBT SMKN 1 KUPANG BARAT (LAB 1)
echo ==============================================================================
echo Mode: Offline Watchdog (Memantau setiap 15 detik tanpa mengganggu siswa)
echo - Jika server port 8000 mati/crash, otomatis dinyalakan ulang.
echo - Jika ada error di laravel.log atau beban WAL membesar, otomatis ditangani.
echo - Tekan CTRL + C untuk menghentikan penjaga ini kapan saja.
echo ==============================================================================
echo.

cd /d C:\xampp\htdocs\cbtvx
C:\xampp\php\php.exe artisan cbt:monitor --reset-offset >nul 2>&1

:LOOP
C:\xampp\php\php.exe artisan cbt:monitor --check-http --heal
set RC=%ERRORLEVEL%

if %RC% EQU 2 (
    color 0c
    echo.
    echo [DARURAT] Server port 8000 tidak merespons! Menyalakan ulang server CBT...
    start "CBT SMKN 1 KUPANG BARAT - SERVER UTAMA (LAB 1)" cmd /c "C:\xampp\htdocs\cbtvx\JALANKAN_SERVER_CBT.bat"
    timeout /t 5 /nobreak >nul
    color 0a
) else if %RC% EQU 1 (
    color 0e
    echo [PERHATIAN] Terdeteksi catatan error baru di log! Periksa detail di atas.
) else (
    color 0a
)

timeout /t 15 /nobreak >nul
goto LOOP
