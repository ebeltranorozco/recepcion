<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Clientes_crud extends CI_Controller {
 
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
	 		$this->load->model('clientes_crud_model');
	 		if (!$_SESSION['user_id']){
				redirect('/login');
			}
	 	}
 
 
	public function index()
	{
 
		//$data['clientes']=$this->clientes_crud_model->get_all_clientes();
		//$this->load->view('crud/v_clientes_crud',$data);
		$data = new stdClass();		
		//$data['clientes']=$this->clientes_crud_model->get_all_clientes();
		$data->clientes=$this->clientes_crud_model->get_all_clientes();


		$data->title = 'Sistema de Recepcion de Muestra';
	 	$data->contenido = 'crud/v_clientes_crud';
	 	$data->panel_title = 'Listado de Clientes';
	 	$data->menu_activo = 'catalogos';
	 	//$data->cargar_crud = true; 	

	 	//$this->load->view('plantillas/head',$data);	 	
	 	$this->load->view('frontend',$data);		
		//$this->load->view('plantillas/mymodal2',$data);
		//$this->load->view('plantillas/footer',$data);
		//$this->load->view('crud/v_clientes_crud',$data);	

	}
	public function clientes__crud(){ // es el que se llama desde el menu
		$data = new stdClass();
		
		//$data['clientes']=$this->clientes_crud_model->get_all_clientes();
		$data->clientes=$this->clientes_crud_model->get_all_clientes();


		$data->title = 'Sistema de Recepcion de Muestra';
	 	$data->contenido = 'crud/v_clientes_crud';
	 	$data->panel_title = 'Listado de Clientes';
	 	$data->menu_activo = 'catalogos';
	 	//$data->cargar_crud = true; 	

	 	//$this->load->view('plantillas/head',$data);	 	
	 	$this->load->view('frontend',$data);		
		//$this->load->view('plantillas/mymodal2',$data);
		//$this->load->view('plantillas/footer',$data);
		//$this->load->view('crud/v_clientes_crud',$data);	
	}

	public function cliente_add()
		{ // es ajax requeri
			
			// HAY Q VALIDARLOS
			
			$this->form_validation->set_rules('NOMBRE_CLIENTE','Nombre','trim|required');
			$this->form_validation->set_rules('EMAIL_CLIENTE','Correo','trim|required|valid_email','callback_email_check');	
			/*
			$this->form_validation->set_rules('DOMICILIO_CLIENTE','Domicilio','trim|required');
			$this->form_validation->set_rules('RFC_CLIENTE','RFC','trim|required');
			$this->form_validation->set_rules('CIUDAD_CLIENTE','Ciudad','trim|required');
			$this->form_validation->set_rules('ESTADO_CLIENTE','Estado','trim|required');
			
			$this->form_validation->set_rules('CONTACTO_CLIENTE','Contacto','trim|required');	
			$this->form_validation->set_rules('TELEFONO_CLIENTE','Telefono','trim|required');	
			
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('integer', 'El campo %s deve poseer solo numeros enteros');
			$this->form_validation->set_message('is_unique', 'El campo %s ya esta registrado');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('max_length', 'El Campo %s debe tener un Maximo de %d Caracteres');
			*/
	        if ($this->form_validation->run() == FALSE)
	        {	
	        	echo "Reglas de Validación de los campos termino en Nulo\nLleene Todos los campos con información correcta ";
	            //$this->alta_cliente();
	            //$this->index();
	        	//$this->session->set_flashdata('mensaje_error','Email no Valido');
	        }
	        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
	        {

				$data = array(
						'ID_CLIENTE' => $this->input->post('ID_CLIENTE'),
						'NOMBRE_CLIENTE' => $this->input->post('NOMBRE_CLIENTE'),
						'DOMICILIO_CLIENTE' => $this->input->post('DOMICILIO_CLIENTE'),
						'RFC_CLIENTE' => $this->input->post('RFC_CLIENTE'),
						'CIUDAD_CLIENTE' => $this->input->post('CIUDAD_CLIENTE'),
						'ESTADO_CLIENTE' => $this->input->post('ESTADO_CLIENTE'),
						'TELEFONO_CLIENTE' => $this->input->post('TELEFONO_CLIENTE'),
						'CONTACTO_CLIENTE' => $this->input->post('CONTACTO_CLIENTE'),
						'EMAIL_CLIENTE' => $this->input->post('EMAIL_CLIENTE'),
						'EMAIL_ALTERNO_CLIENTE' => $this->input->post('EMAIL_ALTERNO_CLIENTE')
					);
				$insert = $this->clientes_crud_model->cliente_add($data);
				echo json_encode(array("status" => TRUE));
			} // FIN DEL IF
		}
		/*************************************************************/
		public function ajax_edit($id)
		{
			$data = $this->clientes_crud_model->get_cliente_by_id($id); 
			echo json_encode($data);
		}
 		/***************************************************************/
		public function cliente_update()
	{
		$data = array(
				'ID_CLIENTE'		 	=> $this->input->post('ID_CLIENTE'),
				'NOMBRE_CLIENTE'	 	=> $this->input->post('NOMBRE_CLIENTE'),
				'DOMICILIO_CLIENTE' 	=> $this->input->post('DOMICILIO_CLIENTE'),
				'RFC_CLIENTE' 			=> $this->input->post('RFC_CLIENTE'),
				'CIUDAD_CLIENTE' 		=> $this->input->post('CIUDAD_CLIENTE'),
				'ESTADO_CLIENTE' 		=> $this->input->post('ESTADO_CLIENTE'),
				'TELEFONO_CLIENTE' 		=> $this->input->post('TELEFONO_CLIENTE'),
				'CONTACTO_CLIENTE'		=> $this->input->post('CONTACTO_CLIENTE'),
				'EMAIL_CLIENTE'			=> $this->input->post('EMAIL_CLIENTE'),
				'EMAIL_ALTERNO_CLIENTE' => $this->input->post('EMAIL_ALTERNO_CLIENTE')
				);
		$this->clientes_crud_model->cliente_update(array('ID_CLIENTE' => $this->input->post('ID_CLIENTE')), $data);
		echo json_encode(array("status" => TRUE));
	}
 
	public function cliente_delete($id)
	{
		$this->clientes_crud_model->delete_cliente_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
 
 
 
}