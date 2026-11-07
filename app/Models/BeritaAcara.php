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
        'kelas_id',
        'nomor_surat',
        'nama_tes',
        'hari_tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan',
        'id_server',
        'kode_kabupaten',
        'kode_sekolah',
        'sesi',
        'proktor_id',
        'nama_proktor',
        'nip_proktor',
        'pengawas_id',
        'nama_pengawas',
        'nip_pengawas',
        'penanggung_jawab_id',
        'penanggung_jawab_nama',
        'penanggung_jawab_nip',
        'jumlah_peserta_seharusnya',
        'jumlah_peserta_hadir',
        'jumlah_peserta_tidak_hadir',
        'daftar_tidak_hadir',
        'catatan_pelaksanaan',
        'presensi_data',
    ];

    protected $casts = [
        'presensi_data' => 'array',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_ujian_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function proktor()
    {
        return $this->belongsTo(User::class, 'proktor_id');
    }

    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
}
