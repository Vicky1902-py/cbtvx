<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_analisis_soal extends Member_Controller {
	private $kode_menu = 'laporan-analisis-soal';
	private $kelompok = 'laporan';
	private $url = 'manager/laporan_analisis_soal';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_model');
		$this->load->model('cbt_tes_token_model');
		$this->load->model('cbt_tes_topik_set_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->model('cbt_soal_model');
		$this->load->model('cbt_jawaban_model');
		$this->load->model('cbt_tes_soal_model');
		$this->load->model('cbt_tes_soal_jawaban_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
		// Mengarahkan ke Analisis Butir Soal terpadu
		redirect('manager/laporan_analisis_butir_soal');
    }
}
