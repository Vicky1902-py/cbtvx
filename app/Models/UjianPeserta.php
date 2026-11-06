<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPeserta extends Model
{
    protected $table = 'ujian_peserta';
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function jadwalUjian()
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jawaban()
    {
        return $this->hasMany(UjianJawaban::class);
    }
}
