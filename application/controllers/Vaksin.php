<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaksin extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('vaksin_model','master_model'));
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));	  
	}

	public function secure(){
	    $this->session->set_userdata('redirect_url', current_url() );
	    if (!$this->ion_auth->logged_in()){
	        redirect('auth/login', 'refresh');
	    }
	}
	
	public function index() {
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['tgl_now']=Date('Y-m-d');
	    $data['cari']="";
	    $data['title']="Vaksin";
	    $data['subtitle']="Manage Vaksin";
	   
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $data['group'] =$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['sts_pegawai'] = $this->master_model->mst_status_pegawai($cari='')->result();
	    $data['kabupaten'] = $this->master_model->mst_kabupaten($cari='')->result();
	    if($group == 1 ){
	        $data['company'] = $this->master_model->company_bygroupcompany(1);
	    }else{
	        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    }
	    $data["menu"]=$this->master_model->menus($group);
	    
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    
	    $this->load->view('header',$data);
	    $this->load->view('vaksin/list_vaksin',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_list($mc_id) {
	    $this->secure();
	    $this->load->helper('url');	  
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    $list = $this->vaksin_model->get_datatables($mc_id);
	    
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $vaksin) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $vaksin->tv_nama;
	        $row[] = $vaksin->tv_nik;
	        $row[] = $vaksin->tv_nip;
	        $row[] = $vaksin->mkab_name;
	        $row[] = $vaksin->tv_date1;
	        $row[] = $vaksin->tv_lokasi1;
	        
	        $btn_edit ='<a class="btn btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit" 
                        onclick="edit_vaksin('."'".$vaksin->tv_id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
	        $btn_delete ='<a class="btn btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete" 
                        onclick="delete_vaksin('."'".$vaksin->tv_id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
	       
	        if($update==1){
	            $btnx_edit =$btn_edit;
	        }else{
	            $btnx_edit ='';
	        }
	        
	        if($delete==1){
	            $btnx_delete =$btn_delete;
	        }else{
	            $btnx_delete ='';
	        }
	        $row[] = ' <div class="button">'.$btnx_edit.' '.$btnx_delete.'</button>';
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->vaksin_model->count_all($mc_id),
	        "recordsFiltered" => $this->vaksin_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	private function _validate($addedit) {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    
	    if($this->input->post('nama_pegawai') == '') {
	        $data['inputerror'][] = 'nama_pegawai';
	        $data['error_string'][] = 'Nama Pegawai is required';
	        $data['status'] = FALSE;
	    }
	    
	    if($this->input->post('nik') == '') {
	        $data['inputerror'][] = 'nik';
	        $data['error_string'][] = 'NIK is required';
	        $data['status'] = FALSE;
	    }else{
	        if($addedit=='add'){
    	        $cnt_nik = $this->vaksin_model->count_nik($this->input->post('nik'));
    	        if(count($cnt_nik) > 0) {
    	            $data['inputerror'][] = 'nik';
    	            $data['error_string'][] = 'NIK is already taken';
    	            $data['status'] = FALSE;
    	        } 
	        }
	        if(strlen($this->input->post('nik'))!=16){
	            $data['inputerror'][] = 'nik';
	            $data['error_string'][] = 'NIK length must 16 ';
	            $data['status'] = FALSE;
	        }
	    }
	    
	    if($this->input->post('nip') == '') {
	        $data['inputerror'][] = 'nip';
	        $data['error_string'][] = 'NIK Pegawai is required';
	        $data['status'] = FALSE;
	    }
	    
	    if($this->input->post('tgl_lahir') == '') {
	        $data['inputerror'][] = 'tgl_lahir';
	        $data['error_string'][] = 'Tanggal Lahir is required';
	        $data['status'] = FALSE;
	    }
	    
	    if($this->input->post('unit') == '') {
	        $data['inputerror'][] = 'unit';
	        $data['error_string'][] = 'unit is required';
	        $data['success'] = FALSE;
	    }
	    
	    if($data['status'] === FALSE) {
	        $data['status'] = 500;
	        echo json_encode($data);
	        exit();
	    }else{
	        $data['inputerror'][] = '';
	        $data['error_string'][] = '';
	        $data['status'] = 200;
	    }
	}
	
	public function ajax_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    $this->_validate('add');

	    $tgl_lahir = $this->input->post('tgl_lahir')!='' ? $this->input->post('tgl_lahir') : NULL;
	    $jml_keluarga = $this->input->post('jml_keluarga')!='' ? $this->input->post('jml_keluarga') : 0;
	    $tglvaksin_1 = $this->input->post('tglvaksin_1')!='' ? $this->input->post('tglvaksin_1') : NULL;
	    $tglvaksin_2 = $this->input->post('tglvaksin_2')!='' ? $this->input->post('tglvaksin_2') : NULL;
	    $tglvaksin_3 = $this->input->post('tglvaksin_3')!='' ? $this->input->post('tglvaksin_3') : NULL;
	    
	    $nikanak_1 = $this->input->post('nikanak_1')!='' ? $this->input->post('nikanak_1') : NULL;
	    $nikanak_2 = $this->input->post('nikanak_2')!='' ? $this->input->post('nikanak_2') : NULL;
	    $nikanak_3 = $this->input->post('nikanak_3')!='' ? $this->input->post('nikanak_3') : NULL;
	    $nikanak_4 = $this->input->post('nikanak_4')!='' ? $this->input->post('nikanak_4') : NULL;
	    $nikanak_5 = $this->input->post('nikanak_5')!='' ? $this->input->post('nikanak_5') : NULL;
	   
	    if ($_FILES['fotovaksin_1']['tmp_name']!='') {
	        $file_name1 =$_FILES['fotovaksin_1']['name'];
	        $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	        $file1x = round(microtime(true) * 1000);
	        
	        $file1 = round(microtime(true) * 1000).'.'.$file_ext1;
	        $this->_do_upload($file1x, 'fotovaksin_1');
	    }else{
	        $file1 = NULL;
	    }
	    
	    if ($_FILES['fotovaksin_2']['tmp_name']!='') {
	        $file_name2 =$_FILES['fotovaksin_2']['name'];
	        $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
	        $file2x = round(microtime(true) * 1000);
	        
	        $file2 = round(microtime(true) * 1000).'.'.$file_ext2;
	        $this->_do_upload($file2x, 'fotovaksin_2');
	    }else{
	        $file2 = NULL;
	    }
	    
	    if ($_FILES['fotovaksin_3']['tmp_name']!='') {
	        $file_name3 =$_FILES['fotovaksin_3']['name'];
	        $file_ext3 =  pathinfo($file_name3, PATHINFO_EXTENSION);
	        $file3x = round(microtime(true) * 1000);
	        
	        $file3 = round(microtime(true) * 1000).'.'.$file_ext3;
	        $this->_do_upload($file3x, 'fotovaksin_3');
	    }else{
	        $file3 = NULL;
	    }
	    
	    $datax = array(
	        'tv_nik' => $this->input->post('nik'),
	        'tv_nama' => $this->input->post('nama_pegawai'),
	        'tv_mc_id' => $this->input->post('kd_perusahaan_modal'),
	        'tv_nip' => $this->input->post('nip'),
	        'tv_no_hp' => $this->input->post('no_hp'),
	        'tv_msp_id' => $this->input->post('sts_pegawai'),
	        'tv_mjk_id' => $this->input->post('jns_kelamin'),
	        'tv_mkab_id' => $this->input->post('kabupaten'),
	        'tv_unit' => $this->input->post('unit'),
	        'tv_ttl_date' => $tgl_lahir,
	        'tv_jml_keluarga' => $this->input->post('jml_keluarga'),
	        'tv_nik_pasangan' => $this->input->post('nik_pasangan'),
	        'tv_nama_pasangan' => $this->input->post('nama_pasangan'),
	        'tv_nik_anak1' => $nikanak_1,
	        'tv_nama_anak1' => $this->input->post('namaanak_1'),
	        'tv_nik_anak2' => $nikanak_2,
	        'tv_nama_anak2' => $this->input->post('namaanak_2'),
	        'tv_nik_anak3' => $nikanak_3,
	        'tv_nama_anak3' => $this->input->post('namaanak_3'),
	        'tv_nik_anak4' => $nikanak_4,
	        'tv_nama_anak4' => $this->input->post('namaanak_4'),
	        'tv_nik_anak5' => $nikanak_5,
	        'tv_nama_anak5' => $this->input->post('namaanak_5'),
	        'tv_date1' => $tglvaksin_1,
	        'tv_lokasi1' => $this->input->post('lokasivaksin_1'),
	        'tv_date2' => $tglvaksin_2,
	        'tv_lokasi2' => $this->input->post('lokasivaksin_2'),
	        'tv_date3' => $tglvaksin_3,
	        'tv_lokasi3' => $this->input->post('lokasivaksin_3'),
	        "tv_file1" => $file1,
	        "tv_file2" => $file2,
	        "tv_file3" => $file3,
	        'tv_date_insert' =>  date('Y-m-d H:i:s'),
	        'tv_user_insert' =>  $this->ion_auth->user()->row()->id
	    );
	    
	    $insert = $this->vaksin_model->save($datax);
	    
	    if($insert==1){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Tambah Data Vaksin'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Tambah Data Vaksin'));
	    }
	}
	
	public function ajax_delete($id) {
	    $delete =  $this->vaksin_model->delete_byid($id);
	    
	    if($delete===true){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Delete data Vaksin'));
	    }else{
	        echo json_encode(array("status" => NULL, "message" => 'Gagal Delete data Vaksin'));
	    }
	}
	
	public function ajax_edit($id) {
	    $vaksin = $this->vaksin_model->get_byid($id);
	    //var_dump($vaksin);die;
	    if(isset($vaksin)) {
	        $tgl_lahir = $vaksin->tv_ttl_date='' ? NULL : $vaksin->tv_ttl_date;
	        $jml_keluarga = $vaksin->tv_jml_keluarga ='' ? 0 : $vaksin->tv_jml_keluarga;
	        $tglvaksin_1 = $vaksin->tv_date1 = '' ? $vaksin->tv_date1 : NULL;
	        $tglvaksin_2 = $vaksin->tv_date2 = '' ? $vaksin->tv_date2 : NULL;
	        $tglvaksin_3 = $vaksin->tv_date3 = '' ? $vaksin->tv_date3 : NULL;

	        if($vaksin->tv_file1!='' && file_exists('uploads/vaksin_eviden/'.$vaksin->tv_file1)){
    	        $urlimg_1 = site_url().'uploads/vaksin_eviden/'.$vaksin->tv_file1;
    	        $img_1 = '<img src="'.$urlimg_1.'" width="400px">';
	        }else{
	            $img_1 = '';
	        }
			
			if($vaksin->tv_file2!='' && file_exists('uploads/vaksin_eviden/'.$vaksin->tv_file2)){
			    $urlimg_2 = site_url().'uploads/vaksin_eviden/'.$vaksin->tv_file2;
			    $img_2 = '<img src="'.$urlimg_2.'" width="400px">';
			}else{
			    $img_2 = '';
			}
			
			if($vaksin->tv_file3!='' && file_exists('uploads/vaksin_eviden/'.$vaksin->tv_file3)){
			    $urlimg_3 = site_url().'uploads/vaksin_eviden/'.$vaksin->tv_file3;
			    $img_3 = '<img src="'.$urlimg_3.'" width="400px">';
			}else{
			    $img_3 = '';
			}
	        
	        $data = array(
	            "id" => $vaksin->tv_id,
	            'kd_perusahaan_modal' => $vaksin->tv_mc_id,
	            'nama_pegawai' => $vaksin->tv_nama,
	            'nip' => $vaksin->tv_nip,
	            'sts_pegawai' => $vaksin->tv_msp_id,
	            'jns_kelamin' => $vaksin->tv_mjk_id,
	            'kabupaten' => $vaksin->tv_mkab_id,
	            'unit' => $vaksin->tv_unit,
	            'nik' => $vaksin->tv_nik,
	            'tgl_lahir' => $tgl_lahir,
	            'no_hp' => $vaksin->tv_no_hp,
	            'jml_keluarga' => $vaksin->tv_jml_keluarga,
	            'nik_pasangan' => $vaksin->tv_nik_pasangan,
	            'nama_pasangan' => $vaksin->tv_nama_pasangan,
	            'nikanak_1' => $vaksin->tv_nik_anak1,
	            'namaanak_1' => $vaksin->tv_nama_anak1,
	            'nikanak_2' => $vaksin->tv_nik_anak2,
	            'namaanak_2' => $vaksin->tv_nama_anak2,
	            'nikanak_3' => $vaksin->tv_nik_anak3,
	            'namaanak_3' => $vaksin->tv_nama_anak3,
	            'nikanak_4' => $vaksin->tv_nik_anak4,
	            'namaanak_4' => $vaksin->tv_nama_anak4,
	            'nikanak_5' => $vaksin->tv_nik_anak5,
	            'namaanak_5' => $vaksin->tv_nama_anak5,
	            "tglvaksin_1" => $tglvaksin_1,
	            "lokasivaksin_1" => $vaksin->tv_lokasi1,
	            "tglvaksin_2" => $tglvaksin_2,
	            "lokasivaksin_2" => $vaksin->tv_lokasi2,
	            "tglvaksin_3" => $tglvaksin_3,
	            "lokasivaksin_3" => $vaksin->tv_lokasi3,
	            "anak_jml" => $vaksin->sts_anak,
	            "vaksin_jml" => $vaksin->sts_vaksin,
	            "img_1" => $img_1,
	            "img_2" => $img_2,
	            "img_3" => $img_3
	        );
	    }else{
	        $data = array();
	    }
	    echo json_encode($data);
	}
	
	public function ajax_update() {
	    date_default_timezone_set('Asia/Jakarta');
	    $this->_validate('edit');
	    
	    $tgl_lahir = $this->input->post('tgl_lahir')!='' ? $this->input->post('tgl_lahir') : NULL;
	    $jml_keluarga = $this->input->post('jml_keluarga')!='' ? $this->input->post('jml_keluarga') : 0;
	    $tglvaksin_1 = $this->input->post('tglvaksin_1')!='' ? $this->input->post('tglvaksin_1') : NULL;
	    $tglvaksin_2 = $this->input->post('tglvaksin_2')!='' ? $this->input->post('tglvaksin_2') : NULL;
	    $tglvaksin_3 = $this->input->post('tglvaksin_3')!='' ? $this->input->post('tglvaksin_3') : NULL;
	    
	    $nikanak_1 = $this->input->post('nikanak_1')!='' ? $this->input->post('nikanak_1') : NULL;
	    $nikanak_2 = $this->input->post('nikanak_2')!='' ? $this->input->post('nikanak_2') : NULL;
	    $nikanak_3 = $this->input->post('nikanak_3')!='' ? $this->input->post('nikanak_3') : NULL;
	    $nikanak_4 = $this->input->post('nikanak_4')!='' ? $this->input->post('nikanak_4') : NULL;
	    $nikanak_5 = $this->input->post('nikanak_5')!='' ? $this->input->post('nikanak_5') : NULL;
	    
	    if ($_FILES['fotovaksin_1']['tmp_name']!='') {
	        $file_name1 =$_FILES['fotovaksin_1']['name'];
	        $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	        $file1x = round(microtime(true) * 1000);
	        
	        $file1 = round(microtime(true) * 1000).'.'.$file_ext1;
	        $this->_do_upload($file1x, 'fotovaksin_1');
	    }else{
	        $file1 = NULL;
	    }
	    
	    if ($_FILES['fotovaksin_2']['tmp_name']!='') {
	        $file_name2 =$_FILES['fotovaksin_2']['name'];
	        $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
	        $file2x = round(microtime(true) * 1000);
	        
	        $file2 = round(microtime(true) * 1000).'.'.$file_ext2;
	        $this->_do_upload($file2x, 'fotovaksin_2');
	    }else{
	        $file2 = NULL;
	    }
	    
	    if ($_FILES['fotovaksin_3']['tmp_name']!='') {
	        $file_name3 =$_FILES['fotovaksin_3']['name'];
	        $file_ext3 =  pathinfo($file_name3, PATHINFO_EXTENSION);
	        $file3x = round(microtime(true) * 1000);
	        
	        $file3 = round(microtime(true) * 1000).'.'.$file_ext3;
	        $this->_do_upload($file3x, 'fotovaksin_3');
	    }else{
	        $file3 = NULL;
	    }
	    
	    $datax = array(
	        'tv_mc_id' => $this->input->post('kd_perusahaan_modal'),
	        'tv_nama' => $this->input->post('nama_pegawai'),
	        'tv_nik' => $this->input->post('nik'),
	        'tv_nip' => $this->input->post('nip'),
	        'tv_unit' => $this->input->post('unit'),
	        'tv_no_hp' => $this->input->post('no_hp'),
	        'tv_msp_id' => $this->input->post('sts_pegawai'),
	        'tv_mjk_id' => $this->input->post('jns_kelamin'),
	        'tv_mkab_id' => $this->input->post('kabupaten'),
	        'tv_ttl_date' => $tgl_lahir,
	        'tv_jml_keluarga' => $this->input->post('jml_keluarga'),
	        'tv_nik_pasangan' => $this->input->post('nik_pasangan'),
	        'tv_nama_pasangan' => $this->input->post('nama_pasangan'),
	        'tv_nik_anak1' => $nikanak_1,
	        'tv_nama_anak1' => $this->input->post('namaanak_1'),
	        'tv_nik_anak2' => $nikanak_2,
	        'tv_nama_anak2' => $this->input->post('namaanak_2'),
	        'tv_nik_anak3' => $nikanak_3,
	        'tv_nama_anak3' => $this->input->post('namaanak_3'),
	        'tv_nik_anak4' => $nikanak_4,
	        'tv_nama_anak4' => $this->input->post('namaanak_4'),
	        'tv_nik_anak5' => $nikanak_5,
	        'tv_nama_anak5' => $this->input->post('namaanak_5'),
	        'tv_date1' => $tglvaksin_1,
	        'tv_lokasi1' => $this->input->post('lokasivaksin_1'),
	        'tv_date2' => $tglvaksin_2,
	        'tv_lokasi2' => $this->input->post('lokasivaksin_2'),
	        'tv_date3' => $tglvaksin_3,
	        'tv_lokasi3' => $this->input->post('lokasivaksin_3'),
	        "tv_file1" => $file1,
	        "tv_file2" => $file2,
	        "tv_file3" => $file3,
	        'tv_date_update' =>  date('Y-m-d H:i:s'),
	        'tv_user_update' =>  $this->ion_auth->user()->row()->id
	    );
	    
	    $update = $this->vaksin_model->update(array('tv_id' => $this->input->post('id')), $datax);
	    
	    if($update==1){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Update Data Vaksin'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Update Data Vaksin'));
	    }
	}
	
	public function post_curl($kd_perusahaan, $filename){
	    $directory =  $_SERVER['DOCUMENT_ROOT'].FOLDER_UPLOAD.'uploads/vaksin_excel/'.$filename;
	    
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_SERVICE."import_vaksin",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "POST",
	        CURLOPT_POSTFIELDS => array(
	            'file_import'=> new CURLFILE($directory),
	            'kd_perusahaan' => $kd_perusahaan),
	    ));
	    
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}
	
	public function ajax_exceladd() {
	    $kd_perusahaan = $this->input->post('kd_perusahaan');
	    
	    if(!empty($_FILES['file_import']['name'])) {
	        $upload = $this->_do_upload_excel($kd_perusahaan);
	        
	        $data['tvx_mc_id'] = $kd_perusahaan;
	        $data['tvx_filename'] = $upload;
	        $data['tvx_user_insert']=$this->ion_auth->user()->row()->id;
	        $data['tvx_date_insert']=date('Y-m-d H:i:s');
	        $insert = $this->vaksin_model->saveexcel($data);
	        
	        $curl = $this->post_curl($kd_perusahaan, $upload);
	        
	        if(isset(json_decode($curl)->status)){
	            $message = json_decode($curl)->message;
	            if(json_decode($curl)->status==200){
	                echo json_encode(array("status" => 200,"message" => $message));
	            }else{
	                $message = json_decode($curl)->message;
	                echo json_encode(array("status" => 500,"message" => $message));
	            }
	        }else{
	            echo json_encode(array("status" => 500,"message" => $message));
	        }
	       
	    }else{
	        echo json_encode(array("status" => 404, "message" => 'File Not Found'));
	    }
	}
	
	public function _do_upload_excel($mc_id) {
		if(!is_dir("uploads/vaksin_excel/")) {
	        mkdir("uploads/vaksin_excel/");
	    }

	    $config['upload_path']          = 'uploads/vaksin_excel/';
	    if(is_file($config['upload_path']))
		{
		    chmod($config['upload_path'], 777); ## this should change the permissions
		}
	    $config['allowed_types']        = 'xlsx|xls';
	    $config['max_size']             = 10*1024;
	    $config['file_name']            = round(microtime(true) * 1000);
	    
	    $this->load->library('upload', $config);
	    
	    if(!$this->upload->do_upload('file_import')) {
	        $data['inputerror'][] = 'file_import';
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    
	    return $this->upload->data('file_name');
	}
	
	public function ajax_list_summary($mc_id) {
	    $response =  $this->vaksin_model->count_vaksin_bymc_id($mc_id)->result();
	   
	    $row='';
	    foreach ($response as $res) {
	        $row  .='<div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                <div class="card-wrap">
		    	<div class="card-header"><b>'.$res->v_judul.'</b></div>';
	        $row  .='<div class="card-body">'.$res->v_jml.'</div>';
	        $row  .='</div>';
	        $row  .='</div>';
	        $row  .='</div>';
	    }
	    echo $row;
	}
	
	public function pegawai() {
	    //6LfOXN8ZAAAAAMQ58k8nJGwc4rNdgiA6m4srnDMI
	    //6LfOXN8ZAAAAAJIzNkgchUFrOBzfrgOoxkjoXCGl
	    $data['company'] = $this->master_model->mst_company($cari='');
        $this->load->view('vaksin/pegawai', $data);
	}
	
	public function googleCaptachStore(){
	    $data = array(
	        'company' => $this->input->post('company'),
	        'nik' => $this->input->post('nik')
	    );
	    
	    $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
	    $userIp=$this->input->ip_address();
	    $secret='6Lc9Xd8ZAAAAAGrdfZEOoq-X5Mr3jPc5FFGvS5OF';
	    $credential = array(
	        'secret' => $secret,
	        'response' => $this->input->post('g-recaptcha-response')
	    );
	    
	    $verify = curl_init();
	    curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
	    curl_setopt($verify, CURLOPT_POST, true);
	    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
	    curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($verify);
	    
	    $status= json_decode($response, true);
	    
	    if($status['success']){
	        
	        $this->session->set_flashdata('message', 'Google Recaptcha Successful');
	    }else{
	        $this->session->set_flashdata('message', 'Sorry Google Recaptcha Unsuccessful!!');
	    }
	   
	}
	
	public function ajax_list_tmp($mc_id) {
	    $this->secure();
	    $this->load->helper('url');
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    $list = $this->vaksin_model->get_datatables_tmp($mc_id);
	    
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $vaksin) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $vaksin->nama;
	        $row[] = $vaksin->nik;
	        $row[] = $vaksin->nip;
	        $row[] = $vaksin->kota;
	        $row[] = $vaksin->sts;
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->vaksin_model->count_all_tmp($mc_id),
	        "recordsFiltered" => $this->vaksin_model->count_filtered_tmp($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	public function upload_vaksin() {
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['tgl_now']=Date('Y-m-d');
	    $data['cari']="";
	    $data['title']="Vaksin";
	    $data['subtitle']="Upload Vaksin";
	    
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $data['group'] =$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['sts_pegawai'] = $this->master_model->mst_status_pegawai($cari='')->result();
	    $data['kabupaten'] = $this->master_model->mst_kabupaten($cari='')->result();
	    if($group == 1 ){
	        $data['company'] = $this->master_model->company_bygroupcompany(1);
	    }else{
	        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    }
	    $data["menu"]=$this->master_model->menus($group);
	    
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    
	    $this->load->view('header',$data);
	    $this->load->view('vaksin/upload_vaksin',$data);
	    $this->load->view('footer',$data);
	}

	function get_autocomplete(){
	    if (isset($_GET['term'])) {
	        $result = $this->vaksin_model->lokasi1_vaksin($_GET['term'])->result();
	        if (count($result) > 0) {
	            foreach ($result as $row)
	                $arr_result[] = array(
	                    'nama_pegawai' => $row->tv_lokasi1
	                );
	            echo json_encode($arr_result);
	        }
	    }
	}
	
	private function _do_upload($file, $nama_form) {
	    set_time_limit(0);
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '-1');
	    ini_set('max_input_time', 3600);
	    
	    if(!is_dir("uploads/vaksin_eviden/")) {
	        mkdir("uploads/vaksin_eviden/");
	    }
	    
	    $config['upload_path']          = 'uploads/vaksin_eviden/';
	    if(is_file($config['upload_path']))
	    {
	        chmod($config['upload_path'], 777); ## this should change the permissions
	    }
	    $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
	    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = $file; //just milisecond timestamp fot unique name
	    
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if(!$this->upload->do_upload($nama_form)) {
	        $data['inputerror'][] = $nama_form;
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    return $this->upload->data('file_name');
	}
}