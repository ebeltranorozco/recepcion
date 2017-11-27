<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once (APPPATH."/third_party/fpdf181/fpdf.php");
 
	class Pdf extends FPDF {

		var $cNameReport;
		var $lAsignarDireccionLaria = true;
		var $cMetodoValidado = 'S';
		/* AGREGADO PARA AJUSTAR LAS CELDAS A UN ANCHO VARIABLE  2017-08-03*/
		//Cell with horizontal scaling if text is too wide
	    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true) {
	        if (empty( $txt )) { $txt = " ";}			
	        //Get string width
	        
	        $str_width=$this->GetStringWidth($txt);

	        //Calculate ratio to fit cell
	        if($w==0)
	            $w = $this->w-$this->rMargin-$this->x;
	        $ratio = ($w-$this->cMargin*2)/$str_width;

	        $fit = ($ratio < 1 || ($ratio > 1 && $force));
	        if ($fit)
	        {
	            if ($scale)
	            {
	                //Calculate horizontal scaling
	                $horiz_scale=$ratio*100.0;
	                //Set horizontal scaling
	                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
	            }
	            else
	            {
	                //Calculate character spacing in points
	                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
	                //Set character spacing
	                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
	            }
	            //Override user alignment (since text will fill up cell)
	            $align='';
	        }

	        //Pass on to Cell method
	        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

	        //Reset character spacing/horizontal scaling
	        if ($fit)
	            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
	    }

	    //Cell with horizontal scaling only if necessary
	    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {	    	
	        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
	    }

	    //Cell with horizontal scaling always
	    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {	    	
	        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
	    }

	    //Cell with character spacing only if necessary
	    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
	        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
	    }

	    //Cell with character spacing always
	    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
	        //Same as calling CellFit directly	        
	        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
	    }

	    //Patch to also work with CJK double-byte text
	    function MBGetStringLength($s) {
	        if($this->CurrentFont['type']=='Type0')
	        {
	            $len = 0;
	            $nbbytes = strlen($s);
	            for ($i = 0; $i < $nbbytes; $i++)
	            {
	                if (ord($s[$i])<128)
	                    $len++;
	                else
	                {
	                    $len++;
	                    $i++;
	                }
	            }
	            return $len;
	        }
	        else
	            return strlen($s);
	    }
		
		
		/* AGREGADO PARA PODER IMPRIMIR LOS CODIGOS HTML DENTRO DE LAS CADENAS */
		protected $B = 0;
		protected $I = 0;
		protected $U = 0;
		protected $HREF = '';
		/*******************************************************************************/
		public function WriteHTML($html) {
		    // Intérprete de HTML
		    $html = str_replace("\n",' ',$html);
		    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		    foreach($a as $i=>$e)
		    {
		        if($i%2==0)
		        {
		            // Text
		            if($this->HREF)
		                $this->PutLink($this->HREF,$e);
		            else
		                $this->Write(5,$e);
		        }
		        else
		        {
		            // Etiqueta
		            if($e[0]=='/')
		                $this->CloseTag(strtoupper(substr($e,1)));
		            else
		            {
		                // Extraer atributos
		                $a2 = explode(' ',$e);
		                $tag = strtoupper(array_shift($a2));
		                $attr = array();
		                foreach($a2 as $v)
		                {
		                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
		                        $attr[strtoupper($a3[1])] = $a3[2];
		                }
		                $this->OpenTag($tag,$attr);
		            }
		        }
		    }
		}

		public function OpenTag($tag, $attr) {
		    // Etiqueta de apertura
		    if($tag=='B' || $tag=='I' || $tag=='U' || $tag=='b' || $tag == 'i')
		        $this->SetStyle($tag,true);
		    if($tag=='A')
		        $this->HREF = $attr['HREF'];
		    if($tag=='BR')
		        $this->Ln(5);
		}

		public function CloseTag($tag) {
		    // Etiqueta de cierre
		    if($tag=='B' || $tag=='I' || $tag=='U' || $tag == 'i')
		        $this->SetStyle($tag,false);
		    if($tag=='A')
		        $this->HREF = '';
		}

		public function SetStyle($tag, $enable) {
		    // Modificar estilo y escoger la fuente correspondiente
		    $this->$tag += ($enable ? 1 : -1);
		    $style = '';
		    foreach(array('B', 'I', 'U') as $s)
		    {
		        if($this->$s>0)
		            $style .= $s;
		    }
		    $this->SetFont('',$style);
		}

		public function PutLink($URL, $txt) {
		    // Escribir un hiper-enlace
		    $this->SetTextColor(0,0,255);
		    $this->SetStyle('U',true);
		    $this->Write(5,$txt,$URL);
		    $this->SetStyle('U',false);
		    $this->SetTextColor(0);
		}
		
		/* FIN DE LO AGREADO DE EL CODIGO HTML DENTRO DE LAS CADENAS */

		public function __construct( $cMetValidado = 'S') {
            parent::__construct();
            //$this->library('utilerias');
            if ($cMetValidado == 'N') { $this->cMetodoValidado = 'N';}
         }
        /*******************************************/
        public function setDireccionLaria($l) { // define si se pone la direccion al reporte en la parte del encabezado
        	$this->$lAsignarDireccionLaria = $l;
        }
        /** *****************************************/
        public function Multicelda2($ancho,$alto,$txt,$nBorde,$alig ,$nDesplaza ,$lFill =false ){ // para incluirle la opcion de impresion en cursiva
        	//obteniendo las posiciones actuales
        	$nPosXactual = $this->getX();
        	$nPosYactual = $this->getY();

        	// PREGUNTAMOS POR FILL
        	if ($lFill) { $this->cell($ancho,$alto, '',0,0,'',true);}
        	//preguntamos por el borde        	
        	if ($nBorde == 1) { $this->rect($nPosXactual,$nPosYactual,$ancho,$alto);}
        	// partimos la cadena en w        	
        	        	
        	$nLen2= intval($ancho-($ancho*.30)); // no se como es pero asi es.!
        	$nLen2= intval($ancho-($ancho*.35)); // no se como es pero asi es.! 2017-07-05
        	$mCad = $this->divide_cadena($txt,$nLen2); // aqui hay problemas.!        	    	
        	        	
        	$nInc = $alto/2;
        	if (count($mCad)>0) {
        		$nInc = $alto/ count($mCad);
        	}

        	//$this->setxy($nPosXactual+2,$nPosYactual);// para que no empieze a escribir sobre la linea
        	for ($nPosFila = 0; $nPosFila  < count($mCad); $nPosFila++){
        		$cParrafo = $mCad[$nPosFila]; // obtenemos parrafo completo
        		
        		$nPosYactual = $this->getY();
        		$this->setxy($nPosXactual+2,$nPosYactual);// para que no empieze a escribir sobre la linea        		
				
				$nPos1 = strpos($cParrafo,'<i>');
				$nPos2 = strpos($cParrafo,'</i>');
				
				//$nAncho = $ancho-2;
				$nAncho = strlen($cParrafo);
				
				if ($nPos1>-1) {
					if ($nPos1>2) {
						$this->Cell($nAncho-$nPos1+2,$nInc,substr($cParrafo,0,$nPos1),0,0,$alig);
					}					
					
					if ($nPos2>-1){
						$this->SetFont('Arial','I',8);
						$this->Cell($nPos2-$nPos1+1,$nInc,substr($cParrafo,$nPos1+3,$nPos2-($nPos1+3)),0,0,$alig);						
						$this->SetFont('Arial','',8);
						$this->Cell($nAncho-$nPos2+1,$nInc,substr($cParrafo,$nPos2+4,$nAncho-$nPos2+3),0,2,$alig);
					}else {						
						$this->Cell($nAncho-$nPos2+3,$nInc,'error'.substr($cParrafo,$nPos1,$nAncho-$nPos2+3),0,2,$alig);
						$this->SetFont('Arial','',8);
					}

				}else { // funciona de manera normal sin cursivas..!
					$this->cell($ancho-2,$nInc,$cParrafo,0,2,$alig);
				}
				
								
				/*
				
				
				$aAncho = array('a'=>2,'b'=>2,'c'=>2,'d'=>2,'e'=>2,'f'=>2,'g'=>2,'h'=>2,'i'=>1,'j'=>2,'k'=>2,'l'=>1,'m'=>3,'n'=>2,'ñ'=>2,'o'=>2,'p'=>1.5,'q'=>2,'r'=>1.5,'s'=>1.5,'t'=>1,'u'=>2,'v'=>2,'w'=>2,'x'=>2,'y'=>2,'z'=>2,
				                'A'=>2,'B'=>2,'C'=>2,'D'=>2,'E'=>2,'F'=>2,'G'=>2,'H'=>2,'I'=>1,'J'=>2,'K'=>2,'L'=>2,'M'=>3,'N'=>2,'Ñ'=>2,'O'=>2,'P'=>1.5,'Q'=>2,'R'=>1.5,'S'=>2,'T'=>2,'U'=>2,'V'=>2,'W'=>3,'X'=>2,'Y'=>2,'Z'=>2,
				                'á'=>2,'é'=>2,'í'=>2,'ó'=>2,'ú'=>2,'.'=>1,' '=>3,' '=>2,'>'=>0,'<'=>0);
				$lImprime = true;

				for ($nPosColumna = 0; $nPosColumna<strlen( $mCad[$nPosFila] ); $nPosColumna++ ) {
							//Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
					$cLetra = utf8_encode($mCad[$nPosFila][$nPosColumna]);
					
					$nAnchoLetra = 0;
					if (!empty($aAncho[$cLetra])){
						$nAnchoLetra = $aAncho[$cLetra];
						
						if ($cLetra =='<'){ $lImprime = false;}
						if ($cLetra =='>'){ $lImprime = true;}
						
					}
					
					//if ($cLetra == 'e') {
					if ( $nAnchoLetra > 0 ){
						if ($lImprime){
							$this->cell($nAnchoLetra,$nInc,utf8_decode($cLetra),0,0,1);
						}						
					}
					
					
					
					
					//$nLenAncho = ps_stringwidth($cLetra);
					//$nLenAncho = ps_stringwidth(,'prueba de cadena');
					//$nLenAncho = mb_strlen('prueba de cadena');
					//ps_stringwidth()
					//$this->cell(10,$nInc,$nLenAncho);
					//$this->cell($ancho-2,$nInc,$nPosFila,0,1,$alig);
					//$this->cell($ancho-2,$nInc,$nPosColumna,0,1,$alig);
				}
				
				*/
				
			} // fin del for nPosfila = 0
			// por ultimo checamos pa donde se va a mover el apuntador de row y col
			if ($nDesplaza == 2 ) { // movemos hacia abajo
				$this->setX( $nPosXactual);
				$this->setY(  $nPosYactual + $alto);
			}
			
			if ($nDesplaza==1) { //movemos a la derecha
				$this->setxy( $nPosXactual+$ancho, $nPosYactual);				
			}			
        } 
        /*******************************************/
        //$nPosEnc['col8']-$nPosEnc['col7'],$nInc,utf8_decode($data[$i]->METODOLOGIA_ESTUDIO),1= borde,'T'=alig ,0 =desplaza);
        //$this->pdf->cellHtml($nPosEnc['col7']-$nPosEnc['col6'],$nInc,utf8_decode($data[$i]->LOTE_MUESTRA),1,'T',0 );
        public function cellHtml($ancho,$alto,$txt,$nBorde,$alig ,$nDesplaza ,$lFill =false ){ // para incluirle la opcion de impresion en cursiva
        	//obteniendo las posiciones actuales
        	$nPosXactual = $this->getX();
        	$nPosYactual = $this->getY();

        	// PREGUNTAMOS POR FILL
        	if ($lFill) { $this->cell($ancho,$alto, '',0,0,'',true);}
        	//preguntamos por el borde        	
        	if ($nBorde == 1) { $this->rect($nPosXactual,$nPosYactual,$ancho,$alto);}
        	// partimos la cadena en w        	
        	        	
        	$nLen2= intval($ancho-($ancho*.30)); // no se como es pero asi es.!
        	$nLen2= intval($ancho-($ancho*.35)); // no se como es pero asi es.! 2017-07-05
        	$nLen2= intval($ancho-($ancho*.25)); // no se como es pero asi es.! 2017-07-05
        	$mCad = $this->divide_cadena($txt,$nLen2); // aqui hay problemas.!        	    	
        	        	
        	$nInc = $alto/2; // en el puro medio
        	if (count($mCad)>0) {
        		//$nInc = ceil(($alto-2)/ count($mCad));
        		$nInc = ($alto)/ count($mCad);
        		$nInc = round( $nInc );
        		//$nInc = $nInc /2; 		
        	}

        	//$this->setxy($nPosXactual+2,$nPosYactual);// para que no empieze a escribir sobre la linea
        	
        	//$this->setxy($nPosXactual,$nPosYactual+1); // imprima fuera de la linea
        	//$nPosYactual = $this->getY()+1;
        	for ($nPosFila = 0; $nPosFila  < count($mCad); $nPosFila++){
        		
        		$nPosYactual = $this->getY();
        		//$cParrafo = $mCad[$nPosFila].'r='.$nPosYactual.' /i=' . $nInc ; // obtenemos parrafo completo
        		$cParrafo = $mCad[$nPosFila];//.'-'.$nPosXactual.'/'.$nPosYactual; // obtenemos parrafo completo
        		//$this->setxy($nPosXactual,$nPosYactual);// para que no empieze a escribir sobre la linea
        		if (count($mCad)==1)  {	// hacer que imprima en el puro medio
					$this->setXY($nPosXactual,$nPosYactual + ($alto/2)-2);	//--> PENDIENTE DE VERIFICAR..!
					//$this->Ln(($alto/2)-2);
				}
				
				$nPos1 = strpos($cParrafo,'<i>');
				$nPos2 = strpos($cParrafo,'</i>');
				
				//$nAncho = $ancho-2;
				$nAncho = strlen($cParrafo);
				
				if ($nPos1>-1) { // significa que si lo encontro
					if ($nPos2==false) { $cParrafo = $cParrafo . '</i>';}
					$this->setXY( $this->getX(),$nPosYactual-.5);
					$this->WriteHTML($cParrafo);
					//$this->Cell( $ancho,$nInc,$cParrafo,$nBorde,2,'L');
				}elseif ($nPos2>-1) { // hay que anexarle <i>
					if ($nPos1==false) { $cParrafo = '<i>'.$cParrafo;}
					$this->setXY( $this->getX(),$nPosYactual-.5);
					$this->WriteHTML($cParrafo);
					//$this->Cell( $ancho,$nInc,$cParrafo,$nBorde,2,'L');					
				}
				else { // funciona de manera normal sin cursivas..!
					//$this->cellFitScale($ancho-2,$nInc,$cParrafo.'['.$nInc.']'.$alto,0,2,$alig);
					$this->cellFitScale($ancho-2,$nInc,$cParrafo,0,2,$alig);
					//$this->Cell( $ancho,$nInc,$cParrafo,0,2,$alig);
				}
				//ajustamos la posicion del renglon
				
				$this->setxy($nPosXactual,$nPosYactual+$nInc);// 
				
			} // fin del for nPosfila = 0
			
			// por ultimo checamos pa donde se va a mover el apuntador de row y col
			if ($nDesplaza == 2 ) { // movemos hacia abajo
				$this->setX( $nPosXactual);
				$this->setY(  $nPosYactual + $alto);
			}
			
			if ($nDesplaza==1) { //movemos a la derecha
				$this->setxy( $nPosXactual+$ancho, $nPosYactual);				
			}			
        } 
		/*************************************************************************************/     
        public function Multicelda($ancho,$alto,$txt,$nBorde,$alig ,$nDesplaza ,$lFill =false ){
        	//Multicelda($nPosEnc['col7']-$nPosEnc['col3'],$nInc,$cDireCte,0,'B',1,false);
        	//obteniendo las posiciones actuales
        	$nPosXactual = $this->getX();
        	$nPosYactual = $this->getY();

        	// PREGUNTAMOS POR FILL
        	if ($lFill) { $this->cell($ancho,$alto, '',0,0,'',true);}
        	//preguntamos por el borde        	
        	if ($nBorde == 1) { $this->rect($nPosXactual,$nPosYactual,$ancho,$alto);}
        	// partimos la cadena en w
        	
        	$nLen2= intval($ancho-($ancho*.30)); // no se como es pero asi es.!
        	$nLen2= intval($ancho-($ancho*.35)); // no se como es pero asi es.! 2017-07-05
        	$mCad = $this->divide_cadena($txt,$nLen2); // aqui hay problemas.!
        	
        	$nInc = $alto/2;
        	if (count($mCad)>0) {
        		$nInc = $alto/ count($mCad);
        	}


        	$this->setxy($nPosXactual,$nPosYactual);
        	for ($nPos = 0; $nPos  < count($mCad); $nPos++){
				//$this->text($nPosXactual,$nPosYactual)
				$this->cell($ancho-2,$nInc,$mCad[$nPos],0,2,$alig);
			}
			// por ultimo checamos pa donde se va a mover el apuntador de row y col
			if ($nDesplaza == 2 ) { // movemos hacia abajo
				$this->setX( $nPosXactual);
				$this->setY(  $nPosYactual + $alto);
			}
			
			if ($nDesplaza==1) { //movemos a la derecha
				$this->setxy( $nPosXactual+$ancho, $nPosYactual);				
			}			
        }
        /***********************************************/

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
	   /*****************************************************/

        public function setNombreReporte( $cNombreReporte){
        	$this->cNameReport = $cNombreReporte;
        }
        /****************************************************/

        public function Header(){
        	//C:\xampp\htdocs\recepcion\public_html\assets\img}
        	// logotipo siempre va..!
        	//$this->Image('\xampp\htdocs\recepcion\public_html\assets\img\logo_laria.png',10,4,37);
        	//$this->Image(base_url('assets/img/logo_laria.png'),10,4,37);
        	$this->Image('assets/img/logo_laria.png',10,4,37);


        	if ($this->cNameReport == 'Informe de Resultados Quimicos' or $this->cNameReport == 'Informe de Resultados Microbiologicos') {
        		$this->SetFont('Arial','B',12);
        		if ($this->cNameReport == 'Informe de Resultados Quimicos') {
	            	$text = utf8_decode('ENSAYOS QUIMÍCOS');
        		}
        		if ($this->cNameReport == 'Informe de Resultados Microbiologicos') {
	            	$text = utf8_decode('ENSAYOS MICROBIOLOGICOS');
        		}
	            $this->text($this->w - $this->GetStringWidth($text)-10,8,$text);
        		//$this->line(10,20,$this->w-10,18);				
        		
	            $this->Ln('10');
	            $this->line($this->getX(),$this->getY(),210,$this->getY());
        	} // fin del Informe de Resultados Quimicos
        	/*********************************************************************/        	

        	if ($this->cNameReport == 'Solicitud de Servicios de Laboratorio') {

	        	$mid_x = $this->w / 2;
	        	$mid_y = $this->h / 2;

	            
	            $this->SetFont('Arial','B',7);
	            //$this->Cell(30);
	            //$this->setX="200";
	            //$this->Cell($mid_x,2,'LABORATORIO REGIONAL DE INOCUIDAD ALIMENTARIA',0,0,'C');
	            $text = 'LABORATORIO REGIONAL DE INOCUIDAD ALIMENTARIA';
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),5,$text);
	            $text = utf8_decode('Callejón al Río No. 616 Nte.');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),8,$text);
	            $text = utf8_decode('San Pedro de Rosales, Navolato, Sinaloa, México');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),11,$text);
	            $text = utf8_decode('C.P. 80376 tel. (667) 170 11 54 y 170 16 50');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),14,$text);

	            $this->SetFont('Arial','B',12);
	            //$this->cell(0,5,'Solicitud de Servicios de laboratorio',1,2,'C');
	            $text = utf8_decode('Solicitud de Servicios de');
	            $this->text($this->w - $this->GetStringWidth($text)-10,8,$text);
	            $text = utf8_decode('laboratorio');
	            $this->text($this->w - $this->GetStringWidth($text)-10,12,$text);	

	            $this->line(10,18,$this->w-10,18);

				//$this->Cell(120,20,utf8_decode('C.P. 80376 tel. (667) 170-11-54 y 170-16-50'),0,0,'C');
	            $this->Ln('5');	            
	            
			} // fin del cNameReport =  Solicitud de Servicios de Laboratorio
			

			if ($this->cNameReport == 'Entrega de Muestras') { // formato laria F02
				$mid_x = $this->w / 2;
	        	$mid_y = $this->h / 2;	            
	            $this->SetFont('Arial','B',7);
	            //$this->Cell(30);
	            //$this->setX="200";
	            //$this->Cell($mid_x,2,'LABORATORIO REGIONAL DE INOCUIDAD ALIMENTARIA',0,0,'C');
	            $text = 'LABORATORIO REGIONAL DE INOCUIDAD ALIMENTARIA';
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),5,$text);
	            $text = utf8_decode('Callejón al Río No. 616 Nte.');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),8,$text);
	            $text = utf8_decode('San Pedro de Rosales, Navolato, Sinaloa, México');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),11,$text);
	            $text = utf8_decode('C.P. 80376 tel. (667) 170 11 54 y 170 16 50');
	            $this->text($mid_x-($this->GetStringWidth($text) / 2),14,$text);

	            $this->SetFont('Arial','B',12);
	            //$this->cell(0,5,'Solicitud de Servicios de laboratorio',1,2,'C');
	            $text = utf8_decode('Entrega de Muestras');
	            $this->text($this->w - $this->GetStringWidth($text)-10,8,$text);
	            //$text = utf8_decode('laboratorio');
	            //$this->text($this->w - $this->GetStringWidth($text)-10,12,$text);	

	            $this->line(10,18,$this->w-10,18);

				//$this->Cell(120,20,utf8_decode('C.P. 80376 tel. (667) 170-11-54 y 170-16-50'),0,0,'C');
	            $this->Ln('5');
	        } // FIN DE NAME = ETRNEGA DE MUIESTRAS
       }
	
   		/****************************************************/
		public function Footer(){
			if ($this->cNameReport == 'Informe de Resultados Quimicos' or $this->cNameReport == 'Informe de Resultados Microbiologicos') {
				 $this->SetY(-25);
				 $nRow = $this->getY();
				 $this->SetFont('Arial','',6);
				 //$this->Set
				 $this->cell(60,4,utf8_decode('Callejón al Río No. 616 Nte. San Pedro de Rosales'),0,1);
				 $this->cell(60,4,utf8_decode('Navolato. Sinaloa. México C.P. 80376'),0,1);
				 $this->cell(60,4,utf8_decode('Tel: (667) 170 16 50 y (667) 170 11 54'),0,1);				 				 
				 $this->cell(60,4,utf8_decode('servicios@laria.mx'),0,2);			 
				 
				 
				 //Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
				 //$this->pdf->setxy(250,$nRow);
				 $this->Image('assets/img/cesavesin_pie.png',75,$nRow+1,45);
				 
				 //$this->Image('assets/img/cesavesin_pie.png',,,59);
				 
				 IF ($this->cMetodoValidado == 'S') {
				 	$this->Image('assets/img/ema_pie.jpg',141,$nRow-1,18);
				 	$this->setXY(160,$nRow+4);
				 	$this->cell(60,4,utf8_decode('Número de acreditación No. A-0733-074/16' ),0,2);
				 	$this->cell(60,4,utf8_decode('Fecha de Acreditación 2016-05-19.' ),0,1);	
				 }			 
				 
				 
				 $this->ln(4);
				 $this->line($this->getX(),$this->getY(),210,$this->getY());

	           $this->SetFont('Arial','',8);
	           $this->cell(0,5,utf8_decode('Código: LARIA-GC-P017-F01 Revisión: 13'));
	           $this->Cell(0,5,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
			}

			if ($this->cNameReport == 'Solicitud de Servicios de Laboratorio') {
				// HAY Q AGREGAR UN CAMPO QUE ME DEFINA EL NUMERO DE REVISION AUTOMATICAMENTE

	           $this->SetXY(10,-10);
	           $this->SetFont('Arial','',8);
	           $this->cell(0,10,utf8_decode('Código: LARIA-GC-P004-F01 Revisión: 10'));
	           $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
	       }
	       if ($this->cNameReport == 'Entrega de Muestras') {
	       		// HAY Q AGREGAR UN CAMPO QUE ME DEFINA EL NUMERO DE REVISION AUTOMATICAMENTE
	           $this->SetXY(10,-10);
	           $this->SetFont('Arial','',8);
	           $this->cell(0,10,utf8_decode('Código: LARIA-GC-P004-F02 Revisión: 10'));
	           $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
	       }

      	}
	}
?>