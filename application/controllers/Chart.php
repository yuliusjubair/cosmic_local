<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
	}	

	public function index() {
	    $data['pic'] = $this->master_model->get_chart_pic();
	    $this->load->view('dashmin/chart',$data);
	}
}
