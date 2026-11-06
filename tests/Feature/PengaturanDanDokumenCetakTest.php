<?php

namespace Tests\Feature;

use App\Models\BeritaAcara;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\MasterSekolah;
use App\Models\MataPelajaran;
use App\Models\BankSoal;
use App\Models\PetugasUjian;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanDanDokumenCetakTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekolah;

    protected function tearDown(): void
    {
        // Bersihkan berkas uji upload di public/images jika ada
        foreach (glob(public_path('images/*baru*.*')) as $testFile) {
            if (file_exists($testFile)) {
                @unlink($testFile);
            }
        }
        foreach (glob(public_path('images/ttd_[0-9]*.*')) as $testFile) {
            if (file_exists($testFile)) {
                @unlink($testFile);
            }
        }
        foreach (glob(public_path('images/logo_sekolah_[0-9]*.*')) as $testFile) {
            if (file_exists($testFile)) {
                @unlink($testFile);
            }
        }
        foreach (glob(public_path('images/logo_pemprov_[0-9]*.*')) as $testFile) {
            if (file_exists($testFile)) {
                @unlink($testFile);
            }
        }

        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Vicky Koroh, S.Pd., M.Kom.',
            'username' => 'admin',
            'email' => 'admin@smkn1kupangbarat.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->sekolah = MasterSekolah::create([
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '69727541',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., M.Kom.',
            'nip_kepala_sekolah' => '198501192011011005',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
            'logo_sekolah' => 'images/logo_smk.png',
            'logo_pemprov' => 'images/logo_pemprov_ntt.svg',
            'ttd_kepala_sekolah' => 'images/ttd_kepsek.png',
        ]);
    }

    public function test_halaman_pengaturan_bisa_diakses_oleh_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Identitas Sekolah');
        $response->assertSee('PEMERINTAH PROVINSI NUSA TENGGARA TIMUR');
        $response->assertSee('DINAS PENDIDIKAN DAN KEBUDAYAAN');
        $response->assertSee('SMK NEGERI 1 KUPANG BARAT');
        $response->assertSee('Vicky Koroh, S.Pd., M.Kom.');
    }

    public function test_simpan_pengaturan_memperbarui_data_dan_berkas()
    {
        Storage::fake('public');

        $fileTtd = UploadedFile::fake()->image('ttd_baru.png');
        $fileLogoSekolah = UploadedFile::fake()->image('logo_baru.png');

        $payload = [
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '69727541',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI NTT',
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., M.Kom.',
            'nip_kepala_sekolah' => '198501192011011005',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
            'ttd_kepala_sekolah' => $fileTtd,
            'logo_sekolah' => $fileLogoSekolah,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.pengaturan.simpan'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_sekolah', [
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI NTT',
        ]);
    }

    public function test_cetak_kartu_petugas_menggunakan_kop_dan_ttd_dinamis()
    {
        $guru = User::create([
            'name' => 'Potifera Buan, S.Pd',
            'username' => 'guru1',
            'email' => 'guru@smkn1kupangbarat.sch.id',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        PetugasUjian::create([
            'user_id' => $guru->id,
            'jenis_petugas' => 'pengawas',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
            'nomor_sk' => '421.5/012/SMKN1.KB/2026',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_kartu_petugas'));
        $response->assertStatus(200);
        $response->assertSee($this->sekolah->kop_baris1);
        $response->assertSee($this->sekolah->kop_baris2);
        $response->assertSee($this->sekolah->kop_baris3);
        $response->assertSee($this->sekolah->titimangsa_lengkap);
        $response->assertSee($this->sekolah->kepala_sekolah);
        $response->assertSee($this->sekolah->nip_kepala_sekolah);
        $response->assertSee('POTIFERA BUAN, S.PD');
    }

    public function test_cetak_kartu_peserta_menggunakan_kop_dan_ttd_dinamis()
    {
        $kelas = Kelas::create([
            'kode_kelas' => 'X-DPIB',
            'nama_kelas' => 'X DPIB',
            'tingkat' => 10,
            'jurusan' => 'DPIB',
            'kuota' => 36,
        ]);

        $siswa = Siswa::create([
            'user_id' => $this->admin->id,
            'kelas_id' => $kelas->id,
            'nisn' => '0071234567',
            'nis' => '10293',
            'nomor_peserta' => '26-24-001-001',
            'nama_lengkap' => 'Aditya Pratama',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Kupang',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Kristen Protestan',
            'ruangan_ujian' => 'Lab Komputer 1',
            'sesi_ujian' => 'Sesi 1',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_kartu'));
        $response->assertStatus(200);
        $response->assertSee($this->sekolah->kop_baris1);
        $response->assertSee($this->sekolah->kop_baris2);
        $response->assertSee($this->sekolah->kop_baris3);
        $response->assertSee($this->sekolah->titimangsa_lengkap);
        $response->assertSee($this->sekolah->kepala_sekolah);
        $response->assertSee('ADITYA PRATAMA');
    }

    public function test_cetak_berita_acara_menggunakan_kop_dan_ttd_dinamis()
    {
        $mapel = MataPelajaran::create([
            'kode_mapel' => 'DPIB-01',
            'nama_mapel' => 'Gambar Teknik Otomotif',
            'tingkat' => 10,
            'kelompok' => 'Kejuruan',
        ]);

        $bankSoal = BankSoal::create([
            'kode_bank' => 'BANK-DPIB-01',
            'mata_pelajaran_id' => $mapel->id,
            'guru_id' => $this->admin->id,
            'judul' => 'Ujian Akhir Semester Gasal',
            'tingkat' => 10,
            'preset_pilihan' => 'preset_25',
            'total_soal' => 25,
            'bobot_pg' => 30,
            'bobot_bs' => 20,
            'bobot_uraian' => 50,
        ]);

        $jadwal = JadwalUjian::create([
            'nama_ujian' => 'Asesmen Sumatif Akhir Semester',
            'bank_soal_id' => $bankSoal->id,
            'waktu_mulai' => '2026-11-10 08:00:00',
            'waktu_selesai' => '2026-11-10 10:00:00',
            'durasi_menit' => 90,
            'token_ujian' => 'TESTOK',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_berita_acara', $jadwal->id));
        $response->assertStatus(200);
        $response->assertSee($this->sekolah->kop_baris1);
        $response->assertSee($this->sekolah->kop_baris2);
        $response->assertSee($this->sekolah->kop_baris3);
        $response->assertSee($this->sekolah->titimangsa_lengkap);
        $response->assertSee($this->sekolah->kepala_sekolah);
        $response->assertSee($jadwal->nama_ujian);
    }

    public function test_cetak_daftar_hadir_menggunakan_kop_dan_ttd_dinamis()
    {
        $mapel = MataPelajaran::create([
            'kode_mapel' => 'TITL-01',
            'nama_mapel' => 'Instalasi Tenaga Listrik',
            'tingkat' => 11,
            'kelompok' => 'Kejuruan',
        ]);

        $bankSoal = BankSoal::create([
            'kode_bank' => 'BANK-TITL-01',
            'mata_pelajaran_id' => $mapel->id,
            'guru_id' => $this->admin->id,
            'judul' => 'Ujian Tengah Semester Gasal',
            'tingkat' => 11,
            'preset_pilihan' => 'preset_25',
            'total_soal' => 25,
            'bobot_pg' => 30,
            'bobot_bs' => 20,
            'bobot_uraian' => 50,
        ]);

        $jadwal = JadwalUjian::create([
            'nama_ujian' => 'Asesmen Sumatif Tengah Semester',
            'bank_soal_id' => $bankSoal->id,
            'waktu_mulai' => '2026-11-10 08:00:00',
            'waktu_selesai' => '2026-11-10 10:00:00',
            'durasi_menit' => 90,
            'token_ujian' => 'TITL99',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_daftar_hadir', $jadwal->id));
        $response->assertStatus(200);
        $response->assertSee($this->sekolah->kop_baris1);
        $response->assertSee($this->sekolah->kop_baris2);
        $response->assertSee($this->sekolah->kop_baris3);
        $response->assertSee($this->sekolah->titimangsa_lengkap);
        $response->assertSee($this->sekolah->kepala_sekolah);
        $response->assertSee($jadwal->nama_ujian);
    }
}
