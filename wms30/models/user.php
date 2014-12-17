<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

    public $user_id = NULL;
    public $city_id = NULL;
    public $warehouse_id = NULL;

    public $city = NULL;
    public $warehouse = NULL;


    public $firstName = NULL;
    public $lastName = NULL;    
    public $username = NULL;
    
    public $permissions = array();
    
    public $isAdmin = FALSE;
    public $init = FALSE;

    private $password = NULL;
    public $hashedPassword = NULL;

    
    function __construct() {
        parent::__construct();
    }

    public function init($username) {
        $this->username = strtolower($username);
        $query = $this->db->get_where('Users', array('userName' => $this->username));
        
        if($query->num_rows() > 1)
            show_error('More than one Username: '.$this->username);
        if($query->num_rows() < 1) return FALSE;
        $row = $query->row();
        if(! $row->accountEnabled) return FALSE;
        $this->build_user($row->user_id);
        
        $this->init = TRUE;
        return TRUE;
    }

    public function get_basket($warehouse_id) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->where('binIsAUserBasket', TRUE);
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get('Bins');
        if($query->num_rows() > 1) show_error($this->username.' has more than one basket in this warehouse, cantact admin');
        $row = $query->row();
        return $row->bin_id;
    }

    public function build($user_id, &$message = NULL) {
        return $this->build_user($user_id, $message);
    }

    public function build_user($user_id, &$message = NULL) {
        // Fetch User row from Database
        $query = $this->db->get_where('Users', array('user_id' => $user_id));
        
        if($query->num_rows() > 1)
            show_error("Database Error: user_id = $user_id, multiple entries found, contact Admin");
        if($query->num_rows() < 1) return FALSE;
        $row = $query->row();
        
        // Populate all Data into object
        foreach($row as $key => $value) 
            $this->{$key} = $value;
        
        // Build User related Objects
        $this->city         = new City;
        $this->warehouse    = new Warehouse;

        $this->city->build_city($this->city_id);                        
        $this->warehouse->build_warehouse($this->warehouse_id);
        
        // Set specific object variables
        $this->username         = ucfirst($this->userName);
        $this->isAdmin          = $this->admin;
        $this->hashedPassword   = $this->userPassword;
        
        // Run build functions
        $this->build_permissions();
        return true;
    }

    public function build_permissions() {
        
        // Module Permissions
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get('ModulePermissions');
        
        foreach ($query->result() as $row) {
            $module         = new Module;
            $permission     = new ModulePermissions;

            $module->build_flat_module($row->module_id);            
            $permission->build_flat_module_permission($row->module_permission_id);

            $this->permissions['modulePermissions'][$module->moduleKey] = $permission;
        }

        // Warehouse Permissions
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get('WarehousePermissions');
        
        foreach ($query->result() as $row) {
            $warehouse      = new Warehouse;
            $permission     = new WarehousePermission;

            $warehouse->build_flat_warehouse($row->warehouse_id);            
            $permission->build_flat_warehouse_permission($row->warehouse_permission_id);

            $this->permissions['warehousePermissions'][$warehouse->warehouse_id] = $permission;
        }

        // Product List Permissions
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get('ProductListsPermissions');

        foreach ($query->result() as $row) {
            $productList      = new ProductLists;
            $permission       = new ProductListPermissions;

            $productList->build($row->product_list_id);            
            $permission->build_flat($row->product_list_permission_id);

            $this->permissions['productListPermissions'][$productList->product_list_id] = $permission;
        }


        return TRUE;

    }

    static function user_options() {
        $userOptions[0] = "Select a User";
        
        foreach(User::fetch_all_users() as $key => $user) 
            $userOptions[$key] = $user->userName;
     
        return $userOptions;
    }

    static function fetch_all_users() {
        $CI =& get_instance();
        $query = $CI->db->get('Users');
        foreach($query->result() as $row) {            
            $return[$row->user_id] = new User;
            $return[$row->user_id]->build_user($row->user_id); 
        }
        return $return;
    }

    static function fetch_user_info($user_id) {
        $CI =& get_instance();
        $query = $CI->db->get_where('Users', array('user_id' => $user_id));
        $row = $query->row();
               
        return $row;
    }

    static function fetch_all_user_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('Users');
        foreach ($query->result_array() as $user) {
            foreach($headers as $key => $field) {
                $return[$user['user_id']][$field] = $user[$key];                
            }
        }
        return $return;
    }

    

    public function generate_password_hash() {
        $nonce = '';
        for($i = 0; $i<16; $i++)    // generate a random 16 character nonce with the Mersenne Twister
            $nonce .= substr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./', mt_rand(0, 63), 1); 

        $this->hashedPassword = substr(crypt($this->password,'$5$rounds=32678$'.$nonce),16);
    }
    static function return_password_hash($password) {
        $nonce = '';
        for($i = 0; $i<16; $i++)    // generate a random 16 character nonce with the Mersenne Twister
            $nonce .= substr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./', mt_rand(0, 63), 1);

        return substr(crypt($password,'$5$rounds=32678$'.$nonce),16);

    }
    public function change_password($password) {
        $this->password = $password;
        $this->generate_password_hash();
        $data = array('userPassword' => $this->hashedPassword);
        
        $this->db->where('user_id', $this->user_id);        
        $this->db->update('Users', $data);
        return TRUE;
    }

    
    public function authenticate($username, $password) {
        if(! $this->init($username)) return FALSE;
        
        $this->password = $password;
        
        if(crypt($this->password,'$5$rounds=32678$'.$this->hashedPassword) == '$5$rounds=32678$'.$this->hashedPassword) 
            return TRUE;
        else            
            return FALSE;
    }


    public function generate_menu(){
        
        if($this->isAdmin)
            $return[] = '<li><a class="main-menu button" href="/admin">Admin</a></li>';
        
        $query = $this->db->get('Modules');
        $this->build_permissions();

        foreach ($query->result() as $module) {
            if(isset($this->permissions['modulePermissions'][$module->moduleKey])) {
                if($this->permissions['modulePermissions'][$module->moduleKey]->read)
                    $return[] = '<li><a class="main-menu button" href="/'.$module->moduleKey.'">'.$module->moduleName.'</a></li>';  
            }
        }

        $return[] = '<li><a class="main-menu button negative" id="logout" href="/auth/logout">Logout</a></li>';
        $return[] = '<li><a class="main-menu button" id="settings" href="/settings">Settings</a></li>';
        $return[] = '<li><a class="main-menu button" id="profile" href="/profile/'.$this->user->user_id.'">Profile</a></li>';
        return $return;
    }

}
