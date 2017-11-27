<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
  function listData($table,$name,$value,$orderBy=null, $where_nombre_campo=null, $where_variable=null) {
      $items = array();
      $CI =& get_instance();
      if($orderBy) {
          $CI->db->order_by($value,$orderBy);
      }
     
	  	if ($where_nombre_campo && $where_variable){
          $CI->db->where($where_nombre_campo,$where_variable);
      	}else {
			if (is_null($where_variable && $where_nombre_campo)) {
				$CI->db->where($where_nombre_campo);
			}			
		}
	 
            
      
      $query = $CI->db->select("$name,$value")->from($table)->get();
      if ($query->num_rows() > 0) {
          foreach($query->result() as $data) {
              $items[$data->$name] = $data->$value;
          }
          $query->free_result();
          return $items;
      }
  }
 
/* End of file dropdwon_helper.php */
/* Location: ./application/helper/dropdown_helper.php */
/* - See more at: https://arjunphp.com/codeigniter-helper-quick-dynamic-dropdown-select-box/#sthash.esJcgsMD.dpuf */