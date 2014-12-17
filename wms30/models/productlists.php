<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProductLists extends CI_Model {

    private static $mainTable = 'ProductLists';
    private static $mainKey   = 'product_list_id';
    private static $parentTable = 'ProductLists';
    private static $parentKey = 'product_list_id';
    private static $class = 'ProductLists';
    private static $select = 'Select a Product List';
    private static $permissionClass = 'Productlistpermissions';
    private static $permissionKey = 'product_list_permission_id';
    private static $dimTable = '';
    private static $dimKey   = '';
    private static $dimName = '';
    private static $dimDescription = '';

    public $is_init = FALSE;

	function __construct() {
        parent::__construct();
    }


    public function build($id, &$message = NULL) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        if($query->num_rows() == 0) {
            $message = 'Invalid Product List ID';
            return FALSE;
        }
        $row = $query->row();
        
        $this->warehouse = new Warehouse;
        $this->warehouse->build($row->warehouse_id);
        
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
        $this->is_init = TRUE;
        return TRUE;
    }

     public function build_flat($id) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        $row = $query->row();
        
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
    }

    static function fetch_all_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get(self::$mainTable);
        foreach ($query->result_array() as $result) {
            foreach($headers as $key => $field) {
                $return[$result[self::$mainKey]][$field] = $result[$key];                
            }
        }
        return $return;
    }

    static function fetch_all($list = NULL) {
        $CI =& get_instance();
        if(is_array($list)){
            foreach($list as $item_id)
                $CI->db->or_where(self::$mainKey, $item_id);
        }
        $query = $CI->db->get(self::$mainTable);
        $return = FALSE;
        foreach($query->result() as $row) {            
            $return[$row->{self::$mainKey}] = new self::$class;
            $return[$row->{self::$mainKey}]->build($row->{self::$mainKey}); 
        }

        return $return;

    }

    static function fetch_info($id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where(self::$mainTable, array(self::$mainKey => $id));
    	return $query->row();
    }

    

    public static function options($user_id = NULL, $list = NULL) {
        // Set the first option as zero and simply ask to choose something
        $options[0] = self::$select;
    
        // If you received an empty array then stop now, we are done
        // They simply requested a placeholder
        if(is_array($list) && empty($list)) return $options;
        
        // If they only provided a user ID then they want only
        // the options for that user so generate a list of allowable
        // options for that user by asking the permission class for all
        // the permissions for that user and then generate a list of id's
        // to pass to the option generator
        if($user_id !== NULL && $list === NULL) {
            if($var = call_user_func(array(self::$permissionClass, 'fetch_all_flat'), NULL, $user_id)){
                foreach($var as $permission)
                    if($permission->read) $list[] = $permission->{self::$mainKey};
            }
        }

        // once you have a list of id's that you want to turn into options
        // we need to get all the information on those items, so pass it 
        // to the fetch all function and get a list of objects
        // if that list was empty or if there was an error then return a
        // placeholder         
        if(! $items = self::fetch_all($list)) return $options;
        
        // generate list and return
        foreach($items as $key => $item) 
            $options[$key] = $item->name;
        
        return $options;   
    }
    
    static function warehouse_options($id) {
        $CI =& get_instance();
        $CI->db->where('warehouse_id', $id);
        $query = $CI->db->get(self::$mainTable);
        foreach($query->result() as $row)
            $list[] = $row->{self::$mainKey};
       
        if(!isset($list)) $list = array();
        $options = self::options(NULL, $list);
        return $options;

    }
    
    public function id_is_valid($id) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        if($row = $query->row())   return TRUE;
        else                       return FALSE;
    }

    public function rm(&$message) {
        if(! $this->is_init) {
            $message = 'Must build object first';
            return FALSE;
        }
        if(! $this->id_is_valid($this->{self::$mainKey})) {
            $message = 'The ID you are trying to delete is not valid';
            return FALSE;
        }
        $this->db->where(self::$mainKey, $this->{self::$mainKey})->delete(self::$mainTable);
        if($this->db->affected_rows() == 0) {$message = 'Zero items deleted';                     return FALSE;}
        if($this->db->affected_rows() > 1)  {$message = 'More than 1 item deleted, inform admin'; return FALSE;}
        if($this->db->affected_rows() == 1)                                                       return TRUE;


    }

    static function get_default($user_id, $para = NULL) {
        if($permissions = call_user_func(array(self::$permissionClass, 'fetch_all_flat'), NULL, $user_id)) {
            if(is_numeric($para)) {
                foreach($permissions as $key => $permission) {
                    $list = self::fetch_info($permission->{self::$mainKey});
                    if($list->warehouse_id == $para) 
                        return $permission->{self::$mainKey};
                }
            }
            return reset($permissions)->{self::$mainKey};
        }
        else return FALSE;
    }

    
//This is in case the object has dimension data to pull
/*
    static function fetch_dims($id = NULL) {
        $CI =& get_instance();
        if($id != NULL) 
            $CI->db->where($this->dimTable, $id);        

        $query = $CI->db->get($this->dimTable);
        foreach($query->result() as $row) {
            $return[$row->{$this->dimKey}] = $row;
        }

        return $return;
    }
*/

//This is for the dimension options
/*
    static function dim_options($id = NULL) {
        $options[0] = "Select a Dimension";

        foreach($this->class()::fetch_dims($id) as $key => $dim) 
            $options[$key] = $dim->{$this->dimName}." - ".$dim->lwh." - ".$dim->{$this->dimDescription};
        
        return $options;
    }
*/
}