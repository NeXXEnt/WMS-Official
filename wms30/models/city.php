<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    static function fetch_all_cities() {
        $CI =& get_instance();
        $query = $CI->db->get('Cities');
        foreach($query->result() as $row) {            
            $return[$row->city_id] = new City;
            $return[$row->city_id]->build_city($row->city_id); 
        }
        return $return;

    }
    static function fetch_city_info($city_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Cities', array('city_id' => $city_id));
    	$row = $query->row;


    	return $row;
    }
    static function fetch_all_city_info($headers) {
        $CI =& get_instance();
        $query = $CI->db->get('Cities');
        foreach ($query->result_array() as $city) {
            foreach($headers as $key => $field) {
                $return[$city['city_id']][$field] = $city[$key];                
            }
        }
        return $return;
    }

    public function build_city($city_id) {
        $query = $this->db->get_where('Cities', array('city_id' => $city_id));
        $row = $query->row();
        $this->province = new Province;
        $this->province->build_province($row->province_id);
        $this->region = new Region;
        $this->region->build_region($row->region_id);
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    static function city_options() {
        $cityOptions[0] = "Select a City";
        
        foreach(City::fetch_all_cities() as $city_id => $city) 
            $cityOptions[$city_id] = $city->name;
        
        return $cityOptions;
    }
}