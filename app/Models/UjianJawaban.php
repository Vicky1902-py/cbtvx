<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianJawaban extends Model
{
    protected $table = 'ujian_jawaban';
    protected $guarded = ['id'];

    protected $casts = [
        'ragu_ragu' => 'boolean',
        'is_benar' => 'boolean',
    ];

    public function ujianPeserta()
    {
        return $this->belongsTo(UjianPeserta::class);
    }

    public function soalButir()
    {
        return $this->belongsTo(SoalButir::class);
    }
}
