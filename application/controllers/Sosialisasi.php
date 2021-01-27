<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sosialisasi extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('sosialisasi_model','master_model'));
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
	    $data['title']="Event";
	    $data['subtitle']="Data Event Perusahaan";

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

	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('sosialisasi/list_sosialisasi',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_list($mc_id) {
	    $this->load->helper('url');
	    $list = $this->sosialisasi_model->get_datatables($mc_id);

	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $sos) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $sos->ts_nama_kegiatan;
	        $row[] = $sos->ts_tanggal;
	        $row[] = $sos->mslk_name;
	        $row[] = substr($sos->ts_deskripsi,0,50);
	        if($sos->ts_file1){
	            $row[] = '<center><img src="'.base_url('/uploads/sosialisasi/'.$sos->ts_mc_id.'/'.$sos->ts_file1).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
	        }else{
	            $row[] = '(No photo)';
	        }
	        if($sos->ts_file2){
	            $row[] = '<center><img src="'.base_url('/uploads/sosialisasi/'.$sos->ts_mc_id.'/'.$sos->ts_file2).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
	        }else{
	            $row[] = '(No photo)';
	        }
	        /*$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit"
                        onclick="edit_sosialisasi('."'".$sos->ts_id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>
                        <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_sosialisasi('."'".$sos->ts_id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';*/
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Event'
                        onclick=\"window.open('".base_url().'sosialisasi/detail_event/'.$sos->ts_id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";

	        $data[] = $row;
	    }

	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->sosialisasi_model->count_all($mc_id),
	        "recordsFiltered" => $this->sosialisasi_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	public function detail_event($id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['row'] = $this->sosialisasi_model->get_byid($id);
	    $data['cari']="";
	    $data['title']="Event";
	    $data['subtitle']="Detail Event";
	    $data['error']="";
	    //$data['menu_hide']="yes";
	    $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('sosialisasi/detail_event',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    $this->_validate();
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    if (empty($_FILES['modal_foto_sosialisasi']['name'])){
	        $data['inputerror'][] = 'modal_foto_sosialisasi';
	        $data['error_string'][] = 'Foto Sosialisasi 1 is required';
	        $data['status'] = FALSE;

	        if($data['status'] === FALSE) {
	            echo json_encode($data);
	            exit();
	        }
	    }else{
	        $kd_perusahaan = $this->input->post('kd_perusahaan_modal');
	        $tanggal = $this->input->post('modal_tgl');
	        $nama_kegiatan = $this->input->post('modal_kegiatan');
	        $jenis_kegiatan = $this->input->post('modal_kategori');
	        $deskripsi = $this->input->post('modal_deskripsi');
					$checklist_dampak = $this->input->post('modal_ceklis_dampak');
					if($checklist_dampak==true){
						$checklist_dampak = 1;
					}else{
						$checklist_dampak = 0;
					}
	        $bulan = $this->input->post('modal_bulan');
	        $persen_dampak = $this->input->post('modal_persen_bulan');
	        $persen_dampak_all = $this->input->post('modal_persen_all_bulan');

	        if ($_FILES['modal_foto_sosialisasi']['tmp_name']!='') {
	            $file_name1 =$_FILES['modal_foto_sosialisasi']['name'];
	            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	            $file_tmp1= $_FILES['modal_foto_sosialisasi']['tmp_name'];
	            $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	            $data1 = file_get_contents($file_tmp1);
	            $file_sosialisasi1 = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	        }else{
	            $file_sosialisasi1 = NULL;
	        }

	        if ($_FILES['modal_foto_sosialisasi2']['tmp_name']!='') {
	            $file_name2 =$_FILES['modal_foto_sosialisasi2']['name'];
	            $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
	            $file_tmp2= $_FILES['modal_foto_sosialisasi2']['tmp_name'];
	            $type2 = pathinfo($file_tmp2, PATHINFO_EXTENSION);
	            $data2 = file_get_contents($file_tmp2);
	            $file_sosialisasi2 = 'data:image/'.$type2.';base64,'.base64_encode($data2);
	        }else{
	            $file_sosialisasi2 = NULL;
	        }
	        $user_id = $this->ion_auth->user()->row()->id;

	        $api_insert = $this->api_insert($user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	            $deskripsi, $file_sosialisasi1, $file_sosialisasi2,$bulan,$persen_dampak,$persen_dampak_all,$checklist_dampak);

	        echo $api_insert;
	    }
	}

	public function ajax_edit($id) {
	    $data = $this->sosialisasi_model->get_byid($id);
	    echo json_encode($data);
	}

	private function _validate() {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;

	    if($this->input->post('modal_kegiatan') == '') {
	        $data['inputerror'][] = 'modal_kegiatan';
	        $data['error_string'][] = 'Kegiatan is required';
	        $data['status'] = FALSE;
	    }

	    if($this->input->post('modal_tgl') == '') {
	        $data['inputerror'][] = 'modal_tgl';
	        $data['error_string'][] = 'Tanggal is required';
	        $data['status'] = FALSE;
	    }

	    if($data['status'] === FALSE) {
	        echo json_encode($data);
	        exit();
	    }else{
	        $data['inputerror'][] = '';
	        $data['error_string'][] = '';
	        $data['status'] = TRUE;
	    }
	}

	private function _do_upload($mc_id, $tanggal) {
	    set_time_limit(0);
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '-1');
	    ini_set('max_input_time', 3600);

	    if(!is_dir("uploads/sosialisasi/".$mc_id)) {
	        mkdir("uploads/sosialisasi/".$mc_id);

	        if(!is_dir("uploads/sosialisasi/".$mc_id."/".$tanggal)) {
	            mkdir("uploads/sosialisasi/".$mc_id."/".$tanggal);
	        }
	    }

	    $config['upload_path']          = 'uploads/sosialisasi/'.$mc_id.'/'.$tanggal.'/';
	    $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
	    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if(!$this->upload->do_upload('modal_foto_sosialisasi')) {

	        $data['inputerror'][] = 'modal_foto_sosialisasi';
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    return $this->upload->data('file_name');
	}

	public function ajax_delete($id) {
	    $sosialisasi = $this->sosialisasi_model->get_byid($id);

	    if(file_exists('uploads/sosialisasi/'.$sosialisasi->ts_mc_id.'/'.$sosialisasi->ts_tanggal.'/'.$sosialisasi->ts_file1)){
	        unlink('uploads/sosialisasi/'.$sosialisasi->ts_mc_id.'/'.$sosialisasi->ts_tanggal.'/'.$sosialisasi->ts_file1);
	    }

	    $delete = $this->sosialisasi_model-> delete_byid($id);
	    if($delete){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Sosialisasi'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Delete Sosialisasi'));
	    }
	}

	public function api_insert($user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    $deskripsi, $file_sosialisasi1, $file_sosialisasi2,$bulan,$persen_dampak,$persen_dampak_all,$checklist_dampak) {
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."sosialisasi/webupload_json/$user_id",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "POST",
	        CURLOPT_POSTFIELDS => array(
	            'kd_perusahaan' => $kd_perusahaan,
	            'tanggal' => $tanggal,
	            'nama_kegiatan' => $nama_kegiatan,
	            'jenis_kegiatan' => $jenis_kegiatan,
	            'deskripsi' => $deskripsi,
	            'file_sosialisasi1' => $file_sosialisasi1,
	            'file_sosialisasi2' => $file_sosialisasi2,
	            'bulan_kegiatan' => $bulan,
	            'persen_dampak' => $persen_dampak,
	            'persen_dampak_keseluruhan' => $persen_dampak_all,
	            'checklist_dampak' => $checklist_dampak
	        )
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);
	    echo $response;
	}

	public function api_update($id, $user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    $deskripsi, $file_sosialisasi1, $file_sosialisasi2,$bulan,$persen_dampak,$persen_dampak_all,$checklist_dampak) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."sosialisasi/webupdate_json/$user_id/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'kd_perusahaan' => $kd_perusahaan,
                'tanggal' => $tanggal,
                'nama_kegiatan' => $nama_kegiatan,
                'jenis_kegiatan' => $jenis_kegiatan,
                'deskripsi' => $deskripsi,
                'file_sosialisasi1' => $file_sosialisasi1,
                'file_sosialisasi2' => $file_sosialisasi2,
								'bulan_kegiatan' => $bulan,
								'persen_dampak' => $persen_dampak,
								'persen_dampak_keseluruhan' => $persen_dampak_all,
								'checklist_dampak' => $checklist_dampak
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
	}

	function file_get_contents_curl($url) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    echo $data;
	}

	public function ajax_update() {
	    date_default_timezone_set('Asia/Jakarta');
	    $this->_validate();
	    $data = array();
	    //var_dump($this->input->post('kd_perusahaan_modal'));die;
	    $kd_perusahaan = $this->input->post('kd_perusahaan_modal');
	    $tanggal = $this->input->post('modal_tgl');
	    $nama_kegiatan = $this->input->post('modal_kegiatan');
	    $jenis_kegiatan = $this->input->post('modal_kategori');
	    $deskripsi = $this->input->post('modal_deskripsi');
			$checklist_dampak = $this->input->post('modal_ceklis_dampak');
		 	$bulan = $this->input->post('modal_bulan');
		 	$persen_dampak = $this->input->post('modal_persen_bulan');
		 	$persen_dampak_all = $this->input->post('modal_persen_all_bulan');
	    $id = $this->input->post('modal_id');
			if($checklist_dampak==true){
				$checklist_dampak = 1;
			}else{
				$checklist_dampak = 0;
			}


	    if ($_FILES['modal_foto_sosialisasi']['tmp_name']!='') {
	        $file_name1 =$_FILES['modal_foto_sosialisasi']['name'];
	        $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	        $file_tmp1= $_FILES['modal_foto_sosialisasi']['tmp_name'];
	        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	        $data1 = file_get_contents($file_tmp1);
	        $file_sosialisasi1 = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	    }else{
	        $file_sosialisasi1 = NULL;
	    }

	    if ($_FILES['modal_foto_sosialisasi2']['tmp_name']!='') {
	        $file_name2 =$_FILES['modal_foto_sosialisasi2']['name'];
	        $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
	        $file_tmp2= $_FILES['modal_foto_sosialisasi2']['tmp_name'];
	        $type2 = pathinfo($file_tmp2, PATHINFO_EXTENSION);
	        $data2 = file_get_contents($file_tmp2);
	        $file_sosialisasi2 = 'data:image/'.$type2.';base64,'.base64_encode($data2);
	    }else{
	        $file_sosialisasi2 = NULL;
	    }
	    $user_id = $this->ion_auth->user()->row()->id;

	    $api_update = $this->api_update($id, $user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	        $deskripsi, $file_sosialisasi1, $file_sosialisasi2,$bulan,$persen_dampak,$persen_dampak_all,$checklist_dampak);

	    echo $api_update;
	}

	public function _do_upload_rk($mc_id) {
	    $config['upload_path']          = 'uploads/rencanakerja/';
	    $config['allowed_types']        = 'pdf';
	    $config['max_size']             = 30*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

	    $this->load->library('upload', $config);

	    if(!$this->upload->do_upload('file_rk')) {
	        $data['inputerror'][] = 'file_rk';
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }

	    return $this->upload->data('file_name');
	}

	public function ajaxrk_add() {
	    $kd_perusahaan = $this->input->post('kd_perusahaan_rk');
	    $curl = curl_init();

	    if(!empty($_FILES['file_rk']['name'])) {
	        $upload = $this->_do_upload_rk($kd_perusahaan);
	        $data['tbrk_mc_id'] = $kd_perusahaan;
	        $data['tbrk_filename'] = $upload;

	        $tbrk = $this->sosialisasi_model->get_tbrk_byid($kd_perusahaan);
	        $delete = $this->sosialisasi_model->delete_tbrk($kd_perusahaan);
	        if(file_exists('uploads/rencanakerja/'.$tbrk->tbrk_filename)
	           && $tbrk->tbrk_filename){
	           unlink('uploads/rencanakerja/'.$tbrk->tbrk_filename);
	        }
	        $insert = $this->sosialisasi_model->save_tbrk($data);
	        echo json_encode(array("status"=>200, "message" => 'Berhasil Upload Rencana Kerja'));
	    }else{
	        echo json_encode(array("status"=>404,"message" => 'File Not Found'));
	    }
	}

	public function download_rk($mc_id) {
	    $rencanakerja = $this->sosialisasi_model->get_tbrk_byid($mc_id);
	    if($rencanakerja==NULL){
	        echo 0;
	    }else{
	        echo $rencanakerja->tbrk_filename;
	    }
	}
}
