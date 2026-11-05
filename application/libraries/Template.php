<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Template{
	protected $_ci;
	
	function __construct(){ 
		$this->_ci=&get_instance();
		$this->_ci->load->library('access');
		$this->_ci->load->model('users_model');
		$this->_ci->load->model('cbt_konfigurasi_model');
	}

	public function get_config_val($kode, $default = ''){
		$q = $this->_ci->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', $kode, 1);
		if($q->num_rows() > 0){
			$v = trim($q->row()->konfigurasi_isi);
			if(!empty($v)) return $v;
		}
		return $default;
	}

	public function get_sekolah_info(){
		return array(
			'nama' => $this->get_config_val('sekolah_nama', $this->get_config_val('cbt_nama', 'SMK NEGERI 1 KUPANG BARAT')),
			'provinsi' => $this->get_config_val('sekolah_provinsi', 'PEMERINTAH PROVINSI NUSA TENGGARA TIMUR'),
			'dinas' => $this->get_config_val('sekolah_dinas', 'DINAS PENDIDIKAN DAN KEBUDAYAAN'),
			'alamat' => $this->get_config_val('sekolah_alamat', 'Jln. Jurusan Batakte - Kuanheun, Desa Kuanheun'),
			'kecamatan' => $this->get_config_val('sekolah_kecamatan', 'Kec. Kupang Barat'),
			'kota' => $this->get_config_val('sekolah_kota', 'Kab. Kupang'),
			'titimangsa' => $this->get_config_val('sekolah_titimangsa', 'Kuanheun'),
			'telepon' => $this->get_config_val('sekolah_telepon', '-'),
			'email' => $this->get_config_val('sekolah_email', 'smkn1kupangbarat@gmail.com'),
			'website' => $this->get_config_val('sekolah_website', 'https://bikinaplikasi.my.id'),
			'kepsek_nama' => $this->get_config_val('sekolah_kepsek_nama', 'Vicky Koroh, S.Pd., M.Kom'),
			'kepsek_nip' => $this->get_config_val('sekolah_kepsek_nip', '-'),
			'logo_provinsi' => $this->get_config_val('sekolah_logo_provinsi', base_url('public/images/logo_pemprov_ntt.svg')),
			'logo_sekolah' => $this->get_config_val('sekolah_logo_sekolah', base_url('public/images/logo_smk.png')),
			'ttd_kepsek' => $this->get_config_val('sekolah_ttd_kepsek', base_url('public/images/ttd_kepsek.png'))
		);
	}
	
	function display_admin($template, $title, $data=null){
		if(!is_array($data)) $data = array();
		if(empty($data['kode_menu'])){
			$data['kode_menu'] = 'KOSONG'; 
		}
		$data['site_name'] = $this->get_config_val('cbt_nama', $this->_ci->config->item('site_name'));
		$data['site_version'] = $this->_ci->config->item('site_version');
		$data['nama'] = $this->_ci->access->get_nama();
		$data['sidemenu'] = $this->_ci->users_model->get_menu($data['kode_menu'], $this->_ci->access->get_level());
		$data['sekolah'] = $this->get_sekolah_info();
		$data['content'] = $this->_ci->load->view($template,$data,true);
		$data['title'] = $title;
		$this->_ci->load->view('template/template_admin.php',$data);
	}
	
	function display_user($template, $title, $data=null){
		if(!is_array($data)) $data = array();
		$data['site_name'] = $this->get_config_val('cbt_nama', $this->_ci->config->item('site_name'));
		$data['site_version'] = $this->_ci->config->item('site_version');
		$data['sekolah'] = $this->get_sekolah_info();

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
				'judul' => $this->get_config_val('slide3_judul', 'SMKN 1 Kupang Barat - Kuanheun'),
				'deskripsi' => $this->get_config_val('slide3_deskripsi', 'Maju bersama menuju pendidikan vokasi unggul, kompeten, berkarakter, dan berdaya saing global di Kuanheun, Kupang Barat.')
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
		$data['sekolah'] = $this->get_sekolah_info();
		$data['content'] = $this->_ci->load->view($template,$data,true);
		$data['title'] = $title;
		$this->_ci->load->view('template/template_tes.php',$data);
	}
	
	function display_clean($template, $data=null){
		if(!is_array($data)) $data = array();
		$data['sekolah'] = $this->get_sekolah_info();
		$data['_content'] = $this->_ci->load->view($template,$data,true);
		$this->_ci->load->view('template/template_clean.php',$data);
	}
} 
?>