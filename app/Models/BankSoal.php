<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table = 'bank_soal';
    protected $guarded = ['id'];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function butirSoal()
    {
        return $this->hasMany(SoalButir::class, 'bank_soal_id')->orderBy('nomor_urut');
    }

    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class);
    }
}
