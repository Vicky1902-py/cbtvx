<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MasterSekolah;
use App\Models\MataPelajaran;
use App\Models\BankSoal;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\BeritaAcara;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DaftarHadirDanBeritaAcaraTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekolah;
    protected $kelasX;
    protected $kelasXI;
    protected $mapel;
    protected $bankSoal;
    protected $jadwal;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Vicky Koroh, S.Pd., Gr.',
            'username' => 'admin',
            'email' => 'admin@smkn1kupangbarat.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'aktif',
            'nip' => '198501192011011005',
        ]);

        $this->sekolah = MasterSekolah::create([
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '69727541',
            'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kabupaten Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., Gr.',
            'nip_kepala_sekolah' => '198501192011011005',
        ]);

        $this->kelasX = Kelas::create([
            'kode_kelas' => 'X-TPFL',
            'nama_kelas' => 'X TPFL',
            'tingkat' => '10',
            'jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam',
        ]);

        $this->kelasXI = Kelas::create([
            'kode_kelas' => 'XI-TP',
            'nama_kelas' => 'XI TP',
            'tingkat' => '11',
            'jurusan' => 'Teknik Pengelasan',
        ]);

        // Siswa di kelas X TPFL
        $userS1 = User::create([
            'name' => 'Letni Yakoharina Ledo',
            'username' => 'K2402001000018',
            'email' => 's1@siswa.test',
            'password' => bcrypt('pass'),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);
        Siswa::create([
            'user_id' => $userS1->id,
            'nisn' => '0103841671',
            'nama_lengkap' => 'Letni Yakoharina Ledo',
            'kelas_id' => $this->kelasX->id,
            'sesi_ujian' => 'Sesi 1',
            'nomor_peserta' => 'K2402001000018',
        ]);

        $userS2 = User::create([
            'name' => 'Arislin Missa',
            'username' => 'TK2402001000098-7',
            'email' => 's2@siswa.test',
            'password' => bcrypt('pass'),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);
        Siswa::create([
            'user_id' => $userS2->id,
            'nisn' => '0103841672',
            'nama_lengkap' => 'Arislin Missa',
            'kelas_id' => $this->kelasX->id,
            'sesi_ujian' => 'Sesi 1',
            'nomor_peserta' => 'TK2402001000098-7',
        ]);

        $this->mapel = MataPelajaran::create([
            'kode_mapel' => 'DKK-TPFL-X',
            'nama_mapel' => 'DKK TPFL',
            'kelompok' => 'Dasar Program Keahlian (DKK)',
            'jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam',
            'tingkat' => '10',
        ]);

        $this->bankSoal = BankSoal::create([
            'kode_bank' => 'BS-TPFL-40',
            'mata_pelajaran_id' => $this->mapel->id,
            'guru_id' => $this->admin->id,
            'judul' => 'Bank Soal DKK TPFL Terstandar',
            'tingkat' => '10',
            'preset_pilihan' => 'preset_40',
            'total_soal' => 40,
            'bobot_pg' => 40,
            'bobot_bs' => 20,
            'bobot_uraian' => 40,
        ]);

        $this->jadwal = JadwalUjian::create([
            'nama_ujian' => 'TES KEMAMPUAN AKADEMIK SMK',
            'bank_soal_id' => $this->bankSoal->id,
            'waktu_mulai' => '2026-11-02 08:00:00',
            'waktu_selesai' => '2026-11-02 09:45:00',
            'durasi_menit' => 105,
            'token_ujian' => 'TKA2026',
            'status' => 'aktif',
        ]);
        $this->jadwal->kelas()->attach([$this->kelasX->id, $this->kelasXI->id]);
    }

    public function test_verifikasi_nomenklatur_tp_dan_tpfl_bebas_pemesinan(): void
    {
        // Pastikan tidak ada jurusan Teknik Pemesinan
        $this->assertDatabaseMissing('mata_pelajaran', ['jurusan' => 'Teknik Pemesinan']);
        $this->assertDatabaseMissing('kelas', ['jurusan' => 'Teknik Pemesinan']);

        // Pastikan TPFL untuk Kelas 10 dan TP untuk Kelas 11
        $this->assertDatabaseHas('kelas', [
            'nama_kelas' => 'X TPFL',
            'jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam',
            'tingkat' => '10',
        ]);
        $this->assertDatabaseHas('kelas', [
            'nama_kelas' => 'XI TP',
            'jurusan' => 'Teknik Pengelasan',
            'tingkat' => '11',
        ]);
    }

    public function test_halaman_index_daftar_hadir_dan_berita_acara_dapat_diakses(): void
    {
        $resDH = $this->actingAs($this->admin)->get(route('admin.daftar_hadir'));
        $resDH->assertStatus(200);
        $resDH->assertSee('Daftar Hadir Peserta Asesmen');
        $resDH->assertSee('X TPFL');

        $resBA = $this->actingAs($this->admin)->get(route('admin.berita_acara'));
        $resBA->assertStatus(200);
        $resBA->assertSee('Berita Acara Pelaksanaan Asesmen');
        $resBA->assertSee('X TPFL');
    }

    public function test_form_isi_daftar_hadir_dan_simpan_presensi_per_kelas_dan_sesi(): void
    {
        $resForm = $this->actingAs($this->admin)->get(route('admin.daftar_hadir.form', [
            'id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => 'Sesi 1',
        ]));
        $resForm->assertStatus(200);
        $resForm->assertSee('Form Presensi Daftar Hadir Ujian');
        $resForm->assertSee('LETNI YAKOHARINA LEDO');

        // Simpan Presensi: 1 Hadir, 1 Tidak Hadir (Arislin Missa absen)
        $siswa1 = Siswa::where('nama_lengkap', 'Letni Yakoharina Ledo')->first();
        $siswa2 = Siswa::where('nama_lengkap', 'Arislin Missa')->first();

        $postData = [
            'kelas_id' => $this->kelasX->id,
            'sesi' => 'Sesi 1',
            'ruangan' => 'TKA 1',
            'id_server' => 'K24020010-AS4K',
            'kode_kabupaten' => '02',
            'kode_sekolah' => '0010',
            'hari_tanggal' => 'Senin, 02 November 2026',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '09:45',
            'nama_proktor' => 'Izak Robinson Koroh, S.Pd.,Gr',
            'nip_proktor' => '199002192020121003',
            'nama_pengawas' => 'Mike Amin Imanuel Modok',
            'nip_pengawas' => '8843778679130052',
            'kehadiran' => [
                $siswa1->id => 'hadir',
                $siswa2->id => 'tidak_hadir',
            ],
            'keterangan_siswa' => [
                $siswa2->id => 'Sakit',
            ],
            'catatan_pelaksanaan' => 'UJIAN BERJALAN AMAN DAN LANCAR',
        ];

        $resSave = $this->actingAs($this->admin)->post(route('admin.daftar_hadir.simpan', $this->jadwal->id), $postData);
        $resSave->assertRedirect();

        // Verifikasi database Berita Acara terisi rekap presensi
        $this->assertDatabaseHas('berita_acara', [
            'jadwal_ujian_id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => 'Sesi 1',
            'jumlah_peserta_seharusnya' => 2,
            'jumlah_peserta_hadir' => 1,
            'jumlah_peserta_tidak_hadir' => 1,
            'nama_proktor' => 'Izak Robinson Koroh, S.Pd.,Gr',
            'nama_pengawas' => 'Mike Amin Imanuel Modok',
        ]);
    }

    public function test_cetak_daftar_hadir_format_pusmendik_a4_presisi(): void
    {
        // Buat BeritaAcara presensi
        BeritaAcara::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => '1',
            'ruangan' => 'TKA 1',
            'id_server' => 'K24020010-AS4K',
            'kode_kabupaten' => '02',
            'kode_sekolah' => '0010',
            'hari_tanggal' => 'Senin, 02 November 2026',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '09:45',
            'nama_proktor' => 'Izak Robinson Koroh, S.Pd.,Gr',
            'nip_proktor' => '199002192020121003',
            'nama_pengawas' => 'Mike Amin Imanuel Modok',
            'nip_pengawas' => '8843778679130052',
            'jumlah_peserta_seharusnya' => 2,
            'jumlah_peserta_hadir' => 2,
            'jumlah_peserta_tidak_hadir' => 0,
        ]);

        $res = $this->actingAs($this->admin)->get(route('admin.cetak_daftar_hadir', [
            'id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => 'Sesi 1',
        ]));

        $res->assertStatus(200);
        $res->assertSee('DAFTAR HADIR PESERTA');
        $res->assertSee($this->sekolah->logo_sekolah_url);
        $res->assertSee('TES KEMAMPUAN AKADEMIK SMK');
        $res->assertSee('K24020010-AS4K / TKA 1');
        $res->assertSee('Izak Robinson Koroh, S.Pd.,Gr');
        $res->assertSee('Mike Amin Imanuel Modok');
        $res->assertSee('KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH');
    }

    public function test_cetak_berita_acara_format_pusmendik_3_ttd(): void
    {
        BeritaAcara::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => '1',
            'ruangan' => 'TKA 1',
            'id_server' => 'K24020010-AS4K',
            'kode_kabupaten' => '02',
            'kode_sekolah' => '0010',
            'nama_tes' => 'Simulasi Ujian CBT Semester Genap',
            'hari_tanggal' => 'Senin, 02 November 2026',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '09:45',
            'nama_proktor' => 'Izak Robinson Koroh, S.Pd.,Gr',
            'nip_proktor' => '199002192020121003',
            'nama_pengawas' => 'Mike Amin Imanuel Modok',
            'nip_pengawas' => '8843778679130052',
            'penanggung_jawab_nama' => 'Apolos Kofan, S.Pd.,MM',
            'penanggung_jawab_nip' => '196704062006041009',
            'jumlah_peserta_seharusnya' => 18,
            'jumlah_peserta_hadir' => 16,
            'jumlah_peserta_tidak_hadir' => 2,
            'daftar_tidak_hadir' => 'TK2402001000098-7, TK2402001000152-7',
            'catatan_pelaksanaan' => 'UJIAN BERJALAN AMAN DAN LANCAR',
        ]);

        $res = $this->actingAs($this->admin)->get(route('admin.cetak_berita_acara', [
            'id' => $this->jadwal->id,
            'kelas_id' => $this->kelasX->id,
            'sesi' => '1',
        ]));

        $res->assertStatus(200);
        $res->assertSee('BERITA ACARA PELAKSANAAN');
        $res->assertSee($this->sekolah->logo_sekolah_url);
        $res->assertSee('SIMULASI UJIAN CBT SEMESTER GENAP');
        $res->assertSee('K24020010-AS4K');
        $res->assertSee('TK2402001000098-7, TK2402001000152-7');
        $res->assertSee('Izak Robinson Koroh, S.Pd.,Gr');
        $res->assertSee('Mike Amin Imanuel Modok');
        $res->assertSee('Apolos Kofan, S.Pd.,MM');
        $res->assertSee('KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH');
    }
}
