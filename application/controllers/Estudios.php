<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class estudios extends CI_Controller {

	public function __construct()	{
		parent::__construct();
		$this->load->model('clientes_model');
		$this->load->model('estudios_model');
		$this->load->database();
		if (!(isset($_SESSION['user_id']))) {
		//if (!($_SESSION['user_id'])){
				redirect('/login');
		}
	}
	/*** *************************************************************/	
	public function __destruct() {
		//2017-08-22 --> liberando la aplicacion para que otra persona la este usando..!
		//$cUserAlias = $_SESSION['alias_usuario'];
		//$query4 = $this->db->query('select REALIZANDO_SOLICITUD from folios')->row();
		//if ($cUserAlias == $query4->REALIZANDO_SOLICITUD) {
		//	$this->db->query("update folios set REALIZANDO_SOLICITUD = ''");
		//	echo "<script>alert('saliendo del constructor')</script>";	
		//}
    }
	/*****************************************************************/
	public function finaliza_detallado_muestra(){ // es ajax reques, es unicamente para ponerle una F a la id de la muestra
		$lRet = false;	
		$cIdDetalladoMuestra = $_POST['id_detalle_muestra'];
		if (isset( $cIdDetalladoMuestra)){
			$lOk = $this->db->query("update detalle_muestras set STATUS_MUESTRA = 'F' where ID_DETALLE_MUESTRA = ".$cIdDetalladoMuestra  );
			if ($lOk){ $lRet = true;}
		}
		echo $lRet;
	}
	/***************************************************************/
	public function graba_encabezado_resultado(){ // is ajax_request  va a grabar la parte enc del informe de resul
		$lRet = false;	

		if (isset($_POST['id_detalle_muestra'])){
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_DETALLE_MUESTRA' 		=> $_POST['id_detalle_muestra'],
				'FECHA_ANALISIS' 	=> $_POST['fecha_analisis'],
				'TCH_RESULTADO'		=> $_POST['tch_resultado'],
				'TCA_RESULTADO'		=> $_POST['tca_resultado'],
				'METODO_PRUEBA'		=> $_POST['metodo_prueba'],
				'REFERENCIA_RESULTADO'		=> $_POST['referencia_resultado'],
				'OBSERVACION_RESULTADO'		=> $_POST['observacion_resultado'],
				'CONDICION_MUESTRA'			=> $_POST['condicion_muestra']
				);		
			$lOk = $this->db->insert('enc_resultado', $datos);
			if ($lOk) { // hay q regresar el ultimo id agregado..
				//mysql_insert_id())
				$lRet = $this->db->insert_id();
			}			
		}	
		echo $lRet;
	}
	/*****************************************************************/
	public function graba_detallado_resultado(){ // is ajax reques va a grabar la parte del det del informe de resultado
		$lRet = false;	
//var data_detalle = {'id_resultado':idMuestra,'prueba_resultado':campo1,'resultado_resultado':campo2,'ch_resultado':campo3,'ca_resultado':campo4};
		if (isset($_POST['id_enc_resultado'])){
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_enc_resultado' 		=> $_POST['id_enc_resultado'],
				'PRUEBA_RESULTADO' 	=> $_POST['prueba_resultado'],
				'RESULTADO_RESULTADO'		=> $_POST['resultado_resultado'],
				'CA_RESULTADO'		=> $_POST['ca_resultado'],
				'CH_RESULTADO'		=> $_POST['ch_resultado']				
				);		
			$lOk = $this->db->insert('det_resultado', $datos);
			if ($lOk) { 
				$lRet = true;
			}
		}	
		echo $lRet;
	}
	/***************************************************************/
	public function informe_estudio( $idDetalleMuestra = null,$cAreaEstudio = null) {// este es para sacar en pantalla la captura del informe de resultados
		//echo 'Vizsualizando  Informe ['. $id.']';
			$data = new stdClass();
			//$idMuestra = 20;// ejemplo unicamente
			$data->title = 'Sistema de Recepcion de Muestra';
			$data->contenido = 'estudios/v_informe_estudio'; //vista informe de resultados del estudios			
			$data->menu_activo = 'servicios';
			$data->accion = 'ALTA';
			$data->panel_title = 'Informe de Resultados';
			$data->area_estudio = $cAreaEstudio;
			
			//if ( !is_null($idMuestra))  { // si no hay un idMuestra entoneces se trata de una trampa.
			
			$data->idProcesando = $idDetalleMuestra;
			$data->AreaEstudio = $cAreaEstudio;
			$data->idDetalleMuestraProcesando = $idDetalleMuestra;


			$this->load->library('table');		

			//$this->table->set_heading('Linfoncito','Resultado','Info1','Info2','Acciones');
			if ($cAreaEstudio=='Q'){
				//$this->table->set_heading('Prueba','Resultado','CH','CA','Acciones');
			}else{//$this->table->set_heading('Resultado','Acciones');
			}

			$template2 = array(
			        'table_open' => '<table border="1" id="tablaDetalleResultados" class="table">'
			);			
						
			$this->table->set_template($template2);	
			$this->db->select('PRUEBA_RESULTADO, RESULTADO_RESULTADO, CH_RESULTADO, CA_RESULTADO,""');
			$this->db->from('det_resultado');
			$this->db->join('enc_resultado','det_resultado.ID_enc_resultado = enc_resultado.ID_enc_resultado','LEFT');			
			$this->db->where('enc_resultado.ID_DETALLE_MUESTRA',$idDetalleMuestra);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();

			if (count($query_result)>0)  {
				$data->accion = 'CONSULTA';
			}

			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!

			// AHORA EL SELECT DE LA DESCRIPCION DE LA MUESTRA
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO','LEFT');
			$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA','LEFT');
			$this->db->join('enc_resultado','detalle_muestras.ID_DETALLE_MUESTRA = enc_resultado.ID_DETALLE_MUESTRA', 'LEFT');
			$this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);
			$cCad2 = $this->db->get_compiled_select();
			$data->datos_muestra = $this->db->query($cCad2)->result();			
			$data->sql2 = $cCad2;

			//AGREGADO 08/12/2016 para incorporar la opcion de ingresar los linfoncitos desde su tabla
			$cEstudio = $data->datos_muestra[0]->ID_ESTUDIO;

			$this->table->set_heading('Analito','Resultado',$data->datos_muestra[0]->NOMBRE_CPO1_INFORME, $data->datos_muestra[0]->NOMBRE_CPO2_INFORME,'Acciones' ) ;


			$this->db->select('*');
			$this->db->from('linfoncitos');
			$this->db->join('estudios','linfoncitos.ID_ESTUDIO = estudios.ID_ESTUDIO','LEFT');
			$this->db->where('linfoncitos.ID_ESTUDIO', $cEstudio);
			$cCad3 = $this->db->get_compiled_select();			
			$data->sql3 = $cCad3;
			$data->linfoncitos = $this->db->query($cCad3)->result();

			$this->load->helper('dropdown');
		
			//  function listData($table,$name,$value,$orderBy=null, $where_nombre_campo=null, $where_variable) {
        
			$data->LinfoncitosCombo = listData('linfoncitos','id_linfoncito', 'nombre_linfoncito',null,'linfoncitos.ID_ESTUDIO',$cEstudio);


			//$data->datos_muestra = $this->db->get()->result();

			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);

			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('estudios/v_informe_estudio', $data);
			$this->load->view('plantillas/footer', $data);
	}
	/***************************************************************/
	public function edit( $id) {
		//capturar_estudio
		//echo 'Editando ['. $id.']';
		$this->capturar_estudio( $id );
	}
	/***************************************************************/
	public function add(){ // agregar usuarios
		echo "ya chinge";
	}
	/***************************************************************/
	public function prueba(){
		$crud = new grocery_CRUD();

		//$crud->set_theme('datatables');
		$crud->set_theme('flexigrid');
		$crud->set_table('usuarios');
		$crud->set_subject('usuario');
		//$crud->set_language($this->session->('english')); 
		$crud->set_language('spanish'); 

		//$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));
		//$crud->required_fields('city');
		$crud->columns('ALIAS_USUARIO','NOMBRE_USUARIO','EMAIL_USUARIO','TIPO_USUARIO','STATUS_USUARIO');
		
		$crud->add_action('More', '', 'demo/action_more','ui-icon-plus');
		//$crud->add_action('Photos', '', '','ui-icon-image',array($this,'just_a_test'));
		//$crud->add_action('Smileys', 'http://www.grocerycrud.com/assets/uploads/general/smiley.png', 'demo/action_smiley');

		$crud->add_fields(array('ALIAS_USUARIO','NOMBRE_USUARIO','EMAIL_USUARIO','TIPO_USUARIO',));
		$crud->edit_fields(array('ALIAS_USUARIO','NOMBRE_USUARIO','EMAIL_USUARIO','TIPO_USUARIO'));
 
		//$crud->required_fields(array('customerName','contactLastName'));

		$output = $crud->render();
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'estudios/v_example'; //vista estudios
		$data->panel_title = 'Prueba de CRUD';
		$data->menu_activo = 'servicios';
		$data->css_files = $output->css_files;
		$data->js_files = $output->js_files;

		$this->load->view('plantillas/head', $data);
		$this->load->view('plantillas/header', $data);
		$this->load->view('v_example', $output);
		$this->load->view('plantillas/footer', $data);

		//$this->load->view('example.php',$output);
	}
	/**************************************************************/
	public function crud_estudio(){ // es para el manejo de el crud de estudios OPCION DE MENU
		$data = new stdClass();
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'estudios/v_crud_estudio'; //vista estudios
		$data->panel_title = 'Informe Status Muestras';
		$data->menu_activo = 'servicios'; // movimiento de servicios

		$this->db->select("*,'1' as TOTAL_ENSAYOS_X_MUESTRA,space(1) as FechaFinalIDR");
		$this->db->from('detalle_muestras');
		$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
		$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
		//$this->db->where('detalle_muestras.STATUS_MUESTRA !=','F'); // finalizada

		// preguntando el tipo de usuario para saber que es lo que visualizara
		if ($_SESSION['user_tipo']=='Q' or $_SESSION['user_tipo']=='M') { // se trata de una persona de analisis
			$this->db->where('AREA_ESTUDIO', $_SESSION['user_tipo']) ;
		}
		//$this->db->order_by('detalle_muestras.STATUS_MUESTRA,recepcion_muestras.FECHA_RECEPCION DESC, detalle_muestras.ID_DETALLE_MUESTRA');
		$this->db->order_by('recepcion_muestras.FECHA_RECEPCION desc');
		$this->db->limit(500);
		$query = $this->db->get();
		
		foreach ($query->result() as $row) {
	        $cIdMuestra = "'".$row->ID_MUESTRA."'";
	        $cIdMetodologia = "'". $row->ID_METODOLOGIA."'";
	        //$data->prueba = $this->db->query("SELECT count(ID_MUESTRA) as Total_Ensayos FROM `detalle_muestras` JOIN `recepcion_muestras` ON `detalle_muestras`.`ID_RECEPCION_MUESTRA` = `recepcion_muestras`.`ID_RECEPCION_MUESTRA`")->row();	        
			$nEnsayos = $this->db->query("SELECT count(ID_MUESTRA) as Total_Ensayos FROM `detalle_muestras` JOIN `recepcion_muestras` ON `detalle_muestras`.`ID_RECEPCION_MUESTRA` = `recepcion_muestras`.`ID_RECEPCION_MUESTRA` WHERE detalle_muestras.ID_MUESTRA = $cIdMuestra")->row()->Total_Ensayos;
	        
	        if ($nEnsayos>1){ $row->TOTAL_ENSAYOS_X_MUESTRA = $nEnsayos; }
	        //2017-08-25 --> para restringuir el crud de los dias que se puedan corregir los informes..!
	        if ($row->ID_IDR == 1 ){ $cCad = "select FECHA_FINAL_AFLATOXINAS as FechaFinal from idr_aflatoxinas where ID_METODOLOGIA = $cIdMetodologia "; }
	        if ($row->ID_IDR == 2 ){ $cCad = "select FECHA_FINAL_PLAGUICIDAS as FechaFinal from idr_enc_plaguicidas where ID_METODOLOGIA = $cIdMetodologia "; }
	        if ($row->ID_IDR == 3 ){ $cCad = "select FECHA_FINAL_MICROBIOLOGIA as FechaFinal from idr_microbiologia where ID_METODOLOGIA = $cIdMetodologia "; }
	        if ($row->ID_IDR == 5 ){ $cCad = "select FECHA_FINAL_METALES as FechaFinal from idr_enc_metales where ID_METODOLOGIA = $cIdMetodologia "; }
	        if ($row->ID_IDR == 4 ){ $cCad = "select FECHA_FINAL_MERCURIO as FechaFinal from idr_mercurio where ID_METODOLOGIA = $cIdMetodologia "; }
			
			$FechaFinal = $this->db->query($cCad)->row();			
			if (count($FechaFinal)>0 ) {
				$FechaFinal = $FechaFinal->FechaFinal;//->format('d/m/Y');				
				$row->FechaFinalIDR = substr($FechaFinal,0,10);
			}			
		}		
		
		$data->query = $query->result();
		$data->sql = $this->db->last_query();

		$this->load->view('plantillas/head', $data);
		$this->load->view('plantillas/header', $data);
		$this->load->view('estudios/v_crud_estudio', $data);
		$this->load->view('plantillas/footer', $data);
	}
	/*************************************************************/	
	public function ImprimeSolicitud(){ // es ajax request

		if (isset($_POST['idFolio'])){
			$idFolio = $_POST['idFolio'];
			$data = $this->estudios_model->getAllEstudio($idFolio);
			var_dump($data);
			exit();
			die();

			// comienza lo bueno ajua...
			$this->load->library('pdf');
		    $this->pdf = new pdf();
		    $this->pdf->AddPage('l','letter'); //l landscape o P normal
		    $this->pdf->SetFont('Arial','B',10);
		    $r = 25;
		    $c = 10;
		    // RECTANGULO REGISTRO DEL SERVICIO
		    $c1 = 10;
		    $c2 = 120;
		    $r1 = 25;
		    $r2 = 45;
		    //$this->pdf->rect($c,$r,$c+110,$r+20);
		    $this->pdf->rect($c1,$r1,$c2,$r2);
		    $this->pdf->rect($c1,$r1+5,$c1+7,5);
		    //$this->pdf->rect($c,$r+8,$c+110);

		    $this->pdf->rect($c+130,$r,$c+120,$r+8);
		    //responsable toma de muestra
		    $this->pdf->rect($c+130,$r+38,$c+120,7);    


		    //dibujando los rectangulos



		    $r=80;

		    $this->pdf->rect($c,$r,$c+250,30);
		    // imprimiendo los campos
		    $this->pdf->SetFont('Arial','B',6);
		    $this->pdf->setxy(10,$r);
		    $this->pdf->multicell(25,5,"ID DE MUESTRA",0,'C');
		    $this->pdf->line(35,$r,35,$r+30);
		    $this->pdf->setxy(35,$r);
		    $this->pdf->multicell(25,5,'ID ASIGNADA POR EL CLIENTE',0,'C');
		    $this->pdf->line(35+25,$r,35+25,$r+30);
		    
		    $this->pdf->setxy(60,$r);
		    $this->pdf->cell(80,5,utf8_decode( 'DESCRIPCIÓN DE LA MUESTRA' ),0,2,'C');
		    $this->pdf->cell(80,5,utf8_decode( '(Descripción general, origen, peso, etc.)' ),0,0,'C');
		    $this->pdf->line(60+80,$r,60+80,$r+30);
		    //$this->pdf->line( $this->pdf->getx(), $this->pdf->gety(), $this->pdf->getx()+50, $this->pdf->gety());
		    $this->pdf->setxy(140,$r);    
		    $this->pdf->cell(20,5,"LOTE",0,0,'C');
		    $this->pdf->line(160,$r,160,$r+30);

		    $this->pdf->multicell(16,5,'No. DE MUESTRAS',0,'C');
		    $this->pdf->line(160+16,$r,160+16,$r+30);
		    $this->pdf->setxy(176,$r);    
		    $this->pdf->cell(50,5,utf8_decode('ANALISIS / METODOLOGÍA'),0,0,'C');
		    $this->pdf->line(176+50,$r,176+50,$r+30);
		    $this->pdf->cell(20,5,'PRECIO',0,0,'C');
		    $this->pdf->line(176+50+20,$r,176+50+20,$r+30);
		    $this->pdf->cell(20,5,'IMPORTE',0,1,'C');
		    $this->pdf->line(176+50+20,$r,176+50+20,$r+30);
		    
		    $this->pdf->line(10,90,270,90);   


		    //resultado final

		    $this->pdf->Output();

		}else {
			return 'false';
		}

	}
	/************************************************************/
	public function add_detallado_estudio(){ // es ajax reques, viene de la funciones.js donde queremos grabar el detallado de los estudios de la muestra (de toda la muestra)
		$lRet = "";		

		if (isset($_POST['id_muestra'])){				
	
			// PRECIO E IMPORTE LLEGAN FORMATEADO..!
			$this->load->library('utilerias');
			//$nPrecio = $this->utilerias->conv_cadena_float($_POST['precio']);
			$nImporte = $this->utilerias->conv_cadena_float($_POST['importe']);

			//05/12/2016 hay q everificar no este ya grabado el id de la muestra
			//$cId_MuestraTemp = $_POST['id_estudio'];
			//$cId_Muestra = $_POST['id_muestra'];
			$cIdEstudio = $_POST['id_estudio'];
			$cIdMetodo  = $_POST['id_metodologia']; // 19/05/2017

			//$query = $this->db->query('SELECT CONSECUTIVO_ESTUDIO FROM estudios WHERE ID_ESTUDIO = '.$cIdEstudio)->row();
			
			$query = $this->db->query("SELECT MAX(CONSECUTIVO_ESTUDIO)+1 as CONSECUTIVO from estudios where ID_ESTUDIO = ".$cIdEstudio )->row();
			if ($this->db->count_all_results()>0) {				
				// ncesitamos obtener los datos de am o am + alias
				$cIdMetodo =substr($cIdMetodo, 0, strlen($cIdMetodo)-4) . str_pad($query->CONSECUTIVO,4,'0',STR_PAD_LEFT);
			}
			
			$datos_detallado = array ('ID_RECEPCION_MUESTRA'	=> $_POST['id_recepcion_muestra'],
								'ID_ESTUDIO'			=> $_POST['id_estudio'],
								'ID_MUESTRA'			=> $_POST['id_muestra'], 								
								'LOTE_MUESTRA'			=> $_POST['lote_muestra'],
								'ID_ASIGNADO_CLIENTE'	=> $_POST['id_asignado_cliente'],
								'ID_METODOLOGIA'		=> $cIdMetodo, //$_POST['id_metodologia'], // 19/05/2017
								'TIPO_MUESTRA'			=> $_POST['tipo_muestra'], //14/06/2017
								'PESO_VOL_MUESTRA'		=> $_POST['peso_vol_muestra'],
								'TEMPERATURA_MUESTRA'	=> $_POST['temperatura_muestra'],
								'IMPORTE'				=> $nImporte,
								'FECHA_SALIDA'			=> $_POST['fecha_salida']);
			$lRet2 = $this->db->set($datos_detallado)->get_compiled_insert('detalle_muestras');
			
			$lRet = $this->db->insert('detalle_muestras', $datos_detallado);
			if ($lRet) {// actualizamos el consecutivo del estudio
				//$nIdMuestra = _POST['id_muestra']+1;
				$this->db->query('update estudios set CONSECUTIVO_ESTUDIO = '.$query->CONSECUTIVO.' where ID_ESTUDIO = '.$_POST['id_estudio']);
				// ahora mandamos el correo al jefe de departamento..!
				$config = array(
					 'protocol' => 'smtp',
					 'smtp_host' => 'smtp.googlemail.com',
					 'smtp_user' => 'ebeltran@laria.mx', //Su Correo de Gmail Aqui
					 'smtp_pass' => 'inf61010', // Su Password de Gmail aqui
					 'smtp_port' => '465', //587
					 'smtp_crypto' => 'ssl', //tls
					 'mailtype' => 'html',
					 'wordwrap' => TRUE,
					 'charset' => 'utf-8'
					 );
				 $this->load->library('email', $config);
				 $this->email->set_newline("\r\n");
				 $this->email->from('muestras@laria.mx');
				 $this->email->subject('Nueva Muestra de Analisis');

				 $datos_Estudio = $this->estudios_model->getEstudioxId( $_POST['id_estudio']);
				 $cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h3>";
				 $cMsg .= "<h3>Informe de Muestra Recibida</h2>";
				 $cMsg .= "<br/>";
				 $cMsg .= '<center>';
				 $cMsg .= "<table width='80%' style='text-align:center;'>" ;
				 	$cMsg .= "<tr>";
				 		$cMsg .= "<th>ID MUESTRA</th>";
				 		$cMsg .= "<th>FECHA INICIO</th>";
				 		$cMsg .= "<th>FECHA FINAL ESTIMADA</th>";
				 	$cMsg .= "</tr>";				 	
				 	$cMsg .= "<tr>";
				 	
				 		$cMsg .= "<td>".$_POST['id_muestra']."</td>";
				 		$cMsg .= "<td>".date('Y-m-d h:i:s')."</td>";
				 		$cMsg .= "<td>".$_POST['fecha_salida']."</td>";
				 	$cMsg .= "</tr>";
				 $cMsg .= "</table>";
				 $cMsg .= "<br/><br/>";

				 $cMsg .= "<table width='80%' style='text-align:center;'>" ;
				 	$cMsg .= "<tr>";
				 		$cMsg .= "<th>ID METODOLOGIA</th>";
				 		$cMsg .= "<th>TIPO DE MUESTRA</th>";
				 		$cMsg .= "<th>METODOLOGIA</th>";
				 		$cMsg .= "<th>ANALISIS</th>";				 		
				 	$cMsg .=  "</tr>";				 	
				 	$cMsg .=  "<tr>";
				 		$cMsg .= "<td>".$cIdMetodo."</td>";				 		
				 		$cMsg .= "<td>". $_POST['tipo_muestra']."</td>";
				 		$cMsg .= "<td>". $datos_Estudio->METODOLOGIA_ESTUDIO . "</td>";
				 		$cMsg .= "<td>". $datos_Estudio->ANALISIS_SOLICITADO . "</td>";
				 	$cMsg .= "</tr>";				 	
				 	$cMsg .= "</table>";
				 	$cMsg .= '</center>';

				 $cMsg .= "<br/><br/>";
				 $cMsg .= "<p>Usuario Relizo:<br/> [<strong>" . $_SESSION['user_nombre']. "</strong>]</p><br/>";
				 
				 $cMsg = $cMsg . "<a href='". base_url('login') ."'>Acceso al Sistema</a>";
				 $cMsg = $cMsg . '<br/><br/>';
				 
				 $this->email->message($cMsg);
				 
				 //14/06/2017
				 if (substr($_POST['id_muestra'],0,2) == 'AQ') {
				 	$this->email->to('analisis_quimicos@laria.mx');
				 	
				 } else {
				 	$this->email->to('analisis_microbiologicos@laria.mx');
				 }
				 $this->email->cc('calidad@laria.mx','sistemas@laria.mx');				 
				 
				 	
				 // hay q preguntar si es para quimicos o microbiologos..!
				 if (	$this->email->send(FALSE) ) {
				 }
			} // fin del if $lRet
			
		}else {
			$lRet = "no esta entrando parametro";
		}
		echo $lRet;
	}
	/**********************************************************/
	public function actualiza_folios_temp(){ // es ajax request, actualiza el valor de temp al campo normal 19/05/2017
		$this->db->set('ID_MUESTRAS_MB', 'ID_MUESTRAS_MB + ID_MUESTRAS_TEMP_MB',FALSE);
		$this->db->set('ID_MUESTRAS_AQ', 'ID_MUESTRAS_AQ + ID_MUESTRAS_TEMP_AQ',FALSE);
		$this->db->set('ID_MUESTRAS_TEMP_AQ', '0',FALSE);
		$this->db->set('ID_MUESTRAS_TEMP_MB', '0',FALSE);
		$this->db->update('folios'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2
		echo 'Actualizacion de los folios temporal termino en >0 = OK [' . $this->db->count_all_results();		
	}
	/**********************************************************/
	public function reiniciar_folios() {// viene del menu principal para reinicar la tabla de folios
		$this->db->set('ID_MUESTRAS_MB', '1',FALSE);
		$this->db->set('ID_MUESTRAS_AQ', '1',FALSE);
		$this->db->set('ID_MUESTRAS_TEMP_AQ', '0',FALSE);
		$this->db->set('ID_MUESTRAS_TEMP_MB', '0',FALSE);
		$this->db->set('IDR_AQ', '0',FALSE);
		$this->db->set('IDR_MB', '0',FALSE);
		$this->db->update('folios'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2
		echo 'Folios Reiniciados [' . $this->db->affected_rows() .'] sql ='.$this->db->last_query();
		//echo 'Actualizacion de los folios temporal termino en >0 = OK [' . $this->db->count_all_results();
	}
	/* *********************************************************/
	public function reiniciar_folios_ensayos(){ // reiniciar del catalogo de estudios los folios de cada estudios
		$this->db->set('CONSECUTIVO_ESTUDIO', '0',FALSE);
		$this->db->update('estudios'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2
		echo 'Folios reiniciados en los ensayos [' . $this->db->affected_rows() .'] sql ='.$this->db->last_query();
	}
	/**********************************************************/
	public function inserta_estudio(){ // es ajax_request
		$lRet = false;
		if (isset($_POST['id']) ) { 
			$idEstudio =  $_POST['id'];		
			$this->db->where('ID_ESTUDIO',$idEstudio);		
			$query = $this->db->get('estudios');
			$row = $query->row_array();
		}
		if (isset($row)) { $nRet = json_encode($row); }
		
		echo $nRet;
		
		//echo $lRet;	
	}
	/***************************************************************/
	/***********************************************************/
	public function adjudica_muestra_solicitud(){ // IS AJAX REQUEST
		$lRet = false;
		if (isset($_POST['id_muestra'])){
			$RespData = array();
			//COMENZAMOS LA GRABADERA
			$datos = array( 'STATUS_MUESTRA'  => $_POST['status_muestra'],
							'FECHA_INICIO_REAL' => date('Y-m-d')); //PROCESANDOSE...!
			$this->db->where('ID_METODOLOGIA', $_POST['id_muestra']);
			//$this->db->update('detalle_muestras', $datos);
			
			$lRet = $this->db->set($datos)->get_compiled_update('detalle_muestras');			
			$RespData['SQL'] = $lRet;
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			$lRet = $lRet . " update = [".$this->db->affected_rows() . "]";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;
	}
	/***********************************************************/
	public function cancela_muestra_solicitud(){// es ajax reques, recibe id muestra y observacion de delete
		$lRet = false;	

		if (isset($_POST['id_muestra'])){
			//COMENZAMOS LA GRABADERA
			$datos = array( 'OBSERVACIONES_MUESTRA' => $_POST['observaciones_muestra'],
							'STATUS_MUESTRA'  => 'C'); //CANCELADA...!
			$this->db->where('ID_MUESTRA', $_POST['id_muestra']);
			// CREO FALTARIA FOLIO DE SOLICITUD ID_RECEPCION
			//$lRet = $this->db->set($datos)->get_compiled_update('recepcion_muestras');
			$lRet = $this->db->update('detalle_muestras', $datos);
			//$lRet = true;
		}
		echo $lRet;
	}
	/**********************************************************/
	public function update_encabezado_estudio(){ // es ajax reques grabar el id cliente desde la captura de la soli}
		$lRet = false;	

		if (isset($_POST['id_cliente'])){
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_CLIENTE' 		=> $_POST['id_cliente'],
							'FECHA_RECEPCION' 	=> $_POST['fecha_recepcion'],
							'ID_USUARIO'		=> $_SESSION['user_id'],
							'OBSERVACIONES_RECEPCION' => $_POST['observaciones_recepcion'],
							'TOMO_MUESTRA' => $_POST['toma_muestra']
				);
			$this->db->where('ID_RECEPCION_MUESTRA', $_POST['id_recepcion_muestra']);
			//$lRet = $this->db->set($datos)->get_compiled_update('recepcion_muestras');
			$lRet = $this->db->update('recepcion_muestras', $datos);
		}
		echo $lRet;
	}
	/****************************************/
	public function add_encabezado_estudio(){ // es ajax_reques
		$lRet = false;	

		if (isset($_POST['id_cliente'])){
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_RECEPCION_MUESTRA' 		=> $_POST['id_recepcion_muestra'],
							'ID_CLIENTE' 		=> $_POST['id_cliente'],
							'FECHA_RECEPCION' 	=> $_POST['fecha_recepcion'],
							'ID_USUARIO'		=> $_SESSION['user_id'],
							'OBSERVACIONES_RECEPCION' => $_POST['observaciones_recepcion'],
							'TOMO_MUESTRA'			=> $_POST['toma_muestra'],						
							'OTROS_SERVICIO'		=> $_POST['otros_servicio'],
							'DESTINO_MUESTRA'		=> $_POST['destino_muestra'],
							'CONDICIONES_MUESTRA'	=> $_POST['condiciones_muestra'],
							'GENERAR_IDR_MUESTRA'	=> $_POST['generar_idr_muestra']);		

			$lRet = $this->db->set($datos)->get_compiled_insert('recepcion_muestras');
			//echo $lRet;
			
			//$this->db->trans_begin();
			$lOk = $this->db->insert('recepcion_muestras', $datos);
									
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$lOk = $this->db->error();
			} else {
				$lOk = $lRet ;
				$this->db->trans_commit();
			}

			
		}
	
		echo $lRet;
	}	
	/***********************************************************/	
	public function getRowEstudio(  ){ // is ajax_reques
		$nRet = null;
		
		if (isset($_POST['id']) ) { 
			$idEstudio =  $_POST['id'];		
			$this->db->where('ID_ESTUDIO',$idEstudio);		
			$query = $this->db->get('estudios');
			$row = $query->row_array();
		}
		if (isset($row)) { $nRet = json_encode($row); }
		
		echo $nRet;		
	}
	/**************************************************************/
	public function getRowEstudioyFechaEntregaResultado(  ){ // is ajax_reques regresa id_estudioy fecha de entrega ese resultado , considerar dias inhabiles
		$nRet = null;
		
		if (isset($_POST['id']) ) { 
			$idEstudio =  $_POST['id'];		
			$this->db->where('ID_ESTUDIO',$idEstudio);		
			$query = $this->db->get('estudios');			
			$row = $query->row_array();
			// anexado 05/12/2016 checar q exista un vector que voy a inventar para controlar las veces que una misma metodologia es puesta en un mismo folio de solicitud  lo quite y lo mande a la bd

			// hay q buscar fecha disponible de donde empezar el estudio y que cumpla los n dias maximo y considerando los dias inhabiles
			$dFecInicial = date("Y-m-d");
			$dFecPaso = $dFecInicial;
			
			$nDias = 1; // ESTO PARA Q CUENTE NADA MAS 9 PORQUE EMPIEZO AGREGANDOLE 1 DIA
			while ($nDias < 10) {
				$dFecPaso= date("Y-m-d", strtotime("$dFecPaso + 1 days"));				
				$nDiaSemana = date("w",strtotime($dFecPaso));				
				if ($nDiaSemana == 1 or $nDiaSemana ==6) {}else {$nDias++;}			
			}
			// ahora hay que buscar no haya dias inhabiles		
			$dFecFinal = $dFecPaso;		
			$query = $this->db->query("select dia from dias_inhabiles where dia BETWEEN '" . date('Y-m-d', strtotime($dFecInicial)) . "' and '". date('Y-m-d', strtotime($dFecFinal)) ."'");		

			$nDiasInhabiles = $query->num_rows(); // dias inhabiles q encontro		
			$nDias = 0;
			while ($nDias<$nDiasInhabiles) {
				$dFecPaso= date("Y-m-d", strtotime("$dFecPaso + 1 days"));				
				$nDiaSemana = date("w",strtotime($dFecPaso));
				if ($nDiaSemana == 0 or $nDiaSemana ==6) {}else {$nDias++;}
			} //FIN DEL while
			
			$row = array_merge($row,array('FechaEntrega' => $dFecPaso)); // uniendo ambos array
			
			/// 16/05/2017 anexando los folio qyue debe llevar el id de metodologia			
			$query2 = $this->db->get('folios');
			$row2 = $query2 -> row_array();
			
			if ( $row['AREA_ESTUDIO'] == 'Q'){			
				$_Cpo= $row2['ID_MUESTRAS_AQ'];
				$_cFolioAux = $row2['ID_MUESTRAS_TEMP_AQ'];
			} else {
				$_Cpo= $row2['ID_MUESTRAS_MB'];
				$_cFolioAux = $row2['ID_MUESTRAS_TEMP_MB'];
			}
			
			$row = array_merge($row,array('FOLIO_MUESTRA'=>$_Cpo,'FOLIO_AUX'=>$_cFolioAux));
		}
		if (isset($row)) { 
			$nRet = json_encode($row); 			
		}		
		echo $nRet;		
	}
	/**************************************************************/	
	public function actualiza_folio_temporal() { // is ajax request , viene de al ingresar un nueva muestra, contarlas.
		if (isset($_POST['Area'])) {	
		
			//$this->db->set('ID_MUESTRAS_TEMP_AQ', 'ID_MUESTRAS_TEMP_AQ + 1',FALSE);
			//$this->db->where('id', 2);
			//$this->db->update('folios'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2
		
			if ($_POST['Area']=='AQ') {
				$this->db->set('ID_MUESTRAS_TEMP_AQ', 'ID_MUESTRAS_TEMP_AQ + 1',FALSE);
		//		$this->db->update('folios',array('ID_MUESTRAS_TEMP_AQ'=>'ID_MUESTRAS_TEMP_AQ'+1));
			} else {
				$this->db->set('ID_MUESTRAS_TEMP_MB', 'ID_MUESTRAS_TEMP_MB + 1',FALSE);
		//		$this->db->update('folios',array('ID_MUESTRAS_TEMP_MB'=>'ID_MUESTRAS_TEMP_MB'+1));
				//$this->db->select('update folios set ID_MUESTRAS_TEMP_MB = ID_MUESTRAS_TEMP_MB + 1');
			}			
			$this->db->update('folios'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2
			echo 'Actualizacion de folio temporal termino en >0 = OK [' . $this->db->count_all_results();
			//echo $this->db->get_compiled_update();
		} else {
			echo FALSE;
		}
	}	
	/**************************************************************/
	public function getPrecioEstudio(  ){ // is ajax_reques
		$nRet = 0;
		
		if (isset($_POST['id']) ) { 
			$idEstudio =  $_POST['id'];		
			$this->db->where('ID_ESTUDIO',$idEstudio);		
			$query = $this->db->get('estudios');
			$row = $query->row();
		}
		if (isset($row)) { $nRet = $row->PRECIO_ESTUDIO; }
		
		echo $nRet;		
	}
	/**********************************************************/
	public function getConsecutivoEstudio(  ){ // is ajax_reques
		$cRet = 0;
		
		if (isset($_POST['id']) ) { 
			$idEstudio =  $_POST['id'];		
			$this->db->where('ID_ESTUDIO',$idEstudio);		
			$query = $this->db->get('estudios');
			$row = $query->row();
		}
		if (isset($row)) { $cRet = $row->ALIAS_ESTUDIO . ' - '.$row->PRECIO_ESTUDIO; }		
		echo $cRet;		
	}
	/***************************************************************/
	public function getFechaEntregaEstudio(  ){ // is ajax_reques
		$dRet = 0; // se trata de buscar disponibilidad despues de los N dias transcurridos
		$hoy = date();
		$i = 0 ;
/*		//var hoy = new Date(); 
		//var i=0; 
		while (i<5) {	
		}	

		while (i<5) {  // definimos el minimo a 5 dias, luego le pongo el toope que debe de tener de la base de datos
			  hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
			  if (hoy.getDay() != 6 && hoy.getDay() != 0)
			    i++;  
			}
			mes = hoy.getMonth()+1;
			if (mes<10) mes = '0'+mes;
			fecha = hoy.getDate()+ '/' + mes + '/' + hoy.getFullYear();*/

	
		echo $dRet;		
	}
	/********************************************************/	
	public function borrar(){
		$this->load->view("borrar");
	}
	/**********************************************************/
	public function capturar_estudio( $nIdView = null){ // casi funcion principal d aurea
			$data = new stdClass();
			//$nIdView = 71;// ejemplo unicamente
			$data->title = 'Sistema de Recepcion de Muestra';
			$data->contenido = 'estudios/v_capturar_estudio'; //vista estudios			
			$data->menu_activo = 'servicios';
			$data->accion = 'ALTA';
			$data->panel_title = 'Alta de Servicio de Laboratorio';
			
			
			//2017-08-22 --> verificando que nada mas un usuario este dentro de esta pantalla..!
			$query3 = $this->db->query('select REALIZANDO_SOLICITUD from folios')->row();
			$cAliasUser = $_SESSION['alias_usuario'];
			$lAcceso = true;
			if ( !empty($query3->REALIZANDO_SOLICITUD) and $query3->REALIZANDO_SOLICITUD!= $cAliasUser)  {				
				$lAcceso = false;
			}
			
			if ($lAcceso) {
			
				if ( !is_null($nIdView))  { // si  hay un idview entoneces se trata de una edicion.
					$nLastId = $nIdView;
					$data->accion = 'EDITAR';
					$data->panel_title = 'Consulta Servicio de Laboratorio';
				}else {
					$nLastId = $this->estudios_model->getLastId('recepcion_muestras','id_recepcion_muestra')+1;
					// limpiar folios temporales de la tabla  16/05/2017
					$cpoLimpiar = array('ID_MUESTRAS_TEMP_AQ'=>0,'ID_MUESTRAS_TEMP_MB'=>0);
					$this->db->update('folios',$cpoLimpiar);
				}

				$this->load->helper('dropdown_helper');
				//                                ($table,      $name,                 $value,$orderBy=null, $where_nombre_campo=null, $where_variable=null) {
						
				$data->MetodosCombo = listData('estudios','id_estudio', 'metodologia_estudio','AREA_ESTUDIO,ALIAS_ESTUDIO');
				$data->EnsayosCombo = listData('estudios','id_estudio', 'analisis_solicitado','AREA_ESTUDIO,ALIAS_ESTUDIO');
				
				$data->last_id = $nLastId;

				$this->load->library('table');						

				$this->table->set_heading('Id Muestra', 'Id Cte.', 'Tipo Muestra','Peso/Vol.','Temp.','Lote','Id Metodo','Id','Ensayo','Método','Importe','Entregar','Acciones');

				$template2 = array(
				        'table_open'            => '<table border="1" id="tablaDetalleMuestras" class="table">',

				        'thead_open'            => '<thead>',
				        'thead_close'           => '</thead>',

				        'heading_row_start'     => '<tr>',
				        'heading_row_end'       => '</tr>',
				        'heading_cell_start'    => '<th>',
				        'heading_cell_end'      => '</th>',

				        'tbody_open'            => '<tbody>',
				        'tbody_close'           => '</tbody>',

				        'row_start'             => '<tr>',
				        'row_end'               => '</tr>',
				        'cell_start'            => '<td>',
				        'cell_end'              => '</td>',

				        'row_alt_start'         => '<tr>',
				        'row_alt_end'           => '</tr>',
				        'cell_alt_start'        => '<td>',
				        'cell_alt_end'          => '</td>',

				        'table_close'           => '</table>'
				);
				
				//$cBtn = '<button type="button" class="btn btn-info btn-xs" data-target="#myModal" onclick="DuplicaRowDetalladoEstudio(this,1) name="B2">X</button>';
				
				$template2 = array( 'table_open' => '<table border="1" id="tablaDetalleMuestras" class="table">');
				
				$this->table->set_template($template2);	

				//$this->db->select('ID_MUESTRA,ID_ASIGNADO_CLIENTE,TIPO_MUESTRA,PESO_VOL_MUESTRA,TEMPERATURA_MUESTRA,LOTE_MUESTRA,
				//ID_METODOLOGIA,estudios.ID_ESTUDIO,METODOLOGIA_ESTUDIO,IMPORTE,date_format(FECHA_SALIDA,"%Y-%m-%d"),"<button onclick=DuplicaRowDetalladoEstudio(this,1) ></button>"');
				
				$cCpo = 'ID_MUESTRA,ID_ASIGNADO_CLIENTE,TIPO_MUESTRA,PESO_VOL_MUESTRA,TEMPERATURA_MUESTRA,LOTE_MUESTRA,ID_METODOLOGIA,estudios.ID_ESTUDIO,ANALISIS_SOLICITADO,METODOLOGIA_ESTUDIO,IMPORTE,date_format(FECHA_SALIDA,"%Y-%m-%d")';
				
				$cCpo .= ',';			
				
				$cBtn = '"<button type=button data-target=#myModal name=B2 data-toggle=modal  class="'.'"btn btn-info btn-xs"'.'"   onclick="'.'"DuplicaRowDetalladoEstudio(this,0)"'.'" >Editar"';
				$cBtn .= '"</button>"';
				
				$cCpo .= $cBtn;
				
				
				$this->db->select( $cCpo);
				
				$this->db->from('detalle_muestras');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
				$this->db->where('ID_RECEPCION_MUESTRA',$nLastId);
				//$cCad = $this->db->get_compiled_select();			
				$data->clientes = $this->db->get();

				$data->estudio = $this->estudios_model->getAllEstudio( $nLastId);
				$data->sql = $this->db->last_query();
				
				$this->db->query("update folios set REALIZANDO_SOLICITUD ='".$cAliasUser."'");
				$this->load->view('plantillas/head', $data);
				$this->load->view('plantillas/header', $data);
				$this->load->view('estudios/v_capturar_estudio', $data);				
				$this->load->view('plantillas/footer', $data);
			}else {
				echo "<script type='text/javascript'>alert('Aplicacion Ocupada por [$query3->REALIZANDO_SOLICITUD]; favor de intentar mas tarde');history.back(); </script>";
			}
	}
	/*******************************************************/
	public function estudio_prueba(){
		$query=$this->db->get('estudios')->result_array() ;	
		//echo $query[1]['METODOLOGIA_ESTUDIO'];
		
		$cCad = '';
		foreach ($query as $key => $value) {
			$cCad = $cCad .  $value['METODOLOGIA_ESTUDIO'];
		}
		echo $cCad;	
	}
	/******************************************************/
	public function cargar_estudios(){ //carga todos los estudios del catalogo
		// controlador que mandara al modelo y este mande todos los estudios
		// debe ser ajax request
		$query=$this->db->get('estudios')->result_array() ;	
		$cCombo = '<option value="0">Seleccionar</option>';
		foreach ($query as $key => $value) {
			$cCombo = $cCombo .  '<option value="'.$value['ID_ESTUDIO'].'">'.$value['METODOLOGIA_ESTUDIO'].'</option>';
		}
		echo $cCombo;	
		//$cCombo = $cCombo . '<option value="1">Prueba de Estudio</option>';
		//$cCombo = $cCombo . '<option value="2" size="100">'.$cQuery.'</option>';
	}
	/******************************************************/
	public function _example_output($output = null)	{
		$this->load->view('example.php',$output);
	}
	/******************************************************/
	public function offices() 	{
		$output = $this->grocery_crud->render();
		$this->_example_output($output);
	}
	/******************************************************/
	public function index() 	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}
	/******************************************************/
	public function listar_estudios()	{
		try{
			$crud = new grocery_CRUD();
			$data = new stdClass();

			$data->title = 'Sistema de Recepcion de Muestra';
			$data->contenido = 'crud/crud'; //vista crud usuarios
			$data->panel_title = 'Listado de estudios Quimicos';
			$data->menu_activo = 'catalogos';

			//$crud->set_theme('datatables');
			$crud->set_table('estudios');

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
	/**************************************************************************/
	public function reporte_resultados(){// viene del route que viene del menu informe de resultados solicitado por calidad es su reporte principal
		$data = new stdClass();
		$this->load->helper('dropdown');

		$data->title = 'Reporte de Resultados';
		$data->contenido = 'estudios/v_reporte_resultados.php'; //vista reporte de resultados
		$data->panel_title = 'Reporte Global sobre el Informe de Resultados';
		$data->menu_activo = 'movimientos';
		$data->ClientesCombo =listData('clientes','id_cliente','nombre_cliente');
		$data->MetodosCombo = listData('estudios','id_estudio','metodologia_estudio');
		
		
		
		$this->load->library('table');
		$template2 = array(
		        'table_open' => '<table border="1" id="tablaInformeIDRGral" class="table">'
		);
					
		$this->table->set_template($template2);	
		/*
		$this->db->select('*');
		$this->db->from('det_resultado');
		$this->db->join('enc_resultado','det_resultado.ID_enc_resultado = enc_resultado.ID_enc_resultado','LEFT');			
		$this->db->where('enc_resultado.ID_DETALLE_MUESTRA',$idDetalleMuestra);
		*/
		
		$this->db->select("FOLIO_SOLICITUD, FECHA_RECEPCION,NOMBRE_CLIENTE,ANALISIS_SOLICITADO,ID_MUESTRA,trim(TIPO_MUESTRA) AS DESCRIPCION,PESO_VOL_MUESTRA,METODOLOGIA_ESTUDIO,IMPORTE,if(STATUS_MUESTRA='A','ACTIVA',STATUS_MUESTRA)");
		$this->db->from('detalle_muestras');
		$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
		$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
		$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
		//$this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',"99");
		$this->table->set_heading('Solicitud','Fecha Ingreso','Cliente','Ensayo','Id Muestra','Descripcion Muestra','Peso/Vol','Metodologia','Importe','Status') ;
		$cCad = $this->db->get_compiled_select();
		$query_result = $this->db->query($cCad)->result_array();		

		$data->resultados = $query_result;// proviene del detallado de resultados..!
		$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
		
		
		$this->load->view('frontend',$data);
	}	
	/* ************************************************************/
	public function reporte_idr_general(){ // is ajax, regresa la consulta  de las solicitudes q estan dentro de un periodo
	$cRet = 'NO HIZO NADA';
	//var_dump($_POST);
		if (isset($_POST['fecha_inicial']) ) { 
			$dFechaIni = $_POST['fecha_inicial'];
			$dFechaFin = $_POST['fecha_final'];
			
			//$this->db->select('*');
			//$this->db->from('detalle_muestras');
			
			$this->db->select("FOLIO_SOLICITUD, FECHA_RECEPCION,NOMBRE_CLIENTE,ANALISIS_SOLICITADO,ID_MUESTRA,trim(TIPO_MUESTRA) AS DESCRIPCION,if(STATUS_MUESTRA='A','ACTIVA',STATUS_MUESTRA)");
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
			$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
			
			//$this->db->join('recepcion_muestras','detallado_muestras.ID_RECEPCION_MUESTRAS =  recepcion_muestras.ID_RECEPCION_MUESTRAS');
			//$this->db->where('recepcion_muestras.FECHA_RECEPCION ')
			//$this->db->where('recepcion_muestras.FECHA_RECEPCION BETWEEN "'. date('Y-m-d', strtotime($dFechaIni)). '" and "'. date('Y-m-d', strtotime($dFechaFin)).'"');
			$cSql = $this->db->get_compiled_select();	
			$cRet = $this->db->query($cSql)->result_array();	
			
			
		}
		echo json_encode($cRet);		
	}
	/* *************************************************************/	
	public function reiniciar_ensayos() { //resetea la tabla de recepcion yn detallado de muestras
		$this->db->query('delete from detalle_muestras');
		$this->db->query('delete from recepcion_muestras');		
		echo 'Tablas de Ensayos Reiniciada..!';
	} 	
	/* *************************************************************/
	public function obtener_todos_los_analitos_acreditados(){ // is ajax, viene de idr_plagicidas ->funciones.js
		$lRet = false;
		$this->db->select('*');
		$this->db->from('analitos');
		$this->db->order_by('NOMBRE_ANALITO');
		$this->db->where('ACREDITADO_ANALITO','S');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);
		
		echo $lRet;		
	}

	/* *************************************************************/
	public function obtener_todos_los_analitos(){ // is ajax, viene de idr_plagicidas ->funciones.js
		$lRet = false;
		$this->db->select('*');
		$this->db->from('analitos');
		$this->db->order_by('NOMBRE_ANALITO');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);
		
		echo $lRet;		
	}
	/* ******************************************************************* */
	public function reiniciar_idrs(){ // is ajax
		$this->db->trans_begin();
		$this->db->query('delete from idr_det_plaguicidas');
		$this->db->query('delete from idr_enc_plaguicidas');
		$this->db->query('delete from idr_mercurio');
		$this->db->query('delete from idr_metales');
		$this->db->query('delete from idr_microbiologia');
		$this->db->query('delete from idr_aflatoxinas');
		// AHORA CON LA TABLA DE DETALLE_MUESTRAS CAMBIANDOLE SU STATUS 2017-07-17		
		$this->db->query("update detalle_muestras set STATUS_MUESTRA ='P' where STATUS_MUESTRA = 'F' ");
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$cRet = 'Error al intentar depurar una de las tablas del IDR';
		} else {
        	$this->db->trans_commit();        	
        	$cRet = 'Depuracion de las Tablas de los IDR Realizada';
        	$cRet = "<script type='text/javascript'>alert('Depuracion de las Tablas de Los IDR y anulacion en Detalla de Muestras Realizada')</script>";
        }		
        echo $cRet;
	}// fin de  funcion reiniciar idrs
	/* **********************************************************************/
	public function graba_solicitud() { // is ajax --> nueva funcion 08/08/2017 para que grabe en un solo paso el encabezado y detalle de la solicitud (aurea)
		$cRet = "";
	
		$enc = isset($_POST['enc']) ? $_POST['enc'] : false; //condicion si existe que tome la variable si no lo 
		$det = isset($_POST['det']) ? $_POST['det'] : false;	
	
		if ($enc && $det)	{				
			$RespData = array();
			
			$datos_enc = array( 'ID_RECEPCION_MUESTRA' 		=> $enc['id_recepcion_muestra'],
							'ID_CLIENTE' 		=> $enc['id_cliente'],
							'FECHA_RECEPCION' 	=> $enc['fecha_recepcion'],
							'ID_USUARIO'		=> $_SESSION['user_id'],
							'OBSERVACIONES_RECEPCION' => $enc['observaciones_recepcion'],
							'TOMO_MUESTRA'			=> $enc['toma_muestra'],						
							'OTROS_SERVICIO'		=> $enc['otros_servicio'],
							'DESTINO_MUESTRA'		=> $enc['destino_muestra'],
							'CONDICIONES_MUESTRA'	=> $enc['condiciones_muestra'],
							'GENERAR_IDR_MUESTRA'	=> $enc['generar_idr_muestra'],
							'FECHA_HORA_TOMA_MUESTRA' => $enc['fecha_toma_muestra']);									
			
			// COMENZANDO LAS TRANSACCIONES
			$this->db->trans_begin();
			$cSql = $this->db->set($datos_enc)->get_compiled_insert('recepcion_muestras');
			//$cSql = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_plaguicidas');			
			$RespData['SQL_ENC'] = 'Cad tabla recepcion_muestras:['.$cSql."] ";
			$query = $this->db->query( $cSql);
			$RespData['RESULTADO_ENC'] = $this->db->affected_rows();
			
			$RespData['SQL_ENC'] = $RespData['SQL_ENC'] .' SQL Folios:['.$this->db->last_query().")";
			
			$this->load->library('utilerias');
			
			$aCorreoQuimicos = array();
			$aCorreoMicrobiologicos = array();
			
			$detallado = $det[0];
			for ($nPos=0;$nPos<count($detallado);$nPos+=12){
				
				$id_muestra 	= $detallado[$nPos];
				$id_asig_cte 	= $detallado[$nPos+1];
				$tipo_muestra 	= $detallado[$nPos+2];
				$peso_vol 		= $detallado[$nPos+3];
				$temp 			= $detallado[$nPos+4];
				$lote 			= $detallado[$nPos+5];
				$id_metodo 		= $detallado[$nPos+6];
				$id 			= $detallado[$nPos+7]; // id del ensayo o metodologia
				$nombre_ensayo  = $detallado[$nPos+8]; // anexado 2017-11-27
				$metodo_prueba 	= $detallado[$nPos+9];
				$importe 		= $detallado[$nPos+10];
				$fec_entrega 	= $detallado[$nPos+11];			
			
				$nImporte = $this->utilerias->conv_cadena_float($importe);
				$cIdEstudio = $id;
				$query = $this->db->query("SELECT MAX(CONSECUTIVO_ESTUDIO)+1 as CONSECUTIVO from estudios where ID_ESTUDIO = ".$cIdEstudio )->row();
				$cIdMetodo = $id_metodo;
				if ($this->db->count_all_results()>0) {		
					// ncesitamos obtener los datos de am o aq + alias
					$cIdMetodo =substr($cIdMetodo, 0, strlen($cIdMetodo)-4) . str_pad($query->CONSECUTIVO,4,'0',STR_PAD_LEFT);
				}
				
				$datos_detallado = array ('ID_RECEPCION_MUESTRA'	=> $enc['id_recepcion_muestra'],
								'ID_ESTUDIO'			=> $id,//_POST['id_estudio'],
								'ID_MUESTRA'			=> $id_muestra,
								'LOTE_MUESTRA'			=> $lote,
								'ID_ASIGNADO_CLIENTE'	=> $id_asig_cte,
								'ID_METODOLOGIA'		=> $cIdMetodo, //$_POST['id_metodologia'], // 19/05/2017
								'TIPO_MUESTRA'			=> $tipo_muestra, 
								'PESO_VOL_MUESTRA'		=> $peso_vol,
								'TEMPERATURA_MUESTRA'	=> $temp,
								'IMPORTE'				=> $nImporte,
								'FECHA_SALIDA'			=> $fec_entrega); //$_POST['fecha_salida']
				$data[] = $datos_detallado;
				if (substr($id_muestra,0,2) == 'AQ') { $aCorreoQuimicos[] = $datos_detallado; }
				if (substr($id_muestra,0,2) == 'MB') { $aCorreoMicrobiologicos[] = $datos_detallado; }
				
				// Actualizamos el consecutivo del estudio
				$this->db->query('update estudios set CONSECUTIVO_ESTUDIO = '.$query->CONSECUTIVO.' where ID_ESTUDIO = '.$cIdEstudio);
				
				//ACTUALIZADNO EL STATUS DEL DETALLADO
				//$idMetodologia = $enc['id_metodologia'];
				//$this->db->query("update detalle_muestras set STATUS_MUESTRA='F' WHERE ID_METODOLOGIA = '".$cIdMetodo."'");
				
			} // fin del for
			
			$this->db->insert_batch('detalle_muestras',$data);
			$cSql = $this->db->last_query();
			$RespData['SQL_DET'] = 'Cad tabla detalle_muestras:['.$cSql."] ";
			$RespData['RESULTADO_DET'] = $this->db->affected_rows();		

			//2017-08-22 --> LIBERANDO LA APLICACION DE ESTE USUARIO PARA QUE OTROS LA PUEDA USAR.!
			$this->db->query("update folios set REALIZANDO_SOLICITUD = ''");
			
			$RespData['MENSAJE_ERROR_BD'] = "";
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$RespData['SITUACION_REGISTRO'] = 'ERROR';
				$RespData['MENSAJE_ERROR_BD'] = $this->db->_error_message();
	        	
	        } else {        
	        	$this->db->trans_commit();
	        	$RespData['SITUACION_REGISTRO'] = 'EXITO';
	        }		
			
			// ahora mandamos el correo al jefe de departamento..!
			$config = array(
				 'protocol' => 'smtp',
				 'smtp_host' => 'smtp.googlemail.com',
				 'smtp_user' => 'ebeltran@laria.mx', //Su Correo de Gmail Aqui
				 'smtp_pass' => 'inf61010', // Su Password de Gmail aqui
				 'smtp_port' => '465', //587
				 'smtp_crypto' => 'ssl', //tls
				 'mailtype' => 'html',
				 'wordwrap' => TRUE,
				 'charset' => 'utf-8'
			 );
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from('muestras@laria.mx','Control de Ensayos');
			$this->email->subject('Nueva Muestra de Analisis');			 
			
			 // AHORA PARA EL CASO DE QUIMICOS..
			if ( count($aCorreoQuimicos) >0 ) {	
				$RespData['CorreoQuimicos'] = $aCorreoQuimicos;
				 
				$cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h3>";
				$cMsg .= "<h3>Informe de Muestra Recibida</h2>";
				$cMsg .= "<br/>";
				$cMsg .= '<center>';
				$cMsg .= "<table width='80%' style='text-align:center;border: 1px solid black;border-collapse: collapse;border-spacing: 2px;'>" ;
				 
    
					$cMsg .= "<tr>";
						$cMsg .= "<th>ID MUESTRA</th>";
						$cMsg .= "<th>FECHA INICIO</th>";
						$cMsg .= "<th>FECHA FINAL ESTIMADA</th>";
						$cMsg .= "<th>ID METODOLOGIA</th>";
						$cMsg .= "<th>TIPO DE MUESTRA</th>";
						$cMsg .= "<th>METODOLOGIA</th>";
						$cMsg .= "<th>ANALISIS</th>";
					$cMsg .= "</tr>";					 	
					 	
					 	// comenzamos a recorrer el array
					foreach( $aCorreoQuimicos as $ensayo ) {
							$datos_Estudio = $this->estudios_model->getEstudioxId( $ensayo['ID_ESTUDIO']);						
						
					 		$cMsg .= "<tr>";
						 		$cMsg .= "<td>".$ensayo['ID_MUESTRA']."</td>";
						 		$cMsg .= "<td>".date('Y-m-d h:i:s')."</td>";
						 		$cMsg .= "<td>".$ensayo['FECHA_SALIDA']."</td>";
						 		$cMsg .= "<td>".$ensayo['ID_METODOLOGIA']."</td>";				 		
						 		$cMsg .= "<td>". $ensayo['TIPO_MUESTRA']."</td>";
						 		$cMsg .= "<td>". $datos_Estudio->METODOLOGIA_ESTUDIO . "</td>";
						 		$cMsg .= "<td>". $datos_Estudio->ANALISIS_SOLICITADO . "</td>";					 		
					 		$cMsg .= "</tr>";				 		
					 		
					 	} // FIN DEL FOREACH 
					 	
				$cMsg .= "</table>";
				$cMsg .= "<br/><br/>";				 
				$cMsg .= '</center>';

				$cMsg .= "<br/><br/>";
				$cMsg .= "<p>Capturista:<br/> [<strong>" . $_SESSION['user_nombre']. "</strong>]</p><br/>";
					 
				$cMsg = $cMsg . "<a href='". base_url('login') ."'>Acceso al Sistema</a>";
				$cMsg = $cMsg . '<br/><br/>';
				//$cMsg = $cMsg . '<small><small>Creditos: M.C. Efraín Beltrán Orozco<br>
				//correo:ebeltranorozco@hotmail.com<br>Cel:667-183-28-95</small></small>';
				 	
				$this->email->message($cMsg);
				$this->email->to('analisis_quimicos@laria.mx','SRM LARIA');
				$this->email->cc(array('calidad@laria.mx','sistemas@laria.mx'));		 	
				 	
				$this->email->send(FALSE);
				$RespData['errorEmailQuimicos'] = $this->email->print_debugger();				 	
			} // fin de if ($aCorreoQuimicos)
			/* COMPARANDO SI EXISTEN MUESTRAS QUE PERTENESCAN PARA EL AREA DE ENSAYOS MICROBIOLOGICOS */
			if ( count($aCorreoMicrobiologicos) >0 ) {	
				$RespData['CorreoMicrobiologicos'] = $aCorreoMicrobiologicos;
				 
				$cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h3>";
				$cMsg .= "<h3>Informe de Muestra Recibida</h2>";
				$cMsg .= "<br/>";
				$cMsg .= '<center>';				
				$cMsg .= "<table width='80%' style='text-align:center;border: 1px solid black;border-collapse: collapse;border-spacing: 2px;'>" ;
					$cMsg .= "<tr>";
						$cMsg .= "<th>ID MUESTRA</th>";
						$cMsg .= "<th>FECHA INICIO</th>";
						$cMsg .= "<th>FECHA FINAL ESTIMADA</th>";
						$cMsg .= "<th>ID METODOLOGIA</th>";
						$cMsg .= "<th>TIPO DE MUESTRA</th>";
						$cMsg .= "<th>METODOLOGIA</th>";
						$cMsg .= "<th>ANALISIS</th>";
					$cMsg .= "</tr>";					 	
					 	
					 	// comenzamos a recorrer el array
					foreach( $aCorreoMicrobiologicos as $ensayo ) {
							$datos_Estudio = $this->estudios_model->getEstudioxId( $ensayo['ID_ESTUDIO']);						
						
					 		$cMsg .= "<tr>";
						 		$cMsg .= "<td>".$ensayo['ID_MUESTRA']."</td>";
						 		$cMsg .= "<td>".date('Y-m-d h:i:s')."</td>";
						 		$cMsg .= "<td>".$ensayo['FECHA_SALIDA']."</td>";
						 		$cMsg .= "<td>".$ensayo['ID_METODOLOGIA']."</td>";				 		
						 		$cMsg .= "<td>". $ensayo['TIPO_MUESTRA']."</td>";
						 		$cMsg .= "<td>". $datos_Estudio->METODOLOGIA_ESTUDIO . "</td>";
						 		$cMsg .= "<td>". $datos_Estudio->ANALISIS_SOLICITADO . "</td>";					 		
					 		$cMsg .= "</tr>";				 		
					 		
					 	} // FIN DEL FOREACH 
					 	
				$cMsg .= "</table>";
				$cMsg .= "<br/><br/>";				 
				$cMsg .= '</center>';

				$cMsg .= "<br/><br/>";
				$cMsg .= "<p>Capturista:<br/> [<strong>" . $_SESSION['user_nombre']. "</strong>]</p><br/>";
					 
				$cMsg = $cMsg . "<a href='". base_url('login') ."'>Acceso al Sistema</a>";
				$cMsg = $cMsg . '<br/><br/>';
				 	
				$this->email->message($cMsg);
				$this->email->to('analisis_microbiologicos@laria.mx','SRM LARIA');
				$this->email->cc(array('calidad@laria.mx','sistemas@laria.mx'));		 	
				 	
				$this->email->send(FALSE);
				$RespData['errorEmailMicro'] = $this->email->print_debugger();				 	
			} // fin de if ($aCorreoQuimicos)		
			
					
	        
	        header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
			echo $lRet;
			
		} // fin de if ($enc && $det)	
		
		
	}// fin de funcion graba_solicitud
	
	/* **************************************************************************/
	public function modifica_solicitud() { // is ajax --> nueva funcion 08/08/2017 para que grabe en un solo paso el encabezado y detalle de la solicitud (aurea)
		$cRet = "";
	
		$enc = isset($_POST['enc']) ? $_POST['enc'] : false; //condicion si existe que tome la variable si no lo 
		$det = isset($_POST['det']) ? $_POST['det'] : false;	
	
		if ($enc && $det)	{				
			$RespData = array();
			
			$datos_enc = array( 'ID_RECEPCION_MUESTRA' 		=> $enc['id_recepcion_muestra'],
							'ID_CLIENTE' 		=> $enc['id_cliente'],
							//'FECHA_RECEPCION' 	=> $enc['fecha_recepcion'],
							'ID_USUARIO'		=> $_SESSION['user_id'],
							'OBSERVACIONES_RECEPCION' => $enc['observaciones_recepcion'],
							'TOMO_MUESTRA'			=> $enc['toma_muestra'],						
							'OTROS_SERVICIO'		=> $enc['otros_servicio'],
							'DESTINO_MUESTRA'		=> $enc['destino_muestra'],
							'CONDICIONES_MUESTRA'	=> $enc['condiciones_muestra'],
							'GENERAR_IDR_MUESTRA'	=> $enc['generar_idr_muestra']);									
			
			// COMENZANDO LAS TRANSACCIONES
			$this->db->trans_begin();
			$this->db->where( 'ID_RECEPCION_MUESTRA',$enc['id_recepcion_muestra']);
			$cSql = $this->db->set($datos_enc)->get_compiled_update('recepcion_muestras');
			//$cSql = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_plaguicidas');			
			$RespData['SQL_ENC'] = 'Cad tabla recepcion_muestras:['.$cSql."] ";
			$query = $this->db->query( $cSql);
			$RespData['RESULTADO_ENC'] = $this->db->affected_rows();
			
			$RespData['SQL_ENC'] = $RespData['SQL_ENC'] .' SQL Folios:['.$this->db->last_query().")";
					
			$this->load->library('utilerias');
			
			$aCorreoQuimicos = array();
			$aCorreoMicrobiologicos = array();
			
			$detallado = $det[0];
			$RespData['SQL_DET'] = "";
			$RespData['RESULTADO_DET'] = "";
			
			for ($nPos=0;$nPos<count($detallado);$nPos+=11){
				
				$id_muestra 	= $detallado[$nPos];
				$id_asig_cte 	= $detallado[$nPos+1];
				$tipo_muestra 	= $detallado[$nPos+2];
				$peso_vol 		= $detallado[$nPos+3];
				$temp 			= $detallado[$nPos+4];
				$lote 			= $detallado[$nPos+5];
				$id_metodo 		= $detallado[$nPos+6];// este es la variable indice que usare
				$id 			= $detallado[$nPos+7];
				//$metodo_prueba 	= $detallado[$nPos+8];
				//$importe 		= $detallado[$nPos+9];
				//$fec_entrega 	= $detallado[$nPos+10];			
			
				/*
				$nImporte = $this->utilerias->conv_cadena_float($importe);
				$cIdEstudio = $id;
				$query = $this->db->query("SELECT MAX(CONSECUTIVO_ESTUDIO)+1 as CONSECUTIVO from estudios where ID_ESTUDIO = ".$cIdEstudio )->row();
				$cIdMetodo = $id_metodo;
				if ($this->db->count_all_results()>0) {		
					// ncesitamos obtener los datos de am o aq + alias
					$cIdMetodo =substr($cIdMetodo, 0, strlen($cIdMetodo)-4) . str_pad($query->CONSECUTIVO,4,'0',STR_PAD_LEFT);
				}
				*/				
				$datos_detallado = array (						
								'ID_MUESTRA'			=> $id_muestra,								
								'ID_ASIGNADO_CLIENTE'	=> $id_asig_cte,								
								'TIPO_MUESTRA'			=> $tipo_muestra, 
								'PESO_VOL_MUESTRA'		=> $peso_vol,
								'TEMPERATURA_MUESTRA'	=> $temp,
								'LOTE_MUESTRA'			=> $lote,
								'ID_ESTUDIO'			=> $id,
								'ID_METODOLOGIA'		=> $id_metodo);
				$data[] = $datos_detallado;
				if (substr($id_muestra,0,2) == 'AQ') { $aCorreoQuimicos[] = $datos_detallado; }
				if (substr($id_muestra,0,2) == 'MB') { $aCorreoMicrobiologicos[] = $datos_detallado; }
				
				//2017-08-14 --> Grabamos uno a uno cada id_metodologia
				$this->db->where('ID_METODOLOGIA',$id_metodo);
				$cSql = $this->db->set($datos_detallado)->get_compiled_update('detalle_muestras');
				//$cSql = $this->db->last_query();
				$query = $this->db->query($cSql );
				$RespData['SQL_DET'] .= 'Cad tabla detalle_muestras:['.$cSql."] <br>";
				$RespData['RESULTADO_DET'] .= $this->db->affected_rows();				
				
				// Actualizamos el consecutivo del estudio
				//$this->db->query('update estudios set CONSECUTIVO_ESTUDIO = '.$query->CONSECUTIVO.' where ID_ESTUDIO = '.$cIdEstudio);
				
				//ACTUALIZADNO EL STATUS DEL DETALLADO
				//$idMetodologia = $enc['id_metodologia'];
				//$this->db->query("update detalle_muestras set STATUS_MUESTRA='F' WHERE ID_METODOLOGIA = '".$cIdMetodo."'");
				
			} // fin del for
			
			//$this->db->insert_batch('detalle_muestras',$data);
			//$cSql = $this->db->last_query();
			//$RespData['SQL_DET'] = 'Cad tabla detalle_muestras:['.$cSql."] ";
			//$RespData['RESULTADO_DET'] = $this->db->affected_rows();
			
			//2017-08-15 --< AHORA QUEDA GRABAR EN LA NUEVA TABLA DE CORRECCIONES_SOLICITUD
			$correciones = array( 'ID_USUARIO'=> $_SESSION['user_id'],
					'ID_RECEPCION_MUESTRA'=>$enc['id_recepcion_muestra'],
					'CAUSA_CORRECCION'=>$enc['justificacion_edicion']);
			$cSqlEdit = $this->db->set($correciones)->get_compiled_insert('correcciones_solicitud');
			$query = $this->db->query($cSqlEdit);
			$RespData['SQL_Correcion_Solicitud'] = $cSqlEdit;
			$RespData['ExitoCorrecion >0 = OK ['] = $this->db->affected_rows();
			
			
			// AHORA GRABAMOS TODA LA INFORMACION
			if ($this->db->trans_status() === FALSE) {
				$RespData['SITUACION_REGISTRO'] = 'ERROR';
	        	$this->db->trans_rollback();
	        } else {        
	        	$this->db->trans_commit();
	        	//$this->db->trans_rollback();
	        	$RespData['SITUACION_REGISTRO'] = 'EXITO';
	        }
					
			
			// ahora mandamos el correo al jefe de departamento..!
			$config = array(
				 'protocol' => 'smtp',
				 'smtp_host' => 'smtp.googlemail.com',
				 'smtp_user' => 'ebeltran@laria.mx', //Su Correo de Gmail Aqui
				 'smtp_pass' => 'inf61010', // Su Password de Gmail aqui
				 'smtp_port' => '465', //587
				 'smtp_crypto' => 'ssl', //tls
				 'mailtype' => 'html',
				 'wordwrap' => TRUE,
				 'charset' => 'utf-8'
			 );
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from('muestras@laria.mx','Control de Ensayos');
			$this->email->subject('Nueva Muestra de Analisis');			 
			
			 // AHORA PARA EL CASO DE QUIMICOS..
			if ( count($aCorreoQuimicos) >0 ) {	
				$RespData['CorreoQuimicos'] = $aCorreoQuimicos;
				 
				$cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h3>";
				$cMsg .= "<h3>Informe de Muestra Recibida (Correcciones)</h2>";
				$cMsg .= "<br/>";
				$cMsg .= '<center>';
				$cMsg .= '<p>Justificación:['.$enc['justificacion_edicion'].']</p>';
				$cMsg .= "<table width='80%' style='text-align:center;'>" ;
					$cMsg .= "<tr>";
						$cMsg .= "<th>ID MUESTRA</th>";
						//$cMsg .= "<th>FECHA INICIO</th>";
						//$cMsg .= "<th>FECHA FINAL ESTIMADA</th>";
						$cMsg .= "<th>ID METODOLOGIA</th>";
						$cMsg .= "<th>TIPO DE MUESTRA</th>";
						$cMsg .= "<th>METODOLOGIA</th>";
						$cMsg .= "<th>ANALISIS</th>";
					$cMsg .= "</tr>";					 	
					 	
					 	// comenzamos a recorrer el array
					foreach( $aCorreoQuimicos as $ensayo ) {
							$datos_Estudio = $this->estudios_model->getEstudioxId( $ensayo['ID_ESTUDIO']);						
						
					 		$cMsg .= "<tr>";
						 		$cMsg .= "<td>".$ensayo['ID_MUESTRA']."</td>";
						 		//$cMsg .= "<td>".date('Y-m-d h:i:s')."</td>";
						 		//$cMsg .= "<td>".$ensayo['FECHA_SALIDA']."</td>";
						 		//$cMsg .= "<td>".'  /  /    '."</td>";
						 		$cMsg .= "<td>".$ensayo['ID_METODOLOGIA']."</td>";				 		
						 		$cMsg .= "<td>". $ensayo['TIPO_MUESTRA']."</td>";
						 		$cMsg .= "<td>". $datos_Estudio->METODOLOGIA_ESTUDIO . "</td>";
						 		$cMsg .= "<td>". $datos_Estudio->ANALISIS_SOLICITADO . "</td>";					 		
					 		$cMsg .= "</tr>";				 		
					 		
					 	} // FIN DEL FOREACH 
					 	
				$cMsg .= "</table>";
				$cMsg .= "<br/><br/>";				 
				$cMsg .= '</center>';

				$cMsg .= "<br/><br/>";
				$cMsg .= "<p>Capturista:<br/> [<strong>" . $_SESSION['user_nombre']. "</strong>]</p><br/>";
					 
				$cMsg = $cMsg . "<a href='". base_url('login') ."'>Acceso al Sistema</a>";
				$cMsg = $cMsg . '<br/><br/>';
				//$cMsg = $cMsg . '<small><small>Creditos: M.C. Efraín Beltrán Orozco<br>
				//correo:ebeltranorozco@hotmail.com<br>Cel:667-183-28-95</small></small>';
				 	
				$this->email->message($cMsg);
				$this->email->to('analisis_quimicos@laria.mx','SRM LARIA');
				$this->email->cc('calidad@laria.mx','sistemas@laria.mx');		 	
				 	
				$this->email->send(FALSE);
				$RespData['errorEmailQuimicos'] = $this->email->print_debugger();				 	
			} // fin de if ($aCorreoQuimicos)
			/* COMPARANDO SI EXISTEN MUESTRAS QUE PERTENESCAN PARA EL AREA DE ENSAYOS MICROBIOLOGICOS */
			if ( count($aCorreoMicrobiologicos) >0 ) {	
				$RespData['CorreoMicrobiologicos'] = $aCorreoMicrobiologicos;
				 
				$cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h3>";
				$cMsg .= "<h3>Informe de Muestra Recibida (Correcciones)</h2>";
				$cMsg .= "<br/>";
				$cMsg .= '<center>';
				$cMsg .= '<p>Justificación:['.$enc['justificacion_edicion'].']</p>';
				$cMsg .= "<table width='80%' style='text-align:center;'>" ;
					$cMsg .= "<tr>";
						$cMsg .= "<th>ID MUESTRA</th>";
						//$cMsg .= "<th>FECHA INICIO</th>";
						//$cMsg .= "<th>FECHA FINAL ESTIMADA</th>";
						$cMsg .= "<th>ID METODOLOGIA</th>";
						$cMsg .= "<th>TIPO DE MUESTRA</th>";
						$cMsg .= "<th>METODOLOGIA</th>";
						$cMsg .= "<th>ANALISIS</th>";
					$cMsg .= "</tr>";					 	
					 	
					 	// comenzamos a recorrer el array
					foreach( $aCorreoMicrobiologicos as $ensayo ) {
							$datos_Estudio = $this->estudios_model->getEstudioxId( $ensayo['ID_ESTUDIO']);						
						
					 		$cMsg .= "<tr>";
						 		$cMsg .= "<td>".$ensayo['ID_MUESTRA']."</td>";
						 		//$cMsg .= "<td>".date('Y-m-d h:i:s')."</td>";
						 		//$cMsg .= "<td>".$ensayo['FECHA_SALIDA']."</td>";
						 		$cMsg .= "<td>".$ensayo['ID_METODOLOGIA']."</td>";				 		
						 		$cMsg .= "<td>". $ensayo['TIPO_MUESTRA']."</td>";
						 		$cMsg .= "<td>". $datos_Estudio->METODOLOGIA_ESTUDIO . "</td>";
						 		$cMsg .= "<td>". $datos_Estudio->ANALISIS_SOLICITADO . "</td>";					 		
					 		$cMsg .= "</tr>";				 		
					 		
					 	} // FIN DEL FOREACH 
					 	
				$cMsg .= "</table>";
				$cMsg .= "<br/><br/>";				 
				$cMsg .= '</center>';

				$cMsg .= "<br/><br/>";
				$cMsg .= "<p>Capturista:<br/> [<strong>" . $_SESSION['user_nombre']. "</strong>]</p><br/>";
					 
				$cMsg = $cMsg . "<a href='". base_url('login') ."'>Acceso al Sistema</a>";
				$cMsg = $cMsg . '<br/><br/>';
				 	
				$this->email->message($cMsg);
				$this->email->to('analisis_microbiologicos@laria.mx','SRM LARIA');
				$this->email->cc('calidad@laria.mx','sistemas@laria.mx');		 	
				 	
				$this->email->send(FALSE);
				$RespData['errorEmailMicro'] = $this->email->print_debugger();				 	
			} // fin de if ($aCorreoQuimicos)		
			
			
			
					
	        
	        header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
			echo $lRet;
			
		} // fin de if ($enc && $det)	
		
		
	}// fin de funcion graba_solicitud
	/* **************************************************************************/
	public function libera_formato_solicitud() { // is ajax, libera al usuario q tiene detenido el formulario de aurea
		$cUserAlias = $_SESSION['alias_usuario'];
		$query4 = $this->db->query('select REALIZANDO_SOLICITUD from folios')->row();
		if ($cUserAlias == $query4->REALIZANDO_SOLICITUD) {
			$this->db->query("update folios set REALIZANDO_SOLICITUD = ''");
			echo "<script>alert('Liberando el Formulario de Captura de la Solicitud')</script>";	
		}	
		
	}
	/*  ***************************************************************************/
	public function obtener_todos_los_metales_acreditados(){ // is ajax, viene de idr_plagicidas ->funciones.js					
		$lRet = false;
		$this->db->select('*');
		$this->db->from('metales');
		$this->db->order_by('NOMBRE_METAL');
		$this->db->where('ACREDITADO_METAL','S');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);
		
		echo $lRet;		
	}

	/* *************************************************************/
	public function obtener_todos_los_metales(){ // is ajax, viene de idr_plagicidas ->funciones.js
		$lRet = false;
		$this->db->select('*');
		$this->db->from('metales');
		$this->db->order_by('ACREDITADO_METAL DESC,NOMBRE_METAL');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);
		
		echo $lRet;		
	}	
	/*************************************************************************/
	public function obtener_todos_los_analitos_x_metodo_lc(){ // is ajax, viene de idr_plagicidas ->funciones.js
		$lRet = false;
		$this->db->select('*');
		$this->db->from('analitos');
		$this->db->order_by('NOMBRE_ANALITO');		
		$this->db->like( 'TECNICA_ANALITO','LC');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);		
		echo $lRet;		
	}
	/***************************************************************************/
	public function obtener_todos_los_analitos_x_metodo_gc(){ // is ajax, viene de idr_plagicidas ->funciones.js
		$lRet = false;
		$this->db->select('*');
		$this->db->from('analitos');
		$this->db->order_by('NOMBRE_ANALITO');
		$this->db->like( 'TECNICA_ANALITO','GC');
		$lRet = $this->db->get();
		$RespData = array();
		$RespData['SQL'] = $this->db->last_query();
		$RespData['RESULTADO'] = $lRet->result();
		
		header('Content-type: application/json; charset=utf-8');
		$lRet = json_encode($RespData);		
		echo $lRet;		
	}
	/**************************************************************/
}