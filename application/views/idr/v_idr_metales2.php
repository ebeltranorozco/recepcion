<center><h3><?php if ($accion =="ALTA") { echo 'NUEVO '; } ?>INFORME DE RESULTADOS [QUIMICOS]</h3></center>
<?php
$cIdMuestra = 		array('id'=>'idMuestra','class'=>'form-control' ,'value' => set_value('idMuestra'),'readonly'=>true); 
$cIdMetodologia = 	array('id'=>'idMetodologia','class'=>'form-control' ,'value' => set_value('idMetodologia'),'readonly'=>true); 
$cIDR = 			array('id'=>'idIDR', 'class'=>'form-control', 'value'=>set_value('idIDR'),'readonly'=>true);

$cAnalisisSolicitado = array('id'=>'idAnalisisSolicitado','class'=>'form-control' ,'value' => set_value('idAnalisisSolicitado'), 'rows'=>4, 'readonly'=>true);
$cMetodoPrueba = array('id'=>'idMetodoPrueba','class'=>'form-control' ,'value' => set_value('idMetodoPrueba'),'rows'=>4, 'readonly'=>true);

$dFechaAnalisis['value'] = date('Y-m-d H:i:s');

$cReferencia = 		array('id'=>'idReferencia','class'=>'form-control' ,'value' => set_value('idReferencia'),'rows'=>2);

$cObsResultado = 	array('id'=>'idObsResultado','class'=>'form-control' ,'value' => set_value('idObsResultado'),'rows'=>5);

$cCondMuestra	= 	array('id'=>'idCondMuestra','class'=>'form-control' ,'value' => set_value('idCondMuestra'),'rows'=>2);

$cValidacion = 		array( 'id' => 'idParaValidacion','name'=>'idParaValidacion','class'=>'form-control',  'checked' => false);

//variables temporal para pasar a la tabla..!
$cResultado = 		array('id'=>'idResultado_metal','class'=>'form-control' ,'value' => set_value('idResultado_metal'));
$cLC = 				array('id'=>'idLC_metal','class'=>'form-control' ,'value' => set_value('idLC_metal'));
$cLMP = 			array('id'=>'idLMP_metal','class'=>'form-control' ,'value' => set_value('idLMP_metal'));
$cTecnica =			array('id'=>'idTecnica','class'=>'form-control' ,'value' => set_value('idTecnica'));

//$cInicialesAnalista = array( 'id' => 'idInicialesAnalista','name'=>'idInicialesAnalista','class'=>'form-control','value' => set_value('idInicialesAnalista'));
//$cSignatario	= array('id' => 'idSignatarioInicialesAnalista','name'=>'idInicialesAnalista','class'=>'form-control','value' => set_value('idInicialesAnalista'));
$dFechaFinal = array( 'id' => 'idFechaFinal','name'=>'idFechaFinal','class'=>'form-control','value' => set_value('idFechaFinal'));

//2017-07-07
//$cAnalito = 		array('id'=>'idAnalito','class'=>'form-control' ,'value' => set_value('idAnalito'),'readonly'=>true);

//2017-08-25
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


/*
var_dump( $resultados );
echo '<br/>Cadena SQL:';
var_dump( $sql2);
echo '<br/> Folio IDR AQ array:';
//var_dump($folios);
//var_dump($folios);
echo '<br/> Folio IDR AQ array::';
echo $folios->IDR_AQ;
echo '<br/>';
//echo $folio_sql_idr_aq;
echo '<br/>Signatarios Combo =';;
//var_dump($SignatariosCombo);
//echo '<br/>Signatarios  =';
//var_dump( $signatarios );
echo '<br/>Seccion [=';
//var_dump($_SESSION);
*/
if ($accion =="ALTA") {
	
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;

	$cTmp = utf8_decode($datos_metodologia->ANALISIS_SOLICITADO);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );
	$cTmp = utf8_decode($datos_metodologia->METODOLOGIA_ESTUDIO);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);
	$cTmp = utf8_decode($datos_metodologia->REFERENCIA_ESTUDIO);
	$cReferencia['value'] = utf8_encode($cTmp);
	
	//$cTmp = utf8_decode('ND = No detectado, No cuantificado, LC = Cromat&oacute;grafo de liquido, GC  = Cromat&oacute;grafo de Gases, LMP = Limite m&aacute;ximo permisible segun la ');
	//$cTmp = 'ND = No detectado, No cuantificado, LC = Cromatógrafo de liquido, GC  = Cromatógrafo de Gases, LMP = Limite máximo permisible segun la ';
	//$cObsResultado['value'] = utf8_encode($cTmp);
	$cObsResultado['value'] = ''; //2017-09-11 --> solicitado por Dacia...!
	
	//$cObsResultado['value'] = 'prueba de como meterle algo';
	echo form_textarea($cObsResultado);
	// hay q heredarlo..!
	$cTmp = utf8_decode($datos_metodologia->CONDICIONES_MUESTRA);
	$cCondMuestra['value'] = utf8_encode($cTmp);	
	
	
	$cInicialesAnalista['value'] = $_SESSION['iniciales_usuario'];
	$dFechaFinal['value']  = date( 'Y-m-d H:i:s ');	
	$cValueOpcSelected = 1;

	$cResultado['value'] = 'ND';
	$cLC['value'] = '0.00';
	$cLMP['value'] = '-';
	$cTecnica['value'] = 'Horno de Grafito';
	
	$cIDR['value'] = $folios->IDR_AQ+1;
} // FIN DE LA ALTA

