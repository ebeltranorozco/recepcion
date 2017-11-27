<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Estudios_crud extends CI_Controller {
 
	public function __construct()	 	{
	 		parent::__construct();
			$this->load->helper('url');
	 		$this->load->model('estudios_crud_model');
	 		if (!$_SESSION['user_id']){
				redirect('/login');
			}
	 	}
 		/*************************************************************/ 
	public function index()	{
 
		//$data['estudios']=$this->estudios_crud_model->get_all_estudios();
		//$this->load->view('crud/v_estudios_crud',$data);
		$data = new stdClass();		
		//$data['estudios']=$this->estudios_crud_model->get_all_estudios();
		$data->estudios=$this->estudios_crud_model->get_all_estudios();


		$data->title = 'Sistema de Recepcion de Muestra';
	 	$data->contenido = 'crud/v_estudios_crud';
	 	$data->panel_title = 'Listado de estudios';
	 	$data->menu_activo = 'catalogos';
	 	//$data->cargar_crud = true; 	

	 	//$this->load->view('plantillas/head',$data);	 	
	 	$this->load->view('frontend',$data);		
		//$this->load->view('plantillas/mymodal2',$data);
		//$this->load->view('plantillas/footer',$data);
		//$this->load->view('crud/v_estudios_crud',$data);	

	}	
		/*************************************************************/
	public function estudio_add()		{ // es ajax requeri
			
			// HAY Q VALIDARLOS
			$this->form_validation->set_rules('ANALISIS_SOLICITADO','Analisis','trim|required');
			$this->form_validation->set_rules('METODOLOGIA_ESTUDIO','Metodologia','trim|required');
			$this->form_validation->set_rules('AREA_ESTUDIO','Área','trim|required');
			$this->form_validation->set_rules('ALIAS_ESTUDIO','Alias','trim|required');
			$this->form_validation->set_rules('DURACION_MIN_ESTUDIO','Duracion Minima','trim|required');
			$this->form_validation->set_rules('DURACION_MAX_ESTUDIO','Duracion Maxima','trim|required');	
			$this->form_validation->set_rules('TOPE_ESTUDIO','Tope','trim|required');	
			$this->form_validation->set_rules('PRECIO_ESTUDIO','Precio','trim|required');	
			
			
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('integer', 'El campo %s deve poseer solo numeros enteros');
			$this->form_validation->set_message('is_unique', 'El campo %s ya esta registrado');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('max_length', 'El Campo %s debe tener un Maximo de %d Caracteres');

	        if ($this->form_validation->run() == FALSE)
	        {	
	        	echo "Reglas de Validación de los campos termino en Nulo\nLa mayoria de los campos deben contener  información correcta ";
	            //$this->alta_estudio();
	            //$this->index();
	        	//$this->session->set_flashdata('mensaje_error','Email no Valido');
	        }
	        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
	        {

				$data = array(
						'ID_ESTUDIO' => $this->input->post('ID_ESTUDIO'),
						'ANALISIS_SOLICITADO' => $this->input->post('ANALISIS_SOLICITADO'),
						'METODOLOGIA_ESTUDIO' => $this->input->post('METODOLOGIA_ESTUDIO'),
						'AREA_ESTUDIO' => $this->input->post('AREA_ESTUDIO'),
						'ALIAS_ESTUDIO' => $this->input->post('ALIAS_ESTUDIO'),
						'DURACION_MIN_ESTUDIO' => $this->input->post('DURACION_MIN_ESTUDIO'),
						'DURACION_MAX_ESTUDIO' => $this->input->post('DURACION_MAX_ESTUDIO'),
						'TOPE_ESTUDIO' => $this->input->post('TOPE_ESTUDIO'),						
						'PRECIO_ESTUDIO' => $this->input->post('PRECIO_ESTUDIO'),
						'VALIDADO_ESTUDIO' => $this->input->post('VALIDADO_ESTUDIO'),
						'ACREDITADO_ESTUDIO' => $this->input->post('ACREDITADO_ESTUDIO'),
						'RECONOCIDO_ESTUDIO' => $this->input->post('RECONOCIDO_ESTUDIO'),
						'REFERENCIA_ESTUDIO' => $this->input->post('REFERENCIA_ESTUDIO')
					);
				$insert = $this->estudios_crud_model->estudio_add($data);
				echo json_encode(array("status" => TRUE));
			} // FIN DEL IF
		}
		/*************************************************************/
	public function ajax_edit($id)		{
			$data = $this->estudios_crud_model->get_estudio_by_id($id); 
			echo json_encode($data);
		}
 		/***************************************************************/
	public function estudio_update()	{
		$data = array(
				'ID_ESTUDIO'		 	=> $this->input->post('ID_ESTUDIO'),
				'ANALISIS_SOLICITADO'	 	=> $this->input->post('ANALISIS_SOLICITADO'),
				'METODOLOGIA_ESTUDIO' 	=> $this->input->post('METODOLOGIA_ESTUDIO'),
				'AREA_ESTUDIO' 			=> $this->input->post('AREA_ESTUDIO'),
				'ALIAS_ESTUDIO' 		=> $this->input->post('ALIAS_ESTUDIO'),
				'DURACION_MIN_ESTUDIO' 		=> $this->input->post('DURACION_MIN_ESTUDIO'),
				'DURACION_MAX_ESTUDIO' 		=> $this->input->post('DURACION_MAX_ESTUDIO'),
				'TOPE_ESTUDIO' 		=> $this->input->post('TOPE_ESTUDIO'),
				'PRECIO_ESTUDIO' => $this->input->post('PRECIO_ESTUDIO'),
				'VALIDADO_ESTUDIO' => $this->input->post('VALIDADO_ESTUDIO'),
				'ACREDITADO_ESTUDIO' => $this->input->post('ACREDITADO_ESTUDIO'),
				'RECONOCIDO_ESTUDIO' => $this->input->post('RECONOCIDO_ESTUDIO'),
				'REFERENCIA_ESTUDIO' => $this->input->post('REFERENCIA_ESTUDIO')
			);
		$this->estudios_crud_model->estudio_update(array('ID_ESTUDIO' => $this->input->post('ID_ESTUDIO')), $data);
		echo json_encode(array("status" => TRUE));
	}
		/*************************************************************/ 
	public function estudio_delete($id)	{
		$this->estudios_crud_model->delete_estudio_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
 
 
}