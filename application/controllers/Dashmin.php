<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashmin_model');
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

        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard";
        $data['group'] = $group;
        $data['images']= $this->master_model->gallery();
        $data['count']= $this->master_model->count_images();
        $this->load->view('header',$data);
        //
        //under maintenance
        //
        /* if($group!=1){
            $logout = $this->ion_auth->logout();
            redirect('auth/mtc', 'refresh');
        }*/
        //end
        if($group==1){
            $this->load->view('dashmin/dashmin',$data);
		}else if($group==5){
            $this->load->view('dashmin/dashcluster',$data);
        }else if($group==6){
            $this->load->view('dashmin/dashmin',$data);
        }else if($group==7){
            //role partner atestasi
            $this->load->view('dashmin/dashboard_partner',$data);
        }else if($group==10){
            //role partner sertifikasi
            $this->load->view('sertifikasi/dashboard',$data);
        }else if($group==12){
            //role user belum verifikasi
            redirect('auth/onboarding', 'refresh');        
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

    function get_dashboard_byperimeter() {
        $list = $this->dashmin_model->get_datatables_bykategori();
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
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashmin_model->count_all_bykategori(),
            "recordsFiltered" => $this->dashmin_model->count_filtered_bykategori(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_dashboard_byprovinsi() {
        $list = $this->dashmin_model->get_datatables_byprovinsi();
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
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashmin_model->count_all_byprovinsi(),
            "recordsFiltered" => $this->dashmin_model->count_filtered_byprovinsi(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_dashboard_bycosmicindex() {
        $list = $this->dashmin_model->get_datatables_bycosmicindex();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $field->v_judul;
            $row[] = $field->v_jml.' %';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashmin_model->count_all_bycosmicindex(),
            "recordsFiltered" => $this->dashmin_model->count_filtered_bycosmicindex(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_cosmicindexall() {
        $curl = curl_init();
        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/cosmic_index_report?group_company=".$group_company;
        } else {
          $string = "dashboard/cosmic_index_report";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        $responsex = json_decode($response)->data;
        $no = 1;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $no++;
            $row[] = $res->mc_name;
            $row[] = $res->ms_name;
            $row[] = $res->cosmic_index;
            $row[] = $res->pemenuhan_protokol;
            $row[] = $res->pemenuhan_ceklist_monitoring;
            $row[] = $res->pemenuhan_eviden;
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Event'
                      onclick=\"window.open('".base_url().'histperimeter/detail/'.$res->mc_id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";

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

    public function ajax_cosmicindexall_depan() {
        $curl = curl_init();
        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/cosmic_index_report?group_company=".$group_company;
        } else {
          $string = "dashboard/cosmic_index_report";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        $responsex = json_decode($response)->data;
        $no = 1;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mc_name;
            $row[] = $res->cosmic_index;

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

    public function ajax_perimeter_bykategori_all() {
        $curl = curl_init();
        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/perimeter_bykategori_all?group_company=".$group_company;
        } else {
          $string = "dashboard/perimeter_bykategori_all";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        //$responsex = [];
        //if(isset($response->data)){
            $responsex = json_decode($response)->data;
        //}
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_judul;
            $row[] = $res->v_jml;
            $data[] = $row;
            $total += $res->v_jml;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => $total,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_perimeter_bykategori_all2() {
        $curl = curl_init();
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/perimeter_bykategori_all?group_company=".$group_company;
        } else {
          $string = "dashboard/perimeter_bykategori_all";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        /*$responsex = [];
        if(isset($response->data)){
            $responsex = json_decode($response)->data;
        }*/
        $responsex = json_decode($response)->data;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_id;
            $row[] = $res->v_judul;
            $row[] = $res->v_jml. " Perimeter";
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

    public function ajax_history_index() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/perimeter_bykategori_all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_judul;
            $row[] = $res->v_jml. " Perimeter";
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

    public function ajax_perimeter_byprovinsi_all() {
        $curl = curl_init();

        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/perimeter_byprovinsi_all?group_company=".$group_company;
        } else {
          $string = "dashboard/perimeter_byprovinsi_all";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        /*$responsex = [];
        if(isset($response->data)){*/
            $responsex = json_decode($response)->data;
        //}
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_judul;
            $row[] = $res->v_jml;
            $total += $res->v_jml;
            $data[] = $row;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => $total,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_perimeter_byprovinsi_all_page2() {
        $curl = curl_init();
        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/perimeter_byprovinsi_all?group_company=".$group_company;
        } else {
          $string = "dashboard/perimeter_byprovinsi_all";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        /*$responsex = [];
        if(isset($response->data)){*/
            $responsex = json_decode($response)->data;
        //}
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_mpro_id;
            $row[] = $res->v_judul;
            $row[] = $res->v_jml;
            $total += $res->v_jml;
            $data[] = $row;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => $total,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_dashboard_head() {
        $curl = curl_init();

        //Group Company Filter
        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/dashboardhead?group_company=".$group_company;
        } else {
          $string = "dashboard/dashboardhead";
        }
        //var_dump($string);
        //exit;

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
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
       /* $responsex = [];
        if(isset($response->data)){
            $responsex = json_decode($response)->data;
        }*/
        $responsex = json_decode($response)->data;
        $output ='';
        foreach ($responsex as $res) {


            $output .='
            <div class="content col-lg col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
            <div class="card-wrap">';
            if($res->v_judul=="Persentase input 100% Perusahaan"){
                $judul = "&nbsp;".$res->v_judul;
            }else{
                $judul = $res->v_judul."<br /><br />";
            }
           // $judul = $res->v_judul;
            $output .='<div class="card-header">'.$judul.'</div>';
            if($res->v_flag_link==1){
                $output .='<div class="card-body"><a href="'.base_url().$res->v_link.'"><b>'.$res->v_jml.'</b></a></div>';
            }else{
                $output  .='<div class="card-body"><b>'.$res->v_jml.'</b></div>';
            }

            $output .='</div>
            </div>
                	</div>';
        }

        echo $output;
    }

    public function excel_rangkuman_all() {
        $group_company = $this->input->get('group_company');
        
        if (isset($group_company)) {
            if($group_company==2){
                $rangkuman = $this->dashmin_model->mv_rangkuman_all_nonbumn();
            }else if($group_company==3){
                $rangkuman = $this->dashmin_model->mv_rangkuman_all_semua();
            }else{
                $rangkuman = $this->dashmin_model->mv_rangkuman_all();
            }
        }else{
            $rangkuman = $this->dashmin_model->mv_rangkuman_all();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kluster Industri');
        $sheet->setCellValue('C1', 'Nama Perusahaan');
        $sheet->setCellValue('D1', 'Jumlah Perimeter');
        $sheet->setCellValue('E1', 'Cosmic Index Minggu Sebelumnya ');
        $sheet->setCellValue('F1', 'Cosmic Index Minggu ini');
        $sheet->setCellValue('G1', 'Kasus Positif');
        $sheet->setCellValue('H1', 'Kasus Suspek');
        $sheet->setCellValue('I1', 'Kasus kontak Erat');
        $sheet->setCellValue('J1', 'Kasus Selesai');
        $sheet->setCellValue('K1', 'Kasus Meninggal');
        $sheet->setCellValue('L1', 'Persentase Input (Protokol & Perimeter)');
        $sheet->setCellValue('M1', 'Kekurangan Input');
        $sheet->setCellValue('N1', 'Event Terakhir Perusahaan');

        $no = 1;
        $x = 2;
        foreach($rangkuman->result() as $row){
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row->ms_name);
            $sheet->setCellValue('C'.$x, $row->mc_name);
            $sheet->setCellValue('D'.$x, $row->cnt_mpm);
            $sheet->setCellValue('E'.$x, $row->cosmic_index_min1);
            $sheet->setCellValue('F'.$x, $row->cosmic_index);
            $sheet->setCellValue('G'.$x, $row->positif);
            $sheet->setCellValue('H'.$x, $row->suspek);
            $sheet->setCellValue('I'.$x, $row->kontakerat);
            $sheet->setCellValue('J'.$x, $row->selesai);
            $sheet->setCellValue('K'.$x, $row->meninggal);
            $sheet->setCellValue('L'.$x, $row->persen_dokumen);
            $sheet->setCellValue('M'.$x, $row->belum_dokumen);
            $sheet->setCellValue('N'.$x, $row->sosialisasi_akhir);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rangkuman_Perusahaan';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function csv_rangkuman_all() {
       $filename = 'Rangkuman_Perusahaan.csv';
       header("Content-Description: File Transfer");
       header("Content-Disposition: attachment; filename=$filename");
       header("Content-Type: application/csv; ");

       // get data
       $group_company = $this->input->get('group_company');
       if (isset($group_company)) {
           if($group_company==2){
               $rangkuman = $this->dashmin_model->mv_rangkuman_all_nonbumn();
           }else if($group_company==3){
               $rangkuman = $this->dashmin_model->mv_rangkuman_all_semua();
           }else{
               $rangkuman = $this->dashmin_model->mv_rangkuman_all();
           }
       }else{
           $rangkuman = $this->dashmin_model->mv_rangkuman_all();
       }
       // file creation
       $file = fopen('php://output', 'w');

       $header = array("Kluster Industri","Nama Perusahaan","Jumlah Perimeter","Cosmic Index Minggu Sebelumnya","Cosmic Index Minggu ini","Kasus Positif","Kasus Suspek","Kasus kontak Erat","Kasus Selesai","Kasus Meninggal","Persentase Input (Protokol & Perimeter)","Kekurangan Input","Event Terakhir Perusahaan");
       fputcsv($file, $header);
       foreach ($rangkuman->result_array() as $key=>$line){

        //print_r($line);
         fputcsv($file,$line);
       }
       fclose($file);
       exit;

    }

    public function all_kategori_perimeter(){
        $this->secure();
        $group_company = $this->input->get('group_company');
        $data['group_company']=null;
        //var_dump($group_company);
        //exit;
        if ((isset($group_company)) && $group_company<3) {
          $data['group_company']=$group_company;
        } else {
          $data['group_company']=null;
        }
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Kategori Perimeter";
        $data['subtitle']="Kategori Perimeter";
        $data['error']="";
        $data['menu_hide']="yes";

        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('dashmin/all_kategori_perimeter',$data);
        $this->load->view('footer',$data);
    }

    public function all_kategori_provinsi(){
        $this->secure();
        $group_company = $this->input->get('group_company');
        $data['group_company']=null;
        //var_dump($group_company);
        //exit;
        if ((isset($group_company)) && $group_company<3) {
          $data['group_company']=$group_company;
        } else {
          $data['group_company']=null;
        }
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Perimeter Provinsi";
        $data['subtitle']="Perimeter Provinsi";
        $data['error']="";
        $data['menu_hide']="yes";

        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('dashmin/all_kategori_provinsi',$data);
        $this->load->view('footer',$data);
    }

    public function all_kategori_index(){
        $this->secure();
        $group_company = $this->input->get('group_company');
        $data['group_company']=null;
        //var_dump($group_company);
        //exit;
        if ((isset($group_company)) && $group_company<3) {
          $data['group_company']=$group_company;
        } else {
          $data['group_company']=null;
        }
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Cosmic Index Perusahaan";
        $data['subtitle']="Cosmic Index Perusahaan";
        $data['error']="";
        $data['menu_hide']="yes";

        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('dashmin/all_kategori_index',$data);
        $this->load->view('footer',$data);
    }

    //sprint16

    public function FormPerusahaanPerimeter($id, $name){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Dashboardxx";
        $data['subtitle']="Dashboard";
        $data['error']="";
        $data['menu_hide']="yes";
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        $data['name']=$name;
        $data['id']=$id;

        $this->load->view('header',$data);
        $this->load->view('dashmin/form_perimeter_perusahaan',$data);
        $this->load->view('footer',$data);
    }

    public function getDataByPerusahaanPerimeter($data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/perimeter_bykategoriperusahaan/".$data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        $responsex = json_decode($response)->data;

        $data = [];
        $no=1;
        foreach ($responsex as $res) {
            //print_r($res);
            $row = array();
            $row[] = $no;
            $row[] = $res->v_mpm_id;
            $row[] = $res->v_name_kategori;
            $row[] = $res->v_jml;
            $row[] = $res->v_name_perusahaan;
            $row[] = $res->v_name_provinsi;
            $row[] = "<i class='ace-icon fa fa-chevron-right bigger-120'></i>";
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

    public function FormPerimeterLevel($id){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Dashboardxx";
        $data['subtitle']="Detail Perimeter";
        $data['error']="";
        $data['id']=$id;
        $data['menu_hide']="yes";

        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('dashmin/form_perimeter_level',$data);
        $this->load->view('footer',$data);
    }

    function get_data_perimeterlevel($id) {
        $list = $this->dashmin_model->get_datatables_byperusahaanperimeter($id);
        //echo $this->db->last_query();die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $status = $this->dashmin_model->get_percent($field->mpml_mpm_id, $field->mpml_id);
            $no++;
            $row = array();
            $row[] = $field->mpml_name;
            $row[] = $status['percentage'].' %';
            $row[] = "PIC : ".$field->pic;
            $row[] = "FO : ".$field->fo;
            $row[] = $field->mpml_id;
            $row[] = '';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => '',
            "recordsFiltered" => '',
            "data" => $data,
        );
        echo json_encode($output);
    }

    function detail_tabel_perimeter($id) {
        $list = $this->dashmin_model->detail_tabel_perimeter($id);
        $row = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        $row .= '<tr><th>Cluster Ruangan</th><th>Jumlah Ruangan</th><th>Status</th></tr>';
        foreach ($list->result() as $field) {
            $status = $this->dashmin_model->get_status_perimeter_detail($field->tpmd_id, $field->mcr_id);
            $row.='<tr>';
            $row.='<td>'.$field->mcr_name.'</td>';
            $row.='<td>'.$field->tpmd_order.'</td>';
            $row.='<td>'.$status.'</td>';
            $row.='</tr>';
        }
        $row .= '</table>';

        echo $row;
    }

    public function FormPerusahaanPerimeterProv($id, $name){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Dashboardxx";
        $data['subtitle']="Dashboard";
        $data['error']="";
        $data['menu_hide']="yes";
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        $data['name']=$name;
        $data['id']=$id;

        $this->load->view('header',$data);
        $this->load->view('dashmin/form_perimeter_perusahaan_prov',$data);
        $this->load->view('footer',$data);
    }

    public function getDataByPerusahaanPerimeterProv($data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/perimeter_bykategoriperusahaanProv/".$data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        //var_dump($response);die;
        curl_close($curl);
        $responsex = json_decode($response)->data;

        $data = [];
        $no=1;
        foreach ($responsex as $res) {
            //print_r($res);
            $row = array();
            $row[] = $no;
            $row[] = $res->v_mpm_id;
            $row[] = $res->v_name_kategori;
            $row[] = $res->v_jml;
            $row[] = $res->v_name_perusahaan;
            //$row[] = $res->v_name_provinsi;
            $row[] = "<i class='ace-icon fa fa-chevron-right bigger-120'></i>";
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

    //sprint17
    public function protokol_terbaru() {
        $group_company = $this->input->get('group_company');
        $responsex = $this->dashmin_model->getprotokol_terbaru($group_company);
        $total=0;
        $data = [];
        foreach ($responsex->result() as $res) {
            $row = array();
            $row[] = $res->tbpt_id;
            $row[] = $res->mc_name;
            $row[] = $res->mpt_name;
            $row[] = date('d-M-y',strtotime($res->tbpt_date_insert));
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

    public function kegiatan_terbaru() {

        $group_company = $this->input->get('group_company');
        //var_dump(isset($group_company));
        //exit;
        $query = $this->dashmin_model->getkegiatan_terbaru($group_company);
        $total=0;
        $data = [];
        foreach ($query->result() as $res) {
            $row = array();
            $row[] = $res->mc_name;
            $row[] = $res->mslk_name;
            $row[] = date('d-M-y',strtotime($res->ts_date_insert));
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

    public function kasus_terbaru() {
      $group_company = $this->input->get('group_company');
        $responsex = $this->dashmin_model->getkasus_terbaru($group_company);
        $total=0;
        $data = [];
        foreach ($responsex->result() as $res) {
            $row = array();
            $row[] = $res->mc_name;
            $row[] = $res->msk_name2;
            $row[] = date('d-M-y',strtotime($res->tk_date_insert));
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

    
    //sprint19

}
