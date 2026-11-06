<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasUjian extends Model
{
    use HasFactory;

    protected $table = 'petugas_ujian';

    protected $fillable = [
        'user_id',
        'jadwal_ujian_id',
        'jenis_petugas',
        'ruangan',
        'sesi',
        'nomor_sk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_ujian_id');
    }
}
