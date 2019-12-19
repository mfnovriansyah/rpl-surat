<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// https://github.com/thewasiullah/CodeIgniter-Authme/tree/master/application/views/auth

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('usersmodel');
		$this->load->library('user_agent');
		// $this->load->library('user_agent');
	}

	public function index() {
		// if ($this->agent->is_referral()){
		// 	die($this->agent->referrer());
		// }
		if($this->session->userdata('login') == true) {
			redirect(base_url('dashboard'));
		}
		$data['ref']=base_url('dashboard');
		if($this->input->get('ref')!==null){
			$data['ref']=urldecode($this->input->get('ref'));
		}
		$this->load->view('login',$data);
	}

	public function dologin() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$data = [
			'username' => $username,
			'password' => $password
		];
		$dologin = $this->usersmodel->login($data);
		$logged_in = $dologin->row();
		if($dologin->num_rows() > 0) {
			// $data_session = [
				// 'username' => $username,
				// 'name' => $logged_in->name,
				// 'role' => $logged_in->role,
				// 'email' => $logged_in->email,
				// 'login' => true
			// ];
			$data_session = array();
			$data_session['id'] = $logged_in->id;
			$data_session['username'] = $username;
			$data_session['name'] = $logged_in->name;
			$data_session['email'] = $logged_in->email;
			$data_session['role_SSO'] = $logged_in->role_SSO;
			$data_session['role'] = $logged_in->role;
			$data_session['status'] = $logged_in->status;	
			$data_session['login'] = true;
			$this->session->set_userdata($data_session);
      if($this->input->post('remember_me')) {
          $this->load->helper('cookie');
          $cookie = $this->input->cookie('ci_session');
          $this->input->set_cookie('ci_session', $cookie, '31557600');
      }
			echo json_encode(['success' => true]);
		}else{
			echo json_encode(['success' => false]);
		}
	}

	public function whoami(){
		echo '<pre>'; print_r($this->session); die();
	}
	public function logout() {
		if($this->session->role_SSO!=''){
			$this->session->sess_destroy();
			$this->load->library('cas');
			$this->cas->logout(base_url());
		}else{
			$this->session->sess_destroy();
			redirect(base_url('login'));
		}
	}
	
	public function sso(){
		$ref=base_url('dashboard');
		if($this->input->get('ref')!==null){
			$ref=urldecode($this->input->get('ref'));
		}
    $this->load->library('cas');
    $this->cas->force_auth();
    $user = $this->cas->user();
		$u = $user->attributes;
		// $u['peran_user']
		// $u['ldap_cn']
		// $u['nip']
		// $u['nama']
		$u['username'] = $user->userlogin;
		$x = $this->usersmodel->getOrInsert( $u );
		$v = array();
		$v['id'] = $x->id;
		$v['username'] = $x->username;
		$v['name'] = $x->name;
		$v['email'] = $x->email;
		$v['role_SSO'] = $x->role_SSO;
		$v['role'] = $x->role;
		$v['status'] = $x->status;
		$v['login'] = true;
		$this->session->set_userdata($v);
		if($this->session->userdata('login') == true) {
			redirect($ref);
		}
	}
	
	public function resetpassword(){
		die('If there is an account associated with the email you enterred, a reset password guide is sent there.');
	}
}