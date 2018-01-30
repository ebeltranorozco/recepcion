<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class idr extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('clientes_model');
		$this->load->model('estudios_model');
		$this->load->database();
		if (!$_SESSION['user_id']){
				redirect('/login');
		}
	}
/********************************* IDR PLAGICIDAS *************************************/
	public function idr_plaguicidas( $idMetodologia = null, $idMuestra = null){ // es el primer reporte que se va diseñar!
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_plaguicidas'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
			$this->load->library('table');
			//$this->table->set_heading('mg/kg','LC (mg/kg)','C.H.','C.A.');
			$this->table->set_heading('Analito','Resultado','LC (mg/kg)','LMP (mg/kg)','Tecnica','Acciones');
			$template2 = array(
			        'table_open' => '<table border="1" id="idTablaIDRPlaguicidas" class="table">'
			);
			$this->table->set_template($template2);
			
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_enc_plaguicidas');
			$this->db->join('idr_det_plaguicidas','idr_enc_plaguicidas.id_enc_plaguicidas = idr_det_plaguicidas.id_enc_plaguicidas ');			
			$this->db->where('idr_enc_plaguicidas.ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();
			
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA					
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;

			// OBTENIENDO LOS ANALITOS...!
			$data->analitos = $this->db->query('select * from analitos')->result();
			$this->load->helper('dropdown');		
			//  function listData($table,$name,$value,$orderBy=null, $where_nombre_campo=null, $where_variable) {        
			$data->AnalitosCombo = listData('analitos','id_analito', 'nombre_analito',null,null,null);
			
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');

			if (count($query_result)>0)  {
				$data->accion = 'EDICION';
				//2017-09-06 --> para la tabla nomas se ocupan ciertos campos
				//'Analito','Resultado','LC (mg/kg)','LMP (mg/kg)','Tecnica','Acciones');
				
				$cCpo = "ANALITO_PLAGUICIDAS,RESULTADO_ANALITO_PLAGUICIDAS,LC_PLAGUICIDAS,LMP_PLAGUICIDAS,TECNICA_PLAGUICIDAS";				
				$cCpo .= ',';				
				//$cBtn = '"<button type=button data-target=#myModal name=B2 data-toggle=modal  class="'.'"btn btn-info btn-xs"'.'"   onclick="'.'"DuplicaRowDetalladoEstudio(this,0)"'.'" >Editar"';
				//$cBtn = '"<button type=button name=B3 class="'.'"btn btn-danger btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"EliminaAnalitoTabla(this)"'.'" >Eliminar"';
				
				$cBtn = '"<button type=button name=B3 class="'.'"btn btn-info btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"EditaRowDetalladoIdrPlagicidas(this)"'.'" >Editar"';
				$cBtn .= '"</button>"';
				$cBtn .= '"<button type=button name=B3 class="'.'"btn btn-danger btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"deleteRowDetalladoIdrPlagicidas(this)"'.'" >Eliminar"';				
				$cBtn .= '"</button>"';
				
				
				$cCpo .= $cBtn;
				
				
				$this->db->select( $cCpo);
				
				//$this->db->select("ANALITO_PLAGUICIDAS,RESULTADO_ANALITO_PLAGUICIDAS,LC_PLAGUICIDAS,LMP_PLAGUICIDAS,TECNICA_PLAGUICIDAS,''");//05/06/2017
				$this->db->from('idr_enc_plaguicidas');
				$this->db->join('idr_det_plaguicidas','idr_enc_plaguicidas.id_enc_plaguicidas = idr_det_plaguicidas.id_enc_plaguicidas ');			
				$this->db->where('idr_enc_plaguicidas.ID_METODOLOGIA',$idMetodologia);

				$cCad2 = $this->db->get_compiled_select();
				$query_result2 = $this->db->query($cCad2)->result_array();
				
				
				//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
				
				$data->resultados_det = $query_result2;// proviene del detallado de resultados..!
				$data->sql2 = $cCad2; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			}
			//2017-07-06
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_plaguicidas', $data);
			$this->load->view('plantillas/footer', $data);
			
			
		} // fin id !is_null( idmetodologia)
		
		
	} // fin de IDR_PLAGUICIDAS
/**************************************************************************************/
	public function plagicidas() { // is ajax	que graba el IDR DE PLAGUICIDAS
	$cRet = "";
	
	$enc = isset($_POST['enc']) ? $_POST['enc'] : false; //condicion si existe que tome la variable si no lo asigna false
	$det = isset($_POST['det']) ? $_POST['det'] : false;
	//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones,'tecnica_plaguicidas':tecnica};
		
	if ($enc && $det)	{		
		// comenzamos a grabar el encabezado del idr de plaguicidas		
		$RespData = array();
		//var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'fecha_plaguicidas':fechafinal,'iniciales_analista_plaguicidas':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas},det:[]}
		//var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,				'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,				'condiciones_plaguicidas':condiciones, 'analisis_solicitado_plaguicidas':analisis,				'metodo_prueba_plaguicidas':metodo,'fecha_plaguicidas':fechafinal,				'tecnica_plaguicidas':tecnica,'id_usuario_signatario':idUserSignatario},det:[]}	
		//$idIDR =$_POST['enc']['idIDR'];
		
		$idIDR =$enc['id_idr'];
		//2017-08-28		
		$idMetodologia = $enc['id_metodologia'];
		//2017-08-21
		$cIdUserSignatario = $enc['id_usuario_signatario'];
		$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
		$RespData['SQL_Signatario'] = $this->db->last_query();
		//2017-08-21 --> DETERMINAMOS MEDIANTE LA VAR NUEVA ACCION QUE DEBE REALIZAR
		$accion = $enc['accion'];
		
		$datos_enc = array( 'ID_IDR'  			=> $enc['id_idr'],
			'ID_MUESTRA'  						=> $enc['id_muestra'],
			'ID_METODOLOGIA'  					=> $enc['id_metodologia'],
			'ANALISIS_SOLICITADO_PLAGUICIDAS'  	=> $enc['analisis_solicitado_plaguicidas'],
			'ID_USUARIO_SIGNATARIO'				=> $enc['id_usuario_signatario'],
			'METODO_PRUEBA_PLAGUICIDAS'  		=> $enc['metodo_prueba_plaguicidas'],
			'REFERENCIA_PLAGUICIDAS'  			=> $enc['referencia_plaguicidas'],
			'OBSERVACION_PLAGUICIDAS'  			=> $enc['observacion_plaguicidas'],
			'CONDICIONES_PLAGUICIDAS'  			=> $enc['condiciones_plaguicidas'],		
			'TECNICO_PLAGUICIDAS'				=> $query->NOMBRE_USUARIO,
			'FECHA_FINAL_PLAGUICIDAS'			=> $enc['fecha_final_plaguicidas'],
			'CARGO_TECNICO_PLAGUICIDAS' 		=> $query->CARGO_USUARIO,
			'INICIALES_ANALISTA_PLAGUICIDAS'	=> $enc['iniciales_analista_plaguicidas'],			
			'ID_USUARIO_CAPTURISTA'				=> $_SESSION['user_id']);			
		
		// COMENZANDO LAS TRANSACCIONES
		$this->db->trans_begin();
		
		if ($accion == 'ALTA') {
			$lRet = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_plaguicidas');				
		}
		if ($accion == 'EDICION') {
			$this->db->where('ID_METODOLOGIA', $idMetodologia);
			$lRet = $this->db->set($datos_enc)->get_compiled_update('idr_enc_plaguicidas');
		}
		
		//$cSql = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_plaguicidas');			
		$RespData['SQL_ENC'] = 'Cad tabla idr_enc_plaguicidas:['.$lRet."] ";
		$query = $this->db->query( $lRet);
		$RespData['RESULTADO_ENC'] = $this->db->affected_rows();
		
		if ($accion == 'ALTA') {
			if ($RespData['RESULTADO_ENC'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
					// obtenemos el ultimo id ingresado..!
				$id_plaguicidas = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas mediante el update de los datos ya insertados..!
					$this->db->update('idr_enc_plaguicidas',array('ID_IDR'=>$idIDR),'ID_PLAGUICIDAS = '.$id_plaguicidas);					
				}
			}	
		}
		
		
		if ($accion == 'EDICION'){
			//2017-09-06 --> Eliminando los analitos grabados anteriormente
			$cID_Plaguicidas = $this->db->query("select ID_ENC_PLAGUICIDAS from idr_enc_plaguicidas where ID_METODOLOGIA = '".$idMetodologia."'")->row();
			if ($this->db->affected_rows()>0) {
				//2017-09-06 -->para el caso de edicion hay que borrar a todos analitos registrados y anexar los de la tabla como nuevos
				$id_plaguicidas = $cID_Plaguicidas->ID_ENC_PLAGUICIDAS;		
				if ($id_plaguicidas>0){
					$this->db->query("delete from idr_det_plaguicidas where ID_ENC_PLAGUICIDAS = '".$id_plaguicidas."'" );
					$accion = 'ALTA';
					$RespData['Analitos_previos_borrados'] = 'SI';
					$RespData['Analitos_borrados'] = $this->db->affected_rows();
				}else {
					$RespData['Analitos_previos_borrados'] = 'NO /SQL EJECUTADA: '.$this->db->last_query();
				}
			}			
			
			// 2017-07-25 --> actualizar la base de correcciones
			$datos2 = array('ID_USUARIO'			=> $_SESSION['user_id'],
							'ID_METODOLOGIA'		=> $enc['id_metodologia'],
							'REFERENCIA_TABLA'		=> 'IDR_PLAGUICIDAS',
							'CAUSAS_CORRECCION'		=> $enc['causas_correccion']	);
			$lRet2 = $this->db->set($datos2)->get_compiled_insert( 'correcciones_idr');				
			$query2 = $this->db->query( $lRet2);
			$RespData['RESULTADO MODIFICACION'] = $this->db->affected_rows();			
		}
					
		$RespData['SQL_ENC'] = $RespData['SQL_ENC'] .' SQL Folios:['.$this->db->last_query().")";
		$detallado = $det[0];		
		
		
		for ($nPos=0;$nPos<count($detallado);$nPos+=5){
			
			$cAnalito 	= $detallado[$nPos];		
			$cResultado = $detallado[$nPos+1];
			$cLC 		= $detallado[$nPos+2];
			$cLMP 		= $detallado[$nPos+3];
			$cTecnica 	= $detallado[$nPos+4];
			
			$otra_data = array( 
				'ID_ENC_PLAGUICIDAS'			=> $id_plaguicidas,
				'ANALITO_PLAGUICIDAS'			=> $cAnalito,
				'RESULTADO_ANALITO_PLAGUICIDAS' => $cResultado,
				'LC_PLAGUICIDAS'				=> $cLC,
				'LMP_PLAGUICIDAS'				=> $cLMP,
				'TECNICA_PLAGUICIDAS'			=> $cTecnica
				//'ACREDITADO_ANALITO'			
			);			
			//array_push( $data, $otra_data);	
			$data[] = $otra_data;
		} // fin del for		
		//print_r($data);
		
		
		
		
		
		
			
		if ($accion == 'ALTA') {
			$this->db->insert_batch('idr_det_plaguicidas',$data);
			$cSql = $this->db->last_query();
			$RespData['SQL_DET'] = 'Cad tabla idr_det_plaguicidas:['.$cSql."] ";
			$RespData['RESULTADO_DET'] = $this->db->affected_rows();
		
			//ACTUALIZADNO EL STATUS DEL DETALLADO
			$idMetodologia = $enc['id_metodologia'];
			$this->db->query("update detalle_muestras set STATUS_MUESTRA='G' WHERE ID_METODOLOGIA = '$idMetodologia'");
		} // fin del alta
			
		if ($this->db->trans_status() === FALSE) {
			$RespData['SITUACION_REGISTRO'] = 'ERROR';
	       	$this->db->trans_rollback();
	    } else {        
	       	$this->db->trans_commit();
	       	$RespData['SITUACION_REGISTRO'] = 'EXITO';
	    }		
	       
	        
	    	header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
			echo $lRet;
		}// fin del if enc y det	

	} // fin de la funcion ajax
	/* ************************************************************************/
	public function plaguicidas_old(){ // is ajax
		$lRet = false;
		//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones,'tecnica_plaguicidas':tecnica};
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_IDR'  => $_POST['idIDR'],
							'ID_MUESTRA'  => $_POST['id_muestra'],
							'ID_METODOLOGIA'  => $_POST['id_metodologia'],
							'ANALISIS_SOLICITADO_PLAGUICIDAS'  => $_POST['analisis_solicitado_plaguicidas'],							
							'METODO_PRUEBA_PLAGUICIDAS'  => $_POST['metodo_prueba_plaguicidas'],
							'REFERENCIA_PLAGUICIDAS'  => $_POST['referencia_plaguicidas'],
							'OBSERVACION_PLAGUICIDAS'  => $_POST['observacion_plaguicidas'],
							'CONDICIONES_PLAGUICIDAS'  => $_POST['condiciones_plaguicidas'],
							'TECNICA_PLAGUICIDAS'		=> $_POST['tecnica_plaguicidas'],
							'TECNICO_AFLATOXINAS' 	=> $_SESSION['user_nombre'],
							'CARGO_TECNICO_AFLATOXINAS' => $_SESSION['cargo_usuario']);
			
			$lRet = $this->db->set($datos)->get_compiled_insert('idr_enc_plaguicidas');			
			$RespData['SQL'] = 'Cad tabla idr_enc_plaguicidas:['.$lRet."] ";
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
				// obtenemos el ultimo id ingresado..!
				$id_plaguicidas = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas mediante el update de los datos ya insertados..!
					$this->db->update('idr_enc_plaguicidas',array('ID_IDR'=>$idIDR),'ID_PLAGUICIDAS = '.$id_plaguicidas);
					
				}
			}			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;		
	}
