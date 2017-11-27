<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; } ?>INFORME DE RESULTADOS [<?php if ($AreaEstudio=='Q'){echo "QUIMICO";}else { echo "MICROBIOLOGICO";} ?>]</h3></center>
<?php 
//var_dump($sql);
/* definicion de variables */
//$dFecha = date('Y-M-D');
$dFecha = array('name'=>'fecha_emision','id'=>'fecha_emision','class'=>'form-control' ,'value' => set_value('fecha_emision'),'readonly'=>true);
$dFecha['value'] = date('Y-m-d'); $dFecha['readonly'] = true;
$cDesc = array('name'=>'descripcion_muestra','id'=>'descripcion_muestra','class'=>'form-control' ,'value' => set_value('descripcion_muestra'),'readonly'=>true, 'rows'=>2);
$nCant = array('name'=>'no_muestras','id'=>'no_muestras','class'=>'form-control' ,'value' => set_value('no_muestras'),'readonly'=>true);
$nLote = array('name'=>'lote_muestra','id'=>'lote_muestra','class'=>'form-control' ,'value' => set_value('lote_muestra'),'readonly'=>true);
$dFechaRecepcion = array('name'=>'fecha_recepcion','id'=>'fecha_recepcion','class'=>'form-control' ,'value' => set_value('fecha_recepcion'),'readonly'=>true);
$cUbica = array('name'=>'ubicacion','id'=>'ubicacion','class'=>'form-control' ,'value' => set_value('ubicacion'),'readonly'=>true);
$cIdCte = array('name'=>'id_cliente','id'=>'id_cliente','class'=>'form-control' ,'value' => set_value('id_cliente'),'readonly'=>true);
$dFechaAnalisis = array('name'=>'fecha_analisis','id'=>'fecha_analisis','class'=>'form-control' ,'value' => set_value('fecha_analisis'),'readonly'=>true);

$cAnalisisSolicitado = array('id'=>'idAnalisisSolicitado','class'=>'form-control' ,'value' => set_value('idAnalisisSolicitado'), 'rows'=>2,'readonly' => true);
$nId_Muestra = array('name'=>'id_muestra','id'=>'id_muestra','class'=>'form-control' ,'value' => set_value('id_muestra'),'readonly'=>true);
$nId_Detalle_Muestra = array('name'=>'id_detalle_muestra','id'=>'id_detalle_muestra','class'=>'form-control' ,'value' => set_value('id_detallado_muestra'),'readonly'=>true);


// Variables parael Boton Agregar. !
$cLinfoncito = array('id'=>'idLinfoncito','class'=>'form-control' ,'value' => set_value('idLinfoncito'));
$cResultado = array('id'=>'idResultado_linfoncito','class'=>'form-control' ,'value' => set_value('idResultado_linfoncito'));
$cCH = array ('id'=>'idCH','class'=>'form-control' ,'value' => set_value('idCH'));
$cCA = array ('id'=>'idCA','class'=>'form-control' ,'value' => set_value('idCA'));
$cTCH = array ('id'=>'idTCH','class'=>'form-control' ,'value' => set_value('idTCH'));
$cTCA = array ('id'=>'idTCA','class'=>'form-control' ,'value' => set_value('idTCA'));

$cMetodoPrueba = array('id'=>'idMetodoPrueba','class'=>'form-control' ,'value' => set_value('idMetodoPrueba'),'rows'=>3);

$cReferencia = array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'));
$cObsResultado = array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);
$cCondMuestra	= array('id'=>'idCondMuestra','class'=>'form-control' ,'value' => set_value('idCondMuestra'));
/// fin de la definicion de la variables...

$dFechaAnalisis['value'] = date('Y-m-d');

// anexado para desactivar en caso de analisis microbiologico
if ($AreaEstudio == 'M') { // anexado 24/11/16
	$cCH['readonly'] = true;
	$cCA['readonly'] = true;
	$cTCH['readonly'] = true;
	$cTCA['readonly'] = true;
}




