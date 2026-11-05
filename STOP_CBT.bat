@echo off
title STOP SERVER CBT - VICKY KOROH
color 0C
cls
echo ==============================================================================
echo       MEMATIKAN SERVER CBT & DATABASE PORTABLE (HEMAT RAM)
echo ==============================================================================
echo.

echo  [1] Mematikan service MariaDB...
if exist "D:\mariadb\bin\mariadb-admin.exe" (
    "D:\mariadb\bin\mariadb-admin.exe" -h 127.0.0.1 -u root shutdown >nul 2>nul
)
taskkill /f /im mysqld.exe >nul 2>nul
echo      [OK] MariaDB berhasil dimatikan (0%% RAM terpakai).

echo.
echo  [2] Mematikan server PHP...
taskkill /f /im php.exe >nul 2>nul
echo      [OK] PHP Server berhasil dimatikan.

echo.
echo ==============================================================================
echo  Server CBT telah dinonaktifkan sepenuhnya. Komputer kembali ringan 100%%.
echo ==============================================================================
echo.
timeout /t 3
