<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productlistpermissions extends CI_Model {

    private static $mainTable        = 'ProductListsPermissions';
    private static $mainKey          = 'product_list_permission_id';
    private static $parentTable      = 'ProductLists';
    private static $parentKey        = 'product_list_id';
    private static $class            = 'Productlistpermissions';
    private static $buildUser        = TRUE;
    private static $buildProductList = TRUE;
    private static $buildWarehouse   = FALSE;
    

    function __construct() {
        parent::__construct();
    }

    public function build($id) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        $row = $query->row();

        if(self::$buildUser) {
            $this->user = new User;
            $this->user->build($row->user_id);
        }

        if(self::$buildProductList) {
            $this->productList = new ProductLists;
            $this->productList->build($row->product_list_id);
        }

        if(self::$buildWarehouse) {
            $this->warehouse = new Warehouse;
            $this->warehouse->build($row->warehouse_id);
        }


        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
    }

    public function build_flat($id) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        $row = $query->row();
        foreach($row as $key => $value)
            $this->{$key} = $value;        
    }
    
    static function fetch_all($parent_id = NULL, $user_id = NULL) {
        $CI =& get_instance();
        if($parent_id)
            $CI->db->where(self::$parentKey, $parent_id);
        if($user_id)
            $CI->db->where('user_id', $user_id);

        $query = $CI->db->get(self::$mainTable);
        foreach($query->result() as $row) {            
            $return[$row->{self::$mainKey}] = new self::$class;
            $return[$row->{self::$mainKey}]->build($row->{self::$mainKey}); 
        }
        if(!isset($return)) return FALSE;
        return $return;

    }

    static function fetch_all_flat($parent_id = NULL, $user_id = NULL) {
        $CI =& get_instance();
        $return = FALSE;

        if($parent_id)
            $CI->db->where(self::$parentKey, $parent_id);
        if($user_id)
            $CI->db->where('user_id', $user_id);

        $query = $CI->db->get(self::$mainTable);
        foreach($query->result() as $row) {            
            $return[$row->{self::$mainKey}] = new self::$class;
            $return[$row->{self::$mainKey}]->build_flat($row->{self::$mainKey}); 
        }
        return $return;
    }

    static function fetch_info($id) {
        $CI =& get_instance();
        $query = $CI->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        
        return $query->row;
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




}