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
use Tests\TestCase;

class ManajemenUserDanKepsekTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekolah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Vicky Koroh, S.Pd., Gr.',
            'username' => 'admin',
            'email' => 'admin@smkn1kupangbarat.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tugas' => 'Koordinator IT',
        ]);

        $this->sekolah = MasterSekolah::create([
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '69727541',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'Dinas Pendidikan dan Kebudayaan Provinsi NTT',
            'kepala_sekolah' => 'Apolos Kofan, S.Pd, MM',
            'nip_kepala_sekolah' => '196704062006041009',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
        ]);
    }

    public function test_users_index_renders_tugas_and_kepsek_info(): void
    {
        $guru = User::create([
            'name' => 'Apriyani Robblah Balla, S.E.',
            'username' => 'apriyani',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'tugas' => 'Bendahara Panitia',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertSee('Guru &amp; Pengguna Sistem', false);
        $response->assertSee('Tugas / Jabatan', false);
        $response->assertSee('Bendahara Panitia', false);
        $response->assertSee('Kepala Sekolah Aktif Saat Ini', false);
        $response->assertSee('Apolos Kofan, S.Pd, MM', false);
    }

    public function test_admin_can_create_user_with_custom_tugas(): void
    {
        $payload = [
            'name' => 'Elfrida Kopong, S.Pd',
            'username' => 'elfrida',
            'email' => 'elfrida@smkn1kupangbarat.sch.id',
            'nip' => '199102242025212028',
            'password' => 'guru123',
            'role' => 'guru',
            'tugas' => 'Wakasek Kurikulum',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.users.simpan'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'username' => 'elfrida',
            'role' => 'guru',
            'tugas' => 'Wakasek Kurikulum',
        ]);
    }

    public function test_admin_can_update_user_role_and_tugas(): void
    {
        $guru = User::create([
            'name' => 'Eliatus Sau, S.Pd',
            'username' => 'eliatus',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'tugas' => 'Guru Pengampu',
            'status' => 'aktif',
        ]);

        $payload = [
            'name' => 'Eliatus Sau, S.Pd',
            'username' => 'eliatus',
            'email' => 'eliatus@smkn1kupangbarat.sch.id',
            'nip' => '198310142022211014',
            'role' => 'proktor',
            'tugas' => 'Koordinator Proktor CBT',
            'status' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.users.update', $guru->id), $payload);

        $response->assertRedirect();
        $guru->refresh();
        $this->assertEquals('proktor', $guru->role);
        $this->assertEquals('Koordinator Proktor CBT', $guru->tugas);
    }

    public function test_setting_teacher_as_kepsek_updates_master_sekolah_and_all_documents(): void
    {
        $guru = User::create([
            'name' => 'Maria Therik, S.Pd.',
            'username' => 'maria',
            'nip' => '198501012010012001',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'tugas' => 'Wali Kelas',
            'status' => 'aktif',
        ]);

        // Buat kelas, siswa, mapel, bank soal, jadwal untuk uji dokumen cetak
        $kelas = Kelas::create([
            'kode_kelas' => 'XII-TKJ-1',
            'nama_kelas' => 'XII TKJ 1',
            'tingkat' => '12',
            'jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        $siswaUser = User::create([
            'name' => 'Alfonso Gomez',
            'username' => '26-001-001',
            'password' => bcrypt('123456'),
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nomor_peserta' => '26-001-001',
            'nis' => '1001',
            'nisn' => '0051234567',
            'nama_lengkap' => 'Alfonso Gomez',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
            'status_login' => 'offline',
            'password' => bcrypt('123456'),
        ]);

        $mapel = MataPelajaran::create([
            'kode_mapel' => 'KKA-XII',
            'nama_mapel' => 'Koding & Kecerdasan Artifisial',
            'tingkat' => '12',
            'guru_id' => $guru->id,
        ]);

        $bankSoal = BankSoal::create([
            'mata_pelajaran_id' => $mapel->id,
            'guru_id' => $guru->id,
            'kode_bank' => 'BANK-KKA-XII',
            'tingkat' => '12',
            'jumlah_soal' => 25,
            'total_poin' => 100,
            'status' => 'aktif',
        ]);

        $jadwal = JadwalUjian::create([
            'nama_ujian' => 'Ujian KKA',
            'bank_soal_id' => $bankSoal->id,
            'jenis_ujian' => 'UAS',
            'waktu_mulai' => now()->startOfDay()->toDateTimeString(),
            'waktu_selesai' => now()->endOfDay()->toDateTimeString(),
            'durasi_menit' => 90,
            'token_ujian' => 'ABCXYZ',
            'status' => 'aktif',
        ]);

        $beritaAcara = BeritaAcara::create([
            'jadwal_ujian_id' => $jadwal->id,
            'hari_tanggal' => now()->toDateString(),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
            'proktor_nama' => 'Vicky Koroh, S.Pd., Gr.',
            'pengawas_nama' => 'Eliatus Sau, S.Pd',
            'penanggung_jawab_id' => null,
            'penanggung_jawab_nama' => 'Apolos Kofan, S.Pd, MM',
            'penanggung_jawab_nip' => '196704062006041009',
        ]);

        $petugas = PetugasUjian::create([
            'user_id' => $this->admin->id,
            'jenis_petugas' => 'Teknisi CBT',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
        ]);

        // EKSEKUSI: Tetapkan Maria Therik sebagai Kepala Sekolah via route post
        $response = $this->actingAs($this->admin)->post(route('admin.users.set_kepsek', $guru->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // VERIFIKASI 1: MasterSekolah terupdate
        $this->sekolah->refresh();
        $this->assertEquals('Maria Therik, S.Pd.', $this->sekolah->kepala_sekolah);
        $this->assertEquals('198501012010012001', $this->sekolah->nip_kepala_sekolah);

        // VERIFIKASI 2: Guru yang bersangkutan tugasnya menjadi 'Kepala Sekolah'
        $guru->refresh();
        $this->assertEquals('Kepala Sekolah', $guru->tugas);

        // VERIFIKASI 3: Berita Acara terupdate
        $beritaAcara->refresh();
        $this->assertEquals('Maria Therik, S.Pd.', $beritaAcara->penanggung_jawab_nama);
        $this->assertEquals('198501012010012001', $beritaAcara->penanggung_jawab_nip);

        // VERIFIKASI 4: Dokumen Cetak Kartu Peserta otomatis memuat Maria Therik
        $resKartu = $this->actingAs($this->admin)->get(route('admin.cetak_kartu'));
        $resKartu->assertStatus(200);
        $resKartu->assertSee('Maria Therik, S.Pd.');
        $resKartu->assertSee('198501012010012001');

        // VERIFIKASI 5: Dokumen Cetak Kartu Petugas otomatis memuat Maria Therik
        $resKartuPetugas = $this->actingAs($this->admin)->get(route('admin.cetak_kartu_petugas'));
        $resKartuPetugas->assertStatus(200);
        $resKartuPetugas->assertSee('Maria Therik, S.Pd.');
        $resKartuPetugas->assertSee('198501012010012001');

        // VERIFIKASI 6: Dokumen Cetak Daftar Hadir Petugas otomatis memuat Maria Therik
        $resDaftarHadirPetugas = $this->actingAs($this->admin)->get(route('admin.cetak_daftar_hadir_petugas'));
        $resDaftarHadirPetugas->assertStatus(200);
        $resDaftarHadirPetugas->assertSee('Maria Therik, S.Pd.');
        $resDaftarHadirPetugas->assertSee('198501012010012001');

        // VERIFIKASI 7: Dokumen Cetak Berita Acara otomatis memuat Maria Therik
        $resBeritaAcara = $this->actingAs($this->admin)->get(route('admin.cetak_berita_acara', $beritaAcara->id));
        $resBeritaAcara->assertStatus(200);
        $resBeritaAcara->assertSee('Maria Therik, S.Pd.');
        $resBeritaAcara->assertSee('198501012010012001');
    }

    public function test_daftar_hadir_petugas_diurutkan_sesuai_hirarki_sk(): void
    {
        // Bersihkan data petugas sebelumnya jika ada
        PetugasUjian::query()->delete();

        // Buat user sesuai nama-nama di SK
        $uBendahara = User::create(['name' => 'Sarah Otemusu, S.Pd.,Gr', 'username' => 'sarah', 'password' => bcrypt('123'), 'role' => 'guru']);
        $uKetua = User::create(['name' => 'Mesak P. Adu, S.Pd.,Gr', 'username' => 'mesak', 'password' => bcrypt('123'), 'role' => 'guru']);
        $uPenanggungjawab = User::create(['name' => 'Apolos Kofan, S. Pd. MM', 'username' => 'apolos', 'password' => bcrypt('123'), 'role' => 'guru']);
        $uSekretaris = User::create(['name' => 'Serli J. Taebenu, S.Pd.,Gr', 'username' => 'serli', 'password' => bcrypt('123'), 'role' => 'guru']);
        $uKoordinator = User::create(['name' => 'Bernabas B. B. Baun, S.Pd, Gr', 'username' => 'bernabas', 'password' => bcrypt('123'), 'role' => 'guru']);

        // Input acak
        PetugasUjian::create(['user_id' => $uBendahara->id, 'jenis_petugas' => 'Bendahara']);
        PetugasUjian::create(['user_id' => $uKetua->id, 'jenis_petugas' => 'Ketua Pelaksana']);
        PetugasUjian::create(['user_id' => $uPenanggungjawab->id, 'jenis_petugas' => 'Penanggungjawab Umum']);
        PetugasUjian::create(['user_id' => $uSekretaris->id, 'jenis_petugas' => 'Sekretaris']);
        PetugasUjian::create(['user_id' => $uKoordinator->id, 'jenis_petugas' => 'Koordinator Umum']);

        $res = $this->actingAs($this->admin)->get(route('admin.cetak_daftar_hadir_petugas'));
        $res->assertStatus(200);

        $content = $res->getContent();

        $posPenanggungjawab = strpos($content, 'Apolos Kofan, S. Pd. MM');
        $posKoordinator = strpos($content, 'Bernabas B. B. Baun, S.Pd, Gr');
        $posKetua = strpos($content, 'Mesak P. Adu, S.Pd.,Gr');
        $posSekretaris = strpos($content, 'Serli J. Taebenu, S.Pd.,Gr');
        $posBendahara = strpos($content, 'Sarah Otemusu, S.Pd.,Gr');

        $this->assertNotFalse($posPenanggungjawab, 'Penanggungjawab Umum harus ada di dokumen');
        $this->assertNotFalse($posKoordinator, 'Koordinator Umum harus ada di dokumen');
        $this->assertNotFalse($posKetua, 'Ketua Pelaksana harus ada di dokumen');
        $this->assertNotFalse($posSekretaris, 'Sekretaris harus ada di dokumen');
        $this->assertNotFalse($posBendahara, 'Bendahara harus ada di dokumen');

        // Pastikan urutannya berjenjang sesuai SK: Penanggungjawab -> Koordinator -> Ketua -> Sekretaris -> Bendahara
        $this->assertTrue($posPenanggungjawab < $posKoordinator, 'Penanggungjawab Umum harus sebelum Koordinator Umum');
        $this->assertTrue($posKoordinator < $posKetua, 'Koordinator Umum harus sebelum Ketua Pelaksana');
        $this->assertTrue($posKetua < $posSekretaris, 'Ketua Pelaksana harus sebelum Sekretaris');
        $this->assertTrue($posSekretaris < $posBendahara, 'Sekretaris harus sebelum Bendahara');
    }
}
