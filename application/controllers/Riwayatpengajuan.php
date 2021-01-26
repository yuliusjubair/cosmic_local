<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayatpengajuan extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('master_model',  'dashmin_model',
            'Companynonbumn_model','Companyatestasi_model','Companysertifikasi_model'
            ,'mperimeter_model'
        ));
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
	    $data['title']="Produk Layanan";
	    $data['subtitle']="Riwayat Pengajuan Layanan";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $data['group']=$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '0000';
	    }
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	
	    $data["menu"]=$this->master_model->menus($group);

	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('product/list_riwayatpengajuan',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_card_riwayatpengajuan($mc_id) {
	    $responsex = $this->Companyatestasi_model->getlistcard_riwayatpengajuan_bymcid($mc_id);
	    $output ='';
	    foreach ($responsex->result() as $res) {
	        $output .='
                <div class="content col-lg col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                <div class="card-wrap">';
	        $output .='<div class="card-header">'.$res->judul.'</div>';
	        $output .='<div class="card-body">'.$res->jml.'</div>';
	        $output .='
                </div>
                </div>
                </div>';
	    }
	    echo $output;
	}
	
	function list_riwayatpengajuan($mc_id) {
	    $this->load->helper('url');
	    $list = $this->Companyatestasi_model->get_datatables_hist($mc_id);
	    
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $rp) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $rp->mlp_name;
	        $row[] = $rp->mlp_by;
	        $row[] = $rp->date_insert;
	        $row[] = $rp->status;

	        if($rp->mlp_id=='1'){
	            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'>
                <i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
	            $row[] = "riwayatpengajuan/detail_atestasi/".$rp->mc_id."/".$rp->id;
	        }else if($rp->mlp_id=='2'){
	            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'>
                <i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
	            $row[] = "riwayatpengajuan/detail_sertifikasi/".$rp->mc_id."/".$rp->id;
	        }
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->Companyatestasi_model->count_all_hist($mc_id),
	        "recordsFiltered" => $this->Companyatestasi_model->count_filtered_hist($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}
	
	public function detail_atestasi($id,$tbpa_id){
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['row'] = $this->Companynonbumn_model->get_byid($id,$tbpa_id);
	    $data['cari']="";
	    $data['title']="Dashboard";
	    $data['subtitle']="Detail Atestasi";
	    $data['error']="";
	    
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 8){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    
	    $data['tbpa_id'] = $tbpa_id;
	    $data['cosmic_index_thisweek'] = $this->Companynonbumn_model->cosmic_index_minggu_ini($id);
	    $data['cosmic_index_weekbefore'] = $this->Companynonbumn_model->cosmic_index_minggu_lalu($id);
	    $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
	    
	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    
	    $this->load->view('header',$data);
	    $this->load->view('product/detail_atestasi',$data);
	    $this->load->view('footer',$data);
	}
	
	public function detail_sertifikasi($id, $tbps_id){
	    $data['rowx'] = $this->Companysertifikasi_model->get_byid_tbps($tbps_id);
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['cari']="";
	    $data['title']="Dashboard";
	    $data['subtitle']="Detail Sertifikasi";
	    $data['error']="";
	    
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 8){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['cosmic_index_thisweek'] = $this->Companynonbumn_model->cosmic_index_minggu_ini($id);
	    $data['cosmic_index_weekbefore'] = $this->Companynonbumn_model->cosmic_index_minggu_lalu($id);
	
	    $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
	    $data['master_status'] = $this->master_model->master_status_sertifikasi($cari='')->result();

	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    
	    $this->load->view('header',$data);
	    $this->load->view('product/detail_sertifikasi',$data);
	    $this->load->view('footer',$data);
	}
}
