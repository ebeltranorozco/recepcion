<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Usuarios_crud extends CI_Controller {
 
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
 
	 public function __construct()
	 	{
	 		parent::__construct();
			$this->load->helper('url');
	 		$this->load->model('usuarios_crud_model');
	 		if (!$_SESSION['user_id']){
				redirect('/login');
			}
	 	}
 
 
	public function index()
	{
 
		//$data['usuarios']=$this->usuarios_crud_model->get_all_usuarios();
		//$this->load->view('crud/v_usuarios_crud',$data);
		$data = new stdClass();		
		//$data['usuarios']=$this->usuarios_crud_model->get_all_usuarios();
		$data->usuarios=$this->usuarios_crud_model->get_all_usuarios();


		$data->title = 'Sistema de Recepcion de Muestra';
	 	$data->contenido = 'crud/v_usuarios_crud';
	 	$data->panel_title = 'Listado de usuarios';
	 	$data->menu_activo = 'catalogos';
	 	//$data->cargar_crud = true; 	

	 	//$this->load->view('plantillas/head',$data);	 	
	 	$this->load->view('frontend',$data);		
		//$this->load->view('plantillas/mymodal2',$data);
		//$this->load->view('plantillas/footer',$data);
		//$this->load->view('crud/v_usuarios_crud',$data);	

	}	

	public function usuario_add()
		{ // es ajax requeri
			
			// HAY Q VALIDARLOS
			//$this->form_validation->set_rules('ID_USUARIO','Id','trim|required');
			$this->form_validation->set_rules('NOMBRE_USUARIO','Nombre','trim|required');
			$this->form_validation->set_rules('CARGO_USUARIO','Domicilio','trim|required');
			$this->form_validation->set_rules('CLAVE_USUARIO','RFC','trim|required');
			$this->form_validation->set_rules('EMAIL_USUARIO','Correo','trim|required|valid_email');	
			$this->form_validation->set_rules('TIPO_USUARIO','Telefono','trim|required');	
			$this->form_validation->set_rules('STATUS_USUARIO','Contacto','trim|required');	
			
			$this->form_validation->set_rules('INICIALES_USUARIO','Contacto','trim|required');	
			$this->form_validation->set_rules('SIGNATARIO_USUARIO','Contacto','trim|required');	
			$this->form_validation->set_rules('TITULO_USUARIO','Contacto','trim|required');	
			
			
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('integer', 'El campo %s deve poseer solo numeros enteros');
			$this->form_validation->set_message('is_unique', 'El campo %s ya esta registrado');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('max_length', 'El Campo %s debe tener un Maximo de %d Caracteres');


	        if ($this->form_validation->run() == FALSE)
	        {	
	        	echo "Reglas de Validación de los campos termino en Nulo\nLa mayoria de los campos deben contener  información correcta ";
	            //$this->alta_usuario();

	            //$this->index();
	        	//$this->session->set_flashdata('mensaje_error','Email no Valido');
	        }
	        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
	        {
				//'ID_USUARIO' => $this->input->post('ID_USUARIO'),
				$data = array(						
						'NOMBRE_USUARIO' => $this->input->post('NOMBRE_USUARIO'),
						'CARGO_USUARIO' => $this->input->post('CARGO_USUARIO'),
						'ALIAS_USUARIO' => $this->input->post('ALIAS_USUARIO'),
						'CLAVE_USUARIO' => sha1(md5($this->input->post('CLAVE_USUARIO'))),
						'EMAIL_USUARIO' => $this->input->post('EMAIL_USUARIO'),
						'TIPO_USUARIO' => $this->input->post('TIPO_USUARIO'),						
						'STATUS_USUARIO' => $this->input->post('STATUS_USUARIO'),
						'SIGNATARIO_USUARIO' => $this->input->post('SIGNATARIO_USUARIO'),
						'INICIALES_USUARIO' => $this->input->post('INICIALES_USUARIO'),
						'FECHA_NACIMIENTO_USUARIO' => $this->input->post('FECHA_NACIMIENTO_USUARIO'),
						'TITULO_USUARIO'			=> $this->input->post('TITULO_USUARIO')
					);
				$insert = $this->usuarios_crud_model->usuario_add($data);
				echo json_encode(array("status" => TRUE));
			} // FIN DEL IF
		}
		/*************************************************************/
		public function ajax_edit($id)
		{
			$data = $this->usuarios_crud_model->get_usuario_by_id($id); 
			echo json_encode($data);
		}
 		/***************************************************************/
		public function usuario_update()
	{
		$cPass = $this->input->post('CLAVE_USUARIO');
		if (strlen($cPass)<40){
				$cPass = sha1(md5($cPass));
		}
		$data = array(
				'ID_USUARIO' => $this->input->post('ID_USUARIO'),
				'NOMBRE_USUARIO' => $this->input->post('NOMBRE_USUARIO'),
				'CARGO_USUARIO' => $this->input->post('CARGO_USUARIO'),
				'ALIAS_USUARIO' => $this->input->post('ALIAS_USUARIO'),
				'CLAVE_USUARIO' => $cPass,
				'EMAIL_USUARIO' => $this->input->post('EMAIL_USUARIO'),
				'TIPO_USUARIO' => $this->input->post('TIPO_USUARIO'),						
				'STATUS_USUARIO' => $this->input->post('STATUS_USUARIO'),
				'SIGNATARIO_USUARIO' => $this->input->post('SIGNATARIO_USUARIO'),
				'INICIALES_USUARIO' => $this->input->post('INICIALES_USUARIO'),
				'TITULO_USUARIO' => $this->input->post('TITULO_USUARIO'),
				'FECHA_NACIMIENTO_USUARIO' => $this->input->post('FECHA_NACIMIENTO_USUARIO')
			);
		$this->usuarios_crud_model->usuario_update(array('ID_USUARIO' => $this->input->post('ID_USUARIO')), $data);
		echo json_encode(array("status" => TRUE));
	}
 
	public function usuario_delete($id)
	{
		$this->usuarios_crud_model->delete_usuario_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
 
 
 
}