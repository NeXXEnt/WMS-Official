<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {

	function __construct() {
        parent::__construct();
    }
    static function fetch_all_module_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('Modules');
        foreach ($query->result_array() as $module) {
            foreach($headers as $key => $field) {
                $return[$module['module_id']][$field] = $module[$key];                
            }
        }
        return $return;
    }
    static function fetch_all_modules() {
        $CI =& get_instance();
        $query = $CI->db->get('Modules');
        foreach($query->result() as $row) {            
            $return[$row->module_id] = new Module;
            $return[$row->module_id]->build_module($row->module_id); 
        }
        return $return;

    }

    static function fetch_module_info($module_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Modules', array('module_id' => $module_id));
    	$row = $query->row;
    	
    	return $row;
    }

    public function build_module($module_id) {
        $query = $this->db->get_where('Modules', array('module_id' => $module_id));
        $row = $query->row();
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
    }

    public function build_flat_module($module_id) {
        $query = $this->db->get_where('Modules', array('module_id' => $module_id));
        $row = $query->row();
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
    }

    static function module_options() {
        
        $moduleOptions[0] = "Select a Module";
        
        foreach(Module::fetch_all_modules() as $key => $module) 
            $moduleOptions[$key] = $module->moduleName;
     
        return $moduleOptions;   
    }
    



}