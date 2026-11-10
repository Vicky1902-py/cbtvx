# Pedoman & Konvensi Sistem CBT SMKN 1 Kupang Barat

Dokumen ini adalah aturan tetap (*always-on rule*) untuk asisten AI (Antigravity) yang bekerja pada repositori ini.

---

## 1. Identitas Sekolah & Pejabat Resmi
- **Nama Sekolah**: SMK Negeri 1 Kupang Barat
- **NPSN**: 69727541
- **Kabupaten / Kota**: Kabupaten Kupang (Kode: 02)
- **Kecamatan**: Kupang Barat
- **Kepala Sekolah & CBT Head**: **Vicky Koroh, S.Pd., Gr.**
  - NIP: 198501192011011005
  - *PENTING*: Jangan pernah menggunakan gelar `S.Kom` atau `M.Kom` untuk Pak Vicky Koroh di seluruh sistem, database, dokumen cetak, maupun tampilan UI.
- **Tahun Ajaran Aktif**: 2026/2027 (Semester Genap)

---

## 2. Nomenklatur Jurusan & Tingkat Kelas
Sesuai arahan kurikulum SMK Negeri 1 Kupang Barat:
- **Kelas X**: Menggunakan **TPFL** (Teknik Pengelasan dan Fabrikasi Logam).
- **Kelas XI & XII**: Menggunakan **TP** (Teknik Pengelasan).
- **Teknik Pemesinan**: *TIDAK ADA* dan tidak boleh digunakan di manapun dalam sistem.
- **Jurusan Lainnya**:
  - TITL (Teknik Instalasi Tenaga Listrik)
  - TKL (Teknik Kendaraan Ringan / Otomotif)

---

## 3. Kelompok Mata Pelajaran (Mapel)
- **Muatan Umum**: Kelompok A & B (Pendidikan Agama, PPKn, Bahasa Indonesia, Matematika, dll).
- **Muatan Kejuruan**:
  - **Kelas X**: Seluruh mapel kejuruan digabung menjadi satu mapel yaitu **DKK** (*Dasar Kompetensi Keahlian*), contoh: `DKK TITL`, `DKK TPFL`.
  - **Kelas XI & XII**: Seluruh mapel kejuruan menjadi **KK** (*Kompetensi Keahlian*), contoh: `KK TITL`, `KK TP`.
- **Mapel Pilihan (MPP)**:
  - **Kelas X**: Wajib **KKA** (*Koding dan Kecerdasan Artifisial*).
  - **Kelas XI & XII**: Bebas diisi dan diubah sesuai kebutuhan kurikulum.
- **Hak Akses Admin**: Admin memiliki hak penuh untuk menambah, mengedit, atau menghapus mapel apapun secara bebas.

---

## 4. Standar Dokumen Cetak Pelaksanaan Ujian (A4 Presisi)
Semua dokumen cetak mengacu pada format resmi Pusmendik Kemendikdasmen:
1. **Daftar Hadir Peserta**:
   - Kop: Menggunakan **Logo Resmi SMKN 1 Kupang Barat** di kiri atas.
   - Judul KOP Baris 2: **Dinamis** mengikuti jenis/nama tes yang dipilih (misal: *SIMULASI UJIAN CBT*, *TKA*, *ASESMEN SUMATIF*), tidak boleh kaku/baku.
   - Tanda tangan: Proktor dan Pengawas Ujian dengan NIP otomatis.
   - Rekapitulasi: Kotak jumlah peserta seharusnya, hadir, tidak hadir, dan daftar username yang tidak hadir.
2. **Berita Acara Pelaksanaan**:
   - Format standar 3 pihak penandatangan: Proktor, Pengawas, dan Penanggung Jawab (Kepala Sekolah: Vicky Koroh, S.Pd., Gr.).
   - Catatan pelaksanaan dan kotak rangkap 3 resmi.
3. **Kartu Login Peserta**:
   - Layout kartu A4 dengan filter fleksibel per-kelas dan per-sesi.
   - Menampilkan foto peserta (dari upload massal ZIP NISN) dengan fallback avatar resmi.

---

## 5. Fitur Pengamanan Ujian Siswa (Anti-Cheat)
- **Full-Screen Lock**: Siswa diwajibkan masuk mode layar penuh saat tes berlangsung.
- **Deteksi Pindah Tab / Minimize**: Memantau event `visibilitychange` dan `blur`.
- **Pencatatan Pelanggaran**: Setiap kali siswa membuka tab baru atau minimize, sistem mencatat `jumlah_pelanggaran` ke tabel `ujian_peserta` dan menampilkan badge merah real-time pada Monitor Proktor.
- **Proteksi Shortcut & Klik Kanan**: Memblokir DevTools (F12), Inspect Element, Copy, Paste, dan Klik Kanan selama ujian.

---

## 6. Arsitektur Teknis & Perintah Penting
- **Framework**: Laravel 12 (PHP 8.2+).
- **Database Lokal / PC Server**: SQLite (`database/database.sqlite`) di jaringan lokal lab.
- **Database Production**: MySQL di cPanel hosting `bikinaplikasi.my.id`.
- **Pengujian**: Seluruh perubahan wajib diverifikasi dengan perintah `php artisan test` (30 test case passed).
- **Perintah Sinkronisasi cPanel (Aman Tanpa Tinker/Shell_exec)**:
  ```bash
  cd ~/public_html/bikinaplikasi.my.id && git fetch origin && git reset --hard origin/main && php artisan migrate --force && php artisan cbt:sync-admin && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && echo "DEPLOY_SUKSES"
  ```