/* ***************************** IDR MICROBIOLOGIA-> previo impresion */
/********************************IMPRESORION DE LOS IDR  DE MICRO ***************************************/	
/********************************IMPRESORION DE LOS IDR  DE MICRO ***************************************/	
/********************************IMPRESORION DE LOS IDR  DE MICRO ***************************************/	
	public function idr_microbiologia($idMetodologia = null, $idMuestra = null, $idEstudio = null ){
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_microbiologia'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;			
			
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_microbiologia');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->row();
			
			$data->resultados = $query_result;// por si encuentra algo
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA			
			$query_result2 = $this->db->query("select IDR_MB from folios ")->row();
			$data->folios = $query_result2;
			
			if (count($query_result)>0)  {
				$data->accion = 'CONSULTA';
			}
			
			//2017-07-20
			$this->load->helper('dropdown');
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','M');
			
			//30/06/2017 --> para este informe se requiere obtener del catalogo de estudios el analisis solicitado y metodo de referencia
			//$this->datos_cata_ensayo = $this->db->query('select * from estudios where ID_ESTUDIO = $idEstudio')->row();
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			//$this->load->view('idr/v_idr_microbiologia', $data);
			$this->load->view('idr/v_idr_microbiologia2', $data); // -->2018-01-17 --> anexar col total/fecal y e coli dentro de la columna resultado
			$this->load->view('plantillas/footer', $data);			
		} // fin de !is_null( idmetodologia)		
	} // fin de idr_microbiologicos	
