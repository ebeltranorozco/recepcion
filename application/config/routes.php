<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Auth
$route['login'] = '/auth/login';
$route['signin'] = '/auth/signin';
$route['registro'] = '/auth/registro';
$route['logout'] = '/auth/logout';
$route['alta_usuario'] = '/auth/alta_usuario';
//$route['listar_usuarios'] = 'auth/listar_usuarios';  // se desactivo por el nuevo crud
$route['password_change'] = 'auth/password_change'; // formulario cambiar de contraseña
$route['actualiza_contrasena'] = 'auth/actualiza_contrasena';

// menu catalogo -> permisos usuarios
$route['permisos_usuario'] ='auth/permisos_usuario';

// menu movimiento de Servivicios -> Alta de Estudios
$route['capturar_estudio'] = '/estudios/capturar_estudio';

//clientes
$route['buscar_cliente'] = '/clientes/buscar_cliente';  // viene de boton search de buscar cliente -> Alta de Estudio 
$route['graba_datos_idr_cliente'] = '/clientes/graba_datos_idr_cliente'; // viene del boton grabar estudio de la ´pantalla de elton solicitud, y grabar los datos nombre direcion contacto DEL IDR unicamente 25/05/2017


$route['getRowEstudioyFechaEntregaResultado'] = "estudios/getRowEstudioyFechaEntregaResultado"; //reg y fecha entrega considera el dia inhabil.. --> envia la informacion del cuadro modal de los estudios y cantidad de la captura de elton capturar_estudio
$route['actualiza_folio_temporal'] = "estudios/actualiza_folio_temporal"; // viene de cuando ingresamos a la solicitud una nueva muestra y queremos saber cuantos se han ingresado, es un folio que me lleva el control de id muestra ingresadas por solicitud de laboratorio (elton)
$route['actualiza_folios_temp'] = 'estudios/actualiza_folios_temp'; // al grabar la solicitud este actualiza los folios temporales que indican cuantas veces se uso un id de muestra

// grabando los encabezado y detallado de los estudios (captura_estudio)
$route['add_encabezado_estudio'] = "/estudios/add_encabezado_estudio"; // grabar el estudio en la base de datos.
$route['add_detallado_estudio'] = 'estudios/add_detallado_estudio';

$route['crud_estudio'] = 'estudios/crud_estudio'; // es para el control de los estudios x estatus

$route['graba_encabezado_resultado'] = 'estudios/graba_encabezado_resultado'; // graba la parte del encabezado del informe de resultados..
$route['graba_detallado_resultado'] ='estudios/graba_detallado_resultado'; // graba la parte del detallado del informe de resultados..

// PARA LOS INFORMES EN EL NUEVO CONTROLADOR IDR
$route['graba_idr_aflatoxinas'] = 'idr/aflatoxinas';
$route['graba_idr_plaguicidas'] = 'idr/plaguicidas';
$route['graba_o_corrige_idr_microbiologia'] = 'idr/graba_o_corrige_idr_microbiologia';

$route['graba_o_corrige_idr_mercurio'] = 'idr/graba_o_corrige_idr_mercurio'; //2017-08-22

$route['graba_idr_mercurio'] = 'idr/mercurio'; //2017-07-03 --> creo q va a eliminarse..!
//2017-07-05
$route['graba_idr_metales'] = 'idr/metales';//2017-07-05
$route['graba_idr_metales2'] = 'idr/metales2';//2017-09-08
//$route['graba_idr_Metales2'] = 'idr/metales2'; //2017-09-08
//06/06/2017
$route['actualiza_status_metodologia'] = 'idr/actualiza_status_metodologia';

//207-07-06
$route['obtener_todos_los_analitos'] = 'estudios/obtener_todos_los_analitos'; //consulta sin parametros unicamente para traer mediante ajax los analitos a mi file funciones, se usa en el idr de plagicidas
$route['obtener_todos_los_analitos_acreditados'] = 'estudios/obtener_todos_los_analitos_acreditados';

//2017-08-09
$route['obtener_todos_los_metales'] = 'estudios/obtener_todos_los_metales';
$route['obtener_todos_los_metales_acreditados']	= 'estudios/obtener_todos_los_metales_acreditados';
//-2017-12-07
$route['obtener_todos_los_analitos_x_metodo_lc'] = 'estudios/obtener_todos_los_analitos_x_metodo_lc';
$route['obtener_todos_los_analitos_x_metodo_gc'] = 'estudios/obtener_todos_los_analitos_x_metodo_gc';

$route['reporte_resultados'] = 'estudios/reporte_resultados' ; // agregado desde el menu --> informe de resultados de los estudios realizado, solicitado por calidad

$route['adjudica_muestra_solicitud'] = 'estudios/adjudica_muestra_solicitud'; // cambiar el estatus a P de procesando

//clientes
$route['alta_cliente'] = '/clientes/alta_cliente';
//16/06/2017
$route['permisos_usuario'] ='auth/permisos_usuario';
$route['inserta_permiso'] = 'auth/inserta_permiso';
$route['elimina_permiso'] = 'auth/elimina_permiso'; 
$route['add_cliente'] = '/clientes/add_cliente';

//21/069/2017
$route['reiniciar_ensayos'] = 'estudios/reiniciar_ensayos';
$route['reiniciar_folios'] = 'estudios/reiniciar_folios';
$route['reiniciar_folios_ensayos'] = 'estudios/reiniciar_folios_ensayos';
//2017-07-13
$route['reiniciar_idrs'] = 'estudios/reiniciar_idrs';

//2017-07-10
$route['graba_idr_Plagicidas'] = 'idr/plagicidas';
//2017-07-11
$route['get_nombre_y_cargo_by_id_usuario'] = 'auth/get_nombre_y_cargo_by_id_usuario';

//2017-08-21
$route['actualiza_idr_aflatoxinas'] = 'idr/actualiza_idr_aflatoxinas';
//$route['actualiza_idr_microbiologia'] = 'idr/actualiza_idr_microbiologia';

//2017-07-28 --> REPORTE GENERAL DE LAS SOLICITUDES
$route['reporte_idr_general'] = 'estudios/reporte_idr_general';

//2017-08-08 --> se unifico una funcion para que grabe el enca y el detalla de la captura de solicitud de aurea 
$route['graba_solicitud'] = 'estudios/graba_solicitud'; 
//2017-08-11
$route['modifica_solicitud'] = 'estudios/modifica_solicitud';  // parecida a la de graba_solicitud, pero usa update en vez de insert

//2017-08-16
$route['envia_correo'] = 'idr/envia_correo'; // para mandar el correo de visto bueno

$route['libera_formato_solicitud'] = 'estudios/libera_formato_solicitud';

// CANCELACION --> 2017-12-06
$route['cancela_muestra_solicitud'] = 'estudios/cancela_muestra_solicitud';

$route['favicon.ico'] = 'estudios/empty_response';//2018-01-29 --> intentando corregir el error de favicon.ico que me marca en el log
