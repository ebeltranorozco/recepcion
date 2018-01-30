<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Impresiones_controller extends CI_Controller {
 
  public function __construct()  {
   	parent::__construct();
  		// $this->load->model('clientes_model');
    $this->load->model('estudios_model');
    $this->load->library('utilerias');
   	//$this->load->helper('download');
   	if (!(isset($_SESSION['user_id']))) {
		//if (!($_SESSION['user_id'])){
				redirect('/login');
	}
  }
    
  /***************************************************************/
  public function idr_microbiologia( $idDetalleMuestra = null) {
  	// lo primero es obtener los datos necesarios ..!
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
  	$this->db->join('idr_microbiologia','detalle_muestras.ID_METODOLOGIA = idr_microbiologia.ID_METODOLOGIA');
	$this->db->join('usuarios','idr_microbiologia.ID_USUARIO_SIGNATARIO = usuarios.ID_USUARIO');   	  	
    $this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);   
    
    $query = $this->db->get();    
  	$data = $query->result();
  	
  	// fin de la obtencion de los datos necesarios ..!
  	  	 
  	 if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalle_Muestra.' ] Inexistente" );</script>';
        exit();        
    }
          	
    //$IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;
    $idMuestra          = $data[0]->ID_MUESTRA;
    //$cFolioSolicitud	= $data[0]->FOLIO_SOLICITUD;
    
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    //$cOrigen
    $dFechaAnalisis     = $data[0]->FECHA_FINAL_MICROBIOLOGIA; // FECHA FINAL  2018-01-16
    
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_MICROBIOLOGIA;
    $cResultado			= $data[0]->RESULTADO_MICROBIOLOGIA; 
    //2018-01-17
    $cResultadoColTotal = $data[0]->RESULTADO_COLIFORMES_TOTALES_MICROBIOLOGIA;
    $cResultadoColFecal = $data[0]->RESULTADO_COLIFORMES_FECALES_MICROBIOLOGIA;
    $cResultadoEColi    = $data[0]->RESULTADO_ECOLI_MICROBIOLOGIA;

    $cMetodoPrueba		= $data[0]->METODO_PRUEBA_MICROBIOLOGIA; 
    
    
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    $cRefResultado       = $data[0]->REFERENCIA_MICROBIOLOGIA;
    $cObsResultado       = $data[0]->OBSERVACION_MICROBIOLOGIA;
    $cCondMuestra        = $data[0]->CONDICIONES_MICROBIOLOGIA;
    
    $cNameTecnico 		 = $data[0]->TECNICO_MICROBIOLOGIA;
    $cCargoTecnico		 = $data[0]->CARGO_TECNICO_MICROBIOLOGIA;
    $cIniciales			 = $data[0]->INICIALES_ANALISTA_MICROBIOLOGIA;
    
    // generando las variables que requiere el informe..!
    
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->pdf = new pdf( $cMetodoValidado ); //   
    
    $nInc = 10;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    $this->idr_encabezado($data,NULL,$dFechaAnalisis);  // fecha inicial no aplica para micro
    
    // DATOS DEL RESULTADO
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',8);
    
    //$nPosEnc = array('col1' => 10,'col2'=>35,'col3'=>70,'col4' =>90,'col5' => 105,'col6'=>130,'col7'=>145,'col8' =>160,'col9'=>206);
    $nPosEnc = array('col1' => 10,'col2'=>70,'col2.5'=>110,'col3'=>150,'col4' =>206);
       
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,utf8_decode('ANALISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode('RESULTADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode('METODO DE PRUEBA'),1,'C',1,false);

	$this->pdf->ln();  
    $nRowActual = $this->pdf->gety();

    // COMENZANDO A ESCRIBIR LOS DATOS..!
    $this->pdf->SetFont('Arial','',8);

    //2018-01-17 --> anexando primero la columan de resultados por si hubera varias variantes col totales/fecales o un resultado general

    $this->pdf->setXY($nPosEnc['col2'],$nRowActual);
    $nnnVeces = 1;
    $nRowTmp =  $nRowActual;
    if ($cResultado) {
        $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc*2,utf8_decode($cResultado),0,'C',2,false);        
        $nRowTmp = $this->pdf->getY();        
    }
    if ($cResultadoColTotal ){
        $this->pdf->setX($nPosEnc['col2']);
        //$this->pdf->Multicelda($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc,utf8_decode('Coliformes Totales:'),0,'L',2,false);
        $this->pdf->cell($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc/2,utf8_decode('Coliformes Totales:'),0,2,'L',false);
        $this->pdf->setX($nPosEnc['col2']+5);
        $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2']-5,$nInc,utf8_decode($cResultadoColTotal),0,'L',2,false);
        $nRowTmp = $this->pdf->getY();
    }
    if ($cResultadoColFecal ){
        $this->pdf->setX($nPosEnc['col2']);
        //$this->pdf->Multicelda($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc,utf8_decode('Coliformes Fecales:'),0,'L',2,false);
        $this->pdf->cell($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc/2,utf8_decode('Coliformes Fecales:'),0,2,'L',false);
        $this->pdf->setX($nPosEnc['col2']+5);
        $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2']-5,$nInc,utf8_decode($cResultadoColFecal),0,'L',2,false);
        $nRowTmp = $this->pdf->getY();
    }
    if ($cResultadoEColi ){
        $this->pdf->setX($nPosEnc['col2']);
        //$this->pdf->Multicelda($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc,utf8_decode('E. Coli:'),0,'L',2,false);
        $this->pdf->SetFont('Arial','I',8);
        $this->pdf->cell($nPosEnc['col2.5']-$nPosEnc['col2'],$nInc/2,'E. Coli:',0,2,'L',false);
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->setX($nPosEnc['col2']+5);
        $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2']-5,$nInc,utf8_decode($cResultadoEColi),0,'L',2,false);
        $nRowTmp = $this->pdf->getY();
    }

    $cTmp = utf8_decode($data[0]->ANALISIS_SOLICITADO_MICROBIOLOGIA);
    $this->pdf->setxy($nPosEnc['col1'],$nRowActual);    
    $this->pdf->cellHtml($nPosEnc['col2']-$nPosEnc['col1'],$nRowTmp-$nRowActual,$cTmp,1,'C' ,1 );   
	
	
	$this->pdf->setxy($nPosEnc['col3'],$nRowActual);	
	$this->pdf->Multicelda2($nPosEnc['col4']-$nPosEnc['col3'],$nRowTmp-$nRowActual,utf8_decode($cMetodoPrueba),1,'L',1,false);
	
    $nRowActual = $this->pdf->gety();
    $this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->pdf->ln();
    $this->idr_pie_de_pagina( $data,'MICROBIOLOGIA',$nInc);
    
    
    
    //REFERENCIAS DE APLICACION DE LA METODOLOGIA
    //$this->pdf->ln($nInc/2);
    
    
  
    // FIN DE PARTE DE LAS OBSERVACINOES
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin del idr_microbiologia  
  /****************************************************************/
  public function idr_aflatoxinas($idDetalleMuestra = null) {
  	// lo primero es obtener los datos necesarios ..!
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
  	$this->db->join('idr_aflatoxinas','detalle_muestras.ID_METODOLOGIA = idr_aflatoxinas.ID_METODOLOGIA');
  	$this->db->join('usuarios','idr_aflatoxinas.ID_USUARIO_SIGNATARIO = usuarios.ID_USUARIO');  	
    $this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);
    
    $query = $this->db->get();
    
  	$data = $query->result();
  	
  	// fin de la obtencion de los datos necesarios ..!
  	  	 
  	 if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalle_Muestra.' ] Inexistente" );</script>';
        exit();        
    }
          	
    //$IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;
    $cFolioSolicitud	= $data[0]->FOLIO_SOLICITUD;
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    $idMuestra          = $data[0]->ID_MUESTRA;
    
    //2017-08-16
    $dFechaIniciaAnalisis= $data[0]->FECHA_INICIO_REAL;    
    
    $dFechaFinalAnalisis= $data[0]->FECHA_AFLATOXINAS;
    
    //$cRangoFechas	= "PRUEBA VDD:";
    
     //date_format($date, 'Y-m-d H:i:s');
    
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    $cRefResultado       = $data[0]->REFERENCIA_AFLATOXINAS;
    $cObsResultado       = $data[0]->OBSERVACION_AFLATOXINAS;
    $cCondMuestra        = $data[0]->CONDICIONES_AFLATOXINAS;
    
    //$cNameTecnico 		 = $data[0]->TECNICO_AFLATOXINAS;
    //$cCargoTecnico		 = $data[0]->CARGO_TECNICO_AFLATOXINAS;   
    
    $cIniciales			 = $data[0]->INICIALES_ANALISTA_AFLATOXINAS;
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->load->library('utilerias');
    $this->pdf = new pdf( $cMetodoValidado );
    
    
    
    $nInc = 10;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();
    $this->pdf->SetMargins(10,20,10);

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    $this->idr_encabezado($data,$dFechaIniciaAnalisis,$dFechaFinalAnalisis);
    
    // DATOS DEL RESULTADO
    $this->pdf->ln(1);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',8);
    
    $nPosEnc = array('col1' => 10,'col2'=>35,'col3'=>55,'col4' =>76,'col5' => 93,'col6'=>115,'col7'=>130,'col8' =>155,'col9'=>206);
    
    //$this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,utf8_decode('ID DE MUESTRA'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode('RESULTADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode('(mg/kg)'),1,'C',1,false);
    
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode('LC (mg/kg)'),1,'C',1,false);
    
    $nPosY = $this->pdf->gety(); // RENGLON
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col6'],$nInc/2,'LMP ( mg/kg )',1,'C',1,false );
    
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO DE PRUEBA'),1,'C',2,false );
       
    //$nPosX = $this->pdf->getx();
    $this->pdf->setxy($nPosEnc['col6'],$nPosY+($nInc/2));
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc/2,'C.H.',1,'C',1,false );
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc/2,'C.A.',1,'C',2,false );
    
    // COMENZANDO A ESCRIBIR LOS DATOS..!
    
    $cLeyendaAsterisco = null;
    //COMENZAMOS SU IMPRESION DE LOS DATOS DE LA TABLA
    foreach( $data as $row) {
    	//$this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*2,utf8_decode($row->ID_MUESTRA),1,'C',1,false);
	    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc*2,utf8_decode($row->ANALISIS_SOLICITADO),1,'C',1,false);
	    $nRowActual = $this->pdf->gety();
	    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'G1',1,'C',0,false);
	    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'G2',1,'C',0,false);
	    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'B1',1,'C',0,false);
	    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'B2',1,'C',0,false);
	    
	    $this->pdf->setxy( $nPosEnc['col4'],$nRowActual);
	    $nLC = (float)$row->LC_AFLATOXINAS;
	    $nResul = (float)$row->RESULTADO_AFLATOXINAS;
	    // SIGUE LA SUMATORIA..!
	    if ($nResul < $nLC ) {
			$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,'<LC',1,'C',1,false);	
		}else {
			$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,$row->RESULTADO_AFLATOXINAS,1,'C',1,false);	
		}// fin del if resultado aflatoxinas > .05
		$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*2,$row->LC_AFLATOXINAS,1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*2,$row->CH_AFLATOXINAS,1,'C',1,false);	
		$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*2,$row->CA_AFLATOXINAS,1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc*2,utf8_decode($row->METODO_PRUEBA_AFLATOXINAS),1,'C',2,false);
		
		//2017-08-28 --> PARA LA ACREDITACION DE LOS ANALITOS..!
		//$cAcreditado = $this->db->query("select ACREDITADO_ANALITO from analitos where NOMBRE_ANALITO = '".
		//if ($row->ACREDITADO_ANALITO == 'N') { $cLeyendaAsterisco = 'Analito NO Acreditado';}
	}// fin del foreach de los renglones
    // PARTE DE LAS OBSERVACIONES
    
    //REFERENCIAS DE APLICACION DE LA METODOLOGIA
    //$this->pdf->ln($nInc/2);
    $nRowActual = $this->pdf->gety();
    $this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->idr_pie_de_pagina( $data,'AFLATOXINAS',$nInc,$cLeyendaAsterisco);
    
   
    // FIN DE PARTE DE LAS OBSERVACINOES
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin de funcion idr_aflatoxinas  
  /***************************************************************/
  public function idr_encabezado( $data,$dFechaInicialAnalisis = null,$dFechaFinalAnalisis = null){ // encabezado para todos los IDR
	$dFechaEmision      = date('Y-m-d h:m:s');	
	if (isset($data[0]->FECHA_PLAGUICIDAS)){ $dFechaEmision = $data[0]->FECHA_PLAGUICIDAS; }
	if (isset($data[0]->FECHA_ALTA_METALES)){ $dFechaEmision = $data[0]->FECHA_ALTA_METALES; }
	if (isset($data[0]->FECHA_AFLATOXINAS)){ $dFechaEmision = $data[0]->FECHA_AFLATOXINAS; }
	if (isset($data[0]->FECHA_ALTA_MERCURIO)){ $dFechaEmision = $data[0]->FECHA_ALTA_MERCURIO; }
	if (isset($data[0]->FECHA_ALTA_METALES)){ $dFechaEmision = $data[0]->FECHA_ALTA_METALES; }
	if (isset($data[0]->FECHA_MICROBIOLOGIA)){ $dFechaEmision = $data[0]->FECHA_MICROBIOLOGIA; }
	

	// DATOS CLIENTE
    $cNombreCte         = utf8_decode($data[0]->NOMBRE_CLIENTE);
    if (!(empty($data[0]->NOMBRE_IDR_CLIENTE  )) ) {
		$cNombreCte         = utf8_decode($data[0]->NOMBRE_IDR_CLIENTE);
	}	
    $cDireCte           = utf8_decode($data[0]->DOMICILIO_CLIENTE);
    if (!(empty($data[0]->DOMICILIO_IDR_CLIENTE) ) ){
		$cDireCte         = utf8_decode($data[0]->DOMICILIO_IDR_CLIENTE);		
	}	
    $cAtenciona         = utf8_decode($data[0]->CONTACTO_CLIENTE);
    if (!(empty($data[0]->CONTACTO_IDR_CLIENTE  )) ) {
		$cAtenciona         = utf8_decode($data[0]->CONTACTO_IDR_CLIENTE);
	}	
    $cRfc               = $data[0]->RFC_CLIENTE;
    $cTel               = $data[0]->TELEFONO_CLIENTE;
    $cEmail             = $data[0]->EMAIL_CLIENTE;
    
    // 2017-11-27
    $nFontSize = 7;
    
    // DATOS MUESTRA
    $cDescMuestra       = utf8_decode($data[0]->DESCRIPCION_MUESTRA); // YA NO SE USA
    
    $cTipoMuestra		= utf8_decode($data[0]->TIPO_MUESTRA);
    $cPesoVol			= utf8_decode($data[0]->PESO_VOL_MUESTRA);
    $nLote              = utf8_decode($data[0]->LOTE_MUESTRA);  
    $dFechaRecepcion    = $data[0]->FECHA_RECEPCION;
    
    
    $cOrigenMuestra		= utf8_decode($data[0]->LOTE_MUESTRA);  
    $IdAsigCliente      = utf8_decode($data[0]->ID_ASIGNADO_CLIENTE);
    $nCant              = $data[0]->NO_MUESTRAS;
    $cTemperatura		= $data[0]->TEMPERATURA_MUESTRA;
    $cUbicacion         = 'NA';
    
    //$dFechaAnalisis     = $data[0]->FECHA_ANALISIS; --> VARIA MUY VARIADO POR CADA REPORTE SE APUNTA, ESTA VARIABLE LLEGA COMO PARAMETRO..!
    
	$nPosEnc = array('col1' => 10,'col2'=>20,'col3'=>38,'col4' =>60,'col5' => 90,'col6'=>110,'col7'=>135,'col8' =>155,'col9'=>165,'col10'=>205,'col11'=>206);
    
    $r =18;
    $r = $this->pdf->getY();
    $nObj = count($data );
    $nInc = 10;
    if ($nObj>4 and $nObj <12 ) { $nInc = 9;}
    if ($nObj>11 and $nObj <15 ) { $nInc = 8;}
    if ($nObj>15 and $nObj <20 ) { $nInc = 7;}
    if ($nObj>20 ) { $nInc = 7;} // este es correcto
    if ($nObj>30 ) { $nInc = 6;}
    if ($nObj>40 ) { $nInc = 5;}
    $nInc = 10;
    
    $this->pdf->setxy($nPosEnc['col1'],$r+2);

    $this->pdf->SetFont('Arial','B',$nFontSize+4);
    $this->pdf->cell(0,$nInc/2,'INFORME DE RESULTADOS',0,2,'C');
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Fecha/hora de Emisión:'),0,'C',1,false);
    $this->pdf->SetFont('Arial','B',$nFontSize);  
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col3'],$nInc/2,$dFechaEmision,0,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col5'],$nInc/2,'IDR:',0,'R',1,false);
    $this->pdf->SetFont('Arial','B',$nFontSize+2);
    $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col8'],$nInc/2,str_pad($data[0]->ID_IDR.'/'.date('y'),4,'0',STR_PAD_LEFT),0,'L',1,false);
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,'ID de Muestra:',0,'R',1,false);
    $this->pdf->SetFont('Arial','B',$nFontSize+2);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nInc/2, $this->utilerias->Personaliza_ID_MUESTRA($data[0]->ID_MUESTRA),0,'L',1,false);
    
    $this->pdf->ln($nInc/2);
    
    /******************************************************************************************
    ********************* DATOS DEL CLIENTE ***************************************************
    ******************************************************************************************/
        
    $this->pdf->SetFont('Arial','B',$nFontSize+2);
    $this->pdf->cell(0,$nInc/2,'DATOS DEL CLIENTE',1,1,'C');
    
    $this->pdf->SetFont('Arial','',$nFontSize);     
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,'Nombre:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,$cNombreCte,1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,'RFC:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cRfc,1,'L',1,false);
          
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Dirección:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    //Multicelda($ancho,$alto,$txt,$nBorde,$alig ,$nDesplaza ,$lFill =false ){
    	
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,$cDireCte,1,'L',1,false);    
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,utf8_decode('Teléfono:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cTel,1,'L',1,false);    
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Atención a:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,$cAtenciona,1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,utf8_decode('Correo electrónico:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicell($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cEmail,1,'J',false);    
     
    /* ****************************************************************************
    ********************* DESCRIPCION DE LA MUESTRA *******************************
    *******************************************************************************/   
    
    $this->pdf->SetFont('Arial','B',$nFontSize+2);
    $this->pdf->cell(0,$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,1,'C');
    
    $nPosEnc = array('col1' => 10,'col2'=>25,'col3'=>38,'col4' =>60,'col5' => 90,'col6'=>135,'col7'=>135,'col8' =>155,'col9'=>165,'col10'=>205,'col11'=>206);
   
    $cTipoMuestra		= utf8_decode($data[0]->TIPO_MUESTRA);
    $cPesoVol			= utf8_decode($data[0]->PESO_VOL_MUESTRA);
    $nLote              = utf8_decode($data[0]->LOTE_MUESTRA);  
    $dFechaRecepcion    = $data[0]->FECHA_RECEPCION;
    
    
    $cOrigenMuestra		= utf8_decode($data[0]->LOTE_MUESTRA);  
    $IdAsigCliente      = utf8_decode($data[0]->ID_ASIGNADO_CLIENTE);
    $nCant              = $data[0]->NO_MUESTRAS;
    $cTemperatura		= utf8_decode($data[0]->TEMPERATURA_MUESTRA);
    $cUbicacion         = 'NA';
    
    //2017-08-16
    $cDestinoMuestra	= utf8_decode($data[0]->DESTINO_MUESTRA);
    
    //2017-11-27 
    $cRespTomaMuestra 	= utf8_decode($data[0]->TOMO_MUESTRA);
    $dFechaHoraTomaMuestra = $data[0]->FECHA_HORA_TOMA_MUESTRA;
    
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Tipo de Muestra:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,$cTipoMuestra,1,'L',1,false);
    
    $this->pdf->SetFont('Arial','',$nFontSize);    
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,utf8_decode('Destino de la Muestra:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cDestinoMuestra,1,'L',1,false);        
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,'Peso/Volumen:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,$cPesoVol,1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col6'],$nInc/2,'ID. Asignada por el Cliente:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$IdAsigCliente,1,'L',1,false);        
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,'Lote/Origen:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,$nLote,1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col6'],$nInc/2,'Temperatura:',1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cTemperatura,1,'L',1,false);        
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Fecha/hora de Recepción:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,$dFechaRecepcion,1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,utf8_decode('Fecha de Análisis:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
   
    $x1 = strftime("%Y-%m-%d", strtotime($dFechaInicialAnalisis));
    $x2 = strftime("%Y-%m-%d", strtotime($dFechaFinalAnalisis));
    $cRangoFechas = $x2;
    if (substr($data[0]->ID_MUESTRA,2,1)=='Q'){ $cRangoFechas = $x1 .' / '. $x2; }
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,$cRangoFechas,1,'L',1,false);    
    
    //2017-11-27 anexar responsable toma de muestras
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','',$nFontSize);
    //$this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Fecha Recepción:'),1,'R',1,false);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc/2,utf8_decode('Responsable de toma de muestra:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc/2,utf8_decode($cRespTomaMuestra),1,'L',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col7'],$nInc/2,utf8_decode('Fecha/hora:'),1,'R',1,false);
    $this->pdf->SetFont('Arial','',$nFontSize);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc/2,utf8_decode($dFechaHoraTomaMuestra),1,'L',1,false);
    //$this->pdf->ln($nInc/2);       		
    $nPosEnc = array('col1' => 10,'col2'=>20,'col3'=>35,'col4' =>60,'col5' => 90,'col6'=>110,'col7'=>135,'col8' =>155,'col9'=>165,'col10'=>205,'col11'=>206);
    
	} // FIN DE LA FUNCION ENCABEZADO...!
  /***************************************************************/
  // FIN DE  LA FUNCION IDR ENCABEZADO ..!
  /***************************************************************/
  public function InformeResultados( $idMuestra = 'null'){
    $data = $this->estudios_model->getResultadoEstudio($idMuestra);
    //ME QUEDE CHECANDO PORQUE EN EL INFORME NO ME APARECEN TODOS LOS LINFONCITOS..
    
    if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idFolio.' ] Inexistente" );</script>';
        exit();        
    }    
    
    // CARGANDO LAS VARIABLES
    $dFechaEmision      = date('Y-m-d');
    

    $IDR                = 'LARIA-XXXX';// pudiera ser el folio de la solicitud
    $IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $idMuestra          = $data[0]->ID_MUESTRA;
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    // DATOS CLIENTE
    $cNombreCte         = $data[0]->NOMBRE_CLIENTE;
    $cDireCte           = $data[0]->DOMICILIO_CLIENTE;
    $cAtenciona         = $data[0]->CONTACTO_CLIENTE;
    $cRfc               = $data[0]->RFC_CLIENTE;
    $cTel               = $data[0]->TELEFONO_CLIENTE;
    $cEmail             = $data[0]->EMAIL_CLIENTE;
    // DATOS MUESTRA
    $cDescMuestra       = $data[0]->DESCRIPCION_MUESTRA;
    $nCant              = $data[0]->NO_MUESTRAS;
    $nLote              = $data[0]->LOTE_MUESTRA;
    $dFechaRecepcion    = $data[0]->FECHA_RECEPCION;
    $cUbicacion         = 'NA';
    $IdAsigCliente      = utf8_decode($data[0]->ID_CLIENTE);
    $dFechaAnalisis     = $data[0]->FECHA_ANALISIS;
    //RESULTADOS
    $cAnalisisSolicitado = $data[0]->ANALISIS_SOLICITADO;    
    $cMetodoPrueba       = $data[0]->METODOLOGIA_ESTUDIO;

    $cTCA                = $data[0]->TCA_RESULTADO;
    $cTCH                = $data[0]->TCH_RESULTADO;
    $cPruebaResultado    = $data[0]->PRUEBA_RESULTADO;
    $cResultadoResultado = $data[0]->RESULTADO_RESULTADO;
    
    $cRefResultado       = $data[0]->REFERENCIA_RESULTADO;
    $cObsResultado       = $data[0]->OBSERVACION_RESULTADO;
    $cCondMuestra        = $data[0]->CONDICION_MUESTRA;

    // CONTINUARA
      
    // empezamos el pdf
    $this->load->library('pdf');
    $this->pdf = new pdf();
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();

    // COMENZAMOS EL REPORTE
    $nPosEnc = array('col1' => 10,'col2'=>20,'col3'=>35,'col4' =>60,'col5' => 90,'col6'=>110,'col7'=>135,'col8' =>155,'col9'=>165,'col10'=>205,'col11'=>206);
    
    $r =15;

    $nObj = count($data);
    $nInc = 10;
    if ($nObj>4 and $nObj <12 ) { $nInc = 9;}
    if ($nObj>11 and $nObj <15 ) { $nInc = 8;}
    if ($nObj>15 and $nObj <20 ) { $nInc = 7;}
    if ($nObj>20 ) { $nInc = 7;} // este es correcto
    if ($nObj>30 ) { $nInc = 6;}
    if ($nObj>40 ) { $nInc = 5;}    

    $this->pdf->setxy($nPosEnc['col1'],$r+$nInc);

    $this->pdf->SetFont('Arial','B',12);
    $this->pdf->cell(0,$nInc/2,'INFORME DE RESULTADOS',0,2,'C');
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Fecha de Emision:',0,'C',1,false);
    $this->pdf->SetFont('Arial','',8);  
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,$dFechaEmision,0,'C',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col4'],$nInc,'IDR:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col8'],$nInc,date('y').'-'.str_pad($IDR,4,'0',STR_PAD_LEFT),0,'L',1,false);
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col1'],$nInc,'ID de Muestra:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nInc,$idMuestra,0,'L',1,false);

    // DATOS DEL CLIENTE AHORA  
    
    $this->pdf->ln($nInc);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'DATOS DEL CLIENTE',1,1,'C');
    $this->pdf->SetFont('Arial','B',8);
    //$this->pdf->ln($nInc/2);    
    
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Nombre:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$cNombreCte,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'RFC:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$cRfc,0,'L',1,false);    
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Direccion:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$cDireCte,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'Telefono:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$cTel,0,'L',1,false);    
    
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Atencion a:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc,$cAtenciona,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col6'],$nInc,'Correo:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$cEmail,0,'L',1,false);    

    $this->pdf->ln($nInc/2);    

    // DATOS DE LA MUESTRA
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'DATOS DE LA MUESTRA',1,1,'C');
    //$this->pdf->ln($nInc/2);
    
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,utf8_decode('Descripción:'),0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$cDescMuestra,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,utf8_decode('Ubicación:'),0,'R',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$cUbicacion,0,'L',1,false);        
    
    $this->pdf->ln($nInc);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Cantidad:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$nCant,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'Id Asig por el Cliente:',0,'R',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$IdAsigCliente,0,'L',1,false);        

    $this->pdf->ln($nInc);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Lote:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$nLote,0,'L',1,false);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'Fecha de Analisis:',0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$dFechaAnalisis,0,'L',1,false);        
    
    $this->pdf->ln($nInc);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col1'],$nInc,utf8_decode('Fecha / Hora Repceción:'),0,'R',1,false);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col3'],$nInc,$dFechaRecepcion,0,'L',1,false);    
    $this->pdf->ln($nInc/2);

    // DATOS DEL RESULTADO
    
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',8);

    if ($cTipoAnalisis == 'Q'){ // SE GENERA INFORME DE ENSAYOS QUIMICOS
        $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col1'],$nInc,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,true);
        $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col4'],$nInc,utf8_decode('  RESULTADO (mg/Kg)'),1,'C',1,true);
        // aqui debe de ir la division de los campos para los diferentes repórtes..!
        //$data[0]->NOMBRE_CPO1_INFORME
        if ($data[0]->ID_ESTUDIO==2){ //aflaxotinas ..!
            $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col6'],$nInc,utf8_decode('LMP (mg/Kg)       C.H.      C.A.'),1,'C',1,true);            
        }

        if ($data[0]->ID_ESTUDIO==1){ //plagicidas ..!
            $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode('Limite de Cuantificación (mg/kg)'),1,'C',1,true);
            $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc,utf8_decode('Técnica'),1,'C',1,true);
            
        }
        $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO DE PRUEBA'),1,'C',1,true);

        $this->pdf->ln($nInc);

        $nObj = count($data); // cuenta los objetos del array
        //$this->pdf->cell(0,$nInc, $nObj,1);
        //$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col1'],$nInc*$nObj,utf8_decode($cAnalisisSolicitado),1,'C',1);

        //obtener el renglon actual
        $nRowAnterior = $this->pdf->gety();
        $lTotalCHoCA = true;

        $this->pdf->SetFont('Arial','B',7);

        for ($nPos=0; $nPos < $nObj; $nPos++) { 
            
            $cPruebaResultado    = $data[$nPos]->PRUEBA_RESULTADO;
            $cResultadoResultado = $data[$nPos]->RESULTADO_RESULTADO;
            $cCA                = $data[$nPos]->CA_RESULTADO;
            $cCH                = $data[$nPos]->CH_RESULTADO;

            $this->pdf->setx( $nPosEnc['col4']);
            $this->pdf->SetFont('Arial','',8);
            $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,utf8_decode($cPruebaResultado),1,'C',1);
            $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,utf8_decode($cResultadoResultado),1,'C',1);

            // preguntamos si hay CA o hay TCA
            //if ($data[0]->ID_ESTUDIO==2){ //aflaxotinas ..!
            //if (!empty( $cCH) or !empty($cCA) or !$lTotalCHoCA){
                $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc/2,utf8_decode($cCH),1,'C',1);
                $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc/2,utf8_decode($cCA),1,'C',1);
                $lTotalCHoCA = false;
            //}
            $this->pdf->ln($nInc/2);        
        } // FIN DEL FOR
        $nRowActual = $this->pdf->gety();
        $this->pdf->setxy( $nPosEnc['col1'], $nRowAnterior) ;

        $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col1'],$nRowActual-$nRowAnterior,utf8_decode($cAnalisisSolicitado),1,'C',1);
        $this->pdf->setxy( $nPosEnc['col8'], $nRowAnterior) ;
        $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nRowActual-$nRowAnterior,utf8_decode($cMetodoPrueba),1,'C',1);
        
    } ELSE { // SE GENERA INFORME DE ENSAYOS MICROBIOLOGICOS
        $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col1'],$nInc,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,true);
        $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col4'],$nInc,utf8_decode('  RESULTADO '),1,'C',1,true);        
        $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO DE PRUEBA'),1,'C',1,true);
        $this->pdf->ln($nInc);

        $nObj = count($data); // cuenta los objetos del array       

        //obtener el renglon actual
        $nRowAnterior = $this->pdf->gety();
        $lTotalCHoCA = true;

        $this->pdf->SetFont('Arial','B',7);

        for ($nPos=0; $nPos < $nObj; $nPos++) { 
            $cCA                = $data[$nPos]->CA_RESULTADO;
            $cCH                = $data[$nPos]->CH_RESULTADO;
            $cPruebaResultado    = $data[$nPos]->PRUEBA_RESULTADO;
            $cResultadoResultado = $data[$nPos]->RESULTADO_RESULTADO;

            $this->pdf->setx( $nPosEnc['col4']);
            $this->pdf->SetFont('Arial','',8);
            $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,utf8_decode($cPruebaResultado),0,'C',1);
            $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col6'],$nInc/2,utf8_decode($cResultadoResultado),0,'C',0);

            // preguntamos si hay CA o hay TCA
            //if (!empty( $cCH) or !empty($cCA) or !$lTotalCHoCA){
            //    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc/2,utf8_decode($cCH),1,'C',1);
            //    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc/2,utf8_decode($cCA),1,'C',1);
            //    $lTotalCHoCA = false;
            //}
            $this->pdf->ln($nInc/2);        
        } // FIN DEL FOR
        
        $nRowActual = $this->pdf->gety();
        $this->pdf->setxy( $nPosEnc['col1'], $nRowAnterior) ;

        $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col1'],$nRowActual-$nRowAnterior,utf8_decode($cAnalisisSolicitado),1,'C',1);
        $this->pdf->setxy( $nPosEnc['col8'], $nRowAnterior) ;
        $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col8'],$nRowActual-$nRowAnterior,utf8_decode($cMetodoPrueba),1,'C',1);   


    }


    //REFERENCIAS DE APLICACION DE LA METODOLOGIA
    //$nRowActual = $this->pdf->gety();
    $this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->pdf->SetFont('Arial','B',8);
    
    $this->pdf->rect($nPosEnc['col1'],$nRowActual,$nPosEnc['col11']-$nPosEnc['col1'],$nInc*1.5);
    $this->pdf->write($nInc/2,utf8_decode('REFERENCIAS DE APLICACIÓN DE METODOLOGÍA:'));
    $this->pdf->ln($nInc/2);

    $this->pdf->SetFont('Arial','',8);    
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode($cRefResultado),0,2,'C');

    //$this->pdf->ln($nInc);
    $this->pdf->ln($nInc/2);
    // OBSERVACIONES EXISTENTES
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('Notas u Observaciones:'),0,2,'L');
    $this->pdf->SetFont('Arial','',8); //normal
    if (!empty($cObsResultado) ){
        //$this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc*3,utf8_decode(($cObsResultado)),0,2,'L');
        $this->pdf->write($nInc/2,utf8_decode($cObsResultado));
        $this->pdf->ln($nInc/2);
    }

    // checando las condiciones de la recepcion muestra 
    if (!empty($cCondMuestra)) {
        $this->pdf->SetFont('Arial','B',8); 
        $this->pdf->write($nInc/2,utf8_decode('Condiciones de Recepción de la Muestra:'));
        $this->pdf->SetFont('Arial','',8); //normal
        $this->pdf->write($nInc/2,utf8_decode($cCondMuestra));
        $this->pdf->ln($nInc);
    }

    // FIRMA DEL TECNICO
    //$this->pdf->ln($nInc);
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,'___________________________________________________',0,2,'C');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode($_SESSION['user_nombre']),0,2,'C');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode($_SESSION['cargo_usuario']),0,2,'C');
    //$this->pdf->ln($nInc/2);

    // DEMAS COSAS
    
    $this->pdf->SetFont('Arial','',6); //normal

    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('(*) Metodo acreditado.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('El presente informe avala unicamente el resultado de la muestra analisada y recibida en el laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('El presente informe no es válido si presenta raspaduras, tachaduras o enmendaduras.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('Prohibida la reproducción parcial o total de este informe sin la autorización del laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc/2,utf8_decode('El plazo para realizar aclaraciones en cuanto al contenido del presente informe es de 5 dias hábiles, despues de su emisión.'),0,2,'L');
    $this->pdf->ln($nInc/2);

    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');

    // FIN DE COMENZAR EL REPORTE 
  }

  /************************************************************************  
  				SOLICITUD QUE SE ALMACEN EN RECEPCION DE MUESTRAS  		
  *************************************************************************/  
  /**************************************************/
  public function prueba_html2(){
  	// empezamos el pdf
  	
  	$this->load->library('utilerias');
    $this->load->library('pdf');
    $this->pdf = new pdf();
    //$this->pdf->setNombreReporte('Solicitud de Servicios de Laboratorio');    
    $this->pdf->AddPage('l','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->SetMargins(5,20,10);
    $this->pdf->AliasNbPages(); 	
	//$this->pdf->SetFont('Arial');
	$this->pdf->SetFont('Arial','',8);
	
	
	
	
	//$this->pdf->WriteHTML('You can<br><p align="center">center a line</p>and add a horizontal rule:<br><hr>');
	$this->pdf->ln(20);
	$query = $this->db->query('select * from estudios where ID_ESTUDIO = 20')->row();
	$cMetodologia = utf8_decode($query->METODOLOGIA_ESTUDIO);
	$this->pdf->WriteHTML('You can<i>cursiva</i> y seguir sin ellas');
	$this->pdf->ln(10);
	$this->pdf->WriteHTML( $cMetodologia );
	$this->pdf->ln(10);
	
	$mCad = $this->utilerias->divide_cadena($cMetodologia,90);
	$this->pdf->ln(10);
	$this->pdf->WriteHTML($mCad[0]);
	$this->pdf->ln(10);
	$this->pdf->WriteHTML($mCad[1]);
	$this->pdf->ln(10);
	$this->pdf->WriteHTML($mCad[2]);
    
    $nFolio = 1000;
    
    $this->pdf->ln(10);
    $this->pdf->ln(10);
    $this->pdf->ln(10);
    
    
    $this->pdf->cellHtml(90,12,$cMetodologia,1,'T' ,0 );
    
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$nFolio;    
          
    $this->pdf->Output($cNombreArchivo,'I');
  }
  /* *************************************************************************/
  public function SolicitudLaboratorio($idFolio2 = 'null'){
    $idFolio = 71;
    if (isset($idFolio2)) {
        $idFolio = $idFolio2;
    }

    $data = $this->estudios_model->getAllEstudio($idFolio);   
 
    if (count($data)== 0) {
        //echo "<script>alert('Folio: ['.$idFolio.'] sin informacion');</script>";
        echo '<script>alert("Error Folio ['.$idFolio.' ] Inexistente" );</script>';
        //echo 'Folio de Solicitud Inexistente';
        exit();        
    }
    // CARGANDO LAS VARIABLES
    $nFolio          = date('y').'-'.str_pad($data[0]->FOLIO_SOLICITUD,4,'0',STR_PAD_LEFT);
    $dFechaHora      = strtotime($data[0]->FECHA_RECEPCION);
    // add 10/05/2017
    //$dFechaHora      = date($dFechaHora,'Y-m-d H:i a');
    $dFechaHora      = date('Y-m-d H:i:s',$dFechaHora);
    
    $cNombreCte      = utf8_decode($data[0]->NOMBRE_CLIENTE);
    $cDireCte         = utf8_decode($data[0]->DOMICILIO_CLIENTE);
    $cRfc             = $data[0]->RFC_CLIENTE;
    $cTel             = $data[0]->TELEFONO_CLIENTE;
    $cContacto        = utf8_decode($data[0]->CONTACTO_CLIENTE);
    $cEmail           = $data[0]->EMAIL_CLIENTE;
    $cObsRecepcion	  = $data[0]->OBSERVACIONES_RECEPCION;
    
    

	//VARIABLES NUEVAS POR EL CUADRO DE IDR QUE ANTES ERA FACTURACION
	$cRespTomaMuestra 	= ""; 
    $dFechaHoraTomaMuestra = "";
	$cNombreCte_IDR 	= "";
	$cDireCte_IDR 		= "";
	$cRfc_IDR 			= "";
	$cContacto_IDR 		= "";
	if ($data[0]->TOMO_MUESTRA!=null) { $cRespTomaMuestra = $data[0]->TOMO_MUESTRA; }
	if ($data[0]->NOMBRE_IDR_CLIENTE!=null) {$cNombreCte_IDR   = utf8_decode($data[0]->NOMBRE_IDR_CLIENTE);}
	if ($data[0]->DOMICILIO_IDR_CLIENTE!=null) {$cDireCte_IDR = utf8_decode($data[0]->DOMICILIO_IDR_CLIENTE);}
	if ($data[0]->RFC_IDR_CLIENTE!=null) {$cRfc_IDR = $data[0]->RFC_IDR_CLIENTE;	}        
	if ($data[0]->CONTACTO_IDR_CLIENTE!=null) {$cContacto_IDR = utf8_decode($data[0]->CONTACTO_IDR_CLIENTE);}
      
    // VARIABLES DE IMPORTES OTROS Y COSTO DE ENVIO 29/05/2017
    $nCosto_Envio = 0;
    $nOtrosServicios = 0;
    //if (($data[0]->COSTO_ENVIO)>0)   { $nCosto_Envio =			$data[0]->COSTO_ENVIO; } 
    if (($data[0]->OTROS_SERVICIO)>0){ $nOtrosServicios =		$data[0]->OTROS_SERVICIO; } 
    
    //15/06/2017
    $cDestinoMuestra = $data[0]->DESTINO_MUESTRA;
	//16/06/2017
	$cCondiciones	  = $data[0]->CONDICIONES_MUESTRA;
    $cTipoMuestra 	  = $data[0]->TIPO_MUESTRA;
    $cPesoVol		  = $data[0]->PESO_VOL_MUESTRA;
    $cTemperatura	  = $data[0]->TEMPERATURA_MUESTRA;

    //2017-11-26
    $dFechaHoraTomaMuestra = $data[0]->FECHA_HORA_TOMA_MUESTRA;
      
      // empezamos el pdf
    $this->load->library('pdf');
    $this->pdf = new pdf();
    $this->pdf->setNombreReporte('Solicitud de Servicios de Laboratorio');    
    $this->pdf->AddPage('l','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->SetMargins(5,20,10);
    $this->pdf->AliasNbPages();
    //$this->pdf->SetAutoPageBreak( 'true' , 30 ) ; // indica que debe brincar a otra pagina cuando el margen llegue a 30 cm antes del final
    
    
    
    $r = 25;
    $c = 10;
    
    // RECTANGULO REGISTRO DEL SERVICIO
    $c1 = 10;
    $c2 = 120;
    $r1 = 25;
    $r2 = 35;
    ;

    $nPosEnc = array('col1'=>10,'col2'=>25,'col3' => 65,'col4' => 80,'col5' => 90,'col6' => 100,'col7' => 130);
    $r = 25;
    $nInc = 6;
    $this->pdf->setxy($nPosEnc['col1'],$r);
    $this->pdf->SetFont('Arial','B',8);
    
    //$this->pdf->cell(0,0,$data[0]->COSTO_ENVIO);
    //$this->pdf->cell(0,0,$data[0]->OTROS_CONCEPTOS);   
    
    
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col1'],$nInc,'REGISTRO DEL SERVICIO',1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Folio:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col2'],$nInc,$nFolio,1,0,'L');
    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col4'],$nInc,'Fecha/Hora:',1,0,'L');
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col6'],$nInc,$dFechaHora,1,0,'L');



    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col1'],$nInc,'CLIENTE',1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Nombre:',1,0,'L');
    $this->pdf->cellFitScale($nPosEnc['col7']-$nPosEnc['col2'],$nInc,($cNombreCte),1,0,'L'); 



    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Domicilio:',1,0,'L');
    $this->pdf->cellFitScale($nPosEnc['col7']-$nPosEnc['col2'],$nInc,($cDireCte),1,0,'L'); 

    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'RFC:',1,0,'L');
    $this->pdf->cell($nPosEnc['col3']-$nPosEnc['col2'],$nInc,$cRfc,1,0,'L'); 
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode('Teléfono'),1,0,'L'); 

    //$this->pdf->cellFitScale($nPosEnc['col7']-$nPosEnc['col4'],$nInc,$cTel,1,0,'L'); 
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col4'],$nInc,$cTel,1,0,'L'); 

    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->Cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Contacto:',1,0,'L');    
    $this->pdf->cellFitScale($nPosEnc['col3']-$nPosEnc['col2'],$nInc,$cContacto,1,0,'L');     
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode('e-mail:'),1,0,'L');    
   	$this->pdf->cellFitScale($nPosEnc['col7']-$nPosEnc['col4'],$nInc,$cEmail,1,0,'L'); 	

    //RECTANGULO FACTURACION Q CAMBIO A IDR-->
    $nPosEnc = array('col1'=>140,'col2'=>165,'col3' => 190,'col4' => 270);
    $this->pdf->setxy($nPosEnc['col1'],$r);
    

    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc,utf8_decode('DATOS PARA INFORME DE RESULTADOS (en caso de ser diferente)'),1,2,'C',true);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Nombre:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col2'],$nInc,($cNombreCte_IDR),1,0,'L');

    $this->pdf->ln(); $this->pdf->setx($nPosEnc['col1']);

    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Domicilio fiscal:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col2'],$nInc,($cDireCte_IDR),1,0,'L');

    $this->pdf->ln(); $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'RFC:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col2'],$nInc,$cRfc_IDR,1,0,'L');

    $this->pdf->ln(); $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'Contacto:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col2'],$nInc,($cContacto_IDR),1,0,'L');

    $this->pdf->ln(); 

    
    
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Responsable de la toma de muestra:',1,0,'L'); 

    if ($cRespTomaMuestra) {	   
	   $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cRespTomaMuestra),1,'L');       
	}else {		
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode(""),1,'L');
	}
    $this->pdf->ln(); 

    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Fecha/Hora:',1,0,'L');
    if ($dFechaHoraTomaMuestra ) {       
       $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($dFechaHoraTomaMuestra ),1,'L');       
    }else {     
        $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode("" ),1,'L');
    }
    $this->pdf->ln(); 


    //15/06/2017 AGREGANDO EL DESTINO DE LA MUESTRA
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->cell($nPosEnc['col3']-$nPosEnc['col1'],$nInc,'Destino de la muestra:',1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cDestinoMuestra),1,2,'L');
    
    //$this->pdf->ln();     

    
    // TEMPORAL
    $this->pdf->ln($nInc/2);
    
    //$r=$r+$nInc *9;
    $nInc = 10;
    
    $nPosEnc = array('col1' => 10,'col2'=>25,'col3'=>50,'col4' =>95,'col5' => 110,'col6'=>125,'col7'=>160,'col8'=>205,'col9' =>250,'col10'=>270);
    
    $this->pdf->setx($nPosEnc['col1']);
    $this->pdf->SetFont('Arial','B',6);


    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'ID DE MUESTRA',1,'C',1,true);    
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,'ID ASIGNADA POR EL CLIENTE',1,'C',1,true);    
    
    //15/06/2017
    $nRow = $this->pdf->gety();
    //$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,0,'C',true);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
        
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc,'ENSAYO',1,'C',1,true);    
    $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO'),1,'C',1,true);    
    $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col9'],$nInc,'IMPORTE',1,'C',1,true);      
    
    
    $this->pdf->setxy($nPosEnc['col3'],$nRow+($nInc/2));
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'TIPO DE MUESTRA',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,'PESO/VOL.',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,'TEMP.',1,0,'C',true);
    
    
    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);

    // IMPRIMIENDO AHORA SI LOS CAMPOS
    $i = 0;
    //$r=$r+10;
    $nInc = 12; //--> 2017-09-07 --> se incfreomento por incremento en las metodologias..!
    
    
    
    $this->pdf->setx($nPosEnc['col1']);
    
    
    
    $nTotalImporte = 0; // 11/05/2017
    $cLeyenda = "";	
	$this->load->library('utilerias');
	
	//2017-08-08 --> leyendas a incorporar
	$cLeyendaAcreditado = "";
	$cLeyendaReconocido = "";
	
	$r = $this->pdf->GetY();
	
	$nTotalMuestras = 1;
	$cIdMuestraActual = $data[$i]->ID_MUESTRA;
	
    foreach ($data as $key => $value) { // ciclo de las metodoligas
    	//2018-01-30 --> SABER EL TOTAL DE MUESTRAS
    	if ($data[$i]->ID_MUESTRA != $cIdMuestraActual){
			$nTotalMuestras += 1;
			$cIdMuestraActual = $data[$i]->ID_MUESTRA;
		}
    	$this->pdf->SetFont('Arial','',7);	
    	//$r = $this->pdf->GetY();
    	
    	$this->pdf->setxy($nPosEnc['col1'],$r);
      	
        
        $this->pdf->MultiCell( $nPosEnc['col2']-$nPosEnc['col1'],$nInc,$this->utilerias->Personaliza_ID_MUESTRA($data[$i]->ID_MUESTRA),1,'C' );
        $this->pdf->setxy($nPosEnc['col2'],$r);
		
		$cId_Cliente = utf8_decode($data[$i]->ID_ASIGNADO_CLIENTE);
        //$this->pdf->MultiCell( $nPosEnc['col3']-$nPosEnc['col2'],$nInc,$cId_Cliente ,1,'C' );
        $this->pdf->MultiCelda( $nPosEnc['col3']-$nPosEnc['col2'],$nInc,$cId_Cliente,1,'C',0 );
        
        $cDestinoMuestra = $data[$i]->DESTINO_MUESTRA;		
		$cCondiciones	  = $data[$i]->CONDICIONES_MUESTRA;
    	$cTipoMuestra 	  = $data[$i]->TIPO_MUESTRA;
    	$cPesoVol		  = $data[$i]->PESO_VOL_MUESTRA;
    	$cTemperatura	  = $data[$i]->TEMPERATURA_MUESTRA;
        
        $this->pdf->setXY($nPosEnc['col3'],$r);
        //$this->pdf->cell( $nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cTipoMuestra),1,0,'C' );
        $this->pdf->MultiCelda( $nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cTipoMuestra),1,'C',0 ); 
        //$this->pdf->cell( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cPesoVol),1,0,'C' );
        //$this->pdf->Multicell( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cPesoVol),1,'C' );
        $this->pdf->setXY($nPosEnc['col4'],$r);
        $this->pdf->MultiCelda( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($data[$i]->PESO_VOL_MUESTRA),1,'C',0 ); 
        
        $this->pdf->setXY($nPosEnc['col5'],$r);
        $this->pdf->cell( $nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cTemperatura),1,0,'C' );

        $this->pdf->setXY($nPosEnc['col6'],$r);        
        //$this->pdf->cellHtml($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 );        
        $this->pdf->MultiCell( $nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 ); 
        
        $this->pdf->setXY($nPosEnc['col7'],$r);   
        $cEnsayoTMP = utf8_decode($data[$i]->ANALISIS_SOLICITADO);       
        $this->pdf->cellHtml($nPosEnc['col8']-$nPosEnc['col7'],$nInc,$cEnsayoTMP,1,'T' ,0 );        
        
        $this->pdf->setXY($nPosEnc['col8'],$r);   
        $cMetodologiaTMP = utf8_decode($data[$i]->METODOLOGIA_ESTUDIO);
        //$cMetodologiaTMP = substr( $cMetodologiaTMP,0,strpos($cMetodologiaTMP," ")+5);
        //$this->pdf->cellHtml($nPosEnc['col9']-$nPosEnc['col8'],$nInc,$cMetodologiaTMP,1,'T' ,0 );
        $this->pdf->MultiCell( $nPosEnc['col9']-$nPosEnc['col8'],$nInc,($cMetodologiaTMP),1,'L',0 ); 
        
        
        $this->pdf->setxy($nPosEnc['col9'],$r);

        $this->pdf->MultiCell( $nPosEnc['col10']-$nPosEnc['col9'],$nInc,number_format($data[$i]->IMPORTE*25/29,2),1,'R' );
        
        
        $nTotalImporte = $nTotalImporte + $data[$i]->IMPORTE; // agregado 11/05/2017 para q empieze a desglozar el iva
        
        $r = $r + $nInc ; // incrementamos por cada renglon 5
        //$this->pdf->setxy($nPosEnc['col1'],$r);
        
        $this->pdf->ln($nInc);        
        //$this->pdf->setxy($nPosEnc['col8'],$r);               
        
         if ( $r+20 > 205) { 
    		$this->pdf->AddPage('l','letter'); 
    		$r = $this->pdf->GetY();
    		
    		$this->pdf->setx($nPosEnc['col1']);
    		$this->pdf->SetFont('Arial','B',6);

		    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'ID DE MUESTRA',1,'C',1,true);    
		    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,'ID ASIGNADA POR EL CLIENTE',1,'C',1,true);    
		    
		    //15/06/2017
		    $nRow = $this->pdf->gety();
		    //$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,0,'C',true);
		    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
		        
		    		    
            $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
            $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc,'ENSAYO',1,'C',1,true);    
            $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO'),1,'C',1,true);    
            $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col9'],$nInc,'IMPORTE',1,'C',1,true); 
		    
		    $this->pdf->setxy($nPosEnc['col3'],$nRow+($nInc/2));
		    
		    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'TIPO DE MUESTRA',1,0,'C',true);
		    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,'PESO/VOL.',1,0,'C',true);
		    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,'TEMP.',1,0,'C',true);
		    
		    
		    $this->pdf->ln();
		    $r = $this->pdf->GetY();
		       
    		
		} 
        
        // ANEXADO 23/05/2017 PARA BUSCAR SI ES METODO VALIDADO O ACREDITADO
        if ($data[$i]->VALIDADO_ESTUDIO == 'N'){
			$cLeyenda = $cLeyenda . 'Leyenda para Metodo no Validado '; // NO SE ESTA EMPLEANDO..!
		} 
		if ($data[$i]->ACREDITADO_ESTUDIO == 'S'){
			$cLeyendaAcreditado = '(*) Acreditación No. A-0733-074/16. Vigente a partir del 2016-05-19.';
		}
		if ($data[$i]->RECONOCIDO_ESTUDIO == 'S'){
			$cLeyendaReconocido = '(¹) Método reconocido por el SENASICA, de acuerdo a lo indicado en el Oficio Nº. B00.04.02.05 3817/2017.';
			if ($data[$i]->AREA_ESTUDIO == 'M'){
				$cLeyendaReconocido = '(²) Método reconocido por el SENASICA, de acuerdo a lo indicado en el Oficio Nº. B00.04.02.05 3818/2017.';
			}
		}
              
        
        $i++;      
    } // fin del foreach AQUI TERMINA EL CICLO
    
    
    //$this->pdf->ln(1);
    // LA PARTE DE LOS COSTOS.
    $nInc2 = 4;  //ALTURA
    
	
	if ( $r > 165) { 
    	$this->pdf->AddPage('l','letter'); 
    	//$r = $this->pdf->GetY();
    	
    	$this->pdf->setx($nPosEnc['col1']);
		$this->pdf->SetFont('Arial','B',6);

	    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'ID DE MUESTRA',1,'C',1,true);    
	    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,'ID ASIGNADA POR EL CLIENTE',1,'C',1,true);    
	    
	    //15/06/2017
	    $nRow = $this->pdf->gety();
	    //$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,0,'C',true);
	    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
	        
	    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
        $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc,'ENSAYO',1,'C',1,true);    
        $this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,utf8_decode('MÉTODO'),1,'C',1,true);    
        $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col9'],$nInc,'IMPORTE',1,'C',1,true);    
	    
	    
	    $this->pdf->setxy($nPosEnc['col3'],$nRow+($nInc/2));
	    
	    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'TIPO DE MUESTRA',1,0,'C',true);
	    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,'PESO/VOL.',1,0,'C',true);
	    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,'TEMP.',1,0,'C',true);
	    
	    
	    $this->pdf->ln();
	    $r = $this->pdf->GetY();  
	    $this->pdf->SetFont('Arial','',8);  	
	}
    if ($r>165){ // por las leyendas
        $this->pdf->AddPage('l','letter'); 
        $r = $this->pdf->GetY();
    }
	
	$this->pdf->setxy($nPosEnc['col8'],$r);

    // imprimiendo la parte de abajo de los subtotales y eso
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, 'Costo de Servicios',0,2,'R');
    //$this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, utf8_decode('Costo de envío'),0,2,'R');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, 'Otros Servicios',0,2,'R');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, 'SubTotal',0,2,'R');    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, 'IVA(_%)',0,2,'R');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc2, 'Total',0,2,'R');
    
    $this->pdf->setxy($nPosEnc['col9'],$r);
    
    $nTmp = 0;  // campo que sera sustituido por costo de servicios, de envio y otros cocepto    
    
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc2, number_format($nTotalImporte *25/29,2) ,1,2,'R');    
    //$this->pdf->cell($nPosEnc['col9']-$nPosEnc['col8'],$nInc2, number_format($nCosto_Envio*25/29,2) ,1,2,'R');    
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc2, number_format($nOtrosServicios*25/29,2) ,1,2,'R');    
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc2, number_format(($nTotalImporte+$nCosto_Envio+$nOtrosServicios) * 25/29,2) ,1,2,'R');    
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc2, number_format(($nTotalImporte+$nCosto_Envio+$nOtrosServicios) * 4/29,2) ,1,2,'R'); 
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc2, number_format($nTotalImporte+$nCosto_Envio+$nOtrosServicios,2) ,1,2,'R'); 
    
    
    // imprimiendo las leyendas 
    
    //$this->pdf->sety($r+5);    
    $this->pdf->sety($r);    //2018-01-30 --> le quite el +5 para poner la leyenda de total de muestras recibidas
    
    $this->pdf->setx($nPosEnc['col1']);
    // leyendas de recien adecuacion dependiendo de los estudios que participan
    $this->pdf->cell(30,$nInc2,utf8_decode('Total de Muestras Recibidas: '.$nTotalMuestras),0,2);
    $this->pdf->cell(30,$nInc2,utf8_decode('El periodo establecido para realizar modificaciones (datos del cliente o de facturación) en la Solicitud de servicios de laboratorio, es de tres días hábiles a partir de '),0,2);
    $this->pdf->cell(30,$nInc2,utf8_decode('la aceptacion de su muestra, previa solicitud por escrito.'),0,2);
    $this->pdf->cell(80,$nInc2,utf8_decode('El tiempo estimado para la emisión del informe de resultados es de 10 días hábiles.'),0,2);
    
    $this->pdf->cell(80,$nInc2,utf8_decode('Las muestras recibidas a partir de las 14:00 h, serán analizadas al día hábil siguiente.'),0,2);
    if ($cLeyendaAcreditado){
		$this->pdf->cell(80,$nInc2,utf8_decode($cLeyendaAcreditado),0,2);
	}
	if ($cLeyendaReconocido){
		$this->pdf->cell(80,$nInc2,utf8_decode($cLeyendaReconocido),0,2);
	}
    
    //imprimiendo los 3 cuadros finales    
    $r = $this->pdf->getY();   
    if ( $r > 170 ) { 
    	$this->pdf->AddPage('l','letter'); 
    	$r = $this->pdf->GetY();
	} 
    $nPosEnc = array('col1' => 10,'col1.1'=>30,'col2'=>80,'col2.1'=>100,'col3'=>150,'col4' =>215,'col5'=>270);
    // 2017-11-28 --> ANEXANDO AL NUEVO FORMATO LAS CONDICIONES Y OBSERVACIONES..!
    
    $nRowIni = $this->pdf->getY();
    $nInc3 = 3;

    $this->pdf->setXY($nPosEnc['col1'],$r+1);    
    
    if (isset($data[0]->CONDICIONES_MUESTRA)) {  
        $this->pdf->SetFont('Arial','B',8); 
        $this->pdf->cell(0,$nInc3,utf8_decode('Condiciones de recepción de la muestra:'),0,2);   
        $this->pdf->SetFont('Arial','',8);         
        $this->pdf->multicell(0,$nInc3,utf8_decode($data[0]->CONDICIONES_MUESTRA),'0','J',false );    
    }else {
        $this->pdf->cell(0,$nInc3*2,utf8_decode('Condiciones de recepción de la muestra:'),0,2);   
    }    
    $this->pdf->rect($nPosEnc['col1'],$nRowIni,$nPosEnc['col5']-$nPosEnc['col1'],$this->pdf->getY()-$nRowIni);

    // AHORA LAS OBSERVACIONES
    $r = $this->pdf->getY();    
    if ( $r > 170 ) { 
        $this->pdf->AddPage('l','letter'); 
        $r = $this->pdf->GetY();
    } 
    $nRowIni = $this->pdf->getY();
    $this->pdf->setXY($nPosEnc['col1'],$r+1);
    if (isset($data[0]->OBSERVACIONES_RECEPCION)) {  
        $this->pdf->SetFont('Arial','B',8); 
        $this->pdf->cell(0,$nInc3,utf8_decode('Observaciones:'),0,2);   
        $this->pdf->SetFont('Arial','',8);         
        $this->pdf->multicell(0,$nInc3,utf8_decode($data[0]->OBSERVACIONES_RECEPCION),'0','J',false );    
    }else {
        $this->pdf->cell(0,$nInc3*2,utf8_decode('Observaciones:'),0,2);   
    }    
    $this->pdf->rect($nPosEnc['col1'],$nRowIni,$nPosEnc['col5']-$nPosEnc['col1'],$this->pdf->getY()-$nRowIni);
    $this->pdf->ln( $nInc3);

    // LOS CUADROS DE ABAJO
    $r = $this->pdf->getY();   
    if ( $r > 180 ) { 
        $this->pdf->AddPage('l','letter'); 
        $r = $this->pdf->GetY();
    } 
    $nInc3 = 4;
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->setXY($nPosEnc['col1'],$r);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,'RECEPCION',1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $nRowIni = $this->pdf->getY();    
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,'Recibido por (nombre y firma):',0,2,'L',false);
    $cNomUserRealizoCaptura = $this->db->query('select NOMBRE_USUARIO from usuarios where id_usuario = '.$data[0]->ID_USUARIO)->row();
    $cNomUserRealizoCaptura = $cNomUserRealizoCaptura->NOMBRE_USUARIO;
    //$this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col1'],$nInc/2,$cNomUserRealizoCaptura,0,1,'C' );    
    
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,utf8_decode($cNomUserRealizoCaptura),0,2,'C',false);
    $dFechaX = substr($data[0]->FECHA_RECEPCION,0,10);
    $this->pdf->cell($nPosEnc['col1.1']-$nPosEnc['col1'],$nInc3,'Fecha/Hora:',1,0,'L',false);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1.1'],$nInc3,$data[0]->FECHA_RECEPCION,1,0,'L',false);
    $this->pdf->rect($nPosEnc['col1'],$nRowIni,$nPosEnc['col2']-$nPosEnc['col1'],$nInc3*3);
    
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->setXY($nPosEnc['col2'],$r);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,'CLIENTE',1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $nRowIni = $this->pdf->getY();    
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,'Entregado por (nombre y firma):',0,2,'L',false);
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc3,utf8_decode(""),0,2,'C',false);
    $dFechaX = substr($data[0]->FECHA_RECEPCION,0,10);
    $this->pdf->cell($nPosEnc['col2.1']-$nPosEnc['col2'],$nInc3,'Fecha/Hora:',1,0,'L',false);
    $this->pdf->cell($nPosEnc['col3']-$nPosEnc['col2.1'],$nInc3,"",1,0,'L',false);
    $this->pdf->rect($nPosEnc['col2'],$nRowIni,$nPosEnc['col3']-$nPosEnc['col2'],$nInc3*3);
    // ENVIO DE RESULTADOS CUADRO
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->setXY($nPosEnc['col3'],$r);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc3,utf8_decode('ENVÍO DE RESULTADOS'),1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $nRowIni = $this->pdf->getY();    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc3,utf8_decode('Atención a'),0,2,'L',false);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc3,utf8_decode(""),0,2,'C',false);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc3,utf8_decode('Impreso [ ] email [ ] paquetería [ ]'),0,2,'C',false);
    $this->pdf->rect($nPosEnc['col3'],$nRowIni,$nPosEnc['col4']-$nPosEnc['col3'],$nInc3*3);
    //REGISTRO DE ENTREGA DE RESULTADOS..!
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->setXY($nPosEnc['col4'],$r);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc3,utf8_decode('REGISTRO DE ENTREGA DE RESULTADOS'),1,2,'C',true);
    $this->pdf->SetFont('Arial','',8);
    $nRowIni = $this->pdf->getY();    
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc3,utf8_decode('No. Informe'),0,2,'L',false);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc3,utf8_decode("Recibido por (nombre y firma):"),0,2,'C',false);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc3,utf8_decode(''),0,2,'C',false);
    $this->pdf->rect($nPosEnc['col4'],$nRowIni,$nPosEnc['col5']-$nPosEnc['col4'],$nInc3*3);    
    
	  

    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$nFolio;    
          
    $this->pdf->Output($cNombreArchivo,'I');
    
  }
  /*******************************************************************************************  
  **********************************************************************************************/
  public function EntregaMuestra($idFolio2 = 'null'){
  	$idFolio = 71;
    if (isset($idFolio2)) {
        $idFolio = $idFolio2;
    }

    $data = $this->estudios_model->getAllEstudio($idFolio);

    if (count($data)== 0) {
            echo '<script>alert("Error Folio ['.$idFolio.' ] Inexistente" );window.close();</script>';    
        exit();
    }
    // CARGANDO LAS VARIABLES
    //$nFolio          = str_pad($data[0]->ID_RECEPCION_MUESTRA,4,'0',STR_PAD_LEFT);
    //$dFechaHora      = $data[0]->FECHA_RECEPCION;
    $cNombreCte      = utf8_decode($data[0]->NOMBRE_CLIENTE);
    $cDireCte         = utf8_decode($data[0]->DOMICILIO_CLIENTE);
    $cRfc             = $data[0]->RFC_CLIENTE;
    $cTel             = $data[0]->TELEFONO_CLIENTE;
    $cContacto        = utf8_decode($data[0]->CONTACTO_CLIENTE);
    $cEmail           = $data[0]->EMAIL_CLIENTE;     

	// 24/05/2017
 	$nFolio          = date('y').'-'.str_pad($data[0]->FOLIO_SOLICITUD,4,'0',STR_PAD_LEFT);
    $dFechaHora      = strtotime($data[0]->FECHA_RECEPCION);
    $dFechaHora      = date('Y-m-d H:i:s',$dFechaHora);
      
      // empezamos el pdf
    $this->load->library('pdf');
    $this->pdf = new pdf();
    $this->pdf->setNombreReporte('Entrega de Muestras');    
    $this->pdf->AddPage('l','letter'); //l landscape o P normal
    $this->pdf->AliasNbPages();
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->SetMargins(5,20,10);
    $this->pdf->SetAutoPageBreak( 'true' , 15 ) ; // indica que debe brincar a otra pagina cuando el margen llegue a 30 cm antes del final
    $this->pdf->AliasNbPages();
    
    $nPosEnc = array('col1' => 10,'col2'=>35,'col3'=>60,'col3.5'=>80,'col4'=>110,'col5' =>140,'col6' => 160,'col7'=>176,'col8'=>190,'col9' =>205,'col10'=>225,'col11'=>270);

    
    $r = 25;
    $nInc = 5;
    $this->pdf->setxy($nPosEnc['col4'],$r);
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col4'],$nInc,'REGISTRO DEL SERVICIO',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc,'Folio:',1,0,'C',true);
    $this->pdf->SetFont('Arial','',7);    
    $this->pdf->cell($nPosEnc['col9']-$nPosEnc['col8'],$nInc,$nFolio,1,0,'L');
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc,'Fecha/Hora:',1,0,'L',true);
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col10'],$nInc,$dFechaHora,1,2,'L');
    
    $r = $this->pdf->getY();

    $this->pdf->setxy($nPosEnc['col7'],$r);
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->multicell($nPosEnc['col9']-$nPosEnc['col7'],$nInc,utf8_decode('Responsable de la toma de muestra:'),1,'R',true);
    
    $this->pdf->SetFont('Arial','',7);
    $this->pdf->setxy($nPosEnc['col9'],$r);
    $cRespTomaMuestra = "";
    if ($data[0]->TOMO_MUESTRA!=null) { $cRespTomaMuestra = $data[0]->TOMO_MUESTRA; }
    
    $this->pdf->multicell($nPosEnc['col11']-$nPosEnc['col9'],$nInc*2,utf8_decode($cRespTomaMuestra),1,'C');   
    
    $r = $this->pdf->getY();
    $this->pdf->setxy($nPosEnc['col7'],$r);
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->cell($nPosEnc['col9']-$nPosEnc['col7'],$nInc,utf8_decode('Fecha/hora:'),1,0,'R',true);
    $dFechaHoraTomaMuestra = "";
    if ($data[0]->FECHA_HORA_TOMA_MUESTRA){ $dFechaHoraTomaMuestra = $data[0]->FECHA_HORA_TOMA_MUESTRA;}
    $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col9'],$nInc,$dFechaHoraTomaMuestra,1,2,'C');

    
    $this->pdf->ln($nInc);
    $r = $this->pdf->getY();
  
   
    //$r=$r+$nInc+5;
    $nInc = 10;
    $nPosEnc = array('col1' => 10,'col2'=>35,'col3'=>60,'col3.5'=>90,'col4'=>110,'col5' =>140,'col6' => 160,'col7'=>176,'col8'=>190,'col9' =>205,'col10'=>225,'col11'=>270);
    
    // 2017-11-30 --> SE ANEXO UNA FUNCION ENCABEZADO DE LA TABLA DE ENTREGA DE MUESTRA
    $this->encabezado_tabla_entrega_muestra( $nPosEnc,$nInc );  
    
    $this->pdf->setx($nPosEnc['col1']);    
    $this->pdf->SetFont('Arial','',7);

	// IMPRIMIENDO AHORA SI LOS CAMPOS
    $i = 0;
    //$r=$r+10;
    $nInc = 12;
    $this->pdf->SetFont('Arial','',7);
    
    $nTotalImporte = 0; // 11/05/2017
    $cLeyenda = "";

	$this->load->library('utilerias');
	$r = $this->pdf->GetY();
    foreach ($data as $key => $value) { // ciclo de las metodoligas
        
        $this->pdf->setxy($nPosEnc['col1'],$r);
      	        
        $this->pdf->multicell( $nPosEnc['col2']-$nPosEnc['col1'],$nInc,$this->utilerias->Personaliza_ID_MUESTRA($data[$i]->ID_MUESTRA),1,'C' );
        $this->pdf->setxy($nPosEnc['col2'],$r);
		
		$nAlturaPagina = $this->pdf->GetPageHeight();
        //$this->pdf->multicell( $nPosEnc['col3']-$nPosEnc['col2'],$nInc,$data[$i]->ID_ASIGNADO_CLIENTE,1,'C' );
        $this->pdf->MultiCelda( $nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode($data[$i]->ID_ASIGNADO_CLIENTE),1,'C',0 );
        
        
        
        $cDestinoMuestra = $data[$i]->DESTINO_MUESTRA;		
		$cCondiciones	  = $data[$i]->CONDICIONES_MUESTRA;
    	$cTipoMuestra 	  = $data[$i]->TIPO_MUESTRA;
    	$cPesoVol		  = $data[$i]->PESO_VOL_MUESTRA;
    	$cTemperatura	  = $data[$i]->TEMPERATURA_MUESTRA;
        
        $this->pdf->setxy($nPosEnc['col3'],$r);        
        $this->pdf->MultiCelda( $nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cTipoMuestra),1,'C',0 ); 
        
        $this->pdf->setXY($nPosEnc['col4'],$r);
        $this->pdf->MultiCelda( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($data[$i]->PESO_VOL_MUESTRA),1,'C',0 ); 
        
        $this->pdf->setXY($nPosEnc['col5'],$r);
        $this->pdf->cell( $nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cTemperatura),1,0,'C' );

        $this->pdf->setxy($nPosEnc['col6'],$r);        
        //$this->pdf->MultiCell( $nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 ); 
        $this->pdf->MultiCelda( $nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 ); 

        //2017-11-30 --> SEPARACION DEENSAYO Y  METODOLOGIA
        $this->pdf->setXY($nPosEnc['col7'],$r);   
        $cEnsayoTMP = utf8_decode($data[$i]->ANALISIS_SOLICITADO);       
        $this->pdf->cellHtml($nPosEnc['col10']-$nPosEnc['col7'],$nInc,$cEnsayoTMP,1,'T' ,0 );        
        
        $this->pdf->setXY($nPosEnc['col10'],$r);   
        $cMetodologiaTMP = utf8_decode($data[$i]->METODOLOGIA_ESTUDIO);
        //$cMetodologiaTMP = substr( $cMetodologiaTMP,0,strpos($cMetodologiaTMP," "));
        $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col10'],$nInc,$cMetodologiaTMP,1,2,'L' );
        
        $this->pdf->ln($nInc);
        $r = $r + $nInc ; // incrementamos por cada renglon 5
        
        $i++;  
        //if ( $r+20 > 170) { 
        if ( $r > 180) { 
    		$this->pdf->AddPage('l','letter');     		
            $this->encabezado_tabla_entrega_muestra( $nPosEnc,$nInc );  
            $r = $this->pdf->GetY();
		}        
    } // fin del foreach AQUI TERMINA EL CICLO	
    $this->pdf->setXY( $nPosEnc['col1'],$r);
    $this->pdf->ln($nInc/3);    
    
    //$r = $r+$nInc;
    // anexado 2017-11-30
    $r = $this->pdf->getY();
    if ( $r > 180 ) { 
        $this->pdf->AddPage('l','letter');
        //$this->encabezado_tabla_entrega_muestra( $nPosEnc,$nInc );
        $r = $this->pdf->GetY();
    } 
    
    // 2017-11-28 --> ANEXANDO AL NUEVO FORMATO LAS CONDICIONES Y OBSERVACIONES..!    
    $nRowIni = $this->pdf->getY();
    $nInc3 = 3;

    $this->pdf->setXY($nPosEnc['col1'],$r+1);
    
    if (isset($data[0]->CONDICIONES_MUESTRA)) {  
        $this->pdf->SetFont('Arial','B',7); 
        $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc3,utf8_decode('Condiciones de recepción de la muestra:'),0,2);   
        $this->pdf->SetFont('Arial','',7);         
        $this->pdf->multicell($nPosEnc['col11']-$nPosEnc['col1'],$nInc3,utf8_decode($data[0]->CONDICIONES_MUESTRA),'0','J',false );    
    }else {
        $this->pdf->cell(0,$nInc3*2,utf8_decode('Condiciones de recepción de la muestra:'),0,2);   
    }    
    $this->pdf->rect($nPosEnc['col1'],$nRowIni,$nPosEnc['col11']-$nPosEnc['col1'],$this->pdf->getY()-$nRowIni);

    // AHORA LAS OBSERVACIONES
    $r = $this->pdf->getY();
    if ( $r > 170 ) { 
        $this->pdf->AddPage('l','letter');
        $r = $this->pdf->GetY();
    } 
    // AHORA LAS OBSERVACIONES..!    
    
    $nRowIni = $this->pdf->getY();
    $nInc3 = 3;

    $this->pdf->setXY($nPosEnc['col1'],$r+1);    
    
    if (isset($data[0]->OBSERVACIONES_RECEPCION)) {  
        $this->pdf->SetFont('Arial','B',7); 
        $this->pdf->cell($nPosEnc['col11']-$nPosEnc['col1'],$nInc3,utf8_decode('Observaciones:'),0,2);   
        $this->pdf->SetFont('Arial','',7);         
        $this->pdf->multicell($nPosEnc['col11']-$nPosEnc['col1'],$nInc3,utf8_decode($data[0]->OBSERVACIONES_RECEPCION),'0','J',false );    
    }else {
        $this->pdf->cell(0,$nInc3*2,utf8_decode('Observaciones:'),0,2);   
    }    
    $this->pdf->rect($nPosEnc['col1'],$nRowIni,$nPosEnc['col11']-$nPosEnc['col1'],$this->pdf->getY()-$nRowIni);    
    // fin delo anexado 3011/17
    $this->pdf->ln($nInc/2);

    $r = $this->pdf->getY();
    if ( $r > 180) { 
        $this->pdf->AddPage('l','letter'); 
        $r = $this->pdf->GetY();
    }

    
    $this->pdf->setxy($nPosEnc['col1'],$r);
    $nInc3 = 4;
    
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col1'],$nInc3,utf8_decode('RECEPCIÓN'),1,0,'C',true);
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col3.5'],$nInc3,utf8_decode('RECIBIDO POR (Área)'),1,1,'C',true);    
    $this->pdf->SetFont('Arial','',7);
    
    //$this->pdf->ln();
    $r = $this->pdf->GetY();
    $this->pdf->setXY($nPosEnc['col1'],$r);   
    $this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col1']+1,$nInc/2,'Recibido por(nombre y firma):',0,0,'L'); 
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col3.5']+1,$nInc/2,'Recibido por(nombre y firma):',0,1,'L');
    $this->pdf->setX($nPosEnc['col1']); 
    //$this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col1'],$nInc/2,$_SESSION['user_nombre'],0,1,'C' );  -->2017-12-01  
    $cNomUserRealizoCaptura = $this->db->query('select NOMBRE_USUARIO from usuarios where id_usuario = '.$data[0]->ID_USUARIO)->row();
    $cNomUserRealizoCaptura = $cNomUserRealizoCaptura->NOMBRE_USUARIO;
    $this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col1'],$nInc/2,$cNomUserRealizoCaptura,0,1,'C' );    
    $this->pdf->setX($nPosEnc['col1']); 
    $this->pdf->rect($nPosEnc['col1'],$r,$nPosEnc['col3.5']-$nPosEnc['col1'],$this->pdf->getY()-$r);
    $this->pdf->setX($nPosEnc['col1']); 
    $this->pdf->rect($nPosEnc['col3.5'],$r,$nPosEnc['col7']-$nPosEnc['col3.5'],$this->pdf->getY()-$r);
    

    $this->pdf->setX($nPosEnc['col1']); 
    $this->pdf->cell($nPosEnc['col2']-$nPosEnc['col1'],$nInc/2,'Fecha/hora:',1,0,'L');
    $this->pdf->cell($nPosEnc['col3.5']-$nPosEnc['col2'],$nInc/2,$data[0]->FECHA_RECEPCION,1,0,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3.5'],$nInc/2,'Fecha/hora:',1,0,'L');
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col4'],$nInc/2,'',1,1,'L');  
 
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$nFolio;
    $this->pdf->Output($cNombreArchivo,'I'); 

	/**  
	DE AQUI PARA ABAJO ES LA REVISION DEL FORMATO 10 
	
	
    $idFolio = 71;
    if (isset($idFolio2)) {
        $idFolio = $idFolio2;
    }

    $data = $this->estudios_model->getAllEstudio($idFolio);

    if (count($data)== 0) {
            echo '<script>alert("Error Folio ['.$idFolio.' ] Inexistente" );window.close();</script>';    
        exit();
    }
    // CARGANDO LAS VARIABLES
    //$nFolio          = str_pad($data[0]->ID_RECEPCION_MUESTRA,4,'0',STR_PAD_LEFT);
    //$dFechaHora      = $data[0]->FECHA_RECEPCION;
    $cNombreCte      = utf8_decode($data[0]->NOMBRE_CLIENTE);
    $cDireCte         = utf8_decode($data[0]->DOMICILIO_CLIENTE);
    $cRfc             = $data[0]->RFC_CLIENTE;
    $cTel             = $data[0]->TELEFONO_CLIENTE;
    $cContacto        = utf8_decode($data[0]->CONTACTO_CLIENTE);
    $cEmail           = $data[0]->EMAIL_CLIENTE;     

	// 24/05/2017
 	$nFolio          = date('y').'-'.str_pad($data[0]->ID_RECEPCION_MUESTRA,4,'0',STR_PAD_LEFT);
    $dFechaHora      = strtotime($data[0]->FECHA_RECEPCION);
    $dFechaHora      = date('Y-m-d H:i:s',$dFechaHora);
      
      // empezamos el pdf
    $this->load->library('pdf');
    $this->pdf = new pdf();
    $this->pdf->setNombreReporte('Entrega de Muestras');    
    $this->pdf->AddPage('l','letter'); //l landscape o P normal
    $this->pdf->AliasNbPages();
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->SetMargins(5,20,10);
    //$this->pdf->SetAutoPageBreak( 'true' , 30 ) ; // indica que debe brincar a otra pagina cuando el margen llegue a 30 cm antes del final
    $this->pdf->AliasNbPages();
    
    $nPosEnc = array('col1' => 10,'col2'=>35,'col3'=>60,'col4'=>110,'col5' =>140,'col6' => 160,'col7'=>176,'col8'=>190,'col9' =>210,'col10'=>250);

    
    $r = 25;
    $nInc = 5;
    $this->pdf->setxy($nPosEnc['col4'],$r);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col4'],$nInc,'REGISTRO DEL SERVICIO',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'Folio:',1,0,'C',true);
    $this->pdf->SetFont('Arial','',8);
    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col7'],$nInc,$nFolio,1,0,'L');
    $this->pdf->cell($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'Fecha/Hora:',1,0,'L');
    $this->pdf->cell($nPosEnc['col10']-$nPosEnc['col9'],$nInc,$dFechaHora,1,0,'L');

    $this->pdf->ln(15);
  
   
    $r=$r+$nInc+5;
    $nInc = 10;
    //$nPosEnc = array('col1' => 10,'col2'=>25,'col3'=>50,'col4' =>140,'col5' => 160,'col6'=>176,'col7'=>226,'col8' =>270,'col9'=>270);
    $nPosEnc = array('col1' => 10,'col2'=>25,'col3'=>50,'col4' =>105,'col5' => 120,'col6'=>135,'col7'=>170,'col8' =>265,'col9'=>270);
    $this->pdf->setxy($nPosEnc['col1'],$r);
    $this->pdf->SetFont('Arial','B',6);   
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'ID DE MUESTRA',1,'C',1,true);    
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,'ID ASIGNADA POR EL CLIENTE',1,'C',1,true);    
    
    //15/06/2017
    $nRow = $this->pdf->gety();
    
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
        
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc,'ENSAYO / METODOLOGIA',1,'C',1,true);    
    //$this->pdf->Multicelda($nPosEnc['col9']-$nPosEnc['col8'],$nInc,'IMPORTE',1,'C',1,true);      
    
    //$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,0,'C',true);
    //$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
    //$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
    //$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col5'],$nInc,'ENSAYO / METODOLOGIA',1,'C',1,true); 
    
    $this->pdf->setxy($nPosEnc['col3'],$nRow+($nInc/2));
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'TIPO DE MUESTRA',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,'PESO/VOL.',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,'TEMP.',1,0,'C',true);   

    
    $this->pdf->ln();
    $this->pdf->setx($nPosEnc['col1']);    
    
    // cambiando a normal el tipo de letra
    $this->pdf->SetFont('Arial','',8);

	// IMPRIMIENDO AHORA SI LOS CAMPOS
    $i = 0;
    //$r=$r+10;
    $nInc = 12;
    $this->pdf->SetFont('Arial','',8);
    
    $nTotalImporte = 0; // 11/05/2017
    $cLeyenda = "";

	$this->load->library('utilerias');
	$r = $this->pdf->GetY();
    foreach ($data as $key => $value) { // ciclo de las metodoligas
        // cuadro id muestra
        // me quede agregando la fila..!        
        
        // 2017-08-09
        //$r = $this->pdf->GetY();
        $this->pdf->setxy($nPosEnc['col1'],$r);
      	        
        $this->pdf->multicell( $nPosEnc['col2']-$nPosEnc['col1'],$nInc,$this->utilerias->Personaliza_ID_MUESTRA($data[$i]->ID_MUESTRA),1,'C' );
        $this->pdf->setxy($nPosEnc['col2'],$r);
		
		$nAlturaPagina = $this->pdf->GetPageHeight();
        $this->pdf->multicell( $nPosEnc['col3']-$nPosEnc['col2'],$nInc,$data[$i]->ID_ASIGNADO_CLIENTE,1,'C' );
        
        $cDestinoMuestra = $data[$i]->DESTINO_MUESTRA;		
		$cCondiciones	  = $data[$i]->CONDICIONES_MUESTRA;
    	$cTipoMuestra 	  = $data[$i]->TIPO_MUESTRA;
    	$cPesoVol		  = $data[$i]->PESO_VOL_MUESTRA;
    	$cTemperatura	  = $data[$i]->TEMPERATURA_MUESTRA;
        
        $this->pdf->setxy($nPosEnc['col3'],$r);
        //$this->pdf->cell( $nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cTipoMuestra),1,0,'C' );
        $this->pdf->MultiCelda( $nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cTipoMuestra),1,'C',0 ); 
        
        $this->pdf->setXY($nPosEnc['col4'],$r);
        $this->pdf->MultiCelda( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($data[$i]->PESO_VOL_MUESTRA),1,'C',0 ); 
        
        //this->pdf->setXY($nPosEnc['col4'],$r);
        //$this->pdf->cell( $nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cPesoVol),1,0,'C' );
        $this->pdf->setXY($nPosEnc['col5'],$r);
        $this->pdf->cell( $nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cTemperatura),1,0,'C' );
        
               

        $this->pdf->setxy($nPosEnc['col6'],$r);
        //$this->pdf->cellHtml($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 );        
        $this->pdf->MultiCell( $nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'C',0 ); 
        $this->pdf->setxy($nPosEnc['col7'],$r);        
        $this->pdf->cellHtml($nPosEnc['col8']-$nPosEnc['col7'],$nInc,utf8_decode($data[$i]->METODOLOGIA_ESTUDIO),1,'T' ,0 );
        
        $this->pdf->ln($nInc);        
        $this->pdf->setxy($nPosEnc['col8'],$r);               
        $r = $r + $nInc ; // incrementamos por cada renglon 5
        
        $i++;  
        if ( $r+20 > 180) { 
    		$this->pdf->AddPage('l','letter'); 
    		$r = $this->pdf->GetY();
		}        
    } // fin del foreach AQUI TERMINA EL CICLO


	//2017-08-15 --> impresion de el cuadro del footer final.
    $r = $r+$nInc/2;
    
    
    //2017-08-15 --> Checando que quepa el cuadro final
    //$r=$this->pdf->getY();
    if ( $r+20 > 180) { 
    	$this->pdf->AddPage('l','letter'); 
    	$r = $this->pdf->GetY();
    } 
    
    $nPosEnc2 = array('col1' => 10,'col2'=>105,'col3'=>185,'col4' =>270);
    $this->pdf->setxy($nPosEnc2['col1'],$r);
    $nInc3 = 4;
    
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc2['col2']-$nPosEnc2['col1'],$nInc3,'RECEPCION',1,0,'C',true);
    $this->pdf->cell($nPosEnc2['col3']-$nPosEnc2['col2'],$nInc3,utf8_decode('RECIBIDO POR (Área)'),1,0,'C',true);
    //$this->pdf->cell($nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3,utf8_decode('ENVÍO DE RESULTADOS'),1,0,'C',true);
    
    $this->pdf->SetFont('Arial','',8);
    $r=$r+$nInc3;
    $this->pdf->setxy($nPosEnc2['col1'],$r);
	

    $this->pdf->text($nPosEnc2['col1']+1,$r+3,'Recibido por(nombre y firma):');
    $this->pdf->text($nPosEnc2['col1']+5,$r+7,$_SESSION['user_nombre'] );
    $this->pdf->rect($nPosEnc2['col1'],$r,$nPosEnc2['col2']-$nPosEnc2['col1'],$nInc3+$nInc3);
    
    $this->pdf->text($nPosEnc2['col2']+1,$r+3,'Recibido por(nombre y firma):');
    //$this->pdf->text($nPosEnc2['col2']+5,$r+7, utf8_decode($data[0]->NOMBRE_CLIENTE ));
    $this->pdf->text($nPosEnc2['col2']+5,$r+7, " " );
    $this->pdf->rect($nPosEnc2['col2'],$r,$nPosEnc2['col3']-$nPosEnc2['col2'],$nInc3+$nInc3);
    
    //$this->pdf->text($nPosEnc2['col3']+1,$r+3,utf8_decode('Atención a:'));
    //$this->pdf->rect($nPosEnc2['col3'],$r,$nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3+$nInc3);

    $r=$r+$nInc3+$nInc3;
    $this->pdf->setxy($nPosEnc2['col1'],$r);
    

    
    $this->pdf->text($nPosEnc2['col1']+1,$r+$nInc3-1,'Fecha/Hora: '.$data[0]->FECHA_RECEPCION );    
    $this->pdf->rect($nPosEnc2['col1'],$r,$nPosEnc2['col2']-$nPosEnc2['col1'],$nInc3);
    
    //$this->pdf->text($nPosEnc2['col2']+1,$r+$nInc3-1,'Fecha: '.$data[0]->FECHA_RECEPCION );    
    $this->pdf->text($nPosEnc2['col2']+1,$r+$nInc3-1,'Fecha/Hora: '.date('Y-m-d') );
    $this->pdf->rect($nPosEnc2['col2'],$r,$nPosEnc2['col3']-$nPosEnc2['col2'],$nInc3);
    
    //$this->pdf->text($nPosEnc2['col3']+1,$r+$nInc3-1,utf8_decode('Impreso [ ]     email [ ]    paquetería [ ]'));
    //$this->pdf->rect($nPosEnc2['col3'],$r,$nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3);


    $r=$r+$nInc3;
    $this->pdf->setxy($nPosEnc2['col1'],$r);
    
    //21/06/2017
    $this->pdf->text($nPosEnc2['col1']+1,$r+2,utf8_decode('Condiciones de recepción de la muestra:'));
    $this->pdf->ln(10);
    //$this->pdf->setxy($nPosEnc2['col1'],$r+2);
	if (isset($data[0]->CONDICIONES_MUESTRA)) { 		
		$this->pdf->setxy($nPosEnc2['col1'],$r+2);		
		$this->pdf->multicell(95,$nInc/3,utf8_decode($data[0]->CONDICIONES_MUESTRA),'0','L',false );
		//$this->pdf->cellHtml(95,$nInc-2,utf8_decode($data[0]->CONDICIONES_MUESTRA),'0','L',0 );			
	}
	$this->pdf->rect($nPosEnc2['col1'],$r,95,$nInc3*6);

    $this->pdf->text(105+1,$r+2,'Observaciones:');    
    if (isset($data[0]->OBSERVACIONES_RECEPCION)) { 
    	$this->pdf->setxy(105,$r+2);
    	//$this->pdf->text($nPosEnc2['col1']+5,$r+$nInc3+2,utf8_decode($data[0]->OBSERVACIONES_RECEPCION));  
    	//$this->pdf->multicell(85,$nInc/3,utf8_decode($data[0]->OBSERVACIONES_RECEPCION),'0','L',false );
    	$this->pdf->multicell(80,$nInc/3,utf8_decode($data[0]->OBSERVACIONES_RECEPCION),'0','L',false );
    	//$this->pdf->cellHtml(85,$nInc-2,utf8_decode($data[0]->OBSERVACIONES_RECEPCION),'0','L',0 );    	
    }
    $this->pdf->rect($nPosEnc2['col1'],$r,$nPosEnc2['col3']-$nPosEnc2['col1'],$nInc3*6);


    //REGISTRO DE ENTREGA DE RESULTADOS
    $this->pdf->setxy( $nPosEnc2['col3'],$r)   ;
    $this->pdf->SetFont('Arial','B',8);
    //$this->pdf->cell($nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3,'REGISTRO DE ENTREGA DE RESULTADOS',1,2,'C',true);
    //$this->pdf->SetFont('Arial','',8);
    //$this->pdf->cell($nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3,'No. Informe:',1,2,'L');
    //$this->pdf->cell($nPosEnc2['col4']-$nPosEnc2['col3'],$nInc3,'Recibido por (nombre y firma)',1,2,'L');   
   

    
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$nFolio;
    $this->pdf->Output($cNombreArchivo,'I');
	
	
  */ // FIN DE LA TERMINACION DE LA REVISION 10

  }// fin de la funcion 
  
  /* *********************************************** */    
  public function encabezado_tabla_entrega_muestra($nPosEnc,$nInc){
    $this->pdf->setX($nPosEnc['col1']);
    $this->pdf->SetFont('Arial','B',6);   
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc,'ID DE MUESTRA',1,'C',1,true);    
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,'ID ASIGNADA POR EL CLIENTE',1,'C',1,true);    
    
    //15/06/2017
    $nRow = $this->pdf->gety();
    
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col3'],$nInc/2,utf8_decode('DESCRIPCIÓN DE LA MUESTRA'),1,'C',1,true);
        
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,'LOTE / ORIGEN',1,'C',1,true);  
    $this->pdf->Multicelda($nPosEnc['col10']-$nPosEnc['col7'],$nInc,'ENSAYO',1,'C',1,true);  
    $this->pdf->Multicelda($nPosEnc['col11']-$nPosEnc['col10'],$nInc,'METODOLOGIA',1,'C',1,true);  
    
    $this->pdf->setxy($nPosEnc['col3'],$nRow+($nInc/2));
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,'TIPO DE MUESTRA',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,'PESO/VOL.',1,0,'C',true);
    $this->pdf->cell($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,'TEMP.',1,0,'C',true);   

    
    $this->pdf->ln();
  }
  /* *********************************************** */    
  public function idr_mercurio( $idDetalleMuestra = null) {
  	// lo primero es obtener los datos necesarios ..!
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
  	$this->db->join('idr_mercurio','detalle_muestras.ID_METODOLOGIA = idr_mercurio.ID_METODOLOGIA');
  	$this->db->join('usuarios','idr_mercurio.ID_USUARIO_SIGNATARIO = usuarios.ID_USUARIO');  	
  	$this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);    
    $query = $this->db->get();    
  	$data = $query->result();
  	
  	// fin de la obtencion de los datos necesarios ..!
  	  	 
  	 if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalleMuestra.' ] Inexistente" );</script>';
        exit();        
    }
          	
    //$IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;
    $idMuestra          = $data[0]->ID_MUESTRA;
    //$cFolioSolicitud	= $data[0]->FOLIO_SOLICITUD;
    
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    //$cOrigen
    $dFechaFinalAnalisis    = $data[0]->FECHA_FINAL_MERCURIO; // FECHA FINAL
    $dFechaInicialAnalisis	= $data[0]->FECHA_INICIO_REAL;
    
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_MERCURIO;
    $cResultado			= $data[0]->RESULTADO_MERCURIO; 
    $cLC				= $data[0]->LC_MERCURIO;
    $cLMP				= $data[0]->LMP_MERCURIO;
    $cTecnica			= $data[0]->TECNICA_MERCURIO;
    $cMetodoPrueba		= $data[0]->METODO_PRUEBA_MERCURIO; 
    
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    $cRefResultado       = $data[0]->REFERENCIA_MERCURIO;
    $cObsResultado       = $data[0]->OBSERVACION_MERCURIO;
    $cCondMuestra        = $data[0]->CONDICIONES_MERCURIO;
    
    $cNameTecnico 		 = $data[0]->TECNICO_MERCURIO;
    $cCargoTecnico		 = $data[0]->CARGO_TECNICO_MERCURIO;
    $cIniciales			 = $data[0]->INICIALES_ANALISTA_MERCURIO;
    
    // generando las variables que requiere el informe..!
    
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->pdf = new pdf( $cMetodoValidado ); //   
    
    $nInc = 10;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    $this->idr_encabezado($data,$dFechaInicialAnalisis	,$dFechaFinalAnalisis );  
    
    // DATOS DEL RESULTADO
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',8);
    
    $nPosEnc = array('col1' => 10,'col2'=>37,'col3'=>60,'col4' =>80,'col5' => 107,'col6'=>130,'col7'=>150,'col8' =>206);
    //$nPosEnc = array('col1' => 10,'col2'=>70,'col3'=>140,'col4' =>206);
       
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*2,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col2'],$nInc*2,utf8_decode('RESULTADO (mg/L)'),1,'C',1,false);    
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,utf8_decode('LÍMITE DE CUANTIFICACIÓN (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*2,utf8_decode('LMP (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*2,utf8_decode('TÉCNICA'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*2,utf8_decode('MÉTODO DE PRUEBA'),1,'C',1,false);
    

	$this->pdf->ln();  

    // COMENZANDO A ESCRIBIR LOS DATOS..!
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*2,utf8_decode($cAnalisis),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc*2,utf8_decode('Mercurio'),1,'C',1,false);
    
    $cResultado2 = (float)$cResultado;    
    if ( $cResultado2 < 0.05 ) {
		$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc*2,'<LC',1,'C',1,false);	
		//$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc*2,utf8_decode($cResultado),1,'C',1,false);
	}else {
		//$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,$row->RESULTADO_AFLATOXINAS,1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc*2,utf8_decode($cResultado),1,'C',1,false);
	}// fin del if resultado aflatoxinas > .05
    
	//$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc*2,utf8_decode($cResultado),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,utf8_decode($cLC),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*2,utf8_decode($cLMP),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*2,utf8_decode($cTecnica),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*2,utf8_decode($cMetodoPrueba),1,'C',1,false);
	$this->pdf->ln($nInc*2);
	
    
    
    
    
    /*
    //REFERENCIAS DE APLICACION DE LA METODOLOGIA
    $this->pdf->ln($nInc*1.5);
    $nRowActual = $this->pdf->gety();
    $this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->pdf->SetFont('Arial','B',8);
    
    $this->pdf->rect($nPosEnc['col1'],$nRowActual,$nPosEnc['col8']-$nPosEnc['col1'],$nInc*1.5);
    $this->pdf->write($nInc/2,utf8_decode('REFERENCIAS DE APLICACIÓN DE METODOLOGÍA:'));
    $this->pdf->ln($nInc/2);

    $this->pdf->SetFont('Arial','',8);    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cRefResultado),0,2,'C');

    //$this->pdf->ln($nInc);
    $this->pdf->ln($nInc/2);
    
    // OBSERVACIONES EXISTENTES
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/2,utf8_decode('Notas u Observaciones:'),0,2,'L');
    $this->pdf->SetFont('Arial','',8); //normal
    if (!empty($cObsResultado) ){        
        $this->pdf->write($nInc/2,utf8_decode($cObsResultado));
        $this->pdf->ln($nInc/2);
    }
	
    // checando las condiciones de la recepcion muestra 
    if (!empty($cCondMuestra)) {
        $this->pdf->SetFont('Arial','B',8); 
        $this->pdf->write($nInc/2,utf8_decode('Condiciones de Recepción de la Muestra: '));
        $this->pdf->SetFont('Arial','',8); //normal
        $this->pdf->write($nInc/2,utf8_decode($cCondMuestra));
        $this->pdf->ln($nInc);
    }
       

    // FIRMA DEL TECNICO
    $this->pdf->ln($nInc);
    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,'___________________________________________________',0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cNameTecnico),0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cCargoTecnico),0,2,'C');
    //$this->pdf->ln($nInc/2);

    // DEMAS COSAS
    
    $this->pdf->SetFont('Arial','',6); //normal

	
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode(strtoupper($cIniciales)),0,2,'L');
    if ($data[0]->ACREDITADO_ESTUDIO == 'N') {
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('(*) Metodo acreditado.'),0,2,'L');	
	}else {
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('(*) Metodo Acreditado.'),0,2,'L');
	}    
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe avala unicamente el resultado de la muestra analisada y recibida en el laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe no es válido si presenta raspaduras, tachaduras o enmendaduras.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('Prohibida la reproducción parcial o total de este informe sin la autorización del laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El plazo para realizar aclaraciones en cuanto al contenido del presente informe es de 5 dias hábiles, despues de su emisión.'),0,2,'L');
    $this->pdf->ln($nInc/2);   
     
    
  
    // FIN DE PARTE DE LAS OBSERVACINOES
    */
    $this->idr_pie_de_pagina( $data,'MERCURIO',$nInc);
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin del idr_mercurio
  /* ********************************************************/  
  public function idr_metales( $idDetalleMuestra = null) {
  	// lo primero es obtener los datos necesarios ..!
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
  	$this->db->join('idr_metales','detalle_muestras.ID_METODOLOGIA = idr_metales.ID_METODOLOGIA');
  	  	
    $this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);    
    $query = $this->db->get();    
  	$data = $query->result();
  	
  	// fin de la obtencion de los datos necesarios ..!
  	  	 
  	 if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalleMuestra.' ] Inexistente" );</script>';
        exit();        
    }
          	
    //$IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;
    $idMuestra          = $data[0]->ID_MUESTRA;
    //$cFolioSolicitud	= $data[0]->FOLIO_SOLICITUD;
    
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    //$cOrigen
    $dFechaAnalisis     = $data[0]->FECHA_FINAL_METALES; // FECHA FINAL
    
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_METALES;
    $cMetodoPrueba		= $data[0]->METODO_PRUEBA_METALES; 
    
    $cResultado_cobre		= $data[0]->RESULTADO_COBRE; 
    $cLC_cobre				= $data[0]->LC_COBRE;
    $cLMP_cobre				= $data[0]->LMP_COBRE;
    $cTecnica_cobre			= $data[0]->TECNICA_COBRE;
    
    $cResultado_manganeso	= $data[0]->RESULTADO_MANGANESO; 
    $cLC_manganeso			= $data[0]->LC_MANGANESO;
    $cLMP_manganeso			= $data[0]->LMP_MANGANESO;
    $cTecnica_manganeso		= $data[0]->TECNICA_MANGANESO;
    
    $cResultado_niquel		= $data[0]->RESULTADO_NIQUEL; 
    $cLC_niquel				= $data[0]->LC_NIQUEL;
    $cLMP_niquel			= $data[0]->LMP_NIQUEL;
    $cTecnica_niquel		= $data[0]->TECNICA_NIQUEL;
        
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    $cRefResultado       = $data[0]->REFERENCIA_METALES;
    $cObsResultado       = $data[0]->OBSERVACION_METALES;
    $cCondMuestra        = $data[0]->CONDICIONES_METALES;
    
    $cNameTecnico 		 = $data[0]->TECNICO_METALES;
    $cCargoTecnico		 = $data[0]->CARGO_TECNICO_METALES;
    $cIniciales			 = $data[0]->INICIALES_ANALISTA_METALES;
    
    // generando las variables que requiere el informe..!
    
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->pdf = new pdf( $cMetodoValidado ); //   
    
    $nInc = 10;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    $this->idr_encabezado($data,$dFechaAnalisis);  
    
    // DATOS DEL RESULTADO
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',8);
    
    $nPosEnc = array('col1' => 10,'col2'=>37,'col3'=>60,'col4' =>80,'col5' => 107,'col6'=>130,'col7'=>150,'col8' =>206);
    //$nPosEnc = array('col1' => 10,'col2'=>70,'col3'=>140,'col4' =>206);
       
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*2,utf8_decode('ANALISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col2'],$nInc*2,utf8_decode('RESULTADO (mg/L)'),1,'C',1,false);    
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,utf8_decode('LÍMITE DE CUANTIFICACION (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*2,utf8_decode('LMP (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*2,utf8_decode('TÉCNICA'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*2,utf8_decode('MÉTODO DE PRUEBA'),1,'C',1,false);

	$this->pdf->ln();  

    // COMENZANDO A ESCRIBIR LOS DATOS..!
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*3,utf8_decode($cAnalisis),1,'C',1,false);
    //COBRE
    $this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode('Cobre'),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cResultado_cobre),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cLC_cobre),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cLMP_cobre),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($cTecnica_cobre),1,'C',1,false);
	$nReng = $this->pdf->gety()+$nInc;
	$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*3,utf8_decode($cMetodoPrueba),1,'C',2,false);
	//MANGANESO
	$this->pdf->setxy($nPosEnc['col2'],$nReng);
	$this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode('Manganeso'),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cResultado_manganeso),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cLC_manganeso),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cLMP_manganeso),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($cTecnica_manganeso),1,'C',2,false);
	//NIQUEL
	$this->pdf->setx($nPosEnc['col2']);
	$this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode('Niquel'),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_decode($cResultado_niquel),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_decode($cLC_niquel),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_decode($cLMP_niquel),1,'C',1,false);
	$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($cTecnica_niquel),1,'C',1,false);
	
	
	//$this->pdf->ln();     
    
    
    //REFERENCIAS DE APLICACION DE LA METODOLOGIA
    $this->pdf->ln($nInc);
    $nRowActual = $this->pdf->gety();
    $this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->pdf->SetFont('Arial','B',8);
    
    $this->pdf->rect($nPosEnc['col1'],$nRowActual,$nPosEnc['col8']-$nPosEnc['col1'],$nInc*1.5);
    $this->pdf->write($nInc/2,utf8_decode('REFERENCIAS DE APLICACIÓN DE METODOLOGÍA:'));
    $this->pdf->ln($nInc/2);

    $this->pdf->SetFont('Arial','',8);    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cRefResultado),0,2,'C');

    //$this->pdf->ln($nInc);
    $this->pdf->ln($nInc/2);
    
    // OBSERVACIONES EXISTENTES
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/2,utf8_decode('Notas u Observaciones:'),0,2,'L');
    $this->pdf->SetFont('Arial','',8); //normal
    if (!empty($cObsResultado) ){        
        $this->pdf->write($nInc/2,utf8_decode($cObsResultado));
        $this->pdf->ln($nInc/2);
    }
	
    // checando las condiciones de la recepcion muestra 
    if (!empty($cCondMuestra)) {
        $this->pdf->SetFont('Arial','B',8); 
        $this->pdf->write($nInc/2,utf8_decode('Condiciones de Recepción de la Muestra: '));
        $this->pdf->SetFont('Arial','',8); //normal
        $this->pdf->write($nInc/2,utf8_decode($cCondMuestra));
        $this->pdf->ln($nInc);
    }
       

    // FIRMA DEL TECNICO
    //$this->pdf->ln($nInc);
    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,'___________________________________________________',0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cNameTecnico),0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cCargoTecnico),0,2,'C');
    //$this->pdf->ln($nInc/2);

    // DEMAS COSAS
    
    $this->pdf->SetFont('Arial','',6); //normal

	
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode(strtoupper($cIniciales)),0,2,'L');
    if ($data[0]->ACREDITADO_ESTUDIO == 'N') {
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('(*) Metodo acreditado.'),0,2,'L');	
	}else {
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('(*) Metodo Acreditado.'),0,2,'L');
	}    
    
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe avala unicamente el resultado de la muestra analisada y recibida en el laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe no es válido si presenta raspaduras, tachaduras o enmendaduras.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('Prohibida la reproducción parcial o total de este informe sin la autorización del laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El plazo para realizar aclaraciones en cuanto al contenido del presente informe es de 5 dias hábiles, despues de su emisión.'),0,2,'L');
    $this->pdf->ln($nInc/2);   
     
    
  
    // FIN DE PARTE DE LAS OBSERVACINOES
    
    
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin del idr_metales va a desaparecer..!
/************************************************************/
  public function idr_plaguicidas( $idDetalleMuestra = null) { // 2017-07-11
  	// lo primero es obtener los datos necesarios ..!
  	//$this->db->select('*,u_s.NOMBRE_USUARIO AS NOMBRE_SIGNATARIO,u_a.NOMBRE_USUARIO AS NOMBRE_ANALISTA,u_s.CARGO_USUARIO AS CARGO_SIGNATARIO');
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');  	
  	$this->db->join('idr_enc_plaguicidas','detalle_muestras.ID_METODOLOGIA = idr_enc_plaguicidas.ID_METODOLOGIA');
	$this->db->join('idr_det_plaguicidas','idr_enc_plaguicidas.ID_ENC_PLAGUICIDAS = idr_det_plaguicidas.ID_ENC_PLAGUICIDAS');
	//$this->db->join('usuarios as u_s','idr_enc_plaguicidas.ID_USUARIO_SIGNATARIO = u_s.ID_USUARIO');
	//$this->db->join('usuarios as u_a','idr_enc_plaguicidas.ID_USUARIO_ANALISTA = u_a.ID_USUARIO');
  	$this->db->join('usuarios','idr_enc_plaguicidas.ID_USUARIO_SIGNATARIO = usuarios.ID_USUARIO');  	
    $this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);    
    $query = $this->db->get();    
  	$data = $query->result();	
  	
  	
  	if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalleMuestra.' ] Inexistente" );</script>';
        exit();        
    }
  		  	
  	// fin de la obtencion de los datos necesarios ..!  	 
    
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;   	
    $idMuestra          = $data[0]->ID_MUESTRA;
    
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    //$cOrigen
    
    //$dFechaAnalisis     = $data[0]->FECHA_FINAL_PLAGUICIDAS; // FECHA FINAL
    $dFechaFinalAnalisis    = $data[0]->FECHA_FINAL_PLAGUICIDAS; // FECHA FINAL
    $dFechaInicialAnalisis	= $data[0]->FECHA_INICIO_REAL;
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_PLAGUICIDAS;
    $cMetodoPrueba		= $data[0]->METODO_PRUEBA_PLAGUICIDAS;    
    
      
    
    // generando las variables que requiere el informe..!
    
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->pdf = new pdf( $cMetodoValidado ); //
     
    $this->pdf->SetLineWidth(.009); //2017-12-22
    
    $nInc =12;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();     
    $this->pdf->SetMargins(10,10,10);//márgenes izquierda, arriba y derecha: 
    
	$this->pdf->SetAutoPageBreak(true,28);  //#Establecemos el margen inferior: 

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    //$this->pdf->ln($nInc);    
    //$this->idr_encabezado($data,$dFechaAnalisis);  
    $this->idr_encabezado($data,$dFechaInicialAnalisis	,$dFechaFinalAnalisis );   
    $nInc = 8;
    // DATOS DEL RESULTADO
    $this->pdf->ln($nInc/2);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    
    
    $nPosEnc = array('col1' => 10,'col2'=>45,'col3'=>77,'col4' =>105,'col5' => 135,'col6'=>155,'col7'=>175,'col8' =>206);
    
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*1.5,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col2'],$nInc*1.5,utf8_decode('RESULTADO (mg/kg)'),1,'C',1,false);    
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*1.5,utf8_decode('LÍMITE DE CUANTIFICACIÓN (mg/kg)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*1.5,utf8_decode('LMP (mg/kg)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*1.5,utf8_decode('TÉCNICA'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*1.5,utf8_decode('MÉTODO DE PRUEBA'),1,'C',2,false);

    // COMENZANDO A ESCRIBIR LOS DATOS..!
    $nRengOld = $this->pdf->gety();
    
    
    //GENERAR UN CICLO PARA RECORRER TODOS LOS ELEMENTOS
    $lImprimioMetodologia = false;
    $cLeyendaAsterisco = null;
    
    foreach( $data as $renglon){
    	$this->pdf->SetFont('Arial','',7);
    	$this->pdf->setx($nPosEnc['col2']);
    	$cAnalito = utf8_encode($renglon->ANALITO_PLAGUICIDAS);
    	//$cAnalito .= $this->pdf->GetY();
    	$cResultado = utf8_decode($renglon->RESULTADO_ANALITO_PLAGUICIDAS);
    	$cLC		= utf8_decode($renglon->LC_PLAGUICIDAS);
    	$cLMP		= utf8_decode($renglon->LMP_PLAGUICIDAS);
    	$cTecnica	= utf8_decode($renglon->TECNICA_PLAGUICIDAS);
		$this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc/2,utf8_decode($renglon->ANALITO_PLAGUICIDAS),1,'C',1,false);
		
		$nResultado = (float)$cResultado;
		if ($nResultado<0.005) { $cResultado = '<LC'; } 
		$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc/2,utf8_encode($cResultado),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc/2,utf8_encode($cLC),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc/2,utf8_encode($cLMP),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc/2,utf8_encode($cTecnica),1,'C',1,false);		
		$this->pdf->ln();
		$nRengAct = $this->pdf->gety();
		if( $this->pdf->gety()>$this->pdf->GetPageHeight()-50)	{  // volver a imprimir el cuadro
		
    		$this->pdf->setxy($nPosEnc['col1'],$nRengOld);
			$this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nRengAct-$nRengOld,utf8_decode($cAnalisis),1,'C',1,false);
			$this->pdf->setxy($nPosEnc['col7'],$nRengOld);
			$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nRengAct-$nRengOld,utf8_decode($cMetodoPrueba),1,'C',2,false);		
		
			$this->pdf->AddPage('P','letter'); //l landscape o P normal			
			$this->pdf->ln();
			$this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*1.5,utf8_decode('ANÁLISIS SOLICITADO'),1,'C',1,false);
		    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col2'],$nInc*1.5,utf8_decode('RESULTADO (mg/kg)'),1,'C',1,false);    
    		$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*1.5,utf8_decode('LÍMITE DE CUANTIFICACIÓN (mg/kg)'),1,'C',1,false);
    		$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*1.5,utf8_decode('LMP (mg/kg)'),1,'C',1,false);
    		$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*1.5,utf8_decode('TÉCNICA'),1,'C',1,false);
    		$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*1.5,utf8_decode('MÉTODO DE PRUEBA'),1,'C',2,false);

    		$nRengOld = $this->pdf->gety();
    		$lImprimioMetodologia = true;
		}// fin del if $this-->pdfty->get
		
		//2017-08-28 --> PARA LA ACREDITACION DE LOS ANALITOS..!		
		$cAnalito = $renglon->ANALITO_PLAGUICIDAS;
		$cAcreditado = $this->db->query("select * from analitos where NOMBRE_ANALITO = '".$cAnalito."'")->row();
		//$cAcreditado = $cAcreditado->row(1);
		//$cAcreditado = $cAcreditado->ACREDITADO_ANALITO;
		//if ( $cAcreditado->ACREDITADO_ANALITO == 'N') { $cLeyendaAsterisco = '(³) Analito NO Acreditado'; }
		
	
		
	} 
	$nRengAct = $this->pdf->gety();
	
	$this->pdf->setxy($nPosEnc['col1'],$nRengOld);
	if (!$lImprimioMetodologia ){ $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nRengAct-$nRengOld,utf8_decode($cAnalisis),1,'C',1,false); }
	if ($lImprimioMetodologia ) { $this->pdf->rect($nPosEnc['col1'],$nRengOld,$nPosEnc['col2']-$nPosEnc['col1'],$nRengAct-$nRengOld); }
	$this->pdf->setxy($nPosEnc['col7'],$nRengOld);
	if (!$lImprimioMetodologia) {$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nRengAct-$nRengOld,utf8_decode($cMetodoPrueba),1,'C',2,false);}
	if ($lImprimioMetodologia ) { $this->pdf->rect($nPosEnc['col7'],$nRengOld,$nPosEnc['col8']-$nPosEnc['col7'],$nRengAct-$nRengOld); }	
	
	$this->pdf->setxy($nPosEnc['col1'],$nRengAct);
	$this->idr_pie_de_pagina($data,'PLAGUICIDAS',$nInc,$cLeyendaAsterisco); 
    
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin del idr_metales
/******************************************************************************************** */
  public function idr_pie_de_pagina( $data,$cNameIDR,$nInc = 8,$cLeyendaAsterisco = null){ // pie de pagina para todos los IDR
	//REFERENCIAS DE APLICACION DE LA METODOLOGIA
	//2017-08-16  --> se va a meter una varioable para la leyenda de los Asteriscos (analitos y metales)
	
    //$this->pdf->ln($nInc);
    $cFirmaSignatario   			= $data[0]->RUTA_FOTO_USUARIO;
    $nLongFirmaSignatario 			= $data[0]->LONGITUD_FIRMA_USUARIO;
    $nAltoFirmaSignatario 			= $data[0]->ALTURA_FIRMA_USUARIO;
    $nDesplazamientoFirmaUsuario 	= $data[0]->DESPLAZAMIENTO_FIRMA_USUARIO;
    if ($cNameIDR == 'PLAGUICIDAS') {
		$cNombreSignatario	= $data[0]->NOMBRE_USUARIO;
    	$cCargoSignatario	= $data[0]->CARGO_USUARIO;
    	$cIniciales			 = $data[0]->INICIALES_ANALISTA_PLAGUICIDAS;	
    	
    	$cRefResultado       = $data[0]->REFERENCIA_PLAGUICIDAS;
    	$cObsResultado       = $data[0]->OBSERVACION_PLAGUICIDAS;
    	$cCondMuestra        = $data[0]->CONDICIONES_PLAGUICIDAS;
    	
	}elseif ($cNameIDR == 'AFLATOXINAS') {
		$cNombreSignatario	= $data[0]->NOMBRE_USUARIO;
    	$cCargoSignatario	= $data[0]->CARGO_USUARIO;
    	$cIniciales			 = $data[0]->INICIALES_ANALISTA_AFLATOXINAS;
		
    	$cRefResultado       = $data[0]->REFERENCIA_AFLATOXINAS;
    	$cObsResultado       = $data[0]->OBSERVACION_AFLATOXINAS;
    	$cCondMuestra        = $data[0]->CONDICIONES_AFLATOXINAS;
    	
		
    }elseif ($cNameIDR == 'MICROBIOLOGIA'){
    	$cNombreSignatario	= $data[0]->NOMBRE_USUARIO;
    	$cCargoSignatario	= $data[0]->CARGO_USUARIO;
    	$cIniciales			 = $data[0]->INICIALES_ANALISTA_MICROBIOLOGIA;
		
    	$cRefResultado       = $data[0]->REFERENCIA_MICROBIOLOGIA;
    	$cObsResultado       = $data[0]->OBSERVACION_MICROBIOLOGIA;
    	$cCondMuestra        = $data[0]->CONDICIONES_MICROBIOLOGIA;		
    	
    }elseif ($cNameIDR == 'MERCURIO'){
    	$cNombreSignatario	= $data[0]->NOMBRE_USUARIO;
    	$cCargoSignatario	= $data[0]->CARGO_USUARIO;
    	$cIniciales			 = $data[0]->INICIALES_ANALISTA_MERCURIO;
		
    	$cRefResultado       = $data[0]->REFERENCIA_MERCURIO;
    	$cObsResultado       = $data[0]->OBSERVACION_MERCURIO;
    	$cCondMuestra        = $data[0]->CONDICIONES_MERCURIO;    	
    }elseif ($cNameIDR == 'METALES'){
    	$cNombreSignatario	= $data[0]->NOMBRE_USUARIO;
    	$cCargoSignatario	= $data[0]->CARGO_USUARIO;
    	$cIniciales			 = $data[0]->INICIALES_ANALISTA_METALES;
		
    	$cRefResultado       = $data[0]->REFERENCIA_METALES;
    	$cObsResultado       = $data[0]->OBSERVACION_METALES;
    	$cCondMuestra        = $data[0]->CONDICIONES_METALES;    	
    } // fin del if   // fin del if        
        
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    
    
    $nPosEnc = array('col1' => 10,'col2'=>50,'col3'=>80,'col4' =>115,'col5' => 145,'col6'=>165,'col7'=>185,'col8' =>206);
    $nRowActual = $this->pdf->gety();
    //$this->pdf->setxy( $nPosEnc['col1'], $nRowActual);
    
    $this->pdf->SetFont('Arial','B',7);
    
    $this->pdf->rect($nPosEnc['col1'],$nRowActual,$nPosEnc['col8']-$nPosEnc['col1'],$nInc*1.5);
    $this->pdf->write($nInc/2,utf8_decode('REFERENCIAS DE APLICACIÓN DE METODOLOGÍA:'));
    $this->pdf->ln($nInc/2);

    $this->pdf->SetFont('Arial','',7);    
    //$this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cRefResultado),0,2,'C');
    $this->pdf->MultiCell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cRefResultado),0,'L');

    //$this->pdf->ln($nInc);
    $this->pdf->ln($nInc/2);
    
    // OBSERVACIONES EXISTENTES
    $this->pdf->SetFont('Arial','B',7);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/2,utf8_decode('Notas u Observaciones:'),0,2,'L');
    $this->pdf->SetFont('Arial','',7); //normal
    if (!empty($cObsResultado) ){        
        $this->pdf->write($nInc/2,utf8_decode($cObsResultado));
        $this->pdf->ln($nInc/2);
    }
	
	
	$this->pdf->ln($nInc/2);
	    
	$this->pdf->SetFont('Arial','B',7);
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/2,utf8_decode('Condiciones de Recepción de la Muestra:'),0,2,'L');
    $this->pdf->SetFont('Arial','',7); //normal
	
    // checando las condiciones de la recepcion muestra 
    if (!empty($cCondMuestra)) {        
        $this->pdf->SetFont('Arial','',7); //normal
        $this->pdf->write($nInc/2,utf8_decode($cCondMuestra));
        //$this->pdf->ln($nInc/2);
    }
       
	//$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col1'],$nInc*5,utf8_decode($cMetodoPrueba),1,'C',2,false);	
    // FIRMA DEL TECNICO
    $nRowFoto = $this->pdf->getY();
    $this->pdf->ln($nInc);
    
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,'___________________________________________________',0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cNombreSignatario),0,2,'C');
    $this->pdf->cell($nPosEnc['col8']-$nPosEnc['col1'],$nInc/2,utf8_decode($cCargoSignatario),0,2,'C');
    /*
    $nLongFirmaSignatario 			= $data[0]->LONGITUD_FIRMA_USUARIO;
    $nAltoFirmaSignatario 			= $data[0]->ALTURA_FIRMA_USUARIO;
    $nDesplazamientoFirmaUsuario 	= $data[0]->DESPLAZAMIENTO_FIRMA_USUARIO;
    */
    if ($cFirmaSignatario ) {
    	//Image(string file [,  x [,  y [,  w [,  h [, string type [, mixed link]]]]]])
        $this->pdf->Image($cFirmaSignatario,90,$nRowFoto+$nDesplazamientoFirmaUsuario,$nLongFirmaSignatario,$nAltoFirmaSignatario);    
    }
    

    if (file_exists('assets/img/sello/sello_laria.png')) {
        $this->pdf->image( 'assets/img/sello/sello_laria.png',160, $nRowFoto,40);    
    }

    // DEMAS COSAS
    
    $this->pdf->SetFont('Arial','',6); //normal

	
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode(strtoupper($cIniciales)),0,2,'L');
    
    //$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode($cLeyendaAsterisco).'Efrain',0,2,'L');
    //2017-08-18 --> ANEXANDO EL APARTADO DE LAS LEYENDAS DINAMICAS
	if ($cLeyendaAsterisco){
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode($cLeyendaAsterisco),0,2,'L');	
	}
	if ($data[0]->ACREDITADO_ESTUDIO == 'S'){
		//$cLeyendaAcreditado = '(*) Acreditación No. A-0733-074/16. Vigente a partir del 2016-05-19.';
		//2017-11-27
		$cLeyendaAcreditado = '(*) Método acreditado.';		
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode($cLeyendaAcreditado),0,2,'L');	
	}
	if ($data[0]->RECONOCIDO_ESTUDIO == 'S'){
		$cLeyendaReconocido = '(¹) Método reconocido por el SENASICA, de acuerdo a lo indicado en el Oficio Nº. B00.04.02.05 3817/2017.';
		if ($data[0]->AREA_ESTUDIO == 'M'){
			$cLeyendaReconocido = '(²) Método reconocido por el SENASICA, de acuerdo a lo indicado en el Oficio Nº. B00.04.02.05 3818/2017.';
		}
		$this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode($cLeyendaReconocido),0,2,'L');	
	}

    
    // LEYENDAS FIJAS
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe avala únicamente el resultado de la muestra analizada y recibida en el laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El presente informe no es válido si presenta raspaduras, tachaduras o enmendaduras.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('Prohibida la reproducción parcial o total de este informe sin la autorización del laboratorio.'),0,2,'L');
    $this->pdf->cell($nPosEnc['col4']-$nPosEnc['col1'],$nInc/3,utf8_decode('El plazo para realizar aclaraciones en cuanto al contenido del presente informe es de 5 días hábiles, después de su emisión.'),0,2,'L');
    $this->pdf->ln($nInc/2);   
     
    
  
    // FIN DE PARTE DE LAS OBSERVACINOES
  } // fin del idr_pie_de_pagina
