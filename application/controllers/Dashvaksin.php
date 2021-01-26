<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashvaksin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('vaksin_model');
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
        $group=$this->ion_auth->get_users_groups()->row()->id;
        if($group==1 or $group==6){
            $this->dashvaksin();
        }else{
            $this->dashvaksin();
        }
    }
    
    public function dashvaksin(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Vaksin";

        $this->load->view('header',$data);
        $this->load->view('dashvaksin/dashvaksin',$data);
        $this->load->view('footer',$data);
    }

    public function ajax_list_summary() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin",
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
        $responsex = json_decode($response)->data;
        $row="";
        foreach ($responsex as $res) {
            $row  .='<div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-wrap">
		    	<div class="card-header"><b>'.$res->v_judul.'</b></div>';
            $row  .='<div class="card-body">'.$res->v_jml.'</div>';
            $row  .='</div>';
            $row  .='</div>';
            $row  .='</div>';
        }
        echo $row;
    }
    
    public function ajax_vaksin_byperusahaan() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_mc",
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
        $responsex = json_decode($response)->data;
     
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_mc_name;
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
    
    public function ajax_vaksin_byprovinsi() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_mpro",
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
        $responsex = json_decode($response)->data;
        
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_mpro;
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
    
    public function ajax_vaksin_bykabupaten() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_mkab",
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
        $responsex = json_decode($response)->data;
        
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_mkab;
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
    
    public function excel_dashvaksin() {
        $rangkuman = $this->dashmin_model->dashvaksin_rangkuman();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Perusahaan');
        $sheet->setCellValue('C1', 'Jumlah Pegawai');
        $sheet->setCellValue('D1', 'Jumlah Keluarga Inti Pegawai');
        $sheet->setCellValue('E1', 'Jumlah Pegawai sudah Vaksin');
        $sheet->setCellValue('F1', 'Jumlah Pegawai belum Vaksin');
        
        $no = 1;
        $x = 2;
        foreach($rangkuman->result() as $row){
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row->v_mc_name);
            $sheet->setCellValue('C'.$x, $row->v_jml_pegawai);
            $sheet->setCellValue('D'.$x, $row->v_jml_keluarga);
            $sheet->setCellValue('E'.$x, $row->v_jml_sudah);
            $sheet->setCellValue('F'.$x, $row->v_jml_belum);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rangkuman_Vaksin';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    public function csv_dashvaksin() {
        $filename = 'Rangkuman_Vaksin.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        
        $rangkuman = $this->dashmin_model->dashvaksin_rangkuman();
        $file = fopen('php://output', 'w');
        
        $header = array("Nama Perusahaan","Jumlah Pegawai","Jumlah Keluarga Inti Pegawai",
            "Jumlah Pegawai sudah Vaksin","Jumlah Pegawai belum Vaksin"
        );
        fputcsv($file, $header);
        foreach ($rangkuman->result_array() as $key=>$line){
            fputcsv($file,$line);
        }
        fclose($file);
        exit;
    }
    
    public function ajax_vaksin_bylokasi1() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_lokasi1",
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
        $responsex = json_decode($response)->data;
        
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_lokasi;
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
    
    public function ajax_vaksin_bylokasi2() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_lokasi2",
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
        $responsex = json_decode($response)->data;
        
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_lokasi;
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
    
    public function ajax_vaksin_bylokasi3() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashvaksin/dashvaksin_lokasi3",
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
        $responsex = json_decode($response)->data;
        
        $total=0;
        $data = [];
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->v_lokasi;
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
}