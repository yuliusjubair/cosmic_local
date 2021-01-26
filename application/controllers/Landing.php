<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('master_model'));
        $this->load->library('mxencryption');
	}

    public function index(){
        $data['company'] = $this->master_model->company(1,NULL,NULL);
   
        $this->load->view('landing/landing_page',$data);
    }

    public function link_reportperimeter(){
        $mpm_id = $this->input->post('perimeter_id');
        $crypt = new mxencryption();
        $enc_mpmid = $crypt->encrypt(strval($mpm_id));
        
        $isi_qrcode  = 'http:'.base_url().'reportprotokol/report/'.$enc_mpmid;
        echo json_encode($isi_qrcode);
    }
}
