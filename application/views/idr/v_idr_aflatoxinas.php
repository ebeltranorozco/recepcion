<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; }else { echo 'EDICION ';} ?>INFORME DE RESULTADOS [QUIMICOS]</h3></center>
<?php 
$cIdMuestra = array('id'=>'idMuestra','class'=>'form-control' ,'value' => set_value('idMuestra'),'readonly'=>true); 
$cIdMetodologia = array('id'=>'idMetodologia','class'=>'form-control' ,'value' => set_value('idMetodologia'),'readonly'=>true); 
$cIDR = array('id'=>'idIDR', 'class'=>'form-control', 'value'=>set_value('idIDR'),'readonly'=>true);

$cResultado_aflatoxinas = array('id'=>'idResultado_Aflatoxinas','class'=>'form-control' ,'value' => set_value('idResultado_Aflatoxinas'));
$cG1 = array( 'id'=>'idG1','class'=>'form-control' ,'value' => set_value('idG1')); 
$cG2 = array( 'id'=>'idG2','class'=>'form-control' ,'value' => set_value('idG2')); 
$cB1 = array( 'id'=>'idB1','class'=>'form-control' ,'value' => set_value('idB1')); 
$cB2 = array( 'id'=>'idB2','class'=>'form-control' ,'value' => set_value('idB2')); 

$cLC = array( 'id'=>'idLC','class'=>'form-control' ,'value' => set_value('idLC')); 
$cCH = array ('id'=>'idCH','class'=>'form-control' ,'value' => set_value('idCH'));
$cCA = array ('id'=>'idCA','class'=>'form-control' ,'value' => set_value('idCA'));
$cMetodoPrueba = array('id'=>'idMetodoPrueba','class'=>'form-control' ,'value' => set_value('idMetodoPrueba'),'rows'=>4, 'readonly'=>true);
$cAnalisisSolicitado = array('id'=>'idAnalisisSolicitado','class'=>'form-control' ,'value' => set_value('idAnalisisSolicitado'), 'rows'=>4, 'readonly'=>true);
$cReferencia = array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'),'rows'=>2);
$cObsResultado = array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);
$cCondMuestra	= array('id'=>'idCondMuestra','class'=>'form-control' ,'value' => set_value('idCondMuestra'),'rows'=>2);

$cValidacion = array( 'id' => 'idParaValidacion','name'=>'idParaValidacion','class'=>'form-control','checked' => false,'disabled'=>'disabled');
//'style' => 'margin:10px'

//29/06/2017
$cInicialesAnalista = array( 'id' => 'idInicialesAnalista','name'=>'idInicialesAnalista','class'=>'form-control','value' => set_value('idInicialesAnalista'));
$dFechaFinal = array( 'id' => 'idFechaFinal','name'=>'idFechaFinal','class'=>'form-control','value' => set_value('idFechaFinal'));

//2017-07-25 --> anexado para cuando se trata de una edicion y se tienen q especificar las causas de dicha correcion
$cCausasCorreccion = array('id' =>'idCausasCorreccion','class'=>'form-control','value'=>set_value('idCausasCorreccion'),'rows'=>5);
$cIdTabla = array('id'=>'idTabla','class'=>'form_control','value'=>set_value('idTabla'),'style'=>'visibility:hidden');

//2017-08-17 --> para indicafr cuando un ensayo es para prueba preliminar
$lGeneraFolioIDR = array( 'name'=>'idGeneraFolioIDR','id'=>'idGeneraFolioIDR','class'=>'form-control' ,'checked'=>false,'value' => set_value('idGeneraFolioIDR'),'style'=>'margin:2px 0 0;border-color:none;box-shadow:none;','disabled'=>'disabled');


