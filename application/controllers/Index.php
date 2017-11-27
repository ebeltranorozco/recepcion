<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		//$this->load->database();
		
	}

	public function index()
	{

		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'index'; // carga una vista llamada
		$data->menu_activo = 'inicio';
		//echo 'hay la llevamos';
		$this->load->view('frontend',$data);
		//var_dump($_SESSION);
	}
}
