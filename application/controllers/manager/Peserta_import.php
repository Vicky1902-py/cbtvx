<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_import extends Member_Controller {
	private $kode_menu = 'peserta-import';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_import';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
        $this->import();
    }

    public function import(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $this->load->library('form_validation');

        $data['error'] = '';
        $data['error_upload'] = '';

        if(!empty($_FILES['userfile']['name'])){
			$config['upload_path'] = './public/uploads/';
	        $config['allowed_types'] = 'xls|xlsx';
	        $config['max_size']	= '0';
	        $config['overwrite'] = true;
	        $config['file_name'] = 'siswa_import_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $_FILES['userfile']['name']);
	            
	        $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfile')){
            	$data['error_upload'] = $this->upload->display_errors().' (Tipe file: '.$_FILES['userfile']['type'].')';
            }else{
            	$upload_data = $this->upload->data();
                $data['filename'] = 'File '.$upload_data['file_name'].' berhasil diunggah.';
                        
                // disini proses import data
                $data['hasil'] = $this->import_file($upload_data['file_name']);
            }   
                    
        }else{
        	$data['error_upload'] = 'Pilih file Excel yang akan di-import';
        }
        
        $this->template->display_admin($this->kelompok.'/peserta_import_view', 'Import Data Peserta', $data);
    }

    function import_file($inputfile){
        $this->load->library('excel');
        $inputFileName = './public/uploads/'.$inputfile;

        try {
            $excel = PHPExcel_IOFactory::load($inputFileName);
        } catch (Exception $e) {
            return '<div class="alert alert-danger" style="border-radius:10px;">Gagal membaca berkas Excel: '.$e->getMessage().'</div>';
        }

        $worksheet = $excel->getSheet(0);
        $highestRow = $worksheet->getHighestRow();
        $pesan = '<div class="alert alert-info alert-dismissible" style="border-radius:10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info-circle"></i> Hasil Import Peserta:</h4>';
        
        if($highestRow >= 2){
            $jmldatasukses = 0;
            $jmldataerror = 0;
            $row = 2;
            $kosong = 0;
            while($kosong < 3 && $row <= $highestRow){
                $kolom1 = trim((string)$worksheet->getCellByColumnAndRow(1, $row)->getValue()); // username / NISN
                $kolom2 = trim((string)$worksheet->getCellByColumnAndRow(2, $row)->getValue()); // password
                $kolom3 = trim(ucwords(addslashes((string)$worksheet->getCellByColumnAndRow(3, $row)->getValue()))); // nama
                $kolom4 = trim((string)$worksheet->getCellByColumnAndRow(4, $row)->getValue()); // email
                $kolom5 = trim((string)$worksheet->getCellByColumnAndRow(5, $row)->getValue()); // group / kelas
				$kolom6 = trim((string)$worksheet->getCellByColumnAndRow(6, $row)->getValue()); // keterangan / ruang

                if(empty($kolom1) && empty($kolom2) && empty($kolom3)){
                    $kosong++;
                    $row++;
                    continue;
                }
                $kosong = 0;
                
                if(!empty($kolom1) && !empty($kolom2) && !empty($kolom3)){
                    // Otomatis buat group jika belum ada
                    if(empty($kolom5)){
                        $kolom5 = 'SEMUA';
                    }
                    if($this->cbt_user_grup_model->count_by_kolom('grup_nama', $kolom5)->row()->hasil == 0){
                        $this->cbt_user_grup_model->save(array('grup_nama' => $kolom5));
                    }

                    if($this->cbt_user_model->count_by_kolom('user_name', $kolom1)->row()->hasil > 0){
                        $pesan .= 'Baris '.$row.': NISN/Username <b>'.$kolom1.'</b> ('.$kolom3.') sudah terdaftar, dilewati.<br>';
                        $jmldataerror++;
                    }else{
                        $grup_row = $this->cbt_user_grup_model->get_by_kolom_limit('grup_nama', $kolom5, 1)->row();
                        $data_siswa['user_name'] = $kolom1;
                        $data_siswa['user_password'] = $kolom2;
                        $data_siswa['user_email'] = !empty($kolom4) ? $kolom4 : $kolom1.'@smkn1kupangbarat.sch.id';
                        $data_siswa['user_firstname'] = $kolom3;
                        $data_siswa['user_grup_id'] = $grup_row->grup_id;
                        $data_siswa['user_detail'] = !empty($kolom6) ? $kolom6 : 'Ruang 1, Sesi 1';

                        $this->cbt_user_model->save($data_siswa);
                        $jmldatasukses++;
                    }
                }else{
                    $pesan .= 'Baris '.$row.': Kolom NISN, Password, atau Nama kosong.<br>';
                    $jmldataerror++;
                }
                
                $row++;
            }
            $pesan .= '<hr style="margin:10px 0;"><i class="fa fa-check text-green"></i> <b>Total Berhasil:</b> '.$jmldatasukses.' peserta.<br>
                       <i class="fa fa-times text-red"></i> <b>Total Gagal / Dilewati:</b> '.$jmldataerror.' baris.</div>';
        }else{
            $pesan .= 'Tidak ada data peserta yang ditemukan pada file Excel. Cek kembali format berkas yang diunggah.</div>';
        }
        
        return $pesan;
    }
}