/****************FIN DEL IDR DE MICROBIOLOGICOS***********************************/	
	public function graba_o_corrige_idr_microbiologia(){ // is ajax es para que grabe el idr de microbiologia graba_idr_microbiologia y ademas ahora actualiza  2017-08-21
		$lRet = false;		
		//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario  };	
		//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas  };
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			$idMetodologia = $_POST['id_metodologia'];
			//2017-08-21
			$cIdUserSignatario = $_POST['id_usuario_signatario'];
			$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
			$RespData['SQL_Signatario'] = $this->db->last_query();
			//2017-08-21 --> DETERMINAMOS MEDIANTE LA VAR NUEVA ACCION QUE DEBE REALIZAR
			$accion = $_POST['accion'];
			//COMENZAMOS LA GRABADERA
			
			$datos = array( 'ID_IDR'  							=> $_POST['idIDR'],
							'ID_MUESTRA'  						=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  					=> $_POST['id_metodologia'],//'ID_USUARIO_ANALISTA'	=> $_SESSION['user_id'],
							'ID_USUARIO_SIGNATARIO'				=> $_POST['id_usuario_signatario'],
							'REFERENCIA_MICROBIOLOGIA'  		=> $_POST['referencia_microbiologia'],
							'OBSERVACION_MICROBIOLOGIA'  		=> $_POST['observacion_microbiologia'],
							'CONDICIONES_MICROBIOLOGIA'  		=> $_POST['condiciones_microbiologia'],
							'ANALISIS_SOLICITADO_MICROBIOLOGIA' => $_POST['analisis_solicitado_microbiologia'],
							'RESULTADO_MICROBIOLOGIA'  			=> $_POST['resultado_microbiologia'],
							'METODO_PRUEBA_MICROBIOLOGIA'  		=> $_POST['metodo_prueba_microbiologia'],
							'TECNICO_MICROBIOLOGIA' 			=> $query->NOMBRE_USUARIO,
							'FECHA_FINAL_MICROBIOLOGIA' 				=> $_POST['fecha_microbiologia'],
							'CARGO_TECNICO_MICROBIOLOGIA' 		=> $query->CARGO_USUARIO,
							'INICIALES_ANALISTA_MICROBIOLOGIA' 	=> $_POST['iniciales_analista_microbiologia'],
							'ID_USUARIO_CAPTURISTA'				=> $_SESSION['user_id'],
							'RESULTADO_COLIFORMES_TOTALES_MICROBIOLOGIA'=> $_POST['coliformes_totales_resultado'],
							'RESULTADO_COLIFORMES_FECALES_MICROBIOLOGIA'=> $_POST['coliformes_fecales_resultado'],
							'RESULTADO_ECOLI_MICROBIOLOGIA'				=> $_POST['ecoli_resultado']);
			
			$this->db->trans_begin(); //COMENZAMOS LAS TRANSACCIONES
			
			if ($accion == 'ALTA') {
				$lRet = $this->db->set($datos)->get_compiled_insert('idr_microbiologia');				
			}
			if ($accion == 'EDICION') {
				//$this->db->where('ID_MICROBIOLOGIA', $_POST['id_aflatoxinas']);
				$this->db->where('ID_METODOLOGIA', $idMetodologia);
				$lRet = $this->db->set($datos)->get_compiled_update('idr_microbiologia');
			}
			
			$RespData['SQL'] = 'Cad tabla idr_microbiologia:['.$lRet."] ";			
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			
			if ($accion == 'ALTA') {
				if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
					// obtenemos el ultimo id ingresado..!
					$id_microbiologia = $this->db->insert_id();
					$aFolio = $this->db->query('select IDR_MB from folios')->row();
					if ($aFolio->IDR_MB == ($idIDR -1) ) { // procedimiento normal
						$this->db->set(array('IDR_MB'=>$idIDR))->update('folios');
					}else {
						// ya me ganaron el id hay que buscar otro..
						$nFolioIDR = $this->db->query('SELECT max( `IDR_MB` ) + 1 as folio FROM folios')->row();
						$idIDR = $nFolioIDR->folio;
						$this->db->set(array('IDR_MB'=>$idIDR))->update('folios');
						//actualizar el idr en microbiologia
						$this->db->update('idr_microbiologia',array('ID_IDR'=>$idIDR),'ID_MICROBIOLOGIA = '.$id_microbiologia);
					}
				}
			} // FIN DE ACCION = ALTA
			
			if ($accion == 'EDICION'){
				// 2017-07-25 --> actualizar la base de correcciones
				$datos2 = array('ID_USUARIO'			=> $_SESSION['user_id'],
								'ID_METODOLOGIA'		=> $_POST['id_metodologia'],
								'REFERENCIA_TABLA'		=> 'IDR_MICROBIOLOGIA',
								'CAUSAS_CORRECCION'		=> $_POST['causas_correccion']	);
				$lRet2 = $this->db->set($datos2)->get_compiled_insert( 'correcciones_idr');				
				$query2 = $this->db->query( $lRet2);
				$RespData['RESULTADO MODIFICACION'] = $this->db->affected_rows();			
			}
			
			if ($this->db->trans_status() === FALSE) {
				$RespData['SITUACION_REGISTRO'] = 'ERROR';
        		$this->db->trans_rollback();
        	} else {
        		$this->db->trans_commit();
        		$RespData['SITUACION_REGISTRO'] = 'EXITO';
	        }			
			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;		
	} // termina el ajax de IDR microbiologia
	/** ******************************************************	*/
	public function correciones_idr_microbiologia($idMetodologia = null,$idMuestra = null){ //es para poder hacer las correcciones a los informes viene del crud
		//2017-08-21
		$data = new stdClass();		
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_microbiologia'; 
		$data->accion = 'EDICION';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
						
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_microbiologia');			
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->row();
			
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA
			
			$query_result2 = $this->db->query("select IDR_MB from folios ")->row();
			$data->folios = $query_result2;
			
			
			if (count($query_result)==0)  {
				$data->accion = 'ALTA';
			}
			
			//2017-07-07
			$this->load->helper('dropdown');
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','M');			
					
			
			//2017-07-17			
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	 // deb de contener valor siendo en edicion
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_microbiologia', $data);
			$this->load->view('plantillas/footer', $data);	
			
		} // fin id !is_null( idmetodologia)
	}
		
/********************************IMPRESORION DE LOS IDR DE QUIMICOS ***************************************/	
/********************************IMPRESORION DE LOS IDR DE QUIMICOS ***************************************/	
/********************************IMPRESORION DE LOS IDR DE QUIMICOS ***************************************/	
	public function idr_aflatoxinas( $idMetodologia = null, $idMuestra = null){ // es el primer reporte que se va diseñar!
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_aflatoxinas'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
						
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_aflatoxinas');			
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();		
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA
			
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;
			
			
			if (count($query_result)>0)  {
				$data->accion = 'CONSULTA';
			}
			//2017-07-07
			$this->load->helper('dropdown');
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');
			
			
			//2017-07-17			
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	
				
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_aflatoxinas', $data);
			$this->load->view('plantillas/footer', $data);		
			
		} // fin id !is_null( idmetodologia)
		
	} // fin de idr_aflaxotinas
/******************************************************************/	
	public function aflatoxinas(){ // is ajax graba el idr de aflatoxinas es el boton que se puslsa de v_idr_aflatoxinas
		$lRet = false;
		
		//var datos={ 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observaciones_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado};
		//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observacion_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado,'id_usuario_signatario':idUserSignatario,'fecha_aflatoxinas':idFechaFinal,'iniciales_analista_aflatoxinas':iniciales_analista  };
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			$cIdUserSignatario = $_POST['id_usuario_signatario'];
			//COMENZAMOS LA GRABADERA
			
			//2017-08-17
			$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
			$RespData['SQL_Signatario'] = $this->db->last_query();
			$datos = array( 'ID_IDR'  							=> $_POST['idIDR'],
							'ID_MUESTRA'  						=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  					=> $_POST['id_metodologia'],//'ID_USUARIO_ANALISTA'=> $_SESSION['user_id'],
							'ID_USUARIO_SIGNATARIO' 			=> $_POST['id_usuario_signatario'],
							'ANALISIS_SOLICITADO_AFLATOXINAS'  	=> $_POST['analisis_solicitado_aflatoxinas'],
							'G1_AFLATOXINAS'  					=> $_POST['g1_aflatoxinas'],
							'G2_AFLATOXINAS'  					=> $_POST['g2_aflatoxinas'],
							'B1_AFLATOXINAS'  					=> $_POST['b1_aflatoxinas'],
							'B2_AFLATOXINAS'  					=> $_POST['b2_aflatoxinas'],
							'LC_AFLATOXINAS'  					=> $_POST['lc_aflatoxinas'],
							'CH_AFLATOXINAS'  					=> $_POST['ch_aflatoxinas'],
							'CA_AFLATOXINAS'  					=> $_POST['ca_aflatoxinas'],
							'METODO_PRUEBA_AFLATOXINAS'  		=> $_POST['metodo_prueba_aflatoxinas'],
							'REFERENCIA_AFLATOXINAS'  			=> $_POST['referencia_aflatoxinas'],
							'OBSERVACION_AFLATOXINAS'  			=> $_POST['observacion_aflatoxinas'],
							'CONDICIONES_AFLATOXINAS'  			=> $_POST['condiciones_aflatoxinas'],
							'RESULTADO_AFLATOXINAS'  			=> $_POST['resultado_aflatoxinas'],							
							'TECNICO_AFLATOXINAS' 				=> $query->NOMBRE_USUARIO,
							'INICIALES_ANALISTA_AFLATOXINAS' 	=> $_POST['iniciales_analista_aflatoxinas'], // cambio
							'CARGO_TECNICO_AFLATOXINAS' 		=> $query->CARGO_USUARIO,
							'ID_USUARIO_CAPTURISTA'				=> $_SESSION['user_id'],
							'FECHA_FINAL_AFLATOXINAS'			=> $_POST['fecha_aflatoxinas']); // ANEXADO 2017-08-17
			
			$lRet = $this->db->set($datos)->get_compiled_insert('idr_aflatoxinas');			
			$RespData['SQL'] = 'Cad tabla idr_aflatoxinas:['.$lRet."] ";
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
				// obtenemos el ultimo id ingresado..!
				$id_aflatoxinas = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$RespData['comentario']	= 'Folio del Informe de Aflatoxinas ['.$idIDR.'] duplicado ';
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$RespData['comentario']	.= ' se va usar ['.$idIDR.'] en su lugar; como nuevo folio';
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas
					$this->db->update('idr_aflatoxinas',array('ID_IDR'=>$idIDR),'ID_AFLATOXINAS = '.$id_aflatoxinas);					
				}
			}			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;		
	} // fin de function aflatoxinas
/* ***********************************************************/
	public function correcciones_idr_aflatoxinas($idMetodologia = null,$idMuestra = null){ //es para poder hacer las correcciones a los informes
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_aflatoxinas'; 
		$data->accion = 'EDICION';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
						
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_aflatoxinas');			
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();
			
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA
			
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;
			
			
			if (count($query_result)==0)  {
				$data->accion = 'ALTA';
			}
			
			//2017-07-07
			$this->load->helper('dropdown');
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');
			//$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario',array('tipo_usuario'=>'Q','signatario_usuario'=>'S');
			
					
			
			//2017-07-17			
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	 // deb de contener valor siendo en edicion
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_aflatoxinas', $data);
			$this->load->view('plantillas/footer', $data);	
			
		} // fin id !is_null( idmetodologia)
	} // fin de function correciones_idr_aflatoxinas
	
/******************************************************************/
	public function idr_mercurio($idMetodologia = null, $idMuestra = null, $idEstudio = null ){
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_mercurio'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;			
			
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_mercurio');			
			
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();		
			
			
			$data->resultados = $query_result;// por si encuentra algo
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA			
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;
			
			if (count($query_result)>0)  {
				$data->accion = 'CONSULTA';
			}			
			
			//30/06/2017 --> para este informe se requiere obtener del catalogo de estudios el analisis solicitado y metodo de referencia
			//$this->datos_cata_ensayo = $this->db->query('select * from estudios where ID_ESTUDIO = $idEstudio')->row();
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;
			
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_mercurio', $data);
			$this->load->view('plantillas/footer', $data);			
		} // fin de !is_null( idmetodologia)		
	} // fin de idr_mercurio
