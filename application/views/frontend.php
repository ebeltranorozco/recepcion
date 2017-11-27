<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//echo "Mi Primer uso de plantillas";

$this->load->view('plantillas/head');
$this->load->view('plantillas/header');
$this->load->view($contenido);
$this->load->view('plantillas/footer');
?>