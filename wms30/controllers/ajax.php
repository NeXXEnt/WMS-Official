<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Ajax extends CI_Controller  {
		
		private $database = NULL;
		private $_query = NULL;
		private $_fields = array();
		public  $_index = NULL;		
		
		public function __construct(){
			parent::__construct();
			
		}
		
		
		public function process_data(){
			$this->_index = ($this->input->post('index'))?$this->input->post('index'):NULL;
			$id = ($this->input->post('id'))?$this->input->post('id'):NULL;
			switch($this->_index){
				case 'country':
					$this->_query = "SELECT * FROM Countries";
					$this->_fields = array('country_id','name');
					break;
				case 'province':
					$this->_query = "SELECT * FROM Provinces WHERE country_id=$id";
					$this->_fields = array('province_id','name');
					break;
				case 'city':
					$this->_query = "SELECT * FROM Cities WHERE province_id=$id";
					$this->_fields = array('city_id','name');
					break;
				default:
					break;
			}
			$this->show_result();
		}
		
		public function show_result(){
			$return = '<option value="0">Select '.ucfirst($this->_index).'</option>';
			$query = $this->db->query($this->_query);
			foreach($query->result_array() as $result){
				$entity_id = $result[$this->_fields[0]];
				$enity_name = $result[$this->_fields[1]];
				$return = $return."<option value='$entity_id'>$enity_name</option>";
			}
			echo $return;
		}
	




	}
	