if ($accion == 'EDICION'){
	$cIDR['value'] = $resultados[0]['ID_IDR'];
	$cIdMuestra['value'] = $idMuestra;
	$cIdMetodologia['value'] = $idMetodologia;	
	
	$cTmp = utf8_decode($resultados[0]['ANALISIS_SOLICITADO_METALES']);
	$cAnalisisSolicitado['value'] = utf8_encode($cTmp );	
	$cTmp = utf8_decode($resultados[0]['METODO_PRUEBA_METALES']);
	$cMetodoPrueba['value'] = utf8_encode($cTmp);	
	$cTmp = utf8_decode($resultados[0]['REFERENCIA_METALES']);
	$cReferencia['value'] = utf8_encode($cTmp);	
	$cTmp = utf8_decode( $resultados[0]['OBSERVACION_METALES']);	
	$cObsResultado['value'] = utf8_encode($cTmp);
	// hay q heredarlo..!
	$cTmp = utf8_decode($resultados[0]['CONDICIONES_METALES']);
	$cCondMuestra['value'] = utf8_encode($cTmp);		
	
	$cInicialesAnalista['value'] = $resultados[0]['INICIALES_ANALISTA_METALES'];
	$dFechaFinal['value']  = $resultados[0]['FECHA_FINAL_METALES'];
	$cValueOpcSelected = $resultados[0]['ID_USUARIO_SIGNATARIO'];
	$cCausasCorreccion['value'] = '';	
	$cIdTabla['value'] = $resultados[0]['ID_ENC_METALES'];
	//$cResultado_mercurio['value']	 = $resultados->RESULTADO_MERCURIO;
	//$cLC['value'] = $resultados->LC_MERCURIO;
	//$cLMP['value'] = $resultados->LMP_MERCURIO;	
	//$cTecnica['value'] = $resultados->TECNICA_MERCURIO;	
	
	
	if ($datos_metodologia->GENERAR_IDR_MUESTRA== 1){
		if ($resultados[0]['ID_IDR'] == 0 ){
			$cIDR['value'] = $folios->IDR_AQ+1;
		}		
	}
}


//2017-08-17 --> para saber si se debe de aplicar a pruebas preliminares o no..!
if (!$datos_metodologia->GENERAR_IDR_MUESTRA) {
	$lGeneraFolioIDR['checked'] = TRUE;
}
?>
<!------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------->
<?php 
//variables temporal para la segunda tabla ..!
$cMetal2 		= array('id'=>'idMetal2','class'=>'form-control' ,'value' => set_value('idMetal2'),'readonly'=>true);
$cResultado2 	= array('id'=>'idResultado_Metal2','class'=>'form-control' ,'value' => set_value('idResultado_Metal2'));
$cLC2 			= array('id'=>'idLC_Metal2','class'=>'form-control' ,'value' => set_value('idLC_Metal2'));
$cLMP2 			= array('id'=>'idLMP_Metal2','class'=>'form-control' ,'value' => set_value('idLMP_Metal2'));
$cTecnica2 		= array('id'=>'idTecnica_Metal2','class'=>'form-control' ,'value' => set_value('idTecnica_Metal2'));

