<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Sertifikasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashmin_model');
        $this->load->model('Companysertifikasi_model');
        $this->load->model('Companynonbumn_model');

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
        $group=$this->ion_auth->get_users_groups()->row()->id;
        
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Sertifikasi";
        $data['group'] = $group;
        $data['images']= $this->master_model->gallery();
        $data['count']= $this->master_model->count_images();

        $this->load->view('header',$data);
        $this->load->view('sertifikasi/dashboard',$data);
        $this->load->view('footer',$data);
    }

    function get_all_company($status=NULL) {
        $list = $this->Companysertifikasi_model->get_company($status);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            if($field->tbps_status=="1"){
                $status = "Disetujui";
            }elseif ($field->tbps_status=="2") {
                $status = "Menunggu Persetujuan";
            }elseif($field->tbps_status=="0"){
                $status = "Belum Disetujui";
            }elseif($field->tbps_status==""){
                $status = "Belum Disetujui";
            }elseif($field->tbps_status=="3"){
                $status = "Belum Disetujui";
            }elseif($field->tbps_status=="4"){
                $status = "Di Tolak";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->mc_name;
            
            $row[] = ($field->tbps_date_insert=="")?"":date('d/m/Y',strtotime($field->tbps_date_insert));
            $row[] = ($field->tbps_date_verifikasi=="")?"":date('d/m/Y',strtotime($field->tbps_date_verifikasi));
            $row[] = $status;
            $row[] = $field->tbps_nama_verifikasi;
            //$row[] = $status;
            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Company'><i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
            $row[] = $field->mc_idnya."/".$field->tbps_id;
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Companysertifikasi_model->count_all($status),
            "recordsFiltered" => $this->Companysertifikasi_model->count_filtered($status),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function detail_sertifikasi($id, $tbps_id){
        $this->secure();
        $data['rowx'] = $this->Companysertifikasi_model->get_byid_tbps($tbps_id);
        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Dashboard";
        $data['subtitle']="Detail Perusahaan";
        $data['error']="";
        $data['tbps_id']=$tbps_id;
        //$data['menu_hide']="yes";

        $group = $this->ion_auth->get_users_groups()->row()->id;
        $sektor = $this->ion_auth->user()->row()->msc_id;
        if($group > 1 && $group < 8){
            $mc_id = $data['user']->mc_id;
        }else{
            $mc_id = '';
        }

        $data['cosmic_index_thisweek'] = $this->Companynonbumn_model->cosmic_index_minggu_ini($id);
        $data['cosmic_index_weekbefore'] = $this->Companynonbumn_model->cosmic_index_minggu_lalu($id);

        $data['kategori'] = $this->master_model->master_sosialisasi_kategori($cari='')->result();
        $data['master_status'] = $this->master_model->master_status_sertifikasi($cari='')->result();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);
        $data["create"]=$this->master_model->one_groups($group)->c;
        $data["update"]=$this->master_model->one_groups($group)->u;
        $data["delete"]=$this->master_model->one_groups($group)->d;
        
        $this->load->view('header',$data);
        $this->load->view('sertifikasi/detail_perusahaan',$data);
        $this->load->view('footer',$data);
    }

    public function getPerimeterByPerusahaan($mc_id) {
        $responsex = $this->db->query("SELECT mpm_id, mpm_name ,COALESCE(a.mpmk_id ,0) v_jml,mc.mc_name, mp.mpro_name, mpml_id, mpm_alamat, mc_status_sertifikasi
                    FROM master_perimeter_kategori a
                    LEFT JOIN (
                    SELECT mpm_id, mpm_mpmk_id, mpm_mpro_id, mpm_name,mpm_mc_id, COUNT(*) cnt, master_perimeter_level.mpml_id,mpm_alamat FROM master_perimeter 
                    LEFT JOIN master_perimeter_level on master_perimeter_level.mpml_mpm_id = master_perimeter.mpm_id
                    GROUP BY mpm_id, mpm_mpmk_id,mpm_mpro_id,mpm_name,mpm_mc_id,mpml_id,mpm_alamat
                    ) b ON b.mpm_mpmk_id=a.mpmk_id
                    left join master_provinsi mp on b.mpm_mpro_id=mp.mpro_id
                    join master_company mc on mc.mc_id = b.mpm_mc_id
                    WHERE mc.mc_id = '$mc_id'
                    ");
        
        $data = [];
        $no=1;
        foreach ($responsex->result() as $res) {
            $status = $this->dashmin_model->get_percent($res->mpm_id, $res->mpml_id);
            //print_r($res);
            $button="";
            $atestasi=$res->mc_status_sertifikasi;
            if($atestasi=="0"){
                $button = "<a class='btn btn-danger' href='javascript:void(0)'' title='Detail Event'
                        onclick=dialog_detail('".$mc_id."','".$res->mpm_id."')>Setujui</a>";
            }else{
                $button = '<a tabindex="0" class="" data-toggle="popover" data-trigger="focus" data-content="<a href=javascript:void(0) onclick=dialog_detail('.$mc_id.','.$res->mpm_id.')>Ubah Status</a> <br /> <a href=javascript:void(0) onclick=dialog_confirm('.$mc_id.')>Tolak Pengajuan</a>" data-html="true"><i class="ace-icon fa fa-list bigger-120"></a></i>';

            }
            $row = array();
            $row[] = $no;
            $row[] = $res->mpm_id;
            $row[] = $res->mpm_name;
            $row[] = $res->mpm_alamat;
            $row[] = $res->v_jml;
            $row[] = $status['percentage'].' %';
            $row[] = $button;
            //$row[] = "<i class='ace-icon fa fa-chevron-right bigger-120'></i>";
           /*  $row[] = "<a class='' href='javascript:void(0)'' title='Detail Event'
                        onclick=\"window.open('".base_url().'sosialisasi/detail_event/'.$res->v_name_kategori."','_self');\"></a>";*/
            $data[] = $row;
            $no++;
        }

        $output = array(
            "draw" =>1,
            "recordsTotal" => 0,
            "recordsFiltered" =>0,
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_data_detail_sertifikasi($mc_id, $tbps_id){
        $company = $this->Companysertifikasi_model->get_byid($mc_id, $tbps_id);
        $output = array(
            "company" =>$company,
        );
        echo json_encode($output);   
    }

    function update_status_pengajuan(){
        $tbps_id = $this->input->post('modal_tbps_id2');
        $check= $this->input->post('check1');
        if(isset($check)){
            $status=1;
        }else{
            $status=0;
        }

        // print_r($status);die;
        
        $petugas= $this->input->post('petugas');
        $date = date('Y-m-d h:i:s');
        $user_id = $this->session->userdata('user_id');
        $kontak_petugas= $this->input->post('kontak_petugas');
        $estimasi= $this->input->post('estimasi');
        if($this->db->query("UPDATE table_pengajuan_sertifikasi SET tbps_status='$status', tbps_nama_verifikasi='$petugas', tbps_date_verifikasi='$date', tbps_estimasi='$estimasi' WHERE tbps_id='$tbps_id'")){
        // $this->db->query("UPDATE master_company SET mc_status_sertifikasi='$status', mc_nama_pic_sertifikasi='$petugas', mc_kontak_sertifikasi='$kontak_petugas', mc_user_update_status='$user_id', mc_user_update_date='$date' WHERE mc_id='$mc_id'");
            echo json_encode(array("status" => 200, "message" => 'Berhasil Update Status Sertifikasi'));
        }else{
            echo json_encode(array("status" => 200, "message" => 'GAGAL Update Status Sertifikasi'));
        }
    }

    function update_status_pengajuan_detail(){
        $tbps_id = $this->input->post('modal_tbps_id');
        $status= $this->input->post('status');
        
        $petugas= $this->input->post('petugas');
        $kontak_petugas= $this->input->post('kontak_petugas');
        // $this->db->query("UPDATE master_company SET mc_status_proses_sertifikasi='$status' WHERE mc_id='$mc_id'");
        $this->db->query("UPDATE table_pengajuan_sertifikasi SET tbps_status_pengajuan_id='$status' WHERE tbps_id='$tbps_id'");
        echo json_encode(array("status" => 200, "message" => 'Berhasil Update Status Proses Sertifikasi'));
    }

     function update_status_batal(){
        $modal_id_mc_idbatal = $this->input->post('modal_id_mc_idbatal');
        $status=4;
        $this->db->query("UPDATE table_pengajuan_sertifikasi SET tbps_status='$status' WHERE tbps_id='$modal_id_mc_idbatal'");
         echo json_encode(array("status" => 200, "message" => 'Berhasil Tolak Pengajuan Sertifikasi'));
    }

    public function ajax_list_card_partner() {
        // echo 'test';die;
        $responsex = $this->Companysertifikasi_model->getlist_card_sertifikasi();
        $output ='';
        foreach ($responsex->result() as $res) {

            $output .='
            <div class="content col-lg col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
            <div class="card-wrap">';
            $output .='<div class="card-header">'.$res->judul.'<br /><br /></div>';
            $output .='<div class="card-body"><b>'.$res->jml.'</b>&nbsp;<font size=-1>Perusahaan</font></div>';
            $output .='</div>
            </div>
                </div>';
        }

        echo $output;
    }
}
