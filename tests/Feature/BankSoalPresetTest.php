<?php

namespace Tests\Feature;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\SoalButir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class BankSoalPresetTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $mapel;
    protected $bank;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test',
        ]);

        $this->mapel = MataPelajaran::create([
            'kode_mapel' => 'TKA-SIMDIG',
            'nama_mapel' => 'Simulasi Digital',
            'jurusan' => 'Semua Jurusan',
            'tingkat' => '10',
        ]);

        $this->bank = BankSoal::create([
            'kode_bank' => 'BS-TEST-PRESET',
            'nama_bank' => 'Paket Uji Preset',
            'mata_pelajaran_id' => $this->mapel->id,
            'guru_id' => $this->admin->id,
            'standar_bobot' => '25_soal',
            'jumlah_soal_bs' => 5,
            'bobot_bs' => 20,
            'jumlah_soal_pg' => 15,
            'bobot_pg' => 30,
            'jumlah_soal_uraian' => 5,
            'bobot_uraian' => 50,
            'kkm' => 75,
            'status' => 'aktif',
        ]);
    }

    public function test_detail_page_renders_preset_modal_and_formula_buttons()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal.detail', $this->bank->id));
        $response->assertStatus(200);
        $response->assertSee('modalPilihTemplate');
        $response->assertSee('Preset 25 Nomor');
        $response->assertSee('Preset 40 Nomor');
        $response->assertSee('btn-formula');
        $response->assertSee('tambah_konten_preview');
        $response->assertSee('edit_konten_preview');
    }

    public function test_download_template_preset_25_generates_25_rows_with_100_points()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal.template_xlsx', [
            'id' => $this->bank->id,
            'preset' => 'preset_25',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=Template_Soal_bs-test-preset_Preset_25_Nomor.xlsx');

        ob_start();
        $response->sendContent();
        $binary = ob_get_clean();

        $tmp = tempnam(sys_get_temp_dir(), 'test_25_');
        file_put_contents($tmp, $binary);

        $spreadsheet = IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // 5 header/banner rows + 25 question rows = row 30
        $this->assertEquals(30, $highestRow);
        $this->assertEquals(25, $highestRow - 5);

        // Check sum of bobot in col K
        $totalBobot = 0;
        for ($r = 6; $r <= $highestRow; $r++) {
            $totalBobot += (float)$sheet->getCell('K' . $r)->getValue();
        }
        $this->assertEquals(100.0, $totalBobot);

        unlink($tmp);
    }

    public function test_download_template_preset_40_generates_40_rows_with_100_points()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal.template_xlsx', [
            'id' => $this->bank->id,
            'preset' => 'preset_40',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=Template_Soal_bs-test-preset_Preset_40_Nomor.xlsx');

        ob_start();
        $response->sendContent();
        $binary = ob_get_clean();

        $tmp = tempnam(sys_get_temp_dir(), 'test_40_');
        file_put_contents($tmp, $binary);

        $spreadsheet = IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // 5 header/banner rows + 40 question rows = row 45
        $this->assertEquals(45, $highestRow);
        $this->assertEquals(40, $highestRow - 5);

        // Check sum of bobot in col K
        $totalBobot = 0;
        for ($r = 6; $r <= $highestRow; $r++) {
            $totalBobot += (float)$sheet->getCell('K' . $r)->getValue();
        }
        $this->assertEquals(100.0, $totalBobot);

        unlink($tmp);
    }

    public function test_import_preset_25_and_preset_40_templates()
    {
        // 1. Download & Import Preset 25
        $resp25 = $this->actingAs($this->admin)->get(route('admin.bank_soal.template_xlsx', [
            'id' => $this->bank->id,
            'preset' => 'preset_25',
        ]));

        ob_start();
        $resp25->sendContent();
        $content25 = ob_get_clean();

        $tmp25 = tempnam(sys_get_temp_dir(), 'imp_25_');
        file_put_contents($tmp25, $content25);
        $file25 = new UploadedFile($tmp25, 'preset25.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $importResp25 = $this->actingAs($this->admin)->post(route('admin.bank_soal.import_xlsx', $this->bank->id), [
            'file_excel' => $file25,
        ]);
        $importResp25->assertRedirect();

        $this->assertEquals(25, $this->bank->butirSoal()->count());
        $this->assertEquals(100.0, (float)$this->bank->butirSoal()->sum('bobot'));
        unlink($tmp25);

        // 2. Download & Import Preset 40
        $resp40 = $this->actingAs($this->admin)->get(route('admin.bank_soal.template_xlsx', [
            'id' => $this->bank->id,
            'preset' => 'preset_40',
        ]));

        ob_start();
        $resp40->sendContent();
        $content40 = ob_get_clean();

        $tmp40 = tempnam(sys_get_temp_dir(), 'imp_40_');
        file_put_contents($tmp40, $content40);
        $file40 = new UploadedFile($tmp40, 'preset40.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Clear existing questions first
        $this->bank->butirSoal()->delete();

        $importResp40 = $this->actingAs($this->admin)->post(route('admin.bank_soal.import_xlsx', $this->bank->id), [
            'file_excel' => $file40,
        ]);
        $importResp40->assertRedirect();

        $this->assertEquals(40, $this->bank->butirSoal()->count());
        $this->assertEquals(100.0, (float)$this->bank->butirSoal()->sum('bobot'));
        unlink($tmp40);
    }

    public function test_bank_soal_index_renders_edit_button_and_modal()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal'));
        $response->assertStatus(200);
        $response->assertSee('editBankSoal(');
        $response->assertSee('modalEditBank');
        $response->assertSee('formEditBank');
    }

    public function test_admin_can_update_bank_soal_mapel_and_details()
    {
        $newMapel = MataPelajaran::create([
            'kode_mapel' => 'C2-TITL-X',
            'nama_mapel' => 'Dasar Listrik',
            'jurusan' => 'Teknik Instalasi Tenaga Listrik',
            'tingkat' => '10',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bank_soal.update', $this->bank->id), [
            'kode_bank' => 'BS-TITL-40-BARU',
            'nama_bank' => 'Bank Soal TITL Terstandar',
            'mata_pelajaran_id' => $newMapel->id,
            'tingkat' => '10',
            'standar_preset' => '40',
            'kkm' => 80,
            'status' => 'aktif',
        ]);

        $response->assertRedirect();
        $this->bank->refresh();

        $this->assertEquals('BS-TITL-40-BARU', $this->bank->kode_bank);
        $this->assertEquals('Bank Soal TITL Terstandar', $this->bank->nama_bank);
        $this->assertEquals($newMapel->id, $this->bank->mata_pelajaran_id);
        $this->assertEquals('40_soal', $this->bank->standar_bobot);
        $this->assertEquals(80.0, (float)$this->bank->kkm);
        $this->assertEquals('aktif', $this->bank->status);
    }

    public function test_sample_questions_kka_kelas_x_sesuai_materi_dan_tanpa_python()
    {
        $controller = new \App\Http\Controllers\AdminController();
        $questions = $controller->getSampleQuestionsKKA25(4.0, 2.0, 10.0);

        $this->assertCount(25, $questions);

        $totalBobot = 0;
        $combinedText = '';

        foreach ($questions as $q) {
            $nomor = $q[0];
            $jenis = $q[1];
            $soal = $q[2];
            $bobot = $q[10];

            $totalBobot += $bobot;
            $combinedText .= ' ' . $soal . ' ' . $q[3] . ' ' . $q[4] . ' ' . $q[5] . ' ' . $q[6] . ' ' . $q[7];

            if ($nomor <= 5) {
                $this->assertEquals('BENAR_SALAH', $jenis);
                $this->assertEquals(4.0, $bobot);
            } elseif ($nomor <= 20) {
                $this->assertEquals('PG', $jenis);
                $this->assertEquals(2.0, $bobot);
            } else {
                $this->assertEquals('URAIAN', $jenis);
                $this->assertEquals(10.0, $bobot);
            }
        }

        // Total nilai harus 100 poin penuh
        $this->assertEquals(100.0, $totalBobot);

        // Verifikasi topik sesuai instruksi user:
        // 1. Pengertian dan sejarah AI (John McCarthy / Dartmouth 1956)
        $this->assertStringContainsString('Artificial Intelligence', $combinedText);
        $this->assertStringContainsString('John McCarthy', $combinedText);
        $this->assertStringContainsString('1956', $combinedText);

        // 2. Isian algoritma sehari-hari (berangkat sekolah)
        $this->assertStringContainsString('berangkat ke sekolah', $combinedText);

        // 3. Kode dasar HTML (tag dasar HTML)
        $this->assertStringContainsString('HTML', $combinedText);
        $this->assertStringContainsString('HyperText Markup Language', $combinedText);
        $this->assertStringContainsString('<body>', $combinedText);
        $this->assertStringContainsString('<h1>', $combinedText);

        // 4. Simbol-simbol flowchart (Terminator, Process, Input/Output, Decision)
        $this->assertStringContainsString('Terminator', $combinedText);
        $this->assertStringContainsString('Persegi Panjang', $combinedText);
        $this->assertStringContainsString('Jajar Genjang', $combinedText);
        $this->assertStringContainsString('flowchart', strtolower($combinedText));

        // 5. TIDAK BOLEH memuat kata Python sama sekali (sesuai instruksi user: "jangan python")
        $this->assertStringNotContainsStringIgnoringCase('python', $combinedText);
    }

    public function test_pratinjau_bank_soal_kka_merender_soal_dan_kunci_dengan_tepat()
    {
        // Seeder KKA
        $seeder = new \Database\Seeders\BankSoalPresetSeeder();
        $seeder->run();

        $bankKka = BankSoal::where('kode_bank', 'BS-KKA-X-25')->first();
        $this->assertNotNull($bankKka);
        $this->assertEquals(25, $bankKka->butirSoal()->count());

        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal.pratinjau', $bankKka->id));
        $response->assertStatus(200);
        $response->assertSee('safeRenderText');
        $response->assertSee('Kunci Jawaban & Rubrik Penilaian Resmi', false);
        $response->assertSee('Artificial Intelligence');
        $response->assertSee('John McCarthy');
    }

    public function test_halaman_ujian_siswa_tidak_membocorkan_kunci_jawaban_dan_rubrik()
    {
        $seeder = new \Database\Seeders\BankSoalPresetSeeder();
        $seeder->run();

        $bankKka = BankSoal::where('kode_bank', 'BS-KKA-X-25')->first();
        $this->assertNotNull($bankKka);

        $kelas = \App\Models\Kelas::create([
            'kode_kelas' => 'X-RPL',
            'nama_kelas' => 'X RPL',
            'tingkat' => 10,
            'jurusan' => 'RPL',
        ]);

        $userSiswa = User::factory()->create([
            'username' => 'siswa_kka_01',
            'role' => 'siswa',
        ]);

        $siswa = \App\Models\Siswa::create([
            'user_id' => $userSiswa->id,
            'nisn' => '0098765411',
            'nama_lengkap' => 'Siswa Penguji KKA',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $jadwal = \App\Models\JadwalUjian::create([
            'nama_ujian' => 'Ujian Akhir KKA',
            'bank_soal_id' => $bankKka->id,
            'kelas_id' => $kelas->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addHour(),
            'durasi_menit' => 90,
            'token' => 'KKA123',
            'status' => 'aktif',
        ]);

        $peserta = \App\Models\UjianPeserta::create([
            'jadwal_ujian_id' => $jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'berlangsung',
            'sisa_detik' => 5400,
            'waktu_mulai' => now(),
        ]);

        $response = $this->actingAs($userSiswa)->get(route('siswa.ujian', $jadwal->id));
        $response->assertStatus(200);

        // 1. Tampilan siswa TIDAK BOLEH memuat badge dan teks rubrik kunci jawaban
        $response->assertDontSee('Kunci Jawaban & Rubrik Penilaian Resmi');
        $response->assertDontSee('✓ Kunci Jawaban');

        // 2. Kunci jawaban juga disembunyikan dari JSON payload (makeHidden)
        $response->assertDontSee('"kunci_jawaban"');
        $response->assertDontSee('"rubrik_penilaian"');
    }

    public function test_satu_lab_beda_kelas_beda_mapel_hanya_menampilkan_ujian_kelasnya_masing_masing()
    {
        $seeder = new \Database\Seeders\BankSoalPresetSeeder();
        $seeder->run();

        // 1. Setup 2 Kelas berbeda
        $kelasRpl = \App\Models\Kelas::create([
            'kode_kelas' => 'X-RPL-A',
            'nama_kelas' => 'X RPL A',
            'tingkat' => 10,
            'jurusan' => 'RPL',
        ]);

        $kelasDpib = \App\Models\Kelas::create([
            'kode_kelas' => 'X-DPIB-A',
            'nama_kelas' => 'X DPIB A',
            'tingkat' => 10,
            'jurusan' => 'DPIB',
        ]);

        // 2. Setup 2 Mapel & Bank Soal berbeda
        $bankKka = BankSoal::where('kode_bank', 'BS-KKA-X-25')->first();

        $mapelDpib = MataPelajaran::create([
            'kode_mapel' => 'DPIB-GT',
            'nama_mapel' => 'Gambar Teknik Bangunan',
            'jurusan' => 'DPIB',
            'tingkat' => '10',
        ]);

        $bankDpib = BankSoal::create([
            'kode_bank' => 'BS-GT-10',
            'nama_bank' => 'Bank Soal Gambar Teknik',
            'mata_pelajaran_id' => $mapelDpib->id,
            'guru_id' => $this->admin->id,
            'standar_bobot' => '25_soal',
            'status' => 'aktif',
        ]);

        // 3. Setup 2 Jadwal Ujian yang berbeda
        $jadwalKka = \App\Models\JadwalUjian::create([
            'nama_ujian' => 'Penilaian Harian KKA RPL',
            'bank_soal_id' => $bankKka->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addHour(),
            'durasi_menit' => 90,
            'token' => 'KKA001',
            'status' => 'aktif',
        ]);
        $jadwalKka->kelas()->sync([$kelasRpl->id]);

        $jadwalDpib = \App\Models\JadwalUjian::create([
            'nama_ujian' => 'Penilaian Harian Gambar Teknik DPIB',
            'bank_soal_id' => $bankDpib->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addHour(),
            'durasi_menit' => 90,
            'token' => 'DPIB01',
            'status' => 'aktif',
        ]);
        $jadwalDpib->kelas()->sync([$kelasDpib->id]);

        // 4. Setup 2 Siswa di Lab yang SAMA (Lab Komputer 1) dan Sesi yang SAMA (Sesi 1)
        $userSiswaRpl = User::factory()->create(['role' => 'siswa', 'username' => 'siswa_rpl_01']);
        $siswaRpl = \App\Models\Siswa::create([
            'user_id' => $userSiswaRpl->id,
            'nisn' => '0011223344',
            'nama_lengkap' => 'Siswa RPL Lab 1',
            'kelas_id' => $kelasRpl->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        $userSiswaDpib = User::factory()->create(['role' => 'siswa', 'username' => 'siswa_dpib_01']);
        $siswaDpib = \App\Models\Siswa::create([
            'user_id' => $userSiswaDpib->id,
            'nisn' => '0055667788',
            'nama_lengkap' => 'Siswa DPIB Lab 1',
            'kelas_id' => $kelasDpib->id,
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab Komputer 1',
        ]);

        // Verifikasi Siswa RPL: Hanya melihat Penilaian Harian KKA RPL
        $resRpl = $this->actingAs($userSiswaRpl)->get(route('siswa.dashboard'));
        $resRpl->assertStatus(200);
        $resRpl->assertSee('Penilaian Harian KKA RPL');
        $resRpl->assertDontSee('Penilaian Harian Gambar Teknik DPIB');

        // Siswa RPL tidak boleh mengakses jadwal DPIB langsung via URL
        $bypassRpl = $this->actingAs($userSiswaRpl)->get(route('siswa.konfirmasi', $jadwalDpib->id));
        $bypassRpl->assertRedirect(route('siswa.dashboard'));
        $bypassRpl->assertSessionHas('error', 'Ujian ini bukan untuk kelas Anda.');

        // Verifikasi Siswa DPIB: Hanya melihat Penilaian Harian Gambar Teknik DPIB
        $resDpib = $this->actingAs($userSiswaDpib)->get(route('siswa.dashboard'));
        $resDpib->assertStatus(200);
        $resDpib->assertSee('Penilaian Harian Gambar Teknik DPIB');
        $resDpib->assertDontSee('Penilaian Harian KKA RPL');

        // Siswa DPIB tidak boleh mengakses jadwal KKA langsung via URL
        $bypassDpib = $this->actingAs($userSiswaDpib)->get(route('siswa.konfirmasi', $jadwalKka->id));
        $bypassDpib->assertRedirect(route('siswa.dashboard'));
        $bypassDpib->assertSessionHas('error', 'Ujian ini bukan untuk kelas Anda.');
    }
}
