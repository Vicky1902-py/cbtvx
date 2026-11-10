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
        Schema::table('master_sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('master_sekolah', 'nama_ujian_aktif')) {
                $table->string('nama_ujian_aktif', 150)->default('Simulasi Sumatif Akhir Semester')->after('semester_aktif');
            }
            if (!Schema::hasColumn('master_sekolah', 'jenis_ujian_aktif')) {
                $table->string('jenis_ujian_aktif', 50)->default('Simulasi SAS')->after('nama_ujian_aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_sekolah', function (Blueprint $table) {
            if (Schema::hasColumn('master_sekolah', 'nama_ujian_aktif')) {
                $table->dropColumn('nama_ujian_aktif');
            }
            if (Schema::hasColumn('master_sekolah', 'jenis_ujian_aktif')) {
                $table->dropColumn('jenis_ujian_aktif');
            }
        });
    }
};
