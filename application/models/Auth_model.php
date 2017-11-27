<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

        public $title;
        public $content;
        public $date;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                
        }
        /************************************/
        public function funcionPrueba($email) {        	
        	//$this->db->where('email_usuario',$email);
        	//$query = $this->db->get('usuarios');  
        	$query = $this->db->query("select * from usuarios");
        	        	
        	foreach ($query->result() as $row)
			{
			        //echo $row->NOMBRE_USUARIO;
			}

        	return $query->row();//result_array() ;
        	//return $query->getrows();
			//return $this->db->count_all('usuarios');
        	//return "<br>prueba de algo que no se que sea [" . $email ."]";
        }
        /**************************************************/
        public function getUser( $email = null){
        	if ($email) {
        		$this->db->where('EMAIL_USUARIO',$email);
        		$query = $this->db->get('usuarios');
        		return $query->row();
        	}else {
        		$query = $this->db->get('usuarios');
        		return $query->result();
        	}        	
        }
        /*************************************/        
        public function actualiza_contrasena($cId, $cPasswordActual, $cPasswordNuevo ){

            $this->db->set('CLAVE_USUARIO', $cPasswordNuevo);
            $this->db->where('ID_USUARIO', $cId);
            $this->db->where('CLAVE_USUARIO', $cPasswordActual);
            $this->db->update('USUARIOS'); 

            //$cCad = $this->db->get_compiled_select();
            //$data->datos_muestra = $this->db->query($cCad2)->result();

            //$cQuery = $this->db->query( "update usuarios set CLAVE_USUARIO ='" . $cPasswordNuevo . "' where ID_USUARIO = ". $cId);
            if ($this->db->affected_rows() > 0) {                
                return true;
            }else {
                //echo '<script type="text/javascript">alert("error")</script>';
                return false;
            }

            //$lSucess = $this->db->update('USUARIOS'); // gives UPDATE id             
            //return $lSucess;
        }
        /************************************/
        public function eliminaPermiso($cId){
            return $this->db->query('delete from permisos_x_usuario where id_permisos_x_usuario = '.$cId );
        }
        /********************************************/
        public function insertaPermiso($user,$modulo,$permiso) {
            $data = array(
                'id_usuario' => $user,
                'id_modulo' => $modulo,
                'id_permiso' => $permiso                
                );
            return $this->db->insert('permisos_x_usuario',$data);
        }
        /***************************************/
        public function  getCrudPermiso() { // regresa crud de permisos fecha usuario modulo permiso acciones
            $this->db->select( 'id_permisos_x_usuario, fecha_permisos_x_usuario, nombre_usuario, nombre_modulo, nombre_permiso');
            $this->db->from('permisos_x_usuario');
            $this->db->join('usuarios','permisos_x_usuario.ID_USUARIO = usuarios.ID_USUARIO');
            $this->db->join('modulos','permisos_x_usuario.ID_modulo = modulos.ID_modulo');
            $this->db->join('permisos','permisos_x_usuario.ID_permiso = permisos.ID_permiso');
            $this->db->order_by('fecha_permisos_x_usuario desc, nombre_usuario');
            $query = $this->db->get();
            if ($query) {
                return $query->result_array();
            }else { return false; }            
        }
        /***************************************/
        public function getUsersCombo() { // regresa  id y user para el combo
            return $this->db->query("select ID_USUARIO, NOMBRE_USUARIO from usuarios where status_usuario ='A' ")->result_array();
        }
        /****************************************/
        public function getModulosCombo(){ // regresa todos los modulos para el combo
            return $this->db->query("select ID_MODULOS, NOMBRE_MODULOS from modulos where status_modulos ='A' ")->result_array();
        }
        /****************************************/
        //$lSucess = $this->auth_model->inserttUser($nombre_usuario,$alias_usuario,$correo,$contrasena,$cargo,$tipo_usuario,$iniciales,$titulo,$fechanac);
        public function inserttUser($nombre_user,$alias_usuario,$correo,$contrasena,$cargo,$tipo_user,$iniciales,$titulo,$fechanac) {
        	$data = array(
       			'nombre_usuario' => $nombre_user,
       			'alias_usuario' => $alias_usuario,
       			'email_usuario' => $correo,
       			'clave_usuario' => sha1(md5($contrasena)),
                'cargo_usuario' => $cargo,
                'tipo_usuario'  => $tipo_user,
                'iniciales_usuario' => $iniciales,
                'titulo_usuario'	=> $titulo,
                'fecha_nacimiento_usuario' => $fechanac
    			);
        	return $this->db->insert('usuarios',$data);    		
        }
}
