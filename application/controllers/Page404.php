<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page404 extends CI_Controller {

	
	public function index()
	{
		$this->output->set_status_header('404'); // setting header to 404
        $this->load->view('404error');//loading view
	}
	

}
