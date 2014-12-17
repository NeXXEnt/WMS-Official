<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	
	public function __construct() {
		parent::__construct();


	}

	public function index() {
		$this->template->write('title', 'Login');
		
		$this->load->helper('form');
		$this->template->write_view('content', 'auth/login');
		$this->template->add_css('css/login.css');

		$this->template->render();
	}

	public function authenticate() {
		if($this->user->authenticate($this->input->post('username'), $this->input->post('password'))) {
			$this->session->set_userdata('username', $this->input->post('username'));
			$this->session->set_userdata('authenticated', 'TRUE');
			redirect('/', 'refresh');
		} else			
			redirect('/auth', 'refresh');
		
			
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('/', 'refresh');
	}
}

