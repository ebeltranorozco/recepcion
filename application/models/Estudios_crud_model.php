<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Estudios_crud_model extends CI_Model
{
 
	var $table = 'estudios';
 
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 
 
	public function get_all_estudios()
	{
	$this->db->from($this->table);
	$this->db->order_by('AREA_ESTUDIO,ALIAS_ESTUDIO');
	$query=$this->db->get();
	return $query->result();
}
 
 
	public function get_estudio_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('ID_ESTUDIO',$id);
		$query = $this->db->get();
 
		return $query->row();
	}
 
	public function estudio_add($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
 
	public function estudio_update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
 
	public function delete_estudio_by_id($id)
	{
		$this->db->where('ID_ESTUDIO', $id);
		$this->db->delete($this->table);
	}
 
 
}