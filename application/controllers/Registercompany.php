<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registercompany extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('Companynonbumn_model','master_model','Companyatestasi_model'));
        $this->load->database();	  
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
	    
	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Registrasi Perusahaan";
	    $data['subtitle']="Registrasi Perusahaan";
	    
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);

	    $this->load->view('header',$data);
	    $this->load->view('register_company/list_company',$data);
	    $this->load->view('footer',$data);
	}
	
	function get_company($status=NULL) {
	    $list = $this->Companynonbumn_model->get_company($status);
	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $field) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $field->mc_name;
	        $row[] = $field->mpro_name;
	        $row[] = date('d/m/Y',strtotime($field->mc_date_insert));
            $row[] = $field->mc_status;
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
	        $row[] = $field->mc_id;
	        $data[] = $row;
	    }
	    
	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->Companynonbumn_model->count_all($status),
	        "recordsFiltered" => $this->Companynonbumn_model->count_filtered($status),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

    function get_all_company($status=NULL) {
        $list = $this->Companyatestasi_model->get_company($status);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            // print_r($field);
            if($field->mc_status_atestasi=="1"){
                $status = "Disetujui";
            }elseif ($field->mc_status_atestasi=="2") {
                $status = "Menunggu Persetujuan";
            }elseif($field->mc_status_atestasi=="0"){
                $status = "Belum Disetujui";
            }elseif($field->mc_status_atestasi=="3"){
                $status = "Belum Disetujui";
            }elseif($field->mc_status_atestasi=="4"){
                $status = "Di Tolak";
            }else{
                $status = "Belum Disetujui";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->mc_name;
            $row[] = $field->mc_update_date_atestasi;
            $row[] = $status;
            $row[] = $field->mc_nama_pic_atestasi;
            // $row[] = date('d/m/Y',strtotime($field->mc_date_insert));
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
            $row[] = $field->mc_id;
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Companyatestasi_model->count_all(),
            "recordsFiltered" => $this->Companyatestasi_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function detail($id){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['row'] = $this->Companynonbumn_model->get_byid($id);
        $data['cari']="";
        $data['title']="Registrasi Perusahaan";
        $data['subtitle']="Detail Perusahaan";
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
        
        $this->load->view('header',$data);
        $this->load->view('register_company/detail',$data);
        $this->load->view('footer',$data);
    }

	public function ajax_perusahaan_byprovinsi_all() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/perusahaan_byprovinsi_all",
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

    public function ajax_perusahaan_byindustri_all() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/perusahaan_byindustri_all",
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

    public function ajax_perusahaan_bypegawai() {
        $query = $this->Companynonbumn_model->get_company_pegawai();
        $total=0;
        $data = [];
        foreach ($query->result() as $res) {
            $row = array();
            $row[] = $res->mc_jumlah_pegawai;
            $row[] = $res->cnt. " Perusahaan";
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

    public function update_verifikasi() {
        $this->load->library('email_lib');
        date_default_timezone_set('Asia/Jakarta');
        //$this->_validate();
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
       
            $modal_id = $this->input->post('modal_id');
            $username = $this->input->post('username');
            $email = trim($this->input->post('email'));
            $password = trim($this->input->post('password'));
            
            $data = array(
                "mc_status"=>"Verifikasi",
            );

            if($this->db->update('master_company', $data, array('mc_id' => $modal_id)))
            {
                //$username = $username.time();
                
                //INSERT KE MASTER USER
                /*$password_default = "P@ssw0rd";
                $password = password_hash($password_default,true);*/
                /*$data = array(
                    'username' => $username,
                    'password' => $password,
                    'first_name' => $username,
                    'mc_id' => $modal_id,
                    'msc_id' => '2',
                    'active' => '1'
                );
                $this->db->insert('app_users', $data);*/

                $cek = $this->db->query("SELECT id from app_users where Username='$username'")->row();
                if(isset($cek->id))
                {
                    //INSERT ke user groups
                    $datax = array('group_id'=>2);
                    
                    $this->db->update('app_users_groups', $datax, array('user_id'=>$cek->id));
                }

                //send email
                //$this->email_lib->send_email($email, $username);

                $data = array('status'=>200,'message'=>'success');
            }
            else
            {
                $data = array('status'=>200,'message'=>'failed');
            }
            echo json_encode($data);    
    }

    public function update_username($username, $username_lama) {
        
        $update =  $this->db->query("update app_users set username='$username' where username='$username_lama'");

        if($update){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Ubah Username'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Ubah Username'));
        }
    }

    public function reset_password($username) {
        $password = password_hash('P@ssw0rd',true);
        
        $update =  $this->db->query("update app_users set password='$password' where username='$username'");

        if($update){
            echo json_encode(array("status" => 200, "message" => 'Berhasil Reset Password'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Reset Password'));
        }
    }

    public function hapus_perusahaan($mc_id, $username) {
        
        $update =  $this->db->query("update app_users set active='0' where username='$username'");

        if($update){
            $this->db->query("update master_company set mc_status='Non Aktif' Where mc_id='".$mc_id."'");
            echo json_encode(array("status" => 200, "message" => 'Berhasil Non Aktifkan Perusahaan'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Non Aktifkan Perusahaan'));
        }
    }

    public function aktif_perusahaan($mc_id, $username) {
        
        $update =  $this->db->query("update app_users set active='1' where username='$username'");

        if($update){
            $this->db->query("update master_company set mc_status='Verifikasi' Where mc_id='".$mc_id."'");
            echo json_encode(array("status" => 200, "message" => 'Berhasil Aktifkan Perusahaan'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Aktifkan Perusahaan'));
        }
    }
}