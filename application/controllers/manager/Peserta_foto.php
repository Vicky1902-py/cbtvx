<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_foto extends Member_Controller {
	private $kode_menu = 'peserta-foto';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_foto';
	private $upload_dir = './public/uploads/foto_siswa/';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');

		if(!is_dir($this->upload_dir)){
			@mkdir($this->upload_dir, 0777, true);
		}

		parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
		$data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

		$query_group = $this->cbt_user_grup_model->get_group();
        $select = '<option value="semua">Semua Group</option>';
        if($query_group->num_rows() > 0){
        	foreach ($query_group->result() as $temp) {
        		$select .= '<option value="'.$temp->grup_id.'">'.$temp->grup_nama.'</option>';
        	}
        }
        $data['select_group'] = $select;

		// Hitung statistik foto
		$total_peserta = $this->cbt_user_model->count_all()->row()->hasil;
		$all_files = is_dir($this->upload_dir) ? array_diff(scandir($this->upload_dir), array('.', '..')) : array();
		$total_foto = count($all_files);

		$data['total_peserta'] = $total_peserta;
		$data['total_foto'] = $total_foto;
		
        $this->template->display_admin($this->kelompok.'/peserta_foto_view', 'Upload Foto Siswa', $data);
    }

	public function upload_zip(){
		$status = array('status' => 0, 'pesan' => '');

		if(empty($_FILES['zip_file']['name'])){
			$status['pesan'] = 'Silahkan pilih berkas file .ZIP terlebih dahulu!';
			echo json_encode($status);
			return;
		}

		$config['upload_path'] = './public/uploads/';
		$config['allowed_types'] = 'zip';
		$config['max_size'] = 51200; // 50MB
		$config['file_name'] = 'foto_siswa_'.time().'.zip';
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);

		if($this->upload->do_upload('zip_file')){
			$upload_data = $this->upload->data();
			$zip_file_path = $upload_data['full_path'];

			$zip = new ZipArchive;
			if($zip->open($zip_file_path) === TRUE){
				$success_count = 0;
				$allowed_exts = array('jpg', 'jpeg', 'png', 'webp');

				for($i = 0; $i < $zip->numFiles; $i++){
					$entry_name = $zip->getNameIndex($i);

					// Skip folder dan metadata mac
					if(substr($entry_name, -1) === '/' || strpos($entry_name, '__MACOSX') !== false) {
						continue;
					}

					$basename = basename($entry_name);
					$ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

					if(in_array($ext, $allowed_exts)){
						$stream = $zip->getStream($entry_name);
						if($stream){
							$dest_path = $this->upload_dir . $basename;
							file_put_contents($dest_path, stream_get_contents($stream));
							fclose($stream);
							$success_count++;
						}
					}
				}

				$zip->close();
				@unlink($zip_file_path); // Hapus zip sementara

				$status['status'] = 1;
				$status['pesan'] = 'Berhasil mengekstrak dan mendaftarkan '.$success_count.' foto siswa ke dalam sistem!';
			}else{
				$status['pesan'] = 'Gagal membaca atau mengekstrak file ZIP. Pastikan file zip valid.';
			}
		}else{
			$status['pesan'] = $this->upload->display_errors('', '');
		}

		echo json_encode($status);
	}

	public function upload_single(){
		$status = array('status' => 0, 'pesan' => '');
		$username = $this->input->post('single_username', true);

		if(empty($username)){
			$status['pesan'] = 'Username atau NISN siswa harus diisi!';
			echo json_encode($status);
			return;
		}

		if(empty($_FILES['single_foto']['name'])){
			$status['pesan'] = 'Silahkan pilih foto siswa!';
			echo json_encode($status);
			return;
		}

		$config['upload_path'] = $this->upload_dir;
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 3072; // 3MB
		$config['file_name'] = $username;
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if($this->upload->do_upload('single_foto')){
			$status['status'] = 1;
			$status['pesan'] = 'Foto untuk peserta '.$username.' berhasil diperbarui!';
		}else{
			$status['pesan'] = $this->upload->display_errors('', '');
		}

		echo json_encode($status);
	}

	public function hapus_semua(){
		$files = glob($this->upload_dir . '*');
		$count = 0;
		foreach($files as $file){
			if(is_file($file)){
				unlink($file);
				$count++;
			}
		}

		$status = array('status' => 1, 'pesan' => 'Berhasil menghapus seluruh foto siswa ('.$count.' file dibersihkan).');
		echo json_encode($status);
	}

	public function get_datatable(){
		$grup_id = $this->input->get('grup');
		$query = $this->cbt_user_model->get_by_kolom('user_grup_id', $grup_id);
		if(empty($grup_id) || $grup_id == 'semua'){
			$query = $this->cbt_user_model->get_all();
		}

		$output = array('data' => array());

		if($query->num_rows() > 0){
			$no = 1;
			foreach($query->result() as $temp){
				// Check foto
				$foto_url = '';
				$status_foto = '<span class="badge bg-red" style="font-size: 11px;">Belum Ada</span>';
				
				$exts = array('jpg', 'jpeg', 'png', 'webp');
				foreach($exts as $ext){
					$path = $this->upload_dir . $temp->user_name . '.' . $ext;
					if(file_exists($path)){
						$foto_url = base_url('public/uploads/foto_siswa/' . $temp->user_name . '.' . $ext);
						$status_foto = '<span class="badge bg-green" style="font-size: 11px;">Sudah Ada</span>';
						break;
					}
				}

				$thumb = !empty($foto_url) 
					? '<img src="'.$foto_url.'" style="width: 42px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">'
					: '<div style="width: 42px; height: 50px; background: #f1f5f9; border-radius: 6px; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #94a3b8;"><i class="fa fa-user"></i></div>';

				$aksi = '<button type="button" class="btn btn-xs btn-default" onclick="gantiFoto(\''.$temp->user_name.'\', \''.htmlspecialchars($temp->user_firstname).'\')"><i class="fa fa-upload"></i> Unggah</button>';

				$output['data'][] = array(
					$no++,
					$thumb,
					'<b>'.$temp->user_name.'</b>',
					$temp->user_firstname,
					$temp->user_detail,
					$status_foto,
					$aksi
				);
			}
		}

		echo json_encode($output);
	}
}
