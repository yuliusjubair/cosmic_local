<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aturlayanan extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('product_model','master_model','mperimeter_model'));
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
	    $data['title']="Dashboard";
	    $data['subtitle']="Manage Atur Layanan";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
			 $data['group'] =  $group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
			$data['product'] = $this->product_model->get_data_bygroupId($group);

			//print_r($data['user']);
			//exit;
			$data['perimeter'] = $this->mperimeter_model->get_perimeter_bymcid($mc_id);
	    $data['sts_pegawai'] = $this->master_model->mst_status_pegawai($cari='')->result();
	    $data['kabupaten'] = $this->master_model->mst_kabupaten($cari='')->result();
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    $data["menu"]=$this->master_model->menus($group);

	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('aturlayanan/list_product',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_add() {
			date_default_timezone_set('Asia/Jakarta');
			$this->_validate();
			$data = array();
			$data['error_string'] = array();
			$data['inputerror'] = array();
			$data['status'] = TRUE;

					$id_product = $this->input->post('modal_id_product');
					$kd_perusahaan = $this->input->post('kd_perusahaan_modal');
					$nama_perusahaan = $this->input->post('modal_nama_perusahaan');
					$nama_pj = $this->input->post('modal_nama_pj');
					$no_telp_pj = $this->input->post('modal_no_telp_pj');
					$email_pj = $this->input->post('modal_email_pj');
					$perimeter_list = $this->input->post('modal_perimeter_list');

					$user_id = $this->ion_auth->user()->row()->id;

				 $data = array(
					 "tbpa_mc_id"=>$kd_perusahaan,
					 "tbpa_nama_pj"=>$nama_pj,
					 "tbpa_no_tlp_pj"=>$no_telp_pj,
					 "tbpa_email_pj"=>$email_pj,
					 "tbpa_user_insert"=>$user_id,
					 "tbpa_mlp_id"=>$id_product,
					 "tbpa_perimeter"=>$perimeter_list,
				 );
				 // echo"<pre>";print_r($data);echo"</pre>";die;
				 $this->db->insert('table_pengajuan_atestasi', $data);

				/* $api_insert = $this->api_insert($user_id, $kd_perusahaan, $tanggal, $nama_kegiatan, $jenis_kegiatan,
						 $deskripsi, $file_sosialisasi1, $file_sosialisasi2);*/
				 $data = array('status'=>200,'message'=>'success');
				 echo json_encode($data);

	}

	private function _validate() {
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;


	    if($this->input->post('modal_nama_pj') == '') {
	        $data['inputerror'][] = 'modal_nama_pj';
	        $data['error_string'][] = 'Nama Penanggung Jawab is required';
	        $data['status'] = FALSE;
	    }

	    if($this->input->post('modal_no_telp_pj') == '') {
	        $data['inputerror'][] = 'modal_no_telp_pj';
	        $data['error_string'][] = 'Nomor Telepon Penanggung Jawab is required';
	        $data['status'] = FALSE;
	    }

	    if($this->input->post('modal_email_pj') == '') {
	        $data['inputerror'][] = 'modal_email_pj';
	        $data['error_string'][] = 'Email Penanggung Jawab is required';
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

		public function ajax_edit_product($id) {
					$product = $this->product_model->get_byid($id);
					if(isset($product)) {
						$data = array(
								"mlp_id" => $product->mlp_id,
								"mlp_desc" => $product->mlp_desc,
								"mlp_name" => $product->mlp_name,
								"mlp_active" => $product->mlp_active,
								"mlp_filename" => $product->mlp_filename,
								"mlp_file_syarat_ketentuan" => $product->mlp_file_syarat_ketentuan,
								"mlp_text_syarat_ketentuan" => $product->mlp_text_syarat_ketentuan,
								"mlp_by" => $product->mlp_by,

						 );
					}else{
							$data = array();
					}
				echo json_encode($data);
		}

		public function ajax_update_product() {
			$kd_perusahaan = $this->input->post('kd_perusahaan_modal');
			// print_r($_POST);die;
		    date_default_timezone_set('Asia/Jakarta');
		    $check = $this->input->post('check1');
		    if(!empty($check)){
		    	$c='t';
		    }else{
		    	$c='f';
		    }
			// echo $c;die;		    
				$data = array(
						"mlp_desc" => $this->input->post('modal_p_deskripsi'),
						"mlp_active" => $c,
						"mlp_text_syarat_ketentuan" => 	$this->input->post('modal_syarat'),
						"mlp_date_update" =>  date('Y-m-d H:i:s'),
						"mlp_user_update" =>  $this->ion_auth->user()->row()->id
				 );
				/* if(!empty($_FILES['modal_p_foto']['name'])) {
 		        $upload = $this->_do_upload($kd_perusahaan);
 		        $data['mlp_filename'] = $upload;
 				}*/
				if(!empty($_FILES['modal_p_file']['name'])) {
					 $upload = $this->_do_upload_file($kd_perusahaan);
					 $data['mlp_file_syarat_ketentuan'] = $upload;
			 }

		    $this->product_model->update(array('mlp_id' => $this->input->post('modal_p_id_product')), $data);

				$data = array('status'=>200,'message'=>'success');
				echo json_encode($data);
		}

		private function _do_upload($mc_id) {
		    set_time_limit(0);
		    ini_set('max_execution_time', 0);
		    ini_set('memory_limit', '-1');
		    ini_set('max_input_time', 3600);

		    if(!is_dir("uploads/product")) {
		        mkdir("uploads/product");
		    }

		    $config['upload_path']          = 'uploads/product/';
		    $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
		    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
		    $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

		    $this->load->library('upload', $config);
		    $this->upload->initialize($config);
		    if(!$this->upload->do_upload('modal_p_foto')) {

		        $data['inputerror'][] = 'modal_p_foto';
		        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
		        $data['status'] = FALSE;
		        echo json_encode($data);
		        exit();
		    }
		    return $this->upload->data('file_name');
		}

		public function _do_upload_file($mc_id) {
			set_time_limit(0);
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '-1');
			ini_set('max_input_time', 3600);

			if(!is_dir("uploads/product")) {
					mkdir("uploads/product");
			}

			$config['upload_path']          = 'uploads/product/';
			$config['allowed_types']        = 'pdf|PDF';
			$config['max_size']             = 2*1024; //set max size allowed in Kilobyte
			$config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('modal_p_file')) {

					$data['inputerror'][] = 'modal_p_file';
					$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
					$data['status'] = FALSE;
					echo json_encode($data);
					exit();
			}
			return $this->upload->data('file_name');
		}
}
