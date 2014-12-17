<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Features extends CI_Controller  {
		
		public function __construct(){
			parent::__construct();
			
			/*
			 *  Admin Security Check
			 */
			if(! $this->session->userdata('authenticated'))                       redirect('/auth', 'refresh');
			if(! $this->user->init($this->session->userdata('username')))         redirect('/auth', 'refresh');
			if(! $this->user->permissions['modulePermissions']['features']->read) redirect('/', 'refresh');

			// Add CSS files
			$this->template->add_css('css/default.css');

			// Add Javascript files
			$this->template->add_js('js/common.js');

			// Set Page Title
			$this->template->write('title', 'EasyRock WMS - features');		

			$data['menuLink'] = $this->user->generate_menu();
			$this->template->write_view('header', 'common/header', $data);

			// Build the SideBar
			$data['sideBarLink'] = array(
	            'Submit Bug/Feature'        => '/features/submit',
	            'Show deleted' => '/features/showall'
				);

			$this->template->write_view('sidebar', 'common/sidebar', $data);


		}
		
		
		public function index() {
			//$data['page']['links']['Product Lists'] = $this->lists_actions();
			$data['page']['id'] = 'features';
			$tableName = 'Feature requests and known bugs';
			$data['page']['links'][$tableName] = $this->config->item('featuresTA');
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_FeaturesUsers',
				'feature_id',
				$this->config->item('featuresTH'),
				'disabled = 0'
			);

			foreach($data['tables'][$tableName] as $key => $row) {
				if($key > 0) $data['tables'][$tableName][$key]['Link'] = '<a href="http://wms.nexxtea.com/'.$row['Link'].'">'.$row['Link'].'</a>';
			}
		
			$this->template->write_view('content', 'common/tables', $data);
			$this->template->render();
			
		}

		public function submit() {
			if($this->form_validation->run('addFeature')) {
				$insert = $this->input->post();
				$insert['user_id'] = $this->user->user_id;
				$this->db->insert('Features', $insert);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
				//insert feature
				//editregion success


			} else {
				$data['editRegion'] = $this->load->view('features/addfeature', '', TRUE);
			}
		
			$data['page']['id'] = 'features';
			$tableName = 'Feature requests and known bugs';
			$data['page']['links'][$tableName] = $this->config->item('featuresTA');
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_FeaturesUsers',
				'feature_id',
				$this->config->item('featuresTH'),
				'disabled = 0'
			);

			foreach($data['tables'][$tableName] as $key => $row) {
				if($key > 0) $data['tables'][$tableName][$key]['Link'] = '<a href="http://wms.nexxtea.com/'.$row['Link'].'">'.$row['Link'].'</a>';
			}
			$this->template->write_view('content', 'common/tables', $data);
			$this->template->render();
		}

		public function edit($id) {
			$this->db->where('feature_id', $id);
			$query = $this->db->get('Features');
			if($query->num_rows() == 0) redirect('/features', 'refresh');
			if($query->num_rows() > 1) show_error('Multiple entries found with that ID, contact admin');
			$feature = $query->row();
			$data['feature'] = $feature;

			if($this->form_validation->run('addFeature')) {
				$update = $this->input->post();
				$this->db->where('feature_id', $id);
				$this->db->update('Features', $update);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			} else {
				$data['editRegion'] = $this->load->view('features/editfeature', $data, TRUE);
			}
		
			$data['page']['id'] = 'features';
			$tableName = 'Feature requests and known bugs';
			$data['page']['links'][$tableName] = $this->config->item('featuresTA');
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_FeaturesUsers',
				'feature_id',
				$this->config->item('featuresTH'),
				'disabled = 0'
			);

			foreach($data['tables'][$tableName] as $key => $row) {
				if($key > 0) $data['tables'][$tableName][$key]['Link'] = '<a href="http://wms.nexxtea.com/'.$row['Link'].'">'.$row['Link'].'</a>';
			}
			$this->template->write_view('content', 'common/tables', $data);
			$this->template->render();
		}

		public function complete($id) {
			$this->db->where('feature_id', $id);
			$this->db->update('Features', array('status' => 'Completed'));
			redirect('/features', 'refresh');
		}

		public function delete($id) {
			$this->db->where('feature_id', $id);
			$this->db->update('Features', array('disabled' => TRUE));
			redirect('/features', 'refresh');
		}

		public function showall() {
			//$data['page']['links']['Product Lists'] = $this->lists_actions();
			$data['page']['id'] = 'features';
			$tableName = 'Feature requests and known bugs';
			$data['page']['links'][$tableName] = $this->config->item('featuresDeletedTA');
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_FeaturesUsers',
				'feature_id',
				$this->config->item('featuresTH'),
				'disabled = 1'
			);

			foreach($data['tables'][$tableName] as $key => $row) {
				if($key > 0) $data['tables'][$tableName][$key]['Link'] = '<a href="http://wms.nexxtea.com/'.$row['Link'].'">'.$row['Link'].'</a>';
			}
		
			$this->template->write_view('content', 'common/tables', $data);
			$this->template->render();
		}

		public function restore($id) {
			$this->db->where('feature_id', $id);
			$this->db->update('Features', array('disabled' => FALSE));
			redirect('/features', 'refresh');
		}
	}
	

