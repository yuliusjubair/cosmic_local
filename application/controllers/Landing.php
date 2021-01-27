<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('master_model'));
	}

    public function index(){
        $data['company'] = $this->master_model->company(1,NULL,NULL);

        $this->load->view('landing/landing_page',$data);
    }

    
}
