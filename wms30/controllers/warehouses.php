<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouses extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		
		/*
		 *  Warehouse Security Check
		 */
		if(! $this->session->userdata('authenticated'))                 			redirect('/auth', 'refresh');
		if(! $this->user->init($this->session->userdata('username')))   			redirect('/auth', 'refresh');
		if(! $this->user->permissions['modulePermissions']['warehouses']->read)     redirect('/', 'refresh');
		$data['warehousePermissions'] = $this->user->permissions['modulePermissions']['warehouses'];
		// Add CSS files
		$this->template->add_css('css/default.css');

		$this->template->add_js('js/common.js');
		// Set Page Title
		$this->template->write('title', 'EasyRock WMS - Warehouses');		

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

		$data['sideBarLink'] = array(
            'Manage Warehouses'     => '/warehouses/manage',
            'Manage Bins'           => '/warehouses/bins'
			);
		
		if(!$this->session->userdata('currentWarehouse')) 
			$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
		$this->currentWarehouse = new Warehouse;
		$this->currentWarehouse->build($this->session->userdata('currentWarehouse'));

		$this->template->write_view('sidebar', 'common/sidebar', $data);

		$this->output->enable_profiler($this->input->get('profiler'));
		
	}

	public function index() {

		$this->template->render();
	}

	private function dump($var) {
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}

	public function bins($bin_id = NULL) {
		
		$data['errorMessage'] = '';

		//get the page data for the view
		$data = $this->get_bin_page_data();

		$data['warehouseName'] = $this->currentWarehouse->name;

		//Build the Table for the View
		
		//If the bin ID is zero then we will add bins by showing the add bins form.
		if($bin_id === NULL) unset($data['editRegion']);
		elseif($bin_id == 0) {
			//permission check to **__** Add **__**
			if(! $this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->create)
				redirect('/warehouses/bins', 'refresh');
			
			if(! $this->form_validation->run('addBins')) 
				$data['editRegion'] = $this->load->view('warehouse/addbins', $data, TRUE);
			else {				
				$startBin = array(
					'x'                  => strtoupper($this->input->post('startXCoord')),
					'y'                  => strtoupper($this->input->post('startYCoord')),
					'z'                  => strtoupper($this->input->post('startZCoord')),
					'bin_dim_id'         => $this->input->post('bin_dim_id'),
					'warehouse_id'       => $this->currentWarehouse->warehouse_id,
					'binIsInfinite'      => $this->input->post('binIsInfinite'),
					'binIsAUserBasket'   => $this->input->post('binIsAUserBasket'),
					'binComment'         => $this->input->post('binComment')
					);
				$startBin['binAddress']  = $this->input->post('customAddress')    ? strtoupper($this->input->post('binAddress')) : ''; 
				$startBin['user_id']     = $this->input->post('binIsAUserBasket') ? $this->input->post('user_id')                : NULL;				
				
				$endBin = $startBin;
				$endBin['x']             = strtoupper($this->input->post('endXCoord'));
    			$endBin['y']             = strtoupper($this->input->post('endYCoord'));
				$endBin['z']             = strtoupper($this->input->post('endZCoord'));
				//create bin objects and build
				$startBins = new Bins;
				if(! $startBins->build_from_array($startBin,       $data['errorMessage']))  goto fail;
				//if we are trying to enter a range of bunks
				if($endBin['x'] != '' && $endBin['y'] != '' && $endBin['z'] != '') {
					//then build the end bin
					$endBins = new Bins;
					if(! $endBins->build_from_array($endBin,       $data['errorMessage']))  goto fail;
					if(! $startBins->are_compatible_with($endBins, $data['errorMessage']))  goto fail;
					if(! $startBins->build_insert_array($endBins,  $data['errorMessage']))  goto fail;
				} 					
					
				if(! $startBins->create(                           $data['errorMessage']))  goto fail;
				$data['message'] = 'All Bins added successfully';           			    goto success;
								
			}
		//Edit a specific bin	
		}elseif($bin_id > 0) {
			//permission check to **__** Update **__**
			if(! $this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->update)
				redirect('/warehouses/bins', 'refresh');

		//delete a bin or range of bins	
		}elseif($bin_id == -1) {
			//permission check to **__** Delete **__**
			if(! $this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->delete)
				redirect('/warehouses/bins', 'refresh');

			if($this->uri->segment(4)) {
				if(! $this->bins->build($this->uri->segment(4), $data['errorMessage']))   goto fail;
				if(! $this->bins->rm_bin($data['errorMessage']))				          goto fail;
				else {
					$data['message'] = $this->bins->binAddress.' has been succesfully removed';
					                                                                      goto success;
				}
			}elseif(! $this->form_validation->run('delBins')) 
				$data['editRegion'] = $this->load->view('warehouse/delbins', $data, TRUE);
			else {	
				foreach($this->input->post() as $key => $value)
					$delete[$key] = $value;
				$delete['warehouse_id'] = $this->currentWarehouse->warehouse_id;
				if(!$return = $this->bins->rm_bin($data['errorMessage'], $delete))        goto fail;
				$data['message'] = 'All bins removed successfully'; 					  goto success;
			}
		}
		
		goto end;
		success:
		$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		goto end;
		fail:
		$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
		end:

		$data['tables'][$this->currentWarehouse->name] = Wms::fetch_table(
			'Bins',
			'bin_id',
			$this->config->item('binTH'),
			'warehouse_id = '.$this->currentWarehouse->warehouse_id
			);


		//write the table view and render the template
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}
	
	private function get_bin_page_data() {
		$data['page']['id'] = 'bins';
		$data['page']['links'][$this->currentWarehouse->name] = array();
		$links = array();		
		$data['user_id'] = $this->user->user_id;
		$optVars = array (
			'link'    => '/warehouses/warehouseUpdate',
			'hidden'  => 'change_warehouse',
			'label'   => '',
			'key'     => 'warehouse_id',
			'class'   => 'Warehouse',
			'funct'   => 'options',
			'user_id' => $this->user->user_id,
			'curVal'  => $this->currentWarehouse->warehouse_id,
			'para'    => NULL
		);
		$data['boolrg'] = TRUE;
		$data['select'][] = $this->load->view('common/select', $optVars, TRUE); 

		if($this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->create)
			$data['buttons'][] = Wms::button('/warehouses/bins/0', 'Add Bins', ICON_ROOT.'/basket_add.png', 'positive');
		if($this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->delete)
			$data['buttons'][] = Wms::button('/warehouses/bins/-1', 'Remove Bins', ICON_ROOT.'/basket_delete.png', 'negative');

		if($this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->delete)
			$links = array_merge_recursive($this->config->item('binTA-delete'), $links);

		if($this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->update)
			$links = array_merge_recursive($this->config->item('binTA-update'), $links);

		$data['page']['links'][$this->currentWarehouse->name] = $links;
		return $data;
	}

	private function get_warehouse_page_data() {
		$data['page']['id'] = 'all-warehouses';
		$links = array();

		if($this->user->permissions['modulePermissions']['warehouses']->create)
			$data['buttons'][] = Wms::button('/warehouses/manage/0', 'Add Warehouse', ICON_ROOT.'/building_add.png', 'positive');
		
		if($this->user->permissions['modulePermissions']['warehouses']->delete)
			$links = array_merge_recursive($this->config->item('warehouseTA-delete'), $links);

		if($this->user->permissions['modulePermissions']['warehouses']->update)
			$links = array_merge_recursive($this->config->item('warehouseTA-update'), $links);


		$data['page']['links']['Warehouses'] = $links;
		$data['tables']['Warehouses'] = Wms::fetch_table(
			'Warehouses',
			'warehouse_id',
			$this->config->item('warehouseTH')
			);

		return $data;
	}
	public function warehouseUpdate() {
		if($this->input->post('change_warehouse')) {
			$warehouse_id = $this->input->post('warehouse_id');
			if($this->warehouse->id_is_valid($warehouse_id)) {
				if(isset($this->user->permissions['warehousePermissions'][$warehouse_id]->read)) {
					if($this->user->permissions['warehousePermissions'][$warehouse_id]->read)
						$this->session->set_userdata('currentWarehouse', $warehouse_id);
					else
						$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
				}
				else
					$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
			}
			else
				$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);

		}
		redirect('/warehouses/bins', 'refresh');
	}
	public function manage($warehouse_id = NULL) {		
		$data = $this->get_warehouse_page_data();
		if($warehouse_id === NULL) $data['editRegion'] = NULL;
		elseif($warehouse_id == 0) {
			if(! $this->user->permissions['modulePermissions']['warehouses']->create) redirect('/warehouses/manage', 'refresh');
			
			if(! $this->form_validation->run('addWarehouse'))
				$data['editRegion'] = $this->load->view('warehouse/addwarehouse', '', TRUE);
			else {
				foreach($this->input->post() as $key => $value)
					$insertWarehouse[$key] = $value;
				$insertWarehouse['warehouse_id'] = NULL;
				$this->db->insert('Warehouses', $insertWarehouse);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}
		} elseif($warehouse_id > 0) {
			if(! $this->form_validation->run('editWarehouse')) {
				$data['editWarehouse'] = new Warehouse;
				$data['editWarehouse']->build_warehouse($warehouse_id);
				$data['editRegion'] = $this->load->view('warehouse/editwarehouse', $data, TRUE);
			} else {
				foreach($this->input->post() as $key => $value)
					$editWarehouse[$key] = $value;
				$this->db->where('warehouse_id', $editWarehouse['warehouse_id']);
				unset($editWarehouse['warehouse_id']);
				$this->db->update('Warehouses', $editWarehouse);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}
			

		} else redirect('/warehouses', 'refresh');

		
		
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function rmwarehouse($warehouse_id = NULL) {
		if(! $warehouse_id) redirect('warehouses/manage', 'refresh');

		if(! $this->input->post('confirmed')){
			$data['rmWarehouse'] = new Warehouse;
			$data['rmWarehouse']->build_warehouse($warehouse_id);
			$this->template->write_view('content', 'warehouse/rmwarehouse', $data);
			$this->template->render();
		} else {
			$this->db->where('warehouse_id', $warehouse_id);
			$this->db->delete('Warehouses');
			redirect('warehouses/manage', 'refresh');
		}
	}

	public function rmaccess($id = NULL) {
		if($id == NULL) redirect('/warehouses/manage', 'refresh');
		if(! $this->user->permissions['modulePermissions']['warehouses']->delete) redirect('/warehouses/manage', 'refresh');
		$permission = new WarehousePermission;
		$permission->build($id);
	}

	public function access($warehouse_id = NULL) {
		if($warehouse_id === NULL) redirect('/warehouses/manage', 'refresh');
		$warehouse = new Warehouse;
		$warehouse->build($warehouse_id);
		$tableName = $warehouse->name.' User Access';
		$data['tables'][$tableName] = Wms::fetch_table(
			'view_WarehousePermissions', 
			'warehouse_permission_id',
			$this->config->item('warehouseAccessTH'),
			"warehouse_id = $warehouse_id"
			);
		$data['page']['links'][$tableName] = $this->config->item('warehouseAccessTA');
		$data['boolrg'] = TRUE;
		$data = array_merge_recursive($data, $this->get_warehouse_page_data());
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}
	public function addaccess($warehouse_id = NULL) {
		if($warehouse_id === NULL || !$warehouse_id) redirect('/warehouses/manage');
		if(!$this->user->permissions['modulePermissions']['warehouses']->update) redirect('/warehouses/manage', 'refresh');
		
		if(!$this->form_validation->run('addWarehouseAccess')) {
			$data['warehouse'] = new Warehouse;
			$data['warehouse']->build($warehouse_id);
			$data['editRegion'] = $this->load->view('warehouse/addaccess', $data, TRUE);
		} else {
			$insert = $this->input->post();
			$insert['warehouse_permission_id'] = NULL;
            if(!isset($insert['create']))   $insert['create']   = FALSE;
            if(!isset($insert['read']))     $insert['read']     = FALSE;
            if(!isset($insert['update']))   $insert['update']   = FALSE;
            if(!isset($insert['delete']))   $insert['delete']   = FALSE;
			$this->db->insert('WarehousePermissions', $insert);

			$data['message'] = 'Warehouse Permissions added successfully.';
			$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		}
	
		
			
		$data = array_merge_recursive($data, $this->get_warehouse_page_data());
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	
}

