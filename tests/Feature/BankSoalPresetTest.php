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
}
