<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Carbon\Carbon;
class Histperimeter extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model(array('histperimeter_model','master_model'));
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

		//$crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
		//var_dump($crweek);
		//exit();
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();

	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Monitoring History Perimeter";

	    $group = $this->ion_auth->get_users_groups()->row()->id;
			$data['group']=$group;
	    $sektor = $this->ion_auth->user()->row()->msc_id;
	    if($group > 1 && $group < 5){
	        $mc_id = $data['user']->mc_id;
            $data['mc_name']  = $this->master_model->one_company($mc_id)->row()->mc_name;
	    }else{
	        $mc_id = '0000';
            $data['mc_name']  = $this->master_model->one_company('0000')->row()->mc_name;
	    }
	    
	    $data['company'] = $this->master_model->company($group, $sektor, $mc_id);
	    $data['mc_id'] = $mc_id;

	    $data['week'] = $this->master_model->list_week();
	    $data["menu"]=$this->master_model->menus($group);

	    $this->load->view('header',$data);
	    $this->load->view('histperimeter/list_histperimeter',$data);
	    $this->load->view('footer',$data);
	}



	public function detail($mc_id) {
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();

	    $data['thn']=Date('Y');
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Monitoring History Perimeter";
	    $data['mc_id']=$mc_id;
		//$data['menu_hide']="yes";

	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    if($group!=1){
	        $mc_id = $data['user']->mc_id;
	        $data['company'] = $this->master_model->one_company($mc_id);
	    }else{
	        $data['company'] = $this->master_model->one_company($mc_id);
	    }
			$data['mc_name']  = $this->master_model->one_company($mc_id)->row()->mc_name;

	    $data['week'] = $this->master_model->list_week();
	    $data["menu"]=$this->master_model->menus($group);

	    $this->load->view('header',$data);
	    $this->load->view('histperimeter/list_histperimeter',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_list($mc_id, $tgl) {
	    $this->load->helper('url');
	    $tgl_senin = date("Y-m-d", strtotime('monday this week'));
	    $list = $this->histperimeter_model->get_datatables_lvl($mc_id, $tgl);

	    $data = array();
	    $no = $_POST['start'];
	    $i=1;
	    foreach ($list as $mperimeter) {
	        if($mperimeter->v_cek=='t') {
	            $iconcekx='<span style="color:green;">Check</span>';
	            //$iconcekx='<span style="color:green;"><i class="fa fa-check" aria-hidden="true"></i></span>';
	        }else{
	            $iconcekx='<span style="color:red;">Un-check</span>';
	            //$iconcekx='<span style="color:red;"><i class="fa fa-close" aria-hidden="true"></i></span>';
	        }

	        if($mperimeter->status_tutup==2) {
                $text_status = '<span style="color:red;">Level Tutup</span>';
            } else {
                $text_status = "";
            }

            if($mperimeter->v_persen_det > 100) {
            	$mperimeter->v_persen_det=100;
            }
	        $no++;
	        $row = array();
	        $row[] = $no;
	        $row[] = $mperimeter->v_mpm_name.'<br>'.$text_status;
	        $row[] = $mperimeter->v_mpml_name;
	        $row[] = $mperimeter->v_mr_name;
	        $row[] = $iconcekx;
	        $row[] = $mperimeter->v_persen_det.' %';
	        $row[] = $mperimeter->v_pic.' - <br>'.$mperimeter->v_pic_nik;
	        $row[] = $mperimeter->v_fo.' - <br>'.$mperimeter->v_fo_nik;

	        if($tgl_senin==$tgl){
	            $row[] = "<a class='' href='javascript:void(0)'' title='Detail Event'
                        onclick=\"window.open('".base_url().'histperimeter/detail_histperimeter/'.$mperimeter->v_pic_nik.'/'.$mperimeter->v_mpml_id."','_self');\">
                        <i class='ace-icon fa fa-chevron-right bigger-120'></i></a>";
	        }else{
                $row[] = "";
	        }
	        $data[] = $row;
	        $i++;
	    }

	    $output = array(
	        "draw" => $_POST['draw'],
	        "recordsTotal" => $i,
	        "recordsFiltered" => $this->histperimeter_model->count_filtered_lvl($mc_id, $tgl),
	        "data" => $data,
	    );
	    echo json_encode($output);
	}

	function get_persenmonitoring($mc_id, $tgl) {
	    $persen = $this->histperimeter_model->get_persenmonitoring($mc_id, $tgl)->row();
	    echo $persen->v_monitoring;
	}

	function ajax_get_cosmic_index($mc_id,$date) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => SERVER_API."dashboard/cosmic_index_detail/".$mc_id."?date=".$date,
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
		//var_dump($response);die;

		echo json_encode($responsex);
	}

	public function init_kurva_chart_history(){
		$this->load->library('xcharts/highchart');
		$chart2 = new Highchart();
		$chart2->chart = array(
				'renderTo' => 'container2',
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
				'text' => 'Rangkuman Grafik Cosmic',
				'x' => - 20
		);

		$chart2->exporting = array(
			'buttons' => array(
				'contextButton' => array(
					'text' => ' Download',
					'symbol' => '',
					'verticalAlign' => 'top',
					'icon' => '<i class="ace-icon fa fa-download"></i>',
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
		$chart2->yAxis = array(
			array('title' => array(
							"text" => "%"
					)
				),
			array(
				'title' => array(
							"text" => "Total"
					),
					'opposite'=> 'true'
				)
		);

		$chart2->legend = array(
				'layout' => 'vertical',
				'align' => 'right',
				'verticalAlign' => 'top',
				'x' => - 10,
				'y' => 100,
				'borderWidth' => 0
		);
		$chart2->tooltip = array(
				'shared' => true,
				'valueSuffix' => ''
		);
		$chart2->plotOptions->column->pointPadding = 0.2;
		$chart2->plotOptions->column->borderWidth = 0;
		$chart2->series[] = array();

		return $chart2;
	}

	public function init_kurva_chart_history_jml_perimeter(){
		$this->load->library('xcharts/highchart');
		$chart3 = new Highchart();
		$chart3->chart = array(
				'renderTo' => 'container3',
				'type' => 'spline',
				'marginRight' => 120,
				'marginBottom' => 80
		);
		$chart3->colors = array(

			"#e60000",
		);
		$chart3->title = array(
				'text' => 'Rangkuman Grafik Jumlah Perimeter',
				'x' => - 20
		);

		$chart3->exporting = array(
			'buttons' => array(
				'contextButton' => array(
					'text' => ' Download',
					'symbol' => '',
					'verticalAlign' => 'top',
					'icon' => '<i class="ace-icon fa fa-download"></i>',
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

		$chart3->subtitle = array(
				'text' => '',//'Update '.date('d-m-Y'),
				'x' => - 20
		);
		$chart3->xAxis->categories = array();
		$chart3->yAxis = array(
			array('title' => array(
							"text" => "Total"
					)
				)
		);

		$chart3->legend = array(
				'layout' => 'vertical',
				'align' => 'right',
				'verticalAlign' => 'top',
				'x' => - 10,
				'y' => 100,
				'borderWidth' => 0
		);
		$chart3->tooltip = array(
				'shared' => true,
				'valueSuffix' => ''
		);
		$chart3->plotOptions->column->pointPadding = 0.2;
		$chart3->plotOptions->column->borderWidth = 0;
		$chart3->series[] = array();

		return $chart3;
	}

	public function get_data_chart($mc_id,$bulan){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => SERVER_API."dashboard/cosmic_index_detaillist/".$mc_id."?month=".$bulan,
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

        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "History Monitoring";
        $result1['caption'][] = "History Monitoring";
        $result1['type'][] = "spline";

        $result2 = array();
        $result2['name'][] = "Cosmic Index";
        $result2['caption'][] = "Cosmic Index";
        $result2['type'][] = "spline";


        foreach( $responsex as $row ){
    		$result['data'][] = $row->weekname;
    		$result1['data'][] = (int)$row->cosmic_index;
    		$result2['data'][] = (int)$row->pemenuhan_ceklist_monitoring;

        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        array_push($json,$result2);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
	}

	public function get_data_chart_jml_perimeter($mc_id,$bulan){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => SERVER_API."dashboard/cosmic_index_detaillist/".$mc_id."?month=".$bulan,
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

        $result = array();
        $result['name'][] = "Tanggal";

        $result1 = array();
        $result1['name'][] = "Jumlah Perimeter";
        $result1['caption'][] = "Jumlah Perimeter";
        $result1['type'][] = "spline";

        foreach( $responsex as $row ){
    		$result['data'][] = $row->weekname;
    		$result1['data'][] = (int)$row->jumlah_perimeter;
        }

        $json = array();
        array_push($json,$result);
        array_push($json,$result1);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        print json_encode($json);
	}

	public function rangkuman_grafik($mc_id){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $group=$this->ion_auth->get_users_groups()->row()->id;

        $data['thn']=Date('Y');
        $data['cari']="";
        $data['title']="Grafik Cosmic";
        $data['subtitle']="Rangkuman Grafik Cosmic";
        $data['mc_id']=$mc_id;
        $data['menu_hide']="yes";
        $data['week'] = $this->master_model->list_week();
        $data["menu"]=$this->master_model->menus($group);

        $data['xcharts2'] = $this->init_kurva_chart_history();
        $data['xcharts3'] = $this->init_kurva_chart_history_jml_perimeter();
        //var_dump($data['xcharts2']);die;
        $this->load->view('header',$data);
        $this->load->view('histperimeter/dashhistory',$data);
        $this->load->view('footer',$data);
	}

	public function ajax_get_cosmic_index_list($mc_id,$bulan) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => SERVER_API."dashboard/cosmic_index_detaillist/".$mc_id."?month=".$bulan,
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
		$no = 1;
		foreach ($responsex as $res) {
			$row = array();
			$row[] = $no++;
			$row[] = $res->weekname;
			$row[] = $res->mc_name;
			$row[] = $res->cosmic_index;
			$row[] = $res->pemenuhan_ceklist_monitoring;
			$row[] = $res->jumlah_perimeter;
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

	public function detail_histperimeter($nik, $id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Detail Monitoring";
	    $data['error']="";

	    $data['nik']=$nik;
	    $data['mpml_id']=$id;
	    $data["det_histperimeter"]=$this->histperimeter_model->get_mpmlbyId($id);
	    $group=$this->ion_auth->get_users_groups()->row()->id;

	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('histperimeter/detail_histperimeter',$data);
	    $this->load->view('footer',$data);
	}

	public function ajax_monitoring_mpml($nik, $mpml_id) {
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => SERVER_API."monitoring/perimeter/".$nik."/".$mpml_id,
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

	    $output='';
	    foreach ($responsex as $res) {
	        $output .='<h6>'.$res->cluster_ruangan.'</h6>';
	        for($a=0; $a < count($res->aktifitas); $a++) {
	           $aktifitas = $res->aktifitas[$a]->aktifitas;
	           $file = $res->aktifitas[$a]->file;
	           $output .='<div>'.$aktifitas.'</div>';
	           for($b=0; $b < count($file); $b++) {
	               $location1 = base_url().'histperimeter/detail_histperimeter_foto/'.$res->aktifitas[$a]->id_aktifitas;
                   $location = "document.location ='$location1'";
	               //$output .='<div>
                   //      <a href="#" id="bottle" onclick="'.$location.'";" >
                   //      <img src="http://103.146.244.78/cosmic_api/storage/app/public'.$file[$b]->file.'"
                   //      class="img-responsive" width="150" height="150"/></a></div>';
                   //103.146.244.79/cosmic/uploads/aktifitas/0702/2020-10-26/1603680279364.jpg
                   $scr = base_url().'uploads';
	               $output .='<div>
                         <a href="#" id="bottle" onclick="'.$location.'";" >
                         <img src="'.$scr.$file[$b]->file.'"
                         class="img-responsive" width="150" height="150"/></a></div>';
	           }
	           $output .='<br>';
	        }
	        $output .='<br>';
	    }
	    echo $output;
	}

	public function detail_histperimeter_foto($id){
	    $this->secure();
	    $data['user'] = $this->ion_auth->user()->row();
	    $data['group']=$this->ion_auth->get_users_groups()->row()->id;
	    $data['cari']="";
	    $data['title']="Perimeter";
	    $data['subtitle']="Detail Monitoring";
	    $data['error']="";

	    $data['aktivitas']=$this->histperimeter_model->get_aktivitasbyId($id);
	    //var_dump($data['aktivitas']);die;
	    $group=$this->ion_auth->get_users_groups()->row()->id;
	    $data["menu"]=$this->master_model->menus($group);
	    $data["create"]=$this->master_model->one_groups($group)->c;
	    $data["update"]=$this->master_model->one_groups($group)->u;
	    $data["delete"]=$this->master_model->one_groups($group)->d;

	    $this->load->view('header',$data);
	    $this->load->view('histperimeter/detail_histperimeter_foto',$data);
	    $this->load->view('footer',$data);
	}

	public function open_perimeter(){
		$mpml_id = $this->input->post('modal_id');
		$curl = curl_init();
		$data = array(
            'id_perimeter_level' => $mpml_id
        );
        $data = json_encode($data);
		curl_setopt_array($curl, array(
		    CURLOPT_URL => SERVER_API."perimeter_open/add",
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
}
