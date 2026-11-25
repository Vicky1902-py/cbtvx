# Panduan Penggunaan Aplikasi CBT SMK Negeri 1 Kupang Barat

Panduan ini disusun untuk **Administrator / Proktor**, **Guru / Penyusun Soal**, **Pengawas**, dan **Peserta Ujian (Siswa)** agar pelaksanaan asesmen di **Lab 1**, **Lab 2**, maupun melalui **Tablet / PC Siswa** berjalan tertib dan lancar.

---

## 1. Menyalakan Server Ujian (LAN Offline / Lokal)

### A. Server Utama (Lab 1 — `C:\xampp\htdocs\cbtvx`)
1. Pastikan kabel LAN Lab 1 terhubung ke Switch/Hub.
2. Klik ganda file **`JALANKAN_SERVER_CBT.bat`** di dalam folder `c:\xampp\htdocs\cbtvx`.
3. Jendela hitam (Command Prompt) akan terbuka dan otomatis menampilkan alamat akses:
   - **Dari komputer server (Proktor):** `http://localhost:8000/`
   - **Dari komputer/tablet siswa di Lab 1:** `http://<IP_SERVER>:8000/` *(lihat IP yang tampil di layar hitam)*
4. **Jangan tutup jendela hitam tersebut** selama ujian berlangsung. Untuk mematikan server setelah ujian selesai, tekan `CTRL + C` lalu tutup jendela.

### B. Server Lab 2 (Portable — `D:\CBT_SERVER_LAB_2`)
1. Pastikan folder `CBT_SERVER_LAB_2` sudah disalin ke Server Lab 2 (tidak perlu instal XAMPP).
2. Klik ganda file **`1_JALANKAN_SERVER_LAB_2.bat`**.
3. Gunakan alamat `http://localhost:8000/` untuk Proktor Lab 2 dan `http://<IP_SERVER_LAB_2>:8000/` untuk seluruh PC/tablet siswa di Lab 2.
4. *(Opsional)* Jika ingin mengosongkan data hasil simulasi/uji coba sebelum hari-H ujian, klik ganda **`2_RESET_DATABASE_BERSIH.bat`** lalu ketik **`Y`**.

---

## 2. Informasi Login Resmi

Buka alamat server di browser (Chrome/Edge/Safari/Browser Tablet), lalu masuk pada halaman `/login`:

| Peran | Tab Login | Username | Password Default |
|---|---|---|---|
| **Administrator** | Proktor / Pengawas / Guru | `admin` | `admin` |
| **Proktor** | Proktor / Pengawas / Guru | `proktor` | `proktor` |
| **Guru** | Proktor / Pengawas / Guru | `guru` *(atau username guru di Data Pengguna)* | `guru123` |
| **Siswa** | Peserta Ujian (Siswa) | **NISN** masing-masing siswa | `12345` *(atau sesuai yang tercetak di Kartu Login)* |

> **Saran Keamanan:** Segera ubah kata sandi default Admin/Proktor melalui menu **Guru & Pengguna** setelah login pertama kali jika diperlukan.

---

## 3. Panduan Administrator & Proktor (Persiapan Ujian)

Urutan kerja yang disarankan sebelum hari pelaksanaan ujian:

### Langkah 1 — Periksa Data Sekolah & Nama Ujian Aktif
- Buka menu **Data Sekolah & TTD Kepsek** (`/admin/pengaturan`).
- Pastikan Nama Sekolah, NPSN, Nama Kepala Sekolah, NIP, Tahun Ajaran/Semester, Logo, dan **Nama / Jenis Ujian Aktif** sudah sesuai (nama ujian ini otomatis muncul di KOP Daftar Hadir, Berita Acara, dan Kartu Login).

### Langkah 2 — Periksa Data Master (Kelas, Mapel, Siswa, Guru)
- **Data Kelas & Rombel** (`/admin/kelas`): memastikan pembagian rombel X (`TPFL`), XI & XII (`TP`), `TITL`, `TKL`.
- **Mata Pelajaran** (`/admin/mapel`): cek mapel umum, DKK (Kelas X), KK (Kelas XI–XII), dan KKA / MPP. Bisa impor massal via template XLSX.
- **Data Siswa (Impor XLSX)** (`/admin/siswa`): tambah/edit/impor siswa serta unggah **foto siswa massal (.zip)** dengan nama file `NISN.jpg` / `NISN.png`.
- **Guru & Pengguna** (`/admin/users`): kelola akun guru/proktor/pengawas serta penetapan Kepala Sekolah (`Set Kepsek`).

### Langkah 3 — Kelola Bank Soal & Butir Soal
- Buka menu **Bank Soal (25/40 Soal)** (`/admin/bank-soal`), buat bank soal baru atau buka detail bank soal yang ada.
- Tersedia 3 cara pengisian soal:
  1. **Input Manual** satu per satu di aplikasi (Pilihan Ganda, Benar/Salah, atau Uraian, lengkap dengan gambar/audio/rubrik).
  2. **Impor Excel (`.xlsx`)**: unduh template Excel (mendukung preset 25 soal / 40 soal dan gambar yang ditempel di kolom L `Gambar / Ilustrasi`), isi lalu unggah.
  3. **Impor Word (`.docx`)**: unduh template Word, isi soal berikut gambar yang ditempel langsung di dokumen, lalu unggah.
- Klik **Pratinjau** untuk memeriksa tampilan soal, gambar, dan kunci jawaban sebelum diujikan.

