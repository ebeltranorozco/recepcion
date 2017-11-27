<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {    

  var $table = 'books';    

    public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                $this->load->database();
        }
    /******************************************************************/



    /*********************************************************************/
    public function getDetalleEstudio( $id = null){
		if ($id) { $this->db->where('id_recepcion',$id) ; }
		return $this->db->get('detalle_muestras')->result();
	}
	/****************************************************************/
    public function email_unique( $cEmail) {
		$this->db->where('email_cliente', $cEmail );
		return $this->db->get('clientes')->result();
	}
	/****************************************************************/    
    public function addCliente($cNombre, $cDomicilio, $cRfc, $cCiudad, $cEstado, $cEmail, $cContacto,$cTelefono	) {
    	$data = array(
			 'nombre_cliente' 		=> $cNombre,
			 'domicilio_cliente'	=> $cDomicilio,
			 'rfc_cliente'			=> $cRfc,
			 'ciudad_cliente' 		=> $cCiudad,
			 'estado_cliente' 		=> $cEstado,
			 'email_cliente' 		=> $cEmail,
			 'contacto_cliente'		=> $cContacto,	
			 'telefono_cliente'		=> $cTelefono,		 
			 );			 
			 return $this->db->insert('clientes',$data);
    }



    public function getCliente( $name = null ) {
       	if ($name) { //buscamos por ese cliente
			   $this->db->like('NOMBRE_CLIENTE',$name);
        	//$query = $this->db->get('clientes');
        	//return  $query->row()  ;
       	}else { // obtener todos los clientes
       		//$this->db->get('clientes');       		
       	}

       	return  json_encode($this->db->get('clientes')->result());
    }

    
}
