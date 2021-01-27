<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perimeter extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('perimeter_model','master_model'));
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

	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Upload Perimeter";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
			$data['group']=$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    $this->load->view('header',$data);
	    $this->load->view('perimeter/list_perimeter',$data);
	    $this->load->view('footer',$data);
	}



	public function ajax_add() {
	    $kd_perusahaan = $this->input->post('kd_perusahaan');
	    $curl = curl_init();

	    $perimeter = $this->perimeter_model->get_by_id($kd_perusahaan);

	    if($perimeter!=NULL){
    	    if(file_exists('uploads/perimeter/'.$perimeter->tbpmx_filename && $perimeter->tbpmx_filename) ){
    	        unlink('uploads/perimeter/'.$perimeter->tbpmx_filename);
    	        $this->perimater_model->delete_by_id($kd_perusahaan);
    	    }
	    }

	    if(!empty($_FILES['file_import']['name'])) {
	        $upload = $this->_do_upload_excel($kd_perusahaan);
	       // var_dump($upload);die;
	        $data['tbpmx_mc_id'] = $kd_perusahaan;
	        $data['tbpmx_filename'] = $upload;
	        $insert = $this->perimeter_model->save($data);

	        $curl = $this->post_curl($kd_perusahaan, $upload);

	        if(isset(json_decode($curl)->status)){
	           $message = json_decode($curl)->message;
	           if(json_decode($curl)->status==200){
    	            $message = json_decode($curl)->message;

    	            $datax['tbpt_mc_id'] = $kd_perusahaan;
    	            $datax['tbpt_mpt_id'] = 6;
    	            $datax['tbpt_filename'] = $upload;
    	            $datax['tbpt_user_insert']=$this->ion_auth->user()->row()->id;
    	            $datax['tbpt_date_insert']=date('Y-m-d H:i:s');

    	            $this->perimeter_model->delete_tbpt($data['tbpmx_mc_id'], $datax['tbpt_mpt_id']);
    	            $insert_tbpt = $this->perimeter_model->save_tbpt($datax);

    	            echo json_encode(array("message" => $message));
    	        }else{
    	            $message = json_decode($curl)->message;
    	            echo json_encode(array("message" => $message));
    	            //echo json_encode(array("message" => 'Gagal Upload'));
    	        }
	        }else{
	            echo json_encode(array("message" => json_decode($curl)->status));
	        }
	    }else{
	        echo json_encode(array("message" => 'File Not Found'));
	    }
	}

	public function post_curl($kd_perusahaan, $filename){
	    $directory =   $_SERVER['DOCUMENT_ROOT'].FOLDER_UPLOAD.'uploads/perimeter/'.$filename;

	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_SERVICE."import",
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

	public function _do_upload_excel($mc_id) {
	    $config['upload_path']          = 'uploads/perimeter/';
	    $config['allowed_types']        = 'xlsx|xls';
	    $config['max_size']             = 10*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

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

	public function ajax_list($mc_id) {
	    $this->load->helper('url');

	    $list = $this->perimeter_model->get_datatables($mc_id);

	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $perimeter) {
	        if($perimeter->status==0){
	            $status='Progress';
	        }else if($perimeter->status==1){
	            $status='Berhasil Parsing';
	        }else{
	            $status='Gagal Parsing';
	        }
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $perimeter->perimeter;
	        $row[] = $perimeter->nik_pic;
	        $row[] = $perimeter->pic;
	        $row[] = $perimeter->level;
	        $row[] = $perimeter->nik_fo;
	        $row[] = $perimeter->fo;
	        $row[] = $status;
			$row[] = $perimeter->keterangan_error;
	        $row[] = $perimeter->created_at;
	        $row[] = $perimeter->provinsi;
	        $row[] = $perimeter->kota;
	        $data[] = $row;
	    }

	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->perimeter_model->count_all($mc_id),
	        "recordsFiltered" => $this->perimeter_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
}