### Langkah 4 — Atur Sesi, Ruang Lab, & Jadwal Ujian
- Buka **Pengaturan Sesi & Lab** (`/admin/sesi`): bagi siswa ke **Sesi 1 / 2 / 3** dan **Lab 1 / Lab 2** (tersedia tombol pembagian otomatis 50:50), lalu cetak rekap sesi bila perlu.
- Buka **Jadwal & Token Ujian** (`/admin/jadwal`):
  - Buat jadwal ujian, pilih Bank Soal, atur durasi (menit), waktu mulai/selesai, dan centang **kelas peserta** yang berhak mengikuti jadwal tersebut.
  - Set status jadwal menjadi **Aktif**.
  - Catat **Token Ujian** (atau klik **Generate Token** untuk membuat token baru) yang akan dibagikan kepada pengawas/siswa saat ujian dimulai.

### Langkah 5 — Petugas & Dokumen Cetak A4
- **Petugas & Pengawas** (`/admin/petugas-ujian`): daftarkan panitia, proktor, dan pengawas per ruang/sesi.
- **Cetak Kartu Login A4** (`/admin/cetak-kartu`): filter berdasarkan kelas/sesi lalu cetak (`Ctrl + P`, kertas A4).
- **Cetak Kartu Petugas A4** (`/admin/cetak-kartu-petugas`) dan daftar hadir petugas.

---

## 4. Panduan Saat Ujian Berlangsung (Monitor Proktor & Dokumen)

### A. Mengawasi Peserta Secara Realtime
- Buka menu **Monitor & Reset Siswa** (`/admin/laporan/reset-ujian`) atau **Dashboard & Monitoring** (`/admin/dashboard`).
- Proktor dapat memantau status siswa (*Belum*, *Sedang Dikerjakan*, *Selesai*), sisa waktu, dan lencana peringatan pindah tab (`⚠️ Nx Pindah Tab`).
- Tombol tindakan cepat untuk setiap peserta:
  - **Buka Kunci**: membuka status login jika komputer/tablet siswa hang atau pindah perangkat.
  - **+15 Menit**: menambah waktu jika terjadi kendala listrik/jaringan pada siswa tertentu.
  - **Reset Peringatan**: mengembalikan catatan pelanggaran ke `0` apabila terjadi *false alarm* (misal pop-up notifikasi sistem).
  - **Selesai Paksa**: menutup ujian siswa yang lupa menekan tombol selesai dan menghitung nilainya otomatis.
  - **Ulang Tes**: menghapus jawaban peserta dan mengulang dari awal.

### B. Mengisi Daftar Hadir & Berita Acara
- **Daftar Hadir Asesmen** (`/admin/daftar-hadir`): isi kehadiran peserta per kelas/sesi lalu cetak format Pusmendik A4.
- **Berita Acara Asesmen** (`/admin/berita-acara`): isi catatan pelaksanaan, pengawas, dan proktor per kelas/sesi lalu cetak rangkap resmi 3 penandatangan.

---

## 5. Panduan Peserta Ujian (Siswa — Desktop & Tablet)

1. **Login**: Buka alamat server (`http://<IP_SERVER>:8000/`) di browser PC atau Tablet, pilih tab **Peserta Ujian (Siswa)**, masukkan **NISN** dan **Password** (`12345` atau sesuai Kartu Login), lalu tekan **Mulai Sesi Ujian**.
2. **Pilih Ujian**: Pada **Portal Asesmen Siswa**, pilih jadwal yang aktif dan tekan **Ikuti Asesmen** (atau **Lanjutkan Tes** bila melanjutkan sesi yang terhenti).
3. **Masukkan Token**: Cocokkan identitas diri pada halaman **Konfirmasi Tes**, masukkan **Token Ujian** dari pengawas, lalu tekan **Mulai Mengerjakan Sekarang**.
4. **Mengerjakan Soal**:
   - **Desktop / PC**: Layar akan meminta mode layar penuh (*Full-Screen*). Jangan membuka tab lain, meminimalkan browser, atau menekan klik kanan/tombol pintasan dev tools karena sistem akan mencatat pelanggaran ke Proktor.
   - **Tablet**: Tampilan otomatis menyesuaikan layar sentuh. Gunakan tombol **☰ Daftar Soal** di bagian atas soal untuk melompat ke nomor soal mana saja dengan cepat.
   - **Memperbesar Gambar**: Ketuk/klik gambar pada soal untuk memperbesar di dalam jendela ujian *(aman, tidak dianggap pindah tab)*.
   - **Status Penyimpanan Otomatis**: Perhatikan indikator hijau **"Semua Tersimpan"** di kanan atas. Jika jaringan lab terputus sesaat (indikator berubah merah *"Offline - Aman di Komputer"*), tetap lanjutkan mengerjakan karena jawaban disimpan di perangkat dan dikirim ulang otomatis saat jaringan tersambung kembali.
5. **Mengakhiri Ujian**: Pada nomor terakhir atau lewat tombol **Selesai Ujian**, periksa ringkasan jumlah soal *Terjawab / Ragu-ragu / Belum Dijawab*, lalu tekan **Ya, Selesai Sekarang**.

---

## 6. Rekap Nilai, Analisis Soal, & Backup Setelah Ujian

1. **Hasil Ujian & Nilai** (`/admin/laporan/nilai`): lihat rekap nilai per kelas/jadwal, cetak laporan, atau ekspor ke **Excel (`.xlsx`)**. *(Jika ada soal Uraian, lengkapi penilaian manual uraian terlebih dahulu sebelum ekspor akhir).*
2. **Analisis Butir Soal** (`/admin/laporan/analisis-soal`): lihat tingkat kesukaran dan persentase jawaban benar tiap butir soal serta ekspor ke Excel.
3. **Backup Database & Soal** (`/admin/backup`):
   - Setelah ujian selesai di **Lab 1** dan **Lab 2**, wajib lakukan **Backup Database** dan **Ekspor Nilai Excel** pada masing-masing server agar seluruh nilai dan arsip bank soal aman tersimpan.
