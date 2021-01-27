<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasus extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('kasus_model','master_model'));
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
	    $data['title']="Pegawai Terdampak";
	    $data['subtitle']="Pegawai Terdampak";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $data['group'] =$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['sts_kasus'] = $this->master_model->mst_status_kasus($cari='')->result();
	    $data['sts_pegawai'] = $this->master_model->mst_status_pegawai($cari='')->result();
	    $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
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
	    $this->load->view('kasus/list_kasus',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_list($mc_id) {
	    $this->load->helper('url');
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    $list = $this->kasus_model->get_datatables($mc_id);

	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $kasus) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $kasus->tk_nama;
	        $row[] = $kasus->msp_name;
	        $row[] = $kasus->mpro_name;
	        $row[] = $kasus->mkab_name;
	        $row[] = $kasus->msk_name2;
	        $row[] = $kasus->tk_date_positif;
	        $row[] = $kasus->tk_date_meninggal;
	        $row[] = $kasus->tk_tempat_perawatan;
	        $row[] = $kasus->tk_date_sembuh;
	        $row[] = $kasus->tk_tindakan;
	        $btn_edit ='<a class="btn btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit"
                        onclick="edit_kasus('."'".$kasus->tk_id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
	        $btn_delete ='<a class="btn btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_kasus('."'".$kasus->tk_id."'".')">
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
	        "recordsTotal" => $this->kasus_model->count_all($mc_id),
	        "recordsFiltered" => $this->kasus_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	function get_kasus_byid(){
	    $kasus_id=$this->input->get('id');
	    $data=$this->kasus_model->getkasus_byid($kasus_id);
	    echo json_encode($data);
	}

	public function ajax_delete($id) {
	    $delete =  $this->kasus_model->delete_byid($id);

        if($delete===true){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Kasus'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Delete Kasus'));
        }
	}

	public function ajax_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    $sts_kasus = $this->input->post('sts_kasus');
	    $this->_validate($sts_kasus);

	    if($sts_kasus==5){
	        $tgl_v = $this->input->post('tgl');
	        $tgl = 'tk_date_meninggal';
	    }else if($sts_kasus==4){
	        $tgl_v = $this->input->post('tgl');
	        $tgl = 'tk_date_sembuh';
	    }else{
	        $tgl_v = $this->input->post('tgl_positif');
	        $tgl = 'tk_date_positif';
	    }

	    if($sts_kasus>2 && $sts_kasus<6){
    	    $data = array(
    	        'tk_mc_id' => $this->input->post('kd_perusahaan_modal'),
    	        'tk_nama' => $this->input->post('nama_pegawai'),
    	        'tk_msp_id' => $this->input->post('sts_pegawai'),
    	        'tk_mpro_id' => $this->input->post('provinsi'),
    	        'tk_mkab_id' => $this->input->post('kabupaten'),
    	        'tk_msk_id' => $this->input->post('sts_kasus'),
    	        'tk_tempat_perawatan' => $this->input->post('tmpt_rawat'),
    	        'tk_tindakan' => $this->input->post('tindakan'),
    	        'tk_date_insert' =>  date('Y-m-d H:i:s'),
    	        'tk_user_insert' =>  $this->ion_auth->user()->row()->id,
    	        'tk_date_positif' => $this->input->post('tgl_positif'),
    	        'tk_date' => $tgl_v,
    	         $tgl => $tgl_v
    	    );
	    }else{
	        $data = array(
	            'tk_mc_id' => $this->input->post('kd_perusahaan_modal'),
	            'tk_nama' => $this->input->post('nama_pegawai'),
	            'tk_msp_id' => $this->input->post('sts_pegawai'),
	            'tk_mpro_id' => $this->input->post('provinsi'),
	            'tk_mkab_id' => $this->input->post('kabupaten'),
	            'tk_msk_id' => $this->input->post('sts_kasus'),
	            'tk_tempat_perawatan' => $this->input->post('tmpt_rawat'),
	            'tk_tindakan' => $this->input->post('tindakan'),
	            'tk_date_insert' =>  date('Y-m-d H:i:s'),
	            'tk_user_insert' =>  $this->ion_auth->user()->row()->id,
	        );
	    }

	    $insert = $this->kasus_model->save($data);

	    if($insert==1){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Tambah Data Kasus'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Tambah Data Kasus'));
	    }
	}

	public function kabupaten(){
	    $id_provinsi = $this->input->post('id_provinsi');
	    $id_kabupaten = $this->input->post('id_kabupaten');

	    $kabupaten = $this->master_model->kabupaten($id_provinsi);
	    $lists = "";

	    foreach($kabupaten->result() as $data){
	        if($id_kabupaten!=0 && $id_kabupaten==$data->mkab_id){
	            $selected = 'selected="selected"';
	        }else{
	            $selected = '';
	        }
	        $lists .= "<option value='".$data->mkab_id."'  $selected>".$data->mkab_name."</option>";
	    }

	    $callback = array('list_kabupaten'=>$lists);
	    echo json_encode($callback);
	}

	public function ajax_edit($id) {
        $kasus = $this->kasus_model->get_byid($id);
        if(isset($kasus)) {
            $sts_kasus = $kasus->tk_msk_id;
            if($sts_kasus==5){
                $tgl = $kasus->tk_date_meninggal;
            }else if($sts_kasus==4){
                $tgl = $kasus->tk_date_sembuh;
            }else{
                $tgl = '';
            }

        	$data = array(
        	    "id" => $kasus->tk_id,
        	    "nama_pegawai" => $kasus->tk_nama,
        	    "sts_kasus" => $kasus->tk_msk_id,
        	    "tgl" => ($tgl == '0000-00-00') ? '' : $tgl,
        	    "tgl_positif" => ( $kasus->tk_date_positif == '0000-00-00') ? '' : $kasus->tk_date_positif,
        	    "provinsi" => $kasus->tk_mpro_id,
        	    "kabupaten" => $kasus->tk_mkab_id,
        	    "sts_pegawai" => $kasus->tk_msp_id,
        	    "tmpt_rawat" =>  $kasus->tk_tempat_perawatan,
        	    "tindakan" =>  $kasus->tk_tindakan,
        	 );
        }else{
            $data = array();
        }
	    echo json_encode($data);
	}

	public function ajax_update() {
	    date_default_timezone_set('Asia/Jakarta');
	    $sts_kasus = $this->input->post('sts_kasus');
	    $this->_validate($sts_kasus);

	    if($sts_kasus==5){
	        $tgl_v = $this->input->post('tgl');
	        $tgl = 'tk_date_meninggal';
	    }else if($sts_kasus==4){
	        $tgl_v = $this->input->post('tgl');
	        $tgl = 'tk_date_sembuh';
	    }else{
	        $tgl_v = $this->input->post('tgl_positif');
	        $tgl = 'tk_date_positif';
	    }

	    if($sts_kasus>2 && $sts_kasus<6){
	        $data = array(
	            'tk_mc_id' => $this->input->post('kd_perusahaan_modal'),
	            'tk_nama' => $this->input->post('nama_pegawai'),
	            'tk_msp_id' => $this->input->post('sts_pegawai'),
	            'tk_mpro_id' => $this->input->post('provinsi'),
	            'tk_mkab_id' => $this->input->post('kabupaten'),
	            'tk_msk_id' => $this->input->post('sts_kasus'),
	            'tk_tempat_perawatan' => $this->input->post('tmpt_rawat'),
	            'tk_tindakan' => $this->input->post('tindakan'),
	            'tk_date_update' => date('Y-m-d H:i:s'),
	            'tk_user_update' => $this->ion_auth->user()->row()->id,
	            'tk_date_positif' => $this->input->post('tgl_positif'),
	            'tk_date' => $tgl_v,
	             $tgl => $tgl_v
	        );
	    }else{
	        $data = array(
	            'tk_mc_id' => $this->input->post('kd_perusahaan_modal'),
	            'tk_nama' => $this->input->post('nama_pegawai'),
	            'tk_msp_id' => $this->input->post('sts_pegawai'),
	            'tk_mpro_id' => $this->input->post('provinsi'),
	            'tk_mkab_id' => $this->input->post('kabupaten'),
	            'tk_msk_id' => $this->input->post('sts_kasus'),
	            'tk_tempat_perawatan' => $this->input->post('tmpt_rawat'),
	            'tk_tindakan' => $this->input->post('tindakan'),
	            'tk_date_update' =>  date('Y-m-d H:i:s'),
	            'tk_user_update' =>  $this->ion_auth->user()->row()->id,
	        );
	    }

	    $update = $this->kasus_model->update(array('tk_id' => $this->input->post('id')), $data);

	    if($update==1){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Update Data Kasus'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Update Data Kasus'));
	    }
	}

	private function _validate($sts_kasus) {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;

	    if($this->input->post('nama_pegawai') == '') {
	        $data['inputerror'][] = 'nama_pegawai';
	        $data['error_string'][] = 'Nama Pegawai is required';
	        $data['status'] = FALSE;
	    }

	    if($this->input->post('tindakan') == '') {
	        $data['inputerror'][] = 'tindakan';
	        $data['error_string'][] = 'Tindakan is required';
	        $data['status'] = FALSE;
	    }
	    if($sts_kasus>2 && $sts_kasus<6){
	        if($sts_kasus==3){
	            if($this->input->post('tgl_positif') == '') {
	                $data['inputerror'][] = 'tgl_positif';
	                $data['error_string'][] = 'Tanggal Positif is required';
	                $data['status'] = FALSE;
	            }else{
	                if($this->input->post('tgl_positif') > Date('Y-m-d')){
	                    $data['inputerror'][] = 'tgl_positif';
	                    $data['error_string'][] = 'Tanggal Positif tidak boleh lebih dari hari ini';
	                    $data['status'] = FALSE;
	                }else{
	                    $data['inputerror'][] = 'tgl_positif';
	                    $data['error_string'][] = '';
	                    $data['status'] = TRUE;
	                }
	            }
	        }else{
	            if($this->input->post('tgl_positif') == '') {
	                $data['inputerror'][] = 'tgl_positif';
	                $data['error_string'][] = 'Tanggal Positif is required';
	                $data['status'] = FALSE;
	            }else{
	                if($this->input->post('tgl_positif') > Date('Y-m-d')){
	                    $data['inputerror'][] = 'tgl_positif';
	                    $data['error_string'][] = 'Tanggal tidak boleh lebih dari hari ini';
	                    $data['status'] = FALSE;
	                }else{
	                    $data['inputerror'][] = 'tgl_positif';
	                    $data['error_string'][] = '';
	                    $data['status'] = TRUE;
	                }
	            }

	            if($this->input->post('tgl') == '') {
	                $data['inputerror'][] = 'tgl';
	                $data['error_string'][] = 'Tanggal is required';
	                $data['status'] = FALSE;
	            }else{
	                if($this->input->post('tgl') > Date('Y-m-d')){
	                    $data['inputerror'][] = 'tgl';
	                    $data['error_string'][] = 'Tanggal tidak boleh lebih dari hari ini';
	                    $data['status'] = FALSE;
	                }else{
	                    $data['inputerror'][] = 'tgl';
	                    $data['error_string'][] = '';
	                    $data['status'] = TRUE;
	                }
	            }
	        }
	    }

	    if($data['status'] === FALSE) {
	        echo json_encode($data);
	        exit();
	    }else{
	        $data['inputerror'][] = '';
	        $data['error_string'][] = '';
	        $data['status'] = TRUE;
	    }
	}

	public function post_curl($kd_perusahaan, $filename){
	    $directory =  $_SERVER['DOCUMENT_ROOT'].FOLDER_UPLOAD.'uploads/kasus/'.$filename;

	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_SERVICE."import_terpapar",
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
	    //return $response;
	    
	}

	public function ajax_exceladd() {
	    $kd_perusahaan = $this->input->post('kd_perusahaan');

	    if(!empty($_FILES['file_import']['name'])) {
	        $upload = $this->_do_upload_excel($kd_perusahaan);

	        $data['tkx_mc_id'] = $kd_perusahaan;
	        $data['tkx_filename'] = $upload;
	        $data['tkx_user_insert']=$this->ion_auth->user()->row()->id;
	        $data['tkx_date_insert']=date('Y-m-d H:i:s');
	        $insert = $this->kasus_model->saveexcel($data);

	        $curl = $this->post_curl($kd_perusahaan, $upload);

	        if(isset(json_decode($curl)->status)){
	            $message = json_decode($curl)->message;
	            if(json_decode($curl)->status==200){
	                echo json_encode(array("message" => $message));
	            }else{
	                $message = json_decode($curl)->message;
	                echo json_encode(array("message" => $message));
	            }
	        }else{
	            echo json_encode(array("message" => json_decode($curl)->status));
	        }
	    }else{
	        echo json_encode(array("message" => 'File Not Found'));
	    }
	}

	public function _do_upload_excel($mc_id) {
	    $config['upload_path']          = 'uploads/kasus/';
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
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."terpapar/laporan_home/".$mc_id,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);
	    $responsex = json_decode($response)->data;

	    foreach ($responsex as $res) {
	        $row = array();
	        $row[] = $res->jenis_kasus;
	        $row[] = $res->jumlah;
	        $data[] = $row;
	    }

	    $output = array(
	        "draw" =>1,
	        "recordsTotal" => 0,
	        "recordsFiltered" =>0,
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	public function ajax_list_summary_new($mc_id) {
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."terpapar/laporan_home/".$mc_id,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);
	    //print_r($response);die;
	    $responsex = json_decode($response)->data;
	  	$row="";
	    foreach ($responsex as $res) {
	        $row  .='<div class="col-lg-2 col-md-6 col-sm-6 col-12">
            <!--div style="padding:0 20px; height:25%; width:39% !important"-->
            <div class="card card-statistic-1">
                <div class="card-wrap">
		    	<div class="card-header"><b>'.$res->jenis_kasus.'</b></div>';
		    $row  .='<div class="card-body">'.$res->jumlah.'</div>';
			$row  .='</div>';
			$row  .='</div>';
			$row  .='</div>';
	    }

	    echo $row;
	}

	public function ajax_reset($kd_perusahaan) {
	    $reset =  $this->kasus_model->reset_bymcid($kd_perusahaan);

	    if($reset===true){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Reset Kasus'));
	    }else{
	        echo json_encode(array("status" => NULL, "message" => 'Gagal Reset Kasus'));
	    }
	}
}
