<?php
use Carbon\Carbon;

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashboard_model');
        $this->load->model('pengumuman_model');
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
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard";
        $data['images']= $this->master_model->gallery();
        $data['count']= $this->master_model->count_images();
        //
        //under maintenance
        //
        /*if($group!=1){
            $logout = $this->ion_auth->logout();
            redirect('auth/mtc', 'refresh');
        }*/
        //end
        $this->load->view('header',$data);
        if($group==1){
            $this->load->view('dashboard/index_admin',$data);
        }else if($group==6){
            $this->load->view('dashboard/index_admin',$data);
        }else if($group==5){
            $this->load->view('dashboard/index_cluster',$data);
        }else{
            $password_default = $this->bcrypt->verify('P@ssw0rd',$data['user']->password);
            if($password_default==true){
                $url_default="/auth/change_password";
                redirect($url_default, 'refresh');
            }
            $this->load->view('dashboard/index',$data);
        }
        $this->load->view('footer',$data);
    }

    function get_dashboard_protokol() {

        $group_company = $this->input->get(group_company);
        //var_dump($group_company);
        //exit;
        $list = $this->dashboard_model->get_datatables($group_company);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->v_mc_name;
            $row[] = (int)$field->v_persentase;
            $row[] = $field->v_belum_isi;
            $row[] = $field->v_sektor;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashboard_model->count_all($group_company),
            "recordsFiltered" => $this->dashboard_model->count_filtered($group_company),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_dashboard_jml() {
        $list = $this->dashboard_model->get_datatables_jml();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $field->v_judul;
            $row[] = $field->v_jml;
            $data[] = $row;
        }

        $output = array(
            "draw" => 1,
            "recordsTotal" => $this->dashboard_model->count_all_jml(),
            "recordsFiltered" => $this->dashboard_model->count_filtered_jml(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function dashperimeter(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Perimeter";

        $this->load->view('header',$data);
        $this->load->view('dashboard/list_dashperimeter',$data);
        $this->load->view('footer',$data);
    }

    public function get_dashperimeter() {
        $this->load->helper('url');
        $list = $this->dashboard_model->get_datatables_mpm();

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $mpm) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $mpm->v_ms_name;
            $row[] = $mpm->v_mc_name;
            $row[] = $mpm->v_cnt;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashboard_model->count_all_mpm(),
            "recordsFiltered" => $this->dashboard_model->count_filtered_mpm(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_week_protokol($mc_id) {
        $cnt = $this->dashboard_model->week_protokol($mc_id)->v_cnt;
        $url_protokol = base_url('/protokol');

        if($cnt>0){
            $ret = '';
        }else{
            $ret = '
            <div class="alert alert-block alert-danger alert-has-icon" style="">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">

                <div class="alert-title">Anda belum melakukan Update Protokol Minggu ini</div>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_protokol()">Update Data</button>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_protokol2()">Tidak ada Protokol yang berubah/disesuaikan</button>
                  </div>
            </div>';
        }
        echo $ret;
    }

    function ajax_week_sosialisasi($mc_id) {
        $cnt = $this->dashboard_model->week_sosialisasi($mc_id)->v_cnt;
        $url_protokol = base_url('/sosialisasi');

        if($cnt>0){
            $ret = '';
        }else{
            $ret = '
            <div class="alert alert-block alert-danger alert-has-icon" style="">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
                <div class="alert-title">Anda belum melakukan Input Data Kegiatan Minggu ini</div>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_sosialisasi()">Tambah Event</button>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_sosialisasi2()">Tidak ada Event yang diadakan Perusahaan</button>
                  </div>
            </div>';
        }
        echo $ret;
    }

    function ajax_week_kasus($mc_id) {
        $cnt = $this->dashboard_model->week_kasus($mc_id)->v_cnt;
        $url_protokol = base_url('/sosialisasi');

        if($cnt>0){
            $ret = '';
        }else{
            $ret = '
            <div class="alert alert-block alert-danger alert-has-icon" style="">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
                <div class="alert-title">Anda belum melakukan Input/Perubahan data Pegawai Terdampak Minggu ini</div>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_kasus()">Update Data</button>
                <button style="vertical-align:middle;margin:1% 1% 0% 0%;" class="btn btn-sm btn-primary" onclick="link_kasus2()">Tidak ada Data yang berubah/disesuaikan</button>
                  </div>
            </div>';
        }
        echo $ret;
    }

    function get_alert($mc_id){
        $nomor1 = 0; $nomor2 = 0; $nomor3 = 0;
        $no1 = 0; $no2 = 0; $no3 = 0;

        $cnt = $this->dashboard_model->week_kasus($mc_id)->v_cnt;
        if($cnt>0){
            $nomor1=1;
        }else{
            $no1 = 1;
        }

        $cnt2 = $this->dashboard_model->week_sosialisasi($mc_id)->v_cnt;
        if($cnt2>0){
            $nomor2=1;
        }else{
            $no2 = 1;
        }

        $cnt3 = $this->dashboard_model->week_protokol($mc_id)->v_cnt;
        if($cnt3>0){
            $nomor3 = 1;
        }else{
            $no3 = 1;
        }

        $total = $nomor1+$nomor2+$nomor3;
        $tot = $no1+$no2+$no3;
        echo json_encode(array("total" => $total, "tot" => $tot));
    }

    function open_news($id){
        $data['row'] = $this->pengumuman_model->get_byid($id);
        $this->load->view('pengumuman/detail',$data);
    }

    public function ajax_dashboard_headbumn($mc_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/dashboardhead_bumn/".$mc_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        /*$responsex = [];
        if(isset($response)){
            $responsex = json_decode($response)->data;
        }*/
        $responsex = json_decode($response)->data;

        $output='';
        foreach ($responsex as $res) {
            $output .='
            <div class="content col-lg col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                        <div class="card-wrap">';
            $output .='<div class="card-header">'.$res->v_judul.'<br /></div>';
            $output .='<div class="card-body"><b>'.$res->v_jml.'</b></div>';
            $output .='</div>
                </div>
                    </div>';
        }

        echo $output;
    }

    public function ajax_bumn_protokol($mc_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/dashboardprotokol_bumn/".$mc_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        //var_dump(isset($response));die;
        if(isset($response)){
            $responsex = json_decode($response)->data;
        }else{
            $responsex = [];
        }

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_name;
            $row[] = $res->v_status;
            $data[] = $row;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => 0,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_bumn_mrpmpm($mc_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/dashboardmrmpm_bumn/".$mc_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responsex = [];
        if(isset($response)){
            $responsex = json_decode($response)->data;
        }
        $data = array();
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_region_name;
            $row[] = $res->v_cnt;
            $data[] = $row;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => 0,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_bumn_kasus($mc_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/laporan_home/".$mc_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responsex = [];
        if(isset($response)){
            $responsex = json_decode($response)->data;
        }
        $data = array();
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->jenis_kasus;
            $row[] = $res->jumlah;
            $data[] = $row;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => 0,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_dashboard_protokol_cluster() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;

        $list = $this->dashboard_model->get_datatables_cluster($msc_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->v_mc_name;
            $row[] = (int)$field->v_persentase;
            $row[] = $field->v_belum_isi;
            $row[] = $field->v_sektor;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashboard_model->count_all_cluster($msc_id),
            "recordsFiltered" => $this->dashboard_model->count_filtered_cluster($msc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function perubahan_week_kasus($mc_id) {
        $kasus = $this->dashboard_model->perubahan_week_kasus($mc_id);

        if($kasus=='success'){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Save Tidak Ada Perubahan Kasus'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Save Tidak Ada Perubahan Kasus'));
        }
    }

    function perubahan_week_protokol($mc_id) {
        $protokol = $this->dashboard_model->perubahan_week_protokol($mc_id);

        if($protokol=='success'){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Save Tidak Ada Perubahan Protokol'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Save Tidak Ada Perubahan Protokol'));
        }
    }

    function perubahan_week_sosialisasi($mc_id) {
        $sosialisasi = $this->dashboard_model->perubahan_week_sosialisasi($mc_id);

        if($sosialisasi=='success'){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Save Tidak Ada Perubahan Sosialisasi'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Save Tidak Ada Perubahan Sosialisasi'));
        }
    }

    
}
