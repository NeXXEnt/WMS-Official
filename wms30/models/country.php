<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    static function fetch_country_info($country_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Countries', array('country_id' => $country_id));
    	$row = $query->row;

    	return $row;
    }

	public function build_country($country_id) {
        $query = $this->db->get_where('Countries', array('country_id' => $country_id));
        $row = $query->row();
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    static function fetch_all_countries() {
        $CI =& get_instance();
        $query = $CI->db->get('Countries');
        foreach($query->result() as $row) {            
            $return[$row->country_id] = new Country;
            $return[$row->country_id]->build_country($row->country_id); 
        }
        return $return;
    }


    static function country_options() {
        
        $countryOptions[0] = "Select a Country";
        
        foreach(Country::fetch_all_countries() as $key => $country) 
            $countryOptions[$key] = $country->name;
     
        return $countryOptions;   
    }

}