/*  ********************************************************************************/	
/*  *******************************************************************/
	public function mercurio (){ // is ajax (VA A DESAPARECER)
		$lRet = false;		
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_IDR'  => $_POST['idIDR'],
							'ID_MUESTRA'  => $_POST['id_muestra'],
							'ID_METODOLOGIA'  => $_POST['id_metodologia'],
							'ANALISIS_SOLICITADO_MERCURIO'  => $_POST['analisis_solicitado_mercurio'],
							'RESULTADO_MERCURIO'  => $_POST['resultado_mercurio'],
							'LC_MERCURIO '				=> $_POST['lc_mercurio'],
							'LMP_MERCURIO'				=> $_POST['lmp_mercurio'],
							'TECNICA_MERCURIO'			=> $_POST['tecnica_mercurio'],
							'METODO_PRUEBA_MERCURIO'  => $_POST['metodo_prueba_mercurio'],
							'REFERENCIA_MERCURIO'  => $_POST['referencia_mercurio'],
							'OBSERVACION_MERCURIO'  => $_POST['observacion_mercurio'],
							'CONDICIONES_MERCURIO'  => $_POST['condiciones_mercurio'],							
							'TECNICO_MERCURIO' 	=> $_SESSION['user_nombre'],
							'CARGO_TECNICO_MERCURIO' => $_SESSION['cargo_usuario'],
							'INICIALES_ANALISTA_MERCURIO'	=>$_POST['iniciales_analista_mercurio'],
							'FECHA_FINAL_MERCURIO'			=>$_POST['fecha_final_mercurio']);			
								
			$lRet = $this->db->set($datos)->get_compiled_insert('idr_mercurio');			
			$RespData['SQL'] = 'Cad tabla idr_mercurio:['.$lRet."] ";
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
				// obtenemos el ultimo id ingresado..!
				$id_mercurio = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas
					$this->db->update('idr_mercurio',array('ID_IDR'=>$idIDR),'ID_MERCURIO = '.$id_mercurio);
				}
			}			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;			
	} // fin de idr mercurio ajax
/************************************************************************************************/
/************************************************************************************************/	
	public function idr_metales($idMetodologia = null, $idMuestra = null, $idEstudio = null ){
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_metales'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;			
			
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_metales');			
			
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();		
			
			
			$data->resultados = $query_result;// por si encuentra algo
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA			
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;
			
			if (count($query_result)>0)  {
				$data->accion = 'CONSULTA';
			}			
			
			//30/06/2017 --> para este informe se requiere obtener del catalogo de estudios el analisis solicitado y metodo de referencia
			//$this->datos_cata_ensayo = $this->db->query('select * from estudios where ID_ESTUDIO = $idEstudio')->row();
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;
			
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_metales', $data);
			$this->load->view('plantillas/footer', $data);			
		} // fin de !is_null( idmetodologia)		
	} // fin de idr_metales
/*  ********************************************************************************/		
/** ***************************************/	
	public function metales (){ // fin de metales is ajax
		$lRet = false;		
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			//COMENZAMOS LA GRABADERA		
			$datos = array( 'ID_IDR'  						=> $_POST['idIDR'],
							'ID_MUESTRA'  					=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  				=> $_POST['id_metodologia'],
							'ANALISIS_SOLICITADO_METALES'  	=> $_POST['analisis_solicitado_metales'],
							'METODO_PRUEBA_METALES'  		=> $_POST['metodo_prueba_metales'],
							'REFERENCIA_METALES'  			=> $_POST['referencia_metales'],
							'RESULTADO_COBRE' 				=> $_POST['resultado_cobre'],
							'LC_COBRE' 						=> $_POST['lc_cobre'],
							'LMP_COBRE' 					=> $_POST['lmp_cobre'],
							'TECNICA_COBRE' 				=> $_POST['tecnica_cobre'],
							'RESULTADO_MANGANESO' 			=> $_POST['resultado_manganeso'],
							'LC_MANGANESO' 					=> $_POST['lc_manganeso'],
							'LMP_MANGANESO' 				=> $_POST['lmp_manganeso'],
							'TECNICA_MANGANESO' 			=> $_POST['tecnica_manganeso'],
							'RESULTADO_NIQUEL' 				=> $_POST['resultado_niquel'],
							'LC_NIQUEL' 					=> $_POST['lc_niquel'],
							'LMP_NIQUEL' 					=> $_POST['lmp_niquel'],
							'TECNICA_NIQUEL' 				=> $_POST['tecnica_niquel'],
							'OBSERVACION_METALES'  			=> $_POST['observacion_metales'],
							'CONDICIONES_METALES'  			=> $_POST['condiciones_metales'],							
							'TECNICO_METALES' 				=> $_SESSION['user_nombre'],
							'CARGO_TECNICO_METALES' 		=> $_SESSION['cargo_usuario'],
							'INICIALES_ANALISTA_METALES'	=> $_POST['iniciales_analista_metales'],
							'FECHA_FINAL_METALES'			=> $_POST['fecha_final_metales']);			
								
			$lRet = $this->db->set($datos)->get_compiled_insert('idr_metales');			
			$RespData['SQL'] = 'Cad tabla idr_metales:['.$lRet."] ";
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
				// obtenemos el ultimo id ingresado..!
				$id_metales = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas
					$this->db->update('idr_metales',array('ID_IDR'=>$idIDR),'ID_METALES = '.$id_metales);
				}
			}			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;			
	} // fin de idr mercurio	
/** ***************************************/	
	
/** ***************************************/		
	public function actualiza_idr_aflatoxinas(){ //is ajax
		$lRet = false;
		
		//var datos={ 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observaciones_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado};
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			$cIdUserSignatario = $_POST['id_usuario_signatario'];
			//COMENZAMOS LA GRABADERA
			
			//2017-08-17
			$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
			$RespData['SQL_Signatario'] = $this->db->last_query();
			$dFechaAflatoxinas = date("Y-m-d",strtotime($_POST['fecha_aflatoxinas'])); // 2017-08-18 --> paraque me permita grabar la fecha en la base de datos mysql
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_IDR'  							=> $_POST['idIDR'],
							'ID_MUESTRA'  						=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  					=> $_POST['id_metodologia'],//'ID_USUARIO_ANALISTA' => $_SESSION['user_id'],
							'ID_USUARIO_SIGNATARIO' 			=> $_POST['id_usuario_signatario'],
							'ANALISIS_SOLICITADO_AFLATOXINAS'  	=> $_POST['analisis_solicitado_aflatoxinas'],
							'G1_AFLATOXINAS'  					=> $_POST['g1_aflatoxinas'],
							'G2_AFLATOXINAS'  					=> $_POST['g2_aflatoxinas'],
							'B1_AFLATOXINAS'  					=> $_POST['b1_aflatoxinas'],
							'B2_AFLATOXINAS'  					=> $_POST['b2_aflatoxinas'],
							'LC_AFLATOXINAS'  					=> $_POST['lc_aflatoxinas'],
							'CH_AFLATOXINAS'  					=> $_POST['ch_aflatoxinas'],
							'CA_AFLATOXINAS'  					=> $_POST['ca_aflatoxinas'],
							'METODO_PRUEBA_AFLATOXINAS'  		=> $_POST['metodo_prueba_aflatoxinas'],
							'REFERENCIA_AFLATOXINAS'  			=> $_POST['referencia_aflatoxinas'],
							'OBSERVACION_AFLATOXINAS'  			=> $_POST['observacion_aflatoxinas'],
							'CONDICIONES_AFLATOXINAS'  			=> $_POST['condiciones_aflatoxinas'],
							'RESULTADO_AFLATOXINAS'  			=> $_POST['resultado_aflatoxinas'],							
							'TECNICO_AFLATOXINAS' 				=> $query->NOMBRE_USUARIO,
							'INICIALES_ANALISTA_AFLATOXINAS' 	=> $_POST['iniciales_analista_aflatoxinas'], // cambio
							'CARGO_TECNICO_AFLATOXINAS' 		=> $query->CARGO_USUARIO,
							'ID_USUARIO_CAPTURISTA'				=> $_SESSION['user_id'],
							'FECHA_AFLATOXINAS'					=> $dFechaAflatoxinas); // ANEXADO 2017-08-17			
			
			$this->db->where('ID_AFLATOXINAS', $_POST['id_aflatoxinas']);
			$lRet = $this->db->set($datos)->get_compiled_update('idr_aflatoxinas');			
			$RespData['SQL'] = 'Cad tabla idr_aflatoxinas:['.$lRet."] ";
			
			$this->db->trans_begin(); //COMENZAMOS LAS TRANSACCIONES			
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			$RespData['SQL'] = $RespData['SQL'] .' SQL LastQuery:['.$this->db->last_query()."] ";
			
			// 2017-07-25 --> actualizar la base de correcciones
			$datos2 = array('ID_USUARIO'			=> $_SESSION['user_id'],
							'ID_METODOLOGIA'		=> $_POST['id_metodologia'],
							'REFERENCIA_TABLA'		=> 'IDR_AFLATOXINAS',
							'CAUSAS_CORRECCION'		=> $_POST['causas_correccion']	);
			$lRet2 = $this->db->set($datos2)->get_compiled_insert( 'correcciones_idr');
			// AQUI ME QUEDE
			$query2 = $this->db->query( $lRet2);
			$RespData['RESULTADO MODIFICACION'] = $this->db->affected_rows();			
			
			if ($this->db->trans_status() === FALSE) {
				$RespData['SITUACION_REGISTRO'] = 'ERROR';
        		$this->db->trans_rollback();
        	} else {
        		$this->db->trans_commit();
        		$RespData['SITUACION_REGISTRO'] = 'EXITO';
	        }
			
			$RespData['SQL'] = $RespData['SQL'] .' SQL LastQuery:['.$this->db->last_query()."] ";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;		
	}	
