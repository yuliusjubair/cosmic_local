
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashkasus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashkasus_model');
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
        $group=$this->ion_auth->get_users_groups()->row()->id;
        if($group==1 or $group==6){
            $this->dashkasus();
        }else{
            $this->cluster_dashkasus();
        }
    }

    public function dashkasus(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Kasus";
        $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');

        $data['xcharts2'] = $this->init_kurva_chart2('Tren Kasus','container2');
        $data['xcharts3'] = $this->init_kurva_chart2('Tren Kasus Pertanggal','container3');

        $this->load->view('header',$data);
        $this->load->view('kasus/dashkasus',$data);
        $this->load->view('footer',$data);
    }

    public function cluster_dashkasus(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data['menu']=$this->master_model->menus($group);
        $data['title']="Dashboard";
        $data['subtitle']="Dashboard Cluster Kasus";
        $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');

        $data['xcharts2'] = $this->init_kurva_chart2('Tren Kasus','container2');
        $data['xcharts3'] = $this->init_kurva_chart2('Tren Kasus Pertanggal','container3');
        //var_dump($data['xcharts2']);die;
        $this->load->view('header',$data);
        $this->load->view('kasus/cluster_dashkasus',$data);
        $this->load->view('footer',$data);
    }

    public function ajax_dashboard_head() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/laporan_home_all",
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

        $output ='';
        $no=0;
        foreach ($responsex as $res) {
            $no++;
            $output .= '
            <div class=" col-lg col-md-6 col-sm-6 col-6">
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">'.$res->jenis_kasus.'<br></div>
                    <b><div class="card-body" id="card_'.$no.'">'.$res->jumlah.'</div></b>
                </div>
            </div>
            </div>';
        }
        echo $output;
    }

    public function ajax_company($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashkasus_company_bymskid/".$id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mc_name;
            $row[] = $res->jumlah;
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

    public function ajax_provinsi($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashkasus_provinsi_bymskid/".$id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mpro_name;
            $row[] = $res->jumlah;
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

    public function ajax_kabupaten($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashkasus_kabupaten_bymskid/".$id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mkab_name;
            $row[] = $res->jumlah;
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

    public function init_kurva_chart2($judul, $containner){
        $this->load->library('xcharts/highchart');
        $chart2 = new Highchart();
        $chart2->chart = array(
            'renderTo' => $containner,
            'type' => 'spline',
            'marginRight' => 120,
            'marginBottom' => 80
        );
        $chart2->colors = array(
            "#ff9900",
            "#33cc33",
            "#e60000",
        );
        $chart2->title = array(
            'text' => $judul,
            'x' => - 20
        );

        $chart2->exporting = array(
            'buttons' => array(
                'contextButton' => array(
                    'text' => ' Download',
                    'symbol' => '',
                    'verticalAlign' => 'top',
                    'icon' => '<i class="ace-icon fa fa-upload"></i>',
                    'align' => 'left',
                    'symbolStroke'=> "red",
                    'theme' => array(
                            "class" => "btn btn-primary text-left putih",
                            'fill'=>"#337ab7",
                            'style' => array('color' => array('white'))
                        )
                )
            )
        );

        $chart2->subtitle = array(
            'text' => '',//'Update '.date('d-m-Y'),
            'x' => - 20
        );
        $chart2->xAxis->categories = array();
        $chart2->legend = array(
            'layout' => 'vertical',
            'align' => 'right',
            'verticalAlign' => 'top',
            'x' => 0,
            'y' => 0,
            'borderWidth' => 0
        );
        $chart2->yAxis->min = 0;
        $chart2->tooltip = array(
            'shared' => true,
            'valueSuffix' => ''
        );
        $chart2->plotOptions->column->pointPadding = 0.2;
        $chart2->plotOptions->column->borderWidth = 0;
        $chart2->series[] = array();

        return $chart2;
    }

    public function get_data_chart(){
        $query=$this->dashkasus_model->data_chart_kasus();
        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "Konfirmasi";
        $result1['caption'][] = "Konfirmasi";
        $result1['type'][] = "spline";

        $result2 = array();
        $result2['name'][] = "Sembuh";
        $result2['caption'][] = "Sembuh";
        $result2['type'][] = "spline";

        $result3 = array();
        $result3['name'][] = "Meninggal";
        $result3['caption'][] = "Meninggal";
        $result3['type'][] = "spline";

        foreach( $query->result() as $row ){
            $result['data'][] = $row->z_tgl;
            $result1['data'][] = (int)$row->z_konfirmasi;
            $result2['data'][] = (int)$row->z_sembuh;
            $result3['data'][] = (int)$row->z_meninggal;
        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        array_push($json,$result2);
        array_push($json,$result3);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
    }

    public function ajax_cluster_dashboard_head() {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/cluster_laporan_home_all/".$msc_id,
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

        $output ='';
        foreach ($responsex as $res) {
            $output .= '
            <div class="col-lg-2 col-md-6 col-sm-6 col-12">
            <!--div style="padding:0 20px; height:25%; width:39% !important"-->
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">'.$res->jenis_kasus.'<br></div>
                    <div class="card-body"><b>'.$res->jumlah.'</b></div>
                </div>
            <!--/div-->
            </div>
            </div>';
        }
        echo $output;
    }

    public function get_data_clusterchart(){
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $query=$this->dashkasus_model->data_chart_clusterkasus($msc_id);
        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "Konfirmasi";
        $result1['caption'][] = "Konfirmasi";
        $result1['type'][] = "spline";

        $result2 = array();
        $result2['name'][] = "Sembuh";
        $result2['caption'][] = "Sembuh";
        $result2['type'][] = "spline";

        $result3 = array();
        $result3['name'][] = "Meninggal";
        $result3['caption'][] = "Meninggal";
        $result3['type'][] = "spline";

        foreach( $query->result() as $row ){
            $result['data'][] = $row->z_tgl;
            $result1['data'][] = (int)$row->z_konfirmasi;
            $result2['data'][] = (int)$row->z_sembuh;
            $result3['data'][] = (int)$row->z_meninggal;
        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        array_push($json,$result2);
        array_push($json,$result3);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
    }

    public function ajax_cluster_company($id) {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashclusterkasus_company_bymskid/".$id."/".$msc_id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mc_name;
            $row[] = $res->jumlah;
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

    public function ajax_cluster_provinsi($id) {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashclusterkasus_provinsi_bymskid/".$id."/".$msc_id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mpro_name;
            $row[] = $res->jumlah;
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

    public function ajax_cluster_kabupaten($id) {
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashclusterkasus_kabupaten_bymskid/".$id."/".$msc_id,
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
        $responsex = json_decode($response)->data;

        foreach ($responsex as $res) {
            $row = array();
            $row[] = $res->mkab_name;
            $row[] = $res->jumlah;
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

    public function get_data_chart_pertgl(){
        $query=$this->dashkasus_model->data_chart_kasus_pertgl();
        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "Konfirmasi";
        $result1['caption'][] = "Konfirmasi";
        $result1['type'][] = "spline";

        $result2 = array();
        $result2['name'][] = "Sembuh";
        $result2['caption'][] = "Sembuh";
        $result2['type'][] = "spline";

        $result3 = array();
        $result3['name'][] = "Meninggal";
        $result3['caption'][] = "Meninggal";
        $result3['type'][] = "spline";

        foreach( $query->result() as $row ){
            $result['data'][] = $row->z_tgl;
            $result1['data'][] = (int)$row->z_konfirmasi;
            $result2['data'][] = (int)$row->z_sembuh;
            $result3['data'][] = (int)$row->z_meninggal;
        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        array_push($json,$result2);
        array_push($json,$result3);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
    }

    public function get_data_clusterchart_pertgl(){
        $msc_id = $this->ion_auth->user()->row()->msc_id;
        $query=$this->dashkasus_model->data_chart_clusterkasus_pertgl($msc_id);
        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "Konfirmasi";
        $result1['caption'][] = "Konfirmasi";
        $result1['type'][] = "spline";

        $result2 = array();
        $result2['name'][] = "Sembuh";
        $result2['caption'][] = "Sembuh";
        $result2['type'][] = "spline";

        $result3 = array();
        $result3['name'][] = "Meninggal";
        $result3['caption'][] = "Meninggal";
        $result3['type'][] = "spline";

        foreach( $query->result() as $row ){
            $result['data'][] = $row->z_tgl;
            $result1['data'][] = (int)$row->z_konfirmasi;
            $result2['data'][] = (int)$row->z_sembuh;
            $result3['data'][] = (int)$row->z_meninggal;
        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        array_push($json,$result2);
        array_push($json,$result3);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
    }

    public function all_provinsi($msk_id){
        $this->secure();

        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Pegawai Terdampak / Provinsi";
        $data['subtitle']="Pegawai Terdampak / Provinsi";
        $data['error']="";
        $data['menu_hide']="yes";
        $data['msk_id']=$msk_id;
        $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('kasus/all_provinsi',$data);
        $this->load->view('footer',$data);
    }

    public function all_perusahaan(){
      $this->secure();

      $data['user'] = $this->ion_auth->user()->row();
      $data['group']=$this->ion_auth->get_users_groups()->row()->id;
      $data['cari']="";
      $data['title']="Pegawai Terdampak / Perusahaan";
      $data['subtitle']="Pegawai Terdampak / Perusahaan";
      $data['error']="";
      $data['menu_hide']="yes";
      //$data['msk_id']=$msk_id;
      $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
      $group=$this->ion_auth->get_users_groups()->row()->id;
      $data["menu"]=$this->master_model->menus($group);

      $this->load->view('header',$data);
      $this->load->view('kasus/all_perusahaan',$data);
      $this->load->view('footer',$data);
  }

  public function ajax_perusahaan_all() {
      $level_company = $this->input->get('level_company');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."dashboard/rangkuman_all?group_company=1&group_level=".$level_company,
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
        $responsex = json_decode($response)->data;

        $no=1;
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $no;
            $row[] = $res->nama_perusahaan;
            $row[] = $res->positif + $res->suspek + $res->kontakerat;
            $row[] = $res->positif;
            $row[] = $res->selesai;
            $row[] = $res->meninggal;
            $row[] = $res->suspek;
            $row[] = $res->kontakerat ;
            $row[] = $res->kasus_last_update;

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

    public function ajax_provinsi_all($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashkasus_provinsi_bymskid/".$id,
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
        $responsex = json_decode($response)->data;

        $no=1;
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $no;
            $row[] = $res->mpro_name;
            $row[] = $res->jumlah;
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

    public function all_kabupaten($msk_id){
        $this->secure();

        $data['user'] = $this->ion_auth->user()->row();
        $data['group']=$this->ion_auth->get_users_groups()->row()->id;
        $data['cari']="";
        $data['title']="Pegawai Terdampak / Kota";
        $data['subtitle']="Pegawai Terdampak / Kota";
        $data['error']="";
        $data['menu_hide']="yes";
        $data['msk_id']=$msk_id;
        $data['status_kasus']=$this->master_model->mst_status_kasus($cari='');
        $group=$this->ion_auth->get_users_groups()->row()->id;
        $data["menu"]=$this->master_model->menus($group);

        $this->load->view('header',$data);
        $this->load->view('kasus/all_kabupaten',$data);
        $this->load->view('footer',$data);
    }

    public function ajax_kabupaten_all($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API."terpapar/dashkasus_kabupaten_bymskid/".$id,
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
        $responsex = json_decode($response)->data;

        $no=1;
        foreach ($responsex as $res) {
            $row = array();
            $row[] = $no;
            $row[] = $res->mkab_name;
            $row[] = $res->jumlah;
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
}
