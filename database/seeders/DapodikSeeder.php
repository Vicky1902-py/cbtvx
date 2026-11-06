<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MasterSekolah;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\JadwalUjian;
use App\Models\PetugasUjian;
use App\Models\BeritaAcara;

class DapodikSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update Master Profil Sekolah Resmi Dapodik
        MasterSekolah::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SMK NEGERI 1 KUPANG BARAT',
                'npsn' => '69727541',
                'alamat' => 'Desa Kuanheun, RT 02 / RW 02',
                'kecamatan' => 'Kupang Barat',
                'kabupaten' => 'Kabupaten Kupang',
                'provinsi' => 'Nusa Tenggara Timur',
                'dinas_pendidikan' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
                'kepala_sekolah' => 'Vicky Koroh, S.Pd., M.Kom.',
                'nip_kepala_sekolah' => '198501192011011005',
                'ttd_kepala_sekolah' => 'images/ttd_kepsek.png',
                'logo_sekolah' => 'images/logo_smk.png',
                'logo_pemprov' => 'images/logo_pemprov_ntt.svg',
                'tahun_ajaran_aktif' => '2026/2027',
                'semester_aktif' => 'Genap',
            ]
        );

        // 2. Sinkronisasi 12 Rombongan Belajar (Kelas Utama)
        $kelasMap = [];
        $kelasData = array (
  0 => 
  array (
    'kode' => 'X-DPIB',
    'nama' => 'X DPIB',
    'tingkat' => '10',
    'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
    'sheet' => '1. X DPIB',
    'kode_rombel' => '001',
  ),
  1 => 
  array (
    'kode' => 'X-TKL',
    'nama' => 'X TKL',
    'tingkat' => '10',
    'jurusan' => 'Teknik Ketenagalistrikan',
    'sheet' => '2. X TKL',
    'kode_rombel' => '002',
  ),
  2 => 
  array (
    'kode' => 'X-TO',
    'nama' => 'X TO',
    'tingkat' => '10',
    'jurusan' => 'Teknik Otomotif',
    'sheet' => '3. X TO',
    'kode_rombel' => '003',
  ),
  3 => 
  array (
    'kode' => 'X-TPFL',
    'nama' => 'X TPFL',
    'tingkat' => '10',
    'jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam',
    'sheet' => '4. X TPFL',
    'kode_rombel' => '004',
  ),
  4 => 
  array (
    'kode' => 'XI-DPIB',
    'nama' => 'XI DPIB',
    'tingkat' => '11',
    'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
    'sheet' => '5. XI DPIB',
    'kode_rombel' => '005',
  ),
  5 => 
  array (
    'kode' => 'XI-TITL',
    'nama' => 'XI TITL',
    'tingkat' => '11',
    'jurusan' => 'Teknik Instalasi Tenaga Listrik',
    'sheet' => '6. XI TITL',
    'kode_rombel' => '006',
  ),
  6 => 
  array (
    'kode' => 'XI-TKR',
    'nama' => 'XI TKR',
    'tingkat' => '11',
    'jurusan' => 'Teknik Kendaraan Ringan',
    'sheet' => '7. XI TKR',
    'kode_rombel' => '007',
  ),
  7 => 
  array (
    'kode' => 'XI-TP',
    'nama' => 'XI TP',
    'tingkat' => '11',
    'jurusan' => 'Teknik Pemesinan',
    'sheet' => '8. XI TP',
    'kode_rombel' => '008',
  ),
  8 => 
  array (
    'kode' => 'XII-DPIB',
    'nama' => 'XII DPIB',
    'tingkat' => '12',
    'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
    'sheet' => '9. XII DPIB',
    'kode_rombel' => '009',
  ),
  9 => 
  array (
    'kode' => 'XII-TITL',
    'nama' => 'XII TITL',
    'tingkat' => '12',
    'jurusan' => 'Teknik Instalasi Tenaga Listrik',
    'sheet' => '10. XII TITL',
    'kode_rombel' => '010',
  ),
  10 => 
  array (
    'kode' => 'XII-TKR',
    'nama' => 'XII TKR',
    'tingkat' => '12',
    'jurusan' => 'Teknik Kendaraan Ringan',
    'sheet' => '11. XII TKR',
    'kode_rombel' => '011',
  ),
  11 => 
  array (
    'kode' => 'XII-TP',
    'nama' => 'XII TP',
    'tingkat' => '12',
    'jurusan' => 'Teknik Pemesinan',
    'sheet' => '12. XII TP',
    'kode_rombel' => '012',
  ),
);
        foreach ($kelasData as $c) {
            $kls = Kelas::updateOrCreate(
                ['kode_kelas' => $c['kode']],
                [
                    'nama_kelas' => $c['nama'],
                    'tingkat' => $c['tingkat'],
                    'jurusan' => $c['jurusan'],
                ]
            );
            $kelasMap[$c['kode']] = $kls->id;
        }

        // 3. Sinkronisasi 38 Guru & Tenaga Kependidikan (PTK)
        $ptkData = array (
  0 => 
  array (
    'name' => 'ANA MARIA PATRICIA GARA, S.Pd',
    'username' => 'anamariapatrici',
    'email' => 'anamariapatriciagara@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Bimbingan dan Konseling',
    'status' => 'Guru Honor Sekolah',
  ),
  1 => 
  array (
    'name' => 'Apolos Kofan, S.Pd',
    'username' => '196704062006041009',
    'email' => 'apoloskofan@smkn1kupangbarat.sch.id',
    'nip' => '196704062006041009',
    'nuptk' => '7738745649200022',
    'jk' => 'L',
    'jenis' => 'Kepala Sekolah',
    'mapel' => 'Pendidikan Sejarah',
    'status' => 'PNS',
  ),
  2 => 
  array (
    'name' => 'Apriyana Sole, S.Sn',
    'username' => '199704112025212104',
    'email' => 'apriyanasole@smkn1kupangbarat.sch.id',
    'nip' => '199704112025212104',
    'nuptk' => '6743775676230132',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Musik',
    'status' => 'PPPK',
  ),
  3 => 
  array (
    'name' => 'Apriyani Robbiah Balla, S.E.',
    'username' => '198104152010012027',
    'email' => 'apriyanirobbiahballa@smkn1kupangbarat.sch.id',
    'nip' => '198104152010012027',
    'nuptk' => '0747759661210002',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Ekonomi',
    'status' => 'PNS',
  ),
  4 => 
  array (
    'name' => 'Arylien Ludji Bire, S.Pd',
    'username' => '198906072022212025',
    'email' => 'arylienludjibire@smkn1kupangbarat.sch.id',
    'nip' => '198906072022212025',
    'nuptk' => '1939767668130072',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Bangunan',
    'status' => 'PPPK',
  ),
  5 => 
  array (
    'name' => 'Bernabas Balu Bildad Baun, S.Pd',
    'username' => '198111232022211008',
    'email' => 'bernabasbalubildadbaun@smkn1kupangbarat.sch.id',
    'nip' => '198111232022211008',
    'nuptk' => '3455759661200033',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Bangunan',
    'status' => 'PPPK',
  ),
  6 => 
  array (
    'name' => 'Elfrida Kopong, S.Pd',
    'username' => '199102242025212028',
    'email' => 'elfridakopong@smkn1kupangbarat.sch.id',
    'nip' => '199102242025212028',
    'nuptk' => '3556769670230202',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Keguruan dan Ilmu Pendidikan',
    'status' => 'PPPK',
  ),
  7 => 
  array (
    'name' => 'Eliatus Sau, S.Pd',
    'username' => '198310142022211014',
    'email' => 'eliatussau@smkn1kupangbarat.sch.id',
    'nip' => '198310142022211014',
    'nuptk' => '6346761662130113',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Olahraga dan Kesehatan',
    'status' => 'PPPK',
  ),
  8 => 
  array (
    'name' => 'Erna Nomate, S.Pd',
    'username' => '9658765666130132',
    'email' => 'ernanomate@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '9658765666130132',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Biologi',
    'status' => 'Guru Honor Sekolah',
  ),
  9 => 
  array (
    'name' => 'Floribertus Dhae Goa, S.Pd',
    'username' => '199103082020121003',
    'email' => 'floribertusdhaegoa@smkn1kupangbarat.sch.id',
    'nip' => '199103082020121003',
    'nuptk' => '9640769670130162',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  10 => 
  array (
    'name' => 'Helma Soraya Tjandri, S.Pd',
    'username' => '1456772673230223',
    'email' => 'helmasorayatjandri@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '1456772673230223',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Bahasa Dan Sastra Indonesia',
    'status' => 'Guru Honor Sekolah',
  ),
  11 => 
  array (
    'name' => 'Ibrahim Metboki, S.Pd',
    'username' => '199002182022211008',
    'email' => 'ibrahimmetboki@smkn1kupangbarat.sch.id',
    'nip' => '199002182022211008',
    'nuptk' => '2550768669130052',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Elektro',
    'status' => 'PPPK',
  ),
  12 => 
  array (
    'name' => 'Izak Robinson Koroh, S.Pd',
    'username' => '199002192020121003',
    'email' => 'izakrobinsonkoroh@smkn1kupangbarat.sch.id',
    'nip' => '199002192020121003',
    'nuptk' => '5551768669130162',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Elektro Arus Lemah',
    'status' => 'PNS',
  ),
  13 => 
  array (
    'name' => 'Januario Pa Padja, S.Pd',
    'username' => '199101182020121003',
    'email' => 'januariopapadja@smkn1kupangbarat.sch.id',
    'nip' => '199101182020121003',
    'nuptk' => '3450769670130162',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  14 => 
  array (
    'name' => 'Jemi Saba Buan, Teologi',
    'username' => '1040763664130223',
    'email' => 'jemisababuan@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '1040763664130223',
    'jk' => 'L',
    'jenis' => 'Tenaga Kependidikan',
    'mapel' => 'Lainnya',
    'status' => 'Tenaga Honor Sekolah',
  ),
  15 => 
  array (
    'name' => 'Jhonson Ebenheiser Boimau, S.Pd',
    'username' => '197901192011011002',
    'email' => 'jhonsonebenheiserboimau@smkn1kupangbarat.sch.id',
    'nip' => '197901192011011002',
    'nuptk' => '1451757659120002',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  16 => 
  array (
    'name' => 'Kader Mansyur, S.Pd',
    'username' => '198006012010011032',
    'email' => 'kadermansyur@smkn1kupangbarat.sch.id',
    'nip' => '198006012010011032',
    'nuptk' => '0933758660200052',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  17 => 
  array (
    'name' => 'Maria Lebangu, S.Ag',
    'username' => '198808192025212040',
    'email' => 'marialebangu@smkn1kupangbarat.sch.id',
    'nip' => '198808192025212040',
    'nuptk' => '4151766670130143',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Agama Katholik',
    'status' => 'PPPK',
  ),
  18 => 
  array (
    'name' => 'Maryon Lazarus Lakapu, S.Pd',
    'username' => '198203112022211016',
    'email' => 'maryonlazaruslakapu@smkn1kupangbarat.sch.id',
    'nip' => '198203112022211016',
    'nuptk' => '3643760663200022',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Lainnya',
    'status' => 'PPPK',
  ),
  19 => 
  array (
    'name' => 'Mesak Meliyaki Natbais, S.Pd',
    'username' => '198603262022211009',
    'email' => 'mesakmeliyakinatbais@smkn1kupangbarat.sch.id',
    'nip' => '198603262022211009',
    'nuptk' => '0658764665130132',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Elektro',
    'status' => 'PPPK',
  ),
  20 => 
  array (
    'name' => 'Messakh Paulus Adu, S.Pd',
    'username' => '198403172010011028',
    'email' => 'messakhpaulusadu@smkn1kupangbarat.sch.id',
    'nip' => '198403172010011028',
    'nuptk' => '4649762664110032',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  21 => 
  array (
    'name' => 'Meylen Petna Amtiran, S.Pd',
    'username' => '199305012022212021',
    'email' => 'meylenpetnaamtiran@smkn1kupangbarat.sch.id',
    'nip' => '199305012022212021',
    'nuptk' => '6833771672130012',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Kimia',
    'status' => 'PPPK',
  ),
  22 => 
  array (
    'name' => 'Milani Sahar, S.Pd',
    'username' => '198412102010012040',
    'email' => 'milanisahar@smkn1kupangbarat.sch.id',
    'nip' => '198412102010012040',
    'nuptk' => '7542762665210023',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Bahasa Inggris',
    'status' => 'PNS',
  ),
  23 => 
  array (
    'name' => 'Mondra Suwita Laibois, S.Pd',
    'username' => '198905062022212023',
    'email' => 'mondrasuwitalaibois@smkn1kupangbarat.sch.id',
    'nip' => '198905062022212023',
    'nuptk' => '3838767668131052',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Pancasila dan Kewarganegaraan',
    'status' => 'PPPK',
  ),
  24 => 
  array (
    'name' => 'Musliadi Tema',
    'username' => '198503142012121003',
    'email' => 'musliaditema@smkn1kupangbarat.sch.id',
    'nip' => '198503142012121003',
    'nuptk' => '',
    'jk' => 'L',
    'jenis' => 'Tenaga Kependidikan',
    'mapel' => 'Ilmu Pengetahuan Sosial (IPS)',
    'status' => 'PNS',
  ),
  25 => 
  array (
    'name' => 'Potifera Buan, S.Pd',
    'username' => '198911042024212044',
    'email' => 'potiferabuan@smkn1kupangbarat.sch.id',
    'nip' => '198911042024212044',
    'nuptk' => '8436767668130143',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Agama Kristen',
    'status' => 'PPPK',
  ),
  26 => 
  array (
    'name' => 'Sara Marisa Otemusu, S.Pd',
    'username' => '198708282025212053',
    'email' => 'saramarisaotemusu@smkn1kupangbarat.sch.id',
    'nip' => '198708282025212053',
    'nuptk' => '7160765667131233',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Bahasa Inggris',
    'status' => 'PPPK',
  ),
  27 => 
  array (
    'name' => 'Serli Jumadil Taebenu, S.Pd',
    'username' => '199312052020122008',
    'email' => 'serlijumadiltaebenu@smkn1kupangbarat.sch.id',
    'nip' => '199312052020122008',
    'nuptk' => '9537771672130033',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Elektro',
    'status' => 'PNS',
  ),
  28 => 
  array (
    'name' => 'Sinta Ariance Pate Lado, S.Pd',
    'username' => '0759773674230242',
    'email' => 'sintaariancepatelado@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '0759773674230242',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Ilmu Pendidikan Agama Islam',
    'status' => 'Guru Honor Sekolah',
  ),
  29 => 
  array (
    'name' => 'Sofia Augustina Boy, S.P',
    'username' => '197508102008032001',
    'email' => 'sofiaaugustinaboy@smkn1kupangbarat.sch.id',
    'nip' => '197508102008032001',
    'nuptk' => '1142753655300073',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Agronomi Pertanian',
    'status' => 'PNS',
  ),
  30 => 
  array (
    'name' => 'Terezinha Da Costa Cabral, S.Pd',
    'username' => '3942775676230152',
    'email' => 'terezinhadacostacabral@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '3942775676230152',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Sejarah',
    'status' => 'Guru Honor Sekolah',
  ),
  31 => 
  array (
    'name' => 'Thomen Blegur, S.Pd',
    'username' => '198105112010011012',
    'email' => 'thomenblegur@smkn1kupangbarat.sch.id',
    'nip' => '198105112010011012',
    'nuptk' => '0843759660200052',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Mesin',
    'status' => 'PNS',
  ),
  32 => 
  array (
    'name' => 'Vinsensius Ferrer Kua, S.Pd',
    'username' => '199204052020121007',
    'email' => 'vinsensiusferrerkua@smkn1kupangbarat.sch.id',
    'nip' => '199204052020121007',
    'nuptk' => '7737770671130092',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Bangunan',
    'status' => 'PNS',
  ),
  33 => 
  array (
    'name' => 'Yefrison Rido, S.Pd',
    'username' => '198601142020121003',
    'email' => 'yefrisonrido@smkn1kupangbarat.sch.id',
    'nip' => '198601142020121003',
    'nuptk' => '0446764665130172',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Teknik Bangunan',
    'status' => 'PNS',
  ),
  34 => 
  array (
    'name' => 'Yesti Yulisna Nomate, S.Pd',
    'username' => '0945774675230212',
    'email' => 'yestiyulisnanomate@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '0945774675230212',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Matematika',
    'status' => 'Guru Honor Sekolah',
  ),
  35 => 
  array (
    'name' => 'Yohana J. Fati, S.Pd',
    'username' => '7339765666131093',
    'email' => 'yohanajfati@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '7339765666131093',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Bahasa Indonesia',
    'status' => 'Guru Honor Sekolah',
  ),
  36 => 
  array (
    'name' => 'Yonatan Martinus Sau, S.Pd',
    'username' => '198406032022212022',
    'email' => 'yonatanmartinussau@smkn1kupangbarat.sch.id',
    'nip' => '198406032022212022',
    'nuptk' => '4935762663130162',
    'jk' => 'L',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Bahasa Indonesia',
    'status' => 'PPPK',
  ),
  37 => 
  array (
    'name' => 'Yuni Yati Tiip, S.Pd',
    'username' => '8946766667130122',
    'email' => 'yuniyatitiip@smkn1kupangbarat.sch.id',
    'nip' => '',
    'nuptk' => '8946766667130122',
    'jk' => 'P',
    'jenis' => 'Guru',
    'mapel' => 'Pendidikan Fisika',
    'status' => 'Guru Honor Sekolah',
  ),
);
        $defaultGuruPassword = Hash::make('guru123');
        foreach ($ptkData as $ptk) {
            User::updateOrCreate(
                ['username' => $ptk['username']],
                [
                    'name' => $ptk['name'],
                    'email' => $ptk['email'],
                    'nip' => $ptk['nip'] ?? null,
                    'nuptk' => $ptk['nuptk'] ?? null,
                    'password' => $defaultGuruPassword,
                    'role' => 'guru',
                    'status' => 'aktif',
                ]
            );
        }

        // 4. Sinkronisasi 89 Siswa Riil SMKN 1 Kupang Barat
        $siswaData = array (
  0 => 
  array (
    'kelas_kode' => 'X-DPIB',
    'nama' => 'ANTRIANA BUAN',
    'nisn' => '0103841671',
    'nis' => '732',
    'jk' => 'P',
    'nopes' => '26-24-05-01-001-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0103841671',
  ),
  1 => 
  array (
    'kelas_kode' => 'X-DPIB',
    'nama' => 'JECIKA FLORADONA AGNESIA LUTTE',
    'nisn' => '0118183006',
    'nis' => '733',
    'jk' => 'P',
    'nopes' => '26-24-05-01-001-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0118183006',
  ),
  2 => 
  array (
    'kelas_kode' => 'X-DPIB',
    'nama' => 'Velinda Faustin Lanus',
    'nisn' => '0117774391',
    'nis' => '734',
    'jk' => 'P',
    'nopes' => '26-24-05-01-001-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0117774391',
  ),
  3 => 
  array (
    'kelas_kode' => 'X-DPIB',
    'nama' => 'YOKHEBET MISSA',
    'nisn' => '0108592725',
    'nis' => '735',
    'jk' => 'P',
    'nopes' => '26-24-05-01-001-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0108592725',
  ),
  4 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'ADI YAKOB NATBAIS',
    'nisn' => '0098369533',
    'nis' => '736',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0098369533',
  ),
  5 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'ANTONIUS BASA JUNIOR HAYON',
    'nisn' => '0111124450',
    'nis' => '737',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0111124450',
  ),
  6 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'ARJUNA IMAN SUFMELA',
    'nisn' => '0119735041',
    'nis' => '738',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0119735041',
  ),
  7 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'AYU DANISHA OLBATA',
    'nisn' => '0117283592',
    'nis' => '739',
    'jk' => 'P',
    'nopes' => '26-24-05-01-002-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0117283592',
  ),
  8 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'CHRISFIKEN DERVERIUS WILSON BENG',
    'nisn' => '0104412914',
    'nis' => '740',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0104412914',
  ),
  9 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'CHRISTIANO PRATAMA ADAM KOLLOH',
    'nisn' => '0105143744',
    'nis' => '741',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0105143744',
  ),
  10 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'ELMI JUBLINA TOSI',
    'nisn' => '0117751359',
    'nis' => '742',
    'jk' => 'P',
    'nopes' => '26-24-05-01-002-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0117751359',
  ),
  11 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'FORLAND ALFIN KOEN',
    'nisn' => '0115575860',
    'nis' => '743',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0115575860',
  ),
  12 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'IRFANCE NUBATONIS',
    'nisn' => '0105984736',
    'nis' => '744',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0105984736',
  ),
  13 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'ORLAN ORLANDO MAJEKI SOLE',
    'nisn' => '0103574694',
    'nis' => '745',
    'jk' => 'L',
    'nopes' => '26-24-05-01-002-010',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0103574694',
  ),
  14 => 
  array (
    'kelas_kode' => 'X-TKL',
    'nama' => 'VANESIA BETRIS ELANA KOFAN',
    'nisn' => '0108391444',
    'nis' => '760',
    'jk' => 'P',
    'nopes' => '26-24-05-01-002-011',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0108391444',
  ),
  15 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'Abraham Silla',
    'nisn' => '0118545259',
    'nis' => '758',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0118545259',
  ),
  16 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'ALDI OKTOVIANUS TLONAEN',
    'nisn' => '3091066165',
    'nis' => '746',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '3091066165',
  ),
  17 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'Beni Hatti',
    'nisn' => '0112443816',
    'nis' => '759',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0112443816',
  ),
  18 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'Charles Mesak Fangidae',
    'nisn' => '3089656369',
    'nis' => '747',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '3089656369',
  ),
  19 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'Danil Uria Yohanes',
    'nisn' => '0102364769',
    'nis' => '748',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0102364769',
  ),
  20 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'DEDI JUPITER ATIN',
    'nisn' => '0115827764',
    'nis' => '749',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0115827764',
  ),
  21 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'DELVAN YELISMAR KOROLADO',
    'nisn' => '0104238664',
    'nis' => '750',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0104238664',
  ),
  22 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'GILBERTO ABNER KOFAN',
    'nisn' => '0109205541',
    'nis' => '751',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0109205541',
  ),
  23 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'IMANUEL JULIO PUTRA NAKOL',
    'nisn' => '0111587780',
    'nis' => '752',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0111587780',
  ),
  24 => 
  array (
    'kelas_kode' => 'X-TO',
    'nama' => 'Miracle Wanara Lona',
    'nisn' => '0112778900',
    'nis' => '753',
    'jk' => 'L',
    'nopes' => '26-24-05-01-003-010',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0112778900',
  ),
  25 => 
  array (
    'kelas_kode' => 'X-TPFL',
    'nama' => 'Reivaldi Juanda Thin',
    'nisn' => '0098633950',
    'nis' => '755',
    'jk' => 'L',
    'nopes' => '26-24-05-01-004-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0098633950',
  ),
  26 => 
  array (
    'kelas_kode' => 'X-TPFL',
    'nama' => 'Rio Sepe',
    'nisn' => '0109183508',
    'nis' => '756',
    'jk' => 'L',
    'nopes' => '26-24-05-01-004-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0109183508',
  ),
  27 => 
  array (
    'kelas_kode' => 'X-TPFL',
    'nama' => 'Yarid Manat',
    'nisn' => '0118382389',
    'nis' => '757',
    'jk' => 'L',
    'nopes' => '26-24-05-01-004-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0118382389',
  ),
  28 => 
  array (
    'kelas_kode' => 'XI-DPIB',
    'nama' => 'Clara Angella Buan',
    'nisn' => '0093134801',
    'nis' => '704',
    'jk' => 'P',
    'nopes' => '26-24-05-01-005-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0093134801',
  ),
  29 => 
  array (
    'kelas_kode' => 'XI-DPIB',
    'nama' => 'Gledis Agnesia Runesi',
    'nisn' => '0105238764',
    'nis' => '705',
    'jk' => 'P',
    'nopes' => '26-24-05-01-005-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0105238764',
  ),
  30 => 
  array (
    'kelas_kode' => 'XI-DPIB',
    'nama' => 'In Gloria Damayka Neslaka',
    'nisn' => '0105747434',
    'nis' => '706',
    'jk' => 'P',
    'nopes' => '26-24-05-01-005-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0105747434',
  ),
  31 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Dion Makristan Polin',
    'nisn' => '0093311163',
    'nis' => '707',
    'jk' => 'L',
    'nopes' => '26-24-05-01-006-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0093311163',
  ),
  32 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Jastin Kesse',
    'nisn' => '0106413332',
    'nis' => '709',
    'jk' => 'L',
    'nopes' => '26-24-05-01-006-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0106413332',
  ),
  33 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Jhun Elyandro Nomate',
    'nisn' => '0101700598',
    'nis' => '710',
    'jk' => 'L',
    'nopes' => '26-24-05-01-006-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0101700598',
  ),
  34 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Metha Ripania Bantaika',
    'nisn' => '0091164028',
    'nis' => '711',
    'jk' => 'P',
    'nopes' => '26-24-05-01-006-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0091164028',
  ),
  35 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'OLIVIA FEBRINA NAHAK MODOK',
    'nisn' => '0107399657',
    'nis' => '712',
    'jk' => 'P',
    'nopes' => '26-24-05-01-006-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0107399657',
  ),
  36 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Olivia Graciana Banu',
    'nisn' => '0096843401',
    'nis' => '713',
    'jk' => 'P',
    'nopes' => '26-24-05-01-006-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0096843401',
  ),
  37 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Rafli Leksi Lumente',
    'nisn' => '0071616291',
    'nis' => '714',
    'jk' => 'L',
    'nopes' => '26-24-05-01-006-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0071616291',
  ),
  38 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Rani Muherji Tenis',
    'nisn' => '0103295119',
    'nis' => '715',
    'jk' => 'P',
    'nopes' => '26-24-05-01-006-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0103295119',
  ),
  39 => 
  array (
    'kelas_kode' => 'XI-TITL',
    'nama' => 'Silverius Tanon',
    'nisn' => '0072178309',
    'nis' => '716',
    'jk' => 'L',
    'nopes' => '26-24-05-01-006-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0072178309',
  ),
  40 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'ALVINO DUDERIUS TANO',
    'nisn' => '0107732305',
    'nis' => '717',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0107732305',
  ),
  41 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'ANGGELOS MAU',
    'nisn' => '0083517641',
    'nis' => '718',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0083517641',
  ),
  42 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'BERNADUS MIKHAEL SOLE',
    'nisn' => '0108623503',
    'nis' => '719',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0108623503',
  ),
  43 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Ferdi Refaya Atin',
    'nisn' => '0083985816',
    'nis' => '720',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0083985816',
  ),
  44 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'GOSARDO VABRIEGAS IMANUEL DAFA',
    'nisn' => '0102565910',
    'nis' => '721',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0102565910',
  ),
  45 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Marco Timoty Kolloh',
    'nisn' => '0106536644',
    'nis' => '722',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0106536644',
  ),
  46 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Rionald Jabi',
    'nisn' => '0094720859',
    'nis' => '723',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0094720859',
  ),
  47 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Satria Marsel Nomate',
    'nisn' => '0098589093',
    'nis' => '724',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0098589093',
  ),
  48 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Stiven Talmon Nakmofa',
    'nisn' => '0089929024',
    'nis' => '725',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0089929024',
  ),
  49 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Yeremias Wadu',
    'nisn' => '0105033896',
    'nis' => '726',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-010',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0105033896',
  ),
  50 => 
  array (
    'kelas_kode' => 'XI-TKR',
    'nama' => 'Zefanya Grevalgio Kesse',
    'nisn' => '0102585889',
    'nis' => '727',
    'jk' => 'L',
    'nopes' => '26-24-05-01-007-011',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0102585889',
  ),
  51 => 
  array (
    'kelas_kode' => 'XI-TP',
    'nama' => 'Amsy Silla',
    'nisn' => '0101686613',
    'nis' => '728',
    'jk' => 'L',
    'nopes' => '26-24-05-01-008-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0101686613',
  ),
  52 => 
  array (
    'kelas_kode' => 'XI-TP',
    'nama' => 'IMANUEL POLIN',
    'nisn' => '0102272896',
    'nis' => '729',
    'jk' => 'L',
    'nopes' => '26-24-05-01-008-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0102272896',
  ),
  53 => 
  array (
    'kelas_kode' => 'XI-TP',
    'nama' => 'Rivaldo Yunian Tano',
    'nisn' => '0092252662',
    'nis' => '730',
    'jk' => 'L',
    'nopes' => '26-24-05-01-008-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0092252662',
  ),
  54 => 
  array (
    'kelas_kode' => 'XI-TP',
    'nama' => 'Shevchenko Buan',
    'nisn' => '0082406405',
    'nis' => '731',
    'jk' => 'L',
    'nopes' => '26-24-05-01-008-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0082406405',
  ),
  55 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Desta Chico Adegebrial Selef',
    'nisn' => '0081922548',
    'nis' => '671',
    'jk' => 'L',
    'nopes' => '26-24-05-01-009-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0081922548',
  ),
  56 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Erlin Henuk',
    'nisn' => '0099968951',
    'nis' => '672',
    'jk' => 'P',
    'nopes' => '26-24-05-01-009-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0099968951',
  ),
  57 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Gio Kristiano Arkian',
    'nisn' => '0106686767',
    'nis' => '673',
    'jk' => 'L',
    'nopes' => '26-24-05-01-009-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0106686767',
  ),
  58 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Inri Elma Bantaika',
    'nisn' => '0093978227',
    'nis' => '674',
    'jk' => 'P',
    'nopes' => '26-24-05-01-009-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0093978227',
  ),
  59 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Letni Yakoharina Ledo',
    'nisn' => '0096030757',
    'nis' => '675',
    'jk' => 'P',
    'nopes' => '26-24-05-01-009-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0096030757',
  ),
  60 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Merciani Illu',
    'nisn' => '0094600510',
    'nis' => '676',
    'jk' => 'P',
    'nopes' => '26-24-05-01-009-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0094600510',
  ),
  61 => 
  array (
    'kelas_kode' => 'XII-DPIB',
    'nama' => 'Salwa Thivanny Bessi',
    'nisn' => '3098412010',
    'nis' => '677',
    'jk' => 'P',
    'nopes' => '26-24-05-01-009-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '3098412010',
  ),
  62 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'ADI GIDEON ALE',
    'nisn' => '0074428364',
    'nis' => '622',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0074428364',
  ),
  63 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Cristiano Gilberd Farenz Leo',
    'nisn' => '0094055462',
    'nis' => '678',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0094055462',
  ),
  64 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Giovano Minfini',
    'nisn' => '0087619569',
    'nis' => '679',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0087619569',
  ),
  65 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Izrael Horazhio Neslaka',
    'nisn' => '0099583575',
    'nis' => '680',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0099583575',
  ),
  66 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Jefri Alexander Saketu',
    'nisn' => '0086519194',
    'nis' => '681',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0086519194',
  ),
  67 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Lili Karina Manat',
    'nisn' => '0095128356',
    'nis' => '682',
    'jk' => 'P',
    'nopes' => '26-24-05-01-010-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0095128356',
  ),
  68 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Rafa Chardia Guysandro Dean',
    'nisn' => '0091500405',
    'nis' => '683',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0091500405',
  ),
  69 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Stevanus Redliadi Banusu',
    'nisn' => '0075026557',
    'nis' => '684',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0075026557',
  ),
  70 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Stinki Jordan Saketu',
    'nisn' => '0093421264',
    'nis' => '685',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0093421264',
  ),
  71 => 
  array (
    'kelas_kode' => 'XII-TITL',
    'nama' => 'Vino Ramon Sede',
    'nisn' => '0092195599',
    'nis' => '686',
    'jk' => 'L',
    'nopes' => '26-24-05-01-010-010',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0092195599',
  ),
  72 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Aril Detefes lasi',
    'nisn' => '0091603315',
    'nis' => '687',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0091603315',
  ),
  73 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Bernad Darius Lasi',
    'nisn' => '0079872632',
    'nis' => '688',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0079872632',
  ),
  74 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Dedi Yanrio Kofan',
    'nisn' => '0094300347',
    'nis' => '689',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0094300347',
  ),
  75 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'DONI SATYA LAKSANA SUFMELA',
    'nisn' => '0089087115',
    'nis' => '690',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0089087115',
  ),
  76 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Frens Misyriko Henuk',
    'nisn' => '0067836221',
    'nis' => '691',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-005',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0067836221',
  ),
  77 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Gregorius Nabunome',
    'nisn' => '0082701787',
    'nis' => '692',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-006',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0082701787',
  ),
  78 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'I Shan Theresa Boling',
    'nisn' => '0083527891',
    'nis' => '693',
    'jk' => 'P',
    'nopes' => '26-24-05-01-011-007',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0083527891',
  ),
  79 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Iddo Abia Luit',
    'nisn' => '0074276217',
    'nis' => '694',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-008',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0074276217',
  ),
  80 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Kevin Jerikho Halla',
    'nisn' => '0096244173',
    'nis' => '695',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-009',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0096244173',
  ),
  81 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Marcel Ardiano Tosi',
    'nisn' => '0087462049',
    'nis' => '696',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-010',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0087462049',
  ),
  82 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Novendro Nehemia Kobo',
    'nisn' => '0085504619',
    'nis' => '697',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-011',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0085504619',
  ),
  83 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Roy Marten Uky',
    'nisn' => '0099670794',
    'nis' => '698',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-012',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0099670794',
  ),
  84 => 
  array (
    'kelas_kode' => 'XII-TKR',
    'nama' => 'Yosua Alfa Rendi Sabuna',
    'nisn' => '0079297107',
    'nis' => '699',
    'jk' => 'L',
    'nopes' => '26-24-05-01-011-013',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0079297107',
  ),
  85 => 
  array (
    'kelas_kode' => 'XII-TP',
    'nama' => 'Arislin Missa',
    'nisn' => '0075382277',
    'nis' => '700',
    'jk' => 'L',
    'nopes' => '26-24-05-01-012-001',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0075382277',
  ),
  86 => 
  array (
    'kelas_kode' => 'XII-TP',
    'nama' => 'ARJUN SAKETU',
    'nisn' => '0075640728',
    'nis' => '701',
    'jk' => 'L',
    'nopes' => '26-24-05-01-012-002',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0075640728',
  ),
  87 => 
  array (
    'kelas_kode' => 'XII-TP',
    'nama' => 'Eurico Etan Nomate',
    'nisn' => '0096732435',
    'nis' => '702',
    'jk' => 'L',
    'nopes' => '26-24-05-01-012-003',
    'sesi' => 'Sesi 1',
    'ruang' => 'Lab Komputer 1',
    'username' => '0096732435',
  ),
  88 => 
  array (
    'kelas_kode' => 'XII-TP',
    'nama' => 'Yandren Dhiki Sion Kese',
    'nisn' => '0082415422',
    'nis' => '703',
    'jk' => 'L',
    'nopes' => '26-24-05-01-012-004',
    'sesi' => 'Sesi 2',
    'ruang' => 'Lab Komputer 1',
    'username' => '0082415422',
  ),
);
        $defaultSiswaPassword = Hash::make('12345');
        foreach ($siswaData as $s) {
            $user = User::updateOrCreate(
                ['username' => $s['username']],
                [
                    'name' => $s['nama'],
                    'email' => $s['username'] . '@siswa.smkn1kupangbarat.sch.id',
                    'password' => $defaultSiswaPassword,
                    'role' => 'siswa',
                    'status' => 'aktif',
                ]
            );

            Siswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $s['nis'],
                    'nisn' => $s['nisn'],
                    'nama_lengkap' => $s['nama'],
                    'jenis_kelamin' => $s['jk'],
                    'kelas_id' => $kelasMap[$s['kelas_kode']] ?? null,
                    'sesi_ujian' => $s['sesi'],
                    'ruangan_ujian' => $s['ruang'],
                    'nomor_peserta' => $s['nopes'],
                    'status_login' => 'offline',
                ]
            );
        }

        // 5. Hubungkan Semua Jadwal Ujian Aktif ke Seluruh 12 Kelas
        $allKelasIds = array_values($kelasMap);
        $jadwals = JadwalUjian::all();
        foreach ($jadwals as $j) {
            $j->kelas()->syncWithoutDetaching($allKelasIds);
        }

        // 6. Default Petugas Ujian (Proktor & Pengawas) & Berita Acara
        $proktorUser = User::where('username', '199002192020121003')->first() ?? User::where('role', 'proktor')->first();
        if ($proktorUser) {
            $proktorUser->update(['role' => 'proktor', 'nip' => '199002192020121003']);
            PetugasUjian::updateOrCreate(
                ['user_id' => $proktorUser->id, 'ruangan' => 'Lab Komputer 1', 'sesi' => 'Sesi 1'],
                ['jenis_petugas' => 'proktor', 'nomor_sk' => '421.5/012/SMKN1.KB/2026']
            );
        }

        $pengawasUser = User::where('username', '198911042024212044')->first() ?? User::where('role', 'guru')->first();
        if ($pengawasUser) {
            PetugasUjian::updateOrCreate(
                ['user_id' => $pengawasUser->id, 'ruangan' => 'Lab Komputer 1', 'sesi' => 'Sesi 1'],
                ['jenis_petugas' => 'pengawas', 'nomor_sk' => '421.5/012/SMKN1.KB/2026']
            );
        }

        $firstJadwal = JadwalUjian::first();
        if ($firstJadwal) {
            BeritaAcara::updateOrCreate(
                ['jadwal_ujian_id' => $firstJadwal->id],
                [
                    'nomor_surat' => '421.5/024/SMKN1.KB/' . date('Y'),
                    'hari_tanggal' => date('d F Y'),
                    'waktu_mulai' => '08:00',
                    'waktu_selesai' => '09:30',
                    'ruangan' => 'Lab Komputer 1',
                    'sesi' => 'Sesi 1',
                    'proktor_id' => $proktorUser ? $proktorUser->id : null,
                    'nama_proktor' => $proktorUser ? $proktorUser->name : 'Izak Robinson Koroh, S.Pd',
                    'nip_proktor' => $proktorUser ? ($proktorUser->nip ?? '199002192020121003') : '199002192020121003',
                    'pengawas_id' => $pengawasUser ? $pengawasUser->id : null,
                    'nama_pengawas' => $pengawasUser ? $pengawasUser->name : 'Potifera Buan, S.Pd',
                    'nip_pengawas' => $pengawasUser ? ($pengawasUser->nip ?? '198911042024212044') : '198911042024212044',
                    'jumlah_peserta_seharusnya' => 89,
                    'jumlah_peserta_hadir' => 89,
                    'jumlah_peserta_tidak_hadir' => 0,
                    'daftar_tidak_hadir' => '-',
                    'catatan_pelaksanaan' => 'Pelaksanaan asesmen CBT berjalan tertib, aman, dan lancar sesuai standar teknis Pusmendik/ANBK.',
                ]
            );
        }

        // 7. Data Master Mata Pelajaran Khas SMK (Muatan Umum & Muatan Kejuruan Berdasarkan Dapodik SMKN 1 Kupang Barat)
        $daftarMapel = [
            // Muatan Umum / Nasional
            [
                'kode' => 'BIN-X',
                'nama' => 'Bahasa Indonesia',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '10',
                'nip_guru' => '',
                'user_guru' => '7339765666131093', // Yohana J. Fati, S.Pd
            ],
            [
                'kode' => 'BIN-XI',
                'nama' => 'Bahasa Indonesia',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '11',
                'nip_guru' => '198406032022212022', // Yonatan Martinus Sau, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'BIN-XII',
                'nama' => 'Bahasa Indonesia',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '12',
                'nip_guru' => '',
                'user_guru' => '1456772673230223', // Helma Soraya Tjandri, S.Pd
            ],
            [
                'kode' => 'MTK-DPIB-X',
                'nama' => 'Matematika Terapan (DPIB)',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => '10',
                'nip_guru' => '',
                'user_guru' => '0945774675230212', // Yesti Yulisna Nomate, S.Pd
            ],
            [
                'kode' => 'MTK-TITL-X',
                'nama' => 'Matematika Terapan (TITL)',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Teknik Instalasi Tenaga Listrik',
                'tingkat' => '10',
                'nip_guru' => '',
                'user_guru' => '0945774675230212', // Yesti Yulisna Nomate, S.Pd
            ],
            [
                'kode' => 'MTK-TO-X',
                'nama' => 'Matematika Terapan (Otomotif)',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Teknik Otomotif',
                'tingkat' => '10',
                'nip_guru' => '',
                'user_guru' => '0945774675230212', // Yesti Yulisna Nomate, S.Pd
            ],
            [
                'kode' => 'MTK-TP-X',
                'nama' => 'Matematika Terapan (Pemesinan)',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Teknik Pemesinan',
                'tingkat' => '10',
                'nip_guru' => '',
                'user_guru' => '0945774675230212', // Yesti Yulisna Nomate, S.Pd
            ],
            [
                'kode' => 'BIG-DPIB',
                'nama' => 'Bahasa Inggris Kejuruan',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => 'Semua',
                'nip_guru' => '198412102010012040', // Milani Sahar, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'BIG-TO',
                'nama' => 'Bahasa Inggris Teknik',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Teknik Otomotif',
                'tingkat' => 'Semua',
                'nip_guru' => '198708282025212053', // Sara Marisa Otemusu, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'PPKN-UMUM',
                'nama' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '198905062022212023', // Mondra Suwita Laibois, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'AGM-KRISTEN',
                'nama' => 'Pendidikan Agama Kristen dan Budi Pekerti',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '198911042024212044', // Potifera Buan, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'AGM-KATOLIK',
                'nama' => 'Pendidikan Agama Katolik dan Budi Pekerti',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '198808192025212040', // Maria Lebangu, S.Ag
                'user_guru' => '',
            ],
            [
                'kode' => 'AGM-ISLAM',
                'nama' => 'Pendidikan Agama Islam dan Budi Pekerti',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '',
                'user_guru' => '0759773674230242', // Sinta Ariance Pate Lado, S.Pd
            ],
            [
                'kode' => 'PJOK-UMUM',
                'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'kelompok' => 'Muatan Kewilayahan (B)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '198310142022211014', // Eliatus Sau, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'SEJ-UMUM',
                'nama' => 'Sejarah Indonesia',
                'kelompok' => 'Muatan Umum (A)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '196704062006041009', // Apolos Kofan, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'SENI-UMUM',
                'nama' => 'Seni Budaya dan Musik',
                'kelompok' => 'Muatan Kewilayahan (B)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => 'Semua',
                'nip_guru' => '199704112025212104', // Apriyana Sole, S.Sn
                'user_guru' => '',
            ],
            [
                'kode' => 'IPAS-X',
                'nama' => 'Projek Ilmu Pengetahuan Alam dan Sosial (IPAS)',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '10',
                'nip_guru' => '199305012022212021', // Meylen Petna Amtiran, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'TKA-SIMDIG',
                'nama' => 'Simulasi dan Komunikasi Digital',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Semua Jurusan',
                'tingkat' => '10',
                'nip_guru' => '198501192011011005', // Vicky Koroh, S.Pd., M.Kom.
                'user_guru' => 'admin',
            ],

            // Muatan Kejuruan Produktif (C2 & C3) Sesuai Jurusan
            [
                'kode' => 'C2-DPIB-X',
                'nama' => 'Dasar-dasar Gambar Teknik dan Statika Bangunan',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => '10',
                'nip_guru' => '199204052020121007', // Vinsensius Ferrer Kua, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-DPIB-XI',
                'nama' => 'Aplikasi Perangkat Lunak & Perancangan Interior Gedung',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => '11',
                'nip_guru' => '198906072022212025', // Arylien Ludji Bire, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-DPIB-XII',
                'nama' => 'Konstruksi Gedung, Sanitasi, dan Estimasi Biaya',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'tingkat' => '12',
                'nip_guru' => '198111232022211008', // Bernabas Balu Bildad Baun, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C2-TITL-X',
                'nama' => 'Dasar-dasar Teknik Ketenagalistrikan & Pengukuran',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Teknik Instalasi Tenaga Listrik',
                'tingkat' => '10',
                'nip_guru' => '199002182022211008', // Ibrahim Metboki, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TITL-XI',
                'nama' => 'Instalasi Penerangan dan Tenaga Listrik Bangunan',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Instalasi Tenaga Listrik',
                'tingkat' => '11',
                'nip_guru' => '198603262022211009', // Mesak Meliyaki Natbais, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TITL-XII',
                'nama' => 'Instalasi Motor Listrik dan Kontrol Otomasi PLC',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Instalasi Tenaga Listrik',
                'tingkat' => '12',
                'nip_guru' => '199312052020122008', // Serli Jumadil Taebenu, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C2-TO-X',
                'nama' => 'Dasar-dasar Otomotif & Gambar Teknik Mesin',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Teknik Otomotif',
                'tingkat' => '10',
                'nip_guru' => '198403172010011028', // Messakh Paulus Adu, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TKR-XI',
                'nama' => 'Pemeliharaan Mesin & Sasis Kendaraan Ringan',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Kendaraan Ringan',
                'tingkat' => '11',
                'nip_guru' => '197901192011011002', // Jhonson Ebenheiser Boimau, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TKR-XII',
                'nama' => 'Pemeliharaan Kelistrikan dan Elektronika Otomotif',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Kendaraan Ringan',
                'tingkat' => '12',
                'nip_guru' => '198006012010011032', // Kader Mansyur, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C2-TP-X',
                'nama' => 'Dasar-dasar Teknik Mesin dan Perkakas Tangan',
                'kelompok' => 'Dasar Program Keahlian (C2)',
                'jurusan' => 'Teknik Pemesinan',
                'tingkat' => '10',
                'nip_guru' => '199103082020121003', // Floribertus Dhae Goa, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TP-XI',
                'nama' => 'Teknik Pemesinan Bubut dan Frais Presisi',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Pemesinan',
                'tingkat' => '11',
                'nip_guru' => '199101182020121003', // Januario Pa Padja, S.Pd
                'user_guru' => '',
            ],
            [
                'kode' => 'C3-TPFL-XII',
                'nama' => 'Teknik Pengelasan dan Fabrikasi Logam Terapan',
                'kelompok' => 'Konsentrasi Keahlian / Produktif (C3)',
                'jurusan' => 'Teknik Pengelasan dan Fabrikasi Logam',
                'tingkat' => '12',
                'nip_guru' => '198105112010011012', // Thomen Blegur, S.Pd
                'user_guru' => '',
            ],
        ];

        foreach ($daftarMapel as $m) {
            $guruUser = null;
            if (!empty($m['nip_guru'])) {
                $guruUser = User::where('nip', $m['nip_guru'])->first();
            }
            if (!$guruUser && !empty($m['user_guru'])) {
                $guruUser = User::where('username', $m['user_guru'])->first();
            }
            if (!$guruUser) {
                $guruUser = User::where('role', 'guru')->first() ?? User::where('role', 'admin')->first();
            }

            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $m['kode']],
                [
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'jurusan' => $m['jurusan'],
                    'tingkat' => $m['tingkat'],
                    'guru_id' => $guruUser ? $guruUser->id : null,
                ]
            );
        }
    }
}
