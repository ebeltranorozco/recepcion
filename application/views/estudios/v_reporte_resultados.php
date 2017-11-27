<?php 
$dFechaIni = array('name'=>'dFechaInicial','id'=>'dFechaInicial','class'=>'form-control');
$dFechaFin = array('name'=>'dFechaFinal','id'=>'dFechaFinal','class'=>'form-control');
$cArea = array('name'=>'cboArea','class'=>'form-control');
$cMetodo = array('name'=>'cboMetodo','class'=>'form-control');
$cCte = array('name'=>'cboCliente','class'=>'form-control');
$AreaCombo = array('0'=>'Seleccion','Q'=>'Analisis Quimicos', 'M'=>'Analisis Microbiologico'); 

$lInforme = array(
        'name'          => 'informe',
        'id'            => 'informe',
        'value'         => 'Incluir',
        //'checked'       => TRUE,
        'style'         => 'margin:10px'
);
//var_dump($resultados);
//var_dump( $sql);
?>
<div class='col-md-4> <!-- center-block nofloat'>
  <div class = "container" >
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div><!--fin panel heading -->
      
      <div class="panel-body">
        
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?> </div>
          <?php } ?>
        
        <div class="row">
          <div class="col-md-4">
            <label for="Fecha1">Fecha Inicial</label>
            <?php echo form_input($dFechaIni); ?>            
          </div>
          <div class="col-md-4">
            <label for="Fecha1">Fecha Final</label>
            <?php echo form_input($dFechaFin); ?>            
          </div>

        </div> <!-- fin del row -->

        <div class="row">
          <div class="col-md-4">
            <label for="Fecha1">Area</label>           

            <?php echo form_dropdown('cboArea',$AreaCombo,$selected = 1,'class="form-control" id="cboArea"'); ?>
            
          </div>
          <div class="col-md-4">
            <label for="Fecha1">Cliente</label>
            <?php 
              $ClientesCombo['0']='Seleccione';
              echo form_dropdown('cboCliente',$ClientesCombo,$selected = 0,'class="form-control" id="cboCte"');
            ?>
          </div>

        </div> <!-- fin del row -->

        <div class="row">
          <div class="col-md-8">
            <label for="Fecha1">Metodo</label>
            
            <?php 
              $MetodosCombo['0']='Seleccione';
              echo form_dropdown('cboEstudios',$MetodosCombo,0,'class="form-control" id="cboMetodo"'); 
            ?>            
          </div>
        </div> 
        <div class="row">
          <div class="col-md-3">
            <label for="Fecha1">Informe de Efectividad</label>
            <?php echo form_checkbox($lInforme); ?>            
            <button type="botton" id="btnGeneraReporteIDRGral" class="form-control btn btn-primary" >Generar</button>
          </div>

        </div> <!-- fin del row -->
        <hr size="10">		
		
        <div class="row">
          <div class="col-md-12" id="divVisualizaReporteIDRGral">          	
          	<?php echo $this->table->generate($resultados); ?>            
          </div>
        </div> <!-- fin del row -->


      </div> <!-- fin del div panel body -->
    </div> <!-- fin del div panel primary -->

  </div>  <!-- fin del div container -->
</div>  <!-- fin del div col-md-4 prinicipal --> 
