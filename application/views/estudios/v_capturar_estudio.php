<?php
//variables de la parte primera del formulario
$cFolio = array('name'=>'folio_solicitud','id'=>'folio_solicitud','class'=>'form-control', 'value'=>set_value('folio_solicitud'));
$dFecha = array('name'=>'fecha_solicitud','id'=>'fecha_solicitud','class'=>'form-control' ,'value' => set_value('fecha_solicitud'));
$cIdCte = array('name'=>'id_cliente','id'=>'id_cliente','class'=>'form-control','value'=>set_value('id_cliente'),'readOnly'=>"true");
$cNomCte= array('name'=>'nombre_cte','id'=>'nombre_cte','class'=>'form-control' ,'value' => set_value('nombre_cte'));
$cDirCte=array('name'=>'direccion_cte','id'=>'direccion_cte','class'=>'form-control' ,'value' => set_value('direccion_cte'),'placeholder' =>'dirección del Cliente');
$cTel = array('name'=>'telefono_cte','id'=>'telefono_cte','class'=>'form-control' ,'value' => set_value('telefono_cte'),'placeholder'=>'teléfono del cliente');
$cRfc = array('name'=>'rfc_cte','id'=>'rfc_cte','class'=>'form-control' ,'value' => set_value('rfc_cte'),'placeholder'=>'rfc del cliente');
$cEmail = array('name'=>'email_cte','id'=>'email_cte','class'=>'form-control' ,'value'=> set_value('email_cte'),'placeholder'=>'correo electronico');
$cCont = array('name'=>'contacto_cte','id'=>'contacto_cte','class'=>'form-control' ,'value' => set_value('contacto_cte'),'placeholder'=>'datos de contacto');
$cObsRecepcion = array('name'=>'obs_recepcion','id'=>'obs_recepcion','class'=>'form-control' ,'value' => set_value('obs_recepcion'),'maxlength'=>'230'); // marzo 2017
$cToma_Muestra = array('name'=>'toma_muestra','id'=>'toma_muestra','class'=>'form-control' ,'value' => set_value('toma_muestra'),'maxlength'=>'99','placeholder'=>'nombre de quien tomo la muestra'); // 23/05/2017

//2017-11-27
$dFechaHoraTomaMuestra = array('name'=>'fecha_toma_muestra','id'=>'fecha_toma_muestra','class'=>'form-control' ,'value' => set_value('fecha_toma_muestra'),'placeholder'=>'fecha y hora en que se tomo la muestra');

$nCosto_Servicio = array('name'=>'costo_servicio','id'=>'costo_servicio','class'=>'form-control' ,'value' => set_value('costo_servicio'),'type'=>"number", 'step'=>"any");
$nCosto_Envio = array('name'=>'costo_envio','id'=>'costo_envio','class'=>'form-control' ,'value' => set_value('costo_envio'),'type'=>"number", 'step'=>"any");

// 15/05/2017 --> FOLIO COTIZACION
$nFolioCotizacion =array('name'=>'folio_cotizacion','id'=>'folio_cotizacion','class'=>'form-control' ,'value' => set_value('folio_cotizacion'));

//14/06/2017 --> variables de reciente ingreso por separacion de descripcion de muestra
$cDestinoMuestra =array('name'=>'destino_muestra','id'=>'destino_muestra','class'=>'form-control' ,'value' => set_value('destino_muestra'),'maxlength'=>'30');
$cCondicionesMuestra =array('name'=>'condiciones_muestra','id'=>'condiciones_muestra','class'=>'form-control' ,'value' => set_value('condiciones_muestra'),'maxlength'=>'230');

//2017-08-03 --> Incorporacion de campo para saber si se va generar idr
$lGeneraFolioIDR = array( 'name'=>'idGeneraFolioIDR','id'=>'idGeneraFolioIDR','class'=>'form-control' ,'value' => set_value('idGeneraFolioIDR'),'style'=>'margin:2px 0 0;border-color:none;box-shadow:none;');
$lMantieneInfo   = array( 'name'=>'idMatieneInfo','id'=>'idMatieneInfo','class'=>'form-control' ,'value' => set_value('idMatieneInfo'),'style'=>'margin:2px 0 0;border-color:none;box-shadow:none;');
//$lGeneraFolioIDR = array( 'name'=>'idGeneraFolioIDR','id'=>'idGeneraFolioIDR','value' => set_value('idGeneraFolioIDR'));

