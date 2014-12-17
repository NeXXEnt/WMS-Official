<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Region extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    static function fetch_region_info($region_id) {
    	$CI =& get_instance();
    	$query = $CI->db->get_where('Regions', array('region_id' => $region_id));
    	$row = $query->row;    	

    	return $row;
    }

    public function build_region($region_id) {
        $query = $this->db->get_where('Regions', array('region_id' => $region_id));
        $row = $query->row();
        foreach($row as $key => $value) {
            $this->{$key} = $value;
        }
    }

    static function fetch_all_regions() {
        $CI =& get_instance();
        $query = $CI->db->get('Regions');
        foreach($query->result() as $row) {            
            $return[$row->region_id] = new Region;
            $return[$row->region_id]->build_region($row->region_id); 
        }
        return $return;
    }


    static function region_options() {
        
        $regionOptions[0] = "Select a Region";
        
        foreach(Region::fetch_all_regions() as $key => $region) 
            $regionOptions[$key] = $region->regionName;
     
        return $regionOptions;   
    }

}