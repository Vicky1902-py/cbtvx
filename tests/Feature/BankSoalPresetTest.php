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

    public function test_admin_dapat_mengunduh_template_word_docx()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bank_soal.template_docx', $this->bank->id));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $this->assertStringContainsString('.docx', $response->headers->get('content-disposition'));
    }

    public function test_admin_dapat_impor_soal_word_docx_dengan_gambar_otomatis()
    {
        // 1. Buat berkas .docx riil dalam memori/temp dengan gambar
        $tmpDocx = tempnam(sys_get_temp_dir(), 'test_docx_import_') . '.docx';
        $zip = new \ZipArchive();
        $zip->open($tmpDocx, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Default Extension="png" ContentType="image/png"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>');

        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>');

        $zip->addFromString('word/_rels/document.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rIdImg1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
</Relationships>');

        $pngSample = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $zip->addFromString('word/media/image1.png', $pngSample);

        $docXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" 
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" 
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
  <w:body>
    <w:p><w:r><w:t>1. [PG] Perhatikan diagram las berikut ini!</w:t></w:r></w:p>
    <w:p><w:r><w:drawing><a:blip r:embed="rIdImg1"/></w:drawing></w:r></w:p>
    <w:p><w:r><w:t>A. Las SMAW</w:t></w:r></w:p>
    <w:p><w:r><w:t>B. Las GMAW</w:t></w:r></w:p>
    <w:p><w:r><w:t>C. Las GTAW</w:t></w:r></w:p>
    <w:p><w:r><w:t>D. Las OAW</w:t></w:r></w:p>
    <w:p><w:r><w:t>E. Las SAW</w:t></w:r></w:p>
    <w:p><w:r><w:t>Kunci: A</w:t></w:r></w:p>
    <w:p><w:r><w:t>Bobot: 2</w:t></w:r></w:p>
  </w:body>
</w:document>';

        $zip->addFromString('word/document.xml', $docXml);
        $zip->close();

        // 2. Upload file docx ke route import_docx
        $uploaded = new \Illuminate\Http\UploadedFile(
            $tmpDocx,
            'soal_bergambar.docx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.bank_soal.import_docx', $this->bank->id), [
            'file_docx' => $uploaded,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 3. Verifikasi soal tersimpan di database dan memiliki gambar_soal
        $soal = \App\Models\SoalButir::where('bank_soal_id', $this->bank->id)->where('nomor_urut', 1)->first();
        $this->assertNotNull($soal);
        $this->assertEquals('pg', $soal->jenis_soal);
        $this->assertEquals('A', $soal->kunci_jawaban);
        $this->assertNotNull($soal->gambar_soal);
        $this->assertStringStartsWith('uploads/soal/soal_docx_', $soal->gambar_soal);

        // Pastikan file gambar fisik ada di public/uploads/soal/
        $this->assertFileExists(public_path($soal->gambar_soal));

        // Bersihkan file fisik yang digenerate test
        @unlink(public_path($soal->gambar_soal));
        @unlink($tmpDocx);
    }

    public function test_admin_dapat_impor_soal_excel_xlsx_dengan_gambar_drawing_otomatis()
    {
        // 1. Buat Spreadsheet riil dengan Drawing
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Baris 1-5 Header/Banner
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'Tipe Soal');
        $sheet->setCellValue('C5', 'Pertanyaan');
        $sheet->setCellValue('D5', 'Pilihan A');
        $sheet->setCellValue('E5', 'Pilihan B');
        $sheet->setCellValue('I5', 'Kunci');
        $sheet->setCellValue('K5', 'Bobot');
        $sheet->setCellValue('L5', 'Gambar');

        // Baris 6 Soal Nomor 1
        $sheet->setCellValue('A6', 1);
        $sheet->setCellValue('B6', 'PG');
        $sheet->setCellValue('C6', 'Perhatikan skema motor listrik pada gambar!');
        $sheet->setCellValue('D6', 'Motor Induksi 3 Fasa');
        $sheet->setCellValue('E6', 'Motor DC Shunt');
        $sheet->setCellValue('I6', 'A');
        $sheet->setCellValue('K6', 2.0);

        // Temp image untuk drawing
        $tmpImg = tempnam(sys_get_temp_dir(), 'test_img_drawing_') . '.png';
        file_put_contents($tmpImg, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Motor Skema');
        $drawing->setPath($tmpImg);
        $drawing->setCoordinates('L6');
        $drawing->setWorksheet($sheet);

        $tmpXlsx = tempnam(sys_get_temp_dir(), 'test_xlsx_import_') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tmpXlsx);

        // 2. Upload file XLSX ke route import_xlsx
        $uploaded = new \Illuminate\Http\UploadedFile(
            $tmpXlsx,
            'soal_drawing.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.bank_soal.import_xlsx', $this->bank->id), [
            'file_excel' => $uploaded,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 3. Verifikasi soal tersimpan di database dan memiliki gambar_soal hasil drawing
        $soal = \App\Models\SoalButir::where('bank_soal_id', $this->bank->id)->where('nomor_urut', 1)->first();
        $this->assertNotNull($soal);
        $this->assertEquals('pg', $soal->jenis_soal);
        $this->assertEquals('A', $soal->kunci_jawaban);
        $this->assertNotNull($soal->gambar_soal);
        $this->assertStringStartsWith('uploads/soal/soal_xlsx_', $soal->gambar_soal);

        // Pastikan file gambar fisik ada di public/uploads/soal/
        $this->assertFileExists(public_path($soal->gambar_soal));

        // Bersihkan
        @unlink(public_path($soal->gambar_soal));
        @unlink($tmpImg);
        @unlink($tmpXlsx);
    }

    public function test_keamanan_route_admin_dan_system_migrate_terblokir_untuk_siswa()
    {
        $kelas = \App\Models\Kelas::create(['kode_kelas' => 'X-TITL-SEC', 'nama_kelas' => 'X TITL 1', 'tingkat' => 10, 'jurusan' => 'TITL']);
        $userSiswa = \App\Models\User::factory()->create(['role' => 'siswa', 'username' => 'siswa_sec_01']);
        \App\Models\Siswa::create([
            'user_id' => $userSiswa->id,
            'kelas_id' => $kelas->id,
            'nisn' => '0099887766',
            'nama_lengkap' => 'Siswa Uji Keamanan',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab 1',
        ]);

        // Siswa tidak boleh mengakses panel admin maupun /system-migrate
        $this->actingAs($userSiswa)->get(route('admin.bank_soal'))->assertRedirect(route('siswa.dashboard'));
        $this->actingAs($userSiswa)->get('/system-migrate')->assertRedirect(route('siswa.dashboard'));

        // Guest tidak boleh mengakses /system-migrate
        auth()->logout();
        $this->get('/system-migrate')->assertRedirect(route('login'));
    }

    public function test_selesai_ujian_menghitung_benar_salah_dan_uraian_secara_akurat()
    {
        $kelas = \App\Models\Kelas::create(['kode_kelas' => 'X-TPFL-BS', 'nama_kelas' => 'X TPFL 1', 'tingkat' => 10, 'jurusan' => 'TPFL']);
        $userSiswa = \App\Models\User::factory()->create(['role' => 'siswa', 'username' => 'siswa_bs_01']);
        $siswa = \App\Models\Siswa::create([
            'user_id' => $userSiswa->id,
            'kelas_id' => $kelas->id,
            'nisn' => '0099112233',
            'nama_lengkap' => 'Siswa Uji Skor',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab 1',
        ]);

        $soalBs = \App\Models\SoalButir::create([
            'bank_soal_id' => $this->bank->id,
            'nomor_urut' => 1,
            'jenis_soal' => 'benar_salah',
            'konten_soal' => 'Apakah Python berjalan di browser?',
            'opsi_a' => 'Benar',
            'opsi_b' => 'Salah',
            'kunci_jawaban' => 'S',
            'bobot' => 50,
        ]);

        $soalUraian = \App\Models\SoalButir::create([
            'bank_soal_id' => $this->bank->id,
            'nomor_urut' => 2,
            'jenis_soal' => 'uraian',
            'konten_soal' => 'Jelaskan konsep algoritma!',
            'kunci_jawaban' => null,
            'bobot' => 50,
        ]);

        $jadwal = \App\Models\JadwalUjian::create([
            'bank_soal_id' => $this->bank->id,
            'nama_ujian' => 'Tes BS & Uraian',
            'waktu_mulai' => now()->subMinute(),
            'waktu_selesai' => now()->addHour(),
            'durasi_menit' => 60,
            'token_ujian' => 'TEST12',
            'status' => 'aktif',
        ]);

        $peserta = \App\Models\UjianPeserta::create([
            'jadwal_ujian_id' => $jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'berlangsung',
            'sisa_detik' => 3600,
        ]);

        // Cek tampilan tablet UI (drawer soal & zoom in-page) ada pada halaman ujian
        $this->actingAs($userSiswa)->get(route('siswa.ujian', $jadwal->id))
            ->assertStatus(200)
            ->assertSee('image-zoom-modal')
            ->assertSee('toggleNavDrawer');

        // Simpan jawaban BS ('SALAH' cocok dengan kunci 'S') dan jawaban Uraian
        $this->actingAs($userSiswa)->postJson(route('siswa.simpan', $jadwal->id), [
            'soal_id' => $soalBs->id,
            'jawaban' => 'SALAH',
            'ragu_ragu' => false,
            'sisa_detik' => 3500,
        ])->assertStatus(200);

        $this->actingAs($userSiswa)->postJson(route('siswa.simpan', $jadwal->id), [
            'soal_id' => $soalUraian->id,
            'jawaban' => 'Langkah sistematis menyelesaikan masalah.',
            'ragu_ragu' => false,
            'sisa_detik' => 3400,
        ])->assertStatus(200);

        $this->actingAs($userSiswa)->post(route('siswa.selesai', $jadwal->id))
            ->assertRedirect(route('siswa.dashboard'));

        $peserta->refresh();
        $this->assertEquals('selesai', $peserta->status);
        $this->assertEquals(1, $peserta->jawaban_benar);
        $this->assertEquals(0, $peserta->jawaban_salah);
        $this->assertEquals(50.0, (float) $peserta->nilai_pg);
    }

    public function test_admin_memiliki_kuasa_penuh_dan_rute_sensitif_terkunci_dari_non_admin()
    {
        $guru = \App\Models\User::factory()->create(['role' => 'guru', 'username' => 'guru_audit_01']);
        $proktor = \App\Models\User::factory()->create(['role' => 'proktor', 'username' => 'proktor_audit_01']);

        // 1. Guru & Proktor tidak bisa mengakses modul Super Admin (Users, Pengaturan Kepsek, Backup/Restore DB)
        $this->actingAs($guru)->get(route('admin.users'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($proktor)->get(route('admin.pengaturan'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($proktor)->get(route('admin.backup'))->assertRedirect(route('admin.dashboard'));

        // Admin penuh bisa mengakses semuanya
        $this->actingAs($this->admin)->get(route('admin.users'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.pengaturan'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.backup'))->assertStatus(200);

        // 2. Admin bisa membuat dan mengedit jadwal bertipe SIMULASI
        $kelas = \App\Models\Kelas::create(['kode_kelas' => 'XII-TKL-1', 'nama_kelas' => 'XII TKL 1', 'tingkat' => 12, 'jurusan' => 'TKL']);
        $this->actingAs($this->admin)->post(route('admin.jadwal.simpan'), [
            'bank_soal_id' => $this->bank->id,
            'nama_ujian' => 'Simulasi Kuasa Admin',
            'jenis_ujian' => 'SIMULASI',
            'token_ujian' => 'ADM01',
            'waktu_mulai' => now()->format('Y-m-d H:i:s'),
            'waktu_selesai' => now()->addDay()->format('Y-m-d H:i:s'),
            'durasi_menit' => 60,
            'kelas_ids' => [$kelas->id],
        ])->assertRedirect();

        $jadwal = \App\Models\JadwalUjian::where('nama_ujian', 'Simulasi Kuasa Admin')->firstOrFail();

        $this->actingAs($this->admin)->post(route('admin.jadwal.update', $jadwal->id), [
            'bank_soal_id' => $this->bank->id,
            'nama_ujian' => 'Simulasi Kuasa Admin Revisi',
            'jenis_ujian' => 'SIMULASI',
            'token_ujian' => 'ADM02',
            'waktu_mulai' => now()->format('Y-m-d H:i:s'),
            'waktu_selesai' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'durasi_menit' => 90,
            'status' => 'aktif',
            'kelas_ids' => [$kelas->id],
        ])->assertRedirect();

        $jadwal->refresh();
        $this->assertEquals('Simulasi Kuasa Admin Revisi', $jadwal->nama_ujian);
        $this->assertEquals(90, $jadwal->durasi_menit);

        // 3. Admin bisa mengoreksi jawaban & menginput nilai uraian peserta
        $userSiswa = \App\Models\User::factory()->create(['role' => 'siswa', 'username' => 'siswa_koreksi_01']);
        $siswa = \App\Models\Siswa::create([
            'user_id' => $userSiswa->id,
            'kelas_id' => $kelas->id,
            'nisn' => '0088776655',
            'nama_lengkap' => 'Siswa Koreksi',
            'sesi_ujian' => 'Sesi 1',
            'ruangan_ujian' => 'Lab 1',
        ]);
        $soalUraian = \App\Models\SoalButir::create([
            'bank_soal_id' => $this->bank->id,
            'nomor_urut' => 1,
            'jenis_soal' => 'uraian',
            'konten_soal' => 'Jelaskan prinsip kerja alternator.',
            'bobot' => 25,
        ]);
        $peserta = \App\Models\UjianPeserta::create([
            'jadwal_ujian_id' => $jadwal->id,
            'siswa_id' => $siswa->id,
            'status' => 'selesai',
            'nilai_pg' => 60,
            'total_nilai' => 60,
        ]);

        $this->actingAs($this->admin)->get(route('admin.koreksi_peserta', $peserta->id))
            ->assertStatus(200)
            ->assertSee('Siswa Koreksi');

        $this->actingAs($this->admin)->post(route('admin.koreksi_peserta.simpan', $peserta->id), [
            'skor_uraian' => [$soalUraian->id => 22.5],
        ])->assertRedirect(route('admin.rekap_nilai', $jadwal->id));

        $peserta->refresh();
        $this->assertEquals(22.5, (float) $peserta->nilai_uraian);
        $this->assertEquals(82.5, (float) $peserta->total_nilai);

        // 4. Admin bisa backup & restore database SQL, serta reset hasil jadwal
        $this->actingAs($this->admin)->post(route('admin.backup.database'))->assertSessionHas('success');
        $backupFiles = glob(storage_path('app/backups/*.sql'));
        $this->assertNotEmpty($backupFiles);
        $latestSql = basename(end($backupFiles));

        $this->actingAs($this->admin)->post(route('admin.jadwal.reset_hasil', $jadwal->id))->assertSessionHas('success');
        $this->assertEquals(0, \App\Models\UjianPeserta::where('jadwal_ujian_id', $jadwal->id)->count());

        $this->actingAs($this->admin)->post(route('admin.backup.database.restore'), [
            'filename' => $latestSql,
        ])->assertSessionHas('success');
        $this->assertEquals(1, \App\Models\UjianPeserta::where('jadwal_ujian_id', $jadwal->id)->count());

        // Bersihkan file backup tes
        @unlink(storage_path('app/backups/' . $latestSql));
    }

    public function test_seeder_vx_simulasi_all_mapel_membuat_10_soal_bergambar_dan_semua_jenis()
    {
        \App\Models\Kelas::firstOrCreate(['kode_kelas' => 'X-TITL-SIM'], ['nama_kelas' => 'X TITL 1', 'tingkat' => 10, 'jurusan' => 'TITL']);
        \App\Models\MataPelajaran::firstOrCreate(['kode_mapel' => 'TKA-SIMDIG'], ['nama_mapel' => 'Simulasi Digital', 'kelompok' => 'Umum', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua']);

        (new \Database\Seeders\VxSimulasiAllMapelSeeder())->run();

        $bank = \App\Models\BankSoal::where('kode_bank', 'VX-SIM-ALL-10')->first();
        $this->assertNotNull($bank);
        $this->assertEquals('Vx Simulasi All mapel', $bank->nama_bank);
        $this->assertEquals(10, $bank->butirSoal()->count());
        $this->assertEquals(6, $bank->butirSoal()->where('jenis_soal', 'pg')->count());
        $this->assertEquals(2, $bank->butirSoal()->where('jenis_soal', 'benar_salah')->count());
        $this->assertEquals(2, $bank->butirSoal()->where('jenis_soal', 'uraian')->count());
        $this->assertEquals(100.0, (float) $bank->butirSoal()->sum('bobot'));
        $this->assertGreaterThanOrEqual(5, $bank->butirSoal()->whereNotNull('gambar_soal')->count());

        $jadwal = \App\Models\JadwalUjian::where('nama_ujian', 'Vx Simulasi All mapel')->first();
        $this->assertNotNull($jadwal);
        $this->assertEquals('SIMULASI', $jadwal->jenis_ujian);
        $this->assertEquals('VXSIM', $jadwal->token_ujian);
        $this->assertEquals('aktif', $jadwal->status);
    }
}
