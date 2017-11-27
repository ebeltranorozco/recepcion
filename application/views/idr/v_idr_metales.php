<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; } ?>INFORME DE RESULTADOS [QUIMICOS]</h3></center>
<?php 
$cIDR = array('id'=>'idIDR', 'class'=>'form-control', 'value'=>set_value('idIDR'),'readonly'=>true);
$cIdMuestra = array('id'=>'idMuestra','class'=>'form-control' ,'value' => set_value('idMuestra'),'readonly'=>true); 
$cIdMetodologia = array('id'=>'idMetodologia','class'=>'form-control' ,'value' => set_value('idMetodologia'),'readonly'=>true); 

$cAnalisisSolicitado = array('id'=>'idAnalisisSolicitado','class'=>'form-control' ,'value' => set_value('idAnalisisSolicitado'), 'rows'=>3, 'readonly'=>true);
$cMetodoPrueba = array('id'=>'idMetodoPrueba','class'=>'form-control' ,'value' => set_value('idMetodoPrueba'),'rows'=>3, 'readonly'=>true);

//04/07/2017
$cResultado_cobre 	= array( 'id' => 'resultado_cobre','name'=>'resultado_cobre','class'=>'form-control','value' => set_value('resultado_cobre'));
$cLC_cobre 			= array( 'id' => 'lc_cobre','name'=>'lc_cobre','class'=>'form-control','value' => set_value('lc_cobre'));
$cLMP_cobre 		= array( 'id' => 'lmp_cobre','name'=>'lmp_cobre','class'=>'form-control','value' => set_value('lmp_cobre'));
$cTecnica_cobre 		= array( 'id' => 'tecnica_cobre','name'=>'tecnica_cobre','class'=>'form-control','value' => set_value('tecnica_cobre'));

$cResultado_manganeso 	= array( 'id' => 'resultado_manganeso','name'=>'resultado_manganeso','class'=>'form-control','value' => set_value('resultado_manganeso'));
$cLC_manganeso 			= array( 'id' => 'lc_manganeso','name'=>'lc_manganeso','class'=>'form-control','value' => set_value('lc_manganeso'));
$cLMP_manganeso 		= array( 'id' => 'lmp_manganeso','name'=>'lmp_manganeso','class'=>'form-control','value' => set_value('lmp_manganeso'));
$cTecnica_manganeso		= array( 'id' => 'tecnica_manganeso','name'=>'tecnica_manganeso','class'=>'form-control','value' => set_value('tecnica_manganeso'));

$cResultado_niquel 	= array( 'id' => 'resultado_niquel','name'=>'resultado_niquel','class'=>'form-control','value' => set_value('resultado_niquel'));
$cLC_niquel 		= array( 'id' => 'lc_niquel','name'=>'lc_niquel','class'=>'form-control','value' => set_value('lc_niquel'));
$cLMP_niquel 		= array( 'id' => 'lmp_niquel','name'=>'lmp_niquel','class'=>'form-control','value' => set_value('lmp_niquel'));
$cTecnica_niquel 	= array( 'id' => 'tecnica_niquel','name'=>'tecnica_niquel','class'=>'form-control','value' => set_value('tecnica_niquel'));


$cReferencia = array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'),'rows'=>2);
$cObsResultado = array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);
$cCondMuestra	= array('id'=>'idCondMuestra','class'=>'form-control' ,'value' => set_value('idCondMuestra'),'rows'=>2);

$cValidacion = array( 'id' => 'idParaValidacion','name'=>'idParaValidacion','class'=>'form-control',  'checked' => false);
//'style' => 'margin:10px'

//29/06/2017
$cInicialesAnalista = array( 'id' => 'idInicialesAnalista','name'=>'idInicialesAnalista','class'=>'form-control','value' => set_value('idInicialesAnalista'));
$dFechaFinal = array( 'id' => 'idFechaFinal','name'=>'idFechaFinal','class'=>'form-control','value' => set_value('idFechaFinal'));

if ($accion =="ALTA") {
$cIDR['value'] = $folios->IDR_AQ+1;
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;	
	
	$cTmp = utf8_decode($datos_metodologia->ANALISIS_SOLICITADO);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	$cTmp = utf8_decode($datos_metodologia->METODOLOGIA_ESTUDIO);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	$cTmp = utf8_decode($datos_metodologia->REFERENCIA_ESTUDIO);
	$cReferencia['value'] = utf8_encode($cTmp);
	$cObsResultado['value'] = utf8_decode('Ninguna');
	// hay q heredarlo..!
	$cTmp = utf8_decode($datos_metodologia->CONDICIONES_MUESTRA);
	$cCondMuestra['value'] = utf8_encode($cTmp);	
	
	$cInicialesAnalista['value'] = '';
	$dFechaFinal['value']  = date( 'Y-m-d H:i:s ');
	
	//2017-07-04 --> VALORES POR DEFAULT SEGUN INFORME DE RESULTADOS..!
	
	$cResultado_cobre['value'] = 'ND';
	$cLC_cobre['value'] = '0.0040';
	$cLMP_cobre['value'] = '2.0';
	$cTecnica_cobre['value'] = 'Horno de Grafito';

	$cResultado_manganeso['value'] = 'ND';
	$cLC_manganeso['value'] = '0.0020';
	$cLMP_manganeso['value'] = '0.15';
	$cTecnica_manganeso['value'] = 'Horno de Grafito';

	$cResultado_niquel['value'] = 'ND';
	$cLC_niquel['value'] = '0.0160';
	$cLMP_niquel['value'] = '6.0';
	$cTecnica_niquel['value'] = 'Horno de Grafito';
	
}

