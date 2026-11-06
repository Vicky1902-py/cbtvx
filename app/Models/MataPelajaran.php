<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $guarded = ['id'];

    public function bankSoal()
    {
        return $this->hasMany(BankSoal::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function getLabelLengkapAttribute()
    {
        $guruName = $this->guru ? $this->guru->name : 'Belum Ditentukan';
        $jurusan = $this->jurusan ?: 'Semua Jurusan';
        $tingkat = ($this->tingkat && $this->tingkat !== 'Semua') ? "Kelas {$this->tingkat}" : "Semua Tingkat";
        return "[{$this->kode_mapel}] {$this->nama_mapel} ({$jurusan} - {$tingkat}) - Pengampu: {$guruName}";
    }
}
