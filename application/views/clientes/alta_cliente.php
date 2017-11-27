<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- VISTA ALTA DEL CLIENTE -->
<div class = "container" >
  <div class='col-md-10 center-block nofloat'>
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div>
      
      <div class="panel-body">
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
        <?php } ?>  
        
        <!-- COMENZANDO A GENERAR EN ARRAYS LOS NOMBRE DE LOS CAMPOS -->
        <?php
        	$cNombre = array(
        		'name'	=> 'nombre_cliente',
        		'id'	=> 'nombre_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('nombre_cliente'));
        	$cDomicilio = array(
        		'name'	=> 'direccion_cliente',
        		'id'	=> 'direccion_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('direccion_cliente'));
        	$cCiudad = array(
        		'name'	=> 'ciudad_cliente',
        		'id'	=> 'ciudad_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('ciudad_cliente'));
        	$cEstado = array(
        		'name'	=> 'estado_cliente',
        		'id'	=> 'estado_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('estado_cliente'));
        	$cRfc = array(
        		'name'	=> 'rfc_cliente',
        		'id'	=> 'rfc_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('rfc_cliente'));
        	$cTelefono = array(
        		'name'	=> 'telefono_cliente',
        		'id'	=> 'telefono_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('telefono_cliente'));        	
        	$cEmail = array(
        		'name'	=> 'email_cliente',
        		'id'	=> 'email_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('email_cliente'));
        	$cContacto = array(
        		'name'	=> 'contacto_cliente',
        		'id'	=> 'contacto_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('contacto_cliente'));
        	$cEmailAlterno = array(
        		'name'	=> 'email_alterno_cliente',
        		'id'	=> 'email_alterno_cliente',
        		'class'	=> 'form-control',
        		'value'	=> set_value('email_alterno_cliente'));
        	
        ?>
        
        <form method="POST" action="<?php echo base_url('add_cliente'); ?>">


			<div class="row">

				<div class="form-group col-xs-6 col-sm-3 col-md-6"> 					
					<?php echo form_label('Nombre:'); ?>
					<?php echo form_input($cNombre); ?>
				</div>

				<div class="form-group col-xs-6 col-sm-3 col-md-6">					
					<?php echo form_label('Domicilio:'); ?>
					<?php echo form_input($cDomicilio); ?>
				</div>

	        </div><!-- fin del row -->

	        <div class="row">

	        	<div class="form-group col-xs-6 col-sm-3 col-md-6">		        	
		           	<?php echo form_label('Ciudad:'); ?>
					<?php echo form_input($cCiudad); ?>
		        </div>

		        <div class="form-group col-xs-6 col-sm-3 col-md-6">			       	
		           	<?php echo form_label('Estado:'); ?>
					<?php echo form_input($cEstado); ?>
		        </div>	        	
	        </div> <!-- fin del row -->

	        <div class="row">	        

		        <div class="form-group col-xs-6 col-sm-3 col-md-6">		            
		            <?php echo form_label('RFC:'); ?>
					<?php echo form_input($cRfc); ?>
		        </div>

		        <div class="form-group col-xs-6 col-sm-3 col-md-6">		            
		            <?php echo form_label('Telefono:'); ?>
					<?php echo form_input($cTelefono); ?>
		        </div>        

		    </div>

		    <div class="row">

		          <div class="form-group col-xs-6 col-sm-3 col-md-6">		            
		            <?php echo form_label('Correo:'); ?>
					<?php echo form_input($cEmail); ?>
		          </div>

		    	<div class="form-group col-xs-6 col-sm-3 col-md-6">
		            
		            <?php echo form_label('Contacto:'); ?>
					<?php echo form_input($cContacto); ?>
		        </div>
		    </div><!-- fin del row -->

		    <!--COMENZAREMOS A DIBUJAR UNA TABLA -->

          <button type="submit" class="btn btn-default">Registrarse</button>
        </form>

      </div>
    </div>
  </div>
</div>

 