// ANEXADO PARA SABER EL ID DEL DETALLADO EN JUEGO
$nId_Detalle_Muestra['value'] = date('y').'-'.str_pad($idDetalleMuestraProcesando,4,'0',STR_PAD_LEFT);


//$nId_Muestra['value'] = $datos_muestra[0]->ID;

//$nId_Muestra['value'] = $idProcesando;
// cehcando ahora $ tenga informacion datos_muestra
if ($datos_muestra) {
	$nId_Muestra['value'] = $datos_muestra[0]->ID_MUESTRA; // este si es el id de la muestra real 24/11/16
	$cDesc['value'] = $datos_muestra[0]->DESCRIPCION_MUESTRA;
	$nCant['value'] = $datos_muestra[0]->NO_MUESTRAS;
	$nLote['value'] = $datos_muestra[0]->LOTE_MUESTRA;
	$dFechaRecepcion['value'] = $datos_muestra[0]->FECHA_RECEPCION;
	$cUbica['value'] = 'N/A';
	$cIdCte['value'] = $datos_muestra[0]->ID_CLIENTE;
	if (isset($datos_muestra[0]->FECHA_ANALISIS)) {
		$dFechaAnalisis['value'] = $datos_muestra[0]->FECHA_ANALISIS;
	}
	$cAnalisisSolicitado['value'] = $datos_muestra[0]->ANALISIS_SOLICITADO;

	if ($AreaEstudio == 'M') { // anexado 24/11/16
		$cLinfoncito['value'] = $datos_muestra[0]->ANALISIS_SOLICITADO;
	}	

	$cMetodoPrueba['value'] = $datos_muestra[0]->METODOLOGIA_ESTUDIO;

	$cCondMuestra['value'] = $datos_muestra[0]->CONDICION_MUESTRA;

	//OBSERVACION_RESULTADO
	$cReferencia['value'] = $datos_muestra[0]->REFERENCIA_RESULTADO;
	$cObsResultado['value'] = $datos_muestra[0]->OBSERVACION_RESULTADO;
	//$cReferencia = array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'));
	//$cObsResultado = array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);	

}
// FIN DE IF $DATOS_MUESTRA

?>
<div class="container">
<label>Id Informe de Resultados:</label>
<?php echo form_input($nId_Detalle_Muestra); ?>
<div class="row">
    <div class="form-group col-xs-6 col-sm-3"> 
      <label for="fecha_emision">Fecha Emision</label>
      <?php echo form_input($dFecha); ?>
    </div>
</div> <!-- fin del row -->

<div class="row">
<hr size=10>
<center><h4>DATOS DE LA MUESTRA</h4></center>
<hr size=10>
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Descripción:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_textarea($cDesc); ?>
	</div>
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Ubicación:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($cUbica); ?>
	</div>
</div> <!-- FIN DEL ROW -->
<div class="row">
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Cantidad:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($nCant); ?>
	</div>
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Id Asignada por el cliente:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($cIdCte); ?>
	</div>
</div> <!-- FIN DEL ROW -->
<div class="row">
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Lote:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($nLote); ?>
	</div>
	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Fecha de Analisis:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($dFechaAnalisis); ?>
	</div>
</div> <!-- FIN DEL ROW -->
<div class="row">

	<div class="form-group  col-xs-6 col-sm-3">
		<label for=''>Fecha/hora Recepción:</label>		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		<?php echo form_input($dFechaRecepcion); ?>
	</div>
	<div class="form-group  col-xs-6 col-sm-3">
		
	</div>
	<div class="form-group  col-xs-6 col-sm-3">			
		
	</div>
</div> <!-- FIN DEL ROW -->


<!-- DETALLADO DE RESULTADOS -->
<div class="row">
	<hr size=10>
	<center><h4>RESULTADOS</h4></center>
	<hr size=10>	
</div> <!-- FIN DEL ROW -->

<div class="row">
	<div class="form-group col-xs-6 col-sm-3" >
		<label for=''>ID MUESTRA:</label>
		<?php echo form_input($nId_Muestra); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-3" >
		<label for=''>Analisis Solicitado:</label>
	</div>
	<div class="form-group col-xs-6 col-sm-6" >
		<?php echo form_textarea($cAnalisisSolicitado); ?>
	</div>
