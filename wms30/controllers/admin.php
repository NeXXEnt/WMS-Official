<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		
		/*
		 *  Admin Security Check
		 */
		if(! $this->session->userdata('authenticated'))                 redirect('/auth', 'refresh');
		if(! $this->user->init($this->session->userdata('username')))   redirect('/auth', 'refresh');
		if(! $this->user->isAdmin)                                      redirect('/', 'refresh');

		// Add CSS files
		$this->template->add_css('css/default.css');

		// Add Javascript files
		$this->template->add_js('js/common.js');

		// Set Page Title
		$this->template->write('title', 'EasyRock WMS - Admin');		

		$data['menuLink'] = $this->user->generate_menu();
		$this->template->write_view('header', 'common/header', $data);

		// Build the SideBar
		$data['sideBarLink'] = array(
            'Manage Users'        => '/admin/user',
            'Manage Modules'      => '/admin/module',
            'Manage Permissions'  => '/admin/module_permissions',
            'Manage Cities'       => '/admin/city'
			);

		$this->template->write_view('sidebar', 'common/sidebar', $data);
	}
	
	public function index() {

		$this->template->render();
	}

	public function module_permissions($module_permission_id = NULL) {
		if($module_permission_id === NULL) $data['editRegion'] = NULL;
		elseif($module_permission_id == 0) {
			if(! $this->form_validation->run('newModulePermission')) 
				$data['editRegion'] = $this->load->view('admin/newmodulepermission', '', TRUE);				
			else {
				foreach($this->input->post() as $key => $post)
					$insertModulePermission[$key] = $post;
                if(!isset($insertModulePermission['read']))   $insertModulePermission['read']       = FALSE;
                if(!isset($insertModulePermission['create'])) $insertModulePermission['create']     = FALSE;
                if(!isset($insertModulePermission['update'])) $insertModulePermission['update']     = FALSE;
                if(!isset($insertModulePermission['delete'])) $insertModulePermission['delete']     = FALSE;
				
				$this->db->where('user_id', $insertModulePermission['user_id']);
				$this->db->where('module_id', $insertModulePermission['module_id']);
				$query = $this->db->get('ModulePermissions');
				$insertModulePermission['module_permission_id'] = NULL;
				if($query->num_rows() == 0)
					$this->db->insert('ModulePermissions', $insertModulePermission);
				else {
					$row = $query->row();
					unset($insertModulePermission['module_permission_id']);
					$this->db->where('module_permission_id', $row->module_permission_id);
					$this->db->update('ModulePermissions', $insertModulePermission);
				}
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}

			
		} elseif ($module_permission_id > 0) {
			$this->db->where('module_permission_id', $module_permission_id);
			$query = $this->db->get('ModulePermissions');
			if($query->num_rows() != 1)
				redirect('/admin/module_permissions', 'refresh');
			else {
				$row = $query->row();
				$data['editModulePermission'] = new ModulePermissions;
				$data['editModulePermission']->build_module_permission($row->module_permission_id);
			}

			if(! $this->form_validation->run('editModulePermission'))
				$data['editRegion'] = $this->load->view('admin/editmodulepermission', $data, TRUE);
			else {
				foreach($this->input->post() as $key => $post)
					$insertModulePermission[$key] = $post;
                if(!isset($insertModulePermission['read']))   $insertModulePermission['read']       = FALSE;
                if(!isset($insertModulePermission['create'])) $insertModulePermission['create']     = FALSE;
                if(!isset($insertModulePermission['update'])) $insertModulePermission['update']     = FALSE;
                if(!isset($insertModulePermission['delete'])) $insertModulePermission['delete']     = FALSE;
				
				$this->db->where('user_id', $insertModulePermission['user_id']);
				$this->db->where('module_id', $insertModulePermission['module_id']);
				$query = $this->db->get('ModulePermissions');
				$insertModulePermission['module_permission_id'] = NULL;
				if($query->num_rows() == 0)
					$this->db->insert('ModulePermissions', $insertModulePermission);
				else {
					$row = $query->row();
					unset($insertModulePermission['module_permission_id']);
					$this->db->where('module_permission_id', $row->module_permission_id);
					$this->db->update('ModulePermissions', $insertModulePermission);
				}
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}
		}
		$data['page']['id'] = "hi";
		$data['buttons'][] = Wms::button('/admin/module_permissions/0', 'Add Permission', ICON_ROOT.'/key_add.png', 'positive');
		$data['boolrg'] = TRUE;
		foreach(Module::fetch_all_modules() as $key => $module) {
			$tableName = $module->moduleName.' Permissions';
			$data['tables'][$tableName] = Wms::fetch_table(
				'view_UserModulePermissions', 
				'module_permission_id', 
				$this->config->item('modulePermissionTH'), 
				'module_id = '.$module->module_id
				);
			$data['page']['links'][$tableName] = $this->config->item('modulePermissionTA');
		}


		$this->template->write_view('content', 'common/tables', $data);
		//$this->template->write_view('content', 'admin/permissions', $data);
		$this->template->render();
	}
	
	public function rmmodule_permission($module_permission_id = NULL) {
		if(! $module_permission_id) redirect('/admin/module_permissions', 'refresh');
		
		if(! $this->input->post('confirmed')){
			$data['rmModulePermission'] = new ModulePermissions;
			$data['rmModulePermission']->build_module_permission($module_permission_id);
			$this->template->write_view('content', 'admin/rmmodulepermission', $data);
			$this->template->render();
		} else {
			$this->db->where('module_permission_id', $module_permission_id);
			$this->db->delete('ModulePermissions');
			redirect('admin/module_permissions', 'refresh');
		}
		
	}

	public function user($user_id = NULL) {		
		$data = $this->user_page_data();

		if ($user_id === NULL)
			$data['editRegion'] = NULL;
		elseif ($user_id == 0) { //**************************************************** New User
			if(! $this->form_validation->run('newUser')) {
				$data['editRegion'] = $this->load->view('admin/newuser', '', TRUE);
			} else {
				foreach($this->input->post() as $key => $post)
					$insertUser[$key] = $post;

				$insertUser['user_id'] = NULL;				
				$insertUser['userPassword'] = User::return_password_hash($insertUser['userPassword']);				
				unset($insertUser['confPassword']);

				$this->db->insert('Users', $insertUser);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);

			}
		} else { //******************************************************************* Edit User
			if($this->form_validation->run('editUser') == FALSE) {
				$data['editUser'] = new User;
				$data['editUser']->build_user($user_id);
				$data['editRegion'] = $this->load->view('admin/edituser', $data, TRUE);
			} else {
				foreach($this->input->post() as $key => $post)
					$editUser[$key] = $post;
				if(! isset($editUser['admin'])) $editUser['admin'] = FALSE;
				if(! isset($editUser['accountEnabled'])) $editUser['accountEnabled'] = FALSE;
				if($editUser['userPassword'] == '') unset($editUser['userPassword']);
				else $editUser['userPassword'] = User::return_password_hash($editUser['userPassword']);
				$compUser = new User;
				$compUser->build_user($user_id);
				if($compUser->userName != $editUser['userName']) {
					$this->db->where('userName', $editUser['userName']);
					$query = $this->db->get('Users');
					if($query->num_rows() > 0) 
						$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
					else {
						unset($editUser['user_id']);
						$this->db->where('user_id', $user_id);
						$this->db->update('Users', $editUser);
						$data['editRegion'] = $this->load->view('common/success', '', TRUE);	
					}
				} else {
					unset($editUser['user_id']);
					$this->db->where('user_id', $user_id);
					$this->db->update('Users', $editUser);
					$data['editRegion'] = $this->load->view('common/success', '', TRUE);	
				}		
			}
		}
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function groupusers($group_id = NULL) {
		if($group_id === NULL || !$group_id) redirect('/admin/user', 'refresh');
		
		$this->db->where('group_id', $group_id);
		$query = $this->db->get('Groups');
		if($query->num_rows() < 1) redirect('/admin/user', 'refresh');
		$row = $query->row();
		$tableName = $row->groupName;
		$data['tables'][$tableName] = Wms::fetch_table(
			'view_UserGroups', 
			'user_group_id',
			$this->config->item('groupUsersTH'),
			'group_id ='.$group_id
			);
		
		

		$data['page']['links'][$tableName] = $this->config->item('userGroupTA');
		$data = array_merge_recursive($data, $this->user_page_data());
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}
	public function usergroup($user_id = NULL) {
		if($user_id === NULL || !$user_id) redirect('/admin/user', 'refresh');
		
		$editUser = new User;
		$editUser->build_user($user_id);
		$tableName = $editUser->firstName.' '.$editUser->lastName;
		$data['tables'][$tableName] = Wms::fetch_table(
			'view_UserGroups', 
			'user_group_id',
			$this->config->item('userGroupTH'),
			'user_id ='.$user_id
			);
		
		

		$data['page']['links'][$tableName] = $this->config->item('userGroupTA');
		$data = array_merge_recursive($data, $this->user_page_data());
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}
	public function group($group_id = NULL) {
		if($group_id === NULL) redirect('/admin/user', 'refresh');
		
		$data = $this->user_page_data();

		if($group_id == 0) {
			if(!$this->form_validation->run('newGroup'))
				$data['editRegion'] = $this->load->view('admin/newgroup','',TRUE);
			else {
				$insert = $this->input->post();
				$insert['group_id'] = NULL;
				$this->db->insert('Groups', $insert);
				$data['editRegion'] = $this->load->view('common/success','',TRUE);
			}
		} else {
			if(!$this->form_validation->run('editGroup')){
				$var = 'hi';
			}

		}
		
		
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();		
	}

	public function addtogroup($user_id = NULL) {
		if($user_id == NULL) redirect('/admin/user', 'refresh');
		$data = $this->user_page_data();
		$user = new User;
		$user->build_user($user_id);
		$data['user'] = $user;
		if(!$this->form_validation->run('addUserToGroup')) {
			$data['editRegion'] = $this->load->view('admin/addtogroup', $data, TRUE);
		} else {
			$insert['user_id'] = $user_id;
			$insert['group_id'] = $this->input->post('group_id');
			$this->db->where('user_id', $insert['user_id']);
			$this->db->where('group_id', $insert['group_id']);
			$query = $this->db->get('UserGroups');
			if($query->num_rows() == 0) {
				$this->db->insert('UserGroups', $insert);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE); 
			} elseif ($query->num_rows() == 1) {
				$data['noticeMessage'] = $user->username.' is already in that group.';
				$data['editRegion'] = $this->load->view('common/notice', $data, TRUE);
			} else {
				$data['errorMessage'] = 'Database Error - Duplicate entries exist, contact Admin';
				$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
			}
		}

		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function rmusergroup($user_group_id = NULL) {
		if($user_group_id === NULL || !$user_group_id || $user_group_id == 'NULL') redirect('/admin/user', 'refresh');
		if(! $this->input->post('confirmed')){
			$data['returnLink'] = '/admin/user';
			$data['forwardLink'] = "/admin/rmusergroup/$user_group_id";
			$data['question'] = "Are you sure you want to remove user from group?";

			$this->template->write_view('content', 'common/confirm', $data);
			$this->template->render();
		} else {
			$this->db->where('user_group_id', $user_group_id);
			$this->db->delete('UserGroups');
			redirect('/admin/user', 'refresh');
		}
	}

	public function rmuser($user_id = NULL) {
		if($user_id === NULL || ! $user_id) redirect('/admin', 'refresh');
		
		if(! $this->input->post('confirmed')){
			$data['rmUser'] = new User;
			$data['rmUser']->build_user($user_id);
			$this->template->write_view('content', 'admin/rmuser', $data);
			$this->template->render();
		} else {
			$this->db->where('user_id', $user_id);
			$this->db->delete('Users');
			redirect('admin/user', 'refresh');
		}
		
	}

	public function module($module_id = NULL) {
		if($module_id === NULL) {
			$data['editRegion'] = NULL;			
		} elseif ($module_id == 0) {
			if(! $this->form_validation->run('newModule')) 
				$data['editRegion'] = $this->load->view('admin/newmodule', '', TRUE);
			else {
				foreach($this->input->post() as $key => $post) 
					$insertModule[$key] = $post;				
				$insertModule['module_id'] = NULL;	
				$this->db->insert('Modules', $insertModule);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
			}
		} else {
			if(! $this->form_validation->run('editModule')) {
				$data['editModule'] = new Module;
				$data['editModule']->build_module($module_id);
				$data['editRegion'] = $this->load->view('admin/editmodule', $data, TRUE);
			} else {
				foreach($this->input->post() as $key => $post) {
					$editModule[$key] = $post;
				}	
				$compModule = new Module;
				$compModule->build_module($module_id);
				if($compModule->moduleName != $editModule['moduleName']) {
					$this->db->where('moduleName', $editModule['moduleName']);
					$query = $this->db->get('Modules');
					if($query->num_rows() > 0) {
						$data['errorMessage'] = 'The Username was not unique - Update Failed';
						$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
					} else {
						unset($editModule['module_id']);
						$this->db->where('module_id', $module_id);
						$this->db->update('Modules', $editModule);
						$data['editRegion'] = $this->load->view('common/success', '', TRUE);
					}
				} else {
					unset($editModule['module_id']);
					$this->db->where('module_id', $module_id);
					$this->db->update('Modules', $editModule);
					$data['editRegion'] = $this->load->view('common/success', '', TRUE);	
				}		
			}

		}

		$data['page']['id'] = 'all-modules';
		$data['page']['links']['Modules'] = $this->config->item('modulesTA');		
		$data['buttons'][] = Wms::button('/admin/module/0', 'Add Module', ICON_ROOT.'/plugin_add.png', 'positive');
		$data['tables']['Modules'] = Wms::fetch_table('Modules', 'module_id', $this->config->item('moduleTH'));
		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function rmmodule($module_id = NULL) {
		if(! $module_id) redirect('/admin/', 'refresh');
		
		if(! $this->input->post('confirmed')){
			$data['rmModule'] = new Module;
			$data['rmModule']->build_module($module_id);
			$this->template->write_view('content', 'admin/rmmodule', $data);
			$this->template->render();
		} else {
			$this->db->where('module_id', $module_id);
			$this->db->delete('Modules');
			redirect('admin/module', 'refresh');
		}
		
	}

	public function city($city_id = NULL) {
		if($city_id === NULL) $data['editRegion'] = NULL;			
		elseif ($city_id == 0) {			
			if(! $this->form_validation->run('newCity')) 				
				$data['editRegion'] = $this->load->view('admin/newcity', '', TRUE);
			else {
				
				if($this->input->post('newRegion')) {
					$insert['region_id'] = NULL;
					$insert['regionName'] = $this->input->post('regionName');
					$insert['regionDirection'] = $this->input->post('regionDirection');
					$insert['regionDescription'] = $this->input->post('regionDescription');
					$this->db->insert('Regions', $insert);
					$data['message'] = $data['message'].'<p class="success">Region added</p>';
				}
				if($this->input->post('newCountry')) {

				}
				if($this->input->post('newProvince')) {

				}



				/*
				
				foreach($this->input->post() as $key => $post) {
					$insertCity[$key] = $post;
				}
				unset($insertCity['country_id']);
				$insertCity['city_id'] = NULL;	
				$this->db->insert('Cities', $insertCity);
				$data['editRegion'] = $this->load->view('common/success', '', TRUE);
				*/
			}
		} else {
			if($this->form_validation->run('editCity') == FALSE) {
				$data['editCity'] = new City;
				$data['editCity']->build_city($city_id);
				$data['editRegion'] = $this->load->view('admin/editcity', $data, TRUE);
			} else {
				foreach($this->input->post() as $key => $post) {
					$editCity[$key] = $post;
				}						 
				$compCity = new City;
				$compCity->build_city($city_id);
				if($compCity->cityName != $editCity['cityName']) {
					$this->db->where('cityName', $editCity['cityName']);
					$query = $this->db->get('Cities');
					if($query->num_rows() > 0) {
						$data['errorMessage'] = 'The City was not unique - Update Failed';
						$data['editRegion'] = $this->load->view('common/fail', $data, TRUE);
					} else {
						unset($editCity['city_id']);
						$this->db->where('city_id', $city_id);
						$this->db->update('Cities', $editCity);
						$data['editRegion'] = $this->load->view('common/success', '', TRUE);
					}
				} else {
					unset($editCity['city_id']);
					$this->db->where('city_id', $city_id);
					$this->db->update('Cities', $editCity);
					$data['editRegion'] = $this->load->view('common/success', '', TRUE);	
				}		
			}
		}
		$data['page']['id'] = 'all-cities';
		$data['page']['links']['Cities'] = $this->config->item('citiesTA');
		$data['buttons'][] = Wms::button('/admin/city/0', 'Add City', ICON_ROOT.'/user_add.png', 'positive');
		$data['tables']['Cities'] = Wms::fetch_table('CityLocations', 'city_id', $this->config->item('cityTH'));

		$this->template->write_view('content', 'common/tables', $data);
		$this->template->render();
	}

	public function rmcity($city_id = NULL) {
		if(! $city_id) redirect('/admin/city', 'refresh');
		
		if(! $this->input->post('confirmed')){
			$data['rmCity'] = new City;
			$data['rmCity']->build_city($city_id);
			$this->template->write_view('content', 'admin/rmcity', $data);
			$this->template->render();
		} else {
			$this->db->where('city_id', $city_id);
			$this->db->delete('Cities');
			redirect('admin/city', 'refresh');
		}
	}

	private function user_page_data() {
		$data['page']['id'] = 'all-users';
		$data['page']['links']['Users'] = $this->config->item('usersTA');
		$data['page']['links']['Groups'] = $this->config->item('groupsTA');		
		$data['buttons'][] = Wms::button('/admin/user/0', 'Add User', ICON_ROOT.'/user_add.png', 'positive');
		$data['buttons'][] = Wms::button('/admin/group/0', 'Add Group', ICON_ROOT.'/group_add.png', 'positive');
		$data['tables']['Users'] = Wms::fetch_table('Users', 'user_id', $this->config->item('userTH'));
		$data['tables']['Groups'] = Wms::fetch_table('Groups', 'group_id', $this->config->item('groupTH'));
		return $data;
	}
}