//var_dump( $resultados );
//echo '<br/>Cadena SQL:';
//var_dump( $sql);
//echo '<br/> Folio IDR AQ array:';
//var_dump($folios);
//var_dump($folios);
//echo '<br/> Folio IDR MB array::';
//echo $folios->IDR_AQ;
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
<?php
//echo $this->table->generate($resultados); 
?>

<div class="container">
<div class="row" align="center">
	<div class="form-group col-xs-3 col-sm-3">
		<?php echo form_input($cIDR); ?>
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
		<label for="">Analisis Solicitado</label>
		<?php echo form_textarea($cAnalisisSolicitado); ?>
	</div>
	
	<div class="form-group col-xs-3 col-sm-3">
		<label>Metodo de Prueba</label>
		<?php echo form_textarea($cMetodoPrueba); ?>		
	</div>
	
	<div class="form-group col-xs-2 col-sm-2">
		<label>Iniciales del Analista</label>
		<?php echo form_input($cInicialesAnalista); ?>		
	</div>	
	
	<div class="form-group col-xs-2 col-sm-2">
		<label>Fecha de Terminaci&oacute;n</label>
		<?php echo form_input($dFechaFinal); ?>		
	</div>	
	
</div>
<div class="row" align="">			
	<div class="form-group col-xs-2 col-sm-2" align="" >
		<label for=''>COBRE:</label>
		<?php echo form_input($cResultado_cobre); ?>
	</div>		
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LC (mg/L):</label>
		<?php echo form_input($cLC_cobre); ?>
	</div>		
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LMP (mg/L):</label>
		<?php echo form_input($cLMP_cobre); ?>
	</div>		
	<div class="form-group col-xs-3 col-sm-3" align="" >
		<label for=''>TECNICA:</label>
		<?php echo form_input($cTecnica_cobre); ?>
	</div>
</div>

<div class="row">	
	<div class="form-group col-xs-2 col-sm-2" align="" >
		<label for=''>MANGANESO:</label>
		<?php echo form_input($cResultado_manganeso); ?>
	</div>	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LC (mg/L):</label>
		<?php echo form_input($cLC_manganeso); ?>
	</div>	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LMP (mg/L):</label>
		<?php echo form_input($cLMP_manganeso); ?>
	</div>	
	<div class="form-group col-xs-3 col-sm-3" align="" >
		<label for=''>TECNICA:</label>
		<?php echo form_input($cTecnica_manganeso); ?>
	</div>
</div>
<div class="row" >		
	
	<div class="form-group col-xs-2 col-sm-2" align="" >
		<label for=''>NIQUEL:</label>
		<?php echo form_input($cResultado_niquel); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LC (mg/L):</label>
		<?php echo form_input($cLC_niquel); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-2" align="" >
		<label for=''>LMP (mg/L):</label>
		<?php echo form_input($cLMP_niquel); ?>
	</div>	
	
	<div class="form-group col-xs-3 col-sm-3" align="" >
		<label for=''>TECNICA:</label>
		<?php echo form_input($cTecnica_niquel); ?>
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
	  <label><input type="checkbox" name="idParaValidacion" id="idParaValidacion"> metodo para validaci&oacute;n (no genera IDR)</label>
	</div>	
</div>


<hr size=10>
	<!-- BOTONES DE AGREGAR Y DE IMPRIMIR-->
	<div class="row">
		<div class="form-group col-xs-6 col-sm-2" id="DivGrabaIDRMetales">
			<?php if($accion=='ALTA') { ?>			
				<button type="button" class="btn btn-primary" id='ibBtnGrabaIDRMetales'>Grabar IDR</button>				
			<?php } // fin de acion = alta?>
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
		<!-- boton para regrsarnos -->
	        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
		</div>		
		 
		 <div class="form-group col-xs-6 col-sm-3">
	        <div id="divBtnImprimeIDRMetales" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
	            <button type="button" class="btn btn-warning" formtarget="_blank" id="idBtnImprimeIDRMetales"  >Imprimir IDR</button>
	        </div>	
		</div>
	</div> <!-- fin del row -->
</div><!-- fin del div contanier -->