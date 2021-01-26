<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('admin_model');
		$this->load->model('Ion_auth_model');
		$this->load->model('profile_model');
		$this->load->database();
		$this->load->helper(array('form', 'url','directory','path'));	  
	}
	
	public function index() {
		$this->users();
	}
	
	public function secure(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
// 		if (!$this->ion_auth->is_admin()) {
// 			$this->session->set_flashdata('message', 'You must be an admin to view this page');
// 			redirect($this->session->userdata('redirect_url'), 'refresh');
// 		}
	}
	
	public function secure_admin(){
	    if (!$this->ion_auth->logged_in()) {
	        redirect('auth/login', 'refresh');
	    }
		if (!$this->ion_auth->is_admin()) {
			$this->session->set_flashdata('message', 'You must be an admin to view this page');
			redirect($this->session->userdata('redirect_url'), 'refresh');
		}
	}
	
	public function users() {
	    $this->secure();
		$data['user'] = $this->ion_auth->user()->row();
		$data['cari']="";
		$data['unitx']="";
		$data['title']="Setting";
		$data['subtitle']="Reset Password";
		$group = $this->ion_auth->get_users_groups()->row()->id;
		$sektor = $this->ion_auth->user()->row()->msc_id;
		if($group > 1 && $group < 8){
		    $mc_id =  $data['user']->mc_id;
		}else{
		    $mc_id = $mc_id;
		}
		
		$data['group'] = $group;
		$data['mc_id'] = $mc_id;
		$data['company'] = $this->master_model->company($group, $sektor, $mc_id);
		
		//if group is admin
		$data['company_bumn'] = $this->master_model->company_bygroupcompany(1);
		$data['company_nonbumn'] = $this->master_model->company_bygroupcompany(2);
		
		$data["menu"]=$this->master_model->menus($group);
		$data["create"]=$this->master_model->one_groups($group)->c;
		
		$this->load->view('header',$data);
		$this->load->view('admin/list_users',$data);
		$this->load->view('footer',$data);
	}
	
	public function ajax_list() {
	    $this->load->helper('url');
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    
	    $group_company = $this->input->get(group_company);
	    if ((isset($group_company)) && $group_company<3) {
	        $list = $this->admin_model->get_datatables($group_company);
	    } else {
	        $list = $this->admin_model->get_datatables(1);
	    }
	  
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $user) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $user->first_name;
	        $row[] = $user->username;
	        if($update==1){
	           $row[] = '  <a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="reset_password('."'".$user->id."'".','."'".$user->first_name."'".' )">
                        <i class="ace-icon fa fa-refresh bigger-120"></i>Reset</a>';
	        }else{
	            $row[] = '';
	        }
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->admin_model->count_all($group_company),
	        "recordsFiltered" => $this->admin_model->count_filtered($group_company),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	public function ajax_reset($id) {
	    $data = array(
	        'password' => password_hash('P@ssw0rd',true)
	    );
	    
	    $update =  $this->admin_model->reset_byid(array('id' => $id), $data);

	    if($update===1){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Reset Password'));
	    }else{
	        echo json_encode(array("status" => NULL, "message" => 'Gagal Reset Password'));
	    }
	}
	
	public function groups() {
	    $this->secure_admin();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['cari']="";
	    $data['unitx']="";
	    $data['title']="Setting";
	    $data['subtitle']="Role";
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    
	    $data["crud"]=$this->master_model->one_groups($group);
	    $data["menu"]=$this->master_model->menus($group);
	    
	    $this->load->view('header',$data);
	    $this->load->view('admin/list_groups',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_list_groups() {
	    $this->load->helper('url');
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    
	    $list = $this->admin_model->get_datatables_groups();
	    
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $groups) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $groups->name;
	        $row[] = $groups->description;
	        $row[] = $groups->c;
	        $row[] = $groups->r;
	        $row[] = $groups->u;
	        $row[] = $groups->d;
	        $row[] = '';
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->admin_model->count_all_groups(),
	        "recordsFiltered" => $this->admin_model->count_filtered_groups(),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	public function manageusers() {
	    $this->secure_admin();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['cari']="";
	    $data['unitx']="";
	    $data['title']="Setting";
	    $data['subtitle']="Manage User";
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    
	    $data["crud"]=$this->master_model->one_groups($group);
	    $data["menu"]=$this->master_model->menus($group);
	    $data["mst_group"]=$this->master_model->mst_users_group()->result();
	    $data["mst_company"]=$this->master_model->mst_company($cari='')->result();
	    $data["mst_sektor"]=$this->master_model->mst_sektor($cari='')->result();
	    
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    
	    $this->load->view('header',$data);
	    $this->load->view('admin/list_manageusers',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_manageusers_list() {
	    $this->load->helper('url');
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    
	    $list = $this->admin_model->get_datatables_musers();
	    
	    $data = array();
	    $no = $_POST['start'];
	    $jml=1;
	    foreach ($list as $user) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $user->v_username;
	        $row[] = $user->v_first_name;
	        $row[] = $user->v_description;
	        $btn_edit ='<a class="btn btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit"
                        onclick="edit_manageusers('."'".$user->v_user_id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
	        $btn_delete ='<a class="btn btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_manageusers('."'".$user->v_user_id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
	        
	        if($update==1){ $btnx_edit =$btn_edit; }else{ $btnx_edit =''; }
	        if($delete==1){ $btnx_delete =$btn_delete; }else{  $btnx_delete =''; }
	        
	        $row[] = ' <div class="button">'.$btnx_edit.' '.$btnx_delete.'</button>';
	        $data[] = $row;
	        $jml++;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->admin_model->count_all_musers(),
	        "recordsFiltered" => $jml,//$this->admin_model->count_filtered_musers(),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	public function ajax_manageusers_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    $group = $this->input->post('modal_groups');
	    $this->_validate($group, 'insert');

	    $user_id = $this->ion_auth->user()->row()->id;
	    $username = $this->input->post('modal_username');
	    $password = $this->input->post('modal_password');
	    $name = $this->input->post('modal_name');
	    $mc_id = $this->input->post('modal_company');
	    $ms_id = $this->input->post('modal_sektor');
	    
	    $save = $this->admin_model->save_users($id=0, $username,
	        $password, $name, $user_id, $mc_id, $ms_id, $group);
	    
	    if($save > 0){
	        $save_groups = $this->admin_model->insert_users_groups($save, $group);
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Add User'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Add User'));
	    }
	}
	
	private function _validate($role, $iu) {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;

	    if($iu == 'insert'){
    	    if($this->input->post('modal_username') == '') {
    	        $data['inputerror'][] = 'modal_username';
    	        $data['error_string'][] = 'Username is required';
    	        $data['status'] = FALSE;
    	    }else{
    	        $cnt_user = $this->admin_model->count_username($this->input->post('modal_username'));
    	        if(count($cnt_user) > 0){
    	            $data['inputerror'][] = 'modal_username';
    	            $data['error_string'][] = 'Username is already taken';
    	            $data['status'] = FALSE;
    	        }
    	        else{
    	            $data['inputerror'][] = 'modal_username';
    	            $data['error_string'][] = '';
    	        }
    	    }
	    
    	    if($this->input->post('modal_password') == '') {
    	        $data['inputerror'][] = 'modal_password';
    	        $data['error_string'][] = 'Password is required';
    	        $data['status'] = FALSE;
    	    }else{
    	        $l_password =  strlen($this->input->post('modal_password'));
    	        if($l_password < 6){
    	            $data['inputerror'][] = 'modal_password';
    	            $data['error_string'][] = 'minimum password length is 6 characters';
    	            $data['status'] = FALSE;
    	        }
    	        else{
    	            $data['inputerror'][] = 'modal_password';
    	            $data['error_string'][] = '';
    	        }
    	    }
	    }
	    
	    if($this->input->post('modal_name') == '') {
	        $data['inputerror'][] = 'modal_name';
	        $data['error_string'][] = 'Name is required';
	        $data['status'] = FALSE;
	    }
	    else{
	        $data['inputerror'][] = 'modal_name';
	        $data['error_string'][] = '';
	    }

	    if($role == 2 && $this->input->post('modal_company') == '0') {
            $data['inputerror'][] = 'modal_company';
            $data['error_string'][] = 'Company is required';
            $data['status'] = FALSE;
	    }
	    else{
	        $data['inputerror'][] = 'modal_company';
	        $data['error_string'][] = '';
	    }
	    //var_dump($this->input->post('modal_sektor'));die;
	    if($role == 5 && $this->input->post('modal_sektor') == '0'){
            $data['inputerror'][] = 'modal_sektor';
            $data['error_string'][] = 'Sektor is required';
            $data['status'] = FALSE;
	    }
	    else{
            $data['inputerror'][] = 'modal_sektor';
            $data['error_string'][] = '';
        }
	
	    if($data['status'] == FALSE) {
	        echo json_encode($data);
	        exit();
	    }else{
	        $data['error_string'] = array();
	        $data['inputerror'] = array();
	        $data['status'] = TRUE;
	    }
	}
	
	public function ajax_manageusers_delete($id) {
        $delete1 =  $this->profile_model->deleteuser_byid($id);
        $delete2 =  $this->profile_model->deleteusergroup_byuserid($id);
        
        if($delete1){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete User'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Delete User'));
        }
    }
    
    public function ajax_manageusers_edit($id) {
        $mu = $this->admin_model->getmanageusers_byid($id)->row();
  
        $data = array(
            "v_user_id" => $mu->v_user_id,
            "v_username" => $mu->v_username,
            "v_first_name" => $mu->v_first_name,
            "v_mc_id" => $mu->v_mc_id,
            "v_group_id" => $mu->v_group_id,
            "v_description" => $mu->v_description,
            "v_ms_id" => $mu->v_ms_id,
            "v_ms_name" => $mu->v_ms_name
        );

        echo json_encode($data);
    }
    
    public function ajax_manageusers_update() {
        date_default_timezone_set('Asia/Jakarta');
        $group = $this->input->post('modal_groups');
        $this->_validate($group,'update');
        
        $user_id = $this->ion_auth->user()->row()->id;
        $id = $this->input->post('modal_id');
        $username = $this->input->post('modal_username');
        $password = $this->input->post('modal_password');
        $name = $this->input->post('modal_name');
        $mc_id = $this->input->post('modal_company');
        $ms_id = $this->input->post('modal_sektor');
        
        $save = $this->admin_model->save_users($id, $username,
            $password, $name, $user_id, $mc_id, $ms_id, $group);
        
        if($save > 0){
            $save_groups = $this->admin_model->update_users_groups($save, $group);
            echo json_encode(array("status" => 200, "message" => 'Berhasil Update User'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Update User'));
        }
    }
}