//2017-08-17--> Anexadno un nuevo campo para las inicial(es) de analista(s)
//$cInicialesAnalista = array( 'name'=>'iniciales_analista','id'=>'iniciales_analista','class'=>'form-control','value'=>set_value('iniciales_analista'));
/*$cIdMuestra = $idMuestra; // viene del controlador estudios
$cIdMetodologia = $idMetodologia; // tambien */
if ($accion =="ALTA") {
	
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;
	$cG1['value'] = '0.00';
	$cG2['value'] = '0.00';
	$cB1['value'] = '0.00';
	$cB2['value'] = '0.00';
	$cLC['value'] = '0.005';
	$cCH['value'] = '0.00';
	$cCA['value'] = '0.00';	
	
	$cTmp = utf8_decode($datos_metodologia->ANALISIS_SOLICITADO);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	$cTmp = utf8_decode($datos_metodologia->METODOLOGIA_ESTUDIO);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	$cTmp = utf8_decode($datos_metodologia->REFERENCIA_ESTUDIO);
	$cReferencia['value'] = utf8_encode($cTmp);
	
	//$cAnalisisSolicitado['value'] = utf8_encode('Determinación de Aflatoxinas Totales');
	//$cMetodoPrueba['value'] = utf8_encode('Determinación de aflatoxinas totales en Maíz por LC-MS/MS-LARIA-AQ-PM003');
	//$cReferencia['value'] = utf8_encode('Método Interno');
	$cObsResultado['value'] = utf8_encode('ND=No detectado
LMP=Límites Máximos Permisibles en base a la NOM-188-SSA1-2002, Productos y Servicios. Control de aflatoxinas en cereales para consumo humano y animal. Especificaciones sanitarias.
C.H.= Consumo Humano C.A.=Consumo Animal L.C.=Límite de cuantificación.
El límite no debe ser mayor al resultado de la suma total de Aflatoxinas detectadas.');
	$cTmp = utf8_decode($datos_metodologia->CONDICIONES_MUESTRA);
	$cCondMuestra['value'] = utf8_encode($cTmp);	
	//$cCondMuestra['value'] = utf8_encode('Muestra a temperatura ambiente.');	
	$cIDR['value'] = $folios->IDR_AQ+1;
	
	$cInicialesAnalista['value'] = $_SESSION['iniciales_usuario'];
	$dFechaFinal['value']  = date( 'Y-m-d H:i:s ');
	//2017-07-25
	$cValueOpcSelected = 1;
	
}
//2017-07-25
if ($accion == 'EDICION'){
	$cIDR['value'] = $resultados[0]['ID_IDR'];
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;
	
	$cG1['value'] = $resultados[0]['G1_AFLATOXINAS'];	
	$cG2['value'] = $resultados[0]['G2_AFLATOXINAS'];
	$cB1['value'] = $resultados[0]['B1_AFLATOXINAS'];
	$cB2['value'] = $resultados[0]['B2_AFLATOXINAS'];
	$cResultado_aflatoxinas['value'] = $resultados[0]['RESULTADO_AFLATOXINAS'];
	$cLC['value'] = $resultados[0]['LC_AFLATOXINAS'];
	$cCH['value'] = $resultados[0]['CH_AFLATOXINAS'];
	$cCA['value'] = $resultados[0]['CA_AFLATOXINAS'];
	
	$cTmp = utf8_decode($resultados[0]['ANALISIS_SOLICITADO_AFLATOXINAS']);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	$cTmp = utf8_decode($resultados[0]['METODO_PRUEBA_AFLATOXINAS']);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	$cTmp = utf8_decode($resultados[0]['REFERENCIA_AFLATOXINAS']);
	$cReferencia['value'] = utf8_encode($cTmp);	
	
	$cObsResultado['value'] = ($resultados[0]['OBSERVACION_AFLATOXINAS']);
	$cTmp = utf8_decode($resultados[0]['CONDICIONES_AFLATOXINAS']);
	$cCondMuestra['value'] = utf8_encode($cTmp);	
	
	$cInicialesAnalista['value'] = $resultados[0]['INICIALES_ANALISTA_AFLATOXINAS'];
	$dFechaFinal['value']  = $resultados[0]['FECHA_FINAL_AFLATOXINAS'];
		
	$cValueOpcSelected = $resultados[0]['ID_USUARIO_SIGNATARIO'];	
	
	$cCausasCorreccion['value'] = '';
	
	$cIdTabla['value'] = $resultados[0]['ID_AFLATOXINAS'];
	
	//$cInicialesAnalista['value'] = $resultados[0]['INICIALES_ANALISTA_AFLATOXINAS'];	
	
	//2017-08-17 --> para saber si se debe de aplicar a pruebas preliminares o no..!
	if (!$datos_metodologia->GENERAR_IDR_MUESTRA) {
		//$lGeneraFolioIDR['checked'] = TRUE;
	}	
	//echo $resultados[0]->GENERAR_IDR_MUESTRA;
}
//2017-08-17 --> para saber si se debe de aplicar a pruebas preliminares o no..!
if (!$datos_metodologia->GENERAR_IDR_MUESTRA) {
	$lGeneraFolioIDR['checked'] = TRUE;
}	

//var_dump( $resultados );
//echo '<br/>Cadena SQL:';
//var_dump( $sql);
//echo '<br/> Folio IDR AQ array:';
//var_dump($folios);
//var_dump($folios);
//echo '<br/> Folio IDR AQ array::';
//echo $folios->IDR_AQ;
//echo '<br/>';
//echo $folio_sql_idr_aq;
//echo '<br/>';
//print_r($folios['IDR_AQ']);
//echo $folios[0]->IDR_AQ;
//echo '<br/>';
//var_dump($datos_metodologia);
//print_r($folios);
?>

<!--<center><h4>DATOS DE LA MUESTRA</h4></center>-->
<hr size=10>


<!-- AQUI IRIA EL APARTADO DE LOS MENSAJES DE ERROR -->
<div id="idDivMsgErrorAflatoxinas"></div>

<center><h4>RESULTADOS</h4></center>
<?php
//echo $this->table->generate($resultados); 
?>

<div class="container">
<div class="row" align="center">
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIDR); ?>
		<?php echo form_input($cIdTabla); ?>
	</div>
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIdMuestra);?>
	</div>
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIdMetodologia);?>	
	</div>
