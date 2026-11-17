@echo off
title CBT SMKN 1 KUPANG BARAT - SERVER ENGINE
color 0b

echo ======================================================================
echo           CBT SMKN 1 KUPANG BARAT - SERVER LOCAL AREA (LAN)           
echo ======================================================================
echo.
echo Sedang memeriksa alamat IP Server Anda...
for /f "tokens=4" %%a in ('route print ^| findstr 0.0.0.0 ^| findstr /v "0.0.0.0.*0.0.0.0"') do (
    set LOCAL_IP=%%a
)

echo [OK] Server siap dijalankan!
echo.
echo ======================================================================
echo 1. Akses dari PC Server (Komputer ini) :
echo    http://localhost:8000/
echo.
echo 2. Akses dari Komputer Siswa di Lab   :
echo    http://10.43.155.91:8000/   (atau IP LAN Lab Anda: http://%LOCAL_IP%:8000/)
echo ======================================================================
echo.
echo PERHATIAN: Jendela ini JANGAN DITUTUP selama ujian berlangsung!
echo Tekan CTRL + C untuk mematikan server jika sudah selesai ujian.
echo.
echo ----------------------------------------------------------------------

cd /d C:\xampp\htdocs\cbtvx
C:\xampp\php\php.exe artisan serve --host=0.0.0.0 --port=8000

pause
