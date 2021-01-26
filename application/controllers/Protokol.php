<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Protokol extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('protokol_model','master_model'));
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
    }

    public function secure(){
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }

    public function index($mc_id='') {
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();

        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Protokol";
        $data['subtitle']="Upload Protokol";

        $group = $this->ion_auth->get_users_groups()->row()->id;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 8){
            $mc_id =  $data['user']->mc_id;
        }else{
            $mc_id = $mc_id;
        }

        $data['group'] = $group;
        $data['mc_id'] = $mc_id;
        $data['company'] = $this->master_model->company($group, $sektor, $mc_id);

        //if group is admin
        $data['company_bumn'] = $this->master_model->company_bygroupcompany(1);
        $data['company_nonbumn'] = $this->master_model->company_bygroupcompany(2);

        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('protokol/list_protokol',$data);
        $this->load->view('footer',$data);
    }


    public function ajax_list($mc_id) {
        $this->load->helper('url');
        $group = $this->ion_auth->get_users_groups()->row()->id;
        $create=$this->master_model->one_groups($group)->c;
        $update=$this->master_model->one_groups($group)->u;
        $delete=$this->master_model->one_groups($group)->d;
        $list = $this->protokol_model->get_datatables($mc_id);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $protokol) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $protokol->v_mpt_name;
            if($protokol->v_tbpt_filename){
                $filename=$mc_id.'/'.$protokol->v_tbpt_filename;
                $row[] = "<button class='btn btn-md btn-primary' type='button'
                onclick=\"window.open('".base_url().'uploads/protokol/'.$filename."');\"
                formtarget='_self'><span class='bluexx'><i class='ace-icon fa fa-download bigger-120'>
                </i> Download</span></button>";
                $btn_edit ='<a class="btn btn-md btn-icon icon-left btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_protokol('."'".$protokol->v_tbpt_id."'".')"><i class="ace-icon fa fa-pencil bigger-120"></i> Edit</a>';
                $btn_delete =' <a class="btn btn-md btn-icon icon-left btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_protokol('."'".$protokol->v_tbpt_id."'".')"><i class="ace-icon fa fa-trash bigger-120"></i> Delete</a>';

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
            } else {
                $row[] = '(No File)';
                $btn_create ='<a class="btn btn-md btn-primary" href="javascript:void(0)" title="Add" onclick="add_protokol('."'".$protokol->v_mpt_id."'".')"><i class="ace-icon fa fa-upload bigger-120"></i> Upload</a>';

                if($create==1 || $create==0){
                    $btnx_create =$btn_create;
                }else{
                    $btnx_create ='';
                }
                $row[] =$btnx_create;

            }


            if($protokol->v_tbpt_date_update != NULL){
                $row[] = $protokol->v_tbpt_date_update;
            }else{
                $row[] = $protokol->v_tbpt_date_insert;
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->protokol_model->count_all($mc_id),
            "recordsFiltered" => $this->protokol_model->count_filtered($mc_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->protokol_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {
        //var_dump('tes');die;
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);

        $data = array(
            'tbpt_mc_id' => $this->input->post('modal_mc_id'),
            'tbpt_mpt_id' => $this->input->post('modal_mpt_id'),
            //'tbpt_page' => $this->input->post('modal_page'),
        );

        $mc_id = $this->input->post('modal_mc_id');
        $mpt_id = $this->input->post('modal_mpt_id');
        $delete = $this->protokol_model->delete_bymcmpt($mc_id, $mpt_id);
        //var_dump($mc_id);var_dump($mpt_id);
        //die;

        if(!empty($_FILES['protokol_file']['name'])) {
            //$page = $this->input->post('modal_page');
            $upload = $this->_do_upload($mc_id);
            $data['tbpt_filename'] = $upload;
            $data['tbpt_user_insert']=$this->ion_auth->user()->row()->id;
            $data['tbpt_date_insert']=date('Y-m-d H:i:s');

            $insert = $this->protokol_model->save($data);

            echo json_encode(array("status" => 200, "message" => 'Berhasil Upload Protokol'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Upload Protokol'));
        }
    }

    public function ajax_update() {
        //var_dump('tes');die;
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);

        $this->_validate();
        $data = array(
            'tbpt_mc_id' => $this->input->post('modal_mc_id'),
            'tbpt_mpt_id' => $this->input->post('modal_mpt_id'),
            'tbpt_page' => $this->input->post('modal_page'),
        );

        $mc_id = $this->input->post('modal_mc_id');

        if(!empty($_FILES['protokol_file']['name'])) {
            $upload = $this->_do_upload($mc_id);

            //delete file
            $protokol = $this->protokol_model->get_by_id($this->input->post('modal_id'));

            //var_dump($protokol);die;
            if(file_exists('uploads/protokol/'.$mc_id.'/'.$protokol->tbpt_filename)
                && $protokol->tbpt_filename){
                unlink('uploads/protokol/'.$mc_id.'/'.$protokol->tbpt_filename);
            }
            $data['tbpt_filename'] = $upload;

            $data['tbpt_user_update']=$this->ion_auth->user()->row()->id;
            $data['tbpt_date_update']=date('Y-m-d H:i:s');
            $this->protokol_model->update(array('tbpt_id' => $this->input->post('modal_id')), $data);

            echo json_encode(array("status" => 200, "message" => 'Berhasil Update Protokol'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Update Protokol'));
        }
    }

    public function ajax_delete($id) {
        //var_dump($id);die;
        $protokol = $this->protokol_model->get_by_id($id);
        //var_dump($protokol);die;
        if($protokol->tbpt_filename){
            if(file_exists('uploads/protokol/'.$protokol->tbpt_mc_id.'/'.$protokol->tbpt_filename)){
                unlink('uploads/protokol/'.$protokol->tbpt_mc_id.'/'.$protokol->tbpt_filename);
            }
            $this->protokol_model->delete_by_id($id);
            echo json_encode(array("status" => 200, "message" => 'Berhasil Delete Protokol'));
        }else{
            echo json_encode(array("status" => 500, "message" => 'Gagal Delete Protokol'));
        }
    }

    public function _do_upload($mc_id) {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 3600);

        $config['upload_path']          = 'uploads/protokol/'.$mc_id;
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 30*1024; //set max size allowed in Kilobyte
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);
        if(!is_dir('./uploads/protokol/'.$mc_id)) {
            @mkdir('./uploads/protokol/'.$mc_id.'/',0777);
        }

        if(!$this->upload->do_upload('protokol_file')) {
            $data['inputerror'][] = 'protokol_file';
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
        $data['status'] = TRUE;

        if($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
