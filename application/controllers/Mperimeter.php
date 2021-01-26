<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperimeter extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('mperimeter_model','master_model','profile_model'));
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

	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Monitoring Perimeter";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
			$data['group']=$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
	    }else{
	        $mc_id = '';
	    }
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    // echo $this->db->last_query();die;
	    $data["menu"]=$this->master_model->menus($group);
			 $data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
			 $data['kategori_perimeter'] = $this->master_model->mst_perimeter_kategori($cari='')->result();

	    $this->load->view('header',$data);
	    $this->load->view('mperimeter/list_mperimeter',$data);
	    $this->load->view('footer',$data);
	}




	public function ajax_list($mc_id) {
	    $this->load->helper('url');

	    $list = $this->mperimeter_model->get_datatables($mc_id);

	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $mperimeter) {
	        $no++;
	        $row = array();
	        $url_detail ='
                        <a class="btn btn-md btn-white btn-default" href="javascript:void(0)" title="Detail"
                        onclick="window.open('."'".base_url()."mperimeter/form_perimeter/".$mperimeter->mpm_id."','_self'".');">
                        <i class="ace-icon fa fa-list bigger-120"></i></a>';

	        $row[] = $no;
	        $row[] = $mperimeter->mr_name;
	        $row[] = $mperimeter->mpm_name;
	        $row[] = $mperimeter->jml_lvl. " lantai";
	        $row[] = $mperimeter->mpro_name;
	        $row[] = $mperimeter->mkab_name;
	        $row[] = $url_detail;
	        $row[] = '<a class="btn btn-md btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_perimeter('."'".$mperimeter->mpm_id."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
	        $data[] = $row;
	    }

	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->mperimeter_model->count_all($mc_id),
	        "recordsFiltered" => $this->mperimeter_model->count_filtered($mc_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	public function ajax_cekaktivitas($id) {
	    $aktivitas =  $this->mperimeter_model->count_transaksi_mpml($id);

	    if($aktivitas > 0){
	        echo json_encode(array("status" => 200, "cnt"=>  $aktivitas, "message" => 'Perimeter telah memiliki aktivitas. Penghapusan akan berdampak pada pelaporan dari perusahaan'));
	    }else{
	        echo json_encode(array("status" => 200, "cnt"=>  $aktivitas));
	    }
	}

	public function ajax_add() {

			$data = array(
					'nama_perimeter' => $this->input->post('perimeter'),
					'region' => $this->input->post('region'),
					'id_kategori' => $this->input->post('kat_perimeter'),
					'id_provinsi' => $this->input->post('provinsi'),
					'id_kota' => $this->input->post('kabupaten'),
					'alamat' => $this->input->post('alamat'),
					'longitude' => $this->input->post('longitude'),
					'latitude' => $this->input->post('latitude'),
					'kd_perusahaan' => $this->input->post('kd_perusahaan'),
			);

			$data = json_encode($data);
			$curl = curl_init();
			curl_setopt_array($curl, array(
					CURLOPT_URL => SERVER_API."list_perimeter/add",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $data,
					CURLOPT_HTTPHEADER => array('Content-Type: application/json')
			));
			$response = curl_exec($curl);
			curl_close($curl);


			echo $response;
	}

	public function ajax_delete($id) {
        $delete =  $this->mperimeter_model->delete_mpml_byid($id);

        if($delete){
            $modul = rawurlencode('Perimeter Level');
            $description = rawurlencode('Menghapus Perimeter Level ');
            $action = rawurlencode('DELETE');
            $username = rawurlencode($this->ion_auth->user()->row()->username);

            log_aktivitas_crud($modul, $id, $description, $action, $username);
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Perimeter Level'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Delete Perimeter Level'));
        }
	}

	public function form_perimeter($mpm_id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $group=$this->ion_auth->get_users_groups()->row()->id;

        $perimeter =$this->master_model->mst_perimeter($mpm_id)->row();
        $data['mc_id'] = $perimeter->mpm_mc_id;
        $mc_id= $perimeter->mpm_mc_id;

	    $data['mpm_id']=$mpm_id;
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Perimeter Detail";
	    $data['error']="";

	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);

	    $data["cluster_ruangan"]=$this->master_model->cluster_ruangan()->result();
        $data['pic'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,3);
        $data['fo'] = $this->profile_model->userbumn_bymcid_and_role($mc_id,4);

	    $this->load->view('header',$data);
	    $this->load->view('mperimeter/form_perimeter',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_perimeter_detail($mpm_id) {
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."list_perimeter/detail/".$mpm_id,
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

	    echo $response;
	}

	public function ajax_perimeter_level($mpm_id) {
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."list_perimeter_level/perimeter/".$mpm_id,
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

	    $no=0;
	    foreach ($responsex as $res) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $res->level;
	        $row[] = $res->nik_pic;
	        $row[] = $res->pic;
	        $row[] = $res->nik_fo;
	        $row[] = $res->fo;
	        $row[] = '<a class="btn btn-md btn-danger" href="javascript:void(0)" title="Delete"
                        onclick="delete_perimeterlevel('."'".$res->id_perimeter_level."'".')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
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

	public function ajaxperimeterlevel_list($mpm_id) {
	    $this->load->helper('url');
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $create=$this->master_model->one_groups($group)->c;
	    $update=$this->master_model->one_groups($group)->u;
	    $delete=$this->master_model->one_groups($group)->d;
	    $list = $this->mperimeter_model->get_datatables_lvl($mpm_id);

	    $data = array();
	    $no = $_POST['start'];
	    foreach ($list as $mpml) {
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $mpml->mpml_name;
	        $row[] = $mpml->mpml_pic_nik;
	        $row[] = $mpml->pic;
	        $row[] = $mpml->mpml_me_nik;
	        $row[] = $mpml->fo;
	        $row[] = $mpml->v_cr;

	        $btn_edit =' <a class="btn btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit"
            onclick="edit_perimeter('."'".$mpml->mpml_id."'".')">
            <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
	        $btn_delete ='<a class="btn btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete"
            onclick="delete_perimeterlevel('."'".$mpml->mpml_id."'".')">
            <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';

	        if($update==1){
	            $btnx_edit =$btn_edit;
	        }else{
	            $btnx_edit ='';
	        }

	        if($delete==1){
	            $btnx_delete =$btn_delete;
	        }else{
	            $btnx_delete ='';
	        }
	        $row[] = ' <div class="button">'.$btnx_edit.' '.$btnx_delete.'</button>';
	        $data[] = $row;
	    }

	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $this->mperimeter_model->count_all_lvl($mpm_id),
	        "recordsFiltered" => $this->mperimeter_model->count_filtered_lvl($mpm_id),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

    public function ajaxperimeterlevel_detail($mpml_id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."perimeter/detail/".$mpml_id,
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

        echo $response;
    }

    public function ajaxperimeterlevel_update() {
        $cluster = array();
        for ($i = 1; $i <= 23; $i++){
           if($this->input->post('jml'.$i)>0){
               $cluster[]=array(
                   'id_cluster_ruangan' =>$i,
                   'jumlah' =>$this->input->post('jml'.$i),
               );
           }
        };

        $data = array(
            'id_perimeter_level' => $this->input->post('id_perimeter_level'),
            'nik_pic' => $this->input->post('pic'),
            'nik_fo' => $this->input->post('fo'),
            'keterangan' => $this->input->post('keterangan'),
            'id_kategori_perimeter' => $this->input->post('id_kategori_perimeter'),
            'cluster' => $cluster
        );
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."perimeter/update",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json')
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    public function ajaxperimeterlevel_add() {
        $cluster = array();
        for ($i = 1; $i <= 23; $i++){
           if($this->input->post('jml'.$i)>0){
               $cluster[]=array(
                   'id_cluster_ruangan' =>$i,
                   'jumlah' =>$this->input->post('jml'.$i),
               );
           }
        };

        $data = array(
            'id_perimeter' => $this->input->post('id_perimeter'),
            'level' => $this->input->post('level'),
            'nik_pic' => $this->input->post('pic'),
            'nik_fo' => $this->input->post('fo'),
            'keterangan' => $this->input->post('keterangan'),
          //  'id_kategori_perimeter' => $this->input->post('id_kategori_perimeter'),
            'cluster' => $cluster
        );
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."perimeter_level/add",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json')
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    //sprint16
    public function delete_perimeter($id) {
        $mpm = $this->mperimeter_model->get_perimeter_bympmid($id);
        $delete =  $this->mperimeter_model->delete_perimeter_byid($id);

        if($delete){
            $modul = rawurlencode('Perimeter Level');
            $description = rawurlencode('Menghapus Perimeter Level '.$mpm->mpm_name);
            $action = rawurlencode('DELETE');
            $username = rawurlencode($this->ion_auth->user()->row()->username);

            log_aktivitas_crud($modul, $id, $description, $action, $username);
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Perimeter Level'));
        }else{
            echo json_encode(array("status" => NULL, "message" => 'Gagal Delete Perimeter Level'));
        }
	}

	public function get_region_autocomplete($id_company){
        if (isset($_GET['term'])) {
            $result = $this->master_model->search_region($id_company,$_GET['term']);
						//var_dump(($result->result()));
						//exit;
            if (count($result->result()) > 0) {
            foreach ($result->result() as $row)
                $arr_result[] = $row->mr_name;
                echo json_encode($arr_result);
            }
        }
    }
}
