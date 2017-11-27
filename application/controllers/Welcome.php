<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function prueba(){
		$this->load->view('welcome_message');
	}
	public function index(){
		$this->load->helper('mysql_to_excel_helper');		
		
		$fields = $this->db->field_data('usuarios');
		$query = $this->db->select('*')->get('usuarios');
		$aRetArray = array("fields" => $fields, "query" => $query);
		to_excel( $aRetArray, "Prueba.xls");
	}
	///*************************************************************//
	public function phpExcel(){
		$data = new stdClass();
		$data->accion = 'alta';
		$data->menu_activo = 'SISTEMAS';
		$data->title = 'TITULO PERRON';
		
		//$this->load->view('plantillas/head', $data);
		//$this->load->view('plantillas/header', $data);		
		
		$cQuery = $this->db->get("analitos");
		$data->datos = $cQuery;
		$this->load->library('table');
		$cPlantilla = array(
			'table_open' => '<table border="1" id="idTablaPrueba" class="table">'
		);
		$this->table->set_template($cPlantilla);		
		//echo $this->table->generate($cQuery);	
		$this->load->view('prueba/v_prueba',$data);		
		//$this->load->view('plantillas/footer', $data);
	}
	// *************************************************************** //
	public function index2()
	{
		//$this->load->view('welcome_message');
		//$this->load->view('dropdown');
		//$this->load->view('pagina_modal');
		//$this->load->view('submenu');
		
		mb_internal_encoding("UTF-8");

		$x="<p style='font-size:x-large;'>12345678901234567890W</p>";
		echo $x;
		echo '<br>';
		
		echo mb_strwidth($x);
		echo '<br>';
		echo mb_strimwidth("Hello World", 0, 10, "...");

		
	}
}
