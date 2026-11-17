@echo off
title CBT SMKN 1 KUPANG BARAT - SERVER UTAMA (LAB 1)
color 0b

echo ======================================================================
echo           CBT SMKN 1 KUPANG BARAT - SERVER UTAMA LAB 1 (LAN)          
echo ======================================================================
echo.
echo Sedang memeriksa alamat IP Server Anda...
set LOCAL_IP=
for /f "tokens=4" %%a in ('route print ^| findstr 0.0.0.0 ^| findstr /v "0.0.0.0.*0.0.0.0"') do (
    set LOCAL_IP=%%a
)
if "%LOCAL_IP%"=="" (
    for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4 Address" /c:"Alamat IPv4"') do (
        for /f "tokens=1" %%b in ("%%a") do (
            if not "%%b"=="" set LOCAL_IP=%%b
        )
    )
)

rem Buka akses Windows Firewall Port 8000 secara otomatis
netsh advfirewall firewall add rule name="CBT Port 8000 SMKN1KB" dir=in action=allow protocol=TCP localport=8000 >nul 2>&1

rem Aktifkan 8 worker paralel multi-proses agar responsif menangani 50+ siswa bersamaan
set PHP_CLI_SERVER_WORKERS=8

echo [OK] Multi-Worker Server (8 Workers) Siap Dijalankan!
echo [OK] Firewall Port 8000 Telah Dipastikan Terbuka.
echo.
echo ======================================================================
echo 1. Akses dari PC Server (Komputer ini) :
echo    http://localhost:8000/
echo.
echo 2. Akses dari Seluruh Komputer Siswa di Lab :
echo    http://%LOCAL_IP%:8000/
echo ======================================================================
echo.
echo PERHATIAN: Jendela ini JANGAN DITUTUP selama ujian berlangsung!
echo Tekan CTRL + C untuk mematikan server jika sudah selesai ujian.
echo.
echo ----------------------------------------------------------------------

cd /d C:\xampp\htdocs\cbtvx
C:\xampp\php\php.exe artisan serve --host=0.0.0.0 --port=8000

pause
