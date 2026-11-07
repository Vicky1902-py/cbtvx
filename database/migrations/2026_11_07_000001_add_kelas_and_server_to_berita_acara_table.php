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
        if (Schema::hasTable('berita_acara')) {
            Schema::table('berita_acara', function (Blueprint $table) {
                if (!Schema::hasColumn('berita_acara', 'kelas_id')) {
                    $table->foreignId('kelas_id')->nullable()->after('jadwal_ujian_id')->constrained('kelas')->onDelete('cascade');
                }
                if (!Schema::hasColumn('berita_acara', 'id_server')) {
                    $table->string('id_server', 80)->nullable()->default('K24020010-AS4K')->after('ruangan');
                }
                if (!Schema::hasColumn('berita_acara', 'kode_kabupaten')) {
                    $table->string('kode_kabupaten', 30)->nullable()->default('02')->after('id_server');
                }
                if (!Schema::hasColumn('berita_acara', 'kode_sekolah')) {
                    $table->string('kode_sekolah', 30)->nullable()->default('0010')->after('kode_kabupaten');
                }
                if (!Schema::hasColumn('berita_acara', 'penanggung_jawab_id')) {
                    $table->foreignId('penanggung_jawab_id')->nullable()->after('nip_pengawas')->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('berita_acara', 'penanggung_jawab_nama')) {
                    $table->string('penanggung_jawab_nama', 150)->nullable()->after('penanggung_jawab_id');
                }
                if (!Schema::hasColumn('berita_acara', 'penanggung_jawab_nip')) {
                    $table->string('penanggung_jawab_nip', 50)->nullable()->after('penanggung_jawab_nama');
                }
                if (!Schema::hasColumn('berita_acara', 'presensi_data')) {
                    $table->json('presensi_data')->nullable()->after('catatan_pelaksanaan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('berita_acara')) {
            Schema::table('berita_acara', function (Blueprint $table) {
                $columns = ['kelas_id', 'id_server', 'kode_kabupaten', 'kode_sekolah', 'penanggung_jawab_id', 'penanggung_jawab_nama', 'penanggung_jawab_nip', 'presensi_data'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('berita_acara', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
