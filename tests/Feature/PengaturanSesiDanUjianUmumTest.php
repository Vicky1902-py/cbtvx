<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MasterSekolah;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaturanSesiDanUjianUmumTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekolah;
    protected $kelas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Vicky Koroh, S.Pd., Gr.',
            'username' => 'admin_sesi',
            'email' => 'admin_sesi@smkn1kupangbarat.sch.id',
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
            'kepala_sekolah' => 'Apolos Kofan, S.Pd.,MM',
            'nip_kepala_sekolah' => '196704062006041009',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
            'nama_ujian_aktif' => 'Simulasi Sumatif Akhir Semester',
            'jenis_ujian_aktif' => 'Simulasi SAS',
        ]);

        $this->kelas = Kelas::create([
            'kode_kelas' => 'X-RPL',
            'nama_kelas' => 'X RPL',
            'tingkat' => 10,
            'jurusan' => 'RPL',
        ]);
    }

    public function test_halaman_pengaturan_sesi_dapat_diakses()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.sesi'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sesi &amp; Ruangan Lab Siswa', false);
        $response->assertSee('Peserta Sesi 1');
        $response->assertSee('Peserta Sesi 2');
        $response->assertSee('Distribusi Ruangan Lab');
    }

    public function test_admin_dapat_mengatur_sesi_dan_ruang_lab_secara_massal()
    {
        $siswaList = [];
        for ($i = 1; $i <= 4; $i++) {
            $user = User::factory()->create(['role' => 'siswa', 'username' => "siswa_test_0{$i}"]);
            $siswaList[] = Siswa::create([
                'user_id' => $user->id,
                'nisn' => "009988770{$i}",
                'nama_lengkap' => "Siswa Test 0{$i}",
                'kelas_id' => $this->kelas->id,
                'sesi_ujian' => 'Sesi 1',
                'ruangan_ujian' => 'Lab Komputer 1',
            ]);
        }

        $idsToUpdate = [$siswaList[2]->id, $siswaList[3]->id];

        $response = $this->actingAs($this->admin)->post(route('admin.sesi.update_massal'), [
            'siswa_ids' => $idsToUpdate,
            'sesi_ujian' => 'Sesi 2',
            'ruangan_ujian' => 'Lab Komputer 2',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswaList[2]->id,
            'sesi_ujian' => 'Sesi 2',
            'ruangan_ujian' => 'Lab Komputer 2',
        ]);

        $this->assertDatabaseHas('siswa', [
            'id' => $siswaList[3]->id,
            'sesi_ujian' => 'Sesi 2',
            'ruangan_ujian' => 'Lab Komputer 2',
        ]);

        // Siswa 01 & 02 tetap di Sesi 1
        $this->assertDatabaseHas('siswa', [
            'id' => $siswaList[0]->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);
    }

    public function test_admin_dapat_membagi_rata_otomatis_50_50_ke_lab_1_dan_lab_2()
    {
        $ids = [];
        for ($i = 1; $i <= 6; $i++) {
            $user = User::factory()->create(['role' => 'siswa', 'username' => "siswa_split_0{$i}"]);
            $s = Siswa::create([
                'user_id' => $user->id,
                'nisn' => "007788990{$i}",
                'nama_lengkap' => "Siswa Split 0{$i}",
                'kelas_id' => $this->kelas->id,
                'sesi_ujian' => 'Sesi 1',
                'ruangan_ujian' => 'Belum Diatur',
            ]);
            $ids[] = $s->id;
        }

        $response = $this->actingAs($this->admin)->post(route('admin.sesi.auto_split'), [
            'siswa_ids' => $ids,
            'sesi_ujian' => 'Sesi 1',
            'lab_a' => 'Lab Komputer 1',
            'lab_b' => 'Lab Komputer 2',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 3 siswa pertama di Lab 1
        for ($i = 0; $i < 3; $i++) {
            $this->assertDatabaseHas('siswa', [
                'id' => $ids[$i],
                'sesi_ujian' => 'Sesi 1',
                'ruangan_ujian' => 'Lab Komputer 1',
            ]);
        }

        // 3 siswa berikutnya di Lab 2
        for ($i = 3; $i < 6; $i++) {
            $this->assertDatabaseHas('siswa', [
                'id' => $ids[$i],
                'sesi_ujian' => 'Sesi 1',
                'ruangan_ujian' => 'Lab Komputer 2',
            ]);
        }
    }

    public function test_pengaturan_nama_dan_jenis_ujian_umum_dapat_disimpan()
    {
        $payload = [
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '69727541',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
            'kepala_sekolah' => 'Apolos Kofan, S.Pd.,MM',
            'nip_kepala_sekolah' => '196704062006041009',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
            'nama_ujian_aktif' => 'Sumatif Akhir Semester',
            'jenis_ujian_aktif' => 'Sumatif Akhir Semester',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.pengaturan.simpan'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_sekolah', [
            'nama_ujian_aktif' => 'Sumatif Akhir Semester',
            'jenis_ujian_aktif' => 'Sumatif Akhir Semester',
        ]);
    }

    public function test_cetak_kartu_peserta_mengikuti_nama_ujian_umum_aktif()
    {
        $user = User::factory()->create(['role' => 'siswa', 'username' => 'siswa_kartu_01']);
        Siswa::create([
            'user_id' => $user->id,
            'nisn' => '0012345679',
            'nama_lengkap' => 'Siswa Kartu Ujian',
            'kelas_id' => $this->kelas->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $this->sekolah->update([
            'nama_ujian_aktif' => 'Sumatif Akhir Semester',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_kartu'));
        $response->assertStatus(200);
        $response->assertSee('KARTU PESERTA SUMATIF AKHIR SEMESTER');
    }

    public function test_cetak_rekap_sesi_dapat_diakses_dan_menampilkan_siswa_ruang_sesi_jam_dan_ttd_ketua_proktor()
    {
        $user1 = User::factory()->create(['role' => 'siswa', 'username' => 'siswa_sesi_01']);
        Siswa::create([
            'user_id' => $user1->id,
            'nisn' => '0098765431',
            'nama_lengkap' => 'Siswa Sesi Pagi',
            'kelas_id' => $this->kelas->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $user2 = User::factory()->create(['role' => 'siswa', 'username' => 'siswa_sesi_02']);
        Siswa::create([
            'user_id' => $user2->id,
            'nisn' => '0098765432',
            'nama_lengkap' => 'Siswa Sesi Siang',
            'kelas_id' => $this->kelas->id,
            'sesi_ujian' => 'Sesi 2',
            'ruangan_ujian' => 'Lab Komputer 2',
        ]);

        $guruKetua = User::factory()->create(['name' => 'Messakh Paulus Adu, S.Pd', 'role' => 'guru', 'tugas' => 'Ketua Panitia']);
        \App\Models\PetugasUjian::create([
            'user_id' => $guruKetua->id,
            'jenis_petugas' => 'Ketua Panitia',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
        ]);

        $guruProktor = User::factory()->create(['name' => 'Izak Robinson Koroh, S.Pd.,Gr', 'role' => 'guru', 'tugas' => 'Proktor']);
        \App\Models\PetugasUjian::create([
            'user_id' => $guruProktor->id,
            'jenis_petugas' => 'Proktor',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.cetak_sesi'));
        $response->assertStatus(200);

        // KOP & Judul
        $response->assertSee('DAFTAR PEMBAGIAN SESI & RUANGAN LAB UJIAN PESERTA');
        $response->assertSee($this->sekolah->kop_baris1);
        $response->assertSee($this->sekolah->kop_baris3);

        // Data Siswa, Ruangan, Sesi, Jam Ujian
        $response->assertSee('SISWA SESI PAGI');
        $response->assertSee('SISWA SESI SIANG');
        $response->assertSee('Lab Komputer 1');
        $response->assertSee('Lab Komputer 2');
        $response->assertSee('07.30 - 09.30 WITA');
        $response->assertSee('10.00 - 12.00 WITA');

        // Tanda Tangan Ketua Panitia & Proktor
        $response->assertSee('Ketua Panitia Pelaksana');
        $response->assertSee('Messakh Paulus Adu, S.Pd');
        $response->assertSee('Koordinator Proktor CBT');
        $response->assertSee('Izak Robinson Koroh, S.Pd.,Gr');
    }
}