?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">EDICION METAL</h4>            
          </div> <!--fin de modal header -->

          <div class="modal-body">

            <!-- vamos agregar las alertas -->
            <div id="msg_alerta_modal_IDRPlagicidas"></div> 
           
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">METAL:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cMetal2); ?>
                    </div>
              </div>
            </div>

            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">RESULTADO:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cResultado2); ?>
                    </div>
              </div>
            </div>
            
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">LIMITE DE CUANTIFICACION:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cLC2); ?>
                    </div>
              </div>
            </div>

            <div class="row">              
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">LIMITE MAXIMO PERMISIBLE:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cLMP2); ?>
                    </div>
              </div> 
            </div>

            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="Lote">TECNICA:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cTecnica2); ?>
                    </div>
              </div>
            </div>     

          </div> <!--fin de modal body -->
          

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="idBtnCloseModalIDRMetales">Salir</button>
            <!--<button type="button" class="btn btn-primary">Anexar</button>-->
            <button type="button" class="btn btn-primary" data-dismiss="modal" name="btnAddDatosIDRMetales" id="btnAddDatosIDRMetales">Actualizar</button>
          </div> <!--fin de modal footer -->
        
        </div> <!--fin de modal content -->
      
      </div> <!--fin de modal dialog -->
    </div> <!-- fin de modal fade (dice q esta de mas esta etiqueta-->


<!------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------->    

<hr size=10>
<center><h4>RESULTADOS</h4></center>

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
		
		<div class="form-group col-xs-6 col-sm-2">
			<label>Signatario</label>			
			<?php echo form_dropdown('idSignatarioCombo',$SignatariosCombo,$selected = 1,'class="form-control" id="idSignatarioCombo"'); ?>			
			<label>Iniciales Analista(s)</label>			
			<?php echo form_input( $cInicialesAnalista) ?>;
		</div>
		
		<div class="form-group col-xs-2 col-sm-2">
			<label>Fecha de Terminaci&oacute;n</label>
			<?php echo form_input($dFechaFinal); ?>		
		</div>	
	
	</div>
	
	<hr size=10>
	
	<div class="row">
	
		<div class="form-group col-xs-6 col-sm-2">
			<label>Metal</label>
			<!--ANEXADO UN COMBOBOX PARA LOS ANALITOS DE ESE ESTUDIO  09/12/2016-->
			<?php echo form_dropdown('idMetalCombo',$MetalesCombo,$selected = 1,'class="form-control" id="idMetalCombo"'); ?>
			<!--<?php// echo form_input($cAnalito); ?>-->
			
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
			<label>Resultado (mg/kg)</label>
			<?php echo form_input($cResultado); ?>
		</div>
		
		<div class="form-group col-xs-6 col-sm-2">
			<label>LC (mg/kg)</label>
			<?php echo form_input($cLC); ?>
		</div>
		
		<div class="form-group col-xs-6 col-sm-2">
			<label>LMP (mg/kg)</label>
			<?php echo form_input($cLMP); ?>
		</div>
		
		<div class="form-group col-xs-6 col-sm-2">
			<label>Tecn&iacute;ca</label>
			<?php echo form_input($cTecnica); ?>
		</div>
		

		<div class="form-group col-xs-6 col-sm-2">	
			<label>Anexar Metal</label>
			<button type="text" class="btn btn-primary" id="BtnAgregaMetalTabla" >Agregar Metal</button>
		</div>
		<div class="form-group col-xs-3 col-sm-2">	
			<label><br/></label>
			<?php if ($accion == 'ALTA') { ?>		
				<button type="text" class="btn btn-primary" id="BtnAgregaTodosMetalesTabla" >Anexar Todos</button>
				
			<?php } ?>
			
		</div>
		
		<div class="form-group col-xs-3 col-sm-3">	
			<label><br/></label>
			<?php if ($accion == 'ALTA') { ?>			
				<button type="text" class="btn btn-primary" id="BtnAgregaMetalesAcreditadosTabla" >Solo Metales Acreditados</button>
			<?php } ?>
			
		</div>

	</div> <!-- fin del row -->
	
	<br>
	<hr size=10>

	<?php 
	if ($accion == 'EDICION') {
		echo $this->table->generate($resultados_det);
	}else {
		echo $this->table->generate($resultados);	
	}
	 ?>
	
	<hr size=10>
	
	
	<div class="row">
		<div class="form-group col-xs-9 col-sm-9">
			<label>Metodo Prueba</label>
			<?php echo form_input($cMetodoPrueba); ?>		
		</div>	
	</div>
	
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
			<div id="divBtnGrabaIDRMetales" <?php if($accion == 'ALTA'){ echo ' style="display:block ;"';} ?> >
				<button type="button" value="ALTA" class="btn btn-primary GrabaIDRMetales" id='idBtnGrabaIDRMetales'>Grabar IDR</button>
			</div>
			<?php } // fin de acion = alta?>
		</div>
		
		<!-- 2017-07-25 -->
		<div class="form-group col-xs-6 col-sm-2">
			<?php if($accion=='EDICION') { ?>	
				<div id='BtnActualizaIDRMetales'>
					<button type="button" class="btn btn-primary GrabaIDRMetales" value="EDICION" id='_idBtnActualizarIDRMetales'>Corregir IDR</button>				
				</div>
			<?php } // fin de acion = alta?>
		</div>
			
		<div class="form-group col-xs-6 col-sm-2">
		<!-- boton para regrsarnos -->
	        <button type="button" class="btn btn-default" onclick="history.back()" id="idBtnSalirEstudio">Regresar</button>     
		</div>		
		 
		 <div class="form-group col-xs-6 col-sm-3">
	        <div id="divBtnImprimeIDRMetales" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';} ?> >
	            <button type="button" class="btn btn-warning" formtarget="_blank" id="idBtnImprimeIDRAMetales"  >Imprimir IDR</button>
	        </div>	
		</div>
	</div> <!-- fin del row -->