</div>
<br>
<div class="row">
	<div class="form-group col-xs-6 col-sm-3">
		<label>Analito</label>
		<!--ANEXADO UN COMBOBOX PARA LOS LINFOCNTITOS DE ESE ESTUDIO  09/12/2016-->
		<?php echo form_dropdown('idLinfoncitoCombo',$LinfoncitosCombo,$selected = 1,'class="form-control" id="idLinfoncitoCombo"'); ?>
		<?php echo form_input($cLinfoncito); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-3">
		<label>Resultado</label>
		<?php echo form_input($cResultado); ?>
	</div>

	<div class="form-group col-xs-6 col-sm-2">
		<label><?php echo $datos_muestra[0]->NOMBRE_CPO1_INFORME; ?></label>
		<?php echo form_input($cCH); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-2">
		<label><?php echo $datos_muestra[0]->NOMBRE_CPO2_INFORME; ?></label>
		<?php echo form_input($cCA); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-2">	
		<label>Anexar Estudio</label>
		<button type="text" class="btn btn-primary" id="idBtnAgregarResultadoInforme" >Agregar al Resultado</button>
	</div>

</div>
<br>
<br>
<hr size=10>

<?php echo $this->table->generate($resultados); ?>

<br>
<hr size=10>
<br>

<div class="row">
	<div class="form-group col-xs-6 col-sm-2">
		<label>Total CH</label>
		<?php echo form_input($cTCH); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-2">
		<label>Total CA</label>
		<?php echo form_input($cTCA); ?>
	</div>
	<div class="form-group col-xs-6 col-sm-4">
		<label>Metodo de Prueba</label>
		<?php echo form_textarea($cMetodoPrueba); ?>		
	</div>
</div>
<div class="row">
	<div class="form-group col-xs-6 col-sm-8">
		<label>Referencias de Aplicación de Metodología:</label>
		<?php echo form_input($cReferencia); ?>
	</div>
</div>
<div class="row">
	<div class="form-group col-xs-6 col-sm-8">
		<label>Notas u Observaciónes:</label>
		<?php echo form_textarea($cObsResultado); ?>
	</div>
</div>
<div class="row">
	<div class="form-group col-xs-6 col-sm-8">
		<label>Condiciones de recepcion de la Muestra:</label>
		<?php echo form_input($cCondMuestra); ?>
	</div>
</div>


<hr size=10>


<!-- BOTONES DE AGREGAR Y DE IMPRIMIR-->
<div class="row">
	<div class="form-group col-xs-6 col-sm-2">
		<div id="divBtnGeneraInformeResultados" <?php if($accion <> 'ALTA'){ echo ' style="display: none;"';}?>>	
			<button type="button" class="btn btn-primary" id="idBtnGrabarInformeResultados">Generar Informe</button>
		</div>
	</div>
	<div class="form-group col-xs-6 col-sm-2">
	<!-- boton para regrsarnos -->
        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
	</div>


	 <div class="form-group col-xs-6 col-sm-3">
        <div id="divBtnImprimirInformeResultados" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
            <button type="button" class="btn btn-warning" formtarget="_blank" id="idBtnImprimirInformeResultados"  >Imprimir</button>
        </div>	
	</div>
</div> <!-- fin del row -->

<?php 
echo 'linfoncitos = ' . var_dump($linfoncitos).'<br/> SQL ='. $sql3."<br/>";
var_dump($resultados); echo '<br><br>Datos Muestra<br/>';
var_dump($datos_muestra); echo '<br/>';
var_dump($sql);
echo '<br><br><br><br><br>';
var_dump($accion);
var_dump($sql2);
//ME QUEDE CHJECANDO PORQUE NO ME DA LOS ID DE AFLAXOTINAS ME ARROJA OTRO, COMO Q ES BRONCA DE LOS ID
//PARECE QUE YA QUEDO ESTO DE ARRIBA
?>



</div> <!-- fin del container -->
