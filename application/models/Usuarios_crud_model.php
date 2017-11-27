<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Usuarios_crud_model extends CI_Model
{
 
	var $table = 'usuarios';
 
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 
 
	public function get_all_usuarios()
	{
	$this->db->from($this->table);
	$query=$this->db->get();
	return $query->result();
}
 
 
	public function get_usuario_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('ID_USUARIO',$id);
		$query = $this->db->get();
 
		return $query->row();
	}
 
	public function usuario_add($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
 
	public function usuario_update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
 
	public function delete_usuario_by_id($id)
	{
		$this->db->where('ID_USUARIO', $id);
		$this->db->delete($this->table);
	}
 
 
}