<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estudios_model extends CI_Model {        

    public function __construct()    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    /****************************************************************************************/
   
    /****************************************************************************************/
    public function getResultadoEstudio( $idMuestra =  null){ // genera join con enc y det de estudios y det muestras y estudios
        if ($idMuestra){
            $this->db->select('*');
            $this->db->from('enc_resultado');
            $this->db->join('det_resultado','enc_resultado.ID_ENC_RESULTADO = det_resultado.ID_ENC_RESULTADO');
            $this->db->join('detalle_muestras','enc_resultado.ID_DETALLE_MUESTRA = detalle_muestras.ID_DETALLE_MUESTRA');
            $this->db->join('recepcion_muestras','detalle_muestras.ID_RECEPCION_MUESTRA = recepcion_muestras.ID_RECEPCION_MUESTRA');
            $this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE');
            $this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO');
            $this->db->where('enc_resultado.ID_DETALLE_MUESTRA',$idMuestra);
            $this->db->order_by('PRUEBA_RESULTADO');
            $query = $this->db->get();
            if ($this->db->affected_rows()>0){ return $query->result();}
        } else { return false;}
    }
    
    /***************************************************************************************/  
    public function getAllEstudio( $nIdFolio = null){ // genera join con el id para todo el estudio
        if ($nIdFolio){
            $this->db->select('*');
            $this->db->from('recepcion_muestras');
            $this->db->join('detalle_muestras','recepcion_muestras.ID_RECEPCION_MUESTRA = detalle_muestras.ID_RECEPCION_MUESTRA');
            $this->db->join('clientes','recepcion_muestras.ID_CLIENTE = clientes.ID_CLIENTE','LEFT');
            $this->db->join('estudios','detalle_muestras.ID_ESTUDIO = estudios.ID_ESTUDIO','LEFT');
            $this->db->where('recepcion_muestras.ID_RECEPCION_MUESTRA',$nIdFolio);
            $query = $this->db->get();            
            return $query->result();
        } else {return 'false';}
    }
    /***************************************************************************************/
    public function getIdEstudiofromDetallado( $cDetallado = null){ // regresa el id tomando como entrada su detallado del estudio
        $nRet = -1;
        if ($cDetallado){
            //$this->db->where('NOMBRE_ESTUDIO', $cDetallado) ;   
            $query = $this->db->query("select ID_ESTUDIO from ESTUDIOS where NOMBRE_ESTUDIO = '".$cDetallado."'" )->row();
            //SELECT ID_ESTUDIO FROM ESTUDIOS WHERE NOMBRE_ESTUDIO = 'Determinación de residuos de plagicidads en alimentos por Cromatografía de Gases y Liquidos'
            $nRet = $query->result;
        }
        return $nRet;
    }
    /***************************************************************************************/
    public function getLastId2($cTabla,$cId){ // from detallado de estudios
        $cCad = "select max(".$cId.") as '_id_tmp' from ".$cTabla ;
        $query = $this->db->query( $cCad)->row();
        $nRet = $query->_id_tmp;
        if (!$nRet) $nRet = 1;
        return $nRet;       
    }
    /***************************************************************************************/
    public function getLastId($cTabla,$cId){
    	//$cCad = 'select max('.$cId.') as '.$cId.' from '.$cTabla ;
        $cCad = "select max(".$cId.") as '_id_tmp' from ".$cTabla ;
    	//$nRet = $this->db->query( $cCad)->num_rows();
        $query = $this->db->query( $cCad)->row();
        if ($query) {
            $nRet = $query->_id_tmp;
        }else { $nRet = 1;}
    	return $nRet;    	
    }
    /***************************************************************************************/
    /*public function getEstudio( $nEstudio = null) { // obtiene del catalogo 1 o todos los estudios
    	if ($nEstudio) { $this->db->where('id_estudio',$nEstudio);}
    	return $this->db->get('estudios')->result();
    }*/
    /******************************************************************************************/
    public function getEstudioxId( $nEstudio = null) { // obtiene del catalogo 1 o todos los estudios
        if ($nEstudio) { 
            $this->db->where('id_estudio',$nEstudio);
             return $this->db->get('estudios')->row();
         }else {
            return $this->db->get('estudios')->result();
        }
    }
    /***************************************************************************************/
    public function getFolio( $id = null){
		if ($id) { $this->db->where('id_recepcion_muestra',$id) ; }
		return $this->db->get('detalle_muestras')->result();
	}
}