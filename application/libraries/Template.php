<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Template{
	protected $_ci;
	
	function __construct(){ 
		$this->_ci=&get_instance();
		$this->_ci->load->library('access');
		$this->_ci->load->model('users_model');
		$this->_ci->load->model('cbt_konfigurasi_model');
	}

	private function get_config_val($kode, $default = ''){
		$q = $this->_ci->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', $kode, 1);
		if($q->num_rows() > 0){
			$v = trim($q->row()->konfigurasi_isi);
			if(!empty($v)) return $v;
		}
		return $default;
	}
	
	function display_admin($template, $title, $data=null){
		if(empty($data['kode_menu'])){
			$data['kode_menu'] = 'KOSONG'; 
		}
		$data['site_name'] = $this->get_config_val('cbt_nama', $this->_ci->config->item('site_name'));
		$data['site_version'] = $this->_ci->config->item('site_version');
		$data['nama'] = $this->_ci->access->get_nama();
		$data['sidemenu'] = $this->_ci->users_model->get_menu($data['kode_menu'], $this->_ci->access->get_level());
		$data['content'] = $this->_ci->load->view($template,$data,true);
		$data['title'] = $title;
		$this->_ci->load->view('template/template_admin.php',$data);
	}
	
	function display_user($template, $title, $data=null){
		if(!is_array($data)) $data = array();
		$data['site_name'] = $this->get_config_val('cbt_nama', $this->_ci->config->item('site_name'));
		$data['site_version'] = $this->_ci->config->item('site_version');

		// Slideshow Beranda & Login (Dapat diatur dari Panel Admin)
		$data['slides'] = array(
			array(
				'gambar' => $this->get_config_val('slide1_gambar', base_url('public/images/slides/slide1.jpg')),
				'judul' => $this->get_config_val('slide1_judul', 'Sistem Asesmen Berbasis Komputer'),
				'deskripsi' => $this->get_config_val('slide1_deskripsi', 'Pelaksanaan ujian terstandar TKA Pusmendik Kemdikbud di SMK Negeri 1 Kupang Barat yang aman, presisi, dan terpercaya.')
			),
			array(
				'gambar' => $this->get_config_val('slide2_gambar', base_url('public/images/slides/slide2.jpg')),
				'judul' => $this->get_config_val('slide2_judul', 'Integritas & Prestasi Kejuruan'),
				'deskripsi' => $this->get_config_val('slide2_deskripsi', 'Mendukung tipe soal Pilihan Ganda, Benar/Salah, Uraian, serta formula matematika KaTeX offline dan gambar berkualitas.')
			),
			array(
				'gambar' => $this->get_config_val('slide3_gambar', base_url('public/images/slides/slide3.jpg')),
				'judul' => $this->get_config_val('slide3_judul', 'SMKN 1 Kupang Barat - Nekamese'),
				'deskripsi' => $this->get_config_val('slide3_deskripsi', 'Maju bersama menuju pendidikan vokasi unggul, kompeten, berkarakter, dan berdaya saing global.')
			)
		);

		$data['content'] = $this->_ci->load->view($template,$data,true);
		$data['title'] = $title;
		$this->_ci->load->view('template/template_user.php',$data);
	}

	function display_tes($template, $title, $data=null){
		if(!is_array($data)) $data = array();
		$data['site_name'] = $this->get_config_val('cbt_nama', $this->_ci->config->item('site_name'));
		$data['site_version'] = $this->_ci->config->item('site_version');
		$data['content'] = $this->_ci->load->view($template,$data,true);
		$data['title'] = $title;
		$this->_ci->load->view('template/template_tes.php',$data);
	}
	
	function display_clean($template, $data=null){
		$data['_content'] = $this->_ci->load->view($template,$data,true);
		$this->_ci->load->view('template/template_clean.php',$data);
	}
} 
?>