</div>
<div class="row" align="center">
	<div class="form-group col-xs-2 col-sm-2">
		<label for="">An&aacute;lisis Solicitado</label>
		<?php echo form_textarea($cAnalisisSolicitado); ?>
	</div>
	
	<div class="form-group col-xs-3 col-sm-3">
		<label>M&eacute;todo de Prueba</label>
		<?php echo form_textarea($cMetodoPrueba); ?>		
	</div>
	
	<div class="form-group col-xs-6 col-sm-2">
			<label>Signatario</label>			
			<?php echo form_dropdown('idSignatarioCombo',$SignatariosCombo,$selected = $cValueOpcSelected,'class="form-control" id="idSignatarioCombo"'); ?>
			<label>Iniciales Analista(s)</label>			
			<?php echo form_input( $cInicialesAnalista) ?>;
			
		</div>
	<!--
	<div class="form-group col-xs-2 col-sm-2">
		<label>Iniciales del Analista</label>
		<?php echo form_input($cInicialesAnalista); ?>		
	</div>	
	-->
	
	<div class="form-group col-xs-2 col-sm-2">
		<label>Fecha de Terminaci&oacute;n</label>
		<?php echo form_input($dFechaFinal); ?>		
	</div>	
	
</div>
<div class="row" align="center">	
	<div class="form-group col-xs-1 col-sm-1">
		<label for="">G1</label>
		<?php echo form_input($cG1); ?>
	</div>
	<div class="form-group col-xs-1 col-sm-1">
		<label for="">G2</label>
		<?php echo form_input($cG2); ?>
	</div>
	<div class="form-group col-xs-1 col-sm-1">
		<label for="">B1</label>
		<?php echo form_input($cB1); ?>
	</div>
	<div class="form-group col-xs-1 col-sm-1">
		<label for="">B2</label>
		<?php echo form_input($cB2); ?>
	</div>
	
	<div class="form-group col-xs-1 col-sm-1" >
		<label for=''>(mg/kg):</label>
		<?php echo form_input($cResultado_aflatoxinas); ?>
	</div>
	<div class="form-group col-xs-2 col-sm-2" >
		<label for=''>LC (mg/kg)</label>
		<?php echo form_input($cLC); ?>
	</div>
	
	<div class="form-group col-xs-1 col-sm-1" >
		<label for=''>C.H.</label>
		<?php echo form_input($cCH); ?>
	</div>
	<div class="form-group col-xs-1 col-sm-1" >
		<label for=''>C.A.</label>
		<?php echo form_input($cCA); ?>
	</div>	
	
	
	
