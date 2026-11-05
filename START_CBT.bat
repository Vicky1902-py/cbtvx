@echo off
setlocal enabledelayedexpansion
title CBT MODERN STANDAR TKA KEMDIKBUD - VICKY KOROH
color 0B
cls

echo ==============================================================================
echo       APLIKASI CBT MODERN STANDAR TKA PUSMENDIK KEMDIKBUD
echo                   HAK CIPTA (C) VICKY KOROH
echo ==============================================================================
echo.

:: Pastikan PHP masuk dalam PATH lingkungan CMD
set "WINGET_PHP=C:\Users\vicky\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe"
if exist "!WINGET_PHP!\php.exe" (
    set "PATH=!WINGET_PHP!;%PATH%"
)

where php >nul 2>nul
if %errorlevel% neq 0 (
    color 0C
    echo [ERROR] Program PHP tidak ditemukan di sistem!
    echo.
    pause
    exit /b
)

:: 1. Memeriksa dan Menyalakan MariaDB Portable
echo [1] Memeriksa Database MariaDB / MySQL...
netstat -ano | findstr ":3306" | findstr "LISTENING" >nul
if %errorlevel% neq 0 (
    echo     Menjalankan MariaDB Portable hemat RAM di latar belakang...
    powershell -WindowStyle Hidden -Command "Start-Process 'D:\mariadb\bin\mysqld.exe' -ArgumentList '--defaults-file=D:\mariadb\my.ini' -WindowStyle Hidden"
    ping 127.0.0.1 -n 3 >nul
) else (
    echo     [OK] Database MySQL sudah aktif di Port 3306.
)

:: 2. Membuka Browser Otomatis ke Halaman Login
echo.
echo [2] Membuka Portal CBT di Browser...
start "" "http://localhost:8080/index.php/manager/"

:: 3. Menjalankan Server Web PHP
echo.
echo [3] Server CBT Berhasil Aktif di Port 8080!
echo     ----------------------------------------------------------
echo     * Login Admin / Guru : http://localhost:8080/index.php/manager/
echo     * Login Peserta Tes  : http://localhost:8080/
echo     ----------------------------------------------------------
echo.
echo  PENTING:
echo  - Biarkan jendela ini tetap terbuka selama ujian berlangsung.
echo  - Untuk mematikan server, cukup tekan tombol Ctrl + C atau tutup jendela ini.
echo ==============================================================================
echo.

cd /d "G:\My Drive\uas2022\uas2022"
php -S 0.0.0.0:8080

echo.
echo Server telah dimatikan.
pause
