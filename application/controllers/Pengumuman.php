<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('pengumuman_model','master_model','profile_model'));
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
	    $data['title']="Setting";
	    $data['subtitle']="Data Pengumuman";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
		}
	    $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    $data["menu"]=$this->master_model->menus($group);
	    $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    $data['pic'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,3);
	    $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
	    $data['kriteria']=$this->master_model->mst_kriteria_orang($cari='');
	    $data['fasilitas_rumah']=$this->master_model->mst_fasilitas_rumah()->result();
	    
	    $this->load->view('header',$data);
	    $this->load->view('pengumuman/index',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_list() {
	    $this->load->helper('url');	    
	    $list = $this->pengumuman_model->get_datatables();
	    //echo $this->db->last_query();die;
	    $data = array();
	    $no = 0;
	    foreach ($list as $sos) {

	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $sos->judul;
	        $row[] = $sos->deskripsi;
	        $row[] = date('d F Y', strtotime($sos->end_date));
	        $row[] = $sos->status;
	        //$row[] = $sos->id;

	        if($sos->file_image){
	            $row[] = '<center><img src="'.base_url('/uploads/pengumuman/'.$sos->file_image).'"
                          class="img-responsive" width="150" height="150"/>
                          </center>';
	        }else{
	            $row[] = '(No photo)';
	        }
	        
	        $row[] = '  <a class="btn btn-md btn-primary" href="javascript:void(0)" title="Edit"
                        onclick="edit_pengumuman('."'".$sos->id."'".')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>
                        <a class="btn btn-md btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_pengumuman('."'".$sos->id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
             /* $row[] = "<a class='' href='javascript:void(0)'' title='Detail Rumah Singgah'
                        onclick=\"window.open('".base_url().'pengumuman/detail_pengumuman/'.$sos->id."','_self');\"><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";*/

	        $data[] = $row;
	    }
	    
	    $output = array(
	        //"draw" => $_POST['draw'],
	        "recordsTotal" => $this->pengumuman_model->count_all(),
	        "recordsFiltered" => $this->pengumuman_model->count_filtered(),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	public function detail_pengumuman($id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['row'] = $this->pengumuman_model->get_byid($id);
	    $data['cari']="";
	    $data['title']="Pengumuman";
	    $data['subtitle']="Detail Pengumuman";
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
	    $this->load->view('pengumuman/detail',$data);
	    $this->load->view('footer',$data);
	}
	
	public function ajax_add() {
	    date_default_timezone_set('Asia/Jakarta');
	    $this->_validate();
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    if (empty($_FILES['modal_foto_pengumuman']['name'])){
	        $data['inputerror'][] = 'modal_foto_pengumuman';
	        $data['error_string'][] = 'Foto Rumah Singgah 1 is required';
	        $data['status'] = FALSE;
	        
	        if($data['status'] === FALSE) {
	            echo json_encode($data);
	            exit();
	        }
	    }else{
	    	$deskripsi = $this->input->post('deskripsi');
	        $judul = $this->input->post('judul');
	        $modal_tgl = date('Y-m-d', strtotime($this->input->post('modal_tgl')));
	        $status = $this->input->post('status');
	        
	        if ($_FILES['modal_foto_pengumuman']['tmp_name']!='') {
	            $file_name1 =$_FILES['modal_foto_pengumuman']['name'];
	            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	            $file_tmp1= $_FILES['modal_foto_pengumuman']['tmp_name'];
	            $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	            $data1 = file_get_contents($file_tmp1);
	            //$file = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	            $file = preg_replace("/[^a-zA-Z0-9.]/", "_",$file_name1);
	        }else{
	            $file = NULL;
	        }
	        
	       
	        $user_id = $this->ion_auth->user()->row()->id;
	        
	        $data = array(
	        	"judul"=>$judul,
	        	"deskripsi"=>$deskripsi,
	        	"end_date"=>$modal_tgl,
	        	"status"=>$status,
	        	"user_insert"=>$user_id,
	        	"date_insert"=>date('Y-m-d'),
	        	"file_image" => $file
	        );
	        // echo"<pre>";print_r($data);echo"</pre>";die;
	        $this->db->insert('master_pengumuman', $data);
	        if(!empty($file)){
		        $this->_do_upload(date('Y-m-d'), $file);
		    }
	        $data = array('status'=>200,'message'=>'success');
	        echo json_encode($data);
	    }
	}
	
	public function ajax_edit($id) {
		$data = $this->pengumuman_model->get_byid($id);
	    
	    $output = array(
	    	'data'=>$data,
	    );

	    echo json_encode($output);
	}
	
	private function _validate() {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    
	    if($this->input->post('judul') == '') {
	        $data['inputerror'][] = 'judul';
	        $data['error_string'][] = 'Judul is required';
	        $data['status'] = FALSE;
	    }

	     if($this->input->post('deskripsi') == '') {
	        $data['inputerror'][] = 'deskripsi';
	        $data['error_string'][] = 'Deskripsi is required';
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
	
	private function _do_upload($tanggal, $file) {
	    set_time_limit(0);
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '-1');
	    ini_set('max_input_time', 3600);
	    
	    if(!is_dir("uploads/pengumuman/")) {
	        mkdir("uploads/pengumuman/");
	    }

	    /*if(!is_dir("uploads/pengumuman/".$tanggal)) {
	        mkdir("uploads/pengumuman/".$tanggal);
	    }*/
	    
	    $config['upload_path']          = 'uploads/pengumuman/';
	    if(is_file($config['upload_path']))
		{
		    chmod($config['upload_path'], 777); ## this should change the permissions
		}
		$config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
	    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = $file; //just milisecond timestamp fot unique name
	    
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if(!$this->upload->do_upload('modal_foto_pengumuman')) {
	      
	        $data['inputerror'][] = 'modal_foto_pengumuman';
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    return $this->upload->data('file_name');
	}
	
	public function ajax_delete($id) {
	    /*$sosialisasi = $this->pengumuman_model->get_byid($id);
	    
	    if(file_exists('uploads/sosialisasi/'.$sosialisasi->mc_id.'/'.$sosialisasi->file)){
	        unlink('uploads/sosialisasi/'.$sosialisasi->mc_id.'/'.$sosialisasi->file);
	    }*/
	    
	    $delete = $this->pengumuman_model->delete_byid($id);
	    if($delete){
	        echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Pengumuman'));
	    }else{
	        echo json_encode(array("status" => 500, "message" => 'Gagal Delete Pengumuman'));
	    }
	}
	
	public function ajax_update() {
	    date_default_timezone_set('Asia/Jakarta');
	    // $this->_validate();
	    $data = array();
		$id = $this->input->post('id');
		$deskripsi = $this->input->post('deskripsi');
        $judul = $this->input->post('judul');
        $modal_tgl = date('Y-m-d', strtotime($this->input->post('modal_tgl')));
        $status = $this->input->post('status');

	    if ($_FILES['modal_foto_pengumuman']['tmp_name']!='') {
	        $file_name1 =$_FILES['modal_foto_pengumuman']['name'];
	        $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	        $file_tmp1= $_FILES['modal_foto_pengumuman']['tmp_name'];
	        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	        $data1 = file_get_contents($file_tmp1);
	        $file = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	        $file = preg_replace("/[^a-zA-Z0-9.]/", "_",$file_name1);
	    }else{
	        $file = NULL;
	    }
	    
	    $user_id = $this->ion_auth->user()->row()->id;
	    
	    // $api_update = $this->api_update($id, $user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
	    //     $deskripsi, $file_sosialisasi1, $file_sosialisasi2);
	    
		// echo $api_update;
		if(!empty($file)){
			$data = array(
	        	"judul"=>$judul,
	        	"deskripsi"=>$deskripsi,
	        	"end_date"=>$modal_tgl,
	        	"status"=>$status,
	        	"user_update"=>$user_id,
	        	"date_update"=>date('Y-m-d'),
	        	"file_image" => $file
	        );
			$this->_do_upload(date('Y-m-d'), $file);
		}else{
			$data = array(
				"judul"=>$judul,
	        	"deskripsi"=>$deskripsi,
	        	"end_date"=>$modal_tgl,
	        	"status"=>$status,
	        	"user_update"=>$user_id,
	        	"date_update"=>date('Y-m-d')
			);
		}

		
		
		//print_r($data);die;
		$this->db->where('id',$id);
		$this->db->update('master_pengumuman',$data);
		$data = array('status'=>200,'message'=>'success');
		echo json_encode($data);
	}
}