if (empty($cFolio['value'])) { $cFolio['value'] = $last_id; }
$cFolio['readonly'] = 'false';

//variables del IDR
	$cNombre_IDR = array('name'=>'nombre_idr_cliente','id'=>'nombre_idr_cliente','class'=>"form-control",'value'=>set_value('nombre_idr_cliente'));// 16/05/2017 AGREGAR CAMPO  ID MUESTRA
    $cDomicilio_IDR   = array('name'=>'domicilio_idr_cliente','id'=>'domicilio_idr_cliente','class'=>"form-control",'value'=>set_value('domicilio_idr_cliente'));
    $cRFC_IDR   = array('name'=>'rfc_idr_cliente','id'=>'rfc_idr_cliente','class'=>"form-control",'value'=>set_value('rfc_idr_cliente'));
    $cContacto_IDR   = array('name'=>'contacto_idr_cliente','id'=>'contacto_idr_cliente','class'=>"form-control",'value'=>set_value('contacto_idr_cliente'));
    // anexadi campos a la base de datos..!  25/05/2017

//2017-08-11 --> Campo oculto para saber si es una alta o edicion
//$cCpoAlta = array( 'name'=>'idAccion','id'=>'idAccion','class'=>'form_control','value'=>set_value('idAccion'));

//2017-08-14 --> variable para justificar la modificacion
$cJustificacionEdicion = array( 'name'=> 'idJustificacionEdicion','id'=>'idJustificacionEdicion','class'=>'form-control','value'=>set_value('idJustificacionEdicion'),'rows'=>4);
$cIdRecepcionMuestra = array( 'id'=>'idRecepcionMuestra','class'=>'form-control','value'=>set_value('idRecepcionMuestra'),'style'=>'display:none;');

if ($accion == 'ALTA') {  
  if (empty($dFecha['value'])) { $dFecha['value'] = date('Y-m-d H:i:s'); }
}else { // hay q cargar los datos.

  $dFecha['value'] = $estudio[0]->FECHA_RECEPCION;
  $cIdCte['value'] = $estudio[0]->ID_CLIENTE;
  $cNomCte['value'] = $estudio[0]->NOMBRE_CLIENTE;
  $cDirCte['value'] = $estudio[0]->DOMICILIO_CLIENTE;
  $cTel['value'] = $estudio[0]->TELEFONO_CLIENTE;
  $cRfc['value'] = $estudio[0]->RFC_CLIENTE;
  $cEmail['value'] = $estudio[0]->EMAIL_CLIENTE;
  $cCont['value'] = $estudio[0]->CONTACTO_CLIENTE;
  $nFolioCotizacion['value'] = $estudio[0]->FOLIO_COTIZACION;
  $cToma_Muestra['value'] = $estudio[0]->TOMO_MUESTRA; // 23/05/2017
  //14/06/2017
  $cDestinoMuestra['value'] = $estudio[0]->DESTINO_MUESTRA;
  $cCondicionesMuestra['value'] = $estudio[0]->CONDICIONES_MUESTRA;
  $nCosto_Servicio['value'] = $estudio[0]->OTROS_SERVICIO;
  //$nCosto_Servicio['value'] = 1500.36;
  //$lGeneraFolioIDR['checked'] = FALSE;
  $lGeneraFolioIDR['checked'] = !$estudio[0]->GENERAR_IDR_MUESTRA;
  //if ($estudio[0]->GENERAR_IDR_MUESTRA == '0') { $lGeneraFolioIDR['checked'] = TRUE; } 
  
  $lGeneraFolioIDR['value'] = $estudio[0]->GENERAR_IDR_MUESTRA;
  $cObsRecepcion['value'] = $estudio[0]->OBSERVACIONES_RECEPCION;
  
  $cIdRecepcionMuestra['value'] = $estudio[0]->ID_RECEPCION_MUESTRA;
  
  //$lGeneraFolioIDR['value'] = $estudio[0]->
  //var_dump($estudio);
  //exit();
} // fin de accion
//var_dump($estudio);
//echo '<br><br>';
//var_dump($sql);
//echo '<br><br>';
//var_dump( $clientes->result_array());
//echo '<br><br>';
?>

