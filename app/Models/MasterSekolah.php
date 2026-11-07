<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MasterSekolah extends Model
{
    protected $table = 'master_sekolah';
    protected $guarded = ['id'];

    /**
     * Kop Baris 1: Pemerintah Provinsi ...
     * Contoh: "PEMERINTAH PROVINSI NUSA TENGGARA TIMUR"
     */
    public function getKopBaris1Attribute(): string
    {
        $prov = trim($this->provinsi ?? 'Nusa Tenggara Timur');
        if (stripos($prov, 'PEMERINTAH') === 0) {
            return strtoupper($prov);
        }
        if (stripos($prov, 'PROVINSI') === 0) {
            return 'PEMERINTAH ' . strtoupper($prov);
        }
        return 'PEMERINTAH PROVINSI ' . strtoupper($prov);
    }

    /**
     * Kop Baris 2: Dinas Pendidikan ...
     * Contoh: "DINAS PENDIDIKAN DAN KEBUDAYAAN"
     */
    public function getKopBaris2Attribute(): string
    {
        $dinas = trim($this->dinas_pendidikan ?? 'DINAS PENDIDIKAN DAN KEBUDAYAAN');
        return strtoupper($dinas);
    }

    /**
     * Kop Baris 3: Nama Resmi Sekolah
     * Contoh: "SMK NEGERI 1 KUPANG BARAT"
     */
    public function getKopBaris3Attribute(): string
    {
        $sekolah = trim($this->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT');
        return strtoupper($sekolah);
    }

    /**
     * Alamat Lengkap untuk KOP Dokumen Resmi
     */
    public function getKopAlamatLengkapAttribute(): string
    {
        $parts = [];
        if (!empty($this->alamat)) {
            $parts[] = $this->alamat;
        }
        if (!empty($this->kecamatan)) {
            $parts[] = 'Kec. ' . $this->kecamatan;
        }
        if (!empty($this->kabupaten)) {
            $parts[] = $this->kabupaten;
        }
        $str = implode(', ', $parts);
        if (!empty($this->npsn)) {
            $str .= ' • NPSN: ' . $this->npsn;
        }
        return $str ?: 'Desa Kuanheun, RT 02 / RW 02, Kec. Kupang Barat, Kabupaten Kupang • NPSN: 69727541';
    }

    /**
     * Titimangsa Kota / Kecamatan untuk pengesahan tanda tangan
     */
    public function getTitimangsaKotaAttribute(): string
    {
        if (!empty($this->kecamatan)) {
            return $this->kecamatan;
        }
        if (!empty($this->kabupaten)) {
            return str_replace(['Kabupaten ', 'Kab. ', 'Kota '], '', $this->kabupaten);
        }
        return 'Kupang Barat';
    }

    /**
     * Format tanggal Indonesia baku (contoh: "6 November 2026")
     */
    public function formatTanggalIndonesia($date = null): string
    {
        $d = $date ? Carbon::parse($date) : Carbon::now();
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $d->day . ' ' . ($bulanIndo[$d->month] ?? $d->format('F')) . ' ' . $d->year;
    }

    /**
     * Titimangsa lengkap: "[Kota/Kecamatan], [Tanggal Bulan Tahun]"
     * Contoh: "Kupang Barat, 6 November 2026"
     */
    public function getTitimangsaLengkapAttribute(): string
    {
        return $this->titimangsa_kota . ', ' . $this->formatTanggalIndonesia();
    }

    /**
     * URL Logo Pemprov (Kiri KOP) dengan fallback
     */
    public function getLogoPemprovUrlAttribute(): string
    {
        if (!empty($this->logo_pemprov) && file_exists(public_path($this->logo_pemprov))) {
            return asset($this->logo_pemprov);
        }
        return asset('images/logo_pemprov_ntt.svg');
    }

    /**
     * URL Logo Sekolah (Kanan KOP) dengan fallback
     */
    public function getLogoSekolahUrlAttribute(): string
    {
        if (!empty($this->logo_sekolah) && file_exists(public_path($this->logo_sekolah))) {
            return asset($this->logo_sekolah);
        }
        return asset('images/logo_smk.png');
    }

    /**
     * URL Logo Tut Wuri Handayani Resmi Kemendikdasmen / Pusmendik
     */
    public function getLogoTutwuriUrlAttribute(): string
    {
        if (file_exists(public_path('images/logo_tutwuri.jpg'))) {
            return asset('images/logo_tutwuri.jpg');
        }
        return asset('images/logo_smk.png');
    }

    /**
     * URL Gambar Tanda Tangan & Cap Stempel Kepala Sekolah dengan fallback
     */
    public function getTtdKepalaSekolahUrlAttribute(): string
    {
        if (!empty($this->ttd_kepala_sekolah) && file_exists(public_path($this->ttd_kepala_sekolah))) {
            return asset($this->ttd_kepala_sekolah);
        }
        return asset('images/ttd_kepsek.png');
    }

    /**
     * Nama Kepala Sekolah Default
     */
    public function getKepalaSekolahAttribute($value): string
    {
        return $value ?: 'Vicky Koroh, S.Pd., Gr.';
    }

    /**
     * NIP Kepala Sekolah Default
     */
    public function getNipKepalaSekolahAttribute($value): string
    {
        return $value ?: '198501192011011005';
    }
}
