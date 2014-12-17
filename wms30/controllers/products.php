<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {
	public function __construct() {
		parent::__construct();

		if(! $this->session->userdata('authenticated'))                 			redirect('/auth', 'refresh');
		if(! $this->user->init($this->session->userdata('username')))   			redirect('/auth', 'refresh');
		if(! $this->user->permissions['modulePermissions']['products']->read)       redirect('/', 'refresh');
		
		if(!$this->session->userdata('currentWarehouse')) 
			$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
		$this->currentWarehouse = new Warehouse;
		$this->currentWarehouse->build($this->session->userdata('currentWarehouse'));

		if($this->session->userdata('currentProductList')) {
			$this->currentProductList = new ProductLists;
			if(! $this->currentProductList->build($this->session->userdata('currentProductList'))) {
				$this->sessions->unset_userdata('currentProductList');
				redirect('/shipping', 'refresh');
			}
		} else {
			$this->currentProductList = new ProductLists;
			if(! $this->currentProductList->build(ProductLists::get_default($this->user->user_id)))
				$this->currentProductList = FALSE;
		
		}

		// Set Page Title
		$this->template->write('title', 'EasyRock WMS - Products');

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

		$data['sideBarLink'] = array(
            'Lists'     => '/products/lists',
            'Products'  => '/products/manage',
            'Product Dimensions' => '/products/dim'
			);
		$this->template->write_view('sidebar', 'common/sidebar', $data);
		if($this->session->userdata('errorMessage')) {
			$this->errorMessage = $this->session->userdata('errorMessage');
			$this->session->unset_userdata('errorMessage');
		}

		// If you got a fresh post to change limit then update the session limit
		if($this->limit = $this->input->post('limit')) {
			$this->session->set_userdata('limit', $this->limit);
		// otherwise see if you have session data and if you don't then set the default
		}elseif(! $this->limit = $this->session->userdata('limit')){
			$this->limit = '20';
			$this->session->set_userdata('limit', $this->limit);
		}

		// If you got a fresh post to change offset then update the session offset
		if($this->offset = $this->input->post('offset')) {
			$this->session->set_userdata('offset', $this->offset);
		// otherwise see if you have session data and if you don't then set the default
		}elseif(! $this->offset = $this->session->userdata('offset')){
			$this->offset = '20';
			$this->session->set_userdata('offset', $this->offset);
		}

	}

	public function index()	{		
		
		$this->template->render();
	}
	

	public function lists($product_list_id = NULL) {
		
		$data['page']['id'] = 'product_lists';
		$data['user_id'] = $this->user->user_id;
		$data['buttons'] = $this->lists_buttons();

		if(isset($this->errorMessage)) {
			$data['errorMessage'] = $this->errorMessage;
		    goto fail;
		}
		if($product_list_id === NULL) $data['editRegion'] = NULL;
		elseif($product_list_id == 0) {
			if(! $this->user->permissions['modulePermissions']['products']->create) redirect('/products/lists', 'refresh');
			

			if(! $this->form_validation->run('addProductList')) {
				$data['editRegion'] = $this->load->view('products/addlist', $data, TRUE);
			}
			else {
				foreach($this->input->post() as $key => $value)
					$insertList[$key] = $value;
				
				$insertPermission                               = $this->config->item('defaultPermissions');
				$insertPermission['user_id']                    = $this->user->user_id;
				$insertPermission['product_list_permission_id'] = NULL;
				$insertList['product_list_id']                  = NULL;
				$this->db->insert('ProductLists', $insertList);
				$insertPermission['product_list_id']            = $this->db->insert_id();	
				$this->db->insert('ProductListsPermissions', $insertPermission);				
				

				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}
		} elseif($product_list_id > 0) {
			if(! isset($this->user->permissions['productListPermissions'][$product_list_id])) {
				$this->session->set_userdata('errorMessage', 'You do not have access to this list');
				redirect('/products/lists', 'refresh');
			}
			if(! $this->user->permissions['productListPermissions'][$product_list_id]->update) {
				$this->session->set_userdata('errorMessage', 'You do not have Update permission to this list');
				redirect('/products/lists', 'refresh');
			}
			if(! $this->form_validation->run('addProductList')) {
				$data['edit'] = new ProductLists;
				$data['edit']->build($product_list_id);
				$data['editRegion'] = $this->load->view('products/editlist', $data, TRUE);
			} else {
				foreach($this->input->post() as $key => $value)
					$edit[$key] = $value;

				$this->db->where('product_list_id', $product_list_id);
				$this->db->update('ProductLists', $edit);
				goto success;
			}
			

		} elseif($product_list_id == -1) {
			if($list_id = $this->uri->segment(4)) {
				if(! isset($this->user->permissions['productListPermissions'][$list_id])) {
					$this->session->set_userdata('errorMessage', 'You do not have access to this list');
					redirect('/products/lists', 'refresh');
				}
				if(! $this->user->permissions['productListPermissions'][$list_id]->delete) {
					$this->session->set_userdata('errorMessage', 'You do not have Delete permission for this list');
					redirect('/products/lists', 'refresh');
				}
				$delList = new ProductLists;
				$delList->build($list_id);
				if(! $delList->rm($data['errorMessage'])) goto fail;
				else goto success;
			}
		} else redirect('/products', 'refresh');

		goto end;
		success:
		$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		goto end;
		fail:
		$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
		end:
		
		$data['page']['links']['Product Lists'] = $this->lists_actions();
		$data['tables']['Product Lists'] = Wms::fetch_table(
			'view_ProductLists',
			'product_list_id',
			$this->config->item('productListTH')
			);
		
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();

	}

	public function editListAccess($id = NULL) {
		if($id === NULL) redirect('/products/lists', 'refresh');
		if($id > 0) {
			$permission = new ProductListPermissions;
			$permission->build($id);
			if(! isset($this->user->permissions['productListPermissions'][$permission->product_list_id])) {
				$this->session->set_userdata('errorMessage', 'You do not have access to this list');
				redirect('/products/lists', 'refresh');
			}
			if(! $this->user->permissions['productListPermissions'][$permission->product_list_id]->update) {
				$this->session->set_userdata('errorMessage', 'You do not have Update permission for this list');
				redirect('/products/lists', 'refresh');
			} 

			if($this->form_validation->run('editAccess')) {
				$update = $this->input->post();
				if(! isset($update['create'])) $update['create'] = FALSE;
				if(! isset($update['read']))   $update['read']   = FALSE;
				if(! isset($update['update'])) $update['update'] = FALSE;
				if(! isset($update['delete'])) $update['delete'] = FALSE;
				$this->db->where('product_list_permission_id', $id);
				$this->db->update('ProductListsPermissions', $update);
				redirect('/products/listAccess/'.$permission->product_list_id);
			} else {
				$data['edit'] = $permission;
				$data['editRegion'] = $this->load->view('products/editaccess', $data, TRUE);
			}
		} elseif($id == -1) {
			if(! $id = $this->uri->segment(4)) redirect('/products/lists', 'refresh');

			$permission = new ProductListPermissions;
			$permission->build($id);

			if(! isset($this->user->permissions['productListPermissions'][$permission->product_list_id])) {
				$this->session->set_userdata('errorMessage', 'You do not have access to this list');
				redirect('/products/lists', 'refresh');
			}
			if(! $this->user->permissions['productListPermissions'][$permission->product_list_id]->delete) {
				$this->session->set_userdata('errorMessage', 'You do not have Delete permission for this list');
				redirect('/products/lists', 'refresh');
			}
			$this->db->where('product_list_permission_id', $permission->product_list_permission_id);
			$this->db->delete('ProductListsPermissions');
			redirect('/products/listAccess/'.$permission->product_list_id);
			
		}


		$data['page']['id'] = 'list-access';
		
		$data['boolrg'] = TRUE;
		$data['page']['links']['Product Lists'] = $this->lists_actions();
		$data['tables']['Product Lists'] = Wms::fetch_table(
			'view_ProductLists',
			'product_list_id',
			$this->config->item('productListTH')
			);

		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function listAccess($list_id = NULL) {
		if($list_id === NULL)                                                    redirect('/products/lists', 'refresh');

		//show access's to this list
		if($list_id > 0){
			if(! isset($this->user->permissions['productListPermissions'][$list_id]->read)) {
				$this->session->set_userdata('errorMessage', 'You do not have access to this list');
				redirect('/products/lists', 'refresh');
			}
			if(! $this->user->permissions['productListPermissions'][$list_id]->read) {
				$this->session->set_userdata('errorMessage', 'You do not have access to this list');
				redirect('/products/lists', 'refresh');
			} 
			
			$productList = new ProductLists;
			$productList->build($list_id);
			$tableName = $productList->name.' User Access';
			$data['page']['links'][$tableName] = $this->config->item('productListAccessTA');
			$data['buttons'][] = Wms::button('/products/listAccess/0/'.$list_id, 'Add Access', ICON_ROOT.'/script_add.png', 'positive');
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_ProductListPermissions', 
				'product_list_permission_id',
				$this->config->item('productListAccessTH'),
				"product_list_id = $list_id"
				);
			
			
		} elseif($list_id == 0) {
			if(! $list_id = $this->uri->segment(4))           redirect('/products/lists', 'refresh');
			
			if(! isset($this->user->permissions['productListPermissions'][$list_id]->update)) {
				$this->session->set_userdata('errorMessage','You do not have permission to add access to this list'); 
			 	redirect('/products/lists', 'refresh');
			}
			if(! $this->user->permissions['productListPermissions'][$list_id]->update){
				$this->session->set_userdata('errorMessage','You do not have permission to add access to this list'); 
			 	redirect('/products/lists', 'refresh');
			}
			
			if(! $this->form_validation->run('addAccess')) {			
				$data['productList'] = new ProductLists;
				$data['productList']->build($list_id);
				$data['editRegion'] = $this->load->view('products/addAccess', $data, TRUE);
			}else{
				foreach($this->input->post() as $key => $value)
					$insert[$key] = $value;
				$insert['product_list_permission_id'] = NULL;
				$insert['product_list_id'] = $list_id;
				$this->db->insert('ProductListsPermissions', $insert);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
				$productList = new ProductLists;
				$productList->build($list_id);
				$tableName = $productList->name.' User Access';
				$data['page']['links'][$tableName] = $this->config->item('productListAccessTA');
				$data['buttons'][] = Wms::button('/products/listAccess/0/'.$list_id, 'Add Access', ICON_ROOT.'/script_add.png', 'positive');
				$data['tables'][$tableName] = Wms::fetch_table(
					'view_ProductListPermissions', 
					'product_list_permission_id',
					$this->config->item('productListAccessTH'),
					"product_list_id = $list_id"
					);
			}

		}


		$data['page']['id'] = 'list-access';
		
		$data['boolrg'] = TRUE;
		$data['page']['links']['Product Lists'] = $this->lists_actions();
		$data['tables']['Product Lists'] = Wms::fetch_table(
			'view_ProductLists',
			'product_list_id',
			$this->config->item('productListTH')
			);

		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	private function lists_buttons(){

		if($this->user->permissions['modulePermissions']['products']->create)
			$buttons[] = Wms::button('/products/lists/0', 'Add List', ICON_ROOT.'/script_add.png', 'positive');

		return $buttons;
	}

	private function manage_buttons() {
		if($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->create)
			$buttons[] = Wms::button('/products/manage/0', 'Add Products', ICON_ROOT.'/brick_add.png', 'positive');
		return $buttons;
	}

	public function manage($id = NULL) {
		if(! $this->currentProductList) {
			$this->session->set_userdata('errorMessage', 'You do not have access to any Lists');
			$this->session->unset_userdata('currentProductList');
			redirect ('/products/lists', 'refresh');
		}
		$data['productList'] = $this->currentProductList;

		if($id === NULL) {
			$data['editRegion'] = NULL;
			goto end;
		}elseif(! is_numeric($id)) {
			$data['errorMessage'] = 'Invalid argument '.$id;
			goto fail;
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id == '0') {
			//create
			if(! $this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->create) redirect('/products/manage', 'refresh');
			if($this->form_validation->run('addProducts')) {
				$insert = $this->input->post();
				$insert['product_id'] = NULL;
				$insert['product_list_id'] = $this->currentProductList->product_list_id;
				if(! Product::is_unique($insert, $data['errorMessage']))   goto fail;
				$this->db->insert('Products', $insert);
				goto success;
			} else {
				$data['editRegion'] = $this->load->view('products/addproducts', $data, TRUE);
				goto end;
			}
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id == '-1') {
			if(! $this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->delete) redirect('/products/manage', 'refresh');
			if(! $id = $this->uri->segment(4)) redirect('/products/manage', 'refresh');
			if(! is_numeric($this->uri->segment(4))) redirect('/products/manage', 'refresh');
			$del = new Product;
			if(! $del->build($id, $data['errorMessage'])) goto fail;
			if(! $del->rm($data['errorMessage']))         goto fail;
			$data['message'] = $del->ipc.' Removed';
			goto success;
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id > '0') {
			if(! $this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->update) redirect('/products/manage', 'refresh');
			
			if($this->form_validation->run('editProducts')) {
				$insert = $this->input->post();
				$insert['product_id'] = $id;
				$update = new Product;
				if(! $update->build($id, $data['errorMessage'])) goto fail;
				if(! $update->update($insert, $data['errorMessage'])) goto fail;
				goto success;
			} else {
				$data['edit'] = new Product;
				if(! $data['edit']->build($id, $data['errorMessage'])) goto fail;
				$data['editRegion'] = $this->load->view('products/editproduct', $data, TRUE);
				goto end;
			}

			goto fail;	
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} else {
			$data['errorMessage'] = 'Invalid argurment '.$id;
			goto fail;
		}



		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// Error Success Block
		goto end;
		success:
		$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		goto end;
		fail:
		$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
		end:
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// keep at bottom of the page
		$optVars = array (
			'link'    => '/products/warehouseUpdate/manage',
			'hidden'  => 'change_warehouse',
			'label'   => '',
			'key'     => 'warehouse_id',
			'class'   => 'Warehouse',
			'funct'   => 'options',
			'para'    => $this->user->user_id,
			'curVal'  => $this->currentWarehouse->warehouse_id
		);
		$data['select'][] = $this->load->view('common/select', $optVars, TRUE);
		$optVars = array (
			'link'    => '/products/productListUpdate/manage',
			'hidden'  => 'change_productList',
			'label'   => '',
			'key'     => 'product_list_id',
			'class'   => 'ProductLists',
			'funct'   => 'warehouse_options',
			'para'    => $this->currentWarehouse->warehouse_id,
			'curVal'  => $this->currentProductList->product_list_id
		);
		$data['select'][] = $this->load->view('common/select', $optVars, TRUE);
		
		
		$data['page']['id'] = 'products';
		$data['user_id'] = $this->user->user_id;
		$data['buttons'] = $this->manage_buttons();
		$data['page']['links'][$this->currentProductList->name] = $this->manage_actions();
		$data['tables'][$this->currentProductList->name] = Wms::fetch_table(
			'view_ProductsExpanded',
			'product_id',
			$this->config->item('productsTH'),
			'warehouse_id = '.$this->currentWarehouse->warehouse_id.' AND product_list_id ='.$this->currentProductList->product_list_id,
			$this->limit,
			$this->offset
			);
		//dump_debug($this->currentWarehouse);
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
	}

	private function manage_actions() {
		$links = array();
		if(! $this->currentProductList) return $links;
		
		if(isset($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id])) {
			if($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->delete)
				$links = array_merge_recursive($this->config->item('productTA-delete'), $links);
		}

		if(isset($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id])) {
			if($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->update)
				$links = array_merge_recursive($this->config->item('productTA-update'), $links);
		}
		
		return $links;
	}

	private function lists_actions() {
		$links = array();
		if(! $this->currentProductList) return $links;
		
		if(isset($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id])) {
			if($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->delete)
				$links = array_merge_recursive($this->config->item('productListTA-delete'), $links);
		}

		if(isset($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id])) {
			if($this->user->permissions['productListPermissions'][$this->currentProductList->product_list_id]->update)
				$links = array_merge_recursive($this->config->item('productListTA-update'), $links);
		}
		
		return $links;
	}

	public function warehouseUpdate($redirect) {
		if($this->input->post('change_warehouse')) {
			$warehouse_id = $this->input->post('warehouse_id');
			if($this->warehouse->id_is_valid($warehouse_id)) {
				if(isset($this->user->permissions['warehousePermissions'][$warehouse_id])) {
					if($this->user->permissions['warehousePermissions'][$warehouse_id]->read) {
						$this->session->set_userdata('currentWarehouse', $warehouse_id);
						$this->session->set_userdata('currentProductList', ProductLists::get_default($this->user->user_id, $warehouse_id));
					}
					else 
						$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
				}
				else
					$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);
			}
			else
				$this->session->set_userdata('currentWarehouse', $this->user->warehouse_id);

		}
		$this->session->unset_userdata('currentProductList');
		redirect("/products/".$redirect, 'refresh');
	}

	public function productListUpdate($redirect) {
		if($this->input->post('change_productList')) {
			$id = $this->input->post('product_list_id');
			if(ProductLists::id_is_valid($id)) {
				if(isset($this->user->permissions['productListPermissions'][$id])) {
					if($this->user->permissions['productListPermissions'][$id]->read)
						$this->session->set_userdata('currentProductList', $id);
					
					else $this->session->unset_userdata('currentProductList');
				} else $this->session->unset_userdata('currentProductList');
			} else $this->session->unset_userdata('currentProductList');			
		} else $this->session->unset_userdata('currentProductList');

		
		redirect('/products/'.$redirect, 'refresh');
	}

	public function dim($id = NULL) {

		if($id === NULL) {
			$data['editRegion'] = NULL;
			                                                                  goto end;
		}elseif(! is_numeric($id)) {
			$data['errorMessage'] = 'Invalid argument '.$id;
			                                                                  goto fail;
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id == '0') {
			//create
			if(! $this->user->permissions['modulePermissions']['products']->create) redirect('/products/dim', 'refresh');
			if($this->form_validation->run('addProductDims')) {
				$insert = $this->input->post();
				$insert['product_dim_id'] = NULL;
				if(! ProductDim::is_unique($insert, $data['errorMessage']))   goto fail;
				$this->db->insert('ProductDim', $insert);
				                                                              goto success;
			} else {
				$data['editRegion'] = $this->load->view('products/addproductdim', '', TRUE);
				                                                              goto end;
			}
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id == '-1') {
			if(! $this->user->permissions['modulePermissions']['products']->delete) redirect('/products/dim', 'refresh');
			if(! $id = $this->uri->segment(4))                                      redirect('/products/dim', 'refresh');
			if(! is_numeric($this->uri->segment(4)))                                redirect('/products/dim', 'refresh');
			$del = new ProductDim;
			if(! $del->build($id, $data['errorMessage']))                     goto fail;
			if(! $del->rm($data['errorMessage']))                             goto fail;
			$data['message'] = $del->name.' Removed';
			                                                                  goto success;
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} elseif($id > '0') {
			if(! $this->user->permissions['modulePermissions']['products']->update) redirect('/products/dim', 'refresh');
			
			if($this->form_validation->run('editProductDims')) {
				$insert = $this->input->post();
				$insert['product_dim_id'] = $id;
				$update = new ProductDim;
				if(! $update->build($id, $data['errorMessage']))              goto fail;
				if(! $update->update($insert, $data['errorMessage']))         goto fail;
				                                                              goto success;
			} else {
				$data['edit'] = new ProductDim;
				if(! $data['edit']->build($id, $data['errorMessage']))        goto fail;
				$data['editRegion'] = $this->load->view('products/editproductdim', $data, TRUE);
				                                                              goto end;
			}

			                                                                  goto fail;	
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		} else {
			$data['errorMessage'] = 'Invalid argurment '.$id;
			goto fail;
		}



		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// Error Success Block
		goto end;
		success:
		$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		goto end;
		fail:
		$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
		end:
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// keep at bottom of the page
		
		
		$data['page']['id'] = 'product-dim';
		$data['user_id'] = $this->user->user_id;
		$data['buttons'] = $this->dim_buttons();
		$data['page']['links']['Product Dimensions'] = $this->dim_actions();
		$data['tables']['Product Dimensions'] = Wms::fetch_table(
			'ProductDim',
			'product_dim_id',
			$this->config->item('productDimTH')
  		    //'product_dim_id = '.$this->currentProductList->product_list_id  /*where*/
			);

		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

	}

	private function dim_buttons() {
		if($this->user->permissions['modulePermissions']['products']->create)
			$buttons[] = Wms::button('/products/dim/0', 'Add Dimension', ICON_ROOT.'/map_add.png', 'positive');

		return $buttons;
	}

	private function dim_actions() {
		$links = array();
		
		if(isset($this->user->permissions['modulePermissions']['products'])) {
			if($this->user->permissions['modulePermissions']['products']->delete)
				$links = array_merge_recursive($this->config->item('productDimTA-delete'), $links);
		}

		if(isset($this->user->permissions['modulePermissions']['products'])) {
			if($this->user->permissions['modulePermissions']['products']->update)
				$links = array_merge_recursive($this->config->item('productDimTA-update'), $links);
		}
		
		return $links;
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */