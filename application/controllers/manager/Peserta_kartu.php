<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_kartu extends Member_Controller {
	private $kode_menu = 'peserta-kartu';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_kartu';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_konfigurasi_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
		$data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
		
		$query_group = $this->cbt_user_grup_model->get_group();
        $select = '<option value="semua">Semua Group / Kelas</option>';

        if($query_group->num_rows() > 0){
        	foreach ($query_group->result() as $temp) {
        		$select .= '<option value="'.$temp->grup_id.'">'.$temp->grup_nama.'</option>';
        	}
        }
        $data['select_group'] = $select;
		$data['sekolah'] = $this->template->get_sekolah_info();
		
        $this->template->display_admin($this->kelompok.'/peserta_kartu_view', 'Cetak Kartu Peserta', $data);
    }

	/**
	* Cetak kartu login peserta dengan kop resmi sekolah, logo pemprov, logo sekolah, foto siswa, dan ttd kepsek
	*/
    public function cetak_kartu($grup_id = null){
		$data['kode_menu'] = $this->kode_menu;
		$data['sekolah'] = $this->template->get_sekolah_info();
		
		$group_name = 'Semua Group / Kelas';
		if(!empty($grup_id) && $grup_id != 'semua'){
			$query_group = $this->cbt_user_grup_model->get_by_kolom_limit('grup_id', $grup_id, 1);
			if($query_group->num_rows() > 0){
				$group_name = $query_group->row()->grup_nama;
			}
			$query_user = $this->cbt_user_model->get_by_kolom('user_grup_id', $grup_id);
		}else{
			$query_user = $this->cbt_user_model->get_all();
		}
		
		$data['group_nama'] = $group_name;
		$data['users'] = ($query_user->num_rows() > 0) ? $query_user->result() : array();

		// Helper map untuk nama grup jika cetak semua
		$grup_map = array();
		$all_groups = $this->cbt_user_grup_model->get_group();
		if($all_groups->num_rows() > 0){
			foreach($all_groups->result() as $g){
				$grup_map[$g->grup_id] = $g->grup_nama;
			}
		}
		$data['grup_map'] = $grup_map;
		
		$this->load->view($this->kelompok.'/peserta_cetak_kartu_view', $data);
	}
}
