<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $table = 'berita_acara';

    protected $fillable = [
        'jadwal_ujian_id',
        'nomor_surat',
        'hari_tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan',
        'sesi',
        'proktor_id',
        'nama_proktor',
        'nip_proktor',
        'pengawas_id',
        'nama_pengawas',
        'nip_pengawas',
        'jumlah_peserta_seharusnya',
        'jumlah_peserta_hadir',
        'jumlah_peserta_tidak_hadir',
        'daftar_tidak_hadir',
        'catatan_pelaksanaan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_ujian_id');
    }

    public function proktor()
    {
        return $this->belongsTo(User::class, 'proktor_id');
    }

    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }
}
