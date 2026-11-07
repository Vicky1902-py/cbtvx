<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MasterSekolah;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\BankSoal;
use App\Models\SoalButir;
use App\Models\JadwalUjian;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Master Sekolah
        MasterSekolah::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
                'npsn' => '69727541',
                'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
                'kecamatan' => 'Kupang Barat',
                'kabupaten' => 'Kabupaten Kupang',
                'provinsi' => 'Nusa Tenggara Timur',
                'kepala_sekolah' => 'Vicky Koroh, S.Pd., M.Kom.',
                'nip_kepala_sekolah' => '198501192011011005',
                'ttd_kepala_sekolah' => 'images/ttd_kepsek.png',
                'logo_sekolah' => 'images/logo_smk.png',
                'logo_pemprov' => 'images/logo_pemprov_ntt.svg',
                'tahun_ajaran_aktif' => '2026/2027',
                'semester_aktif' => 'Genap',
            ]
        );

        // 2. Akun Administrator & Proktor
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Vicky Koroh, S.Pd., M.Kom. (Administrator)',
                'email' => 'admin@smkn1kupangbarat.sch.id',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $proktor = User::updateOrCreate(
            ['username' => 'proktor'],
            [
                'name' => 'Proktor CBT SMKN 1 Kupang Barat',
                'email' => 'proktor@smkn1kupangbarat.sch.id',
                'password' => Hash::make('proktor'),
                'role' => 'proktor',
                'status' => 'aktif',
            ]
        );

        $guru = User::updateOrCreate(
            ['username' => 'guru'],
            [
                'name' => 'Guru Pengampu / Tim Penguji',
                'email' => 'guru@smkn1kupangbarat.sch.id',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'status' => 'aktif',
            ]
        );

        // 3. Sinkronisasi Data Riil Dapodik (Profil Sekolah, 12 Rombel, 38 Guru, 89 Siswa)
        $this->call(DapodikSeeder::class);

        // 5. Mata Pelajaran
        $mapel = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'TKA-SIMDIG'],
            ['nama_mapel' => 'Simulasi Digital dan Literasi TIK']
        );

        // 6. Bank Soal & Preset 25 / 40 Soal Lengkap
        $this->call(BankSoalPresetSeeder::class);
        $bank = BankSoal::where('kode_bank', 'BS-TKA-2026')->first();

        // 7. Jadwal Ujian Aktif
        $jadwal = JadwalUjian::updateOrCreate(
            ['nama_ujian' => 'Simulasi Asesmen Standar TKA Pusmendik 2026'],
            [
                'bank_soal_id' => $bank->id,
                'jenis_ujian' => 'TKA',
                'token_ujian' => 'SMK01',
                'acak_token' => false,
                'waktu_mulai' => now()->subDay(),
                'waktu_selesai' => now()->addDays(30),
                'durasi_menit' => 60,
                'acak_soal' => true,
                'acak_opsi' => true,
                'tampilkan_nilai' => true,
                'izinkan_login_ganda' => false,
                'status' => 'aktif',
            ]
        );

        // Pasangkan ke seluruh 12 kelas Dapodik
        $allKelasIds = Kelas::pluck('id')->toArray();
        $jadwal->kelas()->sync($allKelasIds);
    }
}
