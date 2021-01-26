<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {
	private static $module_ID = 14;//marketing
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');
		//$this->load->model('marketing_model');
		$this->load->database();
		$this->load->helper(array('form', 'url','directory','path'));	  
	}		
	public function index()
	{
		echo "Nothing";
	}
	
	public function secure(){
		if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        } 
	
	}
	public function back_to_url($murl){
		$murl=str_replace("-","/",$murl);
		redirect('/'.$murl, 'location', 301);
	}
}
