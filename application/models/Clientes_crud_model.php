<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Clientes_crud_model extends CI_Model
{
 
	var $table = 'clientes';
 
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 
 
	public function get_all_clientes()
	{
	$this->db->from($this->table);
	$query=$this->db->get();
	return $query->result();
	}
 
 
	public function get_cliente_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('ID_CLIENTE',$id);
		$query = $this->db->get();
 
		return $query->row();
	}
 
	public function cliente_add($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
 
	public function cliente_update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
 
	public function delete_cliente_by_id($id)
	{
		$this->db->where('ID_CLIENTE', $id);
		$this->db->delete($this->table);
	}
 
 
}