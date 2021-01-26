<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('company_model','master_model'));
        $this->load->database();	  
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
	    $data['title']="Company";
	    $data['subtitle']="List Company";
	    
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);

	    $this->load->view('header',$data);
	    $this->load->view('company/list_company',$data);
	    $this->load->view('footer',$data);
	}
	
	function get_company() {
	    $list = $this->company_model->get_company();
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $field) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $field->mc_id;
	        $row[] = $field->mc_code;
	        $row[] = $field->mc_name;
	        $row[] = $field->mc_name2;
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->company_model->count_all(),
	        "recordsFiltered" => $this->company_model->count_filtered(),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
}