/********************************* IDR PLAGICIDAS *************************************/
	public function envia_correo( ){ // IS AJAX REQUEST		
		$lRet = false;
		//var datos = { 'id_metodologia': idMetodo , 'status_muestra':'F','nombre_idr':nombre_idr};
		if (isset($_POST['id_metodologia'])){
			$cIdMetodologia = $_POST['id_metodologia'];
			$cNombreIDR = $_POST['nombre_idr'];
			$RespData = array();
			//COMENZAMOS LA GRABADERA
			$datos = array( 'STATUS_MUESTRA'  => $_POST['status_muestra']);  // FINALIZADA
			//$datos = array( 'STATUS_MUESTRA'  => 'G');  // FINALIZADA
			$this->db->where('ID_METODOLOGIA', $cIdMetodologia );
			$lRet = $this->db->set($datos)->get_compiled_update('detalle_muestras');			
			$RespData['SQL'] = $lRet;
			$query = $this->db->query( $lRet);
			$RespData['RESULTADO'] = $this->db->affected_rows();
			$lRet = $lRet . " update = [".$this->db->affected_rows() . "]";
			
			if ($cNombreIDR == 'AFLATOXINAS') {
				$this->db->select('detalle_muestras.ID_MUESTRA,FECHA_RECEPCION,FECHA_INICIO_REAL,FECHA_FINAL_AFLATOXINAS AS FECHA_FINAL_IDR,detalle_muestras.ID_METODOLOGIA,TIPO_MUESTRA,METODOLOGIA_ESTUDIO,INICIALES_ANALISTA_AFLATOXINAS AS INICIALES_ANALISTA,NOMBRE_USUARIO');
				$this->db->from('detalle_muestras');
				$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
				$this->db->join('idr_aflatoxinas','idr_aflatoxinas.ID_METODOLOGIA = detalle_muestras.ID_METODOLOGIA');
				$this->db->join('usuarios','usuarios.ID_USUARIO = idr_aflatoxinas.ID_USUARIO_SIGNATARIO');
			}elseif ($cNombreIDR == 'PLAGUICIDAS') {
				$this->db->select('detalle_muestras.ID_MUESTRA,FECHA_RECEPCION,FECHA_INICIO_REAL,FECHA_FINAL_PLAGUICIDAS AS FECHA_FINAL_IDR,detalle_muestras.ID_METODOLOGIA,TIPO_MUESTRA,METODOLOGIA_ESTUDIO,INICIALES_ANALISTA_PLAGUICIDAS AS INICIALES_ANALISTA,NOMBRE_USUARIO');
				$this->db->from('detalle_muestras');
				$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');				
				$this->db->join('idr_enc_plaguicidas','idr_enc_plaguicidas.ID_METODOLOGIA = detalle_muestras.ID_METODOLOGIA');
				$this->db->join('usuarios','usuarios.ID_USUARIO = idr_enc_plaguicidas.ID_USUARIO_SIGNATARIO');
			}elseif ($cNombreIDR == 'MICROBIOLOGIA'){
				$this->db->select('detalle_muestras.ID_MUESTRA,FECHA_RECEPCION,FECHA_INICIO_REAL,FECHA_FINAL_MICROBIOLOGIA AS FECHA_FINAL_IDR,detalle_muestras.ID_METODOLOGIA,TIPO_MUESTRA,METODOLOGIA_ESTUDIO,INICIALES_ANALISTA_MICROBIOLOGIA AS INICIALES_ANALISTA,NOMBRE_USUARIO');
				$this->db->from('detalle_muestras');
				$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');				
				$this->db->join('idr_microbiologia','idr_microbiologia.ID_METODOLOGIA = detalle_muestras.ID_METODOLOGIA');
				$this->db->join('usuarios','usuarios.ID_USUARIO = idr_microbiologia.ID_USUARIO_SIGNATARIO');
			}elseif ($cNombreIDR == 'MERCURIO'){
				$this->db->select('detalle_muestras.ID_MUESTRA,FECHA_RECEPCION,FECHA_INICIO_REAL,FECHA_FINAL_MERCURIO AS FECHA_FINAL_IDR,detalle_muestras.ID_METODOLOGIA,TIPO_MUESTRA,METODOLOGIA_ESTUDIO,INICIALES_ANALISTA_MERCURIO AS INICIALES_ANALISTA,NOMBRE_USUARIO');
				$this->db->from('detalle_muestras');
				$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');				
				$this->db->join('idr_mercurio','idr_mercurio.ID_METODOLOGIA = detalle_muestras.ID_METODOLOGIA');
				$this->db->join('usuarios','usuarios.ID_USUARIO = idr_mercurio.ID_USUARIO_SIGNATARIO');
			}elseif ($cNombreIDR == 'METALES'){
				$this->db->select('detalle_muestras.ID_MUESTRA,FECHA_RECEPCION,FECHA_INICIO_REAL,FECHA_FINAL_METALES AS FECHA_FINAL_IDR,detalle_muestras.ID_METODOLOGIA,TIPO_MUESTRA,METODOLOGIA_ESTUDIO,INICIALES_ANALISTA_METALES AS INICIALES_ANALISTA,NOMBRE_USUARIO');
				$this->db->from('detalle_muestras');
				$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
				$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');				
				$this->db->join('idr_metales','idr_metales.ID_METODOLOGIA = detalle_muestras.ID_METODOLOGIA');
				$this->db->join('usuarios','usuarios.ID_USUARIO = idr_metales.ID_USUARIO_SIGNATARIO');
			}
			
			$this->db->where('detalle_muestras.ID_METODOLOGIA',$cIdMetodologia);
			$cSql = $this->db->get_compiled_select();			
			$query = $this->db->query($cSql)->row();
			$RespData['sqlOK'] = $cSql;
			$RespData['queryOK'] = $query;
			
			//2017-08-16 --> MANDANDO EL CORREO 
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
			$this->email->subject('IDR Finalizado');
			
			//$cMsg = "<h2>Laboratorio Regional de Inocuidad Alimentaria del Estado de Sinaloa</h2>";
			$cMsg = "<h2>SRM LARIA</h2>";
			
			$cMsg .= "<h3>Notificación de IDR Finalizado</h3>";
			$cMsg .= "<h4>Fecha:[".date('Y-m-d')."]</h4>";
			$cMsg .= "<br/>";
			$cMsg .= '<center>';
			$cMsg .= "<table width='80%' style='text-align:center;border: 1px solid black;border-collapse: collapse;'>" ;
				$cMsg .= "<tr>";
					$cMsg .= "<th>ID MUESTRA</th>";
					$cMsg .= "<th>FECHA SOLICITUD</th>";
					$cMsg .= "<th>FECHA INICIO ANALISIS</th>";
					$cMsg .= "<th>FECHA FINAL ANALISIS</th>";
					$cMsg .= "<th>ID METODOLOGÍA</th>";
					$cMsg .= "<th>TIPO MUESTRA</th>";
					$cMsg .= "<th>METODOLOGIA</th>";
					//$cMsg .= "<th>USUARIO RECIBIÓ</th>";
					$cMsg .= "<th>USUARIO ANALIZÓ</th>";
					$cMsg .= "<th>USUARIO FIRMÓ</th>";					
				$cMsg .= "</tr>";	// comenzamos a obtener los valores del query
		 		$cMsg .= "<tr>";
			 		$cMsg .= "<td>".$this->utilerias->Personaliza_ID_MUESTRA($query->ID_MUESTRA)."</td>";
			 		// $this->utilerias->Personaliza_ID_MUESTRA
			 		//$dFechaSolicitud = date('Y-m-d', strtotime("$registro->FECHA_RECEPCION + 3 day"));
			 		$cMsg .= "<td>".date('Y-m-d',strtotime($query->FECHA_RECEPCION))."</td>";
			 		$cMsg .= "<td>".date('Y-m-d',strtotime($query->FECHA_INICIO_REAL))."</td>";
			 		
			 		$cMsg .= "<td>".date('Y-m-d',strtotime($query->FECHA_FINAL_IDR))."</td>";
			 		
			 		$cMsg .= "<td>".$query->ID_METODOLOGIA."</td>";
			 		$cMsg .= "<td>".$query->TIPO_MUESTRA."</td>";
			 		$cMsg .= "<td>".$query->METODOLOGIA_ESTUDIO."</td>";
			 		
			 		$cMsg .= "<td>".$query->INICIALES_ANALISTA."</td>";
			 		$cMsg .= "<td>".$query->NOMBRE_USUARIO."</td>";
			 		
			 		
		 		$cMsg .= "</tr>";				 		
					 	
				$cMsg .= "</table>";
				$cMsg .= "<br/><br/>";				 
				$cMsg .= '</center>';
				 	
				$this->email->message($cMsg);
				$this->email->to('calidad@laria.mx','SRM LARIA');
				$this->email->cc('sistemas@laria.mx');		 	
				 	
				$this->email->send(FALSE);
				//$RespData['errorEmailQuimicos'] = $this->email->print_debugger();				 	
			
			
				// FIN DE MANDAR EL CORREO
			
				header('Content-type: application/json; charset=utf-8');
				$lRet = json_encode($RespData);	
			
		} // fin del if isset idMetodologia
		echo $lRet;		
	} // fin de funcion VO BO IDR_PLAGUICIDAS	
