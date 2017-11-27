<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Utilerias{

   function index(){

   }
   function Personaliza_ID_MUESTRA( $__cIdMuestra = null){
   	$cRet = $__cIdMuestra;
   		if (!is_null($__cIdMuestra)) {			
      		$nValNumerico  =preg_replace("/[^0-9]/", "", $__cIdMuestra);      	
			$cRet = substr( $__cIdMuestra,0,3 ) . str_pad($nValNumerico,4,'0',STR_PAD_LEFT);			
		}
	return $cRet;   		
   }
   
   function generateRandomString($length = 10) {
	    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}   
   ////////////////////////////////////////////////////
   // FUNCION PERMISO DE ACCESO A TODO EL SISTEMA
   ////////////////////////////////////////////////////
   function permiso( $cMod, $cPer) {
      $_lRet = false;
      if ($_SESSION['user_tipo']=='A') { //si es un administrador
         $_lRet = true;
      }else {
         $_cId = $_SESSION['user_id'];
         $CI =& get_instance();

         $CI->load->model('seguridad_model');
         $_lRet = $CI->seguridad_model->getPermiso($_cId,$cMod,$cPer);
         
      }
      return $_lRet;
   }
   
   ////////////////////////////////////////////////////
   //Convierte fecha de mysql a español
   ////////////////////////////////////////////////////
   function fecha_mysql_a_espanol($fecha){
      ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
      $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
      return $lafecha;
   }

   ////////////////////////////////////////////////////
   //Convierte fecha de español a mysql
   ////////////////////////////////////////////////////
   function fecha_espanol_a_mysql($fecha){
      ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
      $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
      return $lafecha;
   } 

   function prueba(){
      return 'ya chinge';
   }
   /***************************************************************/
   function conv_cadena_float( $cNumero = null){
      //$string = str_replace(".","",$string); //borro los separadores de miles, si hay 
      $string = str_replace(",","",$cNumero); //borro los separadores de miles, si hay 
      settype($string,"float"); // lo convierto en un número 
      return $string;
   }
   /***************************************************************/
   public function divide_cadena( $_cCadena = null,$_nLen) { //debe regresar un arreglo con el total de filas devueltas por el arreglo
	      $mRet = array();
	      $cCad = "";
	      
	      if ($_cCadena) {
	         $_mCadena = explode(' ',$_cCadena);
	         if (strlen($_mCadena[0])<$_nLen) {                       
	            foreach ($_mCadena as $element ){                
	               if (strlen($cCad)+strlen($element)<=$_nLen){ //lo metemos a la variable                   
	                  $cCad = $cCad ." ".$element;
	               }else { //brincamor renglon
	                  array_push($mRet, trim($cCad) );
	                  $cCad = $element;
	               }
	            } // fin del for echar
	            array_push($mRet, trim($cCad) ); // que tal si la cadena es muy corta o es una fraccionada
	         } // fin del if len
	      } // fin del if _mcadena
	      return $mRet;
	   }// fin de la function 
	/***************************************************************/
   
   function separa_cadena( $_cCadena = null,$_nLen) { //debe regresar un arreglo con el total de filas devueltas por el arreglo, se usa en el multicelda del pdf
      $mRet = array();
      $cCad = "";
      
      if ($_cCadena) {
         $_mCadena = explode(' ',$_cCadena);
         if (strlen($_mCadena[0])<$_nLen) {                       
            foreach ($_mCadena as $element ){                
               if (strlen($cCad)+strlen($element)<=$_nLen){ //lo metemos a la variable                   
                  $cCad = $cCad ." ".$element;
               }else { //brincamor renglon
                  array_push($mRet, trim($cCad) );
                  $cCad = $element;
               }
            } // fin del for echar
            array_push($mRet, trim($cCad) ); // que tal si la cadena es muy corta o es una fraccionada
         } // fin del if len
      } // fin del if _mcadena
      return $mRet;
   }// fin de la function   
}

