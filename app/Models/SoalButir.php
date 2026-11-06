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
}
