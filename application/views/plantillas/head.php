<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set ( 'America/Mazatlan' );
ini_set('date.timezone','America/Mazatlan'); 
ini_set("session.cookie_lifetime","7200");//2017-07-11
ini_set("session.gc_maxlifetime","7200");//2017-07-11
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $title ?></title>

	 <!-- CARGANDO JQUERY -->
	 <!--
	 <script src="<?php echo base_url('assets/jquery/jquery-3.1.0.min.js')?>"></script>  
	 -->	 
	 
	 <!--<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>-->
   <script src="<?php echo base_url('assets/jquery/jquery-2.2.4.min.js');?>" crossorigin="anonymous"></script>
	 
	 
	 
		
	  
	<link href="<?php echo base_url('assets/css/bootstrap.min.css');       ?>" rel="stylesheet"> <!-- parece ser que este es el que marca error -->
    <link href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">    
    <link href="<?php echo base_url('assets/css/font-awesome.min.css');    ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css');               ?>" rel="stylesheet">		
		  
    <!-- para las tablasd e los crud -->
    <!--<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">  -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
   

    <?php 
      if (isset($css_files)) { // SE TRATA DE UN CRUD DE ALGUNA TABLA

        foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        
        <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
        <?php endforeach; }?>

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  