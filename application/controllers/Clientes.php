<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('clientes_model');
		if (!$_SESSION['user_id']){
				redirect('/login');
		}
		
	}

	public function ventana_modal() {
		$data = new stdClass();

		$data->title = 'Sistema de Recepcion de Muestra';
	 	$data->contenido = 'crud/crud'; //vista registro
	 	$data->panel_title = 'Listado de Clientes';
	 	$data->menu_activo = 'catalogos';
	 	$data->cargar_crud = true;

		$this->load->view('plantillas/head',$data);		
		$this->load->view('plantillas/mymodal2',$data);
		$this->load->view('plantillas/footer',$data);		
	}

	public function menu_largo(){

			$data = new stdClass();

			$data->title = 'Sistema de Recepcion de Muestra';
		 	$data->contenido = 'crud/crud'; //vista registro
		 	$data->panel_title = 'Listado de Clientes';
		 	$data->menu_activo = 'catalogos';
		 	$data->cargar_crud = true;
		

			$this->load->view('plantillas/head',$data);
			//$this->load->view('plantillas/header',$data);
			$this->load->view('plantillas/header_multi');
			//$this->load->view('example.php',$output);
			//$this->load->view('crud/crud',$output);
			$this->load->view('plantillas/footer',$data);

	}
	/*******************************************************************************/
	public function callback_email_check( $cEmailCheck ){
		//$cEmailCheck == 'test';
		$this->session->set_flashdata('mensaje_error','Email no Valido');
		$lEmailValido = $this->clientes_model->email_unique($cEmailCheck);
		//print_r($lEmailValido);
		//exit();
		if ( $lEmailValido ) {                
                        $this->form_validation->set_message('username_check', 'The {field} field can not be the word "test"');
                        $this->session->set_flashdata('mensaje_error','Email no Valido');
                        return FALSE;
                }else {
                        return TRUE;
                }

	}
	/*******************************************************************************/
	public function add_cliente(){	
		$this->form_validation->set_rules('nombre_cliente','Nombre','trim|required');
		$this->form_validation->set_rules('direccion_cliente','Domicilio','trim|required');
		$this->form_validation->set_rules('rfc_cliente','RFC','trim|required');
		$this->form_validation->set_rules('ciudad_cliente','Ciudad','trim|required');
		$this->form_validation->set_rules('estado_cliente','Estado','trim|required');
		$this->form_validation->set_rules('email_cliente','Correo','trim|required|valid_email','callback_email_check');	
		$this->form_validation->set_rules('contacto_cliente','Contacto','trim|required');	
		$this->form_validation->set_rules('telefono_cliente','Telefono','trim|required|integer');	


		$this->form_validation->set_message('required', 'El campo [%s]es obligatorio');
		$this->form_validation->set_message('integer', 'El campo [%s] deve poseer solo numeros enteros');
		$this->form_validation->set_message('is_unique', 'El campo [%s] ya esta registrado');
		$this->form_validation->set_message('required', 'El campo [%s] es obligatorio');
		$this->form_validation->set_message('max_length', 'El Campo [%s] debe tener un Maximo de %d Caracteres');	

        if ($this->form_validation->run() == FALSE)
        {
                $this->alta_cliente();
        }
        else  //COMENZZAMOS CON LAS VALIDACIONES DEL PASSWORD
        {
        		//$cNombre, $cDomicilio, $cRfc, $cCiudad, $cEstado, $cEmail, $cContacto
        		$cNombre 	= $this->input->post('nombre_cliente');
        		$cDomicilio = $this->input->post('direccion_cliente');        		
        		$cCiudad	= $this->input->post('ciudad_cliente');
        		$cEstado	= $this->input->post('estado_cliente');
        		$cRfc		= $this->input->post('rfc_cliente');
        		$cTelefono	= $this->input->post('telefono_cliente');
        		$cEmail		= $this->input->post('email_cliente');
        		$cContacto	= $this->input->post('contacto_cliente');  
        		
                                
                $cte = $this->clientes_model->addCliente($cNombre, $cDomicilio, $cRfc, $cCiudad, $cEstado, $cEmail, $cContacto, $cTelefono);

                if (!$cte) {
                	$this->session->set_flashdata('mensaje_error','Datos de Cliente Incorrectos');
                	redirect(base_url('alta_cliente'));
                }                

                
                //$_SESSION['is_logged_in'] 	= TRUE;
                $this->session->set_flashdata('mensaje_sucess','Alta de cliente ['.$nombre.'] Realizada');
                redirect(base_url());

                exit();
        }

	}
	/********************************************************************************/
	public function alta_cliente(){
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'clientes/alta_cliente'; //vista alta de cliente
		$data->panel_title = 'Alta de Clientes';
		$data->menu_activo = 'servicio';
		$data->cargar_crud = false;

		

		//echo $this->table->generate($data);
		//$this->load->view('plantillas/head', $data);
		//$this->load->view('plantillas/header', $data);
		//$this->load->view('clientes/alta_cliente', $cTabla);
		$this->load->view('frontend',$data);
	}
	/*******************************************************************************/
	public function buscar_cliente(){	// is ajax reques	

		if (isset($_POST['name_cte']) ) {
			$clienteabuscar = $_POST['name_cte'];
		}else {
			$clienteabuscar = $this->input->post['name_cte'];		}

		if (isset($clienteabuscar)) {

			//$clienteabuscar = $this->input->post['name_cte'];
			//$clienteabuscar = $_POST['name_cte'];
			//$cRet = "Buscando al Cliente [" . $clienteabuscar;

			$cRet = $this->clientes_model->getCliente($clienteabuscar);
			//var_dump($cRet);
			//exit();

	        if (is_null($cRet)) {
	        	$cRet = 'Nombre de Usuario no encontrado, Redefina la Busqueda';
	        
	        }else { // convertirla al formato de json 'name' => 'valor'
	        	//$cRet = "esto ya me esta sacando de coraje";
	        	//$cRet = var_dump($cRet);

	        	$decode=json_decode($cRet, true); // codificamos a array el objeto

				$cUrl ="onclick= location.href='". base_url('alta_cliente')."'";
				//$cUrl = onclick=" location.href='http://www.google.com'"

			    $cTabla = '<div class="container"><div class="col-md-11"><div class="panel panel-default"><div class="panel-heading"><button type="button" class="btn btn-info" '.$cUrl.'>Agregar Cliente </button></div><div class="panel-body"><table class="table  table-hover table-responsive table-condensed" id="idTablaBusquedaClientes" >
					<thead>
						<tr>						
							<th>Id</th>
							<th>Nombre</th>
							<th>Domicilio</th>
							<th>RFC</th>
							<th>Email</th>
							<th>Telefono</th>
							<th>Contacto</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';

					$cCad = "";
					//$cArray = array();
					$cArray = "";
					foreach ($decode as $key => $value) {
						$cId_Cte = $value['ID_CLIENTE'];
						$cNombre_Cte = "'".$value['NOMBRE_CLIENTE'] . "'";

						$cpo1 = 
						$cCad = $cCad .'<tr>';
						$cCad = $cCad .'<td>'.$value['ID_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['NOMBRE_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['DOMICILIO_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['RFC_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['EMAIL_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['TELEFONO_CLIENTE'].'</td>';
						$cCad = $cCad .'<td>'.$value['CONTACTO_CLIENTE'].'</td>';
						
						
						$cArray = "[".$cId_Cte.",".$cNombre_Cte.",'".$value['DOMICILIO_CLIENTE']."','".$value['RFC_CLIENTE']."','".$value['EMAIL_CLIENTE']."','".$value['TELEFONO_CLIENTE']."','".$value['CONTACTO_CLIENTE']."','" .$value['NOMBRE_IDR_CLIENTE']."','" .$value['DOMICILIO_IDR_CLIENTE']. "','" .$value['RFC_IDR_CLIENTE']. "','" .$value['CONTACTO_IDR_CLIENTE']. "']"; //24/05/2017

						/*										
						$cCad = $cCad . '<td><button type="button" 
						onclick="SeleccionaRegistro('.$value["ID_CLIENTE"].','.$cNombre_Cte.')" 
						class="btn btn-info btn-xs" id="idBtnSeleccionaCte" >+</button>'.'</td>';*/
						$x = '[1, 2, 3]';
						$cCad = $cCad . '<td><button type="button" 
						onclick="SeleccionaRegistro(this,'.$cArray.')" 
						value = "'.$cNombre_Cte .'"
						class="btn btn-info btn-xs" id="'.$cId_Cte.'" >+</button>'.'</td>';
						/*$cCad = $cCad . '<td><button type="button" id="idBtnSeleccionaCte" name="idBtnSeleccionaCte" value="'.$cId_Cte.'">+</button>'.'</td>';*/

						$cCad = $cCad .'<tr>';						
					}						

					$cTabla = $cTabla .$cCad. '</tbody></table></div></div></div></div>';
	        }
	        echo $cTabla;
	    }else{    
	        echo 'hay problemas con la repcecion del parametro';
	    }        	
	}
	/******************************************************************/
	public function graba_datos_idr_cliente() { // is ajax request
		$lRet = 'Todo OK';
		if (isset($_POST['id_cliente'])){
			$id_cliente = $_POST['id_cliente'];
			$data = array(
	        	'NOMBRE_IDR_CLIENTE' => $_POST['nombre_idr_cliente'],
	        	'DOMICILIO_IDR_CLIENTE'  => $_POST['domicilio_idr_cliente'],
	        	'RFC_IDR_CLIENTE'  => $_POST['rfc_idr_cliente'],
	        	'CONTACTO_IDR_CLIENTE'  => $_POST['contacto_idr_cliente']
			);
			//$this->db->where( 'ID_CLIENTE', $id_cliente );
			//$lRet = $this->db->set($data)->where( 'ID_CLIENTE', $id_cliente )->get_compiled_update('clientes');
			$this->db->set($data)->where( 'ID_CLIENTE', $id_cliente )->update('clientes');
			$lRet = "Actualizacion de los Datos del Cliente rubro IDR termino con [". $this->db->count_all_results().'] Registro actualizados [NOMBRE_IDR,DOMICILIO_IDR,RFC_IDR,CONTACTO_IDR]';		
			
			// Produces string: INSERT INTO mytable (`title`, `name`, `date`) VALUES ('My title', 'My name', 'My date')
			/*			
			
			$this->db->set('NOMBRE_IDR_CLIENTE' ,$_POST['nombre_idr_cliente'],false);
			$this->db->set('DOMICILIO_IDR_CLIENTE' ,$_POST['domicilio_idr_cliente'],false);
			$this->db->set('RFC_IDR_CLIENTE' ,$_POST['rfc_idr_cliente'],false);
			$this->db->set('CONTACTO_IDR_CLIENTE' ,$_POST['contacto_idr_cliente'],false);
			
			$this->db->update('clientes');
			
			$lRet = $this->db->get_compiled_update('clientes');
			//implode
			//$array = array('apellido', 'email', 'teléfono');
			//$separado_por_comas = implode(",", $array);
			//echo $separado_por_comas; // apellido,email,teléfono
			
			//echo implode(",",$lRet). " actualizacion termino en [". $this->db->count_all_results();	
			echo $lRet. " actualizacion termino en [". $this->db->count_all_results();		
			
			//$lRet = var_dump($_POST);
			//$lRet = $_POST['nombre_idr_cliente'];
			
			*/
			
		} else {
			$lRet = 'al parece no encontro el post del nombre del idr';
		}		
		echo $lRet;
	}
	/****************************************************************/	
	public function prueba(){
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'prueba'; //vista registro
		$data->panel_title = 'Listado de Clientes';
		$data->menu_activo = 'catalogos';
		$data->cargar_crud = true;		

		$this->load->view('frontend',$data);
	}
	/*********************************************************************/
	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function listar_clientes() {
		try{
			$crud = new grocery_CRUD();			
			$data = new stdClass();

			$data->title = 'Sistema de Recepcion de Muestra';
		 	$data->contenido = 'crud/crud'; //vista registro
		 	$data->panel_title = 'Listado de Clientes';
		 	$data->menu_activo = 'catalogos';
		 	$data->cargar_crud = true;


			$crud->set_theme('flexigrid');
			//$crud->set_theme('datatables');
			$crud->set_table('clientes');
			//$crud->set_subject('Office');
			//$crud->required_fields('city');
			//$crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $crud->render();

			//$this->_example_output($output);
			$this->load->view('plantillas/head',$data);
			$this->load->view('plantillas/header',$data);
			//$this->load->view('example.php',$output);
			$this->load->view('crud/crud',$output);
			$this->load->view('plantillas/footer',$data);


		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}



	}
}	