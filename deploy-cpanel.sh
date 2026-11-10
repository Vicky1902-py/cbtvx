#!/bin/bash
# ==============================================================
# SCRIPT DEPLOY CBT SMKN 1 KUPANG BARAT KE CPANEL
# Domain: bikinaplikasi.my.id
# ==============================================================
set -e

echo "=============================================="
echo "🚀 MEMULAI DEPLOYMENT KE CPANEL..."
echo "=============================================="

echo ">> [1/5] Mengambil commit terbaru dari GitHub (origin/main)..."
git fetch origin
git reset --hard origin/main

echo ">> [2/5] Menjalankan migrasi database otomatis..."
php artisan migrate --force

echo ">> [3/5] Sinkronisasi akun admin CBT..."
php artisan cbt:sync-admin

echo ">> [4/5] Membersihkan cache Laravel (Config, Cache, View, Route)..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ">> [5/5] Memastikan hak akses folder storage dan bootstrap..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=============================================="
echo "🎉 DEPLOY CPANEL SUKSES 100%!"
echo "=============================================="