/****************************************************/

	public function impresion_prueba() {
		$txt_short = 'This text is short enough.';
		$txt_long = 'This text is way too long.';
		for ($i = 1; $i <= 2; $i++)
		    $txt_long.=' '.$txt_long;

	//	$pdf = new FPDF_CellFit();
		
		$cMetodoValidado	= 'S';
    	// EMPEZAMOS EL PDF
    	$this->load->library('pdf');
    	$this->pdf = new pdf( $cMetodoValidado ); //   
    	
    
		$this->pdf->AddPage();
		$this->pdf->SetFillColor(0xff,0xff,0x99);

		$this->pdf->SetFont('Arial','B',16);
		$this->pdf->Write(10,'Cell');
		$this->pdf->SetFont('');
		$this->pdf->Ln();
		$this->pdf->Cell(0,10,$txt_short,1,1);
		$this->pdf->Cell(0,10,$txt_long,1,1);
		$this->pdf->Ln();

		
		//$this->pdf->Line($this->pdf->x,$this->pdf->y,$this->pdf->w-$this->pdf->x-$this->pdf->rMargin,$this->pdf->y);
		$this->pdf->Ln();

		$this->pdf->SetFont('','B');
		$this->pdf->Write(10,'CellFitScale');
		$this->pdf->SetFont('');
		$this->pdf->Write(10,' (horizontal scaling only if necessary)');
		$this->pdf->Ln();
		$this->pdf->CellFitScale(0,10,$txt_short,1,1);
		$this->pdf->CellFitScale(0,10,$txt_long,1,1,'',1);
		$this->pdf->Ln();

		$this->pdf->SetFont('','B');
		$this->pdf->Write(10,'CellFitScaleForce');
		$this->pdf->SetFont('');
		$this->pdf->Write(10,' (horizontal scaling always)');
		$this->pdf->Ln();
		$this->pdf->CellFitScaleForce(0,10,$txt_short,1,1,'',1);
		$this->pdf->CellFitScaleForce(0,10,$txt_long,1,1,'',1);
		$this->pdf->Ln();

		//$this->pdf->Line($this->pdf->x,$this->pdf->y,$this->pdf->w-$this->pdf->x-$this->pdf->rMargin,$this->pdf->y);
		$this->pdf->Ln();

		$this->pdf->SetFont('','B');
		$this->pdf->Write(10,'CellFitSpace');
		$this->pdf->SetFont('');
		$this->pdf->Write(10,' (character spacing only if necessary)');
		$this->pdf->Ln();
		$this->pdf->CellFitSpace(0,10,$txt_short,1,1);
		$this->pdf->CellFitSpace(0,10,$txt_long,1,1,'',1);
		$this->pdf->Ln();

		$this->pdf->SetFont('','B');
		$this->pdf->Write(10,'CellFitSpaceForce');
		$this->pdf->SetFont('');
		$this->pdf->Write(10,' (character spacing always)');
		$this->pdf->Ln();
		$this->pdf->CellFitSpaceForce(0,10,$txt_short,1,1,'',1);
		$this->pdf->CellFitSpaceForce(0,10,$txt_long,1,1,'',1);
		
		
		
		$this->pdf->Output();
	}
 /* ********************************************************/  
  public function idr_metales2( $idDetalleMuestra = null) {
  	// lo primero es obtener los datos necesarios ..!
  	$this->db->select('*');
  	$this->db->from('detalle_muestras');
  	$this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
  	$this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
  	$this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
  	//$this->db->join('idr_metales','detalle_muestras.ID_METODOLOGIA = idr_metales.ID_METODOLOGIA');  	
  	$this->db->join('idr_enc_metales','detalle_muestras.ID_METODOLOGIA = idr_enc_metales.ID_METODOLOGIA');
	$this->db->join('idr_det_metales','idr_enc_metales.ID_ENC_METALES = idr_det_metales.ID_ENC_METALES');
	$this->db->join('usuarios','idr_enc_metales.ID_USUARIO_SIGNATARIO = usuarios.ID_USUARIO');  	
  	  	
    $this->db->where('detalle_muestras.ID_DETALLE_MUESTRA',$idDetalleMuestra);    
    $query = $this->db->get();    
  	$data = $query->result();
  	
  	// fin de la obtencion de los datos necesarios ..!
  	  	 
  	 if (count($data)== 0) {
        echo '<script>alert("Error Folio ['.$idDetalleMuestra.' ] Inexistente" );</script>';
        exit();        
    }
          	
    //$IDR                = $data[0]->ID_RECEPCION_MUESTRA;
    $dFechaEmision      = date('Y-m-d h:m:s');
    $IDR				= $data[0]->ID_IDR;
    $idMuestra          = $data[0]->ID_MUESTRA;
    //$cFolioSolicitud	= $data[0]->FOLIO_SOLICITUD;
    
    
    $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    
     $cTipoAnalisis      = $data[0]->AREA_ESTUDIO;
    
    $dFechaFinalAnalisis    = $data[0]->FECHA_FINAL_METALES; // FECHA FINAL
    $dFechaInicialAnalisis	= $data[0]->FECHA_INICIO_REAL;
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_METALES;
    
    //$cOrigen
    $dFechaAnalisis     = $data[0]->FECHA_FINAL_METALES; // FECHA FINAL
    
    $cAnalisis			= $data[0]->ANALISIS_SOLICITADO_METALES;
    $cMetodoPrueba		= $data[0]->METODO_PRUEBA_METALES; 
       
    // REFERENCIAS DE APKLICACION Y OBSERVCIONES
    $cRefResultado       = $data[0]->REFERENCIA_METALES;
    $cObsResultado       = $data[0]->OBSERVACION_METALES;
    $cCondMuestra        = $data[0]->CONDICIONES_METALES;
    
    $cNameTecnico 		 = $data[0]->TECNICO_METALES;
    $cCargoTecnico		 = $data[0]->CARGO_TECNICO_METALES;
    $cIniciales			 = $data[0]->INICIALES_ANALISTA_METALES;
    
    // generando las variables que requiere el informe..!
    
	//PARA VER LO DE EL LOGO DE LA EMA PARA METODOS NO VALIDADOS
	$cMetodoValidado	= $data[0]->ACREDITADO_ESTUDIO;
    // EMPEZAMOS EL PDF
    $this->load->library('pdf');
    $this->pdf = new pdf( $cMetodoValidado ); //   
    
    $nInc = 10;
    
    // DATOS DEL ENCABEZADO
    if ($cTipoAnalisis == 'Q'){
        $this->pdf->setNombreReporte('Informe de Resultados Quimicos');    
    }else {
        $this->pdf->setNombreReporte('Informe de Resultados Microbiologicos');    
    }
    $this->pdf->AddPage('P','letter'); //l landscape o P normal
    $this->pdf->SetFillColor(237, 237, 237);
    $this->pdf->AliasNbPages();
    
    $this->pdf->SetAutoPageBreak( 'true' , 30 ) ; // indica que debe brincar a otra pagina cuando el margen llegue a 30 cm antes del final

    // COMENZAMOS EL REPORTE    
    
    //GENERAMOS EL ENCABEZADO GENERAL!
    //$this->idr_encabezado($data,$dFechaAnalisis);  
    $this->idr_encabezado($data,$dFechaInicialAnalisis	,$dFechaFinalAnalisis );   
    
    // DATOS DEL RESULTADO
    $this->pdf->ln();
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->cell(0,$nInc,'RESULTADOS',0,1,'C');
    //$this->pdf->ln($nInc/2);    
    $this->pdf->SetFont('Arial','B',7);
    
    $nPosEnc = array('col1' => 10,'col2'=>37,'col3'=>60,'col4' =>80,'col5' => 107,'col6'=>130,'col7'=>150,'col8' =>206);
    //$nPosEnc = array('col1' => 10,'col2'=>70,'col3'=>140,'col4' =>206);
       
    
    $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nInc*2,utf8_decode('ANALISIS SOLICITADO'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col2'],$nInc*2,utf8_decode('RESULTADO (mg/L)'),1,'C',1,false);    
    $this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc*2,utf8_decode('LÍMITE DE CUANTIFICACION (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc*2,utf8_decode('LMP (mg/L)'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc*2,utf8_decode('TÉCNICA'),1,'C',1,false);
    $this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nInc*2,utf8_decode('MÉTODO DE PRUEBA'),1,'C',1,false);

	$this->pdf->ln();  
	
	
	// COMENZANDO A ESCRIBIR LOS DATOS..!
	$nRengOld = $this->pdf->gety();
	//GENERAR UN CICLO PARA RECORRER TODOS LOS ELEMENTOS
    $lImprimioMetodologia = false;
    $cLeyendaAsterisco = null;
    
    foreach( $data as $renglon){
    	$this->pdf->SetFont('Arial','',7);
    	$this->pdf->setx($nPosEnc['col2']);
    	$cMetal = utf8_encode($renglon->METAL_METALES);
    	//$cAnalito .= $this->pdf->GetY();
    	$cResultado = utf8_decode($renglon->RESULTADO_METAL_METALES);
    	$cLC		= utf8_decode($renglon->LC_METALES);
    	$cLMP		= utf8_decode($renglon->LMP_METALES);
    	$cTecnica	= utf8_decode($renglon->TECNICA_METALES);
		$this->pdf->Multicelda($nPosEnc['col3']-$nPosEnc['col2'],$nInc,utf8_decode($renglon->METAL_METALES),1,'C',1,false);
		
		$nResultado = (float)$cResultado;
		if ($nResultado<(float)$cLC) { $cResultado = '<LC'; } 
		$this->pdf->Multicelda($nPosEnc['col4']-$nPosEnc['col3'],$nInc,utf8_encode($cResultado),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col5']-$nPosEnc['col4'],$nInc,utf8_encode($cLC),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col6']-$nPosEnc['col5'],$nInc,utf8_encode($cLMP),1,'C',1,false);
		$this->pdf->Multicelda($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_encode($cTecnica),1,'C',1,false);		
		$this->pdf->ln($nInc);
		$nRengAct = $this->pdf->gety();	
		
		$cAcreditado = $renglon->ACREDITADO_METALES;
		
		if ( $cAcreditado == 'N') { $cLeyendaAsterisco = '(*) Metal NO Acreditado'; }
	}	
	
	$nRengAct = $this->pdf->gety();
	
	$this->pdf->setxy($nPosEnc['col1'],$nRengOld);
	if (!$lImprimioMetodologia ){ $this->pdf->Multicelda($nPosEnc['col2']-$nPosEnc['col1'],$nRengAct-$nRengOld,utf8_decode($cAnalisis),1,'C',1,false); }
	if ($lImprimioMetodologia ) { $this->pdf->rect($nPosEnc['col1'],$nRengOld,$nPosEnc['col2']-$nPosEnc['col1'],$nRengAct-$nRengOld); }
	$this->pdf->setxy($nPosEnc['col7'],$nRengOld);
	if (!$lImprimioMetodologia) {$this->pdf->Multicelda($nPosEnc['col8']-$nPosEnc['col7'],$nRengAct-$nRengOld,utf8_decode($cMetodoPrueba),1,'C',2,false);}
	if ($lImprimioMetodologia ) { $this->pdf->rect($nPosEnc['col7'],$nRengOld,$nPosEnc['col8']-$nPosEnc['col7'],$nRengAct-$nRengOld); }	
	
	$this->pdf->setxy($nPosEnc['col1'],$nRengAct);
	$this->idr_pie_de_pagina($data,'METALES',$nInc,$cLeyendaAsterisco); 
	
    
    
    /* DATOS DEL FOOTER */
    $this->pdf->SetDisplayMode('fullpage','single');
    $cNombreArchivo = date('y')."-".$idMuestra;
    $this->pdf->Output($cNombreArchivo,'I');
  	
  } // fin del idr_metales
/************************************************************/
 
}