/* *********************************************************************/
	public function actualiza_status_metodologia(){ // is ajax request
		$IdMetogologia = $_GET['idMetodologia'];		
		$datos = array( 'STATUS_MUESTRA'  => 'G'); //IDR GENERADO...!
		$this->db->where('ID_METODOLOGIA', $IdMetogologia);			
		$cSql = $this->db->set($datos)->get_compiled_update('detalle_muestras');			
		$this->db->query( $cSql);		
		echo $cSql;
	}
/* **********************************************************************/
	public function idr_mercurio2($idMetodologia = null, $idMuestra = null, $idEstudio = null ){
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_microbiologia'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;			
			
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_mercurio');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->row();
			
			$data->resultados = $query_result;// por si encuentra algo
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA			
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;
			
			if (count($query_result)>0)  {
				$data->accion = 'EDICION';
			}
			
			//2017-07-20
			$this->load->helper('dropdown');
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');
			
			//30/06/2017 --> para este informe se requiere obtener del catalogo de estudios el analisis solicitado y metodo de referencia
			
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_mercurio2', $data);
			$this->load->view('plantillas/footer', $data);			
		} // fin de !is_null( idmetodologia)		
	} // fin de idr_microbiologicos	
/****************FIN DEL IDR DE MICROBIOLOGICOS***********************************/	
	public function graba_o_corrige_idr_mercurio(){ // is ajax es para que grabe el idr de microbiologia graba_idr_microbiologia y ademas ahora actualiza  2017-08-21
		$lRet = false;		
		//var datos={'idIDR':idIDR,'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas  };	
		//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas  };
		
		if (isset($_POST['id_metodologia'])){
			$RespData = array();
			$idIDR =$_POST['idIDR'];
			$idMetodologia = $_POST['id_metodologia'];
			//2017-08-21
			$cIdUserSignatario = $_POST['id_usuario_signatario'];
			$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
			$RespData['SQL_Signatario'] = $this->db->last_query();
			//2017-08-21 --> DETERMINAMOS MEDIANTE LA VAR NUEVA ACCION QUE DEBE REALIZAR
			$accion = $_POST['accion'];
			//COMENZAMOS LA GRABADERA
			$datos = array( 'ID_IDR'  						=> $_POST['idIDR'],
							'ID_MUESTRA'  					=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  				=> $_POST['id_metodologia'],
							'ANALISIS_SOLICITADO_MERCURIO'  => $_POST['analisis_solicitado_mercurio'],
							'ID_USUARIO_SIGNATARIO'			=> $_POST['id_usuario_signatario'],
							'RESULTADO_MERCURIO'  			=> $_POST['resultado_mercurio'],
							'LC_MERCURIO '					=> $_POST['lc_mercurio'],
							'LMP_MERCURIO'					=> $_POST['lmp_mercurio'],
							'TECNICA_MERCURIO'				=> $_POST['tecnica_mercurio'],
							'METODO_PRUEBA_MERCURIO'  		=> $_POST['metodo_prueba_mercurio'],
							'REFERENCIA_MERCURIO'  			=> $_POST['referencia_mercurio'],
							'OBSERVACION_MERCURIO'  		=> $_POST['observacion_mercurio'],
							'CONDICIONES_MERCURIO'  		=> $_POST['condiciones_mercurio'],							
							'TECNICO_MERCURIO' 				=> $query->NOMBRE_USUARIO,
							'CARGO_TECNICO_MERCURIO' 		=> $query->CARGO_USUARIO,
							'INICIALES_ANALISTA_MERCURIO'	=> $_POST['iniciales_analista_mercurio'],
							'FECHA_FINAL_MERCURIO'			=> $_POST['fecha_final_mercurio'],
							'ID_USUARIO_CAPTURISTA'			=> $_SESSION['user_id']);			
			/*					
			$datos = array( 'ID_IDR'  							=> $_POST['idIDR'],
							'ID_MUESTRA'  						=> $_POST['id_muestra'],
							'ID_METODOLOGIA'  					=> $_POST['id_metodologia'],//'ID_USUARIO_ANALISTA'	=> $_SESSION['user_id'],
							'ID_USUARIO_SIGNATARIO'				=> $_POST['id_usuario_signatario'],
							'REFERENCIA_MICROBIOLOGIA'  		=> $_POST['referencia_microbiologia'],
							'OBSERVACION_MICROBIOLOGIA'  		=> $_POST['observacion_microbiologia'],
							'CONDICIONES_MICROBIOLOGIA'  		=> $_POST['condiciones_microbiologia'],
							'ANALISIS_SOLICITADO_MICROBIOLOGIA' => $_POST['analisis_solicitado_microbiologia'],
							'RESULTADO_MICROBIOLOGIA'  			=> $_POST['resultado_microbiologia'],
							'METODO_PRUEBA_MICROBIOLOGIA'  		=> $_POST['metodo_prueba_microbiologia'],
							'TECNICO_MICROBIOLOGIA' 			=> $query->NOMBRE_USUARIO,
							'FECHA_MICROBIOLOGIA' 				=> $_POST['fecha_microbiologia'],
							'CARGO_TECNICO_MICROBIOLOGIA' 		=> $query->CARGO_USUARIO,
							'INICIALES_ANALISTA_MICROBIOLOGIA' 	=> $_POST['iniciales_analista_microbiologia'],
							'ID_USUARIO_CAPTURISTA'				=> $_SESSION['user_id']);
			*/
			$this->db->trans_begin(); //COMENZAMOS LAS TRANSACCIONES
			
			if ($accion == 'ALTA') {
				$lRet = $this->db->set($datos)->get_compiled_insert('idr_mercurio');				
			}
			if ($accion == 'EDICION') {
				
				$this->db->where('ID_METODOLOGIA', $idMetodologia);
				$lRet = $this->db->set($datos)->get_compiled_update('idr_mercurio');
			}
			
			$RespData['SQL'] = 'Cad tabla idr_mercurio:['.$lRet."] ";			
			$query = $this->db->query( $lRet); //EJECUTA LA CONSULTA INICIAL..!
			$RespData['RESULTADO'] = $this->db->affected_rows();
			
			if ($accion == 'ALTA') {
				if ($RespData['RESULTADO'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
					// obtenemos el ultimo id ingresado..!
					$id_mercurio = $this->db->insert_id();
					$aFolio = $this->db->query('select IDR_AQ from folios')->row();
					if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
						$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					}else {
						// ya me ganaron el id hay que buscar otro..
						$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
						$idIDR = $nFolioIDR->folio;
						$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
						//actualizar el idr en microbiologia
						$this->db->update('idr_mercurio',array('ID_IDR'=>$idIDR),'ID_MERCURIO = '.$id_mercurio);
					}
				}
			} // FIN DE ACCION = ALTA
			
			if ($accion == 'EDICION'){
				// 2017-07-25 --> actualizar la base de correcciones
				$datos2 = array('ID_USUARIO'			=> $_SESSION['user_id'],
								'ID_METODOLOGIA'		=> $_POST['id_metodologia'],
								'REFERENCIA_TABLA'		=> 'IDR_MERCURIO',
								'CAUSAS_CORRECCION'		=> $_POST['causas_correccion']	);
				$lRet2 = $this->db->set($datos2)->get_compiled_insert( 'correcciones_idr');				
				$query2 = $this->db->query( $lRet2);
				$RespData['RESULTADO MODIFICACION'] = $this->db->affected_rows();			
			}
			
			if ($this->db->trans_status() === FALSE) {
				$RespData['SITUACION_REGISTRO'] = 'ERROR';
        		$this->db->trans_rollback();
        	} else {
        		$this->db->trans_commit();
        		$RespData['SITUACION_REGISTRO'] = 'EXITO';
	        }			
			
			$RespData['SQL'] = $RespData['SQL'] .' SQL Folios:['.$this->db->last_query().")";
			header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
		}
		echo $lRet;		
	} // termina el ajax de IDR microbiologia
	/** ******************************************************	*/	
	public function idr_metales2( $idMetodologia = null, $idMuestra = null){ // es el primer reporte que se va diseñar!
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_metales2'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
			$this->load->library('table');
			//$this->table->set_heading('mg/kg','LC (mg/kg)','C.H.','C.A.');
			$this->table->set_heading('Metal','Resultado','LC (mg/kg)','LMP (mg/kg)','Tecnica','Acciones');
			$template2 = array(
			        'table_open' => '<table border="1" id="idTablaIDRMetales" class="table">'
			);
			$this->table->set_template($template2);
			
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_enc_metales');
			$this->db->join('idr_det_metales','idr_enc_metales.id_enc_metales = idr_det_metales.id_enc_metales');			
			$this->db->where('idr_enc_metales.ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();
			
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA					
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;

			// OBTENIENDO LOS METALES...!
			$data->analitos = $this->db->query('select * from metales')->result();
			$this->load->helper('dropdown');		
			//  function listData($table,$name,$value,$orderBy=null, $where_nombre_campo=null, $where_variable) {        
			$data->MetalesCombo = listData('metales','id_metal', 'nombre_metal',null,null,null);
			
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');

			if (count($query_result)>0)  {
				$data->accion = 'EDICION';
				//2017-09-06 --> para la tabla nomas se ocupan ciertos campos
				//'Analito','Resultado','LC (mg/kg)','LMP (mg/kg)','Tecnica','Acciones');
				
				$cCpo = "METAL_METALES,RESULTADO_METAL_METALES,LC_METALES,LMP_METALES,TECNICA_METALES,";				
				
				//$cBtn = '"<button type=button data-target=#myModal name=B2 data-toggle=modal  class="'.'"btn btn-info btn-xs"'.'"   onclick="'.'"DuplicaRowDetalladoEstudio(this,0)"'.'" >Editar"';
				//$cBtn = '"<button type=button name=B3 class="'.'"btn btn-danger btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"EliminaAnalitoTabla(this)"'.'" >Eliminar"';
				
				$cBtn = '"<button type=button name=B3 class="'.'"btn btn-info btn-xs btnEliminaMetalTabla"'.'"   onclick="'.'"EditaRowDetalladoIdrMetales(this)"'.'" >Editar"';
				$cBtn .= '"</button>"';
				$cBtn .= '"<button type=button name=B3 class="'.'"btn btn-danger btn-xs btnEliminaMetalTabla"'.'"   onclick="'.'"deleteRowDetalladoIdrMetales(this)"'.'" >Eliminar"';				
				$cBtn .= '"</button>"';				
				
				$cCpo .= $cBtn;				
				
				$this->db->select( $cCpo);
				
				//$this->db->select("ANALITO_PLAGUICIDAS,RESULTADO_ANALITO_PLAGUICIDAS,LC_PLAGUICIDAS,LMP_PLAGUICIDAS,TECNICA_PLAGUICIDAS,''");//05/06/2017
				$this->db->from('idr_enc_metales');
				$this->db->join('idr_det_metales','idr_enc_metales.id_enc_metales = idr_det_metales.id_enc_metales');			
				$this->db->where('idr_enc_metales.ID_METODOLOGIA',$idMetodologia);

				$cCad2 = $this->db->get_compiled_select();
				$query_result2 = $this->db->query($cCad2)->result_array();
				
				
				//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
				
				$data->resultados_det = $query_result2;// proviene del detallado de resultados..!
				$data->sql2 = $cCad2; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			}
			//2017-07-06
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_metales2', $data);
			$this->load->view('plantillas/footer', $data);			
		} // fin id !is_null( idmetodologia)		
	} // fin de IDR_PLAGUICIDAS
