<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalButir extends Model
{
    protected $table = 'soal_butir';
    protected $guarded = ['id'];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function getPertanyaanAttribute()
    {
        return $this->attributes['konten_soal'] ?? '';
    }

    public function setPertanyaanAttribute($value)
    {
        $this->attributes['konten_soal'] = $value;
    }

    public function getTipeSoalAttribute()
    {
        return $this->attributes['jenis_soal'] ?? 'pg';
    }

    public function setTipeSoalAttribute($value)
    {
        $this->attributes['jenis_soal'] = $value;
    }

    public function getPilihanAAttribute()
    {
        return $this->attributes['opsi_a'] ?? null;
    }

    public function setPilihanAAttribute($value)
    {
        $this->attributes['opsi_a'] = $value;
    }

    public function getPilihanBAttribute()
    {
        return $this->attributes['opsi_b'] ?? null;
    }

    public function setPilihanBAttribute($value)
    {
        $this->attributes['opsi_b'] = $value;
    }

    public function getPilihanCAttribute()
    {
        return $this->attributes['opsi_c'] ?? null;
    }

    public function setPilihanCAttribute($value)
    {
        $this->attributes['opsi_c'] = $value;
    }

    public function getPilihanDAttribute()
    {
        return $this->attributes['opsi_d'] ?? null;
    }

    public function setPilihanDAttribute($value)
    {
        $this->attributes['opsi_d'] = $value;
    }

    public function getPilihanEAttribute()
    {
        return $this->attributes['opsi_e'] ?? null;
    }

    public function setPilihanEAttribute($value)
    {
        $this->attributes['opsi_e'] = $value;
    }

    public function getBobotNilaiAttribute()
    {
        return $this->attributes['bobot'] ?? 4.0;
    }

    public function setBobotNilaiAttribute($value)
    {
        $this->attributes['bobot'] = $value;
    }
}
