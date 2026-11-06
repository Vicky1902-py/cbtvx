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
        // 1. Upgrade Tabel Mata Pelajaran
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_pelajaran', 'kelompok')) {
                $table->string('kelompok', 50)->default('Muatan Umum (A/B)')->after('nama_mapel');
            }
            if (!Schema::hasColumn('mata_pelajaran', 'jurusan')) {
                $table->string('jurusan', 100)->default('Semua Jurusan')->after('kelompok');
            }
            if (!Schema::hasColumn('mata_pelajaran', 'tingkat')) {
                $table->enum('tingkat', ['Semua', '10', '11', '12'])->default('Semua')->after('jurusan');
            }
            if (!Schema::hasColumn('mata_pelajaran', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null')->after('tingkat');
            }
        });

        // 2. Upgrade Tabel Bank Soal
        Schema::table('bank_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_soal', 'nama_bank')) {
                $table->string('nama_bank', 150)->nullable()->after('kode_bank');
            }
            if (!Schema::hasColumn('bank_soal', 'standar_bobot')) {
                $table->string('standar_bobot', 30)->default('25_soal')->after('tingkat');
            }
            if (!Schema::hasColumn('bank_soal', 'jumlah_soal_bs')) {
                $table->integer('jumlah_soal_bs')->default(5)->after('jumlah_soal_uraian');
            }
            if (!Schema::hasColumn('bank_soal', 'bobot_bs')) {
                $table->decimal('bobot_bs', 5, 2)->default(20.00)->after('bobot_uraian');
            }
        });

        // 3. Upgrade Tabel Butir Soal
        Schema::table('soal_butir', function (Blueprint $table) {
            if (!Schema::hasColumn('soal_butir', 'gambar_soal')) {
                $table->string('gambar_soal')->nullable()->after('audio_file');
            }
            if (!Schema::hasColumn('soal_butir', 'video_file')) {
                $table->string('video_file')->nullable()->after('gambar_soal');
            }
            if (!Schema::hasColumn('soal_butir', 'rubrik_uraian')) {
                $table->text('rubrik_uraian')->nullable()->after('bobot');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_butir', function (Blueprint $table) {
            $table->dropColumn(['gambar_soal', 'video_file', 'rubrik_uraian']);
        });

        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'standar_bobot', 'jumlah_soal_bs', 'bobot_bs']);
        });

        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn(['kelompok', 'jurusan', 'tingkat', 'guru_id']);
        });
    }
};
