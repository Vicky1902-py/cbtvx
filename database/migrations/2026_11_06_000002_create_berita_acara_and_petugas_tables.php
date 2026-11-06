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
        // 1. Tambah field dinas_pendidikan di master_sekolah & nip/nuptk di users
        if (Schema::hasTable('master_sekolah')) {
            Schema::table('master_sekolah', function (Blueprint $table) {
                if (!Schema::hasColumn('master_sekolah', 'dinas_pendidikan')) {
                    $table->string('dinas_pendidikan')->default('DINAS PENDIDIKAN DAN KEBUDAYAAN')->after('provinsi');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'nip')) {
                    $table->string('nip', 40)->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'nuptk')) {
                    $table->string('nuptk', 40)->nullable()->after('nip');
                }
            });
        }

        // 2. Tabel Petugas Ujian (Proktor & Pengawas)
        Schema::create('petugas_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_ujian_id')->nullable()->constrained('jadwal_ujian')->onDelete('set null');
            $table->enum('jenis_petugas', ['proktor', 'pengawas'])->default('pengawas');
            $table->string('ruangan', 50)->default('Lab Komputer 1');
            $table->string('sesi', 30)->default('Sesi 1');
            $table->string('nomor_sk', 100)->nullable()->default('421.5/012/SMKN1.KB/2026');
            $table->timestamps();
        });

        // 3. Tabel Berita Acara Ujian
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->onDelete('cascade');
            $table->string('nomor_surat', 100)->nullable();
            $table->string('hari_tanggal', 100)->nullable();
            $table->string('waktu_mulai', 30)->nullable();
            $table->string('waktu_selesai', 30)->nullable();
            $table->string('ruangan', 50)->default('Lab Komputer 1');
            $table->string('sesi', 30)->default('Sesi 1');
            
            // Proktor
            $table->foreignId('proktor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_proktor', 150)->nullable();
            $table->string('nip_proktor', 40)->nullable();

            // Pengawas
            $table->foreignId('pengawas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_pengawas', 150)->nullable();
            $table->string('nip_pengawas', 40)->nullable();

            // Data Peserta
            $table->integer('jumlah_peserta_seharusnya')->default(0);
            $table->integer('jumlah_peserta_hadir')->default(0);
            $table->integer('jumlah_peserta_tidak_hadir')->default(0);
            $table->text('daftar_tidak_hadir')->nullable();
            $table->text('catatan_pelaksanaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
        Schema::dropIfExists('petugas_ujian');
        if (Schema::hasColumn('master_sekolah', 'dinas_pendidikan')) {
            Schema::table('master_sekolah', function (Blueprint $table) {
                $table->dropColumn('dinas_pendidikan');
            });
        }
        if (Schema::hasColumn('users', 'nip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['nip', 'nuptk']);
            });
        }
    }
};
