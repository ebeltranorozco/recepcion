<?php //definicion de variables
$cPass_actual = array('name'=>'password_actual', 'class'=>"form-control", 'placeholder' => 'Contraseña Actual'); 
$cPass_nuevo = array('name'=>'password_nuevo', 'id'=>'password_nuevo','class'=>"form-control",'placeholder' => 'Escriba Contraseña Nueva');
$cPass_nuevo2 = array('name'=>'password_nuevo2', 'id'=>'password_nuevo2','class'=>"form-control", 'placeholder' => 'Reescriba Contraseña Nueva');

?>

<div class = "container" >

  <div class='col-md-3 center-block nofloat'>
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div>
      
      <div class="panel-body">
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
        <?php } ?>        
        
        
        <form method="POST" action="<?php echo base_url(); ?>actualiza_contrasena"  >

				<div class="row">            
            	<div class="form-group  col-md-12">
		            <label for="password1">Contraseña Actual</label>
	            	<?php echo form_input($cPass_actual); ?>
	          	</div>	
	        </div> <!--// fin del row-->
	        <div class="row">            
            	<div class="form-group col-md-12">
	        		<label for="password1">Contraseña Nueva</label>
	            	<?php echo form_input($cPass_nuevo); ?>        			
	        	</div>
	        </div> 
	        <div class="row">            
            	<div class="form-group col-md-12">
	        		<label for="password1">Contraseña Nueva</label>
	            	<?php echo form_input($cPass_nuevo2); ?>        			
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="form-group col-md-12">
	      		<button type="submit" class="btn btn-primary">Actualizar</button>  		
	      		</div>
	        </div> 
          
        </form>

      </div>
    </div>
  </div>
</div>

