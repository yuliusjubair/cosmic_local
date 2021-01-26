<?php
use Treinetic\ImageArtist\lib\Image;

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

class Profile extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(array('profile_model','master_model','admin_model'));
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
    }
    
    public function secure(){
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    
    public function index($mc_id='') {
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        
        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Setting";
        $data['subtitle']="Reset Password BUMN";
        
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
        $this->load->view('profile/list_bumn',$data);
        $this->load->view('footer',$data);
    }
    
    public function ajaxbumn_list($mc_id) {
        $this->load->helper('url');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        
        $list = $this->profile_model->getbumn_datatables($mc_id);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $profile) {
            $type = 'BUMN';
            $row = array();
            $row[] = $profile->mc_name;
            $btn_edit ='<div class="button"><a class="btn btn-md btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_profile('."'".$profile->mc_id."'".','."'".$type."'".')"><i class="ace-icon fa fa-pencil bigger-120"></i> Edit</a>&nbsp;';
            $btn_delete ='<a class="btn btn-md btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_bumnprofile('."'".$profile->mc_id."'".')"><i class="ace-icon fa fa-trash bigger-120"></i> Delete</a></div>';
            
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
            
            if($profile->mc_foto){
                $row[] = '<center><img src="'.base_url('/uploads/foto_bumn/'.$profile->mc_foto).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
                
                $row[] = '
	            	<div class="button">'.
	            	$btnx_edit.''.$btnx_delete.
	            	'</div>
                    ';
            }else{
                $row[] = '(No photo)';
                $row[] = $btnx_edit;
            }
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->profile_model->countbumn_all($mc_id),
            "recordsFiltered" => $this->profile_model->countbumn_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    public function ajaxbumn_delete($mc_id) {
        $profile = $this->profile_model->getbumn_byid($mc_id);
        if(file_exists('uploads/foto_bumn/'.$profile->mc_foto) && $profile->mc_foto){
            unlink('uploads/foto_bumn/'.$profile->mc_foto);
        }
        $update = $this->profile_model->bumnupdate(
            array('mc_id' => "$mc_id"), array('mc_foto' =>NULL)
            );
        if($update==1){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Foto'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Delete Foto'));
        }
    }
    
    public function ajaxbumn_edit($id) {
        $data = $this->profile_model->getbumn_byid($id);
        echo json_encode($data);
    }
    
    public function ajaxbumn_update() {
        if(!empty($_FILES['foto_profile']['name'])) {
            $image_info = getimagesize($_FILES['foto_profile']["tmp_name"]);
            $image_width = $image_info[0];
            $image_height = $image_info[1];
            
            if($image_width!=$image_height){
                echo json_encode(array("status" => 500, "message" => 'Dimensi Foto harus 750x750'));
            }else{
                $upload = $this->_do_upload();
                $profile = $this->profile_model->getbumn_byid($this->input->post('modal_mc_id'));
                
                if(file_exists('uploads/foto_bumn/'.$profile->mc_foto) && $profile->mc_foto!=''){
                    unlink('uploads/foto_bumn/'.$profile->mc_foto);
                }
                $data['mc_foto'] = $upload;
                
                $this->profile_model->bumnupdate(array(
                    'mc_id' => $this->input->post('modal_mc_id')), $data);
                
                $path_foto_bumn = 'uploads/foto_bumn/'.$upload;
                $path_foto_profile = 'uploads/profile/'.$upload;
                $bumnprofile = $this->profile_model->userbumn_bymcid($this->input->post('modal_mc_id'));
                copy($path_foto_bumn, $path_foto_profile);
                $data_bp['foto'] = $upload;
                //var_dump($bumnprofile);die;
                foreach ($bumnprofile as $bp){
                    $profile = $this->profile_model->get_byid($bp->user_id);
                    if(file_exists('uploads/profile/'.$bp->foto) && $bp->foto!=''){
                        unlink('uploads/profile/'.$bp->foto);
                    }
                    $this->profile_model->update(array(
                        'id' => $bp->user_id), $data_bp);
                }
                
                echo json_encode(array("status" => 200, "message" => 'Berhasil Update Foto BUMN'));
            }
        }else{
            echo json_encode(array("status" => 500, "message" => 'Foto Tidak Ada'));
        }
    }
    
    private function _do_upload() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);
        
        $config['upload_path']          = 'uploads/foto_bumn/';
        $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
        $config['max_size']             = 250; //set max size allowed in Kilobyte
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
        
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('foto_profile')) {
            $data['inputerror'][] = 'foto_profile';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }
    
    public function user($mc_id='') {
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        
        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Setting";
        $data['subtitle']="Upload Foto Profile";
        
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
        $this->load->view('profile/list_user',$data);
        $this->load->view('footer',$data);
    }
    
    public function ajax_list($mc_id) {
        $this->load->helper('url');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        
        $list = $this->profile_model->get_datatables($mc_id);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $profile) {
            $type='USER';
            $row = array();
            $row[] = $profile->v_username;
            $row[] = $profile->v_first_name;
            
            $btn_edit ='<div class="button"><a class="btn btn-md btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_profile('."'".$profile->v_user_id."'".','."'".$type."'".')"><i class="ace-icon fa fa-pencil bigger-120"></i> Edit</a>&nbsp;';
            $btn_delete ='<a class="btn btn-md btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_profile('."'".$profile->v_user_id."'".')"><i class="ace-icon fa fa-trash bigger-120"></i> Delete</a></div> ';
            
            if($update==1){
                $btnx_edit =$btn_edit;
            }else{
                $btnx_edit ='';
            }
            
            if($delete==1){
                $btnx_delete ='';//$btn_delete;
            }else{
                $btnx_delete ='';
            }
            
            if($profile->v_foto){
                $row[] = '<center><img src="'.base_url('/uploads/profile/'.$profile->v_foto).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
                if($profile->v_group_id!=2){
                    $row[] = '
	            	<div class="button">'.
	            	$btnx_edit.''.$btnx_delete.
	            	'</div>
                    ';
                }else{
                    $row[] = '';
                }
            }else{
                $row[] = '(No photo)';
                $row[] = $btnx_edit;
            }
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->profile_model->count_all($mc_id),
            "recordsFiltered" => $this->profile_model->count_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    public function ajax_edit($id) {
        $data = $this->profile_model->get_byid($id);
        //var_dump($data);die;
        echo json_encode($data);
    }
    
    public function ajax_update() {
        // 	    $data['email'] = $this->input->post('modal_email');
        // 	    $data['no_hp'] = $this->input->post('modal_hp');
        // 	    $data['divisi'] = $this->input->post('modal_divisi');
        if(!empty($_FILES['foto_profile']['name'])) {
            $image_info = getimagesize($_FILES['foto_profile']["tmp_name"]);
            $image_width = $image_info[0];
            $image_height = $image_info[1];
            
            if($image_width!=$image_height){
                echo json_encode(array("status" => 500, "message" => 'Dimensi Foto harus 1:1'));
            }else{
                $upload = $this->_do_upload_user();
                $profile = $this->profile_model->get_byid($this->input->post('modal_id'));
                if(file_exists('uploads/profile/'.$profile->foto) && $profile->foto!=''){
                    unlink('uploads/profile/'.$profile->foto);
                }
                
                $data['foto'] = $upload;
                
                $this->profile_model->update(array(
                    'id' => $this->input->post('modal_id')), $data);
                echo json_encode(array("status" => 200, "message" => 'Berhasil Update Foto & Profile'));
            }
        }else{
            $this->profile_model->update(array(
                'id' => $this->input->post('modal_id')), $data);
            echo json_encode(array("status" => 500, "message" => 'Berhasil Update Profile'));
        }
    }
    
    public function ajax_delete($id) {
        $profile = $this->profile_model->get_byid($id);
        
        if(file_exists('uploads/profile/'.$profile->foto)){
            unlink('uploads/profile/'.$profile->foto);
        }
        
        $update = $this->profile_model->update(
            array('id' => $id), array('foto' =>NULL)
            );
        if($update==1){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Foto'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Delete Foto'));
        }
    }
    
    private function _do_upload_user() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);
        
        $config['upload_path']          = 'uploads/profile/';
        $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
        $config['max_size']             = 250; //set max size allowed in Kilobyte
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
        
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('foto_profile')) {
            $data['inputerror'][] = 'foto_profile';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
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
    
    public function ajaxall_edit($id, $type) {
        if($type=='BUMN'){
            $data = $this->profile_model->get_byid($id);
        }else{
            $data = $this->profile_model->getbumn_byid($id);
        }
        echo json_encode($data);
    }
    
    public function ajaxbumn_resetlist($mc_id) {
        $this->load->helper('url');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        
        $list = $this->profile_model->getbumn_datatables($mc_id);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $profile) {
            $type = 'BUMN';
            $row = array();
            $row[] = $profile->mc_name;
            $row[] = $profile->username;
            
            if($update==1){
                $row[] = '  <a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Reset"
                        onclick="reset_password('."'".$profile->id."'".','."'".$profile->first_name."'".')">
                        <i class="ace-icon fa fa-refresh bigger-120"></i>Reset</a>';
            }else{
                $row[] = '';
            }
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->profile_model->countbumn_all($mc_id),
            "recordsFiltered" => $this->profile_model->countbumn_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    public function picfo($mc_id='') {
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        
        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Setting";
        $data['subtitle']="Konfigurasi PIC & FO";
        
        $group = $this->ion_auth->get_users_groups()->row()->id;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 8){
            $mc_id =  $data['user']->mc_id;
        }else{
            $mc_id = $mc_id;
        }
        $data["mst_group"]=$this->master_model->mst_users_group_pic_fo()->result();
        $data['group'] = $group;
        $data['mc_id'] = $mc_id;
        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
        
        //if group is admin
        $data['company_bumn'] = $this->master_model->company_bygroupcompany(1);
        $data['company_nonbumn'] = $this->master_model->company_bygroupcompany(2);
        
        $data["menu"]=$this->master_model->menus($group);
        $data["create"]=$this->master_model->one_groups($group)->c;
        $this->load->view('header',$data);
        $this->load->view('profile/list_picfo',$data);
        $this->load->view('footer',$data);
    }
    
    public function ajaxuser_resetlist($mc_id) {
        $this->load->helper('url');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        
        $list = $this->profile_model->get_datatables($mc_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $profile) {
            $type='USER';
            $row = array();
            $row[] = $profile->v_username;
            $row[] = $profile->v_first_name;
            $row[] = $profile->v_group_name;
            //$row[] = $profile->v_mpm;
            
            if($profile->v_group_id!=2){
                if($profile->v_mpm=='' || $profile->v_group_name==''){
                    $btn_reset = '<div class="button"> <a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                            onclick="reset_password('."'".$profile->v_user_id."'".')">
                            <i class="ace-icon fa fa-refresh bigger-120"></i>Reset</a>';
                    $btn_delete = '<a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_user('."'".$profile->v_user_id."'".')"><i class="ace-icon fa fa-trash bigger-120"></i> Delete</a></div>';
                    
                    if($update==1){
                        $btnx_reset = $btn_reset;
                    }else{
                        $btnx_reset = '';
                    }
                    
                    if($delete==1){
                        $btnx_delete = '';//$btn_delete;
                    }else{
                        $btnx_delete = '';
                    }
                    
                    $row[]=$btnx_reset.' '.$btnx_delete;
                }else{
                    $btn_reset = '<div class="button"> <a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                            onclick="reset_password('."'".$profile->v_user_id."'".')">
                            <i class="ace-icon fa fa-refresh bigger-120"></i>Reset</a>';
                    
                    if($update==1){
                        $row[] = $btn_reset;
                    }else{
                        $row[] = '';
                    }
                }
            }else{
                $row[] = '';
            }
            $row[] = "<a class='btn btn-lg btn-danger' href='javascript:void(0)'' title='Detail User'
                        onclick=\"window.open('".base_url().'profile/detail_user/'.$profile->v_user_id."','_self');\"><i class='fa fa-info-circle fa-2' aria-hidden='true'></i></a>";
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->profile_model->count_all($mc_id),
            "recordsFiltered" => $this->profile_model->count_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    public function ajax_deleteuser($id) {
        $delete1 =  $this->profile_model->deleteuser_byid($id);
        $delete2 =  $this->profile_model->deleteusergroup_byuserid($id);
        
        if($delete1){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete User'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Delete User'));
        }
    }
    
    public function detail_user($id){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['row'] = $this->profile_model->get_byid($id);
        $data['perimeter'] = $this->profile_model->get_perimeterbynik($id);
        $data['cari']="";
        $data['title']="Setting";
        $data['subtitle']="Detail User PIC/FO";
        $data['error']="";
        //$data['menu_hide']="yes";
        $group = $this->ion_auth->get_users_groups()->row()->id;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 5){
            $mc_id = $data['user']->mc_id;
        }else{
            $mc_id = '';
        }
        
        $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        $data["create"]=$this->master_model->one_groups($group)->c;
        $data["update"]=$this->master_model->one_groups($group)->u;
        $data["delete"]=$this->master_model->one_groups($group)->d;
        
        $this->load->view('header',$data);
        $this->load->view('profile/detail',$data);
        $this->load->view('footer',$data);
    }
    
    public function ajax_update_nik() {
        $this->_validate();
        date_default_timezone_set('Asia/Jakarta');
        $data = array();
        //var_dump($this->input->post('kd_perusahaan_modal'));die;
        $nik = $this->input->post('nik');
        $nik_asli = $this->input->post('nik_asli');
        $user_id = $this->ion_auth->user()->row()->id;
        $username = $this->ion_auth->user()->row()->username;
        
        $this->db->query("UPDATE app_users SET username='$nik' where username='$nik_asli'");
        $this->db->query("UPDATE master_perimeter_level SET mpml_me_nik='$nik' where mpml_me_nik='$nik_asli'");
        $this->db->query("UPDATE master_perimeter_level SET mpml_pic_nik='$nik' where mpml_pic_nik='$nik_asli'");
        $this->db->query("UPDATE transaksi_aktifitas SET ta_nik='$nik' where ta_nik='$nik_asli'");
        echo json_encode(array("status" => 200, "message" => 'Berhasil Update NIK'));
        
    }
    
    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        
        if($this->input->post('nik') == '') {
            $data['inputerror'][] = 'nik';
            $data['error_string'][] = 'NIK is required';
            $data['status'] = FALSE;
        }else{
            $cnt_user = $this->admin_model->count_username($this->input->post('nik'));
            if(count($cnt_user) > 0){
                $data['inputerror'][] = 'nik';
                $data['error_string'][] = 'NIK is already taken';
                $data['status'] = FALSE;
            }
            else{
                $data['inputerror'][] = 'nik';
                $data['error_string'][] = '';
            }
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
    
    public function add_picfo() {
        date_default_timezone_set('Asia/Jakarta');
        //var_dump('tes');die;
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);
        $user_id = $this->ion_auth->user()->row()->id;
        $mc_id = $this->input->post('company_id');
        $sql = $this->db->query("SELECT mc_msc_id from master_company where mc_id='$mc_id'")->row();
        $msc_id = isset($sql->mc_msc_id)?$sql->mc_msc_id:0;
        //cek duplikasi nik/username
        $cek = $this->db->query("SELECT * from app_users where username='".$this->input->post('modal_username')."'");
        if($cek->num_rows()>0){
            echo "<script>
				alert('Username Already Exists');
				window.location.href='../profile/picfo';
				</script>";
            die;
        }
        
        $data = array(
            'username' => $this->input->post('modal_username'),
            'first_name' => $this->input->post('modal_name'),
            'mc_id' => $mc_id,
            'msc_id' => $msc_id,
            'user_insert' => $user_id,
            'date_insert' => date('Y-m-d H:i:s')
        );
        $this->db->insert('app_users', $data);
        
        //$insert_id = $this->profile_model->save($data);
        $cek = $this->db->query("SELECT max(id) id_max from app_users")->row();
        //echo $insert_id;die;
        if($cek->id_max)
        {
            $datax['user_id'] = $cek->id_max;
            $datax['group_id']=$this->input->post('modal_groups');
            
            $this->db->insert('app_users_groups', $datax);
            
            //echo json_encode(array("status" => 200, "message" => 'Berhasil Tambah Data'));
            echo "<script>
				alert('Berhasil Tambah Data');
				window.location.href='../profile/picfo';
				</script>";
        }else{
            //echo json_encode(array("status" => 500, "message" => 'Gagal Update Data'));
            echo "<script>
				alert('Gagal Update Data');
				window.location.href='../profile/picfo';
				</script>";
        }
    }
}