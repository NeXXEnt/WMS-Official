<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModulePermissions extends CI_Model {

	function __construct() {
        parent::__construct();
    }
    
    static function fetch_all_module_permissions($module_id = NULL, $user_id = NULL) {
        $CI =& get_instance();
        if($module_id)
            $CI->db->where('module_id', $module_id);
        if($user_id)
            $CI->db->where('user_id', $user_id);

        $query = $CI->db->get('ModulePermissions');
        foreach($query->result() as $row) {            
            $return[$row->module_permission_id] = new ModulePermissions;
            $return[$row->module_permission_id]->build_module_permission($row->module_permission_id); 
        }
        if(!isset($return)) return FALSE;
        return $return;

    }

    static function fetch_all_module_permission_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('ModulePermissions');
        foreach ($query->result_array() as $modulePermission) {
            foreach($headers as $key => $field) {
                $return[$modulePermission['module_permission_id']][$field] = $modulePermission[$key];                
            }
        }
        return $return;
    }

    static function fetch_module_permission_info($module_permission_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('ModulePermissions', array('module_permission_id' => $module_permission_id));
    	
    	return $query->row;
    }

    public function build_module_permission($module_permission_id) {
        $query = $this->db->get_where('ModulePermissions', array('module_permission_id' => $module_permission_id));
        $row = $query->row();

        $this->user = new User;
        $this->user->build_user($row->user_id);
        $this->module = new Module;
        $this->module->build_module($row->module_id);
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function build_flat_module_permission($module_permission_id) {
        $query = $this->db->get_where('ModulePermissions', array('module_permission_id' => $module_permission_id));
        $row = $query->row();
        foreach($row as $key => $value)
            $this->{$key} = $value;        
    }


}