<!--/**********ESTA ES LA PARTE INICIAL DE CAPTURA DE CAMPOS******************************************/-->
<!-- **************************************************************************************************-->
<div class="col-md-4"> <!-- center-block nofloat -->
  <div class = "container" >
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title" id='panel-encabezado'><?php echo $panel_title; ?></h3>
      </div> <!--fin panel heading -->
      
      <div class="panel-body">
        
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?> </div>
        <?php } ?>
        
        <form method="POST" > <!--action="<?php echo base_url('add_estudio'); ?>"  >   -->
        
        <div class="row">
            
            <div class="form-group col-xs-6 col-sm-3">
              <?php echo form_label('Folio'); ?>
              <?php echo form_input($cFolio); ?>
              <?php echo form_input($cIdRecepcionMuestra); ?>
            </div>
            
            <div class="form-group col-xs-6 col-sm-3">
              <?php echo form_label('Muestra(s) para Prueba(s) preliminar'); ?>              
              <?php echo form_checkbox($lGeneraFolioIDR); ?>          
            </div>

            

            <div class="form-group col-xs-6 col-sm-2">          
              <label for="fecha_solicitud">Fecha / Hora</label>
              <?php echo form_input($dFecha); ?>
            </div>
            <!-- 15/05/2017 -->
            <div class="form-group col-xs-2 col-sm-2">  
            	<?php if ( $this->utilerias->permiso( 'COTIZACIONES','ACCESO' )  ) { ?>        
              		<label for="folio_cotizazcion">Folio de la Cotizacion:</label>
              		<?php echo form_input($nFolioCotizacion); ?>
              		<!--<button type="button" id="idBtnPrueba" >Prueba</button>-->
              	<?php } ?>
              
            </div>
            
            <div class="form-group col-xs-1 col-sm-1">          
            	<?php if ( $this->utilerias->permiso( 'COTIZACIONES','ACCESO' )  ) { ?>
              		<br>
              		<button type="button" name="searchbuttoncot" id="searchbuttoncot" class="btn btn-default" >Buscar Cotización</button>
            	<?php } ?>
            </div>
            <!-- termina lo agregado al 15/05/2017 -->
            
          </div> <!-- fin del row -->
          <br>

          <div class="row">

            <div class="form-group col-xs-6 col-sm-1">
              <?php echo form_label('Id'); ?>
              <?php echo form_input($cIdCte); ?>
            </div>

            <div class="form-group col-xs-6 col-sm-5">

              <label for="nombre_cte2">Nombre de Cliente</label>              
              <?php echo form_input($cNomCte);?>
            </div>
            
            <div class="form-group col-xs-6 col-sm-6">          
              <br>
              <?php if ($accion == 'ALTA' ) { ?>
			  	<button type="button" name="searchbuttoncte" id="searchbuttoncte" class="btn btn-default" >Buscar Cliente</button>	
			  <?php } ?>
              
            </div>
          
          </div> <!-- fin del row -->
          
          <div class="row">
            <div id="response_search"></div>    <!-- aqui es donde aparece los datos de la busqueda del cliente -->        
          </div><!-- fin del row -->

          <div class="row"><!-- fin del row -->

            <div class="form-group col-xs-6 col-sm-6">          
              <label for="direccion_cte">Dirección</label>              
              <?php echo form_input($cDirCte); ?>
            </div>

            <div class="form-group col-xs-6 col-sm-3">          
              <label for="rfc_cte">RFC</label>              
              <?php echo form_input($cRfc); ?>
            </div>

          </div> <!-- fin del row -->   
          
          <div class="row">
            
            <div class="form-group col-xs-6 col-sm-3">
              <label for="telefono_cte">Telefóno del Cliente</label>              
              <?php echo form_input($cTel); ?>
          
            </div>

            <div class="form-group col-xs-6 col-sm-3">          
              <label for="direccion_cte">Contacto</label>              
              <?php echo form_input($cCont); ?>
            </div>

            <div class="form-group col-xs-6 col-sm-3">          
              <label for="rfc_cte">EMAIL</label>              
              <?php echo form_input($cEmail); ?>
            </div>

          </div> <!-- fin del row -->
          
          <div class="row">
            
            <div class="form-group col-xs-6 col-sm-6">
              <label for="telefono_cte">Responsable de la toma de muestra:</label>              
              <?php echo form_input($cToma_Muestra); ?>          
            </div>
            
            <div class="form-group col-xs-3 col-sm-3">
              <label for="fecha_hora_toma_muestra">Fecha y hora:</label>              
              <?php echo form_input($dFechaHoraTomaMuestra ); ?>          
            </div>
            
            
            <div class="form-group col-xs-3 col-sm-3">          
            	<?php if ( $this->utilerias->permiso( 'CLIENTES','REGISTRAR' )  ) { ?>
            		<br>
            		<button type="button" name="button_datos_idr" id="button_datos_idr" class="btn btn-default" data-toggle="modal" data-target="#myModal_IDR">Datos IDR</button>
            	<?php } ?>
            </div>

          </div> <!-- fin del row -->       
          
          <hr size="10" />
          
          <?php if ($accion == 'ALTA' ) {   ?>
          	<div class="form-group col-sm-3">
            	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" id="idBtnAgregarEstudio" name="idBtnAgregarEstudio">Agregar Ensayo(s)</button>
            </div>	            
            <div class="form-group col-xs-3 col-sm-1">
              <?php echo form_checkbox($lMantieneInfo); ?>              
            </div>
            <div  class="form-group col-xs-6 col-sm-3">
              <?php echo form_label('Mantener Detalle de la Muestra'); ?>
            </div>
            
            
            
          <?php } ?> <!--// fin de accion = alta-->
          
          <!-- AQUI ES DONDE SE GENERA LA TABLA DE LAS METODOLOGIAS -->
          <?php echo $this->table->generate($clientes); ?>
          
          <!-- ANEXADO 15/05/2017 -->
          <div class="row"> 
	          <div class="form-group col-xs-7 col-sm-7"></div>
	          	<div class="form-group col-xs-3 col-sm-3"><label  class=" control-label" for="Lote">Otros Servicios:</label></div>
	          	
	          	<div class="form-group col-xs-2 col-sm-2">          		
	          			<?php echo form_input($nCosto_Servicio); ?>
	          	</div>
          	</div>
         
         	<!-- se elimino el costo de paqueteria       	-->
            
          
          <hr size="10"  />
           
			<div class="row"> <!--anexado mar 2017 campo obs -->
              <div class="form-group"> <!-- 14/06/2017 -->
                    <label  class="col-sm-9 control-label" for="Lote">Destino de la Muestra:</label>                  
                    <div class="col-sm-9">                        
                        <?php echo form_input($cDestinoMuestra); ?>
                    </div>
                    
                    <label  class="col-sm-9 control-label" for="Lote">Condiciones de Recepción de la Muestra:</label>                  
                    <div class="col-sm-9">                        
                        <?php echo form_input($cCondicionesMuestra); ?>
                    </div>                    
              </div>
            </div><!-- fin del row --> <!-- termina lo anexado marzo 2017 -->

          
          <div class="row"> <!--anexado mar 2017 campo obs -->
              <div class="form-group">
                    <label  class="col-sm-9 control-label" for="Lote">Observaciones:</label>                  
                    <div class="col-sm-9">                        
                        <?php echo form_input($cObsRecepcion); ?>
                    </div>
              </div>
            </div><!-- fin del row --> <!-- termina lo anexado marzo 2017 -->
            
            <!-- 2017-08-03 -->
            
	        <div class="row">
				<!--<label  class="col-sm-9 control-label" for="Lote">Muestra(s) para prueba(s) preeliminares:</label>-->
                <div class="col-sm-9">                        
                    <!--<?php echo form_checkbox($lGeneraFolioIDR); ?>-->
                	<!--<label><input class="form_control" type="checkbox" name="idParaPruebaPreeliminar" id="idParaPruebaPreeliminar"> Muestra(s) para prueba(s) preeliminares:</label>-->
            	</div>
			</div>
          
	        <div class="row">
	        	<?php if ($accion == 'EDITAR') { ?>
					<label  class="col-sm-9 control-label" for="Lote">Justificacion Edición:</label>
    	            <div class="col-sm-9">                        
        	            <?php echo form_textarea($cJustificacionEdicion ); ?>                	
            		</div>
            	<?php } ?>
			</div>          
          
          <hr size="10"  />
          
          <div class="form-group col-xs-6 col-sm-3" id="idDivGeneraSolicitud">
          <!-- hay que meter validacion para ver si es una alta o edicion -->
          <?php   
            if ( $accion== 'EDITAR' and $estudio[0]->STATUS_MUESTRA == 'A' ) { ?>
              <button type="button" class="btn btn-primary" id="idBtnActualizaEstudio">Actualizar Solicitud</button>
            <?php } else {              
              if ($this->utilerias->permiso( 'ESTUDIOS','REGISTRAR' ) and  $accion== 'ALTA') {  ?>
                    <button type="button" class="btn btn-primary" id="idBtnAltaEstudio">Generar Solicitud</button>  
              <?php } }?>
            <!-- boton para regrsarnos -->
            <button type="button" class="btn btn-default" id="idBtnSalirEstudio">Regresar</button>
          </div> <!--// fion del div del group class="form-group-->
          
          <div class="form-group col-xs-6 col-sm-3">
            <!--necesitamos un boton que aparesca cuando ya se grabo todo-->
            <div id="divBtnImprimir" <?php if($accion == 'ALTA'){ echo ' style="display: none;"';}?>>
            	<?php if ( $this->utilerias->permiso( 'ESTUDIOS','IMPRIMIR' )  ) { ?>  
                	<button type="button" class="btn btn-warning" formtarget="_blank" id="idBtnImprimir"  >Solicitud</button>
                	<button type="button" class="btn btn-warning" formtarget="_blank" id="idBtnImprimirCte">Entrega</button>
                <?php } ?>
              
            </div>
          </div><!--  // fion del div del group-->
          
        
        </form>
        
      </div> <!-- anexado haber si ayuda con el panel -->      
    </div> <!--fin del panel primary -->
  </div> <!-- fin del container  -->
