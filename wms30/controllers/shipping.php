<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipping extends CI_Controller {

	public function __construct() {
		parent::__construct();

		if(! $this->session->userdata('authenticated'))                 			redirect('/auth', 'refresh');
		if(! $this->user->init($this->session->userdata('username')))   			redirect('/auth', 'refresh');
		if(! $this->user->permissions['modulePermissions']['shipping']->read)       redirect('/', 'refresh');
		
		// Set Page Title
		$this->template->write('title', 'EasyRock WMS - Shipping');

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

		$data['sideBarLink'] = array(
            'Items' => '/shipping/items'
			);
		$this->template->write_view('sidebar', 'common/sidebar', $data);

		if($this->session->userdata('errorMessage')) {
			$this->errorMessage = $this->session->userdata('errorMessage');
			$this->session->unset_userdata('errorMessage');
		}

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
	}

	public function index() {
		$this->template->render();
	}
	
	private function items_buttons() {
		$buttons = array(); 
		if($this->user->permissions['warehousePermissions'][$this->currentWarehouse->warehouse_id]->update){
			$buttons[] = Wms::button('/shipping/items/-1', 'Take', ICON_ROOT.'/archive-extract-2.png', 'positive');
			$buttons[] = Wms::button('/shipping/items/0', 'Put', ICON_ROOT.'/archive-insert-2.png', 'positive');
		}
		return $buttons;

	}
	public function move() {
		$this->db->where('item_id', '8');
		$this->db->update('Items', array('bin_id' => '236'));
		redirect('/shipping/items', 'refresh');
	}

	public function items($id = NULL) {
		$method          = $this->input->post('method');             // One of the two ways we are moving through this mess
		$step            = $this->uri->segment(4);				     // The particular step we are at in this method
		
		$ipc             = $this->input->post('ipc');                // Here we process any possible piece of data that came through
		$binAddress      = $this->input->post('binAddress');
		$qty             = $this->input->post('qty');
		$comment         = $this->input->post('comment');
		$poNumber        = $this->input->post('poNumber');
		
		$bin_id          = $this->input->post('bin_id');             // Then we get what ever Id's we can
		$product_list_id = $this->input->post('product_list_id');
		$product_id      = $this->input->post('product_id');
		$basket_id       = $this->input->post('basket_id');
		$item_id		 = $this->input->post('item_id');
		
		$bin             = new Bins;                                 // Declare all the object variables
		$product         = new Product;
		$productList     = new ProductLists;
		$item            = new Items;
		$basket          = new Bins;

		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// Build Objects
		$debug = FALSE;
		$bug   = 0;
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// Build Bin
		// we are building an item that already exists, so $item will have an item_id now
		if($item_id){
			if($debug) echo 'Item<br>';
			if(! $item->build($item_id, $data['errorMessage']))                                                         goto fail;
		// need to rewrite this next one, it doesn't make sense
		}

		if($bin_id > '0') {
			if($debug) echo 'bin1<br>';
			if(! $bin->build($bin_id, $data['errorMessage']))                                                           goto fail;
		}
		elseif($binAddress) {
			if($debug) echo 'bin2<br>';
			if(! $bin->build($bin->find_id($this->currentWarehouse->warehouse_id, $binAddress), $data['errorMessage'])) goto fail;
		}
		
		// Build Product
		if($product_id) {
			if($debug) echo 'product1<br>';
			if(! $product->build($product_id, $data['errorMessage']))                                                   goto fail;
		}
		elseif($ipc && $product_list_id) {
			if($debug) echo 'product2<br>';
			if(! $product->build_ipc($ipc, $product_list_id, $data['errorMessage']))                              		goto fail;
		}

		// Build Product List
		if($product_list_id) { 				
			if($debug) echo 'product list1<br>';
			if(! $productList->build($product_list_id, $data['errorMessage']))                                          goto fail;
		}
		elseif($product->is_init) {
			if($debug) echo 'product list2<br>';
			if(! $productList->build($product->product_list_id, $data['errorMessage']))			                        goto fail;
		}


		// otherwise we are building a new item, so item_id will be null and we will be inserting the item later
		elseif($product->is_init && $bin->is_init && $bin->binIsInfinite) {
			$build          = new stdClass();
			$build->product = $product;
			$build->user    = $this->user;
			$build->bin     = $bin;
			if($debug) echo 'Build new<br>';
			if(! $item->build_new($build, $data['errorMessage']))                                                       goto fail;
		}

		if($basket_id > '0') {
			if($debug) echo 'Basket<br>';

			if(! $basket->build($basket_id, $data['errorMessage']))                                                     goto fail;
		}

		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
		$data['method']      = $method;
		
		$data['bin']         = $bin;
		$data['product']     = $product;
		$data['productList'] = $productList;
		$data['item']        = $item;
		$data['basket'] 	 = $basket;
		

		$data['user']        = $this->user;
		$data['warehouse']   = $this->currentWarehouse;
		
		switch($id) {
			case NULL:
				goto end;
				break;
			// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
			// TAKE
			case '-1' :
				switch($step) {
					case FALSE:
						$data['editRegion'] = $this->load->view('shipping/take/take', $data, TRUE);
						goto end;				
					case '2':
						switch($method) {
							case 'ipc':
								$data['editRegion'] = $this->load->view('shipping/take/ipc', $data, TRUE);
								goto end;
							case 'bin':
								if($bin->is_init) {
									$data['editRegion'] = $this->load->view('shipping/take/bin', $data, TRUE);
								}else redirect('/shipping/items/-1', 'refresh');
								goto end;
							}						
						break;
					case '3':
						switch($method) {
							case 'ipc':
								if($item_id == '0') {
									$data['editRegion'] = $this->load->view('shipping/take/selectinfinite', $data, TRUE);
									goto end;
								}
								if($item->is_init) {
									if($qty == $item->qty) {
										$item->move($this->user->get_basket($this->currentWarehouse->warehouse_id), $this->user);
										goto success;
									}elseif($qty < $item->qty && $qty > '0') {
										$item->split($this->user->get_basket($this->currentWarehouse->warehouse_id), $qty, $this->user);
										goto success;
									}else{
										$data['errorMessage'] = 'Qty must be greater than 0 and less than '.$item->qty;
										goto fail;
									}
								}
								goto end;
							case 'bin':
								if($bin->binIsInfinite) {
									$data['editRegion'] = $this->load->view('shipping/takeinfinite', $data, TRUE);
									goto end;
								}elseif($bin->binIsAUserBasket) {

								}else{

								}
							}
					case '4':
						switch($method) {
							case 'ipc':
								// The following is for creating a new item from a door or infinite bin
								if($item_id == '0') {
									$insert = array(
										'item_id' => NULL,
										'product_id' => $product->product_id,
										'bin_id' => $bin->bin_id,
										'created_by' => $this->user->user_id,
										'createdDate' => date("Y-m-d H:i:s"),
										'updated_by' => $this->user->user_id,
										'qty' => $qty,
										'comment' => $comment,
										'poNumber' => $poNumber,
										'parent_id' => NULL
										);
									$this->db->insert('Items', $insert);
									$insert['item_id'] = $this->db->insert_id();
									// log insert
									$update['bin_id'] = $this->user->get_basket($this->currentWarehouse->warehouse_id);
									$this->db->where('item_id', $insert['item_id']);
									$this->db->update('Items', $update);
									//log update
									goto success;
								}


								if(! $item->is_init) goto end;
								if(! $bin->is_init) goto end;
								if(! $basket->is_init) goto end;
								if($qty < $item->qty && $qty > 0) {
									$newItem              = new stdClass();
									$newItem->item_id     = NULL;
									$newItem->product_id  = $item->product_id;
									$newItem->bin_id      = $basket->bin_id;
									$newItem->created_by  = $this->user->user_id;
									$newItem->createdDate = date("Y-m-d H:i:s");
									$newItem->updated_by  = $this->user->user_id;
									$newItem->qty         = $qty;
									$newItem->comment     = $item->comment;
									$newItem->poNumber    = $item->poNumber;
									$newItem->parent_id   = $item->item_id;
									$this->db->insert('Items', $newItem);
									if($this->db->affected_rows() == 0) {
										$data['errorMessage'] = 'Unable to split item';
										goto fail;
									}elseif($this->db->affected_rows() > 1) {
										$data['errorMessage'] = 'Inserted more than one item';
										goto fail;
									}
									$item->qty -= $qty;
									$update = array('qty' => $item->qty, 'updated_by' => $this->user->user_id,);
									$this->db->where('item_id', $item->item_id);
									$this->db->update('Items', $update);
									if($this->db->affected_rows() == 0) {
										$data['errorMessage'] = 'Unable to Update original item';
										goto fail;
									}elseif($this->db->affected_rows() > 1) {
										$data['errorMessage'] = 'Modified more than one item';
										goto fail;
									}
									goto success;

								} elseif($qty == $item->qty) {
									$this->db->where('item_id', $item->item_id);
									$this->db->update('Items', array('bin_id' => $basket->bin_id, 'updated_by' => $this->user->user_id));
									goto success;
								} else {
									$data['errorMessage'] = 'Invalid Qty, ammount must be greater than 0 and less than or equal to: '.$item->qty;
									goto fail;
								}
								goto end;
							case 'bin':
								$data['editRegion'] = $this->load->view('shipping/takeproductbin', $data, TRUE);
								goto end;

							}
					case '5':
						switch($method) {
							case 'ipc':

							case 'bin':
								$insert = $this->input->post();							
								unset($insert['method']);
								$insert['createdDate'] = date("Y-m-d H:i:s");
								$insert['updatedDate'] = date("Y-m-d H:i:s");
								dump_debug($insert);
								$this->db->insert('Items', $insert);
								goto end;
							}
						
						/* <?= form_hidden('parent_item', NULL) ?>
						// <?= form_hidden('updatedDate', NULL) ?>
						// <?= form_hidden('createdDate', NULL) ?>
						// <?= form_hidden('item_id', NULL) ?>*/	
					}
					break;	
			// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

			// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
			// PUT
			case '0' :
				switch($step) {
					case FALSE:
						$data['editRegion'] = $this->load->view('shipping/put/put', $data, TRUE);
						goto end;
						break;
					case '2':
						if($qty == $item->qty) {
							$update['bin_id'] = $bin->bin_id;
							$update['updated_by'] = $this->user->user_id;
							$this->db->where('item_id', $item->item_id);
							$this->db->update('Items', $update);							
							goto success;
						} elseif($qty < $item->qty) {
							$newItem = array(
								'item_id' => NULL,
								'product_id' => $item->product_id,
								'bin_id' => $bin->bin_id,
								'created_by' => $this->user->user_id,
								'createdDate' => date("Y-m-d H:i:s"),
								'updated_by' => $this->user->user_id,
								'qty' => $qty,
								'comment' => $item->comment,
								'poNumber' => $item->poNumber,
								'parent_id' => $item->item_id
								);
							$update['qty'] = $item->qty - $qty;
							$update['updated_by'] = $this->user->user_id;
							$this->db->insert('Items', $insert);
							$this->db->update('Items', $update);
							goto success;

						}
						goto end;										
				}
				break;
		}
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
	
		// ▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
		// Error/Success Block
		goto end;
		success:
		$data['editRegion'] = $this->load->view('common/success', $data, TRUE);
		goto end;
		fail:
		$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
		end:
		// ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
		$optVars = array (
			'link'    => '/shipping/warehouseUpdate/items',
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
			'link'    => '/shipping/productListUpdate/items',
			'hidden'  => 'change_productList',
			'label'   => '',
			'key'     => 'product_list_id',
			'class'   => 'ProductLists',
			'funct'   => 'warehouse_options',
			'para'    => $this->currentWarehouse->warehouse_id,
			'curVal'  => $this->currentProductList->product_list_id
		);
		$data['select'][] = $this->load->view('common/select', $optVars, TRUE);




		$data['page']['id'] = 'items';
		$data['buttons'] = $this->items_buttons();
		$data['tables'][$this->currentWarehouse->name] = Wms::fetch_table(
			'view_ShippingItems',
			'item_id',
			$this->config->item('itemsTH'),
			'warehouse_id = '.$this->currentWarehouse->warehouse_id.' AND product_list_id ='.$this->currentProductList->product_list_id
			
			);
		
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
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
		redirect("/shipping/".$redirect, 'refresh');
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

		
		redirect('/shipping/'.$redirect, 'refresh');
	}










}