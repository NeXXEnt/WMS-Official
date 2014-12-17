<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct() {
		parent::__construct();

		// Add CSS files
		$this->template->add_css('css/default.css');

		// Set Page Title
		$this->template->write('title', 'EasyRock WMS');

		// Authenticate User first
		$this->user->init('derek', 'casanova');
		$this->user->authenticate();

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

	}

	public function index()
	{
		
		
		
		$this->template->render();

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */