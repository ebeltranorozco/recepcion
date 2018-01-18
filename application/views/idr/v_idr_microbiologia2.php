<!-- SE DESARROLLO EN ENERO DEL 2018 POR PETICION DE ROSELA, DONDE SE AGREGARON 3 CAMPOS A LA BASE DE DATOS COL TOTALES/FECAL/ECOLI -->
<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; } ?>INFORME DE RESULTADOS [MICROBIOLOGICOS]</h3></center>
<?php 
$cIdMuestra = array('id'=>'idMuestra','class'=>'form-control' ,'value' => set_value('idMuestra'),'readonly'=>true); 
$cIdMetodologia = array('id'=>'idMetodologia','class'=>'form-control' ,'value' => set_value('idMetodologia'),'readonly'=>true); 
$cIDR = array('id'=>'idIDR', 'class'=>'form-control', 'value'=>set_value('idIDR'),'readonly'=>true);

$cResultado_microbiologia = array('id'=>'idResultado_microbiologia','class'=>'form-control' ,'value' => set_value('idResultado_microbiologia'));
// 2018-01-17 --> 3 campos anexado el cpo de arriba ya no se usara
$cResultado_ColTotales_microbiologia = array('id'=>'idResultado_coltotales_microbiologia','class'=>'form-control' ,'value' => set_value('idResultado_coltotales_microbiologia'));
$cResultado_ColFecales_microbiologia = array('id'=>'idResultado_colfecales_microbiologia','class'=>'form-control' ,'value' => set_value('idResultado_colfecales_microbiologia'));
$cResultado_ecoli_microbiologia = array('id'=>'idResultado_ecoli_microbiologia','class'=>'form-control' ,'value' => set_value('idResultado_ecoli_microbiologia'));

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

//var_dump($datos_metodologia);

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
	$cTmp = utf8_encode($datos_metodologia->CONDICIONES_MUESTRA);
	$cCondMuestra['value'] = utf8_decode($cTmp);	
	//$cCondMuestra['value'] = $datos_metodologia->CONDICIONES_MUESTRA;	
	$cIDR['value'] = $folios->IDR_MB+1;
	
	//$cInicialesAnalista['value'] = '';
	$cInicialesAnalista['value'] = $_SESSION['iniciales_usuario'];
	$dFechaFinal['value']  = date( 'Y-m-d H:i:s ');	
	$cValueOpcSelected = 1;
}

//2017-08-21 --> para las ediciones
//2017-07-25
if ($accion == 'EDICION'){
	$cIDR['value'] = $resultados->ID_IDR;
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;
	
	//$cTmp = utf8_decode($datos_metodologia->ANALISIS_SOLICITADO);
	$cTmp = utf8_decode($resultados->ANALISIS_SOLICITADO_MICROBIOLOGIA);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	//$cTmp = utf8_decode($datos_metodologia->METODOLOGIA_ESTUDIO);
	$cTmp = utf8_decode($resultados->METODO_PRUEBA_MICROBIOLOGIA);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	//$cTmp = utf8_decode($datos_metodologia->REFERENCIA_ESTUDIO);
	$cTmp = utf8_decode($resultados->REFERENCIA_MICROBIOLOGIA);
	$cReferencia['value'] = utf8_encode($cTmp);
	
	$cTmp = utf8_decode( $resultados->OBSERVACION_MICROBIOLOGIA);	
	//$cObsResultado['value'] = 'Ninguna';
	$cObsResultado['value'] = utf8_encode($cTmp);
	// hay q heredarlo..!
	$cTmp = utf8_decode($resultados->CONDICIONES_MICROBIOLOGIA);
	$cCondMuestra['value'] = utf8_encode($cTmp);
		
	
	$cInicialesAnalista['value'] = $resultados->INICIALES_ANALISTA_MICROBIOLOGIA;
	$dFechaFinal['value']  = $resultados->FECHA_MICROBIOLOGIA;
		
	$cValueOpcSelected = $resultados->ID_USUARIO_SIGNATARIO;	
	
	$cCausasCorreccion['value'] = '';
	
	$cIdTabla['value'] = $resultados->ID_MICROBIOLOGIA;
	
	$cResultado_microbiologia['value']	 = $resultados->RESULTADO_MICROBIOLOGIA;

	//2018-01-17
	$cResultado_ColTotales_microbiologia['value'] 	= $resultados->RESULTADO_COLIFORMES_TOTALES_MICROBIOLOGIA;
	$cResultado_ColFecales_microbiologia['value'] 	= $resultados->RESULTADO_COLIFORMES_FECALES_MICROBIOLOGIA;
	$cResultado_ecoli_microbiologia['value'] 		= $resultados->RESULTADO_ECOLI_MICROBIOLOGIA;

	
	
	if ($datos_metodologia->GENERAR_IDR_MUESTRA== 1){
		if ($resultados->ID_IDR == 0 ){
			$cIDR['value'] = $folios->IDR_MB+1;				
		}		
	}
}
//2017-08-17 --> para saber si se debe de aplicar a pruebas preliminares o no..!
if (!$datos_metodologia->GENERAR_IDR_MUESTRA) {
	$lGeneraFolioIDR['checked'] = TRUE;
}



/*
var_dump( $resultados );
echo '<br/>Cadena SQL:';
var_dump( $sql);
echo '<br/> Folio IDR AQ array:';
var_dump($folios);
//var_dump($folios);
echo '<br/> Folio IDR MB array::';
*/
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
<div class="row">
	<div class="form-group col-sm-9">
		<label>Resultados General:</label>
		<?php echo form_input($cResultado_microbiologia); ?>
	</div>
</div>


<div class="row" align="">		
	<div class="form-group col-xs-4 col-sm-3" align="" >

		<label for=''>Coliformes Totales:</label>
		<?php echo form_input($cResultado_ColTotales_microbiologia); ?>
	</div>	

	<div class="form-group col-xs-4 col-sm-3" align="" >
		<label for=''>Coliformes Fecales:</label>
		<?php echo form_input($cResultado_ColFecales_microbiologia); ?>
	</div>	

	<div class="form-group col-xs-4 col-sm-3" align="" >
		<label for=''><i>E. Coli:</i></label>
		<?php echo form_input($cResultado_ecoli_microbiologia); ?>
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
	  <!--<label><input type="checkbox" name="idParaValidacion" id="idParaValidacion"> metodo para validaci&oacute;n (no genera IDR)</label>-->
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
				<div id="divGrabaIDRMicrobiologia">
					<button type="button" class="btn btn-primary GrabaIDRMicrobiologia" value="ALTA" id='_ibBtnGrabaIDRMicrobiologia'>Grabar IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
		
		<!-- 2017-07-25 -->
		<div class="form-group col-xs-6 col-sm-2">
			<?php if($accion=='EDICION') { ?>	
				<div id='BtnActualizaIDRMicrobiologia'>
					<button type="button" class="btn btn-primary GrabaIDRMicrobiologia" value="EDICION" id='idBtnActualizarIDRMicrobiologia'>Corregir IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
		<!-- boton para regrsarnos -->
	        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
		</div>		
		 
		 <div class="form-group col-xs-6 col-sm-3">
	        <div id="divBtnImprimeIDRMicrobiologia" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
	            <button type="button" class="btn btn-warning EnProcesoDesarrollo" formtarget="_blank" id="idBtnImprimeIDRMicrobiologia"  >Imprimir IDR</button>
	        </div>	
		</div>
	</div> <!-- fin del row -->
</div><!-- fin del div contanier -->