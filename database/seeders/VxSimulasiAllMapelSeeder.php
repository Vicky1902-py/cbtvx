<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\BankSoal;
use App\Models\SoalButir;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;

class VxSimulasiAllMapelSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $mapel = MataPelajaran::where('kode_mapel', 'TKA-SIMDIG')->first()
            ?? MataPelajaran::where('jurusan', 'Semua Jurusan')->first()
            ?? MataPelajaran::first();

        if (!$mapel) {
            return;
        }

        $uploadDir = public_path('uploads/soal');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Generate 5 gambar diagram PNG berkualitas tinggi secara offline (GD)
        $this->generateDiagramSegitiga($uploadDir . '/vx_sim_segitiga.png');
        $this->generateDiagramRangkaian($uploadDir . '/vx_sim_rangkaian.png');
        $this->generateDiagramLas($uploadDir . '/vx_sim_las.png');
        $this->generateDiagramGear($uploadDir . '/vx_sim_gear.png');
        $this->generateDiagramListrik($uploadDir . '/vx_sim_listrik.png');

        $bank = BankSoal::updateOrCreate(
            ['kode_bank' => 'VX-SIM-ALL-10'],
            [
                'nama_bank' => 'Vx Simulasi All mapel',
                'mata_pelajaran_id' => $mapel->id,
                'guru_id' => $admin ? $admin->id : null,
                'tingkat' => 'Semua',
                'standar_bobot' => 'kustom',
                'jumlah_soal_pg' => 6,
                'bobot_pg' => 48.00,
                'jumlah_soal_bs' => 2,
                'bobot_bs' => 12.00,
                'jumlah_soal_uraian' => 2,
                'bobot_uraian' => 40.00,
                'kkm' => 75.00,
                'status' => 'aktif',
            ]
        );

        $bank->butirSoal()->delete();

        $questions = [
            // 1. PG - Matematika Terapan (Hitungan + Gambar)
            [
                'nomor_urut' => 1,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Matematika Terapan] Perhatikan gambar sketsa rangka segitiga siku-siku atap bengkel di atas.\nJika panjang alas a = 8 meter dan tinggi t = 6 meter, berapakah panjang sisi miring (c) dan luas penampang segitiga tersebut?",
                'gambar_soal' => 'uploads/soal/vx_sim_segitiga.png',
                'opsi_a' => 'Sisi miring c = 10 meter dan Luas = 24 m²',
                'opsi_b' => 'Sisi miring c = 12 meter dan Luas = 48 m²',
                'opsi_c' => 'Sisi miring c = 14 meter dan Luas = 24 m²',
                'opsi_d' => 'Sisi miring c = 10 meter dan Luas = 48 m²',
                'opsi_e' => 'Sisi miring c = 14 meter dan Luas = 28 m²',
                'kunci_jawaban' => 'A',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 2. PG - Kejuruan TITL (Hitungan Hukum Ohm + Gambar)
            [
                'nomor_urut' => 2,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Teknik Instalasi Tenaga Listrik / TITL] Perhatikan skema rangkaian listrik seri pada gambar di atas!\nJika sumber tegangan V = 24 Volt dihubungkan seri dengan hambatan R1 = 4 Ω dan R2 = 8 Ω, berapakah besar kuat arus listrik (I) yang mengalir pada rangkaian tersebut?",
                'gambar_soal' => 'uploads/soal/vx_sim_rangkaian.png',
                'opsi_a' => '0,5 Ampere',
                'opsi_b' => '2,0 Ampere',
                'opsi_c' => '3,0 Ampere',
                'opsi_d' => '6,0 Ampere',
                'opsi_e' => '12,0 Ampere',
                'kunci_jawaban' => 'B',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 3. PG - Kejuruan TP/TPFL (Hitungan Elektroda Las + Gambar)
            [
                'nomor_urut' => 3,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Teknik Pengelasan / TP & TPFL] Perhatikan ilustrasi dimensi sambungan pelat baja pada gambar di atas.\nSeorang siswa mengelas 5 buah benda kerja yang masing-masing panjang alurnya 60 cm. Apabila 1 batang elektroda E6013 mampu mengelas sepanjang 25 cm, berapakah jumlah minimal elektroda yang dibutuhkan?",
                'gambar_soal' => 'uploads/soal/vx_sim_las.png',
                'opsi_a' => '8 batang elektroda',
                'opsi_b' => '10 batang elektroda',
                'opsi_c' => '12 batang elektroda',
                'opsi_d' => '15 batang elektroda',
                'opsi_e' => '20 batang elektroda',
                'kunci_jawaban' => 'C',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 4. PG - Kejuruan TKL / Otomotif (Hitungan Transmisi Gear + Gambar)
            [
                'nomor_urut' => 4,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Teknik Kendaraan Ringan / TKL] Perhatikan diagram transmisi dua roda gigi pada gambar di atas.\nGear penggerak memiliki jumlah gigi Z1 = 15 dan berputar pada kecepatan 1.200 RPM, menggerakkan gear kedua berjumlah Z2 = 45 gigi. Berapakah kecepatan putaran (n2) gear kedua?",
                'gambar_soal' => 'uploads/soal/vx_sim_gear.png',
                'opsi_a' => '300 RPM',
                'opsi_b' => '400 RPM',
                'opsi_c' => '600 RPM',
                'opsi_d' => '1.800 RPM',
                'opsi_e' => '3.600 RPM',
                'kunci_jawaban' => 'B',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 5. PG - Bahasa Indonesia (Literasi Teks K3)
            [
                'nomor_urut' => 5,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Bahasa Indonesia] Bacalah paragraf berikut dengan saksama:\n\"Penerapan keselamatan dan kesehatan kerja (K3) di bengkel praktikum SMK merupakan syarat mutlak sebelum siswa mengoperasikan mesin las maupun peralatan kelistrikan bertegangan tinggi. Penggunaan helm las, sarung tangan kulit, serta sepatu safety terbukti menekan angka kecelakaan kerja secara signifikan.\"\nIde pokok paragraf tersebut adalah...",
                'gambar_soal' => null,
                'opsi_a' => 'Harga peralatan keselamatan kerja sangat mahal bagi siswa SMK',
                'opsi_b' => 'Penerapan K3 merupakan syarat mutlak di bengkel praktikum SMK',
                'opsi_c' => 'Bengkel kelistrikan jauh lebih berbahaya dibandingkan bengkel las',
                'opsi_d' => 'Semua siswa wajib membeli helm las pribadi sebelum ujian berlangsung',
                'opsi_e' => 'Kecelakaan kerja tidak dapat dicegah dengan alat pelindung diri',
                'kunci_jawaban' => 'B',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 6. PG - Bahasa Inggris Teknik
            [
                'nomor_urut' => 6,
                'jenis_soal' => 'pg',
                'konten_soal' => "[Bahasa Inggris] Read the workshop safety sign carefully:\n\"WARNING: Always turn off and disconnect the main power supply before inspecting or repairing any electrical panel.\"\nWhat must a student or technician do BEFORE checking the inside of the electrical panel?",
                'gambar_soal' => null,
                'opsi_a' => 'Increase the voltage to test the circuit breaker',
                'opsi_b' => 'Turn off and disconnect the main power source first',
                'opsi_c' => 'Touch the panel cables immediately without tools',
                'opsi_d' => 'Lock the workshop door during inspection',
                'opsi_e' => 'Ignore the sign if wearing regular shoes',
                'kunci_jawaban' => 'B',
                'rubrik_uraian' => null,
                'bobot' => 8.00,
            ],
            // 7. BENAR/SALAH - Koding & Kecerdasan Artifisial (Hitungan Konversi Biner)
            [
                'nomor_urut' => 7,
                'jenis_soal' => 'benar_salah',
                'konten_soal' => "[Koding & Kecerdasan Artifisial / KKA] Dalam sistem bilangan digital komputer, bilangan biner 1011₂ (basis 2) jika dihitung ke sistem desimal (basis 10) menggunakan rumus (1×8) + (0×4) + (1×2) + (1×1) bernilai tepat sama dengan 11₁₀.\nApakah pernyataan tersebut Benar atau Salah?",
                'gambar_soal' => null,
                'opsi_a' => 'Benar — Hasil konversi biner 1011₂ ke desimal adalah 11',
                'opsi_b' => 'Salah — Hasil konversi biner 1011₂ bukan 11',
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'kunci_jawaban' => 'BENAR',
                'rubrik_uraian' => null,
                'bobot' => 6.00,
            ],
            // 8. BENAR/SALAH - PPKn / Umum (Hitungan Persentase Kehadiran)
            [
                'nomor_urut' => 8,
                'jenis_soal' => 'benar_salah',
                'konten_soal' => "[PPKn & Literasi Numerasi] Di ruang ujian Lab 1 terdapat 40 peserta terdaftar. Sebanyak 36 peserta hadir mengikuti ujian dengan jujur sesuai nilai sila Pancasila, sedangkan 4 peserta berhalangan hadir. Dengan demikian, persentase kehadiran peserta pada ruangan tersebut adalah tepat 80%.\nApakah pernyataan tersebut Benar atau Salah?",
                'gambar_soal' => null,
                'opsi_a' => 'Benar — Tingkat kehadiran tepat 80%',
                'opsi_b' => 'Salah — Tingkat kehadiran seharusnya 90% (36 / 40 × 100%)',
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'kunci_jawaban' => 'SALAH',
                'rubrik_uraian' => null,
                'bobot' => 6.00,
            ],
            // 9. URAIAN - IPA Terapan / Matematika (Hitungan Biaya Listrik + Gambar)
            [
                'nomor_urut' => 9,
                'jenis_soal' => 'uraian',
                'konten_soal' => "[IPA Terapan & Matematika] Perhatikan papan data pemakaian daya listrik bengkel pada gambar di atas!\nDiketahui mesin gerinda berdaya 800 Watt dan lampu penerangan berdaya total 200 Watt menyala bersamaan selama 5 jam setiap hari selama 20 hari kerja.\nHitunglah dengan menuliskan langkah-langkahnya:\na. Total energi listrik yang terpakai dalam satuan kWh selama 20 hari!\nb. Total biaya listrik yang harus dibayar jika tarif listrik Rp 1.500 per kWh!",
                'gambar_soal' => 'uploads/soal/vx_sim_listrik.png',
                'opsi_a' => null,
                'opsi_b' => null,
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'kunci_jawaban' => '100 kWh; Rp 150.000',
                'rubrik_uraian' => 'Daya total = 800 W + 200 W = 1.000 W = 1 kW (Skor 5). Energi = 1 kW × 5 jam/hari × 20 hari = 100 kWh (Skor 8). Biaya = 100 kWh × Rp 1.500 = Rp 150.000 (Skor 7).',
                'bobot' => 20.00,
            ],
            // 10. URAIAN - Kejuruan Terpadu & KKA
            [
                'nomor_urut' => 10,
                'jenis_soal' => 'uraian',
                'konten_soal' => "[Kejuruan Terpadu & KKA] Jelaskan 3 (tiga) langkah wajib Keselamatan Kerja (K3LH) yang harus dilakukan sebelum menyalakan peralatan praktik di bengkel (TITL / TKL / TP-TPFL), serta berikan 1 (satu) contoh manfaat penggunaan Kecerdasan Artifisial (AI) atau komputerisasi pada jurusan keahlian Anda!",
                'gambar_soal' => null,
                'opsi_a' => null,
                'opsi_b' => null,
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'kunci_jawaban' => '3 langkah K3LH & 1 contoh AI/komputerisasi kejuruan',
                'rubrik_uraian' => 'Menyebutkan 3 langkah K3LH dengan benar seperti memakai APD lengkap, mengecek kelayakan alat/kabel, dan mematuhi SOP bengkel (Skor 12); memberikan 1 contoh manfaat AI/komputerisasi di bidang keahlian (Skor 8).',
                'bobot' => 20.00,
            ],
        ];

        foreach ($questions as $q) {
            SoalButir::create(array_merge(['bank_soal_id' => $bank->id], $q));
        }

        // Buat / perbarui Jadwal Ujian aktif untuk seluruh 12 kelas agar langsung bisa disimulasikan di Lab 1 & Lab 2
        $jadwal = JadwalUjian::updateOrCreate(
            ['nama_ujian' => 'Vx Simulasi All mapel'],
            [
                'bank_soal_id' => $bank->id,
                'jenis_ujian' => 'SIMULASI',
                'token_ujian' => 'VXSIM',
                'acak_token' => false,
                'waktu_mulai' => now()->subDay(),
                'waktu_selesai' => now()->addDays(60),
                'durasi_menit' => 60,
                'acak_soal' => false,
                'acak_opsi' => false,
                'tampilkan_nilai' => true,
                'izinkan_login_ganda' => false,
                'status' => 'aktif',
            ]
        );

        $allKelasIds = Kelas::pluck('id')->toArray();
        if (count($allKelasIds) > 0) {
            $jadwal->kelas()->sync($allKelasIds);
        }
    }

    private function createCanvas(int $w = 640, int $h = 340, string $title = ''): array
    {
        $im = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($im, 248, 250, 252);
        $navy = imagecolorallocate($im, 15, 43, 72);
        $blue = imagecolorallocate($im, 2, 132, 199);
        $red = imagecolorallocate($im, 220, 38, 38);
        $amber = imagecolorallocate($im, 217, 119, 6);
        $green = imagecolorallocate($im, 22, 163, 74);
        $dark = imagecolorallocate($im, 30, 41, 59);
        $muted = imagecolorallocate($im, 100, 116, 139);
        $border = imagecolorallocate($im, 203, 213, 225);
        $white = imagecolorallocate($im, 255, 255, 255);

        imagefill($im, 0, 0, $bg);
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $border);

        // Header bar
        imagefilledrectangle($im, 0, 0, $w - 1, 38, $navy);
        imagestring($im, 5, 16, 11, $title, $white);

        return compact('im', 'w', 'h', 'bg', 'navy', 'blue', 'red', 'amber', 'green', 'dark', 'muted', 'border', 'white');
    }

    private function drawThickLine($im, int $x1, int $y1, int $x2, int $y2, $color, int $thickness = 3): void
    {
        imagesetthickness($im, $thickness);
        imageline($im, $x1, $y1, $x2, $y2, $color);
        imagesetthickness($im, 1);
    }

    private function generateDiagramSegitiga(string $path): void
    {
        $c = $this->createCanvas(640, 340, 'SKETSA RANGKA SEGITIGA SIKU-SIKU (MATEMATIKA)');
        extract($c);

        // Titik segitiga: A (140, 275), B (470, 275), C (140, 95)
        $ax = 150; $ay = 275;
        $bx = 470; $by = 275;
        $cx = 150; $cy = 95;

        // Isi segitiga ringan
        $fillLight = imagecolorallocate($im, 224, 242, 254);
        imagefilledpolygon($im, [$ax, $ay, $bx, $by, $cx, $cy], $fillLight);

        $this->drawThickLine($im, $ax, $ay, $bx, $by, $navy, 4);
        $this->drawThickLine($im, $ax, $ay, $cx, $cy, $navy, 4);
        $this->drawThickLine($im, $cx, $cy, $bx, $by, $red, 4);

        // Simbol siku-siku
        imagerectangle($im, $ax, $ay - 22, $ax + 22, $ay, $blue);

        // Label dimensi
        imagestring($im, 5, 250, 288, 'Alas (a) = 8 meter', $navy);
        imagestring($im, 5, 32, 175, 'Tinggi', $navy);
        imagestring($im, 5, 32, 195, '(t) = 6 m', $navy);
        imagestring($im, 5, 330, 155, 'Sisi Miring (c) = ?', $red);
        imagestring($im, 3, 18, 315, 'Petunjuk: Gunakan Teorema Pythagoras c^2 = a^2 + t^2', $muted);

        imagepng($im, $path);
        imagedestroy($im);
    }

    private function generateDiagramRangkaian(string $path): void
    {
        $c = $this->createCanvas(640, 340, 'SKEMA RANGKAIAN SERI DC (TEKNIK LISTRIK / TITL)');
        extract($c);

        // Kotak rangkaian kawat
        $this->drawThickLine($im, 110, 95, 530, 95, $navy, 3);
        $this->drawThickLine($im, 530, 95, 530, 260, $navy, 3);
        $this->drawThickLine($im, 110, 260, 530, 260, $navy, 3);
        $this->drawThickLine($im, 110, 95, 110, 160, $navy, 3);
        $this->drawThickLine($im, 110, 195, 110, 260, $navy, 3);

        // Sumber tegangan baterai di kiri
        $this->drawThickLine($im, 82, 160, 138, 160, $red, 4);
        $this->drawThickLine($im, 95, 195, 125, 195, $navy, 5);
        imagestring($im, 5, 25, 168, 'V = 24V', $red);

        // Resistor R1 dan R2 di jalur atas
        imagefilledrectangle($im, 195, 77, 305, 113, $white);
        imagerectangle($im, 195, 77, 305, 113, $blue);
        imagestring($im, 5, 206, 87, 'R1 = 4 Ohm', $blue);

        imagefilledrectangle($im, 355, 77, 465, 113, $white);
        imagerectangle($im, 355, 77, 465, 113, $blue);
        imagestring($im, 5, 366, 87, 'R2 = 8 Ohm', $blue);

        // Arah arus I
        $this->drawThickLine($im, 135, 122, 185, 122, $green, 3);
        imagestring($im, 5, 140, 130, 'Arus I = ?', $green);
        imagestring($im, 3, 18, 308, 'Hukum Ohm Seri: R_total = R1 + R2  |  I = V / R_total', $muted);

        imagepng($im, $path);
        imagedestroy($im);
    }

    private function generateDiagramLas(string $path): void
    {
        $c = $this->createCanvas(640, 340, 'ILUSTRASI SAMBUNGAN PELAT LAS (TEKNIK PENGELASAN / TPFL)');
        extract($c);

        $steel = imagecolorallocate($im, 148, 163, 184);
        $weld = imagecolorallocate($im, 234, 88, 12);

        // Pelat horizontal & vertikal (T-Joint)
        imagefilledrectangle($im, 110, 210, 520, 245, $steel);
        imagerectangle($im, 110, 210, 520, 245, $navy);
        imagefilledrectangle($im, 290, 90, 325, 210, $steel);
        imagerectangle($im, 290, 90, 325, 210, $navy);

        // Jalur las oranye
        imagefilledellipse($im, 286, 206, 18, 18, $weld);
        imagefilledellipse($im, 329, 206, 18, 18, $weld);

        // Garis ukuran panjang las
        $this->drawThickLine($im, 110, 270, 520, 270, $red, 2);
        imagestring($im, 5, 175, 278, '5 Benda Kerja x 60 cm = 300 cm', $red);

        // Keterangan elektroda
        imagefilledrectangle($im, 370, 95, 605, 165, $white);
        imagerectangle($im, 370, 95, 605, 165, $blue);
        imagestring($im, 4, 382, 108, 'Elektroda: AWS E6013', $navy);
        imagestring($im, 4, 382, 132, 'Kapasitas: 25 cm / btg', $blue);

        imagepng($im, $path);
        imagedestroy($im);
    }

    private function generateDiagramGear(string $path): void
    {
        $c = $this->createCanvas(640, 340, 'TRANSMISI RODA GIGI / GEAR RATIO (OTOMOTIF / TKL)');
        extract($c);

        $gearBg1 = imagecolorallocate($im, 219, 234, 254);
        $gearBg2 = imagecolorallocate($im, 254, 243, 199);

        // Gear 1 (Kecil - Z1=15)
        imagefilledellipse($im, 195, 170, 110, 110, $gearBg1);
        imageellipse($im, 195, 170, 110, 110, $blue);
        imageellipse($im, 195, 170, 32, 32, $navy);
        imagestring($im, 5, 152, 238, 'GEAR 1 (Z1=15)', $blue);
        imagestring($im, 4, 150, 258, 'n1 = 1.200 RPM', $navy);

        // Gear 2 (Besar - Z2=45)
        imagefilledellipse($im, 385, 170, 190, 190, $gearBg2);
        imageellipse($im, 385, 170, 190, 190, $amber);
        imageellipse($im, 385, 170, 44, 44, $navy);
        imagestring($im, 5, 330, 275, 'GEAR 2 (Z2=45)', $amber);
        imagestring($im, 5, 340, 295, 'n2 = ? RPM', $red);

        imagestring($im, 3, 20, 315, 'Rumus Transmisi: n1 x Z1 = n2 x Z2', $muted);

        imagepng($im, $path);
        imagedestroy($im);
    }

    private function generateDiagramListrik(string $path): void
    {
        $c = $this->createCanvas(640, 340, 'DATA PEMAKAIAN DAYA LISTRIK BENGKEL (IPA & MTK)');
        extract($c);

        // Kartu informasi tabel
        imagefilledrectangle($im, 45, 65, 595, 295, $white);
        imagerectangle($im, 45, 65, 595, 295, $navy);

        imagefilledrectangle($im, 45, 65, 595, 102, $blue);
        imagestring($im, 5, 65, 76, 'DATA PERALATAN PRAKTIK & TARIF LISTRIK', $white);

        imagestring($im, 5, 65, 120, '1. Mesin Gerinda Bengkel   : 800 Watt', $dark);
        imagestring($im, 5, 65, 152, '2. Lampu Penerangan Ruang  : 200 Watt', $dark);
        imagestring($im, 5, 65, 184, '3. Lama Pemakaian Harian   : 5 Jam / Hari (selama 20 Hari)', $navy);
        imagestring($im, 5, 65, 218, '4. Tarif Dasar Listrik     : Rp 1.500 / kWh', $red);

        imagestring($im, 4, 65, 260, 'Catatan: 1 kW = 1.000 Watt  |  Energi (kWh) = Daya (kW) x Jam', $muted);

        imagepng($im, $path);
        imagedestroy($im);
    }
}