</div> <!-- fin del row -->
<hr size=10>
<div class="row">
	<div class="form-group col-xs-9 col-sm-9">
		<label>Referencias de Aplicaci&oacute;n</label>
		<?php echo form_textarea($cReferencia); ?>		
	</div>	
</div>
<div class="row">
	<div class="form-group col-xs-9 col-sm-9">
		<label>Notas u Observaciones</label>
		<?php echo form_textarea($cObsResultado); ?>		
	</div>	
</div> <!-- fin del row -->
<div class="row">	
	<div class="form-group col-xs-9 col-sm-9">	
		<label>Condiciones de recepci&oacute;n de la muestra</label>
		<?php echo form_textarea($cCondMuestra); ?>		
	</div>	
</div> <!-- fin del row -->
<hr size=10>

<div class="row">
	<div class="checkbox">
	  <!--<label><input  type="checkbox" name="idParaValidacion" id="idParaValidacion" disabled="disabled"> metodo para validaci&oacute;n (no genera IDR)</label>-->
	  <div class="form-group col-xs-1 col-sm-1"> 
	  	<?php echo form_checkbox($lGeneraFolioIDR);?>
	  	
	  </div>
	  <div class="form-group col-xs-4 col-sm-4">
	  	<p>Aplica para Pruebas Preliminares</p>
	  	
	  </div>
	  
	</div>	
</div>


<!--
<div class="row" align="center">
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIDR); ?>
		<?php echo form_input($cIdTabla); ?>
	</div>
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIdMuestra);?>
	</div>
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIdMetodologia);?>	
	</div>
</div>
-->


<hr size=20>
	<?php 
	if ($accion == 'EDICION'){ ?>
		<div class="row">
			<div class="form-group col-xs-9 col-sm-9 row"> 
				<label>Causas de la Correcci&oacute;n:</label>
				<?php echo form_textarea($cCausasCorreccion);  ?>		
			</div>	
		</div>
	<?php } ?>


	<!-- BOTONES DE AGREGAR Y DE IMPRIMIR-->
	<div class="row">
		<div class="form-group col-xs-6 col-sm-2">
			<?php if($accion=='ALTA') { ?>	
				<div id='BtnGrabaIDRAflatoxina'>
					<button type="button" class="btn btn-primary" id='idBtnGrabaIDRAflatoxina'>Grabar IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
		
		<!-- 2017-07-25 -->
		<div class="form-group col-xs-6 col-sm-2">
			<?php if($accion=='EDICION') { ?>	
				<div id='BtnActualizaIDRAflatoxina'>
					<button type="button" class="btn btn-primary" id='idBtnActualizarIDRAflatoxina'>Corregir IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
		
		<div class="form-group col-xs-6 col-sm-3">
	        <div id="divBtnImprimeIDRAflatoxina" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
	            <button type="button" class="btn btn-warning EnProcesoDesarrollo" formtarget="_blank" id="idBtnImprimeIDRAflatoxina"  >Imprimir IDR</button>
	        </div>	
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
		<!-- boton para regrsarnos -->
	        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
		</div>		
		 
		 
	</div> <!-- fin del row -->

</div><!-- fin del div contanier -->
