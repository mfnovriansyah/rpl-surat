<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if($this->session->userdata('login') !== true) redirect(base_url('login'));

	}

	public function index() {
		$output['session'] = $this->session;
		$output['title'] = 'Dashboard';
		
		
		
	
		$this->load->view('dashboard',$output);
	}
}