<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashreport extends CI_Controller {

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
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Cosmic Report Protokol";
        $data['subtitle']="Cosmic Report Protokol";

        $data['error']="";
  
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        
        $this->load->view('header',$data);
        $this->load->view('dashmin/dashreport',$data);
        $this->load->view('footer',$data);
    }
    
    public function ajax_reportall($jns_lvl) {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashreport/all_byjns/".$jns_lvl,
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
            $row[] = $res->v_mc_name;
            $row[] = $res->v_jml_1;
            $row[] = $res->v_jml_2;
            $row[] = $res->v_jml_3.' %';
            $row[] = "<a class=''  title='Detail Report'
                      onclick=\"window.open('".base_url().'reportprotokol/index/'.$res->v_mc_id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
            
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
    
    public function ajax_list_summary($id) {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashreport/all_card_byjns/".$id,
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
            $row  .='<div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                <div class="card-wrap">
		    	<div class="card-header"><b>'.$res->v_judul.'</b></div>';
            if($res->v_id==3){
                $row  .='<div class="card-body">'.$res->v_jml.' %</div>';
            }else{
                $row  .='<div class="card-body">'.$res->v_jml.'</div>';
            }
            $row  .='</div>';
            $row  .='</div>';
            $row  .='</div>';
        }
        
        echo $row;
    }
}