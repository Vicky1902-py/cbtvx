<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ZYA CBT - Modul Status Online Peserta
 * SMK Negeri 1 Kupang Barat
 * Vicky Koroh
 */
class Peserta_online extends Member_Controller {
	private $kode_menu = 'peserta-online';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_online';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tes_model');

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
		
		$tanggal_awal = date('Y-m-d H:i', strtotime('- 12 hours'));
        $tanggal_akhir = date('Y-m-d H:i', strtotime('+ 12 hours'));
		$data['rentang_waktu'] = $tanggal_awal.' - '.$tanggal_akhir;
        
        $this->template->display_admin($this->kelompok.'/peserta_online_view', 'Status Online Peserta', $data);
    }
    
    function get_datatable(){
		$grup_id = $this->input->get('group');
		$keterangan = $this->input->get('keterangan');
		$status_filter = $this->input->get('status');

		$search = "";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		$start = $this->get_start();
		$rows = $this->get_rows();

		// Query daftar peserta
		$this->db->select('cbt_user.*, cbt_user_grup.grup_nama')
		         ->from('cbt_user')
		         ->join('cbt_user_grup', 'cbt_user.user_grup_id = cbt_user_grup.grup_id', 'left');

		if(!empty($grup_id) && $grup_id != 'semua'){
			$this->db->where('cbt_user.user_grup_id', $grup_id);
		}
		if(!empty($keterangan)){
			$this->db->like('cbt_user.user_detail', $keterangan);
		}
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('cbt_user.user_name', $search);
			$this->db->or_like('cbt_user.user_firstname', $search);
			$this->db->group_end();
		}

		$clone_db = clone $this->db;
		$iTotal = $clone_db->count_all_results();

		$this->db->order_by('cbt_user.user_firstname', 'ASC');
		$this->db->limit($rows, $start);
		$query = $this->db->get();

		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

		$i = $start;
		if($query->num_rows() > 0){
			foreach ($query->result() as $u) {
				$i++;
				$record = array();
				$record[] = $i;
				$record[] = '<strong>' . htmlspecialchars($u->user_firstname) . '</strong>';
				$record[] = '<code>' . htmlspecialchars($u->user_name) . '</code>';
				$record[] = htmlspecialchars($u->grup_nama . (!empty($u->user_detail) ? ' &bull; ' . $u->user_detail : ''));

				// Cek status aktif di cbt_tes_user
				$q_tes = $this->db->select('cbt_tes_user.*, cbt_tes.tes_nama')
				                  ->from('cbt_tes_user')
				                  ->join('cbt_tes', 'cbt_tes_user.tesuser_tes_id = cbt_tes.tes_id', 'left')
				                  ->where('cbt_tes_user.tesuser_user_id', $u->user_id)
				                  ->order_by('cbt_tes_user.tesuser_creation_time', 'DESC')
				                  ->limit(1)
				                  ->get();

				$badge = '<span class="label label-default" style="font-size:11px; padding:3px 8px; border-radius:10px;"><i class="fa fa-circle-o"></i> Offline</span>';

				if($q_tes->num_rows() > 0){
					$tu = $q_tes->row();
					if($tu->tesuser_status == 1){
						$badge = '<span class="label label-success" style="font-size:11px; padding:3px 8px; border-radius:10px;"><i class="fa fa-circle"></i> Mengerjakan ('.htmlspecialchars($tu->tes_nama).')</span>';
					}elseif($tu->tesuser_status == 4){
						$badge = '<span class="label label-primary" style="font-size:11px; padding:3px 8px; border-radius:10px;"><i class="fa fa-check-circle"></i> Selesai ('.htmlspecialchars($tu->tes_nama).')</span>';
					}
				}

				$record[] = $badge;
				$output['aaData'][] = $record;
			}
		}

		echo json_encode($output);
	}
}
