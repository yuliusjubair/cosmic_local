<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('admin_model','master_model'));
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
	}

	public function secure(){
	    if (!$this->ion_auth->logged_in()) {
	        redirect('auth/login', 'refresh');
	    }
	    if (!$this->ion_auth->is_admin()) {
	        $this->session->set_flashdata('message', 'You must be an admin to view this page');
	        redirect($this->session->userdata('redirect_url'), 'refresh');
	    }
	}

	public function list_groups() {
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();

	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Master";
	    $data['subtitle']="List Groups";

	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);

	    $this->load->view('header',$data);
	    $this->load->view('master/list_groups',$data);
	    $this->load->view('footer',$data);
	}

	public function data_list_groups($cari="") {
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();

	    $query=$this->master_model->mst_groups($cari);
	    echo "<rows>";
	    $nomor=1;
	    foreach( $query->result() as $row ){
	        $url_edit="<![CDATA[<button class='btn btn-sm btn-white btn-default'
                type='button' onclick=\"openWindowDetail('". $row->id ."')\" >
                <span class='blue'><i class='ace-icon fa fa-pencil bigger-120'>
                </i></span></button>]]>";
	        $url_del="<![CDATA[<button class='btn btn-sm btn-white btn-default'
                type='button' onclick=\"delete_data('". $nomor ."','".$row->id."');\" >
                <span class='red'><i class='ace-icon fa fa-trash-o bigger-120'>
                </i></span></button>]]>";
	        echo "<row>";
	        echo "<cell>$nomor</cell>";
	        echo "<cell>".htmlspecialchars($row->id)."</cell>";
	        echo "<cell>".htmlspecialchars($row->description)."</cell>";
	        echo "<cell>".$url_edit.$url_del."</cell>";
	        echo "</row>\n";
	        $nomor++;
	    }
	    echo "</rows>";
	}

	public function popup_groups_form($id=0) {
	    $this->secure();
	    $data['key_id']=$id;
	    $groups=$this->master_model->onegroups($id);
	    $groupsrow=null;

	    if ($groups->num_rows() > 0){
	        $groupsrow = $groups->row();
	    }
	    $data['groups']=$groupsrow;
	    $data['cari']="";

	    $data['title']="";
	    $data['subtitle']="";
	    $data['error']="";
	    $this->load->view('master/form_groups',$data);
	}

	public function save_groups() {
	    $this->secure();
	    $user = $this->ion_auth->user()->row()->id;
	    $id = $this->input->post("id");
	    $name = $this->input->post("name");

	    $result = $this->master_model->save_groups($id,$name,$user);
	    redirect('/master/list_groups', 301);
	}

	public function del_groups() {
	    $this->secure();
	    $id = $this->input->post("id");
	    $this->master_model->del_groups($id);
	    echo 'ok';
	}

	function get_company_bygroupcompany(){
		 $group_company = $this->input->get('group_company');
		 $data =$this->master_model->company_bygroupcompany($group_company)->result();
		 echo json_encode($data);
	}
	
	function get_onecompany($kd_perusahaan){
	    $data =$this->master_model->one_company($kd_perusahaan)->result();
	    echo json_encode($data);
	}
}
