<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankSoal;
use App\Models\SoalButir;
use App\Models\User;
use App\Http\Controllers\AdminController;

class BankSoalPresetSeeder extends Seeder
{
    public function run(): void
    {
        $controller = new AdminController();
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. BANK SOAL 1: PRESET STANDAR 25 SOAL (100 Poin)
        $b1 = BankSoal::where('kode_bank', 'BS-TKA-2026')->first();
        if (!$b1) {
            $b1 = BankSoal::find(1);
        }
        if ($b1) {
            $b1->nama_bank = 'Simulasi & Komunikasi Digital (Preset Standar 25 Soal)';
            $b1->standar_bobot = '25_soal';
            $b1->jumlah_soal_bs = 5;
            $b1->bobot_bs = 20.00;
            $b1->jumlah_soal_pg = 15;
            $b1->bobot_pg = 30.00;
            $b1->jumlah_soal_uraian = 5;
            $b1->bobot_uraian = 50.00;
            $b1->kkm = 75.00;
            $b1->status = 'aktif';
            $b1->save();

            $b1->butirSoal()->delete();
            $samples25 = $controller->getSampleQuestions25(4.0, 2.0, 10.0);
            foreach ($samples25 as $q) {
                SoalButir::create([
                    'bank_soal_id' => $b1->id,
                    'nomor_urut' => $q[0],
                    'jenis_soal' => strtolower($q[1]),
                    'konten_soal' => $q[2],
                    'opsi_a' => $q[3] ?: null,
                    'opsi_b' => $q[4] ?: null,
                    'opsi_c' => $q[5] ?: null,
                    'opsi_d' => $q[6] ?: null,
                    'opsi_e' => $q[7] ?: null,
                    'kunci_jawaban' => $q[8] ?: null,
                    'rubrik_uraian' => $q[9] ?: null,
                    'bobot' => $q[10],
                ]);
            }
        }

        // 2. BANK SOAL 2: PRESET STANDAR 40 SOAL (100 Poin)
        $b2 = BankSoal::where('kode_bank', 'BS-TITL-40')->first();
        if (!$b2) {
            $b2 = new BankSoal();
            $b2->kode_bank = 'BS-TITL-40';
        }
        $b2->nama_bank = 'Dasar Teknik Ketenagalistrikan (Preset Standar 40 Soal)';
        $b2->mata_pelajaran_id = 22; // C2-TITL-X
        $b2->guru_id = $admin ? $admin->id : null;
        $b2->standar_bobot = '40_soal';
        $b2->jumlah_soal_bs = 10;
        $b2->bobot_bs = 10.00;
        $b2->jumlah_soal_pg = 20;
        $b2->bobot_pg = 40.00;
        $b2->jumlah_soal_uraian = 10;
        $b2->bobot_uraian = 50.00;
        $b2->kkm = 75.00;
        $b2->status = 'aktif';
        $b2->save();

        $b2->butirSoal()->delete();
        $samples40 = $controller->getSampleQuestions40(1.0, 2.0, 5.0);
        foreach ($samples40 as $q) {
            SoalButir::create([
                'bank_soal_id' => $b2->id,
                'nomor_urut' => $q[0],
                'jenis_soal' => strtolower($q[1]),
                'konten_soal' => $q[2],
                'opsi_a' => $q[3] ?: null,
                'opsi_b' => $q[4] ?: null,
                'opsi_c' => $q[5] ?: null,
                'opsi_d' => $q[6] ?: null,
                'opsi_e' => $q[7] ?: null,
                'kunci_jawaban' => $q[8] ?: null,
                'rubrik_uraian' => $q[9] ?: null,
                'bobot' => $q[10],
            ]);
        }
    }
}
