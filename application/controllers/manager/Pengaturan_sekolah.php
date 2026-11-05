<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Data Master & Pengaturan Sekolah SMKN 1 Kupang Barat
 * Mengatur Identitas Sekolah, Kepala Sekolah, Logo Pemprov, Logo Sekolah, TTD Kepsek, Kop Kartu, dan Slideshow
 * Vicky Koroh
 */
class Pengaturan_sekolah extends Member_Controller {
	private $kode_menu = 'pengaturan-sekolah';
	private $kelompok = 'pengaturan';
	private $url = 'manager/pengaturan_sekolah';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_konfigurasi_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
        
        $this->template->display_admin($this->kelompok.'/pengaturan_zyacbt_view', 'Data Master Sekolah', $data);
    }

	private function update_or_insert_config($kode, $isi, $keterangan = ''){
		$q = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', $kode, 1);
		if($q->num_rows() > 0){
			$this->cbt_konfigurasi_model->update('konfigurasi_kode', $kode, array('konfigurasi_isi' => $isi));
		}else{
			$this->cbt_konfigurasi_model->save(array(
				'konfigurasi_kode' => $kode,
				'konfigurasi_isi' => $isi,
				'konfigurasi_keterangan' => $keterangan
			));
		}
	}

	private function get_config_val($kode, $default = ''){
		$q = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', $kode, 1);
		if($q->num_rows() > 0){
			$v = trim($q->row()->konfigurasi_isi);
			if(!empty($v)) return $v;
		}
		return $default;
	}

    function simpan(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('zyacbt-nama', 'Nama Sekolah / CBT','required|strip_tags');
        $this->form_validation->set_rules('zyacbt-keterangan', 'Keterangan CBT','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-link-login', 'Link Login Operator','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-mobile-lock-xambro', 'Lock Mobile Exam Browser','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-informasi', 'Informasi Peserta Tes','required');
        
        if($this->form_validation->run() == TRUE){
			// Identitas Utama
			$nama_sekolah = $this->input->post('zyacbt-nama', true);
			$this->update_or_insert_config('cbt_nama', $nama_sekolah, 'Nama Penyelenggara CBT');
			$this->update_or_insert_config('sekolah_nama', $nama_sekolah, 'Nama Resmi Sekolah');
			$this->update_or_insert_config('cbt_keterangan', $this->input->post('zyacbt-keterangan', true), 'Keterangan Penyelenggara CBT');
			$this->update_or_insert_config('link_login_operator', $this->input->post('zyacbt-link-login', true), 'Menampilkan Link Login Operator');
			$this->update_or_insert_config('cbt_mobile_lock_xambro', $this->input->post('zyacbt-mobile-lock-xambro', true), 'Lock Mobile Browser');
			$this->update_or_insert_config('cbt_informasi', $this->input->post('zyacbt-informasi', false), 'Informasi Dashboard Peserta');

			// Pengaturan Sekolah & Kop Surat
			$this->update_or_insert_config('sekolah_provinsi', $this->input->post('sekolah_provinsi', true), 'Pemerintah Provinsi');
			$this->update_or_insert_config('sekolah_dinas', $this->input->post('sekolah_dinas', true), 'Dinas Pendidikan');
			$this->update_or_insert_config('sekolah_alamat', $this->input->post('sekolah_alamat', true), 'Alamat Sekolah');
			$this->update_or_insert_config('sekolah_kecamatan', $this->input->post('sekolah_kecamatan', true), 'Kecamatan');
			$this->update_or_insert_config('sekolah_kota', $this->input->post('sekolah_kota', true), 'Kabupaten / Kota');
			$this->update_or_insert_config('sekolah_titimangsa', $this->input->post('sekolah_titimangsa', true), 'Tempat Titimangsa Kartu');
			$this->update_or_insert_config('sekolah_telepon', $this->input->post('sekolah_telepon', true), 'Nomor Telepon');
			$this->update_or_insert_config('sekolah_email', $this->input->post('sekolah_email', true), 'Email Sekolah');
			$this->update_or_insert_config('sekolah_website', $this->input->post('sekolah_website', true), 'Website Sekolah');
			$this->update_or_insert_config('sekolah_kepsek_nama', $this->input->post('sekolah_kepsek_nama', true), 'Nama Kepala Sekolah');
			$this->update_or_insert_config('sekolah_kepsek_nip', $this->input->post('sekolah_kepsek_nip', true), 'NIP Kepala Sekolah');

			// File Upload Helper
			$this->load->library('upload');

			// Upload Logo Pemprov NTT
			if(!empty($_FILES['logo_provinsi_file']['name'])){
				$conf_prov['upload_path'] = './public/images/';
				$conf_prov['allowed_types'] = 'png|jpg|jpeg|svg';
				$conf_prov['max_size'] = 3072;
				$conf_prov['file_name'] = 'logo_pemprov_custom_'.time();
				$conf_prov['overwrite'] = TRUE;
				$this->upload->initialize($conf_prov);
				if($this->upload->do_upload('logo_provinsi_file')){
					$up = $this->upload->data();
					$this->update_or_insert_config('sekolah_logo_provinsi', base_url('public/images/'.$up['file_name']), 'Logo Pemprov Kop');
				}
			}

			// Upload Logo Sekolah
			if(!empty($_FILES['logo_sekolah_file']['name'])){
				$conf_sek['upload_path'] = './public/images/';
				$conf_sek['allowed_types'] = 'png|jpg|jpeg';
				$conf_sek['max_size'] = 3072;
				$conf_sek['file_name'] = 'logo_sekolah_custom_'.time();
				$conf_sek['overwrite'] = TRUE;
				$this->upload->initialize($conf_sek);
				if($this->upload->do_upload('logo_sekolah_file')){
					$up = $this->upload->data();
					$this->update_or_insert_config('sekolah_logo_sekolah', base_url('public/images/'.$up['file_name']), 'Logo Sekolah Kop');
				}
			}

			// Upload TTD & Stempel Kepala Sekolah
			if(!empty($_FILES['ttd_kepsek_file']['name'])){
				$conf_ttd['upload_path'] = './public/images/';
				$conf_ttd['allowed_types'] = 'png|jpg|jpeg';
				$conf_ttd['max_size'] = 3072;
				$conf_ttd['file_name'] = 'ttd_kepsek_custom_'.time();
				$conf_ttd['overwrite'] = TRUE;
				$this->upload->initialize($conf_ttd);
				if($this->upload->do_upload('ttd_kepsek_file')){
					$up = $this->upload->data();
					$this->update_or_insert_config('sekolah_ttd_kepsek', base_url('public/images/'.$up['file_name']), 'TTD dan Stempel Kepala Sekolah');
				}
			}

			// Simpan Pengaturan Slideshow
			if(!is_dir('./public/images/slides/')){
				@mkdir('./public/images/slides/', 0777, true);
			}

			for($i = 1; $i <= 3; $i++){
				$file_key = 'slide'.$i.'_file';
				if(!empty($_FILES[$file_key]['name'])){
					$config_up['upload_path'] = './public/images/slides/';
					$config_up['allowed_types'] = 'jpg|jpeg|png|webp';
					$config_up['max_size'] = 5120; // 5MB
					$config_up['file_name'] = 'slide_'.$i.'_'.time();
					$config_up['overwrite'] = TRUE;

					$this->upload->initialize($config_up);
					if($this->upload->do_upload($file_key)){
						$up_data = $this->upload->data();
						$this->update_or_insert_config('slide'.$i.'_gambar', base_url('public/images/slides/'.$up_data['file_name']), 'Gambar Slide '.$i);
					}
				}else{
					$url_img = $this->input->post('slide'.$i.'_gambar', true);
					if(!empty($url_img)){
						$this->update_or_insert_config('slide'.$i.'_gambar', $url_img, 'Gambar Slide '.$i);
					}
				}

				$judul = $this->input->post('slide'.$i.'_judul', true);
				if($judul !== null){
					$this->update_or_insert_config('slide'.$i.'_judul', $judul, 'Judul Slide '.$i);
				}

				$deskripsi = $this->input->post('slide'.$i.'_deskripsi', true);
				if($deskripsi !== null){
					$this->update_or_insert_config('slide'.$i.'_deskripsi', $deskripsi, 'Deskripsi Slide '.$i);
				}
			}

            $status['status'] = 1;
			$status['pesan'] = 'Data Master Sekolah, Kepala Sekolah, dan Kop Resmi berhasil disimpan!';
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }
    
    function get_pengaturan_zyacbt(){
    	$data['data'] = 1;
		$data['link_login_operator'] = $this->get_config_val('link_login_operator', 'ya');
		$data['cbt_nama'] = $this->get_config_val('cbt_nama', 'SMK Negeri 1 Kupang Barat');
		$data['cbt_keterangan'] = $this->get_config_val('cbt_keterangan', 'Ujian Berbasis Komputer Standar TKA Pusmendik');
		$data['cbt_informasi'] = $this->get_config_val('cbt_informasi', 'Silahkan pilih Tes yang diikuti dari daftar tes yang tersedia dibawah ini. Apabila tes tidak muncul, silahkan menghubungi Operator yang bertugas.');
		$data['mobile_lock_xambro'] = $this->get_config_val('cbt_mobile_lock_xambro', 'tidak');

		// Sekolah & Kop
		$data['sekolah_provinsi'] = $this->get_config_val('sekolah_provinsi', 'PEMERINTAH PROVINSI NUSA TENGGARA TIMUR');
		$data['sekolah_dinas'] = $this->get_config_val('sekolah_dinas', 'DINAS PENDIDIKAN DAN KEBUDAYAAN');
		$data['sekolah_nama'] = $this->get_config_val('sekolah_nama', 'SMK NEGERI 1 KUPANG BARAT');
		$data['sekolah_alamat'] = $this->get_config_val('sekolah_alamat', 'Jln. Jurusan Batakte - Kuanheun, Desa Kuanheun');
		$data['sekolah_kecamatan'] = $this->get_config_val('sekolah_kecamatan', 'Kec. Kupang Barat');
		$data['sekolah_kota'] = $this->get_config_val('sekolah_kota', 'Kab. Kupang');
		$data['sekolah_titimangsa'] = $this->get_config_val('sekolah_titimangsa', 'Kuanheun');
		$data['sekolah_telepon'] = $this->get_config_val('sekolah_telepon', '-');
		$data['sekolah_email'] = $this->get_config_val('sekolah_email', 'smkn1kupangbarat@gmail.com');
		$data['sekolah_website'] = $this->get_config_val('sekolah_website', 'https://bikinaplikasi.my.id');
		$data['sekolah_kepsek_nama'] = $this->get_config_val('sekolah_kepsek_nama', 'Vicky Koroh, S.Pd., M.Kom');
		$data['sekolah_kepsek_nip'] = $this->get_config_val('sekolah_kepsek_nip', '-');
		$data['sekolah_logo_provinsi'] = $this->get_config_val('sekolah_logo_provinsi', base_url('public/images/logo_pemprov_ntt.svg'));
		$data['sekolah_logo_sekolah'] = $this->get_config_val('sekolah_logo_sekolah', base_url('public/images/logo_smk.png'));
		$data['sekolah_ttd_kepsek'] = $this->get_config_val('sekolah_ttd_kepsek', base_url('public/images/ttd_kepsek.png'));

		// Slideshow data
		$data['slide1_gambar'] = $this->get_config_val('slide1_gambar', base_url('public/images/slides/slide1.jpg'));
		$data['slide1_judul'] = $this->get_config_val('slide1_judul', 'Sistem Asesmen Berbasis Komputer');
		$data['slide1_deskripsi'] = $this->get_config_val('slide1_deskripsi', 'Pelaksanaan ujian terstandar TKA Pusmendik Kemdikbud di SMK Negeri 1 Kupang Barat yang aman, presisi, dan terpercaya.');

		$data['slide2_gambar'] = $this->get_config_val('slide2_gambar', base_url('public/images/slides/slide2.jpg'));
		$data['slide2_judul'] = $this->get_config_val('slide2_judul', 'Integritas & Prestasi Kejuruan');
		$data['slide2_deskripsi'] = $this->get_config_val('slide2_deskripsi', 'Mendukung tipe soal Pilihan Ganda, Benar/Salah, Uraian, serta formula matematika KaTeX offline dan gambar berkualitas.');

		$data['slide3_gambar'] = $this->get_config_val('slide3_gambar', base_url('public/images/slides/slide3.jpg'));
		$data['slide3_judul'] = $this->get_config_val('slide3_judul', 'SMKN 1 Kupang Barat - Kuanheun');
		$data['slide3_deskripsi'] = $this->get_config_val('slide3_deskripsi', 'Maju bersama menuju pendidikan vokasi unggul, kompeten, berkarakter, dan berdaya saing global di Kuanheun, Kupang Barat.');
		
		echo json_encode($data);
    }
}
