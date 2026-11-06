<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    protected $table = 'jadwal_ujian';
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'acak_opsi' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'izinkan_login_ganda' => 'boolean',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'jadwal_ujian_kelas', 'jadwal_ujian_id', 'kelas_id');
    }

    public function peserta()
    {
        return $this->hasMany(UjianPeserta::class);
    }
}