</div> <!--  fin del col-md-4 -->



<!-- AGREGANDO ESTE FORMULARIO MODAL EL 23/05/2017 -->
<!-- *****************************************************************-->
<!--/*****************************************************************/-->
    <div class="modal fade" id="myModal_IDR" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">DATOS IDR</h4>            
          </div> <!--fin de modal header -->

          <div class="modal-body">

            <!-- vamos agregar las alertas -->
            <div id="msg_alerta_modal_IDR"></div> 
           

            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Nombre para el IDR:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cNombre_IDR); ?>
                    </div>
              </div>
            </div>
            
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Domicilio para el IDR:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cDomicilio_IDR); ?>
                    </div>
              </div>
            </div>

            <div class="row">              
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">RFC para el IDR</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cRFC_IDR); ?>
                    </div>
              </div> 
            </div>

            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="Lote">Contacto para el IDR</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cContacto_IDR); ?>
                    </div>
              </div>
            </div>     
                    
              


          </div> <!--fin de modal body -->
          

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="idBtnCloseModalIDR">Limpiar Formulario</button>
            <!--<button type="button" class="btn btn-primary">Anexar</button>-->
            <button type="button" class="btn btn-primary" data-dismiss="modal" name="btnAddDatosIDR" id="btnAddDatosIDR">Aceptar</button>
          </div> <!--fin de modal footer -->

          
        
        </div> <!--fin de modal content -->
      
      </div> <!--fin de modal dialog -->
    </div> <!-- fin de modal fade (dice q esta de mas esta etiqueta-->



<!-- *****************************************************************-->
<!--/*****************************************************************/-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelMetodologia">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabelMetodologia">Agregar Ensayo(s)</h4>            
          </div> <!--fin de modal header -->

          <div class="modal-body">

            <!-- vamos agregar las alertas -->
            <div id="msg_alerta_modal"></div> 
            <!-- LIMPIAR LOS CAMPOS -->

              
            <?php 
              
              //variables del detallado del estudio
              $nIdMuestra = array('name'=>'id_muestra','id'=>'id_muestra','class'=>"form-control",'readonly'=>'true','value'=>set_value('id_muestra'));// 16/05/2017 AGREGAR CAMPO  ID MUESTRA
              $nIdCte   = array('name'=>'id_cte','id'=>'id_cte','class'=>"form-control",'value'=>set_value('id_cte'),'maxlength'=>'10');
              $cDesc   = array('name'=>'desc_muestra','id'=>'desc_muestra','class'=>"form-control",'value'=>set_value('desc_muestra'),'maxlength'=>'99');
              $cLote   = array('name'=>'lote','id'=>'lote','class'=>"form-control",'value'=>set_value('lote'),'maxlength'=>'50');
              //$nMues   = array('name'=>'no_muestra','id'=>'no_muestra','class'=>"form-control",'value'=>'');
              //$cEstu   = array('name'=>'cve_estudio','id'=>'cve_estudio','class'=>"form-control", 'value'=>set_value('cve_estudio'));
              $nPrec   = array('name'=>'precio','id'=>'precio','class'=>"form-control",'value'=>set_value('precio'));
              $nImpo   = array('name'=>'importe','id'=>'importe','class'=>"form-control",'value'=>set_value('importe'));
              $cMatriz = array('name'=>'matriz_producto','id'=>'matriz_producto','class'=>"form-control",'value'=>set_value('matriz_producto'));
              
              //14/06/2017
              $cTipoMuestra =array('name'=>'tipo_muestra','id'=>'tipo_muestra','class'=>'form-control' ,'value' => set_value('tipo_muestra'),'maxlength'=>'40');
              $cPesoVol =array('name'=>'peso_volumen_muestra','id'=>'peso_volumen_muestra','class'=>'form-control' ,'value' => set_value('peso_volumen_muestra'),'maxlength'=>'20');
              $cTemp = array('name'=>'temperatura_muestra','id'=>'temperatura_muestra','class'=>'form-control' ,'value' => set_value('temperatura_muestra'),'maxlength'=>'10');

              
              
            ?>

            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Id Muestra</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($nIdMuestra); ?>
                    </div>
              </div>
            </div>
            
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Id Cliente</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($nIdCte); ?>
                    </div>
              </div>
            </div>

            <div class="row">              
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">Tipo de Muestra:</label>                         <div class="col-sm-10">                        
                        <?php echo form_input($cTipoMuestra); ?>
                    </div>
              </div> 
            </div>
            
            <div class="row">              
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">Peso / Volumen:</label>                         <div class="col-sm-10">                        
                        <?php echo form_input($cPesoVol); ?>
                    </div>
              </div> 
            </div>
            
            <div class="row">              
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">Temperatura:</label>                            <div class="col-sm-10">                        
                        <?php echo form_input($cTemp); ?>
                    </div>
              </div> 
            </div>


            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="Lote">Lote / Origen:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cLote); ?>
                    </div>
              </div>
            </div>     
            
           <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">Metodologia:</label>                    
                    <div class="col-sm-10"> 
                      <?php 
                        $MetodosCombo['0']='Seleccione';
                        echo form_dropdown('cbo_estudio',$MetodosCombo,$selected = 0,'class="form-control" id="cbo_estudio"');
                      ?>                         
                        <!--
                        <div class="selector-estudio" name="cbo_estudio" id="cbo_estudio" ><select class="selectpicker form-control" data-size="10" data-width="fit"></select></div>
                        -->
                    </div>
              </div>   
            </div>
            
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="desc_muestra">Ensayo:</label>                    
                    <div class="col-sm-10"> 
                      <?php 
                        $EnsayosCombo['0']='Seleccione';
                        echo form_dropdown('cbo_ensayo',$EnsayosCombo,$selected = 0,'class="form-control" id="cbo_ensayo"');
                      ?>
                    </div>
              </div>   
            </div>                


          </div> <!--fin de modal body -->
          

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="idBtnCloseModal">Close</button>
            <!--<button type="button" class="btn btn-primary">Anexar</button>-->
            <button type="button" class="btn btn-primary"  name="btnAddEstudioTabla" id="btnAddEstudioTabla">Aceptar</button>
          </div> <!--fin de modal footer -->

          
        
        </div> <!--fin de modal content -->
      
      </div> <!--fin de modal dialog -->
    </div> <!-- fin de modal fade (dice q esta de mas esta etiqueta-->
<!-- ************************************************************************************************-->  
