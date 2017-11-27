<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; } ?>INFORME DE RESULTADOS [QUIMICOS]</h3></center>
<?php 
$cIdMuestra = array('id'=>'idMuestra','class'=>'form-control' ,'value' => set_value('idMuestra'),'readonly'=>true); 
$cIdMetodologia = array('id'=>'idMetodologia','class'=>'form-control' ,'value' => set_value('idMetodologia'),'readonly'=>true); 
$cIDR = array('id'=>'idIDR', 'class'=>'form-control', 'value'=>set_value('idIDR'),'readonly'=>true);

$cResultado_mercurio = array('id'=>'idResultado_mercurio','class'=>'form-control' ,'value' => set_value('idResultado_mercurio'));
$cLC = array('id'=>'limite_cuantificacion_mercurio','class'=>'form-control' ,'value' => set_value('limite_cuantificacion_mercurio'));
$cLMP = array('id'=>'limite_maximo_mercurio','class'=>'form-control' ,'value' => set_value('limite_maximo_mercurio'));
$cTecnica = array('id'=>'tecnica_mercurio','class'=>'form-control' ,'value' => set_value('tecnica_mercurio'));

$cMetodoPrueba = array('id'=>'idMetodoPrueba','class'=>'form-control' ,'value' => set_value('idMetodoPrueba'),'rows'=>4, 'readonly'=>true);
$cAnalisisSolicitado = array('id'=>'idAnalisisSolicitado','class'=>'form-control' ,'value' => set_value('idAnalisisSolicitado'), 'rows'=>4, 'readonly'=>true);
$cReferencia = array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'),'rows'=>2);
$cObsResultado = array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);
$cCondMuestra	= array('id'=>'idCondMuestra','class'=>'form-control' ,'value' => set_value('idCondMuestra'),'rows'=>2);

$cValidacion = array( 'id' => 'idParaValidacion','name'=>'idParaValidacion','class'=>'form-control',  'checked' => false);
//'style' => 'margin:10px'

//29/06/2017
$cInicialesAnalista = array( 'id' => 'idInicialesAnalista','name'=>'idInicialesAnalista','class'=>'form-control','value' => set_value('idInicialesAnalista'));
$dFechaFinal = array( 'id' => 'idFechaFinal','name'=>'idFechaFinal','class'=>'form-control','value' => set_value('idFechaFinal'));

//2017-08-21 --> anexado para cuando se trata de una edicion y se tienen q especificar las causas de dicha correcion
$cCausasCorreccion = array('id' =>'idCausasCorreccion','class'=>'form-control','value'=>set_value('idCausasCorreccion'),'rows'=>5);
$cIdTabla = array('id'=>'idTabla','class'=>'form_control','value'=>set_value('idTabla'),'style'=>'visibility:hidden');

//2017-08-17 --> para indicafr cuando un ensayo es para prueba preliminar
$lGeneraFolioIDR = array( 'name'=>'idGeneraFolioIDR','id'=>'idGeneraFolioIDR','class'=>'form-control' ,'checked'=>false,'value' => set_value('idGeneraFolioIDR'),'style'=>'margin:2px 0 0;border-color:none;box-shadow:none;','disabled'=>'disabled');


if ($accion =="ALTA") {
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;
	
	$cTmp = utf8_decode($datos_metodologia->ANALISIS_SOLICITADO);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	$cTmp = utf8_decode($datos_metodologia->METODOLOGIA_ESTUDIO);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	$cTmp = utf8_decode($datos_metodologia->REFERENCIA_ESTUDIO);
	$cReferencia['value'] = utf8_encode($cTmp);
	$cObsResultado['value'] = 'Ninguna';
	// hay q heredarlo..!
	$cCondMuestra['value'] = utf8_decode($datos_metodologia->CONDICIONES_MUESTRA);	
	$cIDR['value'] = $folios->IDR_AQ+1;
	
	//$cInicialesAnalista['value'] = '';
	$cInicialesAnalista['value'] = $_SESSION['iniciales_usuario'];
	$dFechaFinal['value']  = date( 'Y-m-d H:i:s ');	
	$cValueOpcSelected = 1;
	$cTecnica['value'] = 'Vapor Frio';
}

