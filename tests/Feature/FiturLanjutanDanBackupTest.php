<?php

namespace Tests\Feature;

use App\Models\BankSoal;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\MasterSekolah;
use App\Models\MataPelajaran;
use App\Models\PetugasUjian;
use App\Models\Siswa;
use App\Models\SoalButir;
use App\Models\UjianJawaban;
use App\Models\UjianPeserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FiturLanjutanDanBackupTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekolah;
    protected $kelas;
    protected $mapel;
    protected $bankSoal;
    protected $jadwal;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'name' => 'Vicky Koroh, S.Pd., Gr.',
        ]);

        $this->sekolah = MasterSekolah::create([
            'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
            'npsn' => '50305148',
            'alamat' => 'Jl. Jurusan Bolok',
            'kecamatan' => 'Kupang Barat',
            'kabupaten' => 'Kupang',
            'provinsi' => 'Nusa Tenggara Timur',
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., Gr.',
            'nip_kepala_sekolah' => '198501192011011005',
            'tahun_ajaran_aktif' => '2026/2027',
            'semester_aktif' => 'Genap',
        ]);

        $this->kelas = Kelas::create([
            'kode_kelas' => 'X-TITL',
            'nama_kelas' => 'X TITL',
            'tingkat' => '10',
            'jurusan' => 'TITL',
            'wali_kelas' => 'Vicky Koroh, S.Pd., Gr.',
        ]);

        $this->mapel = MataPelajaran::create([
            'nama_mapel' => 'Dasar Teknik Ketenagalistrikan',
            'kode_mapel' => 'DKK-TITL-X',
            'kelompok' => 'Kejuruan',
            'jurusan' => 'TITL',
        ]);

        $this->bankSoal = BankSoal::create([
            'mata_pelajaran_id' => $this->mapel->id,
            'guru_id' => $this->admin->id,
            'kode_bank' => 'BS-TITL-X',
            'nama_bank' => 'Bank Soal TITL X',
            'kkm' => 75,
            'standar_preset' => 25,
            'status' => 'aktif',
        ]);

        $this->jadwal = JadwalUjian::create([
            'bank_soal_id' => $this->bankSoal->id,
            'nama_ujian' => 'Simulasi Asesmen 1',
            'jenis_ujian' => 'SIMULASI',
            'kode_ujian' => 'SIM-01',
            'token' => 'ABCDEF',
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addHour(),
            'durasi_menit' => 60,
            'status' => 'aktif',
        ]);
    }

    /** @test */
    public function nama_pak_vicky_tidak_memuat_skom_atau_mkom()
    {
        $sekolah = MasterSekolah::first();
        $this->assertEquals('Vicky Koroh, S.Pd., Gr.', $sekolah->kepala_sekolah);
        $this->assertStringNotContainsString('S.Kom', $sekolah->kepala_sekolah);
        $this->assertStringNotContainsString('M.Kom', $sekolah->kepala_sekolah);
    }

    /** @test */
    public function admin_dapat_mengunggah_foto_siswa_massal_zip()
    {
        $userSiswa = User::factory()->create([
            'username' => '0103841671',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0103841671',
            'nama_lengkap' => 'Letni Yakoharina Ledo',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'P',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
            'nomor_peserta' => '26-24-05-00001',
        ]);

        // Buat temporary zip file
        $tempZipPath = sys_get_temp_dir() . '/test_foto_' . time() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('0103841671.jpg', 'dummy image content');
        $zip->close();

        $uploadedZip = new UploadedFile($tempZipPath, 'foto_siswa.zip', 'application/zip', null, true);

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.upload_foto_zip'), [
            'file_zip' => $uploadedZip,
        ]);

        $response->assertRedirect(route('admin.siswa'));
        $response->assertSessionHas('success');

        $siswa->refresh();
        $this->assertNotNull($siswa->foto);
        $this->assertStringContainsString('0103841671.jpg', $siswa->foto);

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    /** @test */
    public function admin_dapat_menambah_dan_mengubah_penugasan_petugas()
    {
        $guru = User::factory()->create([
            'username' => 'guru_cbt_01',
            'name' => 'Guru Penugasan CBT',
            'role' => 'guru',
        ]);

        // Simpan penugasan
        $responseSimpan = $this->actingAs($this->admin)->post(route('admin.petugas_ujian.simpan'), [
            'user_id' => $guru->id,
            'jenis_petugas' => 'pengawas',
            'ruangan' => 'Lab Komputer 1',
            'sesi' => 'Sesi 1',
            'nomor_sk' => '421/001/2026',
        ]);
        $responseSimpan->assertSessionHas('success');

        $petugas = PetugasUjian::where('user_id', $guru->id)->first();
        $this->assertNotNull($petugas);

        // Update penugasan
        $responseUpdate = $this->actingAs($this->admin)->post(route('admin.petugas_ujian.update', $petugas->id), [
            'user_id' => $guru->id,
            'jenis_petugas' => 'proktor',
            'ruangan' => 'Lab Multimedia 2',
            'sesi' => 'Sesi 2',
            'nomor_sk' => '421/002/2026',
        ]);
        $responseUpdate->assertSessionHas('success');

        $petugas->refresh();
        $this->assertEquals('proktor', $petugas->jenis_petugas);
        $this->assertEquals('Lab Multimedia 2', $petugas->ruangan);
        $this->assertEquals('Sesi 2', $petugas->sesi);
    }

    /** @test */
    public function modul_laporan_nilai_dapat_diakses_dan_diekspor()
    {
        $userSiswa = User::factory()->create([
            'username' => 'siswa_nilai_01',
            'role' => 'siswa',
        ]);
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0098765432',
            'nama_lengkap' => 'Peserta Uji Nilai',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        UjianPeserta::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'selesai',
            'total_nilai' => 88.00,
            'nilai_pg' => 88.00,
            'jawaban_benar' => 22,
            'jawaban_salah' => 3,
            'jawaban_kosong' => 0,
        ]);

        $responseIndex = $this->actingAs($this->admin)->get(route('admin.laporan.nilai', [
            'jadwal_id' => $this->jadwal->id,
        ]));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Peserta Uji Nilai');
        $responseIndex->assertSee('88.00');

        $responseCetak = $this->actingAs($this->admin)->get(route('admin.laporan.nilai.cetak', [
            'jadwal_id' => $this->jadwal->id,
        ]));
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('Vicky Koroh, S.Pd., Gr.');

        $responseExport = $this->actingAs($this->admin)->get(route('admin.laporan.nilai.export_xlsx', [
            'jadwal_id' => $this->jadwal->id,
        ]));
        $responseExport->assertStatus(200);
    }

    /** @test */
    public function modul_analisis_butir_soal_dapat_diakses_dan_dikalkulasi()
    {
        $soal = SoalButir::create([
            'bank_soal_id' => $this->bankSoal->id,
            'nomor_urut' => 1,
            'jenis_soal' => 'pg',
            'konten_soal' => 'Pertanyaan uji daya pembeda',
            'opsi_a' => 'Opsi A',
            'opsi_b' => 'Opsi B',
            'opsi_c' => 'Opsi C',
            'opsi_d' => 'Opsi D',
            'opsi_e' => 'Opsi E',
            'kunci_jawaban' => 'A',
            'bobot' => 4.0,
        ]);

        $userSiswa = User::factory()->create([
            'username' => 'siswa_analisis_01',
            'role' => 'siswa',
        ]);
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0098765433',
            'nama_lengkap' => 'Siswa Analisis',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $peserta = UjianPeserta::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'selesai',
            'total_nilai' => 100.0,
        ]);

        UjianJawaban::create([
            'ujian_peserta_id' => $peserta->id,
            'soal_butir_id' => $soal->id,
            'jawaban_siswa' => 'A',
            'is_benar' => true,
            'nilai_diperoleh' => 4.0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan.analisis_soal', [
            'jadwal_id' => $this->jadwal->id,
        ]));
        $response->assertStatus(200);
        $response->assertSee('Analisis Butir Soal');

        $responseCetak = $this->actingAs($this->admin)->get(route('admin.laporan.analisis_soal.cetak', [
            'jadwal_id' => $this->jadwal->id,
        ]));
        $responseCetak->assertStatus(200);
    }

    /** @test */
    public function admin_dapat_mereset_dan_menambah_waktu_peserta()
    {
        $userSiswa = User::factory()->create([
            'username' => 'siswa_analisis_01',
            'role' => 'siswa',
        ]);
        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0098765434',
            'nama_lengkap' => 'Siswa Reset Waktu',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $peserta = UjianPeserta::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'berlangsung',
            'sisa_detik' => 600,
        ]);

        // Tambah waktu 15 menit
        $responseWaktu = $this->actingAs($this->admin)->post(route('admin.reset_ujian.tambah_waktu', $peserta->id), [
            'menit' => 15,
        ]);
        $responseWaktu->assertSessionHas('success');
        $peserta->refresh();
        $this->assertEquals(600 + (15 * 60), $peserta->sisa_detik);

        // Reset ujian total (ulang tes)
        $responseReset = $this->actingAs($this->admin)->post(route('admin.reset_ujian.reset_tes', $peserta->id));
        $responseReset->assertSessionHas('success');
        $peserta->refresh();
        $this->assertEquals('belum', $peserta->status);
    }

    /** @test */
    public function backup_database_dan_export_import_bank_soal_berfungsi()
    {
        // 1. Backup database
        $responseBackup = $this->actingAs($this->admin)->post(route('admin.backup.database'));
        $responseBackup->assertSessionHas('success');

        // 2. Export Bank Soal JSON
        $responseExport = $this->actingAs($this->admin)->get(route('admin.backup.bank_soal.export_json', $this->bankSoal->id));
        $responseExport->assertStatus(200);
        $responseExport->assertHeader('Content-Type', 'application/json');

        $exportedJson = $responseExport->getContent();
        $this->assertStringContainsString('Bank Soal TITL X', $exportedJson);

        // 3. Import Bank Soal JSON
        $tempJsonPath = sys_get_temp_dir() . '/test_bank_soal_' . time() . '.json';
        file_put_contents($tempJsonPath, $exportedJson);

        $uploadedJson = new UploadedFile($tempJsonPath, 'backup.json', 'application/json', null, true);
        $responseImport = $this->actingAs($this->admin)->post(route('admin.backup.bank_soal.import_json'), [
            'file_json' => $uploadedJson,
        ]);
        $responseImport->assertSessionHas('success');

        if (file_exists($tempJsonPath)) {
            unlink($tempJsonPath);
        }
    }

    /** @test */
    public function sistem_mencatat_pelanggaran_pindah_tab_siswa()
    {
        $userSiswa = User::factory()->create([
            'username' => 'siswa_anticheat_01',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0098765499',
            'nama_lengkap' => 'Siswa Anti Cheat',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $peserta = UjianPeserta::create([
            'jadwal_ujian_id' => $this->jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'berlangsung',
            'jumlah_pelanggaran' => 0,
        ]);

        $response = $this->actingAs($userSiswa)->postJson(route('siswa.pelanggaran', $this->jadwal->id), [
            'type' => 'tab_switch',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'pelanggaran' => 1,
        ]);

        $peserta->refresh();
        $this->assertEquals(1, $peserta->jumlah_pelanggaran);
    }

    /** @test */
    public function cetak_kartu_bisa_difilter_per_kelas_dan_sesi()
    {
        $kelasLain = Kelas::create([
            'kode_kelas' => 'XI-TKRO',
            'nama_kelas' => 'XI TKRO',
            'tingkat' => '11',
            'jurusan' => 'TKRO',
            'wali_kelas' => 'Guru TKRO',
        ]);

        $user1 = User::factory()->create(['username' => 's_titl_01', 'role' => 'siswa']);
        $siswaTITL = Siswa::create([
            'user_id' => $user1->id,
            'nisn' => '0011111111',
            'nama_lengkap' => 'Siswa TITL Saja',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab 1',
        ]);

        $user2 = User::factory()->create(['username' => 's_tkro_01', 'role' => 'siswa']);
        $siswaTKRO = Siswa::create([
            'user_id' => $user2->id,
            'nisn' => '0022222222',
            'nama_lengkap' => 'Siswa TKRO Saja',
            'kelas_id' => $kelasLain->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 2',
            'ruangan_ujian' => 'Lab 2',
        ]);

        // Filter per kelas TITL saja
        $responseFilter = $this->actingAs($this->admin)->get(route('admin.cetak_kartu', [
            'kelas_id' => $this->kelas->id,
        ]));

        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('SISWA TITL SAJA');
        $responseFilter->assertDontSee('SISWA TKRO SAJA');
    }
}
