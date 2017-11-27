<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set ( 'America/Mazatlan' );
ini_set('date.timezone','America/Mazatlan'); 
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>

	 <!-- CARGANDO JQUERY -->
	 <script src="<?php echo base_url('assets/jquery/jquery-3.1.0.min.js')?>"></script>  
	   
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	    
	 <!-- Bootstrap -->    
    <!--<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>-->
	  
	<link href="<?php echo base_url('assets/css/bootstrap.min.css');       ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">    
    <link href="<?php echo base_url('assets/css/font-awesome.min.css');    ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css');               ?>" rel="stylesheet">
	  
	  
    <!-- para las tablasd e los crud -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">  

    
  </head>
  <body>
  <?php var_dump($_SESSION);
  //echo "<br/>Consulta:[";
  //echo $this->db->last_query();
  ?>
  
  	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  	Launch demo modal
	</button>  
  
  </body>
</html>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        MODAL BODY...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

