<div class = "container" >

  <div class='col-md-4 center-block nofloat'>
    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div>
      
      <div class="panel-body">
        <?php if (validation_errors()) { ?> 
          <div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
        <?php } ?>        
        
        
        <form method="POST" action="<?php echo base_url(); ?>signin"  >
        <!--$attributes = array('class' => 'email', 'id' => 'myform');-->
        <!--<?php echo form_open(base_url('signin')); ?>-->


          <div class="form-group">
            <label for="email">Correo Electronico</label>
            <input type="email" name="correo"  class="form-control" id="correo" placeholder="Email" value="<?php if (isset($_GET['d'])) echo $_GET['d']; //echo set_value('correo'); ?>">

          <!--<div class="form-group">
            <label for="nombre">Nombre de Usuario</label>
            <input type="text" name="name" class="form-control" id="name" value="<?php echo set_value('name');?>">
          </div>-->

        
            <!--<?php 
              $email = array('type'=> 'email', 'name' => 'correo' , 'class' => "form-control", 'placeholder' => "Direccion de correo Valida"   );
            echo form_input($email,set_value('$correo')) ;?>-->
          </div>
          <div class="form-group">
            <label for="password1">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" id="pass" placeholder="Password">
          </div>
          
          
          <button type="submit" class="btn btn-default">Login</button>
        </form>

      </div>
    </div>
  </div>
</div>

