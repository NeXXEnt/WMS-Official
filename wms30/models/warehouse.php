<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse extends CI_Model {
    private static $mainTable         = 'Warehouses';
    private static $mainKey           = 'warehouse_id';
    private static $parentTable       =  FALSE; /*for permission Models*/
    private static $parentKey         =  FALSE; /* for permission Models */
    private static $class             = 'Warehouse';
    private static $select            = 'Warehouse';
    
    private static $permissionClass   = 'WarehousePermission';
    private static $permissionKey     = 'warehouse_permission_id';
    //private static $dimTable          = 'ProductDim';
    //private static $dimKey            = 'product_dim_id';
    //private static $dimName           = 'name';
    //private static $dimDescription    = 'shortDescription';
    
    private static $buildUser         = FALSE;
    private static $buildProductList  = FALSE;
    private static $buildWarehouse    = FALSE;
    private static $buildProductDim   = FALSE;
    
    // Unique variables for the is_unique method
    private static $uniqWith          = FALSE;
    private static $uniqKey1          = 'name';
    private static $uniqKey2          = FALSE;
    private static $uniqLabel1        = 'Dimension Name';
    private static $uniqLabel2        = '';
    
    public $is_init                   = FALSE;
    
	function __construct() {
        parent::__construct();
    }


    public function build_warehouse($warehouse_id, &$message = NULL) {
        $query = $this->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
        $row = $query->row();
        $this->city = new City;
        $this->city->build_city($row->city_id);
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
        return TRUE;
    }

     public function build_flat_warehouse($warehouse_id) {
        $query = $this->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
        $row = $query->row();
        
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function build($warehouse_id, &$message = NULL) {
        $query = $this->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
        $row = $query->row();
        $this->city = new City;
        $this->city->build_city($row->city_id);
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
        return TRUE;
    }

    public function build_flat($warehouse_id) {
        $query = $this->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
        $row = $query->row();

        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    static function fetch_all_warehouses_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('Warehouses');
        foreach ($query->result_array() as $warehouse) {
            foreach($headers as $key => $field) {
                $return[$warehouse['warehouse_id']][$field] = $warehouse[$key];                
            }
        }
        return $return;
    }

    static function fetch_all_warehouses($warehouseList = NULL) {
        $CI =& get_instance();
        if(is_array($warehouseList)){
            foreach($warehouseList as $warehouse_id)
                $CI->db->or_where('warehouse_id', $warehouse_id);
        }
        $query = $CI->db->get('Warehouses');
        foreach($query->result() as $row) {            
            $return[$row->warehouse_id] = new Warehouse;
            $return[$row->warehouse_id]->build_warehouse($row->warehouse_id); 
        }
        return $return;

    }

    static function fetch_warehouse_info($warehouse_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
    	return $query->row();
    }

    

    static function options($user_id = NULL, $warehouseList = NULL) {
        
        $warehouseOptions[0] = "Select a Warehouse";
        
        if($user_id) {
            foreach(WarehousePermission::fetch_all_flat_warehouse_permissions(NULL, $user_id) as $permission)
                if($permission->read) $warehouseList[] = $permission->warehouse_id;
        }         

        foreach(Warehouse::fetch_all_warehouses($warehouseList) as $key => $warehouse) 
            $warehouseOptions[$key] = $warehouse->name;
        
        return $warehouseOptions;   
    }
    
    static public function user_basket_options($warehouse_id, $user_id, &$message = '') {
        $CI =& get_instance();
        $where = "binIsAUserBasket = 1 AND ((warehouse_id = ".$warehouse_id." AND user_id = ".$user_id.") OR (warehouse_id = ".$warehouse_id." AND user_id IS NULL))";
        
        $CI->db->where($where);
        $query = $CI->db->get('Bins');
        
        $basketOptions[0] = "Select a Basket";
        
        foreach($query->result() as $row)
            $basketOptions[$row->bin_id] = $row->binAddress;
        
        return $basketOptions;
        return true;

    }

    public function id_is_valid($warehouse_id) {
        $query = $this->db->get_where('Warehouses', array('warehouse_id' => $warehouse_id));
        if($row = $query->row())   return TRUE;
        else                       return FALSE;
    }

    static function fetch_binDims($bin_dim_id = NULL) {
        $CI =& get_instance();
        if($bin_dim_id != NULL) 
            $CI->db->where('bin_dim_id', $bin_dim_id);        

        $query = $CI->db->get('BinDim');
        foreach($query->result() as $row) {
            $return[$row->bin_dim_id] = $row;
        }

        return $return;
    }

    static function binDim_options($bin_dim_id = NULL) {
        $options[0] = "Select a Dimension";

        foreach(Warehouse::fetch_binDims($bin_dim_id) as $key => $dim) 
            $options[$key] = $dim->binDimName." - ".$dim->lwh." - ".$dim->binDimShortDescription;
        
        return $options;
    }


}