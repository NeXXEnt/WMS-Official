<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WarehousePermission extends CI_Model {

	function __construct() {
        parent::__construct();
    }
    
    static function fetch_all_warehouse_permissions($warehouse_id = NULL, $user_id = NULL) {
        $CI =& get_instance();
        if($warehouse_id)
            $CI->db->where('warehouse_id', $warehouse_id);
        if($user_id)
            $CI->db->where('user_id', $user_id);

        $query = $CI->db->get('WarehousePermissions');
        foreach($query->result() as $row) {            
            $return[$row->warehouse_permission_id] = new WarehousePermission;
            $return[$row->warehouse_permission_id]->build_warehouse_permission($row->warehouse_permission_id); 
        }
        if(!isset($return)) return FALSE;
        return $return;

    }

    static function fetch_all_flat_warehouse_permissions($warehouse_id = NULL, $user_id = NULL) {
        $CI =& get_instance();
        if($warehouse_id)
            $CI->db->where('warehouse_id', $warehouse_id);
        if($user_id)
            $CI->db->where('user_id', $user_id);

        $query = $CI->db->get('WarehousePermissions');
        foreach($query->result() as $row) {            
            $return[$row->warehouse_permission_id] = new WarehousePermission;
            $return[$row->warehouse_permission_id]->build_flat_warehouse_permission($row->warehouse_permission_id); 
        }
        if(!isset($return)) return FALSE;
        return $return;
    }

    static function fetch_all_warehouse_permission_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('WarehousePermissions');
        foreach ($query->result_array() as $warehousePermission) {
            foreach($headers as $key => $field) {
                $return[$warehousePermission['warehouse_permission_id']][$field] = $warehousePermission[$key];                
            }
        }
        return $return;
    }

    static function fetch_warehouse_permission_info($warehouse_permission_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('WarehousePermissions', array('warehouse_permission_id' => $warehouse_permission_id));
    	
    	return $query->row;
    }

    public function build_warehouse_permission($warehouse_permission_id) {
        $query = $this->db->get_where('WarehousePermissions', array('warehouse_permission_id' => $warehouse_permission_id));
        $row = $query->row();

        $this->user = new User;
        $this->user->build_user($row->user_id);
        $this->warehouse = new Warehouse;
        $this->warehouse->build_warehouse($row->warehouse_id);
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
    }
    public function build($id, &$message) {
        $query = $this->db->get_where('WarehousePermissions', array('warehouse_permission_id' => $id));
        $row = $query->row();

        $this->user = new User;
        $this->user->build_user($row->user_id);
        $this->warehouse = new Warehouse;
        $this->warehouse->build_warehouse($row->warehouse_id);
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        return TRUE;
    }

    public function build_flat_warehouse_permission($warehouse_permission_id) {
        $query = $this->db->get_where('WarehousePermissions', array('warehouse_permission_id' => $warehouse_permission_id));
        $row = $query->row();
        foreach($row as $key => $value)
            $this->{$key} = $value;        
    }

    public function rm(&$message) {
        if(! $this->is_init) {
            $message = "Object not Initialized";
            return FALSE;
        }

        $this->db->where('warehouse_permission_id', $this->warehouse_permission_id);
        $this->db->delete('WarehousePermissions');
        if($this->db->affected_rows() == 1) return TRUE;
        elseif($this->db->affected_rows() > 1){
            $message = "You have deleted more than one row, contact admin";
            return FALSE;
        }
        else {
            $message = "No rows deleted, id not found";
            return FALSE;
        }
    }

}