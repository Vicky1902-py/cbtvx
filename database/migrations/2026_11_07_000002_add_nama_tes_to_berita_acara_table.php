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
                if (!Schema::hasColumn('berita_acara', 'nama_tes')) {
                    $table->string('nama_tes', 150)->nullable()->after('nomor_surat');
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
                if (Schema::hasColumn('berita_acara', 'nama_tes')) {
                    $table->dropColumn('nama_tes');
                }
            });
        }
    }
};
