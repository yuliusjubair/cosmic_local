<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashrs extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('rumahsinggah_model','master_model','profile_model'));
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
	    $data['title']="Rumah Singgah";
	    $data['subtitle']="Seluruh Rumah Singgah BUMN";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $data['group'] =$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
		}
	    $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
	    if($group == 1 ){
	        $data['company'] = $this->master_model->company_bygroupcompany(1);
	    }else{
	        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    }
	    $data["menu"]=$this->master_model->menus($group);
	    $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    $data['pic'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,3);
	    $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
	    $data['kriteria']=$this->master_model->mst_kriteria_orang($cari='');
	    $data['fasilitas_rumah']=$this->master_model->mst_fasilitas_rumah()->result();
	    $data['count'] = $this->rumahsinggah_model->count_all($mc_id=NULL);
	    $kota = $this->master_model->name_mst_kabupaten($cari='')->result();
	    
	    $k = array();
	    foreach ($kota as $key => $value) {
	    	$k[] = $value->tag;
	    }

	    $comp = $this->master_model->company_bygroupcompany(1)->result();
		$c = array();
	    foreach ($comp as $key => $value) {
	    	$c[] = $value->mc_name;
	    }

	    $data['kota'] = json_encode($k, true);
	    $data['comp_name'] = json_encode($c, true);
	    //die;
	    $this->load->view('header',$data);
	    $this->load->view('rumahsinggah/dashboard',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_list($mc_id=NULL) {
		$kota = isset($_POST['kota'])?$_POST['kota']:'';
		$nama_bumn = isset($_POST['bumn'])?$_POST['bumn']:'';
		$kapasitas = isset($_POST['kapasitas'])?$_POST['kapasitas']:'';
		//print_r($kota);die;
	    $this->load->helper('url');	    
	    $list = $this->rumahsinggah_model->get_datatables_filter($kota, $nama_bumn, $kapasitas);
	    //echo $this->db->last_query();die;
	    $data = array();
	    $no = 0;
	    foreach ($list as $sos) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $sos->nama_rumahsinggah;
	        $row[] = $sos->mkab_name;
	        $row[] = $sos->mc_name;
	        $row[] = $sos->kapasitas." Orang";
	        $row[] = $sos->id;
	        /*$row[] = $sos->kapasitas;
	        $row[] = $sos->ruangan_available;
	        $row[] = $sos->user_insert;
	        $row[] = $sos->date_insert;
	        if($sos->file){
	            $row[] = '<center><img src="'.base_url('/uploads/sosialisasi/'.$sos->ts_mc_id.'/'.$sos->ts_file1).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
	        }else{
	            $row[] = '(No photo)';
	        }*/
	        
	      /*  $row[] = '  <a class="btn btn-md btn-primary" href="javascript:void(0)" title="Edit"
                        onclick="edit_rumahsinggah('."'".$sos->id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>
                        <a class="btn btn-md btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_rumahsinggah('."'".$sos->id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';*/
              $row[] = "<a class='' href='javascript:void(0)'' title='Detail Rumah Singgah'
                        onclick=\"window.open('".base_url().'dashrs/detail_rumahsinggah/'.$sos->id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";

	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => 1,
	        "recordsTotal" => $this->rumahsinggah_model->count_all($mc_id),
	        "recordsFiltered" => $this->rumahsinggah_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	public function detail_rumahsinggah($id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['row'] = $this->rumahsinggah_model->get_byid($id);
	    $data['cari']="";
	    $data['title']="Rumah Singgah";
	    $data['subtitle']="Detail Rumah Singgah";
	    $data['error']="";
	    $data['menu_hide']="yes";

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
	    $data["update"]=0;
	    $data["delete"]=0;
	    $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    $data['pic'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,3);
	    $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
	    $data['kriteria']=$this->master_model->mst_kriteria_orang($cari='');
	    $data['fasilitas_rumah']=$this->master_model->mst_fasilitas_rumah()->result();

	    $this->load->view('header',$data);
	    $this->load->view('rumahsinggah/detail',$data);
	    $this->load->view('footer',$data);
	}
}