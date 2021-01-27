<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rumahsinggah extends CI_Controller {

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
	    $data['subtitle']="Data Rumah Singgah BUMN";

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
	    
	    $this->load->view('header',$data);
	    $this->load->view('rumahsinggah/index',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_list($mc_id) {
	    $this->load->helper('url');	    
	    $list = $this->rumahsinggah_model->get_datatables($mc_id);
	    //echo $this->db->last_query();die;
	    $data = array();
	    $no = 0;
	    foreach ($list as $sos) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $sos->nama_rumahsinggah;
	        $row[] = $sos->mpro_name;
	        $row[] = $sos->mkab_name;
	        $row[] = number_format($sos->membayar,2);
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
                        onclick=\"window.open('".base_url().'rumahsinggah/detail_rumahsinggah/'.$sos->id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";

	        $data[] = $row;
	    }
	    
	    $output = array(
	        //"draw" => $_POST['draw'],
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
	    //$data['menu_hide']="yes";

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
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;
	    $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    $data['pic'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,3);
	    $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
	    $data['kriteria']=$this->master_model->mst_kriteria_orang($cari='');
	    $data['fasilitas_rumah']=$this->master_model->mst_fasilitas_rumah()->result();

	    $this->load->view('header',$data);
	    $this->load->view('rumahsinggah/detail',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    //$this->_validate();
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    /*if (empty($_FILES['modal_foto_rumahsinggah']['name'])){
	        $data['inputerror'][] = 'modal_foto_rumahsinggah';
	        $data['error_string'][] = 'Foto Rumah Singgah 1 is required';
	        $data['status'] = FALSE;
	        
	        if($data['status'] === FALSE) {
	            echo json_encode($data);
	            exit();
	        }
	    }else{*/
	    	$kd_perusahaan = $this->input->post('kd_perusahaan_modal');
	        $nama = $this->input->post('nama');
	        $alamat = $this->input->post('alamat');
	        $provinsi = $this->input->post('provinsi');
	        $kota = $this->input->post('kabupaten');
	        $kapasitas = $this->input->post('kapasitas');
	        $ruangan = $this->input->post('ruangan');
	        $pic = $this->input->post('pic');
	        $no_pic = $this->input->post('no_pic');
	        $kasus = $this->input->post('kasus');
	        $keterangan = $this->input->post('keterangan');
	        $biaya = $this->input->post('biaya');
	        $biaya_membayar =!empty($this->input->post('biaya_membayar')) ?  $this->input->post('biaya_membayar') : 0;
			$fasilitas_rumah = $this->input->post('fasilitas_rumah');
			$kriteria = $this->input->post('kriteria');
			if($biaya != 'Membayar'){
				$biaya_membayar = 0;
			}
	    	// print_r($_POST);die;
	        
	        if ($_FILES['modal_foto_rumahsinggah']['tmp_name']!='') {
	            $file_name1 =$_FILES['modal_foto_rumahsinggah']['name'];
	            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	            $file_tmp1= $_FILES['modal_foto_rumahsinggah']['tmp_name'];
	            $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	            $data1 = file_get_contents($file_tmp1);
	            //$file = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	            $file = $file_name1;
	        }else{
	            $file = NULL;
	        }
	        
	       
	        $user_id = $this->ion_auth->user()->row()->id;
	        //set jenis kasus
	        $set_jenis='';
	        $set_jenis.="'";
			$set_jenis.=implode("','",$kasus)."'";

			//set fasilitasi Rumah
			$set_fasilitas='';
	        $set_fasilitas.="'";
			$set_fasilitas.=implode("','",$fasilitas_rumah)."'";

			//set kriteria orang
			$set_kriteria='';
	        $set_kriteria.="'";
			$set_kriteria.=implode("','",$kriteria)."'";
	        
	        $data = array(
	        	"mc_id"=>$kd_perusahaan,
	        	"alamat"=>$alamat,
	        	"prov_id"=>$provinsi,
	        	"kota_id"=>$kota,
	        	"kapasitas"=>$kapasitas,
	        	"ruangan_available"=>$ruangan,
	        	"pic_nik"=>$pic,
	        	"pic_kontak"=>$no_pic,
	        	"nama_rumahsinggah"=>$nama,
	        	"keterangan"=>$keterangan,
	        	"user_insert" => $user_id,
				"date_insert" => date("Y-m-d H:i:s"),
	        	"jenis_kasus" => $set_jenis,
	        	"biaya_ygdiperlukan" => $biaya,
	        	"membayar" => $biaya_membayar,
				"file" => $file,
				"kriteria_id" => $set_kriteria,
				"fas_rumah_id" => $set_fasilitas
	        );
	        // echo"<pre>";print_r($data);echo"</pre>";die;
	        $this->db->insert('table_rumahsinggah', $data);
	        if(!empty($file)){
		        $this->_do_upload($kd_perusahaan, date('Y-m-d'), $file);
		    }
	       /* $api_insert = $this->api_insert($user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	            $deskripsi, $file_sosialisasi1, $file_sosialisasi2);*/
	        $data = array('status'=>200,'message'=>'success');
	        echo json_encode($data);
	    //}
	}
	
	public function ajax_edit($id) {
		$data = $this->rumahsinggah_model->get_byid($id);

		$status_kasus = $this->master_model->mst_status_kasus($cari='')->result();
		$fasilitas_rumah=$this->master_model->mst_fasilitas_rumah()->result();
		$kriteria=$this->master_model->mst_kriteria_orang($cari='')->result();
	    
	    $output = array(
	    	'data'=>$data,
	    	'status_kasus' => $status_kasus,
	    	'kriteria' => $kriteria,
	    	'fasilitas_rumah' => $fasilitas_rumah
	    );

	    echo json_encode($output);
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
	
	private function _do_upload($mc_id, $tanggal, $file) {
	    set_time_limit(0);
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '-1');
	    ini_set('max_input_time', 3600);
	    
	    if(!is_dir("uploads/rumahsinggah/")) {
	        mkdir("uploads/rumahsinggah/");
	    }

	    if(!is_dir("uploads/rumahsinggah/".$mc_id)) {
	        mkdir("uploads/rumahsinggah/".$mc_id);
	        
	        if(!is_dir("uploads/rumahsinggah/".$mc_id."/".$tanggal)) {
	            mkdir("uploads/rumahsinggah/".$mc_id."/".$tanggal);
	        }
	    }
	    
	    $config['upload_path']          = 'uploads/rumahsinggah/'.$mc_id.'/'.$tanggal.'/';
	    if(is_file($config['upload_path']))
		{
		    chmod($config['upload_path'], 777); ## this should change the permissions
		}
	    $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
	    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = $file; //just milisecond timestamp fot unique name
	    
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if(!$this->upload->do_upload('modal_foto_rumahsinggah')) {
	      
	        $data['inputerror'][] = 'modal_foto_rumahsinggah';
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    return $this->upload->data('file_name');
	}
	
	public function ajax_delete($id) {
	    /*$sosialisasi = $this->rumahsinggah_model->get_byid($id);
	    
	    if(file_exists('uploads/sosialisasi/'.$sosialisasi->mc_id.'/'.$sosialisasi->file)){
	        unlink('uploads/sosialisasi/'.$sosialisasi->mc_id.'/'.$sosialisasi->file);
	    }*/
	    
	    $delete = $this->rumahsinggah_model->delete_byid($id);
	    if($delete){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Rumah Singgah BUMN'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Delete Rumah Singgah BUMN'));
	    }
	}
	
	public function api_insert($user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    $deskripsi, $file_sosialisasi1, $file_sosialisasi2) {
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
	            'file_sosialisasi2' => $file_sosialisasi2
	        )
	    ));
	    
	    $response = curl_exec($curl);
	    curl_close($curl);
	    echo $response;
	}
	
	public function api_update($id, $user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    $deskripsi, $file_sosialisasi1, $file_sosialisasi2) {
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
                'file_sosialisasi2' => $file_sosialisasi2
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
	    // $this->_validate();
	    $data = array();
		//var_dump($this->input->post('kd_perusahaan_modal'));die;

	    // $kd_perusahaan = $this->input->post('kd_perusahaan_modal');
	    // $tanggal = $this->input->post('modal_tgl');
	    // $nama_kegiatan = $this->input->post('modal_kegiatan');
	    // $jenis_kegiatan = $this->input->post('modal_kategori');
	    // $deskripsi = $this->input->post('modal_deskripsi');

	    $id = $this->input->post('id');
		$kd_perusahaan = $this->input->post('kd_perusahaan_modal');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$provinsi = $this->input->post('provinsi');
		$kota = $this->input->post('kabupaten');
		$kapasitas = $this->input->post('kapasitas');
		$ruangan = $this->input->post('ruangan');
		$pic = $this->input->post('pic');
		$kasus = $this->input->post('kasus');
		$keterangan = $this->input->post('keterangan');
		$biaya = $this->input->post('biaya');
		$biaya_membayar =!empty($this->input->post('biaya_membayar')) ?  $this->input->post('biaya_membayar') : 0;
		$fasilitas_rumah = $this->input->post('fasilitas_rumah');
		$kriteria = $this->input->post('kriteria');
		if($biaya != 'Membayar'){
			$biaya_membayar = 0;
		}

		$set_jenis='';
		$set_jenis.="'";
		$set_jenis.=implode("','",$kasus)."'";
	    
	    //set fasilitasi Rumah
		$set_fasilitas='';
        $set_fasilitas.="'";
		$set_fasilitas.=implode("','",$fasilitas_rumah)."'";

		//set kriteria orang
		$set_kriteria='';
        $set_kriteria.="'";
		$set_kriteria.=implode("','",$kriteria)."'";

	    if ($_FILES['modal_foto_rumahsinggah']['tmp_name']!='') {
	        $file_name1 =$_FILES['modal_foto_rumahsinggah']['name'];
	        $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	        $file_tmp1= $_FILES['modal_foto_rumahsinggah']['tmp_name'];
	        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	        $data1 = file_get_contents($file_tmp1);
	        $file = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	        $file = $file_name1;
	    }else{
	        $file = NULL;
	    }
	    
	   /* if ($_FILES['modal_foto_sosialisasi2']['tmp_name']!='') {
	        $file_name2 =$_FILES['modal_foto_sosialisasi2']['name'];
	        $file_ext2 =  pathinfo($file_name2, PATHINFO_EXTENSION);
	        $file_tmp2= $_FILES['modal_foto_sosialisasi2']['tmp_name'];
	        $type2 = pathinfo($file_tmp2, PATHINFO_EXTENSION);
	        $data2 = file_get_contents($file_tmp2);
	        $file_sosialisasi2 = 'data:image/'.$type2.';base64,'.base64_encode($data2);
	    }else{
	        $file_sosialisasi2 = NULL;
	    }*/
	    $user_id = $this->ion_auth->user()->row()->id;
	    
	    // $api_update = $this->api_update($id, $user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    //     $deskripsi, $file_sosialisasi1, $file_sosialisasi2);
	    
		// echo $api_update;
		if(!empty($file)){
			$data = array(
				"mc_id"=>$kd_perusahaan,
				"alamat"=>$alamat,
				"prov_id"=>$provinsi,
				"kota_id"=>$kota,
				"kapasitas"=>$kapasitas,
				"ruangan_available"=>$ruangan,
				"pic_nik"=>$pic,
				"nama_rumahsinggah"=>$nama,
				"keterangan"=>$keterangan,
				"user_update" => $user_id,
				"date_update" => date("Y-m-d H:i:s"),
				"jenis_kasus" => $set_jenis,
				"biaya_ygdiperlukan" => $biaya,
				"membayar" => $biaya_membayar,
				"file" => $file,
				"kriteria_id" => $set_kriteria,
				"fas_rumah_id" => $set_fasilitas
			);
			$this->_do_upload($kd_perusahaan, date('Y-m-d'), $file);
		}else{
			$data = array(
				"mc_id"=>$kd_perusahaan,
				"alamat"=>$alamat,
				"prov_id"=>$provinsi,
				"kota_id"=>$kota,
				"kapasitas"=>$kapasitas,
				"ruangan_available"=>$ruangan,
				"pic_nik"=>$pic,
				"nama_rumahsinggah"=>$nama,
				"keterangan"=>$keterangan,
				"user_update" => $user_id,
				"date_update" => date("Y-m-d H:i:s"),
				"jenis_kasus" => $set_jenis,
				"biaya_ygdiperlukan" => $biaya,
				"membayar" => $biaya_membayar,
				"kriteria_id" => $set_kriteria,
				"fas_rumah_id" => $set_fasilitas
			);
		}

		
		
		//print_r($data);die;
		$this->db->where('id',$id);
		$this->db->update('table_rumahsinggah',$data);
		$data = array('status'=>200,'message'=>'success');
		echo json_encode($data);
	}
}