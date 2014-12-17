<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		
		/*
		* Standard security check common for most controllers
		 */
		if(! $this->session->userdata('authenticated')) 
			redirect('/auth', 'refresh');
		if(! $this->user->init($this->session->userdata('username')))
			redirect('/auth', 'refresh');


		// Add CSS files
		$this->template->add_css('css/default.css');

		// Set Page Title
		$this->template->write('title', 'EasyRock WMS');		

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

		$data['sideBarLink'] = array(
			'<li><a class="side-bar-menu" href="">Phone App</a></li>',
			'<li><a class="side-bar-menu" href="">Phone App</a></li>',
			'<li><a class="side-bar-menu" href="">Phone App</a></li>',
			'<li><a class="side-bar-menu" href="">Phone App</a></li>',
			'<li><a class="side-bar-menu" href="">Phone App</a></li>'
			);
		$this->template->write_view('sidebar', 'common/sidebar', $data);
	}

	public function index()
	{
		
		
		
		$this->template->render();

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */