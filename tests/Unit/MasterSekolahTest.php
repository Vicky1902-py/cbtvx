<?php

namespace Tests\Unit;

use App\Models\MasterSekolah;
use Tests\TestCase;

class MasterSekolahTest extends TestCase
{
    public function test_master_sekolah_accessors()
    {
        $sekolah = new MasterSekolah([
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'npsn' => '69727541',
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., M.Kom.',
            'nip_kepala_sekolah' => '198501192011011005',
            'logo_pemprov' => 'images/logo_pemprov_ntt.svg',
            'logo_sekolah' => 'images/logo_smk.png',
            'ttd_kepala_sekolah' => 'images/ttd_kepsek.png',
        ]);

        $this->assertEquals('PEMERINTAH PROVINSI NUSA TENGGARA TIMUR', $sekolah->kop_baris1);
        $this->assertEquals('DINAS PENDIDIKAN DAN KEBUDAYAAN', $sekolah->kop_baris2);
        $this->assertEquals('SMK NEGERI 1 KUPANG BARAT', $sekolah->kop_baris3);
        $this->assertStringContainsString('NPSN: 69727541', $sekolah->kop_alamat_lengkap);
        $this->assertEquals('Kupang Barat', $sekolah->titimangsa_kota);
        $this->assertStringContainsString('Kupang Barat, ', $sekolah->titimangsa_lengkap);
        $this->assertStringContainsString('logo_pemprov', $sekolah->logo_pemprov_url);
        $this->assertStringContainsString('logo_smk', $sekolah->logo_sekolah_url);
        $this->assertStringContainsString('ttd_kepsek', $sekolah->ttd_kepala_sekolah_url);
        $this->assertEquals('Vicky Koroh, S.Pd., M.Kom.', $sekolah->kepala_sekolah);
        $this->assertEquals('198501192011011005', $sekolah->nip_kepala_sekolah);
    }

    public function test_master_sekolah_prefixes_provinsi_correctly()
    {
        // When user types just "Nusa Tenggara Timur"
        $s1 = new MasterSekolah(['provinsi' => 'Nusa Tenggara Timur']);
        $this->assertEquals('PEMERINTAH PROVINSI NUSA TENGGARA TIMUR', $s1->kop_baris1);

        // When user types already "Pemerintah Provinsi Nusa Tenggara Timur"
        $s2 = new MasterSekolah(['provinsi' => 'Pemerintah Provinsi Nusa Tenggara Timur']);
        $this->assertEquals('PEMERINTAH PROVINSI NUSA TENGGARA TIMUR', $s2->kop_baris1);

        // When user types "Provinsi Nusa Tenggara Timur"
        $s3 = new MasterSekolah(['provinsi' => 'Provinsi Nusa Tenggara Timur']);
        $this->assertEquals('PEMERINTAH PROVINSI NUSA TENGGARA TIMUR', $s3->kop_baris1);
    }
}
