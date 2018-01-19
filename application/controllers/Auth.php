<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('auth_model');
		//HOLA$this->load->model('grocery_CRUD_model');
		//$this->load->library('grocery_CRUD');
		/*if (!$_SESSION['user_id']){
				redirect('/login');
		}*/
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}


	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}


	public function listar_usuarios(){ // aqui espero poder utilizar la libreria...!

		//$this->grocery_CRUD->set_table('usuarios');

		try{
			$crud = new grocery_CRUD();
			$data = new stdClass();

			$data->title = 'Sistema de Recepcion de Muestra';
			$data->contenido = 'crud/crud'; //vista crud usuarios
			$data->panel_title = 'Listado de Usuarios';
			$data->menu_activo = 'catalogos';
			

			//$crud->set_theme('datatables');
			$crud->set_table('usuarios');


			$crud->set_theme('flexigrid');
			//$crud->set_theme('datatables');
			//$crud->set_subject('Office');
			//$crud->required_fields('city');
			//$crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $crud->render();
			$this->load->view('plantillas/head',$data);
			$this->load->view('plantillas/header',$data);
			//$this->load->view('example.php',$output);
			$this->load->view('crud/crud',$output);
			$this->load->view('plantillas/footer',$data);


			//$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

	}
	/********************************************************************************/
	public function login()
	{
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'auth/login'; //vista login
		$data->panel_title = 'Inicio de Sesion';
		$data->menu_activo = 'login';
		$this->load->view('frontend',$data);
		//$this->load->view('auth/login');
	}
	/****************************************************************************/
	public function signin(){
		//$this->load->library('form_validation');
		$this->form_validation->set_rules('correo', 'Email', 'required');
		//$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('contrasena', 'Password', 'required', array('required' => 'You must provide a %s.')  );    
        //$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $this->login();
        }
        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
        {
        		//$this->load->database();
                $correo = $this->input->post('correo');
                $contrasena = $this->input->post('contrasena');
                                
                $user = $this->auth_model->getUser($correo);

                if (!$user) {
                	$this->session->set_flashdata('mensaje_error','Datos de Usuario Incorrectos');
                	redirect(base_url().'login');
                }                

                if ($user->CLAVE_USUARIO!=sha1(MD5($contrasena)) ) {
                	$this->session->set_flashdata('mensaje_error','Contraseña Incorrecta');
                	redirect(base_url().'login?d='.$correo);
                }
                if ($user->TIPO_USUARIO==NULL) {
                	$this->session->set_flashdata('mensaje_error','Se Requiere que su cuenta sea Validada por un Administrador');
                	redirect(base_url().'login');
                }

                $_SESSION['user_id'] 		= $user->ID_USUARIO;
                $_SESSION['user_email'] 	= $user->EMAIL_USUARIO; // creo esta de mas
                $_SESSION['user_tipo'] 		= $user->TIPO_USUARIO;
                $_SESSION['user_nombre'] 	= $user->NOMBRE_USUARIO;
                $_SESSION['cargo_usuario'] 	= $user->CARGO_USUARIO;
                $_SESSION['iniciales_usuario'] = $user->INICIALES_USUARIO;
                $_SESSION['signatario_usuario'] = $user->SIGNATARIO_USUARIO;
                $_SESSION['is_logged_in'] 	= TRUE;
                $_SESSION['alias_usuario'] = $user->ALIAS_USUARIO; //2017-08-22 --> PARA EL MANEJO DE 1 USER EN CAPT DE AUREA
                //$_SESSION['ruta_firma_usuario'] = $user->ARCHIVO_FOTO_USUARIO ; //2018-01-18
                $this->session->set_flashdata('mensaje_sucess','Bienvenido ['.$user->NOMBRE_USUARIO.']');
                redirect(base_url());

                exit();
        }
	} //Fin de la funcion (metodo)

	public function logout(){
		//session_start();
		session_destroy();
		redirect(base_url());
	}
	/**************************************************/
	public function actualiza_contrasena() { // viene de la vista v_password_change
		$this->form_validation->set_rules('password_actual','Contraseña Actual', 'required');
		$this->form_validation->set_rules('password_nuevo','Contraseña Nueva','required');
		$this->form_validation->set_rules('password_nuevo2', 'Confirmacion de Contraseña', 'required|matches[password_nuevo]');		

/*		$this->form_validation->set_rules('contrasena', 'Contraseña', 'required', array('required' => 'You must provide a %s.')  );
        $this->form_validation->set_rules('contrasena2', 'Confirmacion de Contraseña', 'required|matches[contrasena]
*/		

		if ($this->form_validation->run() == FALSE)
        {
                $this->password_change();
                
        }
        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
        {            	
   			$cId = $_SESSION['user_id'] ;
			$cPasswordNuevo = sha1(md5($this->input->post('password_nuevo')));
			$cPasswordActual = sha1(md5($this->input->post('password_actual')));            
            
            $lSucess = $this->auth_model->actualiza_contrasena($cId, $cPasswordActual, $cPasswordNuevo ) ;

            if (!$lSucess) {
            	$this->session->set_flashdata('mensaje_error','Ocurrio un error al intentar grabar los datos a la base de datos');
            	//print_r($this->db->last_query());
            	//exit();
            	redirect(base_url().'password_change');
            }else{
	            $this->session->set_flashdata('mensaje_sucess','Contraseña Actualizada con Exito, ingrese con sus credenciales nuevas');
	            
	            //redirect(base_url());
	            //session_destroy();
	            //SIGO PELIANDO CON ESTO
	            //echo '<script type="text/javascript">alert("Ingrese con las Credenciales Nuevas")</script>';
	            //redirect(base_url('logout'));
	            $this->login();
        	}
        }		
	}
	/*************************************************/
	public function password_change(){ // formulario cambiar contraseña
		$data = new stdClass();

		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'auth/v_password_change'; 
		$data->panel_title = 'Password del Usuario';
		$data->menu_activo = 'catalogos';
		$this->load->view('frontend',$data);

	}
	/**************************************************/
	public function elimina_permiso() { // is ajax_reques
		if ($this->input->post('id_permisos_x_usuario')){
			$id		= $this->input->post('id_permisos_x_usuario');                        
            $lSucess = $this->auth_model->eliminaPermiso($id);
            return $lSucess;
        } else { return false; } // fin del if
	}
	/***************************************************/
	public function inserta_permiso(){ // is ajax request
		if ($this->input->post('id_usuario')){
			$idUsuario		= $this->input->post('id_usuario');
            $idModulo 		= $this->input->post('id_modulo');
            $idPermiso 	= $this->input->post('id_permiso');
                        
            $lSucess = $this->auth_model->insertaPermiso($idUsuario,$idModulo,$idPermiso);

            if (!$lSucess) {
            	$this->session->set_flashdata('mensaje_error','Ocurrio un error al intentar grabar los datos a la base de datos');
            	//redirect(base_url('permisos_usuario'));
            	//http://localhost/recepcion/public_html/permisos_usuario
            	return $this->db->_error_message();
            }else{
	            $this->session->set_flashdata('mensaje_sucess','Permiso agregado con exito a la Base de Datos');
	            //redirect(base_url());
        	}


		}else { return false; }

	}
	/*************************************************/
	public function permisos_usuario(){
		// es para visualizar la vista de los permisos de los usuarios
		//opcion del administrador unicamente
		$data = new stdClass();

		$this->load->helper('dropdown');
		//LinfoncitosCombo
		//listData($table,$name,$value,$orderBy='ASC') {
		//$dropdownItems = listData('usuarios','id_usuario', 'nombre_usuario');
        //$data->UserCombo = $dropdownItems;
		$data->UserCombo = listData('usuarios','id_usuario', 'nombre_usuario');
		$data->ModuloCombo = listData('modulos','id_modulo', 'nombre_modulo');
		$data->PermisoCombo = listData('permisos','id_permiso','nombre_permiso');
		$data->crud_permiso = $this->auth_model->getCrudPermiso();

		//$data->usuarios = $this->auth_model->getUsersCombo(); // regresa todos los usuarios para el combo
		//$data->modulos = $this->auth_model->getModulosCombo(); // regresa todos los modulos para el combo

		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'usuario/v_permisos'; //vista permisos
		$data->panel_title = 'Permisos de los Usuarios';
		$data->menu_activo = 'catalogos';
		$this->load->view('frontend',$data);
	}
	/******************************************************/

	public function registro(){
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'auth/registro'; //vista registro
		$data->panel_title = 'Registro al Sistema';
		$data->menu_activo = 'registro';
		$this->load->view('frontend',$data);
	}
	/************************************************/
	public function required_inputs($str){
		if ($this->input->post('TipoUsuario') != 'S') { return true;}else { 
			$this->form_validation->set_message('TipoUsuario', 'The {field} field can not be the word "test"');
			return false;}
		//if ($this->input->post('TipoUsuario') != 'Seleccione') { return true;} else { return false;}
	}
	/*****************************************************/
	public function alta_usuario(){
		
		$this->form_validation->set_rules('nombre_usuario','Nombre de Usuario','required');
		$this->form_validation->set_rules('alias_usuario','Alias del Usuario','required');
		$this->form_validation->set_rules('correo', 'Email', 'required|valid_email|is_unique[usuarios.email_usuario]');
        $this->form_validation->set_rules('contrasena', 'Contraseña', 'required', array('required' => 'You must provide a %s.')  );
        $this->form_validation->set_rules('contrasena2', 'Confirmacion de Contraseña', 'required|matches[contrasena]');
        $this->form_validation->set_rules('CargoUsuario', 'Cargo del Usuario', 'required');
        $this->form_validation->set_rules('TipoUsuario', 'Tipo del Usuario', 'required','callback_required_inputs');
        
        $this->form_validation->set_rules('InicialesUsuario', 'Iniciales del Usuario', 'required|max_length[5]');
        //$this->form_validation->set_rules('TituloUsuario', 'Titulo del Usuario', 'required');
        $this->form_validation->set_rules('FechaNacUsuario', 'Fecha de Nacimiento en el Formato AAAA-MM-DD', 'required');
        
        $this->form_validation->set_message('required', 'El campo %s es obligatorio');
		$this->form_validation->set_message('integer', 'El campo %s deve poseer solo numeros enteros');
		$this->form_validation->set_message('is_unique', 'El campo %s ya esta registrado');
		$this->form_validation->set_message('required', 'El campo %s es obligatorio');
		$this->form_validation->set_message('max_length', 'El Campo %s debe tener un Maximo de %d Caracteres');

        
        //callback_required_inputs

        if ($this->form_validation->run() == FALSE)
        {
                $this->registro();
        }
        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
        {        		
            $nombre_usuario		= $this->input->post('nombre_usuario');
            $alias_usuario 		= $this->input->post('alias_usuario');
            $correo 			= $this->input->post('correo');
            $contrasena 		= $this->input->post('contrasena');
            $cargo 				= $this->input->post('CargoUsuario');
            $tipo_usuario 		= $this->input->post('TipoUsuario');
            
            $iniciales			= $this->input->post('InicialesUsuario');
            $titulo				= $this->input->post('TituloUsuario');
            $fechanac			= $this->input->post('FechaNacUsuario');
            
            
            
            $lSucess = $this->auth_model->inserttUser($nombre_usuario,$alias_usuario,$correo,$contrasena,$cargo,$tipo_usuario,$iniciales,$titulo,$fechanac);

            if (!$lSucess) {
            	$this->session->set_flashdata('mensaje_error','Ocurrio un error al intentar grabar los datos a la base de datos');
            	redirect(base_url().'registro');
            }else{
	            $this->session->set_flashdata('mensaje_sucess','En breve recibira una confirmacion por correo para que valide su contraseña</br>no es necesario que se vuelva a registrar');
	            redirect(base_url());
        	}
        }
	} //Fin de la funcion (alta_usuario)
	/******************************************************************************************/
	public function get_nombre_y_cargo_by_id_usuario(){ // is ajax 2017-11-07		
		$cIdUserSignatario = $_GET['id_user_signatario'];
		$this->db->select('*');
		$this->db->from('usuarios');		
		$this->db->where('ID_USUARIO', $cIdUserSignatario );
		$cRet = $this->db->get()->result();		
		if ($this->db->affected_rows()==0){ $cRet = false; }
				
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $cRet;
		
		header('Content-type: application/json; charset=utf-8');
		$cRet = json_encode($RespData);
		echo $cRet;
	} // fin de la funcion
	
	
}



