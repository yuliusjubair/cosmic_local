<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportprotokol extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('printqrcode_model');
        $this->load->model('master_model');
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
        $this->load->library('mxencryption');
    }

    public function secure() {
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }

    public function index($p_mc_id='') {
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['tgl_now']=Date('Y-m-d');
        $data['cari']="";
        $data['title']="Protokol";
        $data['subtitle']="Report Protokol";

        $group = $this->ion_auth->get_users_groups()->row()->id;
        $data['group'] = $group;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 8){
            $mc_id =  $data['user']->mc_id;
        }else{
            $mc_id = $p_mc_id;
        }
        $data['p_mc_id'] = $mc_id;
        $list_picfo = $this->master_model->get_picfo_bymcid('0305')->result_array();
        $data['list_picfo'] = json_encode($list_picfo);

        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
        $data["menu"]=$this->master_model->menus($group);

        $data["create"]=$this->master_model->one_groups($group)->c;
        $data["update"]=$this->master_model->one_groups($group)->u;
        $data["delete"]=$this->master_model->one_groups($group)->d;

        $this->load->view('header',$data);
        $this->load->view('reportprotokol/list_report',$data);
        $this->load->view('footer',$data);
    }

    function get_company_bygroupcompany(){
           //$group_company = $this->input->post('id',TRUE);
           $group_company = $this->input->get(group_company);
           $data =$this->master_model->company_bygroupcompany($group_company)->result();
           echo json_encode($data);
    }

    public function report($enc_mpmid){
        $crypt = new mxencryption();
        $mpm_id = $crypt->decrypt(strval($enc_mpmid));
        
        $data['tgl_now']=Date('Y-m-d');
        $data['cari']="";
        $data['title']="Report";
        $data['subtitle']="ReportProtokol";
        
        if($mpm_id>0){
            $data['perimeter'] = $this->printqrcode_model->printqrcode($mpm_id);
            $data['perimeter_id']=$enc_mpmid;
            
            if($data['perimeter']==NULL){
                $this->load->view('404error',$data);
            }else{
                $data['perimeter_lantai'] = $this->printqrcode_model->mpml_bympm($mpm_id);
                $this->load->view('reportprotokol/report',$data);
            }
        }else{
            $this->load->view('404error',$data);
        }
    }

    public function save_report(){
        $data = array();
        date_default_timezone_set('Asia/Jakarta');
        $valid = $this->_validate();

        $mc_id = $this->printqrcode_model->mc_bympml($this->input->post('lantai'))->mc_id;
        $no_faktur = $this->printqrcode_model->get_faktur($mc_id)->no_faktur;

        if ($_FILES['eviden_1']['tmp_name']!='') {
            $file_name1 =$_FILES['eviden_1']['name'];
            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
            $file1x = round(microtime(true) * 1000);

            $file1 = round(microtime(true) * 1000).'.'.$file_ext1;
            $this->_do_upload($file1x, 'eviden_1',$mc_id,$this->input->post('lantai'));
        }else{
            $file1 = NULL;
        }

        if ($_FILES['eviden_2']['tmp_name']!='') {
            $file_name2 =$_FILES['eviden_2']['name'];
            $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
            $file2x = round(microtime(true) * 1000);

            $file2 = round(microtime(true) * 1000).'.'.$file_ext2;
            $this->_do_upload($file2x, 'eviden_2',$mc_id,$this->input->post('lantai'));
        }else{
            $file2 = NULL;
        }

        $datax = array(
            'tr_mpml_id' => $this->input->post('lantai'),
            'tr_laporan' => $this->input->post('laporan'),
            'tr_no' => $no_faktur,
            "tr_file1" => $file1,
            "tr_file2" => $file2,
            'tr_date_insert' =>  date('Y-m-d H:i:s')
        );

        $insert = $this->printqrcode_model->save_report($datax);
        //var_dump($insert);die;
        if($insert==1){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Submit Report'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Submit Report'));
        }
    }

    private function _do_upload($file, $nama_form, $mc_id, $mpml_id) {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);

        if(!is_dir("uploads/report_protokol/")) {
            mkdir("uploads/report_protokol/");
        }else{
            if(!is_dir("uploads/report_protokol/".$mc_id)) {
                mkdir("uploads/report_protokol/".$mc_id);
            }else{
                if(!is_dir("uploads/report_protokol/".$mc_id."/".$mpml_id)) {
                    mkdir("uploads/report_protokol/".$mc_id."/".$mpml_id);
                }
            }
        }

        $config['upload_path']          = 'uploads/report_protokol/'.$mc_id.'/'.$mpml_id;
        //var_dump($config['upload_path']);die;
        if(is_file($config['upload_path'])) {
            chmod($config['upload_path'], 777);
        }
        $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
        $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
        $config['file_name']            = $file; //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload($nama_form)) {
            $data['inputerror'][] = $nama_form;
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = 200;
        $data['success'] = TRUE;

        $data = $this->googleCaptachStore();

        if($this->input->post('laporan') == '') {
            $data['inputerror'][] = 'laporan';
            $data['error_string'][] = 'laporan is required';
            $data['status'] = 500;
            $data['success'] = FALSE;
        }
        
        if($this->input->post('lantai') == '') {
            $data['inputerror'][] = 'lantai';
            $data['error_string'][] = 'lantai is required';
            $data['status'] = 500;
            $data['success'] = FALSE;
        }

        if($data['success'] === FALSE) {
            echo json_encode($data);
            exit();
        }else{
            $data['inputerror'][] = '';
            $data['error_string'][] = '';
            $data['status'] = 200;
            $data['success'] = TRUE;
        }
    }

    public function googleCaptachStore(){
        $data=array();
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
        $userIp=$this->input->ip_address();
        $secret='6Lc9Xd8ZAAAAAGrdfZEOoq-X5Mr3jPc5FFGvS5OF';
        $credential = array(
            'secret' => $secret,
            'response' => $this->input->post('g-recaptcha-response')
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);

        $status= json_decode($response, true);

        if(!$status['success']){
            $data['inputerror'][] = 'recaptcha';
            $data['error_string'][] = 'recaptcha is required';
            $data['status'] = 500;
            $data['success'] = FALSE;
        }

        return $data;
    }

    public function ajax_list($mc_id) {
        $this->load->helper('url');
        $group = $this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        $list = $this->printqrcode_model->get_datatables($mc_id);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $rp) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $rp->tr_no;
            $row[] = $rp->mpm_name;
            $row[] = $rp->mpml_name;
            if($rp->tr_close>0){
                $row[] = '<span>'.$rp->status.'</span>';
            }else{
                $row[] = '<span style="color:red;">'.$rp->status.'</span>';
            }
            $btn_edit = '<a class=""  title="Detail Report"
                       onclick="edit_report('."'".$rp->tr_id."'".')"><i class="ace-icon fa fa-chevron-right bigger-120"></i></a>';

            if($update==1){
                $btnx_edit =$btn_edit;
            }else{
                $btnx_edit ='';
            }

            $row[] = ' <div class="button">'.$btnx_edit.'</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->printqrcode_model->count_all($mc_id),
            "recordsFiltered" => $this->printqrcode_model->count_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_list_summary($mc_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashreport/card_bymcid/".$mc_id,
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
        //var_dump($responsex);die;
        $row="";
        foreach ($responsex as $res) {
            $row  .='<div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-wrap">
		    	<div class="card-header"><b>'.$res->v_judul.'</b></div>';
            if($res->v_id!=3){
                $row  .='<div class="card-body">'.$res->v_jml.'</div>';
            }else{
                $row  .='<div class="card-body">'.$res->v_jml.' %</div>';
            }
            $row  .='</div>';
            $row  .='</div>';
            $row  .='</div>';
        }

        echo $row;
    }

    public function ajax_edit($id) {
        $report= $this->printqrcode_model->get_byid($id);
        if(isset($report)) {
            if($report->tr_file1!='' && file_exists('uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_file1)){
                $urlimg_1 = site_url().'uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_file1;
                $img_1 = '<label class="control-label" id="label-photo1">Report Photo Pertama</label>
                    <br><img src="'.$urlimg_1.'" width="200px"><br>';
            }else{
                $img_1 = '';
            }

            if($report->tr_file2!='' && file_exists('uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_file2)){
                $urlimg_2 = site_url().'uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_file2;
                $img_2 = '<label class="control-label" id="label-photo1">Report Photo Kedua</label>
                     <br><img src="'.$urlimg_2.'" width="200px"><br>';
            }else{
                $img_2 = '';
            }

            if($report->tr_tl_file1!='' && file_exists('uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_tl_file1)){
                $urlimg_tl_1 = site_url().'uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_tl_file1;
                $img_tl_1 = '<label class="control-label" id="label-photo1">Report Photo Pertama</label>
                     <br><img src="'.$urlimg_tl_1.'" width="200px"><br>';
            }else{
                $img_tl_1 = '';
            }

            if($report->tr_tl_file2!='' && file_exists('uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_tl_file2)){
                $urlimg_tl_2 = site_url().'uploads/report_protokol/'.$report->mc_id.'/'.$report->mpml_id.'/'.$report->tr_tl_file2;
                $img_tl_2 = '<label class="control-label" id="label-photo1">Report Photo Kedua</label>
                      <br><img src="'.$urlimg_tl_2.'" width="200px"><br>';
            }else{
                $img_tl_2 = '';
            }

            if($report->tr_close>0){
                $status = '<span>'.$report->status.'</span>';
            }else{
                $status = '<span style="color:red;">'.$report->status.'</span>';
            }

            $data = array(
                "id" => $report->tr_id,
                "laporan" => $report->tr_laporan,
                "no_laporan" => $report->tr_no,
                "perimeter" => $report->mpm_name,
                "perimeter_level" => $report->mpml_name,
                "tgl_lapor" => $report->date_insert,
                "status" => $status,
                "penanggungjawab" => $report->tr_penanggungjawab,
                "close" => $report->tr_close,
                "img_1" => $img_1,
                "img_2" => $img_2,
                "img_tl_1" => $img_tl_1,
                "img_tl_2" => $img_tl_2,
            );
        }else{
            $data = array();
        }
        echo json_encode($data);
    }

    public function ajax_update() {
        date_default_timezone_set('Asia/Jakarta');
        $data = array();

        $id = $this->input->post('id');
        $kd_perusahaan = $this->input->post('kd_perusahaan_modal');
        $penanggungjawab = $this->input->post('penanggungjawab');
        $ceklist= $this->input->post('modal_ceklis');

        if(isset($ceklist)){
            $ceklis = 1;
        }else{
            $ceklis = 0;
        }

        if ($_FILES['modal_foto_tl']['tmp_name']!='') {
            $file_name1 =$_FILES['modal_foto_tl']['name'];
            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
            $file_tmp1= $_FILES['modal_foto_tl']['tmp_name'];
            $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
            $data1 = file_get_contents($file_tmp1);
            $file_report1 = 'data:image/'.$type1.';base64,'.base64_encode($data1);
        }else{
            $file_report1 = NULL;
        }

        if ($_FILES['modal_foto_tl2']['tmp_name']!='') {
            $file_name2 =$_FILES['modal_foto_tl2']['name'];
            $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
            $file_tmp2= $_FILES['modal_foto_tl2']['tmp_name'];
            $type2 = pathinfo($file_tmp2, PATHINFO_EXTENSION);
            $data2 = file_get_contents($file_tmp2);
            $file_report2 = 'data:image/'.$type2.';base64,'.base64_encode($data2);
        }else{
            $file_report2 = NULL;
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $api_update = $this->api_update($id, $user_id, $ceklis,
            $penanggungjawab, $file_report1, $file_report2);

        echo $api_update;
    }

    public function api_update($id, $user_id, $ceklis, $penanggungjawab,
        $file_report1, $file_report2){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."report/webupdate_json/$user_id/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'ceklis' => $ceklis,
                'penanggungjawab' => $penanggungjawab,
                'file_report1' => $file_report1,
                'file_report2' => $file_report2
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    public function autocomplete_picfo($mc_id){
        $list_picfo = $this->master_model->get_picfo_bymcid($mc_id)->result_array();
        $picfo = json_encode($list_picfo);

        echo $picfo;
    }
    
    public function reportprotokol(){
        $data['perimeter'] = $this->input->get('perimeter');
        
        $data['tgl_now']=Date('Y-m-d');
        $data['cari']="";
        $data['title']="Report";
        $data['subtitle']="ReportProtokol";
        
        if($data['perimeter']!=NULL){
            $crypt = new mxencryption();
            $mpm_id = $crypt->decrypt(strval($data['perimeter']));
        }else{
            $data['company'] = $this->master_model->company(1,NULL,NULL);
        }
        
        $this->load->view('reportprotokol/report_new',$data);
    }
    
    public function perimeter(){
        $kd_perusahaan = $this->input->post('kd_perusahaan');
        if($kd_perusahaan!=NULL){
            $perimeter = $this->master_model->perimeter_bymcid($kd_perusahaan);
            $lists = "";
            
            foreach($perimeter->result() as $data){
                $lists .= "<option value='".$data->mpm_id."'  $selected>".$data->mpm_name."</option>";
            }
        }else{
            $lists = "";
        }
        
        $callback = array('list_perimeter'=>$lists);
        echo json_encode($callback);
    }
    
    public function perimeter_level(){
        $perimeter_id = $this->input->post('perimeter_id');
        if($perimeter_id!=NULL){
            $perimeter_level = $this->printqrcode_model->mpml_bympm($perimeter_id);
            $lists = "";
        
            foreach($perimeter_level as $data){
                $lists .= "<option value='".$data->mpml_id."'  $selected>".$data->mpml_name."</option>";
            }
        }else{
            $lists = "";
        }
        
        $callback = array('list_perimeter_level'=>$lists);
        echo json_encode($callback);
    }
    
    public function perimeter_alamat($perimeter_id){
        if($perimeter_id!=NULL){
            $perimeter = $this->master_model->mst_perimeter($perimeter_id)->row()->mpm_alamat;
        }else{
            $perimeter = "";
        }
        echo $perimeter;
    }
}
