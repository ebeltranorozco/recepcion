<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seguridad_model extends CI_Model {

	public function __construct() {
               // Call the CI_Model constructor
        parent::__construct();
    }
    

    public function getPermiso(  $cId, $cMod, $cPer ) {
    	$_lRet = false;
    	$cIdModulo = $this->db->query("select id_modulo from modulos where nombre_modulo = '" .$cMod."'")->result();
		
		if ($this->db->affected_rows()>0 ) {			
		
	    	$cIdModulo = $cIdModulo[0]->id_modulo;

	        $cIdPermiso = $this->db->query("select id_permiso from permisos where nombre_permiso = '" .$cPer."'")->result();
	        $cIdPermiso = $cIdPermiso[0]->id_permiso;
	        $this->db->select('*');
	        //$this->db->from('permisos_x_usuario');
	        $this->db->where( 'id_usuario' ,$cId);
	        $this->db->where( 'id_modulo' ,$cIdModulo);
	        $this->db->where( 'id_permiso' ,$cIdPermiso);
	        $this->db->get('permisos_x_usuario');
	        if ($this->db->affected_rows()>0 ) {
	            $_lRet = true;
	         } 
		}
		return $_lRet;	
     }
}