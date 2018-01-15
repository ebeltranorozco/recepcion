<?php //var_dump( $sql); 
//var_dump($query);
//var_dump($prueba);
//var_dump($registro);
//var_dump($_SESSION);
?>
<div class="container">
	<table class="table table-striped" id="idTablaCrudEstudiosGeneral">
		<thead>
			<tr>
				<th>ID</th>
				<th>Solicitud</th>
				<th>Id Muestra</th>
				<th>#</th>				
				<th>Fecha Ingresó</th>
				<th>Fecha Final</th>
				<th>Descripción</th>
				<th>Id Metodo</th>
				<th>Metodología</th>
				<th>Analisis</th>				
				<th>Status</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>

			<?php 		
			
			foreach ($query as $registro  ) {
				echo '<tr>';
					echo '<td>'.$registro->ID_RECEPCION_MUESTRA.'</td>';
					echo '<td>'.$registro->FOLIO_SOLICITUD.'</td>';
					$cAreaIdMuestra = substr($registro->ID_MUESTRA,2,1);
					echo '<td>'.$registro->ID_MUESTRA.'</td>';
					echo '<td>'.$registro->TOTAL_ENSAYOS_X_MUESTRA.'</td>';
					echo '<td>'.$registro->FECHA_RECEPCION.'</td>';
					echo '<td>'.$registro->FechaFinalIDR.'</td>';// Temporal..!
										
					$cDescMuestra = trim($registro->TIPO_MUESTRA).' '.trim( $registro->PESO_VOL_MUESTRA) . ' '. trim($registro->TEMPERATURA_MUESTRA);					
					echo '<td>'.$cDescMuestra.'</td>';
					echo '<td>'.$registro->ID_METODOLOGIA.'</td>';
					echo '<td>'.$registro->METODOLOGIA_ESTUDIO.'</td>';
					echo '<td>'.$registro->ANALISIS_SOLICITADO.'</td>'; // tiendo a ocultarlo..!
					
					$sm = $registro->STATUS_MUESTRA;
					if ($sm== 'F') $sm = 'FINALIZADA';
					if ($sm== 'A') $sm = 'ACTIVA';
					if ($sm== 'I') $sm = 'INVALIDA';
					if ($sm== 'C') $sm = 'CANCELADA';
					if ($sm== 'P') $sm = 'EN PROCESO';
					if ($sm== 'G') $sm = 'IDR GENERADO';
					
					$idStatus = 'idColumnaStatusMuestra' . $registro->ID_METODOLOGIA;
					//$idStatus = $this->utilerias->generateRandomString();

					echo '<td><div id='.$idStatus.'>'.$sm.'</div></td>';
					$accion = '';
					//////////////////////////////////////////////////////////////////
					//////CORRECCIONES DE SOLICITUDES ANTES DE IDR
					/////////////////////////////////////////////////////////
					if ($this->utilerias->permiso( 'ESTUDIOS','CORREGIR' ) && $sm == 'ACTIVA' ) { 
						
						$dFechaSolicitud = date('Y-m-d', strtotime("$registro->FECHA_RECEPCION + 3 day"));
						$dFechaActual = date('Y-m-d');
						
						if ($dFechaActual < $dFechaSolicitud) {
							$accion = '<a href= "'.base_url('estudios/edit/').$registro->ID_RECEPCION_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Editar muestra" class="btn btn-info btn-xs" aria-label="Left Align"><span  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></a>';
						}else {
							$accion = '<a href= "'.base_url('estudios/edit/').$registro->ID_RECEPCION_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Editar muestra(Tiempo Exedido)" class="btn btn-info btn-xs" aria-label="Left Align"><span  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></a>';
						}
					}
					
					// CANCELAR / ELIMINAR

					/* <p><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" id="idBtnAgregarEstudio">Agregar Estudio</button></p>*/
					if ($this->utilerias->permiso( 'ESTUDIOS','ELIMINAR' ) && $sm <> 'CANCELADA' && $sm <>'FINALIZADA') {
						$accion = $accion . '<button type="button" value="'.$registro->ID_MUESTRA.'" class="btn btn-danger btn-xs esCancelable" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip"  title="Cancelar" value="'.$registro->ID_MUESTRA.'"  ></span></button>';
					}
					// IMPRIMIR 
					if ($this->utilerias->permiso( 'ESTUDIOS','IMPRIMIR' ) ) { // LAS HOJAS DE RECEP Y CLIENTE
						//'+idFolioSolicitud;
						$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/SolicitudLaboratorio/').$registro->ID_RECEPCION_MUESTRA.'">';
						$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Imprimir Solicitud Cliente" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						
						// ahora la solicitud del cliente
						$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/EntregaMuestra/').$registro->ID_RECEPCION_MUESTRA.'">';
						$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Imprimir Entrega LARIA" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></button></a>';

					}
		
					//EN PROCESO 		 // INICIAR ANALISIS			
					if ( $sm == 'ACTIVA'){       // TEMPORAL    {}
						$cadAleatoria = $this->utilerias->generateRandomString();// de moment no se usa			
						
						if ($this->utilerias->permiso( 'INFORMES','ACCESO' ) or $_SESSION["user_tipo"]== 'A' ) {
							//$accion .= '<div id="'.$cadAleatoria.'">';							
							// boton exclusivo para quimicos o microbiologos							
							
							//$accion = $accion . '<button id= "'.$cadAleatoria.'"  type="button" value="'.$registro->ID_METODOLOGIA.'" class="btn btn-primary btn-xs esProcesable" ><span class="glyphicon glyphicon glyphicon-log-out" aria-hidden="true" data-toggle="tooltip"  title="Iniciar Analisis" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';							
							$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.'" class="btn btn-primary btn-xs esProcesable" ><span class="glyphicon glyphicon glyphicon-log-out" aria-hidden="true" data-toggle="tooltip"  title="Iniciar Analisis" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';							
							
						} // FIN DEL SESSION if ($this->utilerias->permiso( 'IDR','ACCESO' ) or $_SESSION["user_tipo"]== 'A' ) {						
					}// DEIN DE STATUS = A
					
					
					//31/05/2017 cuado empeze lo de los IDR
					//$cStyle = 'visibility:hidden';
					$cStyle = 'display:none';
					if ($sm == 'EN PROCESO') { // CFREO ES ESTE ($this->utilerias->permiso( 'IDR','GRABAR' ) )
						if ($this->utilerias->permiso( 'INFORMES','ACCESO' ) || $_SESSION["user_tipo"]== 'A' ) {
							$cStyle = 'display:block';
						} // fin del this->utilerias->permiso ( estudios, imprimir)
					} // FIN DEL SM=EN PROCESO		
								
								// DEFINIMOS QUE SE VA IMPRIMIR
								if ($registro->ID_IDR == 1 ) { // SE TRATA DE AFLAXOTINAS TOTALES de la tabla estudios viene
									$accion = $accion . '<a style="'.$cStyle.'" href= "'.base_url('idr/idr_aflatoxinas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';									
									$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR Aflaxotinas"></span></button></a>';									
								} // FIN DEL IDR = 1 Aflaxotinas 
								
								if ($registro->ID_IDR == 2 ) { // SE TRATA DE PLAGUICIDAS X CROMATOGRAFIA
									$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
									
								$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR Plaguicidas"></span></button></a>';	
									
								} // FIN DEL IDR = 1 Aflaxotinas 
								
								if ($registro->ID_IDR == 3 ) { // SE TRATA DE MICROBIOLOGIA
									$accion = $accion . '<a style="'.$cStyle.'" href= "'.base_url('idr/idr_microbiologia/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';									
									$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR para Microbiologia"></span></button></a>';									
									//$accion .= '<p>'.$sm.'</p>';									
								} // FIN DEL IDR = 1 Aflaxotinas 
								
								if ($registro->ID_IDR == 4 ) { // SE TRATA DE MERCURIO
									//$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_mercurio/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';
									$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_mercurio2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';
									$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR para Mercurio"></span></button></a>';									
								} // FIN DEL IDR = 4 mercurio
								
								
								if ($registro->ID_IDR == 5 ) { // SE TRATA DE METALES
									//$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_metales/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';									
									$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_metales2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';									
									$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR para Metales"></span></button></a>';									
								} // FIN DEL IDR = 4 metales
								
								
							
						
					
					/*****************************************************************************
					 **********IMPRESION INFORME DE RESULTADOS************************************
					 ****************************************************************************/
					 
					if ($this->utilerias->permiso( 'INFORMES','IMPRIMIR' ) && ($sm == 'FINALIZADA' or $sm == 'IDR GENERADO' )) { // 
						//'+idFolioSolicitud;
						// DEFINIMOS QUE SE VA IMPRIMIR
						if ($registro->ID_IDR == 1 ) { // SE TRATA DE AFLAXOTINAS 
							$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/idr_aflatoxinas/').$registro->ID_DETALLE_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="IDR Aflatoxinas" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
							
						if ($registro->ID_IDR == 2 ) { // SE TRATA DE PLAGUICIDAS 
							$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/idr_plaguicidas/').$registro->ID_DETALLE_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="IDR Plaguicidas" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 3 ) { // SE TRATA DE MICROBIOLOGICO
							$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/idr_microbiologia/').$registro->ID_DETALLE_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="IDR Microbiologia" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 4 ) { // SE TRATA DE MERCURIO QUIMICO
							$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/idr_mercurio/').$registro->ID_DETALLE_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="IDR Mercurio" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 5 ) { // SE TRATA DE METALES QUIMICOS
							$accion = $accion . '<a target= "new" href= "'.base_url('impresiones_controller/idr_metales2/').$registro->ID_DETALLE_MUESTRA.'">';
							$accion = $accion . '<button type="button" data-toggle="tooltip"  title="IDR Metales" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
					
					
					}
					/*****************************************************************************
					 **********CORRECCION DE LOS INFORME DE RESULTADOS****************************
					 ****************************************************************************/
					//2017-07-25 --> CORRECCIONES DE LOS INFORMES
					if ($this->utilerias->permiso( 'INFORMES','CORREGIR' ) && $sm == 'IDR GENERADO') { // 
						
						//2017-08-25 --> verificar los dias transcurridos (maximo dos)para saber si es viable la correccion
						$dFechaFinalIDR = date('Y-m-d', strtotime("$registro->FechaFinalIDR + 2 day"));
						$dFechaActual = date('Y-m-d');
						
						if ($dFechaActual < $dFechaFinalIDR) {
							//$cTiempo = 'Corrección IDR'
							//$accion = '<a href= "'.base_url('estudios/edit/').$registro->ID_RECEPCION_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Editar muestra" class="btn btn-info btn-xs" aria-label="Left Align"><span  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></a>';
						
							// DEFINIMOS QUE SE VA CORREGIR
							if ($registro->ID_IDR == 1 ) { // SE TRATA DE AFLAXOTINAS 
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_aflatoxinas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Aflatoxinas" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 2 ) { // SE TRATA DE PLAGUICIDAS 
							  
								//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								//$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Plaguicidas" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';							  
								
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 3 ) { // SE TRATA DE MICROBIOLOGICO
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_microbiologia/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Microbiologia" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 4 ) { // SE TRATA DE MERCURIO QUIMICO
								//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_mercurio/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_mercurio2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';							
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Mercurio" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 5 ) { // SE TRATA DE METALES QUIMICOS
									//$accion = $accion . '<a style="'.$cStyle.'"  href= "'.base_url('idr/idr_metales2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA. '/'.$registro->ID_ESTUDIO .'">';									
									//$accion = $accion . '<button type="button" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" data-toggle="tooltip"  title="IDR para Metales"></span></button></a>';									
							
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_metales2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Metales" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
						
						}else { // YA NO ESTAMOS EN CONDICIONES DE REALIZAR CONDICIONES, 2 DIAS EXCEDIDO
							//$accion = '<a href= "'.base_url('estudios/edit/').$registro->ID_RECEPCION_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Editar muestra(Tiempo Exedido)" class="btn btn-info btn-xs" aria-label="Left Align"><span  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></a>';
							// DEFINIMOS QUE SE VA IMPRIMIR
							if ($registro->ID_IDR == 1 ) { // SE TRATA DE AFLAXOTINAS 
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_aflatoxinas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Aflatoxinas (Tiempo Exedido)" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 2 ) { // SE TRATA DE PLAGUICIDAS 
								//$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Plaguicidas" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
								
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Plaguicidas (Tiempo Exedido)" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 3 ) { // SE TRATA DE MICROBIOLOGICO
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_microbiologia/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Microbiologia (Tiempo Exedido)" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 4 ) { // SE TRATA DE MERCURIO QUIMICO
								//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correcciones_idr_mercurio/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_mercurio2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';							
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Mercurio (Tiempo Exedido)" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
									
							if ($registro->ID_IDR == 5 ) { // SE TRATA DE METALES QUIMICOS
								$accion = $accion . '<a target= "new" href= "'.base_url('idr/idr_metales2/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
								$accion = $accion . '<button type="button" data-toggle="tooltip"  title="Corrección IDR Metales (Tiempo Exedido)" class="btn btn-warning btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>';
							} // FIN REPORTE IDR $REGISTRO->ID_IDR
							
							
						}
						
						
						
					}
					
					/*****************************************************************************
					 **********VO BO DE LOS INFORME DE RESULTADOS****************************
					 ****************************************************************************/
					//2017-07-25 --> CORRECCIONES DE LOS INFORMES
					if ($this->utilerias->permiso( 'INFORMES','CORREGIR' ) && $sm == 'IDR GENERADO') { // 
						//'+idFolioSolicitud;
						// DEFINIMOS QUE SE VA IMPRIMIR
						if ($registro->ID_IDR == 1 ) { // SE TRATA DE AFLAXOTINAS 
							$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.';AFLATOXINAS" class="btn btn-primary btn-xs voboIDR" ><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"  title="VoBo IDR Aflatoxinas" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';
							//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correo_idr_aflatoxinas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="VoBo IDR Aflatoxinas" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 2 ) { // SE TRATA DE PLAGUICIDAS
							$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.';PLAGUICIDAS" class="btn btn-primary btn-xs voboIDR" ><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"  title="VoBo IDR Plaguicidas" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';
							//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correo_idr_plaguicidas/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="VoBo IDR Plaguicidas" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 3 ) { // SE TRATA DE MICROBIOLOGICO
							$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.';MICROBIOLOGIA" class="btn btn-primary btn-xs voboIDR" ><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"  title="VoBo IDR Microbiologia" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';
							//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correo_idr_microbiologia/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="VoBo IDR Microbiologia" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 4 ) { // SE TRATA DE MERCURIO QUIMICO
						$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.';MERCURIO" class="btn btn-primary btn-xs voboIDR" ><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"  title="VoBo IDR Mercurio" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';
							//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correo_idr_mercurio/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="VoBo IDR Mercurio" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
								
						if ($registro->ID_IDR == 5 ) { // SE TRATA DE METALES QUIMICOS
							$accion = $accion . '<button type="button" value="'.$registro->ID_METODOLOGIA.';METALES" class="btn btn-primary btn-xs voboIDR" ><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"  title="VoBo IDR Metales" value="'.$registro->ID_METODOLOGIA.'"  ></span></button>';
							//$accion = $accion . '<a target= "new" href= "'.base_url('idr/correo_idr_metales/').$registro->ID_METODOLOGIA.'/'.$registro->ID_MUESTRA.'">';
							//$accion = $accion . '<button type="button" data-toggle="tooltip"  title="VoBo IDR Metales" class="btn btn-primary btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button></a>';
						} // FIN REPORTE IDR $REGISTRO->ID_IDR
					}

					
					echo '<td>'.$accion.'</td>';
				echo '</tr>';
			} // fin del for each 


			//echo '</br>';
			//var_dump($query);
			?>

			
		</tbody>

	</table>

</div> <!--// dein div container-->


<!-- ******************************************************************************
***********************************VENTANA MODAL PARA LOS CASOS DE CANCELACION -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Cancelacion(s)</h4> 
          </div> <!--fin de modal header -->

          <div class="modal-body">

            <!-- vamos agregar las alertas -->
            <div id="msg_alerta_modal"></div> 
            <!-- LIMPIAR LOS CAMPOS -->
              
            <?php 
              
              //variables del detallado del estudio
              $cIdMuestra	= array( 'id'=>'id_Muestra','class'=>"form-control",'value'=>'');
              $cObs 		= array( 'id'=>'idObs_Cte','class'=>"form-control",'value'=>'', 'rows'=>3 );
              
            ?>

			<div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Id Muestra:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_input($cIdMuestra); ?>
                    </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                    <label  class="col-sm-8 control-label" for="id_cte">Detalle de Cancelación:</label>                    
                    <div class="col-sm-10">                        
                        <?php echo form_textarea($cObs); ?>
                    </div>
              </div>
            </div>


          </div> <!--fin de modal body -->
          

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="idBtnCloseModal">Close</button>
            <!--<button type="button" class="btn btn-primary">Anexar</button>-->
            <button type="button" class="btn btn-primary"  name="idBtnCancelaEstudio" id="idBtnCancelaEstudio">Aplicar</button>
          </div> <!--fin de modal footer -->
         
        
        </div> <!--fin de modal content -->
      
      </div> <!--fin de modal dialog -->
    </div> <!-- fin de modal fade (dice q esta de mas esta etiqueta-->