//2017-08-21 --> para las ediciones
//2017-07-25
if ($accion == 'EDICION'){
	$cIDR['value'] = $resultados->ID_IDR;
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;	
	
	$cTmp = utf8_decode($resultados->ANALISIS_SOLICITADO_MERCURIO);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );	
	$cTmp = utf8_decode($resultados->METODO_PRUEBA_MERCURIO);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);	
	$cTmp = utf8_decode($resultados->REFERENCIA_MERCURIO);
	$cReferencia['value'] = utf8_encode($cTmp);	
	$cTmp = utf8_decode( $resultados->OBSERVACION_MERCURIO);	
	$cObsResultado['value'] = utf8_encode($cTmp);
	// hay q heredarlo..!
	$cTmp = utf8_decode($resultados->CONDICIONES_MERCURIO);
	$cCondMuestra['value'] = utf8_encode($cTmp);		
	
	$cInicialesAnalista['value'] = $resultados->INICIALES_ANALISTA_MERCURIO;
	$dFechaFinal['value']  = $resultados->FECHA_FINAL_MERCURIO;		
	$cValueOpcSelected = $resultados->ID_USUARIO_SIGNATARIO;									  
	$cCausasCorreccion['value'] = '';	
	$cIdTabla['value'] = $resultados->ID_MERCURIO;	
	$cResultado_mercurio['value']	 = $resultados->RESULTADO_MERCURIO;
	$cLC['value'] = $resultados->LC_MERCURIO;
	$cLMP['value'] = $resultados->LMP_MERCURIO;	
	$cTecnica['value'] = $resultados->TECNICA_MERCURIO;	
	
	
	if ($datos_metodologia->GENERAR_IDR_MUESTRA== 1){
		if ($resultados->ID_IDR == 0 ){
			$cIDR['value'] = $folios->IDR_AQ+1;				
		}		
	}
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
//echo '<br/> Folio IDR MB array::';
//echo $folios->IDR_MB;
//echo '<br/>SQL para condiciones muestra:[';
//var_dump($sql2);
//echo '<br/>Datos vector Metodologia<br/>';
//var_dump( $datos_metodologia);
//echo $folio_sql_idr_aq;
//echo '<br/>';
//echo $datos_metodologia->CONDICIONES_MUESTRA;
//print_r($folios['IDR_AQ']);
//echo $folios[0]->IDR_AQ;
//echo '<br/>';
//print_r($folios);
?>

<!--<center><h4>DATOS DE LA MUESTRA</h4></center>-->
<hr size=10>

<!-- AQUI IRIA EL APARTADO DE LOS MENSAJES DE ERROR -->
<center><h4>RESULTADOS</h4></center>

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
	
	<div class="form-group col-xs-2 col-sm-2">
		<label>Fecha de Terminaci&oacute;n</label>
		<?php echo form_input($dFechaFinal); ?>		
	</div>	
	
</div>
<div class="row" align="">	
		
	<div class="form-group col-xs-2 col-sm-2" align="" >
		<label for=''>RESULTADO:</label>
		<?php echo form_input($cResultado_mercurio); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LC (mg/L):</label>
		<?php echo form_input($cLC); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LMP (mg/L):</label>
		<?php echo form_input($cLMP); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-3" align="" >
		<label for=''>T&Eacute;CNICA:</label>
		<?php echo form_input($cTecnica); ?>
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
	  <div class="form-group col-xs-1 col-sm-1"> 
	  	<?php echo form_checkbox($lGeneraFolioIDR);?>
	  	
	  </div>
	  <div class="form-group col-xs-4 col-sm-4">
	  	<p>Aplica para Pruebas Preliminares</p>
	  	
	  </div>
	</div>	
</div>


<hr size=10>
	
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
				<div id="divGrabaIDRMercurio">
					<button type="button" class="btn btn-primary GrabaIDRMercurio2" value="ALTA" id='_ibBtnGrabaIDRMercurio'>Grabar IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
		
		<!-- 2017-07-25 -->
		<div class="form-group col-xs-6 col-sm-2">
			<?php if($accion=='EDICION') { ?>	
				<div id='BtnActualizaIDRMercurio'>
					<button type="button" class="btn btn-primary GrabaIDRMercurio2" value="EDICION" id='_idBtnActualizarIDRMercurio'>Corregir IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
		<!-- boton para regrsarnos -->
	        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
		</div>		
		 
		 <div class="form-group col-xs-6 col-sm-3">
	        <div id="divBtnImprimeIDRMicrobiologia" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
	            <button type="button" class="btn btn-warning EnProcesoDesarrollo" formtarget="_blank" id="idBtnImprimeIDRMercurio"  >Imprimir IDR</button>
	        </div>	
		</div>
	</div> <!-- fin del row -->
</div><!-- fin del div contanier -->