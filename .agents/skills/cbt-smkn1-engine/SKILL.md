---
name: cbt-smkn1-engine
description: >-
  Panduan operasional dan pengetahuan arsitektur sistem CBT SMK Negeri 1 Kupang Barat.
  Gunakan skill ini ketika diminta mengelola jadwal ujian, presensi/berita acara,
  bank soal, upload foto siswa, anti-cheat, sinkronisasi cPanel, atau trouble-shooting server.
---

# CBT SMKN 1 Kupang Barat Engine - Operational Skill

Panduan operasional resmi untuk pengembangan dan pemeliharaan aplikasi CBT SMKN 1 Kupang Barat.

## 1. Menjalankan Server di Jaringan Lab Komputer

Untuk melayani ujian bagi ratusan siswa di lab komputer:
```bash
# Menjalankan di port 80 (default HTTP):
php artisan serve --host=0.0.0.0 --port=80

# Atau di port 8000 jika port 80 terpakai Apache XAMPP:
php artisan serve --host=0.0.0.0 --port=8000
```
Siswa mengakses melalui browser di `http://[IP_PC_SERVER]:8000/`.

## 2. Modul-Modul Utama & Lokasi File Penting

- **Admin Controller**: `app/Http/Controllers/AdminController.php` (Pengaturan, Presensi, Berita Acara, Laporan Nilai, Analisis Butir Soal, Monitor & Reset Ujian, Backup).
- **Siswa Controller**: `app/Http/Controllers/SiswaController.php` (Alur Ujian, Jawaban Autosave, Catat Pelanggaran Pindah Tab, Selesai Tes).
- **Model Utama**:
  - `App\Models\MasterSekolah`: Pengaturan profil sekolah, logo, KOP, ttd kepsek.
  - `App\Models\JadwalUjian`: Jadwal asesmen, jenis tes, durasi, token.
  - `App\Models\BeritaAcara`: Data presensi per-kelas/per-sesi, catatan proktor/pengawas.
  - `App\Models\UjianPeserta`: Status sesi ujian siswa, waktu sisa, jumlah pelanggaran.
  - `App\Models\BankSoal` & `App\Models\SoalButir`: Bank soal preset 25 & 40 butir, opsi A-E, formula math.
- **Tampilan Cetak Dokumen A4**:
  - `resources/views/admin/cetak_daftar_hadir.blade.php`: Daftar Hadir format resmi Pusmendik Kemendikdasmen A4.
  - `resources/views/admin/cetak_berita_acara.blade.php`: Berita Acara format resmi 3 TTD.
  - `resources/views/admin/cetak_kartu.blade.php`: Cetak kartu peserta dengan filter kelas dan sesi.
  - `resources/views/admin/cetak_laporan_nilai.blade.php`: Laporan nilai per jadwal dan kelas.
  - `resources/views/admin/cetak_analisis_soal.blade.php`: Analisis daya pembeda ($D$) dan tingkat kesukaran ($P$).

## 3. Prosedur Deploy & Sinkronisasi Hosting cPanel

Hosting cPanel memiliki batasan keamanan `disable_functions` (`shell_exec`, `exec`, `symlink`). Jangan gunakan `artisan tinker` atau `artisan storage:link` pada terminal cPanel.

Gunakan perintah resmi:
```bash
cd ~/public_html/bikinaplikasi.my.id && git fetch origin && git reset --hard origin/main && php artisan migrate --force && php artisan cbt:sync-admin && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && echo "DEPLOY_SUKSES"
```

## 4. Verifikasi dan Pengujian Otomatis

Sebelum melakukan push atau setelah melakukan perubahan kode, selalu jalankan test suite:
```bash
php artisan test
```
Pastikan seluruh 46 tes lulus dengan 338 assertion.
