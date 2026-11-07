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
        Schema::table('ujian_peserta', function (Blueprint $table) {
            if (!Schema::hasColumn('ujian_peserta', 'jumlah_pelanggaran')) {
                $table->integer('jumlah_pelanggaran')->default(0)->after('ip_pengerjaan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujian_peserta', function (Blueprint $table) {
            if (Schema::hasColumn('ujian_peserta', 'jumlah_pelanggaran')) {
                $table->dropColumn('jumlah_pelanggaran');
            }
        });
    }
};
