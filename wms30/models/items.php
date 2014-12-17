<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Model {
    
    private static $mainTable         = 'Items';
    private static $mainKey           = 'item_id';
    //private static $parentTable     = '';
    //private static $parentKey       = '';
    private static $class             = 'Items';
    private static $select            = 'Select an Item';
    //private static $permissionClass = '';
    //private static $permissionKey   = '';
    //private static $dimTable          = 'ProductDim';
    //private static $dimKey            = 'product_dim_id';
    //private static $dimName           = 'name';
    //private static $dimDescription    = 'shortDescription';
    
    private static $buildUser         = TRUE;
    private static $buildProduct      = TRUE;
    private static $buildBin          = TRUE;
    private static $buildProductList  = FALSE;
    private static $buildWarehouse    = FALSE;
    private static $buildProductDim   = FALSE;

    
    // Unique variables for the is_unique method
    //private static $uniqWith          = 'item_id';
    //private static $uniqKey1          = 'ipc';
    //private static $uniqKey2          = 'upc';
    //private static $uniqLabel1        = 'IPC/SKU#';
    //private static $uniqLabel2        = 'UPC#';
    
    public $is_init                   = FALSE;

	function __construct() {
        parent::__construct();
    }

    // Build the item from an IPC code and Product list
    // meant for the products model but left here just in case it's still relevent
    public function build_ipc($ipc, $product_list_id, &$message = NULL) {
        $this->db->where('ipc', $ipc);
        $this->db->where('product_list_id', $product_list_id);
        $query = $this->db->get('Products');
        if($query->num_rows() > 1) {$message = 'More than one match found'; return FALSE;}
        if($query->num_rows() == 0) {$message = 'Match not found'; return FALSE;}
        $row = $query->row();
        if(! $this->build($row->product_id, $message)) return FALSE;
        else return TRUE;
    }

    // This function requires objects in the $data variable
    // $data->product
    // $data->user
    // $data->bin
    public function build_new($data, &$message = '') {
        $this->item_id     = NULL;
        $this->product_id  = $data->product->product_id;
        $this->bin_id      = $data->bin->bin_id;
        $this->created_by  = $data->user->user_id;
        $this->createdDate = date("Y-m-d H:i:s");
        $this->updated_by  = $data->user->user_id;
        $this->is_init     = TRUE;
        return TRUE;


    }

    public function move($bin_id, $user) {
        $update['bin_id'] = $bin_id;
        $update['updated_by'] = $user->user_id;
        $this->db->where(self::$mainKey, $this->item_id);
        $this->db->update(self::$mainTable, $update);
        return TRUE;
    }

    public function split($bin_id, $qty, $user) {
        $insert = array(
            'item_id' => NULL,
            'product_id' => $this->product->product_id,
            'bin_id' => $bin_id,
            'created_by' => $user->user_id,
            'createdDate' => date("Y-m-d H:i:s"),
            'updated_by' => $user->user_id,
            'qty' => $qty,
            'comment' => $this->comment,
            'poNumber' => $this->poNumber,
            'parent_id' => $this->item_id
            );
        $this->db->insert(self::$mainTable, $insert);
        $update['qty'] = $this->qty - $qty;
        $update['updated_by'] = $user->user_id;
        $this->db->where(self::$mainKey, $this->{self::$mainKey});
        $this->db->update(self::$mainTable, $update);
        return TRUE;
    }

    //standard build function
    public function build($id, &$message = NULL) {
        $query = $this->db->get_where(self::$mainTable, array(self::$mainKey => $id));
        if($query->num_rows() == 0) {
            $message = 'Invalid ID';
            return FALSE;
        }
        $row = $query->row();
        
        if(self::$buildUser) {
            $this->createdUser = new User;
            $this->updatedUser = new User;

            $this->createdUser->build($row->created_by);
            $this->updatedUser->build($row->updated_by);
        }

        if(self::$buildProduct) {
            $this->product = new Product;
            $this->product->build($row->product_id, $message);
        }
         
        if(self::$buildBin) {
            $this->bin = new Bins;
            $this->bin->build($row->bin_id, $message);
        } 

        if(self::$buildProductList) {
            $this->productList = new ProductLists;
            $this->productList->build($row->product_list_id);
        }

        if(self::$buildWarehouse) {
            $this->warehouse = new Warehouse;
            $this->warehouse->build($row->warehouse_id);
        }
        if(self::$buildProductDim) {
            $this->productDim = new ProductDim;
            $this->productDim->build($row->product_dim_id);
        }
        
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
        $this->is_init = TRUE;
        return TRUE;
    }

    // Build from the Shipping items table
    public function build_shippingItem($bin_id, $product_id, &$message = NULL) {
        $this->db->where('bin_id', $bin_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('view_ShippingItemsLong');
        if($query->num_rows() > 1) {$message = 'Found two of the same product types in that bin, you must select one specifically'; return FALSE;}
        if($query->num_rows() == 0) {$message = 'Match not found'; return FALSE;}
        $row = $query->row();
        foreach($row as $key => $value)
            $this->{$key} = $value;
        if(! $this->build($row->item_id, $message)) return FALSE;
        else return TRUE;

    }

    // don't build sub objects
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

    static function get_product_items($product_id, &$message = NULL) {
        $CI =& get_instance();
        $CI->db->where('product_id', $product_id);
        $query = $CI->db->get('view_ShippingItemsLong');
        return $query->result();
    }
    

    /*public static function options($user_id = NULL, $list = NULL) {
        
        $options[0] = self::$select;
        
        // This model doesn't have a permission Class
        /*if($user_id !== NULL && $list === NULL) {
            if($var = call_user_func(array(self::$permissionClass, 'fetch_all_flat'), NULL, $user_id)){
                foreach($var as $permission)
                    if($permission->read) $list[] = $permission->{self::$mainKey};
            }
        }         
        if(! $items = self::fetch_all($list)) return $options;
        
        foreach($items as $key => $item) 
            $options[$key] = $item->name;
        
        return $options;   
    }
    */
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

    /*public static function get_default($user_id) {
        if($permissions = call_user_func(array(self::$permissionClass, 'fetch_all_flat'), NULL, $user_id))
            return reset($permissions)->{self::$mainKey};
        else return FALSE;
    }
    */

    //This is in case the object has dimension data to pull

    /*public static function fetch_dims($id = NULL) {
        $CI =& get_instance();
        if($id != NULL) 
            $CI->db->where(self::$dimKey, $id);        

        $query = $CI->db->get(self::$dimTable);
        foreach($query->result() as $row) {
            $return[$row->{self::$dimKey}] = $row;
        }

        return $return;
    }
    */

    //This is for the dimension options

    /*public static function dim_options($id = NULL) {
        $options[0] = "Select a Dimension";

        foreach(call_user_func(array(self::$class, 'fetch_dims'), $id) as $key => $dim) 
            $options[$key] = $dim->{self::$dimName};
        
        return $options;
    }
    */

    /*public static function is_unique($where, &$message = '') {
        $message = '';
        $CI =& get_instance();
        
        $CI->db->where(self::$uniqWith, $where[self::$uniqWith]);
        $CI->db->where(self::$uniqKey1, $where[self::$uniqKey1]);
        $query = $CI->db->get(self::$mainTable);
        if($query->num_rows() > 0) $message = self::$uniqLabel1.': '.$where[self::$uniqKey1].' already exists';
        $query->free_result();
        
        if(self::$uniqKey2){
            $CI->db->where(self::$uniqWith, $where[self::$uniqWith]);
            $CI->db->where(self::$uniqKey2, $where[self::$uniqKey2]);
            $query = $CI->db->get(self::$mainTable);
            if($query->num_rows() > 0) { 
                if($message != '') $message .= '<br>';
                $message .= self::$uniqLabel2.': '.$where[self::$uniqKey2].' already exists';
            }
            $query->free_result();
        }

        if($message === '') return TRUE;
        else return FALSE;
        
    }
    */

    public function update($insert, &$message = '') {
        $message = '';

        if(! $this->is_init) {
            $message = 'You must build the object first';
            return FALSE;
        }

        $this->db->where(self::$mainKey, $this->{self::$mainKey});
        if(isset($insert[self::$mainKey])) unset($insert[self::$mainKey]);
        $this->db->update(self::$mainTable, $insert);
        if($this->db->affected_rows() == 1) return TRUE;
        if($this->db->affected_rows() > 1) {
            $message = "DB Error, contact admin";
            return FALSE;
        }
        if($this->db->affected_rows() == 0) {
            $message = "Nothing changed";
            return FALSE;
        }

        $message = "Unkown error, nothing happened";
        return FALSE;
    }

}