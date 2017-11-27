<!-- Definiendo Variables -->
<?php
  $cNombreUser 	= array( 'name'=>"nombre_usuario",'class'=>"form-control", 'placeholder'=>"Nombre y Apellido", 'value' =>set_value('nombre_usuario'));
  $cAliasUser 	= array( 'name'=>"alias_usuario",'class'=>"form-control", 'placeholder'=>"Alias de Usuario", 'value' =>set_value('alias_usuario'));
  $cCorreo 		= array( 'name'=>"correo",'class'=>"form-control", 'placeholder'=>"Email de Usuario Valido", 'value' =>set_value('correo'));
  $cTipoUser 	= array( 'name'=>"TipoUsuario",'class'=>"form-control", 'placeholder'=>"Tipo de Usuario Valido", 'value' =>set_value('TipoUsuario'));
  $cCargoUser 	= array( 'name'=>"CargoUsuario",'class'=>"form-control", 'placeholder'=>"Cargo de Usuario Valido", 'value' =>set_value('CargoUsuario'));
  
  $cInicialesUser 	= array( 'name'=>"InicialesUsuario",'class'=>"form-control", 'placeholder'=>"Iniciales del Nombre", 'value' =>set_value('InicialesUsuario'));
  $cTituloUser 	= array( 'name'=>"TituloUsuario",'class'=>"form-control", 'placeholder'=>"Titulo Universitario abreviado", 'value' =>set_value('TituloUsuario'));
  $cFechaNacimientoUser 	= array( 'name'=>"FechaNacUsuario",'type'=>'date2', 'class'=>"form-control", 'placeholder'=>"AAAA-MM-DD [Fecha de Nacimiento]", 'value' =>set_value('FechaNacUsuario'));

  
?>


<div class = "container" >
  <div class='col-md-6 center-block nofloat'>
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div>
      
      <div class="panel-body">
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
        <?php } ?> 
        
        
        
        <form method="POST" action="<?php echo base_url(); ?>alta_usuario"  >
          <div class="form-group">          
            <label for="nombre_user">Nombre Completo</label>
            <?php echo form_input($cNombreUser); ?>
            
          </div>
          <div class="form-group">          
            <label for="alias_usuario">Alias del usuario</label>
            <?php echo form_input($cAliasUser); ?>
            
          </div>
          <div class="form-group">          
            <label for="email">Correo Electronico</label>
            <?php echo form_input($cCorreo); ?>
            
          </div>

          <div class="form-group">
            <label for="password1">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" id="pass" placeholder="Escriba una Contraseña">
          </div>
          <div class="form-group">
            <label for="password2">Contraseña</label>
            <input type="password" name="contrasena2" class="form-control" id="pass2" placeholder="Volver a Escribir una Contraseña">
          </div>

          <div class="form-group">
            <label for="tipo_usuario">Tipo de Usuario<br /></label>
            <?php 
              $options = array('R' => 'Recien Ingreso' );
              $selected = array('R'=> 'Recien Ingreso');            
            echo form_dropdown('TipoUsuario',$options,$selected,'class="form_dropdown form-control"'); ?>
          </div>
          <div class="form-group">
            <label for="cargo">Cargo</label>
            <?php echo form_input($cCargoUser); ?>            
          </div>        
          
            
  		<div class="form-group">
            <label for="cargo">Iniciales</label>
            <?php echo form_input($cInicialesUser); ?>            
        </div>
          
        <div class="form-group">
            <label for="cargo">Titulo</label>
            <?php echo form_input($cTituloUser); ?>            
        </div>
          
        <div class="form-group">
            <label for="cargo">Fecha Nacimiento</label>
            <?php echo form_input($cFechaNacimientoUser); ?>            
        </div>
  
          
          <button type="submit" class="btn btn-default">Registrarse</button>
        </form>

      </div>
    </div>
  </div>
</div>

