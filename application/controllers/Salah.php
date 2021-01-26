<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salah extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('salah_model');
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

        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Perimeter";
        $data['subtitle']="Monitor Kesalahan Input (PIC & FO sama)";

        $group = $this->ion_auth->get_users_groups()->row()->id;
        $data['group']=$group;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 5){
            $mc_id =  $data['user']->mc_id;
        }else{
            $mc_id = '';
        }
        $data['mc_id'] = $mc_id;
        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
        $data["menu"]=$this->master_model->menus($group);

        $group_company = $this->input->get('group_company');
        $data['group_company']=null;
        //var_dump($group_company);
        //exit;
        if ((isset($group_company)) && $group_company<3) {
          $data['group_company']=$group_company;
        } else {
          $data['group_company']=null;
        }

        $this->load->view('header',$data);
        if($group==1){
            $this->load->view('dashboard/list_slh',$data);
        }else if($group==6){
            $this->load->view('dashboard/list_slh',$data);
        }else if($group==5){
            $this->load->view('dashboard/list_slh_cluster',$data);
        }
        $this->load->view('footer',$data);
    }

    function get_slh() {
      $group_company = $this->input->get('group_company');
        $this->load->helper('url');
        $list = $this->salah_model->get_datatables($group_company);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $salah) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $salah->z_mc;
            $row[] = $salah->z_mr;
            $row[] = $salah->z_mpm;
            $row[] = $salah->z_mpml;
            $row[] = $salah->z_pic;
            $row[] = $salah->z_fo;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salah_model->count_all($group_company),
            "recordsFiltered" => $this->salah_model->count_filtered($group_company),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_slh_cluster() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $this->load->helper('url');
        $list = $this->salah_model->get_datatables_cluster($msc_id);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $salah) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $salah->z_mc;
            $row[] = $salah->z_mr;
            $row[] = $salah->z_mpm;
            $row[] = $salah->z_mpml;
            $row[] = $salah->z_pic;
            $row[] = $salah->z_fo;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salah_model->count_all_cluster($msc_id),
            "recordsFiltered" => $this->salah_model->count_filtered_cluster($msc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
}
