<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashatestasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('vaksin_model');
        $this->load->model('dashmin_model');
        $this->load->model('Companyatestasi_model');
        $this->load->model('Companynonbumn_model');

        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
    }

    public function secure() {
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }

    public function index(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Atestasi";
        $data['error']="";
  
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        
        $this->load->view('header',$data);
        $this->load->view('dashmin/dashboard_partner',$data);
        $this->load->view('footer',$data);
    }
    
    function get_all_company($status=NULL) {
        $list = $this->Companyatestasi_model->get_company($status);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            // print_r($field);
            if($field->tbpa_status=="1"){
                $status = "Disetujui";
            }elseif ($field->tbpa_status=="2") {
                $status = "Menunggu Persetujuan";
            }elseif($field->tbpa_status=="0"){
                $status = "Belum Disetujui";
            }elseif($field->tbpa_status=="3"){
                $status = "Belum Disetujui";
            }elseif($field->tbpa_status=="4"){
                $status = "Di Tolak";
            }else{
                $status = "Belum Disetujui";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->mc_name;
            $row[] = ($field->tbpa_date_insert=="")?"":date('d/m/Y',strtotime($field->tbpa_date_insert));
            $row[] = ($field->tbpa_date_update=="")?"":date('d/m/Y',strtotime($field->tbpa_date_update));
            $row[] = $status;
            $row[] = $field->tbpa_nama_pj;
            // $row[] = date('d/m/Y',strtotime($field->mc_date_insert));
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
            $row[] = $field->mc_id."/".$field->tbpa_id;
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Companyatestasi_model->count_all(),
            "recordsFiltered" => $this->Companyatestasi_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_list_card_partner() {
        $responsex = $this->dashmin_model->getlist_card_partner();
        $output ='';
        foreach ($responsex->result() as $res) {

            $output .='
            <div class="content col-lg col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
            <div class="card-wrap">';
            $output .='<div class="card-header">'.$res->judul.'<br /><br /></div>';
            $output .='<div class="card-body"><b>'.$res->jml.'</b>&nbsp;<font size=-1>Perusahaan</font></div>';
            $output .='</div>
            </div>
                </div>';
        }

        echo $output;
    }

    public function detail_nonbumn($id,$tbpa_id){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['row'] = $this->Companynonbumn_model->get_byid($id,$tbpa_id);
        $data['cari']="";
        $data['title']="Dashboard";
        $data['subtitle']="Detail Perusahaan";
        $data['error']="";
        //$data['menu_hide']="yes";

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
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        $data["create"]=$this->master_model->one_groups($group)->c;
        $data["update"]=$this->master_model->one_groups($group)->u;
        $data["delete"]=$this->master_model->one_groups($group)->d;
        $data['tbpa_id'] = $tbpa_id;
        $this->load->view('header',$data);
        $this->load->view('dashboard/detail_perusahaan',$data);
        $this->load->view('footer',$data);
    }


    public function getPerimeterByPerusahaan($mc_id, $tbpa_id) {
        $responsex = $this->db->query("select mpm_id, mpm_name, mc.mc_name, mpm_alamat,tbspa_id, tspa.tbspa_status, COALESCE(mpk.mpmk_id ,0) v_jml
            from master_perimeter mp
            inner join master_perimeter_kategori mpk on mpk.mpmk_id = mp.mpm_mpmk_id 
            inner join master_company mc on mc.mc_id = mp.mpm_mc_id 
            join table_status_pengajuan_atestasi tspa on tspa.tbspa_mpm_id = mp.mpm_id
            where mp.mpm_mc_id =  '$mc_id' and tspa.tbspa_tbpa_id='$tbpa_id'");

        $data = [];
        $no=1;
        foreach ($responsex->result() as $res) {
            $status = $this->dashmin_model->get_percent_by_mpl_id($res->mpm_id);
            //print_r($res);
            $button="";
            $atestasi=$res->tbspa_status;
            if($atestasi=="0" || $atestasi=="4"){
                $button = "<a class='btn btn-danger' href='javascript:void(0)'' title='Detail Event'
                        onclick=dialog_detail('".$mc_id."','".$res->mpm_id."','".$res->tbspa_id."')>Setujui</a>";
            }else{
                $button = '<a tabindex="0" class="" data-toggle="popover" data-trigger="focus" data-content="<a href=javascript:void(0) onclick=dialog_detail('.$mc_id.','.$res->mpm_id.','.$res->tbspa_id.')>Ubah Status</a> <br /> <a href=javascript:void(0) onclick=dialog_confirm('.$res->tbspa_id.')>Tolak Pengajuan</a>" data-html="true"><i class="ace-icon fa fa-list bigger-120"></a></i>';

            }
            $row = array();
            $row[] = $no;
            $row[] = $res->mpm_id;
            $row[] = $res->mpm_name;
            $row[] = $res->mpm_alamat;
            $row[] = $res->v_jml;
            $row[] = $status['percentage'].' %';
            $row[] = $button;
            //$row[] = "<i class='ace-icon fa fa-chevron-right bigger-120'></i>";
           /*  $row[] = "<a class='' href='javascript:void(0)'' title='Detail Event'
                        onclick=\"window.open('".base_url().'sosialisasi/detail_event/'.$res->v_name_kategori."','_self');\"></a>";*/
            $data[] = $row;
            $no++;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => 0,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_data_detail_partner($mc_id, $mpm_id, $tbspa_id){
        $perimeter = $this->master_model->mst_perimeter_byid_mcid($mc_id, $mpm_id)->row();
        $tbspa = $this->master_model->get_tbpsa_by_id($tbspa_id)->row();
         $output = array(
            "perimeter" =>$perimeter,
            "tbspa" => $tbspa
        );
        echo json_encode($output);
    }

    function update_status_atestasi(){
        $user_id = $this->session->userdata('user_id');
        $check = $this->input->post('check1');
        $modal_tbspa_id = $this->input->post('modal_tbspa_id');
        $mc_id = $this->input->post('modal_mc_id_stat');
        $petugas = $this->input->post('petugas');
        $kontak_petugas = $this->input->post('kontak_petugas');
        $estimasi = $this->input->post('estimasi');
        if(isset($check)){
            $status=1;
        }else{
            $status=0;
        }
        $date = date('Y-m-d h:i:s');
        if($status==1){
            $this->db->query("update table_pengajuan_atestasi set tbpa_status=1, tbpa_user_update='$user_id', tbpa_date_update='$date' where tbpa_mc_id='$mc_id'");
            // $this->db->query("update master_company set mc_status_atestasi=1, mc_nama_pic_atestasi='$petugas', mc_update_date_atestasi='$date' where mc_id='$mc_id'");
        }
        $this->db->query("UPDATE table_status_pengajuan_atestasi SET tbspa_status='$status', tbspa_petugas='$petugas', tbspa_kontak='$kontak_petugas', tbspa_estimasi='$estimasi', tbspa_user_insert='$user_id' WHERE tbspa_id='$modal_tbspa_id'");
         echo json_encode(array("status" => 200, "message" => 'Berhasil Update Status Atestasi'));
    }

    function update_status_pengajuan(){
        $modal_tbspa_id = $this->input->post('modal_id_tbspa');
        $status=4;
        $this->db->query("UPDATE table_status_pengajuan_atestasi SET tbspa_status='$status' WHERE tbspa_id='$modal_tbspa_id'");
         echo json_encode(array("status" => 200, "message" => 'Berhasil Tolak Pengajuan Atestasi'));
    }
}