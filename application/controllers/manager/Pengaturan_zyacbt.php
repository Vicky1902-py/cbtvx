<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengaturan_zyacbt extends Member_Controller {
	private $kode_menu = 'user-zyacbt';
	private $kelompok = 'pengaturan';
	private $url = 'manager/pengaturan_zyacbt';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_konfigurasi_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
        
        $this->template->display_admin($this->kelompok.'/pengaturan_zyacbt_view', 'Pengaturan CBT & Slideshow', $data);
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
        
        $this->form_validation->set_rules('zyacbt-nama', 'Nama CBT','required|strip_tags');
        $this->form_validation->set_rules('zyacbt-keterangan', 'Keterangan CBT','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-link-login', 'Link Login Operator','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-mobile-lock-xambro', 'Lock Mobile Exam Browser','required|strip_tags');
		$this->form_validation->set_rules('zyacbt-informasi', 'Informasi Peserta Tes','required');
        
        if($this->form_validation->run() == TRUE){
			$this->update_or_insert_config('cbt_nama', $this->input->post('zyacbt-nama', true), 'Nama Penyelenggara CBT');
			$this->update_or_insert_config('cbt_keterangan', $this->input->post('zyacbt-keterangan', true), 'Keterangan Penyelenggara CBT');
			$this->update_or_insert_config('link_login_operator', $this->input->post('zyacbt-link-login', true), 'Menampilkan Link Login Operator');
			$this->update_or_insert_config('cbt_mobile_lock_xambro', $this->input->post('zyacbt-mobile-lock-xambro', true), 'Lock Mobile Browser');
			$this->update_or_insert_config('cbt_informasi', $this->input->post('zyacbt-informasi', false), 'Informasi Dashboard Peserta');

			// Simpan Pengaturan Slideshow
			if(!is_dir('./public/images/slides/')){
				@mkdir('./public/images/slides/', 0777, true);
			}

			$this->load->library('upload');

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
			$status['pesan'] = 'Pengaturan CBT dan Slideshow berhasil disimpan!';
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

		// Slideshow data
		$data['slide1_gambar'] = $this->get_config_val('slide1_gambar', base_url('public/images/slides/slide1.jpg'));
		$data['slide1_judul'] = $this->get_config_val('slide1_judul', 'Sistem Asesmen Berbasis Komputer');
		$data['slide1_deskripsi'] = $this->get_config_val('slide1_deskripsi', 'Pelaksanaan ujian terstandar TKA Pusmendik Kemdikbud di SMK Negeri 1 Kupang Barat yang aman, presisi, dan terpercaya.');

		$data['slide2_gambar'] = $this->get_config_val('slide2_gambar', base_url('public/images/slides/slide2.jpg'));
		$data['slide2_judul'] = $this->get_config_val('slide2_judul', 'Integritas & Prestasi Kejuruan');
		$data['slide2_deskripsi'] = $this->get_config_val('slide2_deskripsi', 'Mendukung tipe soal Pilihan Ganda, Benar/Salah, Uraian, serta formula matematika KaTeX offline dan gambar berkualitas.');

		$data['slide3_gambar'] = $this->get_config_val('slide3_gambar', base_url('public/images/slides/slide3.jpg'));
		$data['slide3_judul'] = $this->get_config_val('slide3_judul', 'SMKN 1 Kupang Barat - Nekamese');
		$data['slide3_deskripsi'] = $this->get_config_val('slide3_deskripsi', 'Maju bersama menuju pendidikan vokasi unggul, kompeten, berkarakter, dan berdaya saing global.');
		
		echo json_encode($data);
    }
}
