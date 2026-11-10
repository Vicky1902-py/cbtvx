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
        Schema::table('petugas_ujian', function (Blueprint $table) {
            $table->string('jenis_petugas', 100)->default('Pengawas Ruang')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas_ujian', function (Blueprint $table) {
            $table->string('jenis_petugas', 50)->default('pengawas')->change();
        });
    }
};
