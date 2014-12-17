<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Province extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    static function fetch_province_info($province_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Provinces', array('province_id' => $province_id));
    	$row = $query->row;
    	
    	

    	return $row;
    }

    public function build_province($province_id) {
        $query = $this->db->get_where('Provinces', array('province_id' => $province_id));
        $row = $query->row();
        $this->country = new Country;
        $this->country->build_country($row->country_id);
        
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    static function fetch_all_provinces() {
        $CI =& get_instance();
        $query = $CI->db->get('Provinces');
        foreach($query->result() as $row) {            
            $return[$row->province_id] = new Province;
            $return[$row->province_id]->build_province($row->province_id); 
        }
        return $return;
    }


    static function province_options() {
        
        $provinceOptions[0] = "Select a Province";
        
        foreach(Province::fetch_all_provinces() as $key => $province) 
            $provinceOptions[$key] = $province->name;
     
        return $provinceOptions;   
    }
}