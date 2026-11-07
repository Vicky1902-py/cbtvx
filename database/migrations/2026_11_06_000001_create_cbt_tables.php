<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Master Sekolah (SMKN 1 Kupang Barat)
        Schema::create('master_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->default('SMK NEGERI 1 KUPANG BARAT');
            $table->string('npsn', 20)->default('69984954');
            $table->string('alamat')->default('Desa Kuanheun');
            $table->string('kecamatan')->default('Kupang Barat');
            $table->string('kabupaten')->default('Kab. Kupang');
            $table->string('provinsi')->default('Nusa Tenggara Timur');
            $table->string('kepala_sekolah')->default('Vicky Koroh, S.Pd., Gr.');
            $table->string('nip_kepala_sekolah')->nullable()->default('198501192011011005');
            $table->string('ttd_kepala_sekolah')->nullable()->default('images/ttd_kepsek.png');
            $table->string('logo_sekolah')->nullable()->default('images/logo_smk.png');
            $table->string('logo_pemprov')->nullable()->default('images/logo_pemprov_ntt.svg');
            $table->string('tahun_ajaran_aktif', 20)->default('2026/2027');
            $table->enum('semester_aktif', ['Ganjil', 'Genap'])->default('Genap');
            $table->timestamps();
        });

        // 2. Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelas', 30)->unique();
            $table->string('nama_kelas', 50);
            $table->enum('tingkat', ['10', '11', '12'])->default('10');
            $table->string('jurusan', 50)->default('Teknik Komputer dan Jaringan');
            $table->timestamps();
        });

        // 3. Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 30)->unique();
            $table->string('nama_mapel', 100);
            $table->timestamps();
        });

        // 4. Data Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nis', 30)->nullable();
            $table->string('nisn', 20)->unique();
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('sesi_ujian', 20)->default('Sesi 1');
            $table->string('ruangan_ujian', 30)->default('Lab Komputer 1');
            $table->string('nomor_peserta', 40)->nullable()->unique();
            $table->string('foto')->nullable();
            $table->enum('status_login', ['offline', 'online', 'sedang_tes', 'selesai'])->default('offline');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 5. Bank Soal
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bank', 50)->unique();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('tingkat', ['Semua', '10', '11', '12'])->default('Semua');
            $table->integer('jumlah_soal_pg')->default(25);
            $table->integer('jumlah_soal_uraian')->default(0);
            $table->decimal('bobot_pg', 5, 2)->default(100.00);
            $table->decimal('bobot_uraian', 5, 2)->default(0.00);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->enum('status', ['draft', 'aktif', 'arsip'])->default('aktif');
            $table->timestamps();
        });

        // 6. Butir Soal (Mendukung PG, Benar/Salah, dan Uraian)
        Schema::create('soal_butir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->onDelete('cascade');
            $table->integer('nomor_urut')->default(1);
            $table->enum('jenis_soal', ['pg', 'benar_salah', 'uraian'])->default('pg');
            $table->longText('konten_soal');
            $table->string('audio_file')->nullable();
            $table->text('opsi_a')->nullable();
            $table->text('opsi_b')->nullable();
            $table->text('opsi_c')->nullable();
            $table->text('opsi_d')->nullable();
            $table->text('opsi_e')->nullable();
            $table->string('kunci_jawaban', 20)->nullable();
            $table->decimal('bobot', 5, 2)->default(4.00); // 25 soal x 4 = 100
            $table->timestamps();
        });

        // 7. Jadwal Ujian / Asesmen Aktif
        Schema::create('jadwal_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->onDelete('cascade');
            $table->string('nama_ujian', 150);
            $table->string('jenis_ujian', 50)->default('TKA');
            $table->string('token_ujian', 10)->default('SMK01');
            $table->boolean('acak_token')->default(false);
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->integer('durasi_menit')->default(60);
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_opsi')->default(true);
            $table->boolean('tampilkan_nilai')->default(true);
            $table->boolean('izinkan_login_ganda')->default(false);
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        // 8. Relasi Jadwal Ujian ke Kelas Peserta
        Schema::create('jadwal_ujian_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
        });

        // 9. Peserta Ujian (Sesi Siswa Mengerjakan)
        Schema::create('ujian_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->integer('sisa_detik')->default(3600);
            $table->enum('status', ['belum', 'berlangsung', 'selesai', 'diblokir'])->default('belum');
            $table->text('urutan_soal')->nullable(); // Urutan soal jika acak
            $table->decimal('nilai_pg', 5, 2)->default(0.00);
            $table->decimal('nilai_uraian', 5, 2)->default(0.00);
            $table->decimal('total_nilai', 5, 2)->default(0.00);
            $table->integer('jawaban_benar')->default(0);
            $table->integer('jawaban_salah')->default(0);
            $table->integer('jawaban_kosong')->default(0);
            $table->string('ip_pengerjaan', 45)->nullable();
            $table->integer('jumlah_pelanggaran')->default(0);
            $table->timestamps();
        });

        // 10. Jawaban Siswa per Butir Soal
        Schema::create('ujian_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_peserta_id')->constrained('ujian_peserta')->onDelete('cascade');
            $table->foreignId('soal_butir_id')->constrained('soal_butir')->onDelete('cascade');
            $table->string('jawaban_siswa', 255)->nullable();
            $table->boolean('ragu_ragu')->default(false);
            $table->boolean('is_benar')->nullable();
            $table->decimal('nilai_diperoleh', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_jawaban');
        Schema::dropIfExists('ujian_peserta');
        Schema::dropIfExists('jadwal_ujian_kelas');
        Schema::dropIfExists('jadwal_ujian');
        Schema::dropIfExists('soal_butir');
        Schema::dropIfExists('bank_soal');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('master_sekolah');
    }
};
