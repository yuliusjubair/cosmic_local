<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class DashminMonitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('admin_model');
        $this->load->model('dashmin_model');
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
    }


    public function secure() {
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }

    function get_server_memory_usage(){
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		    $a=array();
		    array_push($a, 600);
			array_push($a, 1000);
		    $memory_usage = 10000/600;
			array_push($a, $memory_usage);

	    	return $a;
		} else {
		    $free = shell_exec('free');
		    $free = (string)trim($free);
		    $free_arr = explode("\n", $free);
		    $mem = explode(" ", $free_arr[1]);
		    $mem = array_filter($mem);
		    $mem = array_merge($mem);	   
		    $memory_usage = $mem[2]/$mem[1]*100;
		    $a=array();
			array_push($a, $mem[2]);
			array_push($a, $mem[1]);
			array_push($a, $memory_usage);

	    	return $a;
		}
	}

	function HumanSize($Bytes)
	{
	  $Type=array("", "KB", "MB", "GB", "TB", "peta", "exa", "zetta", "yotta");
	  $Index=0;
	  while($Bytes>=1024)
	  {
	    $Bytes/=1024;
	    $Index++;
	  }
	  return("".round($Bytes, 2)." ".$Type[$Index]);
	}

	function get_disk_usage(){
		$disk = ".";
		$free = disk_free_space($disk);
		$total = disk_total_space($disk);
		$percent = $free / $total * 100;
		$a=array();
		array_push($a, $total);
		array_push($a, $free);
		array_push($a, $percent);

		return $a;
	}

	function get_server_cpu_usage(){
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	    	return 5;
	    }else{
	    	$load = sys_getloadavg();
			return $load[0];
	    }	    
	}

	function get_server_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	function format_interval(DateInterval $interval) {
	    $result = "";
	    if ($interval->y) { $result .= $interval->format("%y Tahun "); }
	    if ($interval->m) { $result .= $interval->format("%m Bulan "); }
	    if ($interval->d) { $result .= $interval->format("%d Hari "); }
	    if ($interval->h) { $result .= $interval->format("%h Jam "); }
	    if ($interval->i) { $result .= $interval->format("%i menit "); }
	    if ($interval->s) { $result .= $interval->format("%s Detik "); }

	    return $result;
	}

	function get_time_session($date){
		$first_date = new DateTime($date);
		$second_date = new DateTime(date('Y-m-d H:i:s'));

		$difference = $first_date->diff($second_date);

		return $this->format_interval($difference);
	}
	

    public function index(){
        $this->secure();
        $data['user'] = $this->ion_auth->user()->row();
        $last_login_time = $data['user']->last_login_time;
        $group=$this->ion_auth->get_users_groups()->row()->id;

        $data['menu']=$this->master_model->menus_monitoring_usage($group);
        $data['title']="Monitoring Usage";
        $data['subtitle']="Monitoring Usage";
        $data['group'] = $group;
        // $data['images']= $this->master_model->gallery();
        // $data['count']= $this->master_model->count_images();
        $data['memory_usage'] = $this->get_server_memory_usage();
        $disk = $this->get_disk_usage();
        $data['disk_usage'] = $this->HumanSize($disk[1]);
        $data['disk_free'] = $disk[2];
        $data['disk_total'] = $this->HumanSize($disk[0]);
        $data['cpu'] = $this->get_server_cpu_usage();
        $data['ip'] = $this->get_server_ip();
        $data['time_session'] = $this->get_time_session($last_login_time);
        $data['total_user'] = $this->master_model->get_total_user_login();
        $user = $data['user'];
        $data['total_bumn'] = $this->master_model->get_bumn($user->mc_id);
        $data['total_fo'] = $this->master_model->get_fo();
        $data['total_pic'] = $this->master_model->get_pic();
        $data['disk'] = $this->HumanSize($disk[2]); 
        // $data['haloo'] = $this->master_model->get_chart_pic();

        $this->load->view('header',$data);

        if($group==1){
            $this->load->view('dashmin/dashmin_monitoring_usage',$data);
		}else if($group==5){
            $this->load->view('dashmin/dashcluster',$data);
        }else if($group==6){
            $this->load->view('dashmin/dashmin',$data);
        }else if($group==7){
            //role partner atestasi
            $this->load->view('dashmin/dashboard_partner',$data);
        }else if($group==10){
            //role partner sertifikasi
            $this->load->view('sertifikasi/dashboard',$data);
        }else if($group==12){
            //role user belum verifikasi
            redirect('auth/onboarding', 'refresh');        
        }else{

        $password_default = $this->bcrypt->verify('P@ssw0rd',$data['user']->password);
        if($password_default==true){
            $url_default="/auth/change_password";
            redirect($url_default, 'refresh');
        }
        $this->load->view('dashboard/index',$data);

        }
        $this->load->view('footer',$data);
    }


    public function get_pic() {
    	$data = $this->master_model->get_chart_pic();
    	print_r($data);
    }

    public function get_fo() {
    	$data = $this->master_model->get_chart_pic(4);
    	print_r($data);
    }
    
    public function ajax_dashboard_monitoring_usage() {
        $curl = curl_init();

        $group_company = $this->input->get('group_company');
        if ((isset($group_company)) && $group_company<3) {
          $string = "dashboard/dashboardhead?group_company=".$group_company;
        } else {
          $string = "dashboard/dashboardhead";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => SERVER_API.$string,
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
        $total_user = $this->master_model->get_total_user_login();
        $total_bumn = $this->master_model->get_total_user_login();
        $total_pic = $this->master_model->get_total_user_login();
        $total_fo = $this->master_model->get_total_user_login();
        $total_session = $this->master_model->get_total_user_login();

        echo '
			<div class="content col-lg col-md-6 col-sm-6 col-12">
				<div class="card card-statistic-1">
					<div class="card-wrap">
						<div class="card-header pt-3">
							<p class="total_title">Total User Login </p>
							<p class="total_title">Saat Ini</p>
						</div>
						<div class="card-body">
							<b>'.$total_user.'</b>
						</div>
					</div>
				</div>
			</div>
			<div class="content col-lg col-md-6 col-sm-6 col-12">
				<div class="card card-statistic-1">
					<div class="card-wrap">
						<div class="card-header pt-3">
							<p class="total_title">Total BUMN Login </p>
							<p class="total_title">Saat Ini
								<p />
							</div>
							<div class="card-body">
								<a href="//localhost/ilcs/cosmic_2/dashboard/dashperimeter">
									<b>'.$total_user.'</b>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="content col-lg col-md-6 col-sm-6 col-12">
					<div class="card card-statistic-1">
						<div class="card-wrap">
							<div class="card-header pt-3">
								<p class="total_title">Total PIC Login </p>
								<p class="total_title">Saat Ini
									<p />
								</div>
								<div class="card-body">
									<b>'.$total_user.' %</b>
								</div>
							</div>
						</div>
					</div>
					<div class="content col-lg col-md-6 col-sm-6 col-12">
						<div class="card card-statistic-1">
							<div class="card-wrap">
								<div class="card-header pt-3">
									<p class="total_title">Total FO Login </p>
									<p class="total_title">Saat Ini
										<p />
									</div>
									<div class="card-body">
										<b>'.$total_user.'</b>
									</div>
								</div>
							</div>
						</div>
						<div class="content col-lg col-md-6 col-sm-6 col-12">
							<div class="card card-statistic-1">
								<div class="card-wrap">
									<div class="card-header pt-3">
										<p class="total_title">Lama Session </p>
										<p class="total_title">yang terjadi</p>
									</div>
									<div class="card-body">
										<b>'.$total_user.' %</b>
									</div>
								</div>
							</div>
						</div>';

        echo $output;
    }

   
    
    //sprint19

}