/**************************************************************************************/
	public function metales2() { // is ajax	que graba el IDR DE PLAGUICIDAS
	$cRet = "";
	
	$enc = isset($_POST['enc']) ? $_POST['enc'] : false; //condicion si existe que tome la variable si no lo asigna false
	$det = isset($_POST['det']) ? $_POST['det'] : false;
	//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones,'tecnica_plaguicidas':tecnica};
		
	if ($enc && $det)	{		
		// comenzamos a grabar el encabezado del idr de plaguicidas		
		$RespData = array();		
		//var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,'referencia_metales':referencia,'observacion_metales':obs,'condiciones_metales':condiciones, 'analisis_solicitado_metales':analisis,'metodo_prueba_metales':metodo,'fecha_final_metales':fechafinal,'iniciales_analista_metales':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas},det:[]}
		$idIDR =$enc['id_idr'];
		//2017-08-28		
		$idMetodologia = $enc['id_metodologia'];
		//2017-08-21
		$cIdUserSignatario = $enc['id_usuario_signatario'];
		$query = $this->db->query('select NOMBRE_USUARIO, CARGO_USUARIO FROM usuarios where ID_USUARIO = '.$cIdUserSignatario)->row();
		$RespData['SQL_Signatario'] = $this->db->last_query();
		//2017-08-21 --> DETERMINAMOS MEDIANTE LA VAR NUEVA ACCION QUE DEBE REALIZAR
		$accion = $enc['accion'];
		
		$datos_enc = array( 'ID_IDR'  		=> $enc['id_idr'],
			'ID_MUESTRA'  					=> $enc['id_muestra'],
			'ID_METODOLOGIA'  				=> $enc['id_metodologia'],
			'ANALISIS_SOLICITADO_METALES'  	=> $enc['analisis_solicitado_metales'],
			'ID_USUARIO_SIGNATARIO'			=> $enc['id_usuario_signatario'],
			'METODO_PRUEBA_METALES'  		=> $enc['metodo_prueba_metales'],
			'REFERENCIA_METALES'  			=> $enc['referencia_metales'],
			'OBSERVACION_METALES'  			=> $enc['observacion_metales'],
			'CONDICIONES_METALES'  			=> $enc['condiciones_metales'],		
			'TECNICO_METALES'				=> $query->NOMBRE_USUARIO,
			'FECHA_FINAL_METALES'			=> $enc['fecha_final_metales'],
			'CARGO_TECNICO_METALES' 		=> $query->CARGO_USUARIO,
			'INICIALES_ANALISTA_METALES'	=> $enc['iniciales_analista_metales'],			
			'ID_USUARIO_CAPTURISTA'			=> $_SESSION['user_id']);			
		
		// COMENZANDO LAS TRANSACCIONES
		$this->db->trans_begin();
		
		if ($accion == 'ALTA') {
			$lRet = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_metales');				
		}
		if ($accion == 'EDICION') {
			$this->db->where('ID_METODOLOGIA', $idMetodologia);
			$lRet = $this->db->set($datos_enc)->get_compiled_update('idr_enc_metales');
		}
		
		//$cSql = $this->db->set($datos_enc)->get_compiled_insert('idr_enc_plaguicidas');			
		$RespData['SQL_ENC'] = 'Cad tabla idr_enc_metales:['.$lRet."] ";
		$query = $this->db->query( $lRet);
		$RespData['RESULTADO_ENC'] = $this->db->affected_rows();
		
		if ($accion == 'ALTA') {
			if ($RespData['RESULTADO_ENC'] > 0 and $idIDR>0 ) { // actualizar el folio del IDR
					// obtenemos el ultimo id ingresado..!
				$id_metales = $this->db->insert_id();
				$aFolio = $this->db->query('select IDR_AQ from folios')->row();
				if ($aFolio->IDR_AQ == ($idIDR -1) ) { // procedimiento normal
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
				}else {
					// ya me ganaron el id hay que buscar otro..
					$nFolioIDR = $this->db->query('SELECT max( `IDR_AQ` ) + 1 as folio FROM folios')->row();
					$idIDR = $nFolioIDR->folio;
					$this->db->set(array('IDR_AQ'=>$idIDR))->update('folios');
					//actualizar el idr en aflatoxinas mediante el update de los datos ya insertados..!
					$this->db->update('idr_enc_metles',array('ID_IDR'=>$idIDR),'ID_METALES = '.$id_metales);					
				}
			}	
		}
		
		
		if ($accion == 'EDICION'){
			//2017-09-06 --> Eliminando los analitos grabados anteriormente
			$cID_Metales = $this->db->query("select ID_ENC_METALES from idr_enc_metales where ID_METODOLOGIA = '".$idMetodologia."'")->row();
			if ($this->db->affected_rows()>0) {
				//2017-09-06 -->para el caso de edicion hay que borrar a todos analitos registrados y anexar los de la tabla como nuevos
				$id_metales = $cID_Metales->ID_ENC_METALES;		
				if ($id_metales>0){
					$this->db->query("delete from idr_det_metales where ID_ENC_METALES = '".$id_metales."'" );
					$accion = 'ALTA';
					$RespData['Analitos_previos_borrados'] = 'SI';
					$RespData['Analitos_borrados'] = $this->db->affected_rows();
				}else {
					$RespData['Analitos_previos_borrados'] = 'NO /SQL EJECUTADA: '.$this->db->last_query();
				}
			}			
			
			// 2017-07-25 --> actualizar la base de correcciones
			$datos2 = array('ID_USUARIO'			=> $_SESSION['user_id'],
							'ID_METODOLOGIA'		=> $enc['id_metodologia'],
							'REFERENCIA_TABLA'		=> 'IDR_METALES',
							'CAUSAS_CORRECCION'		=> $enc['causas_correccion']	);
			$lRet2 = $this->db->set($datos2)->get_compiled_insert( 'correcciones_idr');				
			$query2 = $this->db->query( $lRet2);
			$RespData['RESULTADO MODIFICACION'] = $this->db->affected_rows();			
		}
					
		$RespData['SQL_ENC'] = $RespData['SQL_ENC'] .' SQL Folios:['.$this->db->last_query().")";
		$detallado = $det[0];		
		
		
		for ($nPos=0;$nPos<count($detallado);$nPos+=5){
			
			$cMetal 	= $detallado[$nPos];		
			$cResultado = $detallado[$nPos+1];
			$cLC 		= $detallado[$nPos+2];
			$cLMP 		= $detallado[$nPos+3];
			$cTecnica 	= $detallado[$nPos+4];
			
			//$cAcreditado = $this->db->query("select ACREDITADO_METALES from metales where METAL_METALES = '". $cMetal."'")->row('ACREDITADO_METALES');
			$cAcreditado = $this->db->query("select ACREDITADO_METAL from metales where NOMBRE_METAL = '". $cMetal."'")->row();
			$cAcreditado = $cAcreditado->ACREDITADO_METAL;
			if (!$cAcreditado) {
				$cAcreditado = 'Q';
			}				
				
			$otra_data = array( 
				'ID_ENC_METALES'			=> $id_metales,
				'METAL_METALES'				=> $cMetal,
				'RESULTADO_METAL_METALES' 	=> $cResultado,
				'LC_METALES'				=> $cLC,
				'LMP_METALES'				=> $cLMP,
				'TECNICA_METALES'			=> $cTecnica,
				'ACREDITADO_METALES'		=> $cAcreditado
			);			
			//array_push( $data, $otra_data);	
			$data[] = $otra_data;
		} // fin del for		
		//print_r($data);
		
			
		if ($accion == 'ALTA') {
			$this->db->insert_batch('idr_det_metales',$data);
			$cSql = $this->db->last_query();
			$RespData['SQL_DET'] = 'Cad tabla idr_det_metales:['.$cSql."] ";
			$RespData['RESULTADO_DET'] = $this->db->affected_rows();
		
			//ACTUALIZADNO EL STATUS DEL DETALLADO
			$idMetodologia = $enc['id_metodologia'];
			$this->db->query("update detalle_muestras set STATUS_MUESTRA='G' WHERE ID_METODOLOGIA = '$idMetodologia'");
		} // fin del alta
			
		if ($this->db->trans_status() === FALSE) {
			$RespData['SITUACION_REGISTRO'] = 'ERROR';
	       	$this->db->trans_rollback();
	    } else {        
	       	$this->db->trans_commit();
	       	$RespData['SITUACION_REGISTRO'] = 'EXITO';
	    }		
	       
	        
	    	header('Content-type: application/json; charset=utf-8');
			$lRet = json_encode($RespData);
			echo $lRet;
		}// fin del if enc y det	

	} // fin de la funcion ajax
	/* ************************************************************************/	
	/********************************* IDR PLAGICIDAS *************************************/
	public function idr_plaguicidas_agua( $idMetodologia = null, $idMuestra = null){ // es el primer reporte que se va diseñar!
		$data = new stdClass();
		//$idMuestra = 20;// ejemplo unicamente
		$data->title = 'Sistema de Recepcion de Muestra';
		$data->contenido = 'idr/v_idr_plaguicidas_agua'; 
		$data->accion = 'ALTA';
		$data->panel_title = 'Informe de Resultados';
		$data->menu_activo = 'servicios';
		
		if (!is_null($idMetodologia)) {
			$data->idMetodologia = $idMetodologia;
			$data->idMuestra = $idMuestra;
			$this->load->library('table');
			//$this->table->set_heading('mg/kg','LC (mg/kg)','C.H.','C.A.');
			$this->table->set_heading('Analito','Resultado','LC (mg/L)','Técnica','Acciones');
			$template2 = array(
			        'table_open' => '<table border="1" id="idTablaIDRPlaguicidasAgua" class="table">'
			);
			$this->table->set_template($template2);
			
			//$this->db->select('ANALISIS_SOLICITADO_AFLATOXINAS, RESULTADO_AFLATOXINAS, LC_AFLATOXINAS,CH_AFLATOXINAS, CA_AFLATOXINAS, METODO_PRUEBA_AFLATOXINAS');
			$this->db->select('*');//05/06/2017
			$this->db->from('idr_enc_plaguicidas_agua');
			$this->db->join('idr_det_plaguicidas_agua','idr_enc_plaguicidas_agua.id_enc_plaguicidas_agua = idr_det_plaguicidas_agua.id_enc_plaguicidas_agua');
			$this->db->where('idr_enc_plaguicidas_agua.ID_METODOLOGIA',$idMetodologia);

			$cCad = $this->db->get_compiled_select();
			$query_result = $this->db->query($cCad)->result_array();
			
			//$data->estudio = $this->estudios_model->getAllEstudio( $idMuestra);
			
			$data->resultados = $query_result;// proviene del detallado de resultados..!
			$data->sql = $cCad; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			
			// OBTENIENDO EL FOLIO DEL IDR POR AREA					
			$query_result2 = $this->db->query("select IDR_AQ from folios ")->row();
			$data->folios = $query_result2;

			// OBTENIENDO LOS ANALITOS...!
			$data->analitos = $this->db->query('select * from analitos_plaguicidas_agua')->result();
			$this->load->helper('dropdown');		
			//  function listData($table,$name,$value,$orderBy=null, $where_nombre_campo=null, $where_variable) {        
			$data->AnalitosCombo = listData('analitos_plaguicidas_agua','id_analito_plaguicidas_agua', 'nombre_analito_plaguicidas_agua',null,null,null);
			
			//OBTENIENDO LOS USUARIOS SIGNATARIOS DE QUIMICA			
			//					function listData($table,      $nameCpo,   $valueCpo,   $orderBy=null, $where_nombre_campo=null, $where_variable=null) {
			$data->SignatariosCombo = listData('usuarios','id_usuario','nombre_usuario','nombre_usuario','tipo_usuario','Q');

			if (count($query_result)>0)  {
				$data->accion = 'EDICION';
				//2017-09-06 --> para la tabla nomas se ocupan ciertos campos
				//'Analito','Resultado','LC (mg/kg)','LMP (mg/kg)','Tecnica','Acciones');
				
				$cCpo = "ANALITO_PLAGUICIDAS_AGUA,RESULTADO_ANALITO_PLAGUICIDAS_AGUA,LC_PLAGUICIDAS_AGUA,TECNICA_PLAGUICIDAS_AGUA";
				$cCpo .= ',';				
				$cBtn = '"<button type=button name=B3 class="'.'"btn btn-info btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"EditaRowDetalladoIdrPlagicidas(this)"'.'" >Editar"';
				$cBtn .= '"</button>"';
				$cBtn .= '"<button type=button name=B3 class="'.'"btn btn-danger btn-xs btnEliminaAnalitoTabla"'.'"   onclick="'.'"deleteRowDetalladoIdrPlagicidas(this)"'.'" >Eliminar"';
				$cBtn .= '"</button>"';				
				
				$cCpo .= $cBtn;				
				
				$this->db->select( $cCpo);
				
				//$this->db->select("ANALITO_PLAGUICIDAS,RESULTADO_ANALITO_PLAGUICIDAS,LC_PLAGUICIDAS,LMP_PLAGUICIDAS,TECNICA_PLAGUICIDAS,''");//05/06/2017
				$this->db->from('idr_enc_plaguicidas_agua');
				$this->db->join('idr_det_plaguicidas_agua','idr_enc_plaguicidas_agua.id_enc_plaguicidas_agua = idr_det_plaguicidas_agua.id_enc_plaguicidas_agua');
				$this->db->where('idr_enc_plaguicidas_agua.ID_METODOLOGIA',$idMetodologia);

				$cCad2 = $this->db->get_compiled_select();
				$query_result2 = $this->db->query($cCad2)->result_array();
				
				$data->resultados_det = $query_result2;// proviene del detallado de resultados..!
				$data->sql2 = $cCad2; // CONOCER LA CONSULTA QUE LO ESTA EJECUTANDO..!
			}
			//2017-07-06
			$this->db->select('*');
			$this->db->from('detalle_muestras');
			$this->db->join('recepcion_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
			$this->db->join('estudios','estudios.ID_ESTUDIO = detalle_muestras.ID_ESTUDIO');
			$this->db->where('ID_METODOLOGIA',$idMetodologia);
			$cCad = $this->db->get_compiled_select();
			$data->sql2 = $cCad;
			$query_result = $this->db->query($cCad)->row();
			$data->datos_metodologia = $query_result;	
			
			$this->load->view('plantillas/head', $data);
			$this->load->view('plantillas/header', $data);
			$this->load->view('idr/v_idr_plaguicidas_agua', $data);
			$this->load->view('plantillas/footer', $data);
			
		} // fin id !is_null( idmetodologia)		
		
	} // fin de IDR_PLAGUICIDAS
	/*************************************************/
	
}