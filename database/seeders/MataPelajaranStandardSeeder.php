<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;
use App\Models\User;
use App\Models\BankSoal;

class MataPelajaranStandardSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. DAFTAR MATA PELAJARAN UMUM (A & B)
        $mapelUmum = [
            ['kode' => 'BIN-X', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => '10'],
            ['kode' => 'BIN-XI', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => '11'],
            ['kode' => 'BIN-XII', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => '12'],
            ['kode' => 'PPKN-UMUM', 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'AGM-KRISTEN', 'nama' => 'Pendidikan Agama Kristen dan Budi Pekerti', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'AGM-KATOLIK', 'nama' => 'Pendidikan Agama Katolik dan Budi Pekerti', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'AGM-ISLAM', 'nama' => 'Pendidikan Agama Islam dan Budi Pekerti', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'PJOK-UMUM', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelompok' => 'Muatan Kewilayahan (B)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'SEJ-UMUM', 'nama' => 'Sejarah Indonesia', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'SENI-UMUM', 'nama' => 'Seni Budaya dan Musik', 'kelompok' => 'Muatan Kewilayahan (B)', 'jurusan' => 'Semua Jurusan', 'tingkat' => 'Semua'],
            ['kode' => 'IPAS-X', 'nama' => 'Projek Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => '10'],
            ['kode' => 'TKA-SIMDIG', 'nama' => 'Simulasi dan Komunikasi Digital', 'kelompok' => 'Muatan Umum (A)', 'jurusan' => 'Semua Jurusan', 'tingkat' => '10'],
        ];

        foreach ($mapelUmum as $m) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $m['kode']],
                [
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'jurusan' => $m['jurusan'],
                    'tingkat' => $m['tingkat'],
                ]
            );
        }

        // 2. KELAS X: DKK (DASAR KOMPETENSI KEAHLIAN) PER JURUSAN
        $mapelDkk = [
            [
                'kode' => 'DKK-TITL-X',
                'nama' => 'DKK TITL',
                'kelompok' => 'Dasar Program Keahlian (DKK)',
                'jurusan' => 'Teknik Instalasi Tenaga Listrik',
                'tingkat' => '10',
            ],
            [
                'kode' => 'DKK-DPIB-X',
                'nama' => 'DKK DPIB',
                'kelompok' => 'Dasar Program Keahlian (DKK)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => '10',
            ],
            [
                'kode' => 'DKK-TKRO-X',
                'nama' => 'DKK TKRO',
                'kelompok' => 'Dasar Program Keahlian (DKK)',
                'jurusan' => 'Teknik Kendaraan Ringan',
                'tingkat' => '10',
            ],
            [
                'kode' => 'DKK-TP-X',
                'nama' => 'DKK TP',
                'kelompok' => 'Dasar Program Keahlian (DKK)',
                'jurusan' => 'Teknik Pemesinan',
                'tingkat' => '10',
            ],
        ];

        foreach ($mapelDkk as $m) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $m['kode']],
                [
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'jurusan' => $m['jurusan'],
                    'tingkat' => $m['tingkat'],
                ]
            );
        }

        // 3. KELAS XI & XII: KK (KOMPETENSI KEAHLIAN) PER JURUSAN & TINGKATAN
        $mapelKk = [
            // Kelas XI
            ['kode' => 'KK-TITL-XI', 'nama' => 'KK TITL XI', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Instalasi Tenaga Listrik', 'tingkat' => '11'],
            ['kode' => 'KK-DPIB-XI', 'nama' => 'KK DPIB XI', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Desain Pemodelan dan Informasi Bangunan', 'tingkat' => '11'],
            ['kode' => 'KK-TKRO-XI', 'nama' => 'KK TKRO XI', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Kendaraan Ringan', 'tingkat' => '11'],
            ['kode' => 'KK-TP-XI', 'nama' => 'KK TP XI', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Pemesinan', 'tingkat' => '11'],

            // Kelas XII
            ['kode' => 'KK-TITL-XII', 'nama' => 'KK TITL XII', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Instalasi Tenaga Listrik', 'tingkat' => '12'],
            ['kode' => 'KK-DPIB-XII', 'nama' => 'KK DPIB XII', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Desain Pemodelan dan Informasi Bangunan', 'tingkat' => '12'],
            ['kode' => 'KK-TKRO-XII', 'nama' => 'KK TKRO XII', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Kendaraan Ringan', 'tingkat' => '12'],
            ['kode' => 'KK-TP-XII', 'nama' => 'KK TP XII', 'kelompok' => 'Konsentrasi Keahlian (KK)', 'jurusan' => 'Teknik Pemesinan', 'tingkat' => '12'],
        ];

        foreach ($mapelKk as $m) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $m['kode']],
                [
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'jurusan' => $m['jurusan'],
                    'tingkat' => $m['tingkat'],
                ]
            );
        }

        // 4. MAPEL PILIHAN (MPP)
        // Kelas X Wajib: KKA (Koding dan Kecerdasan Artifisial)
        MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'MPP-KKA-X'],
            [
                'nama_mapel' => 'Koding dan Kecerdasan Artifisial',
                'kelompok' => 'Mata Pelajaran Pilihan (MPP)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '10',
                'guru_id' => $admin ? $admin->id : null,
            ]
        );

        // Kelas XI & XII: Mapel Pilihan (Dapat diubah bebas namanya oleh Admin)
        $mppLanjutan = [
            ['kode' => 'MPP-TITL-XI', 'nama' => 'Mata Pelajaran Pilihan (TITL XI)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Instalasi Tenaga Listrik', 'tingkat' => '11'],
            ['kode' => 'MPP-DPIB-XI', 'nama' => 'Mata Pelajaran Pilihan (DPIB XI)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Desain Pemodelan dan Informasi Bangunan', 'tingkat' => '11'],
            ['kode' => 'MPP-TKRO-XI', 'nama' => 'Mata Pelajaran Pilihan (TKRO XI)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Kendaraan Ringan', 'tingkat' => '11'],
            ['kode' => 'MPP-TP-XI', 'nama' => 'Mata Pelajaran Pilihan (TP XI)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Pemesinan', 'tingkat' => '11'],

            ['kode' => 'MPP-TITL-XII', 'nama' => 'Mata Pelajaran Pilihan (TITL XII)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Instalasi Tenaga Listrik', 'tingkat' => '12'],
            ['kode' => 'MPP-DPIB-XII', 'nama' => 'Mata Pelajaran Pilihan (DPIB XII)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Desain Pemodelan dan Informasi Bangunan', 'tingkat' => '12'],
            ['kode' => 'MPP-TKRO-XII', 'nama' => 'Mata Pelajaran Pilihan (TKRO XII)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Kendaraan Ringan', 'tingkat' => '12'],
            ['kode' => 'MPP-TP-XII', 'nama' => 'Mata Pelajaran Pilihan (TP XII)', 'kelompok' => 'Mata Pelajaran Pilihan (MPP)', 'jurusan' => 'Teknik Pemesinan', 'tingkat' => '12'],
        ];

        foreach ($mppLanjutan as $m) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $m['kode']],
                [
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'jurusan' => $m['jurusan'],
                    'tingkat' => $m['tingkat'],
                ]
            );
        }

        // 5. Hubungkan Bank Soal BS-TITL-40 ke KK TITL XI secara presisi
        $mapelTitlKk = MataPelajaran::where('kode_mapel', 'KK-TITL-XI')->first()
            ?? MataPelajaran::where('kode_mapel', 'DKK-TITL-X')->first();

        $bankTitl = BankSoal::where('kode_bank', 'BS-TITL-40')->first();
        if ($bankTitl && $mapelTitlKk) {
            $bankTitl->mata_pelajaran_id = $mapelTitlKk->id;
            $bankTitl->save();
        }

        // Hubungkan Bank Soal KKA26 jika ada ke MPP-KKA-X
        $mapelKka = MataPelajaran::where('kode_mapel', 'MPP-KKA-X')->first();
        $bankKka = BankSoal::where('kode_bank', 'KKA26')->first();
        if ($bankKka && $mapelKka) {
            $bankKka->mata_pelajaran_id = $mapelKka->id;
            $bankKka->save();
        }

        // 6. Bersihkan mapel C2 & C3 lama jika tidak dipakai di Bank Soal manapun
        $oldCodes = [
            'C2-DPIB-X', 'C3-DPIB-XI', 'C3-DPIB-XII',
            'C2-TITL-X', 'C3-TITL-XI', 'C3-TITL-XII',
            'C2-TO-X', 'C3-TKR-XI', 'C3-TKR-XII',
            'C2-TP-X', 'C3-TP-XI', 'C3-TPFL-XII',
        ];
        foreach ($oldCodes as $oc) {
            $oldM = MataPelajaran::where('kode_mapel', $oc)->first();
            if ($oldM && $oldM->bankSoal()->count() == 0) {
                $oldM->delete();
            }
        }
    }
}
