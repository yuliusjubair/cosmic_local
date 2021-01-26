<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashcluster extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashboard_model');
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
      
    }

    public function ajax_dashboard_head() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
     
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashcluster/cluster_dashboardhead/".$msc_id,
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
        
        $output ='';
        foreach ($responsex as $res) {
            $output .='
            <div class="content col-lg col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
            <div class="card-wrap">';
            $judul = $res->v_judul;
            $output .='<div class="card-header">'.$judul.'</div>';
            $output  .='<div class="card-body"><b>'.$res->v_jml.'</b></div>';
            $output .='</div>
            </div>
            </div>';
        }
        echo $output;
    }
    
    public function ajax_perimeter_bykategori_all() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashcluster/cluster_perimeter_bykategori_all/".$msc_id,
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
    
    public function ajax_perimeter_byprovinsi_all() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashcluster/cluster_perimeter_byprovinsi_all/".$msc_id,
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
        $total=0;
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
    
    public function ajax_cosmicindexall() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashcluster/cluster_perimeter_bycosmicindex/".$msc_id,
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
            $row[] = $res->v_jml;
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
    
    public function excel_rangkuman_all() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $rangkuman = $this->dashmin_model->mv_cluster_rangkuman_all($msc_id);
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
            $sheet->setCellValue('E'.$x, $row->cosmic_index);
            $sheet->setCellValue('F'.$x, $row->cosmic_index_min1);
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
        $rangkuman = $this->dashmin_model->mv_rangkuman_all();
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
}