<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Seeder Ujian Simulasi Asesmen Standar TKA
 * SMK Negeri 1 Kupang Barat di Kuanheun
 * Kepala Sekolah: Vicky Koroh, S.Pd., M.Kom
 */
class Simulasi_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    /**
     * Memastikan User Uji Coba, Token Ujian, dan Relasi Grup selalu aktif
     */
    public function ensure_token_and_user(){
        // 1. Pastikan Group XII SMKN 1 Kupang Barat ada
        $grup_id = 1;
        $q_grup = $this->db->get_where('cbt_user_grup', array('grup_nama' => 'XII SMKN 1 KUPANG BARAT'));
        if($q_grup->num_rows() == 0){
            $this->db->insert('cbt_user_grup', array('grup_nama' => 'XII SMKN 1 KUPANG BARAT'));
            $grup_id = $this->db->insert_id();
        }else{
            $grup_id = $q_grup->row()->grup_id;
        }

        // 2. Pastikan Siswa Uji Coba Terdaftar (password: 123456)
        $q_siswa = $this->db->get_where('cbt_user', array('user_name' => 'siswa_uji'));
        if($q_siswa->num_rows() == 0){
            $this->db->insert('cbt_user', array(
                'user_name' => 'siswa_uji',
                'user_password' => '123456',
                'user_email' => 'siswa_uji@smkn1kupangbarat.sch.id',
                'user_regdate' => date('Y-m-d H:i:s'),
                'user_firstname' => 'Siswa Uji Coba Asesmen (TKA)',
                'user_grup_id' => $grup_id,
                'user_detail' => 'Ruang 1, Sesi 1'
            ));
        }else{
            $this->db->where('user_name', 'siswa_uji')->update('cbt_user', array(
                'user_password' => '123456',
                'user_grup_id' => $grup_id
            ));
        }

        // 3. Pastikan token_user_id mengambil id admin yang valid dari tabel user
        $admin_user_id = 1;
        $q_admin = $this->db->get_where('user', array('username' => 'admin'));
        if($q_admin->num_rows() > 0){
            $admin_user_id = $q_admin->row()->id;
        }else{
            $any_u = $this->db->limit(1)->get('user');
            if($any_u->num_rows() > 0){
                $admin_user_id = $any_u->row()->id;
            }
        }

        // 4. Pastikan Token TKA2026 Aktif untuk semua tes
        $q_token = $this->db->get_where('cbt_tes_token', array('token_isi' => 'TKA2026'));
        if($q_token->num_rows() == 0){
            $this->db->insert('cbt_tes_token', array(
                'token_isi' => 'TKA2026',
                'token_user_id' => $admin_user_id,
                'token_ts' => date('Y-m-d H:i:s'),
                'token_aktif' => 1,
                'token_tes_id' => 0
            ));
        }else{
            $this->db->where('token_isi', 'TKA2026')->update('cbt_tes_token', array(
                'token_aktif' => 1,
                'token_ts' => date('Y-m-d H:i:s'),
                'token_tes_id' => 0
            ));
        }

        // 5. Pastikan Waktu Ujian Simulasi selalu aktif dan terbuka
        $this->db->where('tes_nama LIKE "%SIMULASI%"')->update('cbt_tes', array(
            'tes_begin_time' => '2025-01-01 00:00:00',
            'tes_end_time' => '2030-12-31 23:59:59',
            'tes_token' => 1
        ));

        // 6. Hubungkan semua tes simulasi ke semua grup siswa yang ada di sistem
        $q_tests = $this->db->like('tes_nama', 'SIMULASI')->get('cbt_tes');
        if($q_tests->num_rows() > 0){
            $all_groups = $this->db->get('cbt_user_grup')->result();
            foreach($q_tests->result() as $t){
                foreach($all_groups as $g){
                    $cek_grup = $this->db->get_where('cbt_tesgrup', array('tstgrp_tes_id' => $t->tes_id, 'tstgrp_grup_id' => $g->grup_id));
                    if($cek_grup->num_rows() == 0){
                        $this->db->insert('cbt_tesgrup', array('tstgrp_tes_id' => $t->tes_id, 'tstgrp_grup_id' => $g->grup_id));
                    }
                }
            }
        }
    }

    /**
     * Seed Data Ujian, Topik, dan Soal Lengkap (PG, Benar/Salah, Uraian, Isian Singkat)
     */
    public function seed_simulasi(){
        // Cek jika tes simulasi 25 soal sudah ada di database
        $cek_tes = $this->db->get_where('cbt_tes', array('tes_nama' => 'SIMULASI ASESMEN 25 SOAL (SEMUA TIPE SOAL)'));
        if($cek_tes->num_rows() > 0){
            $this->ensure_token_and_user();
            return;
        }

        // 1. Modul
        $modul_id = 1;
        $q_modul = $this->db->get_where('cbt_modul', array('modul_nama' => 'SIMULASI ASESMEN TKA'));
        if($q_modul->num_rows() == 0){
            $this->db->insert('cbt_modul', array('modul_nama' => 'SIMULASI ASESMEN TKA', 'modul_aktif' => 1));
            $modul_id = $this->db->insert_id();
        }else{
            $modul_id = $q_modul->row()->modul_id;
        }

        // 2. Topik-topik per kategori soal
        // Topik 1: Benar / Salah
        $topik_bs_id = 0;
        $q_tbs = $this->db->get_where('cbt_topik', array('topik_nama' => 'Simulasi - Benar / Salah'));
        if($q_tbs->num_rows() == 0){
            $this->db->insert('cbt_topik', array(
                'topik_modul_id' => $modul_id,
                'topik_nama' => 'Simulasi - Benar / Salah',
                'topik_detail' => 'Bank Soal Benar / Salah SMKN 1 Kupang Barat',
                'topik_aktif' => 1
            ));
            $topik_bs_id = $this->db->insert_id();
        }else{
            $topik_bs_id = $q_tbs->row()->topik_id;
        }

        // Topik 2: Pilihan Ganda
        $topik_pg_id = 0;
        $q_tpg = $this->db->get_where('cbt_topik', array('topik_nama' => 'Simulasi - Pilihan Ganda'));
        if($q_tpg->num_rows() == 0){
            $this->db->insert('cbt_topik', array(
                'topik_modul_id' => $modul_id,
                'topik_nama' => 'Simulasi - Pilihan Ganda',
                'topik_detail' => 'Bank Soal Pilihan Ganda SMKN 1 Kupang Barat',
                'topik_aktif' => 1
            ));
            $topik_pg_id = $this->db->insert_id();
        }else{
            $topik_pg_id = $q_tpg->row()->topik_id;
        }

        // Topik 3: Isian Singkat
        $topik_is_id = 0;
        $q_tis = $this->db->get_where('cbt_topik', array('topik_nama' => 'Simulasi - Isian Singkat'));
        if($q_tis->num_rows() == 0){
            $this->db->insert('cbt_topik', array(
                'topik_modul_id' => $modul_id,
                'topik_nama' => 'Simulasi - Isian Singkat',
                'topik_detail' => 'Bank Soal Isian Singkat SMKN 1 Kupang Barat',
                'topik_aktif' => 1
            ));
            $topik_is_id = $this->db->insert_id();
        }else{
            $topik_is_id = $q_tis->row()->topik_id;
        }

        // Topik 4: Uraian / Esai
        $topik_ur_id = 0;
        $q_tur = $this->db->get_where('cbt_topik', array('topik_nama' => 'Simulasi - Uraian / Esai'));
        if($q_tur->num_rows() == 0){
            $this->db->insert('cbt_topik', array(
                'topik_modul_id' => $modul_id,
                'topik_nama' => 'Simulasi - Uraian / Esai',
                'topik_detail' => 'Bank Soal Uraian / Esai SMKN 1 Kupang Barat',
                'topik_aktif' => 1
            ));
            $topik_ur_id = $this->db->insert_id();
        }else{
            $topik_ur_id = $q_tur->row()->topik_id;
        }

        // 3. Masukkan 10 Butir Soal Benar / Salah (Tipe 4)
        $soal_bs = array(
            array("SMK Negeri 1 Kupang Barat secara administratif berlokasi di Desa Kuanheun, Kecamatan Kupang Barat, Kabupaten Kupang.", 1),
            array("Ibukota dari Provinsi Nusa Tenggara Timur (NTT) adalah Kota Kupang.", 1),
            array("Rumus keliling lingkaran dengan jari-jari r adalah K = pi x r kuadrat.", 0),
            array("Pancasila terdiri dari 5 sila yang menjadi dasar falsafah dan ideologi Negara Kesatuan Republik Indonesia.", 1),
            array("Protokol standar HTTP secara default berjalan pada port komunikasi nomor 443.", 0),
            array("Perangkat keras RAM berfungsi sebagai media penyimpanan data komputer yang bersifat sementara (volatile).", 1),
            array("Kepala SMK Negeri 1 Kupang Barat saat ini adalah Bapak Vicky Koroh, S.Pd., M.Kom.", 1),
            array("Alamat IP 192.168.1.1 termasuk dalam kategori pengalamatan IP Address kelas A.", 0),
            array("Pulau Semau merupakan salah satu wilayah pulau di kawasan Kabupaten Kupang, Nusa Tenggara Timur.", 1),
            array("Satuan kecepatan transmisi bandwidth jaringan komputer diukur dalam satuan Hertz (Hz).", 0)
        );

        foreach($soal_bs as $s){
            $cek_s = $this->db->get_where('cbt_soal', array('soal_topik_id' => $topik_bs_id, 'soal_detail' => $s[0]));
            if($cek_s->num_rows() == 0){
                $this->db->insert('cbt_soal', array(
                    'soal_topik_id' => $topik_bs_id,
                    'soal_detail' => $s[0],
                    'soal_tipe' => 4,
                    'soal_difficulty' => 1,
                    'soal_aktif' => 1
                ));
                $s_id = $this->db->insert_id();
                // Opsi Benar dan Salah
                $this->db->insert('cbt_jawaban', array(
                    'jawaban_soal_id' => $s_id,
                    'jawaban_detail' => 'Benar',
                    'jawaban_benar' => ($s[1] == 1 ? 1 : 0),
                    'jawaban_aktif' => 1
                ));
                $this->db->insert('cbt_jawaban', array(
                    'jawaban_soal_id' => $s_id,
                    'jawaban_detail' => 'Salah',
                    'jawaban_benar' => ($s[1] == 0 ? 1 : 0),
                    'jawaban_aktif' => 1
                ));
            }
        }

        // 4. Masukkan 18 Pilihan Ganda (Tipe 1)
        $soal_pg = array(
            array(
                "Lambang bintang emas pada perisai Garuda Pancasila melambangkan sila ke...",
                array("Sila Pertama (Ketuhanan Yang Maha Esa)", "Sila Kedua (Kemanusiaan yang Adil dan Beradab)", "Sila Ketiga (Persatuan Indonesia)", "Sila Keempat (Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan)", "Sila Kelima (Keadilan Sosial bagi Seluruh Rakyat Indonesia)")
            ),
            array(
                "Hasil dari perhitungan matematika 25 + 15 x 3 - 10 adalah...",
                array("60", "110", "50", "75", "45")
            ),
            array(
                "Topologi jaringan yang menghubungkan setiap komputer klien melalui konsentrator Switch atau Hub disebut topologi...",
                array("Star (Bintang)", "Bus", "Ring (Cincin)", "Mesh", "Tree")
            ),
            array(
                "Perangkat keras jaringan komputer yang berfungsi memilih rute lalu lintas data terbaik dan menghubungkan subnet berbeda adalah...",
                array("Router", "Hub", "Repeater", "Modem USB", "NIC")
            ),
            array(
                "Tarian tradisional khas dari Nusa Tenggara Timur yang diiringi musik gong gendang serta melambangkan kebersamaan dan persatuan adalah...",
                array("Tari Ja'i", "Tari Saman", "Tari Pendet", "Tari Piring", "Tari Kecak")
            ),
            array(
                "Sistem operasi komputer open-source yang banyak digunakan sebagai server web dan infrastruktur jaringan adalah...",
                array("Linux", "Microsoft Windows 11", "macOS Sonoma", "MS-DOS", "iOS")
            ),
            array(
                "Kabel jaringan UTP (Unshielded Twisted Pair) kategori 5e/6 menggunakan konektor standar bertipe...",
                array("RJ-45", "RJ-11", "BNC", "Fiber SC", "USB Type-C")
            ),
            array(
                "Fungsi utama dari protokol DHCP (Dynamic Host Configuration Protocol) dalam jaringan komputer adalah...",
                array("Memberikan konfigurasi alamat IP secara otomatis kepada perangkat klien", "Menerjemahkan nama domain web ke alamat IP", "Mengamankan transmisi file transfer", "Mengirimkan email secara terjadwal", "Memindai virus komputer")
            ),
            array(
                "Layanan internet yang bertugas menerjemahkan alamat domain seperti bikinaplikasi.my.id menjadi alamat IP adalah...",
                array("DNS (Domain Name System)", "DHCP", "FTP", "SMTP", "SNMP")
            ),
            array(
                "Nilai dari akar 144 ditambah akar 81 adalah...",
                array("21", "19", "25", "23", "18")
            ),
            array(
                "Sistem bilangan heksadesimal merupakan sistem bilangan yang berbasis angka...",
                array("16", "2", "8", "10", "32")
            ),
            array(
                "Perintah pada baris perintah (CLI) yang digunakan untuk menguji latensi dan keterhubungan koneksi antar perangkat jaringan adalah...",
                array("ping", "dir", "format", "mkdir", "chkdsk")
            ),
            array(
                "Alat Pelindung Diri (APD) utama yang wajib dikenakan saat melakukan instalasi kelistrikan di bengkel praktik teknik adalah...",
                array("Kacamata pelindung dan sarung tangan isolasi berstandar K3", "Jas hujan plastik", "Sandal jepit santai", "Topi kain biasa", "Dasi panjang")
            ),
            array(
                "Perintah SQL (Structured Query Language) yang digunakan untuk mengambil dan menampilkan data dari tabel database adalah...",
                array("SELECT", "INSERT", "UPDATE", "DELETE", "DROP")
            ),
            array(
                "Kepanjangan dari akronim CBT dalam sistem evaluasi asesmen modern di sekolah adalah...",
                array("Computer Based Test", "Central Board Test", "Computer Basic Tool", "Control Board Terminal", "Core Based Technology")
            ),
            array(
                "Pada segitiga siku-siku dengan panjang sisi tegak a = 6 cm dan b = 8 cm, panjang sisi miringnya (c) adalah...",
                array("10 cm", "12 cm", "14 cm", "9 cm", "11 cm")
            ),
            array(
                "Proses menerjemahkan seluruh baris kode sumber program komputer menjadi bahasa mesin sebelum dijalankan disebut...",
                array("Kompilasi (Compilation)", "Defragmentasi", "Kompresi data", "Enkripsi sandi", "Formatting")
            ),
            array(
                "Komponen elektronika pasif yang berfungsi menyimpan energi muatan listrik dalam jangka waktu tertentu adalah...",
                array("Kapasitor (Kondensator)", "Resistor", "Dioda", "Transistor", "Induktor")
            )
        );

        foreach($soal_pg as $sp){
            $cek_sp = $this->db->get_where('cbt_soal', array('soal_topik_id' => $topik_pg_id, 'soal_detail' => $sp[0]));
            if($cek_sp->num_rows() == 0){
                $this->db->insert('cbt_soal', array(
                    'soal_topik_id' => $topik_pg_id,
                    'soal_detail' => $sp[0],
                    'soal_tipe' => 1,
                    'soal_difficulty' => 1,
                    'soal_aktif' => 1
                ));
                $sp_id = $this->db->insert_id();
                // Opsi A (Benar), B, C, D, E (Salah)
                foreach($sp[1] as $idx => $opsi){
                    $this->db->insert('cbt_jawaban', array(
                        'jawaban_soal_id' => $sp_id,
                        'jawaban_detail' => $opsi,
                        'jawaban_benar' => ($idx == 0 ? 1 : 0),
                        'jawaban_aktif' => 1
                    ));
                }
            }
        }

        // 5. Masukkan 2 Soal Isian Singkat (Tipe 3)
        $soal_isian = array(
            array("Nama Desa tempat kampus definitif SMK Negeri 1 Kupang Barat berada adalah Desa... (Tuliskan nama desa dalam huruf kapital)", "KUANHEUN"),
            array("Berapakah hasil dari 20 x 5 ? (Tuliskan dalam angka bulat)", "100")
        );

        foreach($soal_isian as $si){
            $cek_si = $this->db->get_where('cbt_soal', array('soal_topik_id' => $topik_is_id, 'soal_detail' => $si[0]));
            if($cek_si->num_rows() == 0){
                $this->db->insert('cbt_soal', array(
                    'soal_topik_id' => $topik_is_id,
                    'soal_detail' => $si[0],
                    'soal_tipe' => 3,
                    'soal_kunci' => $si[1],
                    'soal_difficulty' => 1,
                    'soal_aktif' => 1
                ));
            }
        }

        // 6. Masukkan 10 Butir Soal Uraian / Esai (Tipe 2)
        $soal_uraian = array(
            "Jelaskan pentingnya penerapan Keselamatan dan Kesehatan Kerja (K3) bagi siswa di laboratorium komputer dan bengkel praktik SMK!",
            "Uraikan fungsi utama sistem operasi komputer dalam menjembatani pengguna (user), perangkat lunak aplikasi, dan perangkat keras (hardware)!",
            "Sebutkan dan jelaskan perbedaan mendasar antara jaringan lokal (LAN) dengan jaringan area luas (WAN)!",
            "Jelaskan langkah-langkah yang harus dilakukan jika sebuah komputer klien tidak mendapatkan alamat IP otomatis dari server DHCP!",
            "Tuliskan gagasan atau inovasi Anda untuk memajukan pendidikan kejuruan dan potensi daerah di SMK Negeri 1 Kupang Barat!",
            "Jelaskan apa yang dimaksud dengan integritas akademik dalam pelaksanaan ujian berbasis komputer (CBT)!",
            "Uraikan keuntungan menggunakan media penyimpanan SSD (Solid State Drive) dibandingkan HDD (Hard Disk Drive) konvensional!",
            "Jelaskan konsep dasar keamanan jaringan komputer (Network Security) untuk mencegah akses tidak sah dari pihak luar!",
            "Sebutkan 3 macam perangkat masukan (input device) dan 3 macam perangkat keluaran (output device) pada komputer beserta kegunaannya!",
            "Jelaskan peran pendidikan vokasi (SMK) dalam mempersiapkan lulusan yang siap kerja, berkarakter, dan berdaya saing global!"
        );

        foreach($soal_uraian as $su){
            $cek_su = $this->db->get_where('cbt_soal', array('soal_topik_id' => $topik_ur_id, 'soal_detail' => $su));
            if($cek_su->num_rows() == 0){
                $this->db->insert('cbt_soal', array(
                    'soal_topik_id' => $topik_ur_id,
                    'soal_detail' => $su,
                    'soal_tipe' => 2,
                    'soal_difficulty' => 1,
                    'soal_aktif' => 1
                ));
            }
        }

        // 7. Buat Ujian 1: SIMULASI ASESMEN 25 SOAL (SEMUA TIPE SOAL)
        $this->db->insert('cbt_tes', array(
            'tes_nama' => 'SIMULASI ASESMEN 25 SOAL (SEMUA TIPE SOAL)',
            'tes_detail' => 'Simulasi Asesmen Standar TKA SMKN 1 Kupang Barat (25 Soal Lengkap: 5 Benar/Salah @ 4 poin [tot 20], 13 PG + 2 Isian @ 2 poin [tot 30], 5 Uraian @ 10 poin [tot 50]. Total Skor Benar Semua = 100).',
            'tes_begin_time' => '2025-01-01 00:00:00',
            'tes_end_time' => '2030-12-31 23:59:59',
            'tes_duration_time' => 60,
            'tes_ip_range' => '*.*.*.*',
            'tes_results_to_users' => 1,
            'tes_detail_to_users' => 1,
            'tes_score_right' => 2.00,
            'tes_score_wrong' => 0.00,
            'tes_score_unanswered' => 0.00,
            'tes_max_score' => 100.00,
            'tes_token' => 1
        ));
        $tes25_id = $this->db->insert_id();

        // Hubungkan Topik ke Tes 25 Soal:
        // 5 Benar/Salah (tipe 4)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes25_id,
            'tset_topik_id' => $topik_bs_id,
            'tset_tipe' => 4,
            'tset_difficulty' => 1,
            'tset_jumlah' => 5,
            'tset_jawaban' => 2,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));
        // 13 Pilihan Ganda (tipe 1)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes25_id,
            'tset_topik_id' => $topik_pg_id,
            'tset_tipe' => 1,
            'tset_difficulty' => 1,
            'tset_jumlah' => 13,
            'tset_jawaban' => 5,
            'tset_acak_jawaban' => 1,
            'tset_acak_soal' => 1
        ));
        // 2 Isian Singkat (tipe 3)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes25_id,
            'tset_topik_id' => $topik_is_id,
            'tset_tipe' => 3,
            'tset_difficulty' => 1,
            'tset_jumlah' => 2,
            'tset_jawaban' => 0,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));
        // 5 Uraian / Esai (tipe 2)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes25_id,
            'tset_topik_id' => $topik_ur_id,
            'tset_tipe' => 2,
            'tset_difficulty' => 1,
            'tset_jumlah' => 5,
            'tset_jawaban' => 0,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));

        // 8. Buat Ujian 2: SIMULASI ASESMEN 40 SOAL (STANDAR TKA)
        $this->db->insert('cbt_tes', array(
            'tes_nama' => 'SIMULASI ASESMEN 40 SOAL (STANDAR TKA)',
            'tes_detail' => 'Simulasi Asesmen Standar TKA SMKN 1 Kupang Barat (40 Soal: 10 Benar/Salah @ 1 poin [tot 10], 18 PG + 2 Isian @ 2 poin [tot 40], 10 Uraian @ 5 poin [tot 50]. Total Skor Benar Semua = 100).',
            'tes_begin_time' => '2025-01-01 00:00:00',
            'tes_end_time' => '2030-12-31 23:59:59',
            'tes_duration_time' => 90,
            'tes_ip_range' => '*.*.*.*',
            'tes_results_to_users' => 1,
            'tes_detail_to_users' => 1,
            'tes_score_right' => 2.00,
            'tes_score_wrong' => 0.00,
            'tes_score_unanswered' => 0.00,
            'tes_max_score' => 100.00,
            'tes_token' => 1
        ));
        $tes40_id = $this->db->insert_id();

        // Hubungkan Topik ke Tes 40 Soal:
        // 10 Benar/Salah (tipe 4)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes40_id,
            'tset_topik_id' => $topik_bs_id,
            'tset_tipe' => 4,
            'tset_difficulty' => 1,
            'tset_jumlah' => 10,
            'tset_jawaban' => 2,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));
        // 18 Pilihan Ganda (tipe 1)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes40_id,
            'tset_topik_id' => $topik_pg_id,
            'tset_tipe' => 1,
            'tset_difficulty' => 1,
            'tset_jumlah' => 18,
            'tset_jawaban' => 5,
            'tset_acak_jawaban' => 1,
            'tset_acak_soal' => 1
        ));
        // 2 Isian Singkat (tipe 3)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes40_id,
            'tset_topik_id' => $topik_is_id,
            'tset_tipe' => 3,
            'tset_difficulty' => 1,
            'tset_jumlah' => 2,
            'tset_jawaban' => 0,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));
        // 10 Uraian / Esai (tipe 2)
        $this->db->insert('cbt_tes_topik_set', array(
            'tset_tes_id' => $tes40_id,
            'tset_topik_id' => $topik_ur_id,
            'tset_tipe' => 2,
            'tset_difficulty' => 1,
            'tset_jumlah' => 10,
            'tset_jawaban' => 0,
            'tset_acak_jawaban' => 0,
            'tset_acak_soal' => 1
        ));

        // 9. Pastikan Token dan Siswa terdaftar & terhubung
        $this->ensure_token_and_user();
    }
}
