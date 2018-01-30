<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
}
</style>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <!--<span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>-->
      </button>
      <a class="navbar-brand" href="<?php echo base_url();?>">Recepción de Muestras</a>
    </div> <!--<div class="navbar-header">-->

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">      
      
      <ul class="nav navbar-nav">
        <?php if(!empty($_SESSION['is_logged_in'])) { ?>

          <!--  ***********************MENU DE CATALOGOS **********************-->
          <!--<li ><a href="#">Link <span class="sr-only">(current)</span></a></li>-->  
		  
          <?php if (true) { ?>
          <li class="dropdown">  <!-- Menu Catalogo -->
            <a href="#"  <?php if ($menu_activo=='catalogos') {echo "class='active'";} ?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catalogos <span class="caret" ></span></a>
            <ul class="dropdown-menu">

              <?php if ( $this->utilerias->permiso( 'CLIENTES','ACCESO' )  ) { ?>
                <li><a href="<?php echo base_url('clientes_crud'); ?>">Clientes</a></li>
              <?php } ?>

              <?php if ($this->utilerias->permiso( 'ESTUDIOS','ACCESO' )  ) { ?>
                <li><a href="<?php echo base_url('estudios_crud'); ?>">Ensayos</a></li>
              <?php } ?>				
            
              <?php if ($this->utilerias->permiso( 'USUARIOS','ACCESO' ) ) { ?>
                <li><a href="<?php echo base_url('usuarios_crud'); ?>">Usuarios</a></li>
              <?php } ?>				
             
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li><a href="<?php echo base_url('permisos_usuario'); ?>">Permisos</a></li>
              <?php } ?>   
		      

            </ul>
          </li>
          <?php } ?>

          <!--  ***********************MENU DE MOVIMIENTOS DE SERVICIO **********************-->
          <?php if (true) { ?>
          <li class="dropdown">  <!-- Menu movimiento de servicios -->
            <a href="#"  <?php if ($menu_activo=='movimientos') {echo "class='active'";} ?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Movimientos de Servicios <span class="caret"></span></a>   
                                
            <ul class="dropdown-menu">
              <?php if ($this->utilerias->permiso( 'ESTUDIOS','ACCESO' ) or $this->utilerias->permiso( 'INFORMES','ACCESO' ) ) { ?>
                <li><a href="<?php echo base_url('capturar_estudio')?>">Alta de Ensayos</a></li>
              <?php } ?>
              
                <li role="separator" class="divider"></li>
                <?php if ($this->utilerias->permiso( 'ESTUDIOS','ACCESO' ) ) { ?>
                	<li><a href="<?php echo base_url('crud_estudio')?>">Control de Ensayos</a></li>
              	<?php } ?>
         
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li role="separator" class="divider"></li>
                <li><a href="<?php echo base_url('reporte_resultados')?>">Informe de Resultados</a></li>
              <?php } ?>
              
            </ul>
          </li>
          <?php } ?>            
          


      <?php }?> <!-- fin de is loggein -->

      </ul>

      <!--
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>-->
      
      <!--  ***********************MENU PERFIL DE USUARIO **********************-->


      <ul class="nav navbar-nav navbar-right">
        <?php if(!empty($_SESSION['is_logged_in'])) { ?>
          <!-- si hay una secion iniciada -->
          <!--<li><a href="#">Link</a></li>-->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo 'Perfil('.$_SESSION['user_nombre'].')';?> <span class="caret"></span></a>
            <ul class="dropdown-menu">
            <li><a href="#">Perfil</a></li>              
              <li><a href="<?php echo base_url('password_change'); ?>">Contraseña</a></li>              
              <li role="separator" class="divider"></li>
              <li><a href="<?php echo base_url(); ?>logout">Cerrar Sesion</a></li>
              
              <li role="separator" class="divider"></li>
              
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li><a id="idReiniciaEnsayos" href="<?php echo base_url('reiniciar_ensayos'); ?>">Reiniciar Ensayos</a></li>
              <?php } ?>
              
              
              
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li><a id="idReiniciaFolios" href="<?php echo base_url('reiniciar_folios'); ?>">Reiniciar Folios</a></li>
              <?php } ?>
              
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li><a id="idReiniciaFoliosEnsayos" href="<?php echo base_url('reiniciar_folios_ensayos'); ?>">Reiniciar Folios de los Ensayos</a></li>
              <?php } ?>  
              
              <?php if ($_SESSION['user_tipo']=='A') { ?>
                <li><a id="idReiniciaIDRS" href="<?php echo base_url('reiniciar_idrs'); ?>">Reiniciar Todos los IDR</a></li>
              <?php } ?>  
              
              
            </ul>
          </li>
        
        <!--  SI NO HAY Sesion INICIADA -->
        <?php } else {?>
          <li <?php if ($menu_activo=='registro') {echo "class='active'";} ?>><a href="<?php echo base_url() ?>registro">Registro</a></li>
          <li <?php if ($menu_activo=='login') {echo "class='active'";}   ?> ><a href="<?php echo base_url('login'); ?>">Login</a></li>
        <?php }  ?>
      </ul> <!--class="nav navbar-nav navbar-right">-->


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<style type="text/css">
  .centrado-porcentual2{  	
  	position: fixed;
    left: 40%;
    top: 40%;
    width: 100px; 
	height:100px;
	margin-left: -80px; 
	margin-top: -80px;    
    z-index: 9999;
    /*background: url('images/pageLoader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;*/
  }
  .centrado-porcentual4 {
  	position: absolute;
	top: 50%;
	left: 50%;
	width: 160px; 
	height:160px; 
	margin-left: -80px; 
	margin-top: -80px; 
  }
  .centrado-porcentual {
  	position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);     
  }
  .centrado-porcentual5 {
  	position:fixed;
width:300px;
height:200px;
left:50%;
top:50%;
margin-left:-150px;
margin-top:-100px;
  }
  
  .centrado-porcentual3 {
  	width: 580px;
	height: 390px;
	margin-top: -195px;
	margin-left: -290px;
	left: 50%;
	top: 50%;
	position: absolute;
  }
  
  .centrado-porcentual6 {
    width: 150px;
   	height: 150px;
   	position: fixed;
   	background: url('images/pageLoader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
	}
	.centrado-porcentual7{
	 width: 150px;
     height: 150px;
     position: absolute;
     top: 50%;
     left: 50%;
     margin-top: -75px;
     margin-left: -75px;
     background-color: #69a6e5;
	}
  }
</style>

<div class="container">
<div id="msg_alert_full"></div> <!-- mensajes globales -->
  <?php // para que ponga los mensajes cuando los haya..!
    if ($this->session->flashdata('mensaje_error') !=NULL) {
      echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('mensaje_error') . '</div>';
      $this->session->flashdata('mensaje_error') ==NULL;
    }
    if ($this->session->flashdata('mensaje_sucess') !=NULL) {
      echo '<div class="alert alert-success" role="alert">' . $this->session->flashdata('mensaje_sucess') . '</div>';
      $this->session->flashdata('mensaje_sucess') ==NULL;
    }
  ?>  
  
  <div class="centrado-porcentual2" id="ajax-loading" style="display:none" ><img id="imagen_cargando" src="http://innovaciones.com.py/images/cargando.gif" /></div> <!-- mensaje de carga del ajax -->  
</div>