<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wms extends CI_Model {

	function __construct() {
        parent::__construct();
    }

    static function fetch_table($table, $primaryKey, $headers = NULL, $where = NULL, $limit = NULL, $offset = NULL) {
    	$CI =& get_instance();
        if($where) $CI->db->where($where);
        $query = $CI->db->get($table, $limit, $offset);
        $count = 0;
        if(!$query->num_rows()) {
            if($headers) {
                foreach($headers as $header)
                    $return['NULL'][] = 'None';
            } else {
                $query = $CI->db->get($table, $limit, $offset);
                $row = $query->result_array();
                foreach($row as $key)
                    $return['NULL'][] = 'None';       
            }            
        }

        foreach ($query->result_array() as $row) {
            if($headers){
	            foreach($headers as $key => $field) 
	                $return[$row[$primaryKey]][$field] = $row[$key];
	            $return[0] = $headers;
	        } else {
	        	$return[$row[$primaryKey]] = $row;
        		if($count < 1){
        			foreach($row as $key => $value)
        				$return[0][$key] = $key;
        			$count++;
        		}
        	}		   
        }
        if(! isset($return[0]) && $headers) $return[0] = $headers;
        if(! isset($return[0]) && !$headers) $return[0][] = '&nbsp';
        return $return;
    }

    static function table_rows($table, $where = NULL) {
        $CI =& get_instance();
        $CI->db->where($where);
        $query = $CI->db->get($table);
        return $query->num_rows();
    }

    static function button($link, $title, $img = '', $class = '') {
    	return "<a class='button $class' href='$link'><img src='$img'>$title</a>";
    }

    static function log($message, $type = 1) {

    }

    static function fetch_options($table, $primaryKey, $field, $select = 'Select Option') {
        $CI =& get_instance();
        $CI->db->select($primaryKey);
        $CI->db->select($field);
        $query = $CI->db->get($table);
        $return[0] = $select;
        foreach($query->result() as $row)
            $return[$row->$primaryKey] = $row->$field;
        return $return;
    }
}

// End of file