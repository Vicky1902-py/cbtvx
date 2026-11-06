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
                'npsn' => '69984954',
                'alamat' => 'Desa Kuanheun',
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

        // 3. Master Kelas
        $k1 = Kelas::updateOrCreate(
            ['kode_kelas' => 'XI-MM'],
            ['nama_kelas' => 'XI MM', 'tingkat' => '11', 'jurusan' => 'Multimedia']
        );
        $k2 = Kelas::updateOrCreate(
            ['kode_kelas' => 'XII-TKJ'],
            ['nama_kelas' => 'XII TKJ', 'tingkat' => '12', 'jurusan' => 'Teknik Komputer dan Jaringan']
        );
        $k3 = Kelas::updateOrCreate(
            ['kode_kelas' => 'X-TBSM'],
            ['nama_kelas' => 'X TBSM', 'tingkat' => '10', 'jurusan' => 'Teknik Bisnis Sepeda Motor']
        );

        // 4. Master Siswa
        $siswaUsers = [
            [
                'username' => '12345',
                'name' => 'Joko Susanto',
                'nis' => '22101',
                'nisn' => '0054819201',
                'kelas_id' => $k1->id,
                'jk' => 'L',
                'sesi' => 'Sesi 1',
                'ruang' => 'Lab Komputer 1',
                'nopes' => '26-24-05-01-001-8',
            ],
            [
                'username' => '0054819202',
                'name' => 'Maria Fatima Berek',
                'nis' => '22102',
                'nisn' => '0054819202',
                'kelas_id' => $k1->id,
                'jk' => 'P',
                'sesi' => 'Sesi 1',
                'ruang' => 'Lab Komputer 1',
                'nopes' => '26-24-05-01-002-7',
            ],
            [
                'username' => '0054819203',
                'name' => 'Yehezkiel Nalle',
                'nis' => '21101',
                'nisn' => '0054819203',
                'kelas_id' => $k2->id,
                'jk' => 'L',
                'sesi' => 'Sesi 2',
                'ruang' => 'Lab Komputer 2',
                'nopes' => '26-24-05-01-003-6',
            ],
        ];

        foreach ($siswaUsers as $su) {
            $u = User::updateOrCreate(
                ['username' => $su['username']],
                [
                    'name' => $su['name'],
                    'password' => Hash::make('12345'),
                    'role' => 'siswa',
                    'status' => 'aktif',
                ]
            );

            Siswa::updateOrCreate(
                ['user_id' => $u->id],
                [
                    'nis' => $su['nis'],
                    'nisn' => $su['nisn'],
                    'nama_lengkap' => $su['name'],
                    'jenis_kelamin' => $su['jk'],
                    'kelas_id' => $su['kelas_id'],
                    'sesi_ujian' => $su['sesi'],
                    'ruangan_ujian' => $su['ruang'],
                    'nomor_peserta' => $su['nopes'],
                    'status_login' => 'offline',
                ]
            );
        }

        // 5. Mata Pelajaran
        $mapel = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'TKA-SIMDIG'],
            ['nama_mapel' => 'Simulasi Digital dan Literasi TIK']
        );

        // 6. Bank Soal (25 Soal, Bobot 4 = 100)
        $bank = BankSoal::updateOrCreate(
            ['kode_bank' => 'BS-TKA-2026'],
            [
                'mata_pelajaran_id' => $mapel->id,
                'guru_id' => $admin->id,
                'tingkat' => 'Semua',
                'jumlah_soal_pg' => 25,
                'jumlah_soal_uraian' => 0,
                'bobot_pg' => 100.00,
                'bobot_uraian' => 0.00,
                'kkm' => 75.00,
                'status' => 'aktif',
            ]
        );

        // Butir Soal Contoh Berstandar TKA / Pusmendik (10 Soal Berbobot 10 = 100)
        $daftarSoal = [
            [
                'no' => 1,
                'konten' => 'Protokol jaringan yang bertugas menerjemahkan alamat IP menjadi nama domain (seperti smkn1kupangbarat.sch.id) adalah...',
                'a' => 'DHCP (Dynamic Host Configuration Protocol)',
                'b' => 'DNS (Domain Name System)',
                'c' => 'FTP (File Transfer Protocol)',
                'd' => 'HTTP (Hypertext Transfer Protocol)',
                'e' => 'SMTP (Simple Mail Transfer Protocol)',
                'kunci' => 'B',
                'bobot' => 10.0,
            ],
            [
                'no' => 2,
                'konten' => 'Topologi jaringan komputer yang menggunakan satu kabel utama sebagai tulang punggung (backbone) lalu setiap workstation terhubung pada kabel tersebut dinamakan topologi...',
                'a' => 'Star',
                'b' => 'Ring',
                'c' => 'Bus',
                'd' => 'Mesh',
                'e' => 'Tree',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
            [
                'no' => 3,
                'konten' => 'Perangkat keras jaringan yang berfungsi menghubungkan dua atau lebih jaringan komputer yang berbeda segmen IP atau subnet adalah...',
                'a' => 'Switch Layer 2',
                'b' => 'Hub',
                'c' => 'Router',
                'd' => 'Repeater',
                'e' => 'Modem dial-up',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
            [
                'no' => 4,
                'konten' => 'Pada pengalamatan IPv4, subnet mask 255.255.255.0 setara dengan notasi CIDR (Classless Inter-Domain Routing)...',
                'a' => '/8',
                'b' => '/16',
                'c' => '/24',
                'd' => '/30',
                'e' => '/32',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
            [
                'no' => 5,
                'konten' => 'Teknologi media transmisi jaringan kabel yang menggunakan gelombang cahaya untuk mengirimkan data dengan kecepatan sangat tinggi dan bebas interferensi elektromagnetik adalah...',
                'a' => 'Unshielded Twisted Pair (UTP)',
                'b' => 'Shielded Twisted Pair (STP)',
                'c' => 'Coaxial Cable',
                'd' => 'Fiber Optic',
                'e' => 'Kabel Tembaga Telkom',
                'kunci' => 'D',
                'bobot' => 10.0,
            ],
            [
                'no' => 6,
                'konten' => 'Dalam arsitektur sistem operasi berbasis Linux, perintah CLI yang digunakan untuk mengecek konektivitas dan latensi respon ke host tujuan menggunakan paket ICMP adalah...',
                'a' => 'ifconfig',
                'b' => 'netstat',
                'c' => 'ping',
                'd' => 'traceroute',
                'e' => 'nslookup',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
            [
                'no' => 7,
                'konten' => 'Format file citra vektor digital yang tidak akan pecah saat diperbesar berapapun skalanya dan cocok untuk logo sekolah modern adalah...',
                'a' => 'JPEG / JPG',
                'b' => 'PNG 8-bit',
                'c' => 'SVG (Scalable Vector Graphics)',
                'd' => 'BMP',
                'e' => 'GIF',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
            [
                'no' => 8,
                'konten' => 'Sistem backup data otomatis yang menyimpan salinan cadangan di dua media berbeda secara bersamaan bertujuan memenuhi standar keamanan informasi...',
                'a' => 'Confidentiality (Kerahasiaan)',
                'b' => 'Availability (Ketersediaan data)',
                'c' => 'Non-Repudiation (Anti-penyangkalan)',
                'd' => 'Authentication (Otentikasi)',
                'e' => 'Cryptography (Kriptografi)',
                'kunci' => 'B',
                'bobot' => 10.0,
            ],
            [
                'no' => 9,
                'konten' => 'Ketika siswa mengerjakan asesmen CBT dan jaringan internet tiba-tiba terputus selama 1 menit, mekanisme sistem yang paling aman agar jawaban tidak hilang adalah...',
                'a' => 'Menghapus sesi dan mewajibkan login ulang',
                'b' => 'Menyimpan jawaban sementara di local storage browser dan menyinkronkan saat koneksi kembali',
                'c' => 'Memaksa siswa mengulang dari nomor 1',
                'd' => 'Mengunci komputer ujian secara permanen',
                'e' => 'Mematikan timer komputer',
                'kunci' => 'B',
                'bobot' => 10.0,
            ],
            [
                'no' => 10,
                'konten' => 'Berapa jumlah total poin maksimal yang harus dicapai dalam perancangan asesmen terstandar TKA jika seluruh butir soal dijawab dengan benar?',
                'a' => '50 Poin',
                'b' => '75 Poin',
                'c' => '100 Poin',
                'd' => '120 Poin',
                'e' => '150 Poin',
                'kunci' => 'C',
                'bobot' => 10.0,
            ],
        ];

        foreach ($daftarSoal as $s) {
            SoalButir::updateOrCreate(
                [
                    'bank_soal_id' => $bank->id,
                    'nomor_urut' => $s['no'],
                ],
                [
                    'jenis_soal' => 'pg',
                    'konten_soal' => $s['konten'],
                    'opsi_a' => $s['a'],
                    'opsi_b' => $s['b'],
                    'opsi_c' => $s['c'],
                    'opsi_d' => $s['d'],
                    'opsi_e' => $s['e'],
                    'kunci_jawaban' => $s['kunci'],
                    'bobot' => $s['bobot'],
                ]
            );
        }

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

        // Pasangkan ke semua kelas
        $jadwal->kelas()->sync([$k1->id, $k2->id, $k3->id]);
    }
}
