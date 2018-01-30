if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery')
}
/********************************************************/
function esNumero(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
/******************************************************/
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
/*****************************************************/
function getRandomChars(lon){
chars = "abcdefABCDEF";
code = "";
for (x=0; x < lon; x++)
{
rand = Math.floor(Math.random()*chars.length);
code += chars.substr(rand, 1);
}
return code;
}
/*************************************************************/
function formatoNumero(numero, decimales, separadorDecimal, separadorMiles) {
    var partes, array;

    if ( !isFinite(numero) || isNaN(numero = parseFloat(numero)) ) {
        return "";
    }
    if (typeof separadorDecimal==="undefined") {
        separadorDecimal = ".";
    }
    if (typeof separadorMiles==="undefined") {
        separadorMiles = ",";
    }

    // Redondeamos
    if ( !isNaN(parseInt(decimales)) ) {
        if (decimales >= 0) {
            numero = numero.toFixed(decimales);
        } else {
            numero = (
                Math.round(numero / Math.pow(10, Math.abs(decimales))) * Math.pow(10, Math.abs(decimales))
            ).toFixed();
        }
    } else {
        numero = numero.toString();
    }

    // Damos formato
    partes = numero.split(".", 2);
    array = partes[0].split("");
    for (var i=array.length-3; i>0 && array[i-1]!=="-"; i-=3) {
        array.splice(i, 0, separadorMiles);
    }
    numero = array.join("");

    if (partes.length>1) {
        numero += separadorDecimal + partes[1];
    }

    return numero;
}
/****************************************************************/
function deleteRowCrudPermiso(id,r){ // en desuso
	//var i = r.parentNode.parentNode.rowIndex;
	//if (confirm('Seguro Eliminar el Elemento')){
	//	document.getElementById("idTablaCrudPermisos").deleteRow(i);
		//$("#tablaDetalleMuestras" + i).remove();   
		//$("#rowDetalle_" + oId).remove();   
		//alert( "entrando " + i);
	//}
}
/***************************************************************/
function deleteRowDetalladoIdrMetales(r){
	var i = r.parentNode.parentNode.rowIndex;
	var metal = $(r).parents("tr").find("td").eq(0).html();
	if (confirm('Seguro Eliminar el Metal ['+metal+']')){
    		document.getElementById("idTablaIDRMetales").deleteRow(i);    		
    }
}
/***************************************************************/
function EditaRowDetalladoIdrMetales(thisButton){	
		 
	//var i = r.parentNode.parentNode.rowIndex;
	//var r = r.parentNode.parentNode.parentNode;
	var metal 			= $(thisButton).parents("tr").find("td").eq(0).html();
	var resultado 		= $(thisButton).parents("tr").find("td").eq(1).html();
	var lc_metal 		= $(thisButton).parents("tr").find("td").eq(2).html();
	var lmp_metal 		= $(thisButton).parents("tr").find("td").eq(3).html();
	var tecnica_metal 	= $(thisButton).parents("tr").find("td").eq(4).html();	
	
	// SE LES CARGA EN SU VENTANA MODAL CON LAS NUEVAS	
	/*
	$cResultado2 	= array('id'=>'idResultado_Metal2','class'=>'form-control' ,'value' => set_value('idResultado_Metal2'));
	$cLC2 			= array('id'=>'idLC_Metal2','class'=>'form-control' ,'value' => set_value('idLC_Metal2'));
	$cLMP2 			= array('id'=>'idLMP_Metal2','class'=>'form-control' ,'value' => set_value('idLMP_Metal2'));
	$cTecnica2 		= array('id'=>'idMetal2','class'=>'form-control' ,'value' => set_value('idMetal2'));
	$cMetal2 		= array('id'=>'idMetal2','class'=>'form-control' ,'value' => set_value('idMetal2'),'readonly'=>true);
	*/	
	
	//alert(lc_metal);
	//alert(lmp_metal);
	//alert(tecnica_metal);
	
	$("#idMetal2").val(metal);
	$("#idResultado_Metal2").val(resultado);
	$("#idLC_Metal2").val(lc_metal);
	$("#idLMP_Metal2").val(lmp_metal);
	$("#idTecnica_Metal2").val(tecnica_metal);
	
	$("#exampleModal").modal("show");	
}// fin de funcion EditaRowDetalladoIdrPlagicidas(this)
/***************************************************************/

/******************************************************************/
function deleteRowDetalladoIdrPlagicidas(r){
	var i = r.parentNode.parentNode.rowIndex;
	var analito = $(r).parents("tr").find("td").eq(0).html();
	if (confirm('Seguro Eliminar el Analito ['+analito+']')){
    		document.getElementById("idTablaIDRPlaguicidas").deleteRow(i);    		
    }
}
/******************************************************************/

function EditaRowDetalladoIdrPlagicidas(thisButton){	
	//var i = r.parentNode.parentNode.rowIndex;
	//var r = r.parentNode.parentNode.parentNode;
	var analito 		= $(thisButton).parents("tr").find("td").eq(0).html();
	var resultado 		= $(thisButton).parents("tr").find("td").eq(1).html();
	var lc_analito 		= $(thisButton).parents("tr").find("td").eq(2).html();
	var lmp_analito 	= $(thisButton).parents("tr").find("td").eq(3).html();
	var tecnica_analito = $(thisButton).parents("tr").find("td").eq(4).html();	
	
	// SE LES CARGA EN SU VENTANA MODAL CON LAS NUEVAS 
	$("#idAnalito2").val(analito);
	$("#idResultado_analito2").val(resultado);
	$("#idLC_analito2").val(lc_analito);
	$("#idLMP_analito2").val(lmp_analito);
	$("#idTecnica2").val(tecnica_analito);
	
	$("#exampleModal").modal("show");	
}// fin de funcion EditaRowDetalladoIdrPlagicidas(this)
/***************************************************************/
function deleteRowDetalladoResultado(r){ 
	var i = r.parentNode.parentNode.rowIndex;
	var elemento = $(r).parents("tr").find("td").eq(0).html();
	if (confirm('Seguro Eliminar el Elemento ['+elemento+']')){
    		document.getElementById("tablaDetalleResultados").deleteRow(i);    		
    	}
}
/******************************************************************/
function EliminaAnalitoTabla(r ){ // REPETIDO EN deleteRowDetalladoIdrPlagicidas()
	var i = r.parentNode.parentNode.rowIndex;
	var elemento = $(r).parents("tr").find("td").eq(0).html();
	if (confirm('Seguro Eliminar el Analito ['+elemento+']')){
    	document.getElementById("idTablaIDRPlaguicidas").deleteRow(i);    		
    }
}
/******************************************************************/
function deleteRowDetalladoEstudio(r,cPrueba) { // BORRA EL ROW DE LA TABLA DE RESULTADOS EN EL INFORME DEL MISMO..!
    var i = r.parentNode.parentNode.rowIndex;
    var elemento = $(r).parents("tr").find("td").eq(0).html();
    
    if (confirm('Seguro Eliminar el Elemento' + cPrueba + elemento)){
    	document.getElementById("tablaDetalleMuestras").deleteRow(i);    		
    }
}
/***********************************************************************/
function DuplicaRowDetalladoEstudio( r ,nDuplicado ) { // DUPLICA UN RENGLON (SOLICITUD DE SERVI ELTON METODOLOGIA HACIA UNA VENTANA MODAL SI DUPLICA = 1 ES ALTA SINO ES EDICION
	//if (confirm('Duplicar Id de la Muestra')){

		var i = r.parentNode.parentNode.rowIndex;
		
		console.log('Entradno a la Funcion Dulplica Row Detallado de Estudio');		
		console.log('Indice de la tabla [tablaDetalleMuestras] ['+i);// se refiere a la tabla //<table class="table" id="tablaDetalleMuestras" border="1">
				
		var tabla = document.getElementById('tablaDetalleMuestras');		
		
		var id_muestra = tabla.rows[i].cells[0].innerHTML;
		var id_cliente = tabla.rows[i].cells[1].innerHTML;
		
		var tipo_muestra = tabla.rows[i].cells[2].innerHTML; //22/06/2017
		var peso_vol_muestra = tabla.rows[i].cells[3].innerHTML;
		var temp_muestra = tabla.rows[i].cells[4].innerHTML;
		
		var lote = tabla.rows[i].cells[5].innerHTML;		
		var metodologia_muestra = tabla.rows[i].cells[8].innerHTML;
		
		var id = tabla.rows[i].cells[7].innerHTML; // id de el ensayo o estudio
			
		//alert(id_muestra)	;
		//console.log(id_muestra);	
		
		$('[name="id_muestra"]').val(id_muestra); // POR POBNER UN EJEMPLO
		$('[name="id_cte"]').val(id_cliente);		
		
		//22/06/2017
        $('[name="tipo_muestra"]').val(tipo_muestra);
        $('[name="peso_volumen_muestra"]').val(peso_vol_muestra);
        $('[name="temperatura_muestra"]').val(temp_muestra);
		
		//$('[name="desc_muestra"]').val(des_muestra);		
		$('[name="lote"]').val(lote);		
		
		//22/06/2017
		//alert('preparado para el cambio');	
		
		
		//2017-08-10 --> para cuando es edicion el cbo_estudo no debe permitir que sea corregible y desplazarnos al que debe de ser
		
		var accion = $("#panel-encabezado").html();
		console.log( 'Accion = ['+accion+']');
		
		$("#cbo_estudio").removeAttr('disabled');
		$("#myModalLabelMetodologia").text('Agregar Ensayo(s)');
		
		if (nDuplicado==1) { // duplicar
			$('[name="cbo_estudio"] option:last-child').attr('selected', 'selected'); // SELECCIONA EL ULTIMO DE LA LISTA QUE ES Seleccione un estudio
			$("#btnAddEstudioTabla").val('Duplicar Ensayo');
			$("#myModalLabelMetodologia").text('Duplicar Muestra');
		}else { // 0 es igual a editar
			//$('[name="cbo_estudio"] option:last-child').attr('selected', 'selected'); // SELECCIONA EL ULTIMO DE LA LISTA QUE ES Seleccione un estudio
			$("#cbo_estudio").val(id).change();
			if (accion.substr(0,8) == 'Consulta') {
				$("#cbo_estudio").attr('disabled', 'disabled');
				console.log('Desabilitando el combo de las Metodologias por tratarse de una Edición');				
			}				
			$("#btnAddEstudioTabla").val('Editar Ensayo;'+i);// y es para saber q indice de la tabla modificare
			$("#myModalLabelMetodologia").text('Editar Ensayo');			
		}
		
		console.log('Saliendo de la Funcion Dulplica Row Detallado de Estudio');
	//}
}
/************************************************************/
function SeleccionaRegistro( valor, arreglo ){// viene del controlador clientes y buscar clientes
	// queda pendiente pasarla a jquery
	//var i = valor.parentNode.parentNode.parentnode.rowIndex;
	//alert(arreglo);

	if (confirm('Seleccionar al cliente ['+valor.value+']' )){		
		document.getElementById("id_cliente").value = arreglo[0]; //id
		document.getElementById("nombre_cte").value = arreglo[1]; // nombre
		document.getElementById("direccion_cte").value = arreglo[2]; // direccion
		document.getElementById("rfc_cte").value = arreglo[3]; // rfc
		document.getElementById("email_cte").value = arreglo[4]; // Email
		document.getElementById("telefono_cte").value = arreglo[5]; // telefono
		document.getElementById("contacto_cte").value = arreglo[6]; // contacto
		//24/05/2017
		console.log('Valor de  Array que regresa el seleccionar un cliente');
		console.log(arreglo);
		
		if (arreglo[7]!=null){
			document.getElementById("nombre_idr_cliente").value = arreglo[7]; // nombre
		} 		
		if (arreglo[8]!=null) {
			document.getElementById("domicilio_idr_cliente").value = arreglo[8]; // contacto
		}
		if (arreglo[9]!=null){
			document.getElementById("rfc_idr_cliente").value = arreglo[9]; // contacto
		}
		if (arreglo[10]!=null){
			document.getElementById("contacto_idr_cliente").value = arreglo[10]; // contacto
		}	
				
		//OCULTANDO EL DIV response_search		
		document.getElementById('response_search').innerHTML = "";
	}		
}


/***********************A Q U I  E M P E Z A M O S  L O  D E  E L  J Q U E R Y*****************************/
$(function () {
	
	//alert($.fn.jquery);
	
	$("#ajax-loading").hide(); // ocultando la imagen de carga del ajax
		
		
		
	/* RECIEN AGREGANDO LO DE EL AJAX START */
	
	$(document).ajaxStart(function() {	  
	  $("#ajax-loading").show();	 
	  });

	$(document).ajaxStop(function() {		
	  	$("#ajax-loading").hide();	    
	  });

	$.ajaxSetup({

	  error: function( jqXHR, textStatus, errorThrown ) {
	  		//console.log("Errores encontrados");
	  		console.log('TextStatus Error ='+textStatus);
	  		console.log('errorThrow Error ='+errorThrown);
	  		console.log(jqXHR);

	          if (jqXHR.status === 0) {

	            alert('Not connect: Verify Network.');

	          } else if (jqXHR.status == 404) {

	            alert('Requested page not found [404]');

	          } else if (jqXHR.status == 500) {

	            alert('Internal Server Error [500].');

	          } else if (textStatus === 'parsererror') {

	            alert('Requested JSON parse failed.');

	          } else if (textStatus === 'timeout') {

	            alert('Time out error.');

	          } else if (textStatus === 'abort') {

	            alert('Ajax request aborted.');

	          } else {

	            alert('Uncaught Error: ' + jqXHR.responseText);

	          }

	        }
	}); // fin del ajax setup
	
		
	// ocultar columna de una tabla    
    //$("#tablaDetalleMuestras tr").find('th:eq(6)').addClass("hidden");
    //$("#tablaDetalleMuestras tr").find('th:eq(7)').addClass("hidden");
    
    $("#tablaDetalleMuestras td:nth-child(7), #tablaDetalleMuestras th:nth-child(7)").hide();  
    $("#tablaDetalleMuestras td:nth-child(8), #tablaDetalleMuestras th:nth-child(8)").hide();  
	
	var cargando = $("#ajax-loading");  // para el efecto del ajax
	var getUrl = window.location;
	var baseUrlCorta = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	var baseUrlCortaCorta = getUrl .protocol + "//" + getUrl.host + "/";
	
	if(baseUrlCortaCorta == 'http://localhost/') {
		baseUrlCortaCorta += 'recepcion/';
		alert('Trabajando de Manera Local');
	}
		
	 $('[data-toggle="tooltip"]').tooltip(); //activando los tooltips
	 /************************************************************/
	 $('#idTablaCrudEstudiosGeneral').DataTable({ // crud de clientes
        "language": {
            "lengthMenu": "Mostrar _MENU_ Registros por Pagina",
            "zeroRecords": "Nada para mostrar - reintente",
            "info": "Mostrando _PAGE_ de _PAGES_ paginas",
            "infoEmpty": "No información Disponible",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sLoadingRecords": "Cargando...",
            "search":"Buscar:",
            "paginate": {
              "first":      "Primero",
              "last":       "Último",
              "next":       "Siguiente",
              "previous":   "Anterior"   },
        },
       stateSave: true,
       'iDisplayLength': 50        
      }); // fin de datatable
	/* ******************************************************************/
	 $('#idTablaPrueba').DataTable({ // crud de clientes
        "language": {
            "lengthMenu": "Mostrar _MENU_ Registros por Pagina",
            "zeroRecords": "Nada para mostrar - reintente",
            "info": "Mostrando _PAGE_ de _PAGES_ paginas",
            "infoEmpty": "No información Disponible",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sLoadingRecords": "Cargando...",
            "search":"Buscar:",
            "paginate": {
              "first":      "Primero",
              "last":       "Último",
              "next":       "Siguiente",
              "previous":   "Anterior"   },
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
       stateSave: true,
       'iDisplayLength': 50        
      }); // fin de datatable
	 /****************************************************************/
	 $(".voboIDR").click(function(){ // aplica a todas las clases del crud
	 
	 	var metodo_nombreidr = $(this).val();
	 	var nPos = metodo_nombreidr.indexOf(';');	 	
	 	var idMetodo = metodo_nombreidr.substr(0,nPos);
	 	var nombre_idr = metodo_nombreidr.substr(nPos+1);
	 	
	 	//idColumnaStatusMuestraAQ-PM001-0037
	 	
	 	//alert(idMetodo);
	 	var ColumnaStatusMuestra = 'idColumnaStatusMuestra'+idMetodo;
	 	//var ColumnaStatusMuestra = ra'+idMetodo;
	 	if (confirm("Enviar Correo de Conclusión IDR ["+idMetodo +"]")) {
	 		// ENVIAR CORREO DE VOBO (FINALIZACION Y VERIFICACION DE ESA METODOLOGIA)
	 		var datos = { 'id_metodologia': idMetodo , 'status_muestra':'F','nombre_idr':nombre_idr};
			$.ajax({
				type: 'POST',
				url: baseUrlCortaCorta+ "/envia_correo",
				data:datos,
				success: function(Resp_Ok){
					$(this).hide();
					if (document.getElementById(ColumnaStatusMuestra).innerHTML) {
						document.getElementById(ColumnaStatusMuestra).innerHTML = "FINALIZADA";
					}					 
					var cLeyenda = 'Id [' +idMetodo+'] Envió Correo de Confirmación';
					cAlerta = '<div class="alert alert-success" role="alert">' + cLeyenda + '<br/>Refresque la Pantalla para visualizar el Cambio</div>';
					console.log('Entrando a es voboIDR class');
					console.log(Resp_Ok);				
					console.log('saliendo de es voboIDR class');					
					document.getElementById('msg_alert_full').innerHTML = cAlerta;					
					//alert(Resp_Ok);					
					alert(cLeyenda);
					//location.reload(true);										
				}
			}) // FIN DEL AJAX---!
	 		
	 		
	 	} // fin del if confirm
	 });
	 /****************************************************************/
	 $(".esProcesable").click(function (){// viene del crud de analisis v_crud_estudios para adjudicarse el estudio y que ya no pueda ser modificable por aurea
		var cIdMuestra = $(this).val();
		var cIdBoton = $(this).attr('id');	
				
		var ColumnaStatusMuestra = 'idColumnaStatusMuestra'+cIdMuestra;
		//alert("Valor de la columna " + ColumnaStatusMuestra);
		if (confirm("Iniciar Etapa de Analisis ["+cIdMuestra+"]")) {
			// actualizar el estatus de  la muestra
			var datos = { 'id_muestra': cIdMuestra , 'status_muestra':'P'};			
			$.ajax({
				type: 'POST',
				url: baseUrlCortaCorta+ "/adjudica_muestra_solicitud",
				data:datos,
				success: function(Resp_Ok){
					$(this).hide();
					document.getElementById(ColumnaStatusMuestra).innerHTML = "EN PROCESO";
					cAlerta = '<div class="alert alert-success" role="alert">' + 'Id [' +cIdMuestra+'] Inicio Etapa de Analisis' + '</div>';
					console.log('Entrando a es procesable class');
					console.log(Resp_Ok);					
					console.log('saliendo de es procesable class');					
					document.getElementById('msg_alert_full').innerHTML = cAlerta;					
				}
			}) // FIN DEL AJAX---!
		}// FIN DEL CONFIRM
	 })
	 /************************************************************/
	 $(".esCancelable").click(function(){ // es cuando se pulsa desde el crud sin modal el boton eliminar
	 	// hay q actualizar los valores de los campos en la ventana modal que va aparecer despues de esto
	 	//id_Muestra //idObs_Cte
	 	var cIdMuestra = $ (this).val();
	 	$("#id_Muestra").val(cIdMuestra);
	 	//alert('Entro a la funcion en clase de la tabla esCancelable');
	 	$("#idObs_Cte").val('');
	 	//console.log( this);	 	
	 	// aqui pudieramos mediante ajax ir por la observacion ala base de datos..!	 	
	 })
	 /**********************************************************/
	 $("#idBtnCancelaEstudio").click(function (){ // viene del crud modal de estudio cancelacion
	 	// senecesita saber el id de la muestra y el campo de observacion
	 	var cObs =$("#idObs_Cte").val(); // la obseracion del cte
	 	//alert ( 'Observacion = ' + cObs);
	 	if (!cObs) {
	 		//document.getElementById('msg_alerta_modal').innerHTML = '<div class="alert alert-success" role="alert">' + 'Se Debe Especificar la razon de la Cancelación' + '</div>';	 		
	 		$('#msg_alerta_modal').html('<div class="alert alert-danger" role="alert">' + 'Se Debe Especificar la razon de la Cancelación' + '</div>');
	 	}else {
	 		//document.getElementById('msg_alerta_modal').innerHTML = '';
	 		$('#msg_alerta_modal').html('');
	 		var cIdMuestra = $("#id_Muestra").val();
		 	var datos = {  'id_muestra' : cIdMuestra, 'observaciones_muestra': cObs} ;
		 	var ColumnaStatusMuestra = 'idColumnaStatusMuestra'+cIdMuestra;
		 	$.ajax({
		 		type: 'POST',
				url: baseUrlCorta+ "/cancela_muestra_solicitud", 
				data: datos,
				success: function(){
					// recargar la pagina
					//$("#myModal").empty();
					
					document.getElementById('idBtnCloseModal').click();
					//document.getElementById(ColumnaStatusMuestra).innerHTML = "CANCELADO";				
					//cAlerta = '<div class="alert alert-success" role="alert">' + 'Id [' +cIdMuestra+'] Cancelado con Exito' + '</div>';
					cAlerta = '<div class="alert alert-success" role="alert">' + 'Id [' +cIdMuestra+'] Cancelado con Exito (refresque para visualizar)' + '</div>';
					document.getElementById('msg_alert_full').innerHTML = cAlerta;
					//$("#msg_alert_full").fadeout(3500);
					//alert('Anulado de la Muestra Realizada con Exito');

				}
		 	})// fin del ajax
		 } // fin del empty
	 })

	/***************************************************/
	$('#table_id_clientes').DataTable({ // crud de clientes	
         "language": {
            "lengthMenu": "Mostrar _MENU_ Registros por Paginaaa",
            "zeroRecords": "Nada para mostrar - reintente",
            "info": "Mostrando _PAGE_ de _PAGES_ paginas",
            "infoEmpty": "No información Disponible",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sLoadingRecords": "Cargando...",
            "search":"Buscar:",
            "paginate": {
              "first":      "Primero",
              "last":       "Último",
              "next":       "Siguiente",
              "previous":   "Anterior"   },
        },
	    "columnDefs": [
                    {
                        "targets": [ 5,6,7 ], // Estado / Telefono / Correo
                        "visible": false,
                        "searchable": false
                    }                    
                ],
       stateSave: true,
       'iDisplayLength': 50       
      }); // fin de datatable
	/**************************************************/
	/**************************************************/
	$(".esBorrable").click(function(){				
		var i = this.parentNode.parentNode.rowIndex;
		var row = this.parentNode.parentNode;
		var cId = $(this).attr('value');
		//var cUserName = $("tr td")[2].innerHTML;
		//var cUserName = $("this.parentNode.parentNode td")[2].innerHTML;

		//console.log( children);
		
		if (confirm('Seguro Eliminar el Elemento Seleccionado ')){
			// generamos el ajax para borrarlo
			var datos  ={ 'id_permisos_x_usuario':cId};
			$.ajax({
				type: 'POST',
				url: baseUrlCortaCorta+ "/elimina_permiso", //elimina en e crud de permisos
				data: datos,
				success: function(){ 					
					document.getElementById("idTablaCrudPermisos").deleteRow(i); 
				},
				error: function(){
					alert("Imposible Eliminar al Elemento");
				}

			})// fin del ajax

    	} // fin del confirm
	})	
	/********************************************************/
	// CRUD DE PERMISOS COMIENZA
	$("#idBtnAgregarPermiso").click(function (){
		var cUser = $("#idUser").val();
		var cMod = $("#idModulo").val();
		var cPer = $("#idPermiso").val();

		var datos  ={ 'id_usuario':cUser, 'id_modulo': cMod, 'id_permiso': cPer};

		$.ajax({
				type: 'POST',
				url: baseUrlCortaCorta+ "/inserta_permiso", //inserta en e crud de permisos
				data: datos,
				success: function(cTabla){ 
					//alert(cTabla);
					location.reload();
				},
				error: function(algo){
					alert('Ocurrio un error al intentar Grabar la información a la base de Datos; Revisa la Consola para mas información');						console.log(algo);
					//alert(algo);
					$('#ajax-loading').html('<div><img src=""/></div>');
				}
		})		
	})
	/******************************************************/	
	$('#idTablaCrudPermisos').DataTable({ // crud de permisos	
        "language": {
            "lengthMenu": "Mostrar _MENU_ Registros por Pagina",
            "zeroRecords": "Nada para mostrar - reintente",
            "info": "Mostrando _PAGE_ de _PAGES_ paginas",
            "infoEmpty": "No información Disponible",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sLoadingRecords": "Cargando...",
            "search":"Buscar:",
            "paginate": {
              "first":      "Primero",
              "last":       "Último",
              "next":       "Siguiente",
              "previous":   "Anterior"   },
        },
       stateSave: true,
       iDisplayLength: 50       
    }); // fin de datatable     
	/*****************************************************/ 
	
	$("#btnAddDatosIDRPlagicidas").click(function(){
		//alert('entro');

		var cResultado 	= $("#idResultado_analito2").val();			
		var cLC 		= $("#idLC_analito2").val();
		var cLMP 		= $("#idLMP_analito2").val();
		var cTecnica 	= $("#idTecnica2").val();		
		 
        //obtenemos el valor insertado a buscar
		var cAnalito 	= $("#idAnalito2").val(); // campo de busqueda 
        //utilizamos esta variable solo de ayuda y mostrar que se encontro
        encontradoResultado=false;
 
        //realizamos el recorrido solo por las celdas que contienen el código, que es la primera
        $("#idTablaIDRPlaguicidas tr").find('td:eq(0)').each(function () {
 
             //obtenemos el codigo de la celda
              codigo = $(this).html();
 
               //comparamos para ver si el código es igual a la busqueda
               if(codigo==cAnalito){
 
                    //aqui ya que tenemos el td que contiene el codigo utilizaremos parent para obtener el tr.
                    trDelResultado=$(this).parent();                  
 
                    //ya que tenemos el tr seleccionado ahora podemos navegar a las otras celdas con find
                                      
                    trDelResultado.find("td:eq(1)").html(cResultado);
                    trDelResultado.find("td:eq(2)").html(cLC);
                    trDelResultado.find("td:eq(3)").html(cLMP);
                    trDelResultado.find("td:eq(4)").html(cTecnica);
 
                    //mostramos el resultado en el div
                    //$("#mostrarResultado").html("El nombre es: "+nombre+", la edad es: "+edad)
 
                    encontradoResultado=true; 
               } 
        })
 
        //si no se encontro resultado mostramos que no existe.
        if(!encontradoResultado) {
			alert('no se encontro el analito ['+cAnalito +']');
		}
        //$("#mostrarResultado").html("No existe el código: "+buscar)		
	})
	/***********************************************************/
	$("#button_datos_idr_plagicidas").click(function(){ // viene de servicios de elton, verificar haya capturado informacion de cliente para poderse activar.
		alert('Seleccione Cliente Primero');		
				
	});
	/**********************************************************/
	$(".claseAlgo").click(function (){
			//alert('algo');
			$("#idResultado_analito").val('NUEVO');
			$("#exampleModal").modal("show");
		});
		
	 /**********************************************************/
	$(".tablaIdrPlagicidasEdit").click( function(){
		var i = this.parentNode.parentNode.rowIndex;
		var row = this.parentNode.parentNode;
		var cId = $(this).attr('value');
		alert('entro a intentar eliminar el renglon de la tabla');
		if (confirm('Seguro Eliminar el Elemento Seleccionado ')){
			alert('hay que eliminarlo');
		}		
		//$("#tablaDetalleMuestras tr").find('th:eq(7)').addClass("hidden");
	}); // fin de clase .tablaIdrPlagicidasEdit
	/*************************************************************/
	$(".btnEliminaAnalitoTabla").click(function(){
		//var id = (this).val();
		//alert('se pulso');
	}) // fin de class btnEliminaAnalitoTabla
	/*************************************************************/
	//INFORME DE RESULTADOS VISTA
	$("#BtnAgregaAnalitoTabla").click(function(){
		
		//obtener las variables que participan
		var iniciales  = $("#idInicialesAnalista").val();
		//alert(iniciales);		
		
		var cAnalito = $("#idAnalitoCombo option:selected").text();
		var cResultado =  $("#idResultado_analito").val();			
		var cLC = $("#idLC_analito").val();
		var cLMP = $("#idLMP_analito").val();
		var cTecnica = $("#idTecnica").val();

		if ( !cResultado ) { alert('Especificar Resultado Permisible');
		}else { 

			if (confirm('Anexar el Resultado ['+cAnalito+'/'+cResultado+'/'+cLC+'/'+cLMP+'/'+cTecnica+']')){			

				cHtml = '<tr id="'+getRandomChars(6)+'">';
				cHtml += '<td>'+cAnalito+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td>'+'</td><td>'+cTecnica+'</td>';
				cHtml += '<td>';				
				cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
				//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
				cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
								
				//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs esModificable"><span class="glyphicon glyphicon-pencil"></span></button>';
				//cHtml += '<button type="button" class="btn btn-info btn-xs esModificable"><span class="glyphicon glyphicon-pencil"></span></button>';
				//cHtml += '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Launch demo modal</button>';
				cHtml += '</td>';
				cHtml += '</tr>';				

				if ($('#idTablaIDRPlaguicidas >tbody >tr').length == 0){
		 			$("#idTablaIDRPlaguicidas").append("<tbody></tbody>");
		 				//alert("Agregando un tbody a la tabla");
				}
				$("#idTablaIDRPlaguicidas tbody").append(cHtml);
				$("#idResultado_analito").val("ND");
				// cambiar el analito
				$('#idAnalitoCombo option:selected').next().attr('selected', 'selected');

    			
				$("#idResultado_analito").focus();
			} // fin del confirm
		}// fin del if !cResultado
	})
	// *******************************************************************
	$("#BtnAgregaAnalitosAcreditadosTabla").click(function(){ // agrega todos los analitos a la tabla
		//obtener las variables que participan USANDO UN AJAX
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_analitos_acreditados",
			success: function(htmlResponse){
				var objAnalitos = htmlResponse['RESULTADO'];				
				//console.log(objAnalitos);
				
				$.each(objAnalitos,function(i,contenido){			
					
					console.log(contenido);
					
					var cAnalito 	= objAnalitos[i].NOMBRE_ANALITO;					
					var cResultado 	= objAnalitos[i].RESULTADO_ANALITO;
					var cLC 		= objAnalitos[i].LC_ANALITO;
					var cLMP 		= objAnalitos[i].LMP_ANALITO;
					var cTecnica	= objAnalitos[i].TECNICA_ANALITO;
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cAnalito+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs tablaIdrPlagicidasEdit"  ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" data-togle="ModalAnalitoCorreccion" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRPlaguicidas >tbody >tr').length == 0){
			 			$("#idTablaIDRPlaguicidas").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRPlaguicidas tbody").append(cHtml);
					
					
				});			
			},				
						
		}); // fin del ajax	
	
		
	}) // fin de BtnAgregaTodosAnalitosTabla
	/* ************************************************************************/	
	$("#BtnAgregaTodosAnalitosTabla").click(function(){ // agrega todos los analitos a la tabla
		//obtener las variables que participan USANDO UN AJAX
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_analitos",
			success: function(htmlResponse){
				var objAnalitos = htmlResponse['RESULTADO'];				
				//console.log(objAnalitos);
				
				$.each(objAnalitos,function(i,contenido){			
					
					console.log(contenido);
					
					var cAnalito 	= objAnalitos[i].NOMBRE_ANALITO;					
					var cResultado 	= objAnalitos[i].RESULTADO_ANALITO;
					var cLC 		= objAnalitos[i].LC_ANALITO;
					var cLMP 		= objAnalitos[i].LMP_ANALITO;
					var cTecnica	= objAnalitos[i].TECNICA_ANALITO;
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cAnalito+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs tablaIdrPlagicidasEdit"  ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" data-togle="ModalAnalitoCorreccion" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRPlaguicidas >tbody >tr').length == 0){
			 			$("#idTablaIDRPlaguicidas").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRPlaguicidas tbody").append(cHtml);
					
					
				});			
			},				
						
		}); // fin del ajax	
	
		
	}) // fin de BtnAgregaTodosAnalitosTabla
	/**********NUEVO SISTEMA DE IDRS INDEPENDIENTES***********/
	$(".GrabaIDRPlaguicidas").click(function(){
		
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var accion 			= $(this).val(); // toma el value del boton que llamo a este procedimiento // ALTA o EDICION
			
		
		//var iniciales 	= $("#idInicialesAnalista").val();
		var fechafinal	= $("#idFechaFinal").val();		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();
		var analisis 	= $("#idAnalisisSolicitado").val();
		var metodo 		= $("#idMetodoPrueba").val();
		
		//2017-07-10
		//obteniendo el nombre y cargo de quien este como signataria seleccionada del combo que les puse nuevo
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		
		var idIDR 			= 0;
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
		
		//2017-07-20
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		//2017-08-28
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr
		
		
		$nFilas = $("#idTablaIDRPlaguicidas >tbody >tr").length;
		
		// pasos previos a la validacion..!		
		if (!idUserSignatario){
			alert('Seleccionar usuario Signatario');
		}else if (accion == 'EDICION'  && !causas) {
			alert('Debe Indicar las Causas de porque se hizo la corrección ...!');			
		} else if ( $nFilas == 0 ){
			alert('Informacion incompleta en el Resultado (Debe Especificar Analitos al IDR)');
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Microbiologia')) {
				// EMPIEZA LO BUENO AJUA..!
				//recorrer las filas de las tablas y ponerlas en un array				
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_mercurio':metodo,'referencia_mercurio':referencia,'observacion_mercurio':obs,'condiciones_mercurio':condiciones,'resultado_mercurio':resultado,'fecha_final_mercurio':idFechaFinal,'iniciales_analista_mercurio':iniciales_analista,'lc_mercurio':lc, 'lmp_mercurio':lmp,'tecnica_mercurio':tecnica,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				  var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'fecha_final_plaguicidas':fechafinal,'iniciales_analista_plaguicidas':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas},det:[]}
				
				var detallado = new Array();
				var nPos = 0;
				$('#idTablaIDRPlaguicidas tr').each(function () {

					var analito = $(this).find("td").eq(0).html();
					var resultado = $(this).find("td").eq(1).html();
					var lc = $(this).find("td").eq(2).html();
					var lmp = $(this).find("td").eq(3).html();
					var tecnica = $(this).find("td").eq(4).html();
					if (analito) {
						detallado.push( analito,resultado,lc,lmp,tecnica);	
					}			

				});
				console.log( detallado);
				data.det.push(detallado);		
				
				console.log(data);
				//alert('Entrando al ajax para grabar el idr de plagicidas');
				console.log('Entrando al ajax para grabar el idr de plagicidas');
				//dataType: "json",
				$.ajax({			
					data: data,
					method: 'POST',
					url: baseUrlCortaCorta+'/graba_idr_Plagicidas',
					success: function (htmlResponse){
						console.log('entro a la funcion sucesso de grabar idr_plagicidas');
						console.log(htmlResponse);
						//alert(htmlResponse);
						
						if (htmlResponse['SITUACION_REGISTRO']=='EXITO'){
							$("#divBtnGrabaIDRPlaguicidas").hide();
							alert('Informe de Resultado Grabado');
						}
						
					},
				}); // fin del ajax
				//alert('saleidno del ajax para grabar idr de plagicidas');
				console.log('saleidno del ajax para grabar idr de plagicidas');
			
			}  // FIN DEL CONFIRM
		}// FIN DEL IF PASOS PREVIOS A LA VALIDACION
				
		
	});
	/**********************************************************/
	$("#idBtnGrabaIDRAflatoxina").click(function(){			    
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var  resultado 		= $("#idResultado_Aflatoxinas").val();
		var  g1				= $("#idG1").val();
		var  g2				= $("#idG2").val();
		var  b1				= $("#idB1").val();
		var  b2				= $("#idB2").val();
		var  lc				= $("#idLC").val();
		var  ch				= $("#idCH").val();
		var  ca				= $("#idCA").val();
		var  metodo 		= $("#idMetodoPrueba").val();
		var  analisis 		= $("#idAnalisisSolicitado").val();
		var  referencia		= $("#idReferencia").val();
		var  obs			= $("#idObsResultado").val();
		var  condiciones	= $("#idCondMuestra").val();
		//29/06/2017
		//var  iniciales 		= $("#idInicialesAnalista").val();
		
		//2017-07-17	
		//obteniendo el nombre y cargo de quien este como signataria seleccionada del combo que les puse nuevo
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		
		
		var idIDR 			= 0;
		//var lParaValidacion = $("#idParaValidacion").val();
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
				
		// pasos previos a la validacion..!	
		if (!resultado){
			alert('Informacion incompleta en el Resultado..;')	
		}else {
			
			if (confirm('Proceder con el Grabado del IDR para Aflatoxinas')) {
				// EMPIEZA LO BUENO AJUA..!
				var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observacion_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado,'id_usuario_signatario':idUserSignatario,'fecha_aflatoxinas':idFechaFinal,'iniciales_analista_aflatoxinas':iniciales_analista  };
				console.log('mandando grabar el idr aflatoxinas');
				console.log(datos);		
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/graba_idr_aflatoxinas", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 
						// GRABAR EL DETALLADO AHORA
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						console.log('actualizando el status de id_metodologia');	
						//if (confirm('Finalizar Informe de Resultados ...')){
							$.get(baseUrlCortaCorta+"actualiza_status_metodologia",{'idMetodologia':idMetodologia},function(htmlResponse){
								$("#BtnGrabaIDRAflatoxina").hide();
								alert('Informe de Resultado Generado con Exito');
								console.log(htmlResponse);
								//alert(htmlResponse);							
							});
						//}
					}
				});
				
				console.log('saliendo de la funcion ibBtnGrabaIDRAflatoxina');
			}// fin de la confirmacion..!
		}// fin de las validaciones
	});	
/* *********************************************************/
	$("#idBtnActualizarIDRAflatoxina").click(function(){	    
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var  resultado 		= $("#idResultado_Aflatoxinas").val();
		var  g1				= $("#idG1").val();
		var  g2				= $("#idG2").val();
		var  b1				= $("#idB1").val();
		var  b2				= $("#idB2").val();
		var  lc				= $("#idLC").val();
		var  ch				= $("#idCH").val();
		var  ca				= $("#idCA").val();
		var  metodo 		= $("#idMetodoPrueba").val();
		var  analisis 		= $("#idAnalisisSolicitado").val();
		var  referencia		= $("#idReferencia").val();
		var  obs			= $("#idObsResultado").val();
		var  condiciones	= $("#idCondMuestra").val();
		//2017-07-25
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr

		//2017-07-17	
		//obteniendo el nombre y cargo de quien este como signataria seleccionada del combo que les puse nuevo
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		var idIDR 			= $("#idIDR").val(); // corregido
		
		//var lParaValidacion = $("#idParaValidacion").val();
		/*
		if ($("#idParaValidacion").is(':checked')) {  
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		*/		
				//alert(idTabla);
				//alert(idMetodologia);
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
				
		// EMPIEZA LO BUENO AJUA..!		
				
		// pasos previos a la validacion..!	
		if (!resultado){
			alert('Informacion incompleta en el Resultado...!');
		}else if (!causas){
			alert('Debe Indicar las Causas de porque se hizo la corrección...!');			
		}else if (idIDR == 0){
			alert('Se Requiere Contar con un Numero de Informe de Resultados (IDR)...!');			
		}else {
			
			if (confirm('Proceder con el Grabado del IDR para Aflatoxinas')) {
				// EMPIEZA LO BUENO AJUA..!
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observacion_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado,'id_usuario_signatario':idUserSignatario,'causas_correccion':causas,'id_aflatoxinas':idTabla};
				var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_aflatoxinas':analisis,'g1_aflatoxinas':g1,'g2_aflatoxinas':g2,'b1_aflatoxinas':b1,'b2_aflatoxinas':b2,'lc_aflatoxinas':lc,'ch_aflatoxinas':ch,'ca_aflatoxinas':ca,'metodo_prueba_aflatoxinas':metodo,'referencia_aflatoxinas':referencia,'observacion_aflatoxinas':obs,'condiciones_aflatoxinas':condiciones,'resultado_aflatoxinas':resultado,'id_usuario_signatario':idUserSignatario,'causas_correccion':causas,'id_aflatoxinas':idTabla,'fecha_aflatoxinas':idFechaFinal,'iniciales_analista_aflatoxinas':iniciales_analista  };
 				console.log('mandando grabar el idr aflatoxinas');
				console.log(datos);			
				
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/actualiza_idr_aflatoxinas", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 
						// GRABAR EL DETALLADO AHORA
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						alert('Informe de Resultado Actualizado con Exito');					
					}
				}); // fin del ajax
				
				console.log('saliendo de la funcion ibBtnActualizaGrabaIDRAflatoxina');
			}// fin de la confirmacion..!
		}// fin de las validaciones
		
		
	});	
	/********************************************************/
	$("#idBtnGrabarInformeResultados").click(function(){		
		// recopilacion de variables
		var idDetalleMuestra = $("#id_detalle_muestra").val();
		var dFechaAnalisis = $("#fecha_analisis").val();
		var cTCH = $("#idTCH").val();
		var cTCA = $("#idTCA").val();
		var cMetPrueba = $("#idMetodoPrueba").val();
		var cRefResultado = $("#idReferencia").val();
		var cObsResultado = $("#idObsResultado").val();
		var cCondMuestra  = $("#idCondMuestra").val();


		if ($('#tablaDetalleResultados >tbody >tr').length  <1){
				alert("Validacion de los Resultados incompleta");
		} else { // empieza lo bueno..!


			var datos={ 'id_detalle_muestra' : idDetalleMuestra, 'fecha_analisis' : dFechaAnalisis, 'tch_resultado' : cTCH, 'tca_resultado' : cTCA, 'metodo_prueba': cMetPrueba, 'referencia_resultado' : cRefResultado, 'observacion_resultado' : cObsResultado ,'condicion_muestra': cCondMuestra};

			console.log( datos );
			$.ajax({
				type: 'POST',
				url: baseUrlCortaCorta+ "/graba_encabezado_resultado", //grabamos la parte del encabezado regresa lastid
				data: datos,
				success: function(nLastIDResultado){ // GRABAR EL DETALLADO AHORA
					//alert("Ultimo id Anexado :" + nLastIDResultado);
					if (nLastIDResultado){
						$("#tablaDetalleResultados tbody tr").each(function (index) {
					            var campo1, campo2, campo3,campo4;
					            //alert("entro al detallado");
					            $(this).children("td").each(function (index2) 
					            {
					                switch (index2) 
					                {
					                    case 0: campo1 = $(this).text(); //Linfoncito
					                            break;
					                    case 1: campo2 = $(this).text(); //resultado
					                            break;
					                    case 2: campo3 = $(this).text(); //Cpo Aux1
					                            break;
					                    case 3: campo4 = $(this).text(); //Cpo Aux2
					                            break;					                   		                    
					                }
					                //$(this).css("background-color", "#ECF8E0");
					            })
					            // mandarlo grabar ahora					            
					            var data_detalle = {'id_enc_resultado':nLastIDResultado,'prueba_resultado':campo1,'resultado_resultado':campo2,'ch_resultado':campo3,'ca_resultado':campo4};
					            console.log( 'Entrando al Detallado del Resultado');
					            console.log( data_detalle);

					            //alert(campo1 + ' - ' + campo2 + ' - ' + campo3' - ' + campo4+' - ' + campo5+' - ' + campo6+' - ' + campo7+' - ' + campo8+' - ' + campo9);
					            $.ajax({
					            	type: 'POST',
									url: baseUrlCortaCorta+ "/graba_detallado_resultado", //grabamos la parte del encabezado
									data: data_detalle,              
									success: function (resp_ok4){
										// HAY Q GRABAR EL STATUS DE LA MUESTRA A F = FINALIZADA
										var datos2 = { 'id_detalle_muestra' : idDetalleMuestra };
										alert("Entrando a la Finalizacion de la Muestra" + datos2);
										console.log( 'Entrando a la finalizcion de la Muestra');
										console.log( datos2);
										$.ajax({
											data:datos2,
											type: 'POST',
											url : baseUrlCorta + "/finaliza_detallado_muestra", // STATUS = F 
											success: function(){
												console.log("Actualizado y Finalizada id Detallado : "+idDetalleMuestra);
												$("#divBtnImprimirInformeResultados").show();
												$("#divBtnGeneraInformeResultados").hide();
											},
											error: function(){
												alert("error al Finalizar el Detallado");
												console.log('Error al Finalizar el Detallado de la Grabada del Encabezado')
											}
										}) // fin del ajax de finalizacion..!										
									}
					            })
					        })

					}// fin del resp_ok3					
				},
				error: function ( resp_no_ok3){
					alert("<-- Al grabar el Detallado del Resultado -->");
				}
			})			


		}
	})	
	/*******************************************************************/
	/*$("#divBtnImprimirInformeResultados2").click(function(){ // creo que se debe eliminar esta abajito c btn
		var idMuestra = $("#id_muestra").val();
		var cRuta = baseUrlCortaCorta + '/impresiones_controller/InformeResultados/'+idMuestra;
		window.open( cRuta,'_blank');
	});*/
	/**********************************************************************/
	//$('a[name=modal]').click(function(e) {  
	$("#button_datos_idr").click(function(e){ // viene de servicios de elton, verificar haya capturado informacion de cliente para poderse activar.
		var id_cliente = $("#id_cliente").val();
		if (id_cliente){
			
		}else{
			e.preventDefault(); 
			$("#myModal_IDR").hide();
			alert('Seleccione Cliente Primero');				
		}				
	});	
	/**********************************************************************/
	$("#idBtnImprimirInformeResultados").click(function(){
		//var idMuestra = $("#id_muestra").val();
		var idMuestra = $("#id_detalle_muestra").val();
		var cRuta = baseUrlCortaCorta + '/impresiones_controller/InformeResultados/'+idMuestra;
		window.open( cRuta,'_blank');
	});	
	/***********************AQUI TERMINA LO DE EL INFORME DE RESULTADOS******************************************/
	
	/////****************************************************/
	$("#2idBtnAltaEstudioOLD").click(function(){ // viene de la captura de elton (capturar_estudio)
		//alert("entraod");
		// es para grabar en la base de da tos los estudios		
		var fecha_recepcion = $("#fecha_solicitud").val();
		var id_cliente = $("#id_cliente").val();
		var id_recepcion_muestra = $("#folio_solicitud").val();
		var observaciones_recepcion = $("#obs_recepcion").val(); // mar 2017
		var toma_muestra = $("#toma_muestra").val();//25/05/2017
		
		// datos del IDR 25/05/2017
		var nombre_idr_cliente = $("#nombre_idr_cliente").val();
		var domicilio_idr_cliente = $("#domicilio_idr_cliente").val();
		var rfc_idr_cliente = $("#rfc_idr_cliente").val();
		var contacto_idr_cliente = $("#contacto_idr_cliente").val();
		
		//26/05/2017
		//var costo_servicio = $("#costo_servicio").val();
		//var costo_envio = $("#costo_envio").val();
		var otros_servicio = $("#costo_servicio").val();
		
		//14/06/2017
		var destino_muestra = $("#destino_muestra").val();
		var condiciones_muestra = $("#condiciones_muestra").val();
		
		//2017-08-03
		var genera_idr = 1; // true
		if($("#idGeneraFolioIDR").is(':checked')) {  			
			if (!confirm('Se ha Seleccionado que esta muestra(s) no genere IDR \n ¿Es Correcto?')) {				
				return;
			}
			genera_idr = 0; // false
		}
		
		console.log(" Folio Recepcion:" + id_recepcion_muestra);
		//validando la informacion de manera light
		
		// saber si la tabla tiene al menos 1 estudio cargado..!
			//if ($('#tablaDetalleMuestras >tbody >tr').length > 0){
		if (fecha_recepcion && id_cliente ) {
			if ($('#tablaDetalleMuestras >tbody >tr').length  <1){
				alert("debe Ingresar Informacion de las Muestras");
			} else {

			// pensar en grabar los datos. 	validar haya detallado de estudios
			//data: 'id_cliente=' + id_cliente,
				var datos = { 'id_recepcion_muestra':id_recepcion_muestra, 'id_cliente': id_cliente, 'fecha_recepcion': fecha_recepcion, 'observaciones_recepcion':observaciones_recepcion,'toma_muestra':toma_muestra,'otros_servicio':otros_servicio,"destino_muestra":destino_muestra,"condiciones_muestra":condiciones_muestra,'generar_idr_muestra':genera_idr};
				// como si hay datos en la tabla detallado y tambien en el encabezado grabamos el encabezado usando ajax
				//alert("insertando la parte del encabezado");
				console.log('Enviando grabar encabezado de los estudio (recepcion de muestras) datos son ver abajo');
				console.log( datos );
				$.ajax({
					type: 'POST',					
					url: baseUrlCortaCorta+ "/add_encabezado_estudio", //grabamos la parte del encabezad
					data: datos,
					success: function(resp_ok){
						//alert("Perfecto se supone que ya se grabo la infor");
						console.log(resp_ok);
						//alert('salida de la funcion grabar encabezado'+resp_ok);
						if (resp_ok) { 
							// grabar ahora el detallado
							//var data_detalle = array();
							$("#tablaDetalleMuestras tbody tr").each(function (index) {
					            var campo1, campo2, campo3,campo4,campo5,campo6,campo7,campo8,campo9,campo10,campo11;
					            //alert("entro al detallado");
					            $(this).children("td").each(function (index2) 
					            {
					                switch (index2) 
					                {
					                    case 0: campo1 = $(this).text(); //id muestra , 05/12/16 hay q verificar que no este ya grabado
					                            break;
					                    case 1: campo2 = $(this).text(); //id_asignado_cliente
					                            break;
					                    case 2: campo3 = $(this).text(); //Tipo muestra // 14/06/2017
					                            break;
					                    case 3: campo4 = $(this).text(); //peso volumen
					                            break;
					                    case 4: campo5 = $(this).text(); //temperatura
					                            break;
					                    case 5: campo6 = $(this).text(); //lote
					                            break;
					                    case 6: campo7 = $(this).text(); //id metodo
					                            break;
					                    case 7: campo8 = $(this).text(); //id
					                            break;
					                    case 8: campo9 = $(this).text(); //metodo de prueba
					                            break;
					                    case 9: campo10 = $(this).text(); //importe 
					                            break;
					                    case 10: campo11 = $(this).text(); //Fecha de entrega
					                            break;					                           
					                }
					                //$(this).css("background-color", "#ECF8E0");
					            })
					            // mandarlo grabar ahora					            
					            
					            var data_detalle = { 'id_recepcion_muestra': id_recepcion_muestra, 'id_muestra':campo1,'id_asignado_cliente':campo2,'tipo_muestra':campo3,'peso_vol_muestra':campo4,'temperatura_muestra':campo5,'lote_muestra':campo6,'id_metodologia':campo7,'id_estudio':campo8,'descripcion_analisis':campo9,'importe':campo10,'fecha_salida':campo11};
					            
					            console.log(data_detalle);
					            console.log('Entrando a grabar el detallado');
					            //alert(campo1 + ' - ' + campo2 + ' - ' + campo3' - ' + campo4+' - ' + campo5+' - ' + campo6+' - ' + campo7+' - ' + campo8+' - ' + campo9);
					            $.ajax({
					            	type: 'POST',
									url: baseUrlCortaCorta+ "add_detallado_estudio", //grabamos la parte del encabezado
									data: data_detalle,
									success: function (resp_ok2){
										console.log('Solicitud Almacenada OK');
										alert("Solicitud Almacenada");
										//alert(resp_ok2);
										console.log('Ajax Return ='+ resp_ok2);
										
										// Actualiza Folios Temporal de la tabla de captura de la solicitud 19/05/2017
										//25/05/2017 actualizar la tabkla de cliente si pusieron algo en los campos IDR
										if (nombre_idr_cliente != null){
											//alert("entrando a grabar los datos nuevos del IDR");
											console.log("entrando a grabar los datos nuevos del IDR");
											
										//{'nombre_idr_cliente':nombre_idr_cliente}
											var datos3 = { 'nombre_idr_clente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente };
											$.post(baseUrlCortaCorta+"graba_datos_idr_cliente",{ 'id_cliente':id_cliente, 'nombre_idr_cliente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente },function(htmlResponse){											
												//alert(htmlResponse);
												console.log('Grabado con exito de datos del IDR del Cliente ['+htmlResponse+"]");
												
											});	// fin del POST								
										} // fin del if nombre_idr_cliente != null
										
										$.get(baseUrlCortaCorta+"actualiza_folios_temp",function(htmlResponse){
											console.log(htmlResponse);
										});
										
										
										/*
										$.get(baseUrlCortaCorta+"actualiza_folios_temp",function(htmlResponse){
											console.log(htmlResponse);
										});
										*/
										
										// a imprimirlo se ha dicho activamos el boton
										$("#idDivGeneraSolicitud").hide(); // ocultamos el div de genera solicitud
										$("#divBtnImprimir").show();
									}

					            })
					        })


							/*** FIN DE GRABAR AHORA EL DETALLADO **/
						}else{
							alert("ocurrio un error al intentar grabar la informacion en la base de datos")
						}
					}
				});// fin del ajax



			// fin de recorrer los elementos de una tabla
			
			} // fin del $('#tablaDetalleMuestras >tbody >tr').length = <2){

		}// fin del 	 if (folio_solicitud && fecha_solicitud && id_cliente) 

	})
/************************************************************************/
/*********************** EMPIEZA LO DE LA CAPTURA DEL STUDIO *****************************/
	$("#idBtnActualizaEstudio").click(function (){ // btn actualizar un estudio unicamente datos del cliente
		//alert("entraod");
		// es para grabar en la base de da tos los estudios		
		var fecha_recepcion = $("#fecha_solicitud").val();
		var id_cliente = $("#id_cliente").val();
		var id_recepcion_muestra = $("#folio_solicitud").val();
		
		var id_recepcion_muestra = $("#idRecepcionMuestra").val();//2017-08-14 -->se intenta que el sistema tome este campo como llave, ya qyue en la alta no es asi.
		
		var observaciones_recepcion = $("#obs_recepcion").val(); // mar 2017
		var toma_muestra = $("#toma_muestra").val();//25/05/2017
		
		// datos del IDR 25/05/2017
		var nombre_idr_cliente = $("#nombre_idr_cliente").val();
		var domicilio_idr_cliente = $("#domicilio_idr_cliente").val();
		var rfc_idr_cliente = $("#rfc_idr_cliente").val();
		var contacto_idr_cliente = $("#contacto_idr_cliente").val();
		
		//26/05/2017
		//var costo_servicio = $("#costo_servicio").val();
		//var costo_envio = $("#costo_envio").val();
		var otros_servicio = $("#costo_servicio").val();
		
		//14/06/2017
		var destino_muestra = $("#destino_muestra").val();
		var condiciones_muestra = $("#condiciones_muestra").val();
		
		//2017-08-03
		var genera_idr = 1; // true
		if($("#idGeneraFolioIDR").is(':checked')) {  			
			if (!confirm('Se ha Seleccionado que esta muestra(s) no genere(n) Folio de IDR \n ¿Es Correcto?')) {				
				return;
			}
			genera_idr = 0; //false
		}
		
		console.log(" Folio Recepcion:" + id_recepcion_muestra);
		//validando la informacion de manera light		
		
		var Justificacion_Edicion = $("#idJustificacionEdicion").val();
		//alert(Justificacion_Edicion );
		
	
		if (fecha_recepcion && id_cliente && Justificacion_Edicion) {
			
			
			if ($('#tablaDetalleMuestras >tbody >tr').length  <1){
				alert("debe Ingresar un detallado");
			} else { 


		
				var data = { enc:{'id_recepcion_muestra':id_recepcion_muestra, 'id_cliente': id_cliente, 'fecha_recepcion': fecha_recepcion, 'observaciones_recepcion':observaciones_recepcion,'toma_muestra':toma_muestra,'otros_servicio':otros_servicio,"destino_muestra":destino_muestra,"condiciones_muestra":condiciones_muestra,'generar_idr_muestra':genera_idr,'justificacion_edicion':Justificacion_Edicion},det:[]}
				var detallado = new Array();
				//recorrer las filas de las tablas y ponerlas en un array	
				
				
				$("#tablaDetalleMuestras tbody tr").each(function () {
					var id_muestra 		= $(this).find("td").eq(0).html();
					var id_asig_cte 	= $(this).find("td").eq(1).html();
					var tipo_muestra 	= $(this).find("td").eq(2).html();
					var peso_vol 		= $(this).find("td").eq(3).html();
					var temp 			= $(this).find("td").eq(4).html();
					var lote 			= $(this).find("td").eq(5).html();
					var id_metodo 		= $(this).find("td").eq(6).html();
					var id 				= $(this).find("td").eq(7).html();
					var metodo_prueba 	= $(this).find("td").eq(8).html();
					var importe 		= $(this).find("td").eq(9).html();
					var fec_entrega 	= $(this).find("td").eq(10).html();
					if (id_muestra){
						detallado.push( id_muestra,id_asig_cte,tipo_muestra,peso_vol,temp,lote,id_metodo,id,metodo_prueba,importe,fec_entrega);
						console.log('almacenando muestra con id [' + id_muestra + ']');
					}
				}); // fin de tabladetalle muestra tbody tr	
				
				// de las instrucciones importantes ..!
				data.det.push(detallado); 				
				
				console.log('Grabando el folio de Solicitud [' + id_recepcion_muestra +']');
				$.ajax({			
					data: data,
					method: 'POST',
					url: baseUrlCortaCorta+'/modifica_solicitud',
					success: function (htmlResponse){
						
						console.log('entro a la funcion sucesso de modifica_solicitud idBtnAltaEstudio Folio de Solicitud (ahora id_recepcion_muestra)');
						console.log(htmlResponse);
						
						if (htmlResponse['SITUACION_REGISTRO']=='EXITO'){
														
							if (nombre_idr_cliente != null){
								console.log("entrando a grabar los datos anexos a los datos del cliente para los IDR");
									
								//{'nombre_idr_cliente':nombre_idr_cliente}
								var datos3 = { 'nombre_idr_clente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente };
								$.post(baseUrlCortaCorta+"graba_datos_idr_cliente",{ 'id_cliente':id_cliente, 'nombre_idr_cliente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente },function(htmlResponse){											
									console.log('Grabado con exito de datos del IDR del Cliente ['+htmlResponse+"]");
								});	// fin del POST								
							} // fin del if nombre_idr_cliente != null
								
							/*
							$.get(baseUrlCortaCorta+"actualiza_folios_temp",function(htmlResponse){
								console.log(htmlResponse);
							});									
							*/
							// a imprimirlo se ha dicho activamos el boton
							//$("#idDivGeneraSolicitud").hide(); // ocultamos el div de genera solicitud
							//$("#divBtnImprimir").show();
							alert("Solicitud Corregida!");
						
						}// fin de htmlResponse == EXITO						
					} // funcion sucess
				}); // fin del ajax

				console.log('saliendo del ajax para grabar la correccion de la solicitud de ensayos captura');					
				
				}// fin de if ($('#tablaDetalleMuestras >tbody >tr').length  <1){
			} else {
				alert("Ingrese los campos que son requeridos; y el porque de esta correccion?");
			}// fin de fecha_recepcion && id_cliente
	});
	//***************************************************************/
	$("#idBtnAltaEstudio").click(function(){ // viene de la captura de elton (capturar_estudio) es la q graba todo el estudio
		//alert("entraod");
		// es para grabar en la base de da tos los estudios		
		var fecha_recepcion = $("#fecha_solicitud").val();
		var id_cliente = $("#id_cliente").val();
		//var id_recepcion_muestra = $("#folio_solicitud").val(); -->se elimino el 04012018
		var folio_solicitud =  $("#folio_solicitud").val();
		var observaciones_recepcion = $("#obs_recepcion").val(); // mar 2017
		var toma_muestra = $("#toma_muestra").val();//25/05/2017
		var fecha_toma_muestra = $("#fecha_toma_muestra").val() // 2017-11-27
		
		// datos del IDR 25/05/2017
		var nombre_idr_cliente = $("#nombre_idr_cliente").val();
		var domicilio_idr_cliente = $("#domicilio_idr_cliente").val();
		var rfc_idr_cliente = $("#rfc_idr_cliente").val();
		var contacto_idr_cliente = $("#contacto_idr_cliente").val();
		
		//26/05/2017
		//var costo_servicio = $("#costo_servicio").val();
		//var costo_envio = $("#costo_envio").val();
		var otros_servicio = $("#costo_servicio").val();
		
		//14/06/2017
		var destino_muestra = $("#destino_muestra").val();
		var condiciones_muestra = $("#condiciones_muestra").val();
		
		//2017-08-03
		var genera_idr = 1; // true
		if($("#idGeneraFolioIDR").is(':checked')) {  			
			if (!confirm('Se ha Seleccionado que esta muestra(s) no genere(n) Folio de IDR \n ¿Es Correcto?')) {				
				return;
			}
			genera_idr = 0; //false
		}
		
		console.log(" Folio Recepcion:" + folio_solicitud);
		//validando la informacion de manera light		
		
		
	
		if (fecha_recepcion && id_cliente ) {
			
			
			if ($('#tablaDetalleMuestras >tbody >tr').length  <1){
				alert("debe Ingresar un detallado");
			} else { 


		
				var data = { enc:{'folio_solicitud':folio_solicitud, 'id_cliente': id_cliente, 'fecha_recepcion': fecha_recepcion, 'observaciones_recepcion':observaciones_recepcion,'toma_muestra':toma_muestra,'otros_servicio':otros_servicio,"destino_muestra":destino_muestra,"condiciones_muestra":condiciones_muestra,'generar_idr_muestra':genera_idr,'fecha_toma_muestra':fecha_toma_muestra},det:[]}
				var detallado = new Array();
				//recorrer las filas de las tablas y ponerlas en un array	
				
				
				$("#tablaDetalleMuestras tbody tr").each(function () {
					var id_muestra 		= $(this).find("td").eq(0).html();
					var id_asig_cte 	= $(this).find("td").eq(1).html();
					var tipo_muestra 	= $(this).find("td").eq(2).html();
					var peso_vol 		= $(this).find("td").eq(3).html();
					var temp 			= $(this).find("td").eq(4).html();
					var lote 			= $(this).find("td").eq(5).html(); 
					var id_metodo 		= $(this).find("td").eq(6).html();// MB-PM02-006 (oculto)
					var id 				= $(this).find("td").eq(7).html(); // ID LLAVE DEL ENSAYO oculto
					
					var nombre_ensayo 	= $(this).find("td").eq(8).html();
					var metodo_prueba 	= $(this).find("td").eq(9).html();
					var importe 		= $(this).find("td").eq(10).html();
					var fec_entrega 	= $(this).find("td").eq(11).html();
					if (id_muestra){
						detallado.push( id_muestra,id_asig_cte,tipo_muestra,peso_vol,temp,lote,id_metodo,id,nombre_ensayo,metodo_prueba,importe,fec_entrega);
						console.log('almacenando muestra con id [' + id_muestra + ']');
					}
				}); // fin de tabladetalle muestra tbody tr	
				
				// de las instrucciones importantes ..!
				data.det.push(detallado); 				
				
				console.log('Grabando el folio de Solicitud [' + folio_solicitud +']');
				$.ajax({			
					data: data,
					method: 'POST',
					url: baseUrlCortaCorta+'/graba_solicitud',
					success: function (htmlResponse){
						
						console.log('entro a la funcion sucesso de grabar idBtnAltaEstudio Folio de Solicitud');
						console.log(htmlResponse);
						
						if (htmlResponse['SITUACION_REGISTRO']=='EXITO'){
														
							if (nombre_idr_cliente != null){
								console.log("entrando a grabar los datos anexos a los datos del cliente para los IDR");
									
								//{'nombre_idr_cliente':nombre_idr_cliente}
								var datos3 = { 'nombre_idr_clente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente };
								$.post(baseUrlCortaCorta+"graba_datos_idr_cliente",{ 'id_cliente':id_cliente, 'nombre_idr_cliente':nombre_idr_cliente, 'domicilio_idr_cliente': domicilio_idr_cliente, 'rfc_idr_cliente':rfc_idr_cliente,'contacto_idr_cliente':contacto_idr_cliente },function(htmlResponse){											
									console.log('Grabado con exito de datos del IDR del Cliente ['+htmlResponse+"]");
								});	// fin del POST								
							} // fin del if nombre_idr_cliente != null
								
							$.get(baseUrlCortaCorta+"actualiza_folios_temp",function(htmlResponse){
								console.log('Actualizando Folios Temporales');
								console.log(htmlResponse);
							});									
							// a imprimirlo se ha dicho activamos el boton
							$("#idDivGeneraSolicitud").hide(); // ocultamos el div de genera solicitud
							$("#divBtnImprimir").show();
							alert("Solicitud Almacenada!");
						
						}else {// fin de htmlResponse == EXITO						
							console.log($htmlResponse['MENSAJE_ERROR_BD']);
						}
					} // funcion sucess
				}); // fin del ajax

				console.log('saleidno del ajax para grabar solicitud de ensayos captura');					
				
				}// fin de if ($('#tablaDetalleMuestras >tbody >tr').length  <1){
			} else {
				alert("Ingrese los campos que son requeridos");
			}// fin de fecha_recepcion && id_cliente

	});
	/****************************************************/
	$("#idBtnSalirEstudio").click(function(){ // formulario captura aurea boton salir y limpie usuario que detiene ese formulario
		//alert('entro');
		$.post(baseUrlCortaCorta+"libera_formato_solicitud",{ },function(htmlResponse){											
			console.log('Liberando el usuario que tiene el formulario de aurea ['+htmlResponse+"]");			
		});	// fin del POST
		history.back();
	});
    //pagina_actual = getUrl.slice(url_incio+1); // Extraemos el nombre de la pagina
    //$(".menu li a[href='"+ pagina_actual +"']").addClass("activo"); //Asignamos la clase llamada "activo"
	//alert(getUrl.pathname.split('/')[3]); // sacamos la ultima palabra de la url	
	/**********************************************/
	if (getUrl=='http://localhost/recepcion/capturar_estudio') { //pendiente de recortar
		//document.getElementById('msg_alert').innerHTML= "";		 // LIMPIAMOS LAS ALERTA DE LA VENT MODAL
		/*		
		alert('entramos a una parte que no se de que se trate....!');
		//$("#divBtnImprimir").hide(); //ocultamos boton imprimiir..!
	
		$.ajax({			
		        type: "POST",
		        url: baseUrlCorta+ "/cargar_estudios", //carga el combobox de estudios
		        success: function(resp)
		        {
		            $('.selector-estudio select').html(resp).fadeIn();
		        },
		        error: function (response){
		        	alert(response);
		        }
		    });
		    */
	}// fin del if geturl	
	/**************************************************************/	
	$("#idBtnImprimir").click(function(){ // boton imprimir solicitud
		var idFolioSolicitud =$("#folio_solicitud").val();		
		var cRuta = baseUrlCortaCorta + '/impresiones_controller/SolicitudLaboratorio/'+idFolioSolicitud;
		window.open( cRuta,'_blank');
	});
	/**********************************************************************/
	$("#idBtnImprimirCte").click(function(){ // boton imprimir solicitud para el Cte entrega de muestra
		var idFolioSolicitud =$("#folio_solicitud").val();		
		var cRuta = baseUrlCortaCorta + '/impresiones_controller/EntregaMuestra/'+idFolioSolicitud;		
		window.open( cRuta,'_blank');
	});
	/***********************************************************************/
	$("#idBtnDuplicaEstudio").click(function ( ){ // viene de v_capturar_estudio (limpia los campos) SE ACTIV AL CERRAR MODAL FORMULARIO 
		$("#msg_alerta_modal").empty();		
	})
	/****************************************************************************/
	$("#idBtnAgregarEstudio").click(function ( ){ // viene de v_capturar_estudio (limpia los campos) SE ACTIV AL CERRAR MODAL FORMULARIO 
		//$('#form')[0].reset(); // reset form on modals
		//alert('entrando..!');
		//$('[name="ID_CLIENTE"]').val(data.ID_CLIENTE);
		//if ($("#idMantieneInfo").is(':checked')) {  
		
		$("#peso_volumen_muestra").val("");
		$("#temperatura_muestra").val("");		
		
		if ($("#idMantieneInfo").attr('checked') == false) {
			
			
			$('[name="id_muestra"]').val("");
			$('[name="id_cte"]').val("");
			$('[name="desc_muestra"]').val("");
			$('[name="lote"]').val("");
			$('[name="precio"]').val("");
			$('[name="no_muestra"]').val("1");
			$('[name="matriz_producto"]').val("");
			$("#tipo_muestra").val("");			
		} else {
			var id_cte = $("#id_cte").val();
			//alert('entro 1');
			if (id_cte){ // si hay algo
				//alert('entro 2');
				// buscarle numeros
				var cAux = "";
				var nPos = id_cte.length;
				//console.log( nPos);
				var num = 0;				
				console.log( 'POSICION:' + nPos);
				while( esNumero(id_cte.charAt( nPos) )  ) {
					
					num = parseInt(id_cte.substr( nPos-1, id_cte.length));
					if ( num == 0) { exit }						
					
					console.log( 'NUM:' + num);
					nPos -= 1;
					console.log( 'POSICION:' + nPos);
				}// fin del while
				console.log ('num:'+ num);
				cAux = id_cte.substr(0,nPos-1) + (num+1);
				console.log( num);
				// AQUI ME QUEDE..!
				//alert( cAux);			
			} // fin del id_cte			
		}// fin del if if ($("#idMantieneInfo").attr('checked') == false) {
		
		//$('#cbo_estudio option:selected').val("Seleccionar");
		//$("#msg_alerta_modal").hide();
		//$("#msg_alerta_modal").text("");
		$("#msg_alerta_modal").empty();
		// se me hac q ocupo limpiar el div
		//22/06/2017 es un indicativo para saber si es alta, duplicacion o edicion dependiendo de los botones en la tabla
		$("#btnAddEstudioTabla").val('Alta Ensayo');
		$("#myModalLabelMetodologia").text('Agregar Ensayo(s)');
	})	    
	/*********************************************************************/
	$("#btnAddEstudioTabla").click(function(){ // llamadas desde v_capturar_estudio cuando se anexa un estudio al detallado
		// obtener las variables a validar
		var id_cte = $("#id_cte").val();
		var desc_muestra = $("#desc_muestra").val();
		var lote = $("#lote").val();
		//var no_muestra = $("#no_muestra").val(); --> se elimino x act 09/05/2017
		var matriz_producto = $("#matriz_producto").val(); // 19/05/2017
		var no_muestra = 1;
		var id_estudio =$('#cbo_estudio option:selected').attr('value');
		var cbo_estudio = $("#cbo_estudio option:selected").html(); //obtenermos el text del combo seleccionado	
		
		var id_estudio_ensayo =$('#cbo_ensayo option:selected').attr('value');
		var cbo_ensayo = $("#cbo_ensayo option:selected").html(); //obtenermos el text del combo seleccionado	
		
		//14/06/2017
		var tipo_muestra = $("#tipo_muestra").val();
		var peso_volumen_muestra = $("#peso_volumen_muestra").val();
		var temperatura_muestra = $("#temperatura_muestra").val();
		
		//22/06/2017 --> tomar la accion del boton value ALTA ENSAYO/ DUPLICAR ENSAYO O EDITAR ENSAYO
		var cAccion = $("#btnAddEstudioTabla").val();
		
		var nPrecio = 0;		
		var cIdMuestra = $("#id_muestra").val();; // add 16/05/2017
		var cIdMetodologia = "";
		var nConsecutivo;
		var nTope;
		var lErrorValidacion = false;
		var cHtml= '';
		var idTablaCons;
		//var resp_ok;

		// hay que validar las variables luego lo hago.
		var cErrorValidacion="";
		if (!tipo_muestra ) { 			
			cErrorValidacion += "Debe Ingresar la Descripción de la Muestra</br>";
			lErrorValidacion = true;
		}
		
		if (!peso_volumen_muestra ) { 
			//alert('Ingrese descripción de la Muestra');
			cErrorValidacion += "Debe Ingresar el Peso o Volumen de la Muestra</br>";
			lErrorValidacion = true;
		}
		
		if (id_estudio == 0 || cbo_estudio == "Seleccionar") { 
			//alert('Ingrese Tipo de Metodología');
			cErrorValidacion += "Debe Seleccionar un tipo de analisis a realizar</br>";
			lErrorValidacion = true;
		}	

		if (lErrorValidacion){
			lErrorValidacion = false;
			cHtml = '<div class="alert alert-danger" role="alert"><strong>Error! </strong>'+cErrorValidacion+'</div>';		
			$("#msg_alerta_modal").append( cHtml);
			cHtml = "";
			return false; // segun esta mal hacerlo..!
		}
		//alert(cAccion);
		
		//22/06/2017 -> aprovechamos el boton del modal para indicar si no es par edicion
		
		if (cAccion.substr(0,6) == 'Editar') {			
			var nPosIndice = cAccion.substr( cAccion.indexOf( ';')+1);
			if (nPosIndice > -1 ) { // si se encontro
				console.log( 'posicion ['+ nPosIndice);				
				alert('vamos a editar');
				var tabla = document.getElementById('tablaDetalleMuestras');
				console.log( tabla );
				tabla.rows[nPosIndice].cells[1].innerHTML = id_cte;
				tabla.rows[nPosIndice].cells[2].innerHTML = tipo_muestra;
				tabla.rows[nPosIndice].cells[3].innerHTML = peso_volumen_muestra;
				tabla.rows[nPosIndice].cells[4].innerHTML = temperatura_muestra;
				tabla.rows[nPosIndice].cells[5].innerHTML = lote;
				//tabla.rows[nPosIndice].cells[5].innerHTML = lote;
				//tabla.rows[nPosIndice].cells[5].innerHTML = lote;
			}else {
				alert('Ocurrion algo inesperado');
				return;
			}
			document.getElementById('idBtnCloseModal').click(); // CERRANDO VENTANA MODAL
			
			
		}else {
				
			$.ajax({
				type: 'POST',
				//url: baseUrlCorta + "//getRowEstudio", 
				url: baseUrlCortaCorta + "/getRowEstudioyFechaEntregaResultado", 
				datatype: 'JSON',			
				data: 'id=' + id_estudio, // el select del combo seleccionado
				success: function(resp_ok) { //Cuando se procese con éxito la petición se ejecutará esta función
					//alert(resp_ok);
					console.log('Va el resultado de agregar el estudio a la tabla');
					console.log(resp_ok);
					var resp = JSON.parse(resp_ok);//Convierte cadena de notación de objetos de JavaScript (JSON) en un objeto.
					nPrecio = resp['PRECIO_ESTUDIO'];
					nConsecutivo = parseInt(resp['CONSECUTIVO_ESTUDIO']) +1;				
					//cConsecutivo = resp['ALIAS_ESTUDIO'] + ' ' +  nConsecutivo ;
					var cArea = 'MB';
					if (resp['AREA_ESTUDIO'] == 'Q') { cArea = 'AQ';}
					
					// anexado 16/05/2017 para incorpoara un id de muestras correcto y pasar el anterio a id_metodo
					console.log('Folio Muestra = ['+resp['FOLIO_MUESTRA']+']  Incremento en Renglones ['+resp['FOLIO_AUX']);
					console.log('Valor del Id de la Muestra [Vacia=Alta] ' + cIdMuestra);
					
					if (cAccion == 'Alta Ensayo') {
						console.log('Se trata de una Alta por que no cuenta con su Id de Muestra');					
						
						cIdMuestra = parseInt(resp['FOLIO_MUESTRA'])+parseInt(resp['FOLIO_AUX']);  
						cIdMuestra = cArea+"-"+ cIdMuestra;
					
						// hay q actualizar el folio temporal										
						var datos = { 'Area':cArea};
						console.log('Entrando Actualizacion del folio Temporal');

						$.ajax({
							type:'POST',
							url: baseUrlCortaCorta + "/actualiza_folio_temporal",
							datatype: 'JSON',
							data: datos,
							success: function (resp_ok3){
								//console.log("Se Actualizo correctamente el folio Temporal");
								console.log(resp_ok3);
								//alert( resp_ok3);
							},
							error: function( resp_ok3){						
								alert(resp_ok3);						
								console.log(resp_ok3);
							}
						}) // fin del ajax interno de la actualizacion del folio temporal...!			
						console.log('Saliendo Actualizacion del folio Temporal');					
					}	// FIN DE IF cIdMuestra == "alta ensayo"
					
					
					idTablaCons = resp['ALIAS_ESTUDIO'] +  nConsecutivo ; // ESTE ES PARA EL DIV DE CADA TD
					//StrZero( iNum, iZeros ) 
					cIdMetodologia = cArea +'-' +resp['ALIAS_ESTUDIO'] + '-'+pad( nConsecutivo,4);
					//zeroFill(o,n) {

					nTope = parseInt(resp['TOPE_ESTUDIO']); // maximo de este estudio x dia
					console.log(nTope);
					console.log( resp['FechaEntrega']);
					console.log( resp );
					
					fecha = resp['FechaEntrega']; // agergado 18_11_16

					// agregarmos a la tabla del detallado
					cHtml = '<tr id="'+idTablaCons+'" >';																																		//formatoNumero(numero, decimales, separadorDecimal, separadorMiles) {
					cHtml += '<td>'+cIdMuestra+'</td>';
					cHtml += '<td>'+id_cte+'</td>';
					cHtml += '<td>'+tipo_muestra+'</td>'
					cHtml += '<td>'+peso_volumen_muestra+'</td>'
					cHtml += '<td>'+temperatura_muestra+'</td>'
					cHtml += '<td>'+lote+'</td>';
					//cHtml += '<td>'+matriz_producto+'</td>';
					cHtml += '<td style="display: none;">'+cIdMetodologia+'</td>';
					cHtml += '<td style="display: none;">'+id_estudio_ensayo+'</td>';
					cHtml += '<td>'+cbo_ensayo+'</td>';
					cHtml += '<td>'+cbo_estudio+'</td>';
					//cHtml += '<td>'+formatoNumero(nPrecio, 2)+'</td>';
					cHtml += '<td>'+formatoNumero(nPrecio,2,".",",")+'</td>';
					//cHtml += '<td>'+moment(fecha).format("YYYY-MM-DD")+'</td>';
					cHtml += '<td>'+fecha+'</td>';
					cHtml += '<td>';
					//cHtml += '<input type="button" value="Delete" onclick="deleteRowDetalladoEstudio(this)" / >'
					// se cancela la opcion de liminar por posibles conflictor con el ID_MUYESTRA 17/05/2017
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoEstudio(this)" name="B1"><span class="glyphicon glyphicon-trash"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="DuplicaRowDetalladoEstudio(this,1)" name="B2"><span class="glyphicon glyphicon-copy" data-toggle="tooltip" title="Duplicar Muestra"></span></button>';
					//22/06/2017 AGERGANDO UN BOTON DE MODIFICAR.
					cHtml += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="DuplicaRowDetalladoEstudio(this,0)" name="B2"><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" title="Correccion de los Datos de la Muestra"></span></button>';
											
				
					cHtml += '</td></tr>';
					
					if ($('#tablaDetalleMuestras >tbody >tr').length == 0){
	 				   $("#tablaDetalleMuestras").append("<tbody></tbody>");
	 				   //alert("Agregando un tbody a la tabla");
					}
						
							    
					if (cAccion=='Alta Ensayo' || cAccion == 'Duplicar Ensayo') {
						$("#tablaDetalleMuestras tbody").append(cHtml);
					}else { // se trata de una edicion ..!
						console.log("se trata de una edicion");
						
						var nIndPosTabla = cAccion.substr(cAccion.search(';')+1);
						console.log('Entrando a realizar la Actualizacion del indice ['+nIndPosTabla+"] en la tabla ");
						var tabla = document.getElementById('tablaDetalleMuestras');
						
						if (parseInt(nIndPosTabla)){							
							//tabla.rows(nIndPosTabla).cells(0).innerHTML= id_muestra; //este no cambia
							
							console.log ( cIdMuestra);
							console.log( cIdMetodologia);
							
							if (cIdMuestra.substr(1,2) == cIdMetodologia.substr(1,2)){							
								tabla.rows[nIndPosTabla].cells[1].innerHTML= id_cte;
								tabla.rows[nIndPosTabla].cells[2].innerHTML= tipo_muestra;
								tabla.rows[nIndPosTabla].cells[3].innerHTML= peso_volumen_muestra;
								tabla.rows[nIndPosTabla].cells[4].innerHTML= temperatura_muestra;
								tabla.rows[nIndPosTabla].cells[5].innerHTML= lote;
								// dejar estos cinco pudiera traer problemas de consecutivo en las metodologias
								tabla.rows[nIndPosTabla].cells[6].innerHTML= cIdMetodologia;
								tabla.rows[nIndPosTabla].cells[7].innerHTML= id_estudio_ensayo;
								tabla.rows[nIndPosTabla].cells[8].innerHTML= cbo_ensayo;
								tabla.rows[nIndPosTabla].cells[9].innerHTML= cbo_estudio;
								tabla.rows[nIndPosTabla].cells[10].innerHTML= formatoNumero(nPrecio,2,".",",");
								tabla.rows[nIndPosTabla].cells[11].innerHTML= fecha;
							}else {
								alert('Imposible agregar por no pertenecer a la misma area de estudio');
							}													
							
						} // fin de parseInt						
					} // FIN DE SE TRATA DE UNA EDICION
						
					// ocultar columna de una tabla    
					
					//$("#tablaDetalleMuestras tr").find('th:eq(7)').addClass("hidden");
					// hay que cerrar la ventana modal
					document.getElementById('idBtnCloseModal').click();
					
			 	},
			 	error: function(resp_no_ok){
			 		alert(resp_no_ok);
			 	}
			}); // fin del ajax
			
		} // fin de accion != alta o duplicado
				
		console.log('Accion = ['+ cAccion+"]");
		console.log(cAccion.search(';'));
	});
	/*******************************************************************************/ 
	/**************CIERRA EL MODAL DE LOS DATOS DEL IDR DE ELTON*******************/
	$('#btnAddDatosIDR').click(function(){
		//document.getElementById('idBtnCloseModalIDR').click();		
		// hay q cerrar la ventana sin llamar al metodo
		//$("#myModal_IDR").hide();
		//$("#myModal_IDR").empty();
		// CREO Q NO TIENE RAZON DE SER, SE VA  ELIMINAR CUANDO EMPIEZE LA DEPURACION..! 23/05/2017		
	});
	/*******************************************************************************/
	$('#idBtnCloseModalIDR').click(function(){ // cierra las ventanas modales de la captura de elton (solicitud)	
		$('[name="nombre_idr_cliente"]').val("");
		$('[name="direccion_idr_cliente"]').val("");
		$('[name="rfc_idr_cliente"]').val("");
		$('[name="contacto_idr_cliente"]').val("");		
	});
	/******************************************************************************/	
	$('#searchbuttoncot').click(function(){
		alert('En Proceso de Desarrollo\nNo seamos impacientes :)');
	});
	/****************************************************************************/	
	$(".EnProcesoDesarrollo").click(function(){
		alert('En Proceso de Desarrollo\nNo seamos impacientes :)');
	});
	/****************************************************************************/	
	$('#searchbuttoncte').click(function(){  // despliega informacion del cliente de aurea captura
		var nombre_cte = $("#nombre_cte").val();
		
		$.ajax({
			type: 'POST',
			url: baseUrlCortaCorta + "/buscar_cliente", //Realizaremos la petición al metodo list_dropdown del controlador match
			data: 'name_cte='+nombre_cte, //Pasaremos por parámetro POST el id 
			success: function(resp) { //Cuando se procese con éxito la petición se ejecutará esta función
				
		 		//$("#response_search").innerHTML("<div><h1>estaes una prueba de algo qyue salio bien..</h1)</div>");
		 		//<div id='ejemplo'></div>
		 		document.getElementById('response_search').innerHTML=resp;
		 		//$('<h1/>').text(json.title).appendTo('body');

		 	}
		}); // fin del ajax
	});
	/*********************************************************************/
	/***************GRABAR EL IDR DE MICROBIOLOGIA**********************************/
	/*********************************************************************/	
	$(".GrabaIDRMicrobiologia").click(function(){		//
				
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var accion 			= $(this).val(); // toma el value del boton que llamo a este procedimiento // ALTA o EDICION
		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();
		var analisis 	= $("#idAnalisisSolicitado").val();
		var resultado 	= $("#idResultado_microbiologia").val();
		// 2018-01-17 --> 3 campos anexado el cpo de arriba ya no se usara
		var coltotales_resultado	= $("#idResultado_coltotales_microbiologia").val();
		var colfecales_resultado	= $("#idResultado_colfecales_microbiologia").val();
		var ecoli_resultado			= $("#idResultado_ecoli_microbiologia").val();
		
		var metodo 		= $("#idMetodoPrueba").val();
		var iniciales 	= $("#idInicialesAnalista").val();
		
		//30/06/2017 -->ABAJO SE ESTAN USANDO..!
		//var fechafinal	= $("#idFechaFinal").val();
		//var analista	= $("#idInicialesAnalista").val();	
		
		//2017-08-21
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr
		
		
		var idIDR 			= 0;		
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
		
		//2017-07-20
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
				
		// pasos previos a la validacion..!	
		if (!idUserSignatario){
			alert('Seleccionar usuario Signatario');
		}else if (accion == 'EDICION'  && !causas) {
			alert('Debe Indicar las Causas de porque se hizo la corrección...!');			
		} else if (!resultado && ( !coltotales_resultado && !colfecales_resultado && !ecoli_resultado) ){
			alert('Informacion incompleta en el Resultado..;')	
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Microbiologia')) {
				// EMPIEZA LO BUENO AJUA..!				
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_microbiologia':analisis,'metodo_prueba_microbiologia':metodo,'referencia_microbiologia':referencia,'observacion_microbiologia':obs,'condiciones_microbiologia':condiciones,'resultado_microbiologia':resultado,'fecha_microbiologia':idFechaFinal,'iniciales_analista_microbiologia':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas,'coliformes_totales_resultado': coltotales_resultado,'coliformes_fecales_resultado':colfecales_resultado,'ecoli_resultado': ecoli_resultado };
				console.log('mandando grabar el idr Microbiologia General');
				console.log(datos);
				
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/graba_o_corrige_idr_microbiologia", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 
						// GRABAR EL DETALLADO AHORA
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						console.log('actualizando el status de id_metodologia');	
						
							if (accion == 'ALTA')	{ //2017-08-21
								$("#divGrabaIDRMicrobiologia").hide();
								$.get(baseUrlCortaCorta+"actualiza_status_metodologia",{'idMetodologia':idMetodologia},function(htmlResponse){
									console.log(htmlResponse);									
									alert('IDR de Microbiología Realizadó');
								});
							}else {
								$("#BtnActualizaIDRMicrobiologia").hide();
								alert('IDR de Microbiología Actualizadó');
							}
								
						//}
					}
				});
				
				console.log('saliendo de la funcion ibBtnGrabaIDRAflatoxina');
			}// fin de la confirmacion..!
		}// fin de las validaciones // pasos previos a la validacion..!	
	});	
	/************  GRABAR EL INFORME DE MERCURIO ********************************************/
	/************  GRABAR EL INFORME DE MERCURIO ********************************************/
	/************  GRABAR EL INFORME DE MERCURIO ********************************************/
	$(".GrabaIDRMercurio2").click(function(){		//funcion nueva al informe de mercurio tomando micorbiologia como base
				
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var accion 			= $(this).val(); // toma el value del boton que llamo a este procedimiento // ALTA o EDICION
		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();
		var analisis 	= $("#idAnalisisSolicitado").val();
		var resultado 	= $("#idResultado_mercurio").val();
		var lc			= $("#limite_cuantificacion_mercurio").val();
		var lmp			= $("#limite_maximo_mercurio").val();
		var tecnica		= $("#tecnica_mercurio").val();
		var metodo 		= $("#idMetodoPrueba").val();			
		
		//2017-08-21
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr
		
		
		var idIDR 			= 0;		
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
		
		//2017-07-20
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
				
		// pasos previos a la validacion..!	
		if (!idUserSignatario){
			alert('Seleccionar usuario Signatario');
		}else if (accion == 'EDICION'  && !causas) {
			alert('Debe Indicar las Causas de porque se hizo la corrección...!');			
		} else if (!resultado){
			alert('Informacion incompleta en el Resultado..;')	
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Microbiologia')) {
				// EMPIEZA LO BUENO AJUA..!				
				  var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_mercurio':metodo,'referencia_mercurio':referencia,'observacion_mercurio':obs,'condiciones_mercurio':condiciones,'resultado_mercurio':resultado,'fecha_final_mercurio':idFechaFinal,'iniciales_analista_mercurio':iniciales_analista,'lc_mercurio':lc, 'lmp_mercurio':lmp,'tecnica_mercurio':tecnica,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_microbia':metodo,'referencia_microbia':referencia,'observacion_microgia':obs,'condiciones_micobiia':condiciones,'resultado_miclogia':resultado,'fecha_microbiologia':idFechaFinal ,'iniciales_analista_microbia':iniciales_analista,                                                                'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				console.log('mandando grabar el idr Microbiologia General');
				console.log(datos);
				//aqui me quede
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/graba_o_corrige_idr_mercurio", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 
						// GRABAR EL DETALLADO AHORA
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						console.log('actualizando el status de id_metodologia');	
						
							if (accion == 'ALTA')	{ //2017-08-21
								$("#divGrabaIDRMercurio").hide();
								$.get(baseUrlCortaCorta+"actualiza_status_metodologia",{'idMetodologia':idMetodologia},function(htmlResponse){
									console.log(htmlResponse);									
									alert('IDR de Mercurio Realizadó');
								});
							}else {
								$("#BtnActualizaIDRMercurio").hide();
								alert('IDR de Mercurio Actualizadó');
							}
								
						//}
					}
				});
				
				console.log('saliendo de la funcion ibBtnGrabaIDRAflatoxina');
			}// fin de la confirmacion..!
		}// fin de las validaciones // pasos previos a la validacion..!	
	});	
	/* ******************************************************************/
	$("#ibBtnGrabaIDRMercurio").click(function(){			// va a desaparecer
		
		var idIDR 			= 0;
		//var lParaValidacion = $("#idParaValidacion").val();
		if ($("#idParaValidacion").is(':checked')) {  
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();		
		
		var analisis 	= $("#idAnalisisSolicitado").val();
		var resultado 	= $("#idResultado_mercurio").val();
		var lc			= $("#limite_cuantificacion_mercurio").val();
		var lmp			= $("#limite_maximo_mercurio").val();
		var tecnica		= $("#tecnica_mercurio").val();
		var metodo 		= $("#idMetodoPrueba").val();
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();				
		
		var iniciales 	= $("#idInicialesAnalista").val();		
		//30/06/2017
		var fechafinal	= $("#idFechaFinal").val();
		//var analista	= $("#idInicialesAnalista").val();		
		
		// pasos previos a la validacion..!	
		if (!iniciales){
			alert('Falta las Iniciales del Analista encargado de realizar el ensayo');
		} else if (!resultado || !lc || !lmp ||!tecnica ){
			alert('Informacion incompleta en el Resultado..;')	
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Mercurio')) {
				// EMPIEZA LO BUENO AJUA..!
				var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_mercurio':metodo,'referencia_mercurio':referencia,'observacion_mercurio':obs,'condiciones_mercurio':condiciones,'resultado_mercurio':resultado,'fecha_final_mercurio':fechafinal,'iniciales_analista_mercurio':iniciales,'lc_mercurio':lc, 'lmp_mercurio':lmp,'tecnica_mercurio':tecnica};
				console.log('mandando grabar el idr Mercurio Individual');
				console.log(datos);
				
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/graba_idr_mercurio", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 						
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						console.log('actualizando el status de id_mercurio');	
						
						$("#DivGrabaIDRMexico").hide();
						$("#divBtnImprimeIDRMercurio").show();
						if (confirm('Actualizar el Folio a Finalizado ...')){
								$.get(baseUrlCortaCorta+"actualiza_status_metodologia",{'idMetodologia':idMetodologia},function(htmlResponse){
									console.log(htmlResponse);
									alert(htmlResponse);							
							});
						}
					}
				});
				
				console.log('saliendo de la funcion ibBtnGrabaIDRMercurio');
			}// fin de la confirmacion..!
		}// fin de las validaciones // pasos previos a la validacion..!	
	});	// va a desaparecer
	
	/*************************************************************************/
	$("#ibBtnGrabaIDRMetales").click(function(){			
	
		var idIDR 			= 0;
		//var lParaValidacion = $("#idParaValidacion").val();
		if ($("#idParaValidacion").is(':checked')) {  
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();		
		
		var analisis 	= $("#idAnalisisSolicitado").val();
		var metodo 		= $("#idMetodoPrueba").val();
		
		var resultado_cobre = $("#resultado_cobre").val();
		var lc_cobre		= $("#lc_cobre").val();
		var lmp_cobre		= $("#lmp_cobre").val();
		var tecnica_cobre	= $("#tecnica_cobre").val();
		
		var resultado_manganeso = $("#resultado_manganeso").val();
		var lc_manganeso		= $("#lc_manganeso").val();
		var lmp_manganeso		= $("#lmp_manganeso").val();
		var tecnica_manganeso	= $("#tecnica_manganeso").val();
		
		var resultado_niquel = $("#resultado_niquel").val();
		var lc_niquel		= $("#lc_niquel").val();
		var lmp_niquel		= $("#lmp_niquel").val();
		var tecnica_niquel	= $("#tecnica_niquel").val();		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();				
		
		var iniciales 	= $("#idInicialesAnalista").val();		
		//30/06/2017
		var fechafinal	= $("#idFechaFinal").val();
		//var analista	= $("#idInicialesAnalista").val();		
		
		// pasos previos a la validacion..!	
		if (!iniciales){
			alert('Falta las Iniciales del Analista encargado de realizar el ensayo');
		} else if (!resultado_cobre || !lc_cobre || !lmp_cobre ||!tecnica_cobre   || !resultado_manganeso || !lc_manganeso || !lmp_manganeso || !tecnica_manganeso || !resultado_niquel || !lc_niquel || !lmp_niquel || !tecnica_niquel ){
			alert('Informacion incompleta en el Resultado..;')	
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Metales')) {
				// EMPIEZA LO BUENO AJUA..!
				var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_metales':analisis,'metodo_prueba_metales':metodo,'referencia_metales':referencia,'observacion_metales':obs,'condiciones_metales':condiciones,'fecha_final_metales':fechafinal,'iniciales_analista_metales':iniciales,
				'resultado_cobre':resultado_cobre, 'lc_cobre':lc_cobre,'lmp_cobre':lmp_cobre,'tecnica_cobre':tecnica_cobre,
				'resultado_manganeso':resultado_manganeso, 'lc_manganeso':lc_manganeso,'lmp_manganeso':lmp_manganeso,'tecnica_manganeso':tecnica_manganeso,
				'resultado_niquel':resultado_niquel, 'lc_niquel':lc_niquel,'lmp_niquel':lmp_niquel,'tecnica_niquel':tecnica_niquel};
				console.log('mandando grabar el idr Mercurio Individual');
				console.log(datos);				
				
				$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/graba_idr_metales", //grabamos la parte del encabezado regresa lastid
					data: datos,
					success: function(Resp_OK){ 						
						console.log('regreso de funcion succes');
						console.log(Resp_OK);				
						console.log('actualizando el status de id_mercurio');	
						
						$("#DivGrabaIDRMetales").hide();
						$("#divBtnImprimeIDRMetales").show();
						if (confirm('Actualizar el Folio a Finalizado ...')){
								$.get(baseUrlCortaCorta+"actualiza_status_metodologia",{'idMetodologia':idMetodologia},function(htmlResponse){
									console.log(htmlResponse);
									alert(htmlResponse);							
							});
						}
					}
				});
				
				console.log('saliendo de la funcion ibBtnGrabaIDRMercurio');
			}// fin de la confirmacion..!
		}// fin de las validaciones // pasos previos a la validacion..!	
	});		
	/**************************************************************************/	
	$("#idReiniciaEnsayos,#idReiniciaFolios,#idReiniciaFoliosEnsayos,#idReiniciaIDRS").click(function(event) {
		if (!confirm('Seguro de Eliminar todos los Ensayos registrados')) {
			event.preventDefault();		
		}	  
	}); 
	/************************************************************/
	$("#btnGeneraReporteIDRGral3").click(function(){
		$('#tablaInformeIDRGral tbody tr').each(function() {	// borrando todos los registros de la tabla
	      $(this).remove();
	    });
		alert('anexando un renglon');
		$("#divVisualizaReporteIDRGral tbody").append('<tr><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td></tr>');
	});
	/**********************************************************************************/
	$("#btnGeneraReporteIDRGral").click(function(){
		var dFechaIni 	= $("#dFechaInicial").val();
		var dFechaFin 	= $("#dFechaFinal").val();
		var cArea 		= $("#cboArea").val();
		var cCte 		= $("#cboCliente").val();
		var cMetodo 	= $("#cboEstudios").val();
		
		if (!dFechaIni || !dFechaFin ) {
			dFechaIni = '2017-01-01';
			dFechaFin = '2017-07-28';
		}	
		
		if (!dFechaIni || !dFechaFin ) {		
			alert('Especificar Fechas');
		}else {
			
			var datos={ 'fecha_inicial' : dFechaIni, 'fecha_final':dFechaFin };
			$.ajax({
					type: 'POST',
					url: baseUrlCortaCorta+ "/reporte_idr_general", //genera el reporte de la tabla de recepcion de muestas
					data: datos,
					success: function(Resp_OK){ 						
						//console.log('regreso de funcion succes reporte_idr_general');
						//console.log(Resp_OK);
						
						$('#tablaInformeIDRGral tbody tr').each(function() {	// borrando todos los registros de la tabla
					      $(this).remove();
	    				});
						
						
						alert('anexando un renglon');						
						$("#divVisualizaReporteIDRGral tbody").append('<tr><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td><td>prueba</td></tr>');
						/*
						$.each( Resp_OK,function(i,val){
							$("#"+1)
						});
						*/
						
						var obj = jQuery.parseJSON(Resp_OK); // esto funciono
						console.log(obj); 
						
						console.log(obj[0].ANALISIS_SOLICITADO);
						var cHtml = "";
						$.each(obj,function(i,value){
							cHtml = '<tr>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '<td>'+obj[i].FOLIO_SOLICITUD+'</td>';
							cHtml = cHtml + '</tr>';
														
							$("#divVisualizaReporteIDRGral tbody").append(cHtml);
							console.log(value);
						});
						
						
						

						
					}
				});
		}
	}); // fin de btnGeneraReporteIDRGral
	/* ******************************************************/
	$('#tablaInformeIDRGral').DataTable({		
	    dom: 'Bfrtip',		
	    buttons: [
	      'copy', 'csv', 'excel', 'pdf', 'print'
	    ],
	    "language": {
	    "lengthMenu": "Mostrar _MENU_ Registros por Pagina",
	    "zeroRecords": "Nada para mostrar - reintente",
	    "info": "Mostrando _PAGE_ de _PAGES_ paginas",
	    "infoEmpty": "No información Disponible",
	    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
	    "sLoadingRecords": "Cargando...",
	    "search":"Buscar:",
	    "paginate": {
	        "first":      "Primero",
	        "last":       "Último",
	        "next":       "Siguiente",
	    	"previous":   "Anterior"   },
	    },
	    "columnDefs": [
                    {
                        "targets": [ 5,6,7,8 ], // borro de mientras la descripcion de la muestra
                        "visible": false,
                        "searchable": false
                    }                    
                ],
		});
	/************************************************************/
	$("#BtnAgregaMetalTabla").click(function(){
		
		//obtener las variables que participan
		var iniciales  = $("#idInicialesAnalista").val();
		//alert(iniciales);		
		
		var cMetal = $("#idMetalCombo option:selected").text();
		var cResultado =  $("#idResultado_metal").val();			
		var cLC = $("#idLC_metal").val();
		var cLMP = $("#idLMP_metal").val();
		var cTecnica = $("#idTecnica").val();
		

		if ( !cResultado || !cTecnica) { alert('Información Incompleta');
		}else { 

			if (confirm('Anexar el Resultado ['+cMetal+'/'+cResultado+'/'+cLC+'/'+cLMP+'/'+cTecnica+']')){			

				cHtml = '<tr id="'+getRandomChars(6)+'">';
				cHtml += '<td>'+cMetal+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td>'+'</td><td>'+cTecnica+'</td>';
				cHtml += '<td>';				
				cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
				//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
				cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
								
				//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs esModificable"><span class="glyphicon glyphicon-pencil"></span></button>';
				//cHtml += '<button type="button" class="btn btn-info btn-xs esModificable"><span class="glyphicon glyphicon-pencil"></span></button>';
				//cHtml += '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Launch demo modal</button>';
				cHtml += '</td>';
				cHtml += '</tr>';				

				if ($('#idTablaIDRMetales >tbody >tr').length == 0){
		 			$("#idTablaIDRMetales").append("<tbody></tbody>");
		 				//alert("Agregando un tbody a la tabla");
				}
				$("#idTablaIDRMetales tbody").append(cHtml);
				$("#idResultado_metal").val("ND");
				$('#idTecnica').val('Horno de Grafito');
				// cambiar el analito
				$('#idMetalCombo option:selected').next().attr('selected', 'selected');

    			
				$("#idResultado_metal").focus();
			} // fin del confirm
		}// fin del if !cResultado
	})
	// *******************************************************************		
	$("#BtnAgregaMetalesAcreditadosTabla").click(function(){ // agrega todos los analitos a la tabla
		//obtener las variables que participan USANDO UN AJAX
		
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_metales_acreditados",
			success: function(htmlResponse){
				var objMetales = htmlResponse['RESULTADO'];				
				
				$.each(objMetales ,function(i,contenido){			
					
					console.log(contenido);
					
					var cMetal 		= objMetales [i].NOMBRE_METAL;					
					var cResultado 	= objMetales [i].RESULTADO_METAL;
					var cLC 		= objMetales [i].LC_METAL;
					var cLMP 		= objMetales [i].LMP_METAL;
					var cTecnica	= objMetales [i].TECNICA_METAL;
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cMetal+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs tablaIdrPlagicidasEdit"  ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" data-togle="ModalAnalitoCorreccion" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRMetales >tbody >tr').length == 0){
			 			$("#idTablaIDRMetales").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRMetales tbody").append(cHtml);
					
					
				});			
			},				
						
		}); // fin del ajax	
	
		
	}) // fin de BtnAgregaTodosAnalitosTabla
	/* ************************************************************************/	
	$("#BtnAgregaTodosMetalesTabla").click(function(){ // agrega todos los analitos a la tabla
		//obtener las variables que participan USANDO UN AJAX
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_metales",
			success: function(htmlResponse){
				var objMetales = htmlResponse['RESULTADO'];				
				//console.log(objAnalitos);
				
				$.each(objMetales,function(i,contenido){			
					
					console.log(contenido);
					
					var cMetal 	= objMetales[i].NOMBRE_METAL;					
					var cResultado 	= objMetales[i].RESULTADO_METAL;
					var cLC 		= objMetales[i].LC_METAL;
					var cLMP 		= objMetales[i].LMP_METAL;
					var cTecnica	= objMetales[i].TECNICA_METAL;
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cMetal+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" name="button_datos_idr_plagicidas" id="button_datos_idr_plagicidas" class="btn btn-info btn-xs" data-toggle="modal" data-target="#exampleModal" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrMetales(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';
					
					//cHtml += '<button type="button" class="btn btn-info btn-xs tablaIdrPlagicidasEdit"  ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					//cHtml += '<button type="button" class="btn btn-info btn-xs" data-togle="ModalAnalitoCorreccion" ><span class="glyphicon glyphicon-pencil"></span></button>';
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRMetales >tbody >tr').length == 0){
			 			$("#idTablaIDRMetales").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRMetales tbody").append(cHtml);
					
					
				});			
			},				
						
		}); // fin del ajax	
	
		
	}) // fin de BtnAgregaTodosAnalitosTabla	
	/**************************************************************/
	$("#btnAddDatosIDRMetales").click(function(){
		
		//alert('entro');

		
		var cResultado 	= $("#idResultado_Metal2").val();			
		var cLC 		= $("#idLC_Metal2").val();
		var cLMP 		= $("#idLMP_Metal2").val();
		var cTecnica 	= $("#idTecnica_Metal2").val();		
		 
        //obtenemos el valor insertado a buscar
		var cMetal		= $("#idMetal2").val();
        //utilizamos esta variable solo de ayuda y mostrar que se encontro
        encontradoResultado=false;		
 		
 		
        //realizamos el recorrido solo por las celdas que contienen el código, que es la primera
        $("#idTablaIDRMetales tr").find('td:eq(0)').each(function () {
 		    
             //obtenemos el codigo de la celda
              metal = $(this).html();
 
               //comparamos para ver si el código es igual a la busqueda
               if(metal==cMetal){
 
                    //aqui ya que tenemos el td que contiene el codigo utilizaremos parent para obtener el tr.
                    trDelResultado=$(this).parent();
                    //ya que tenemos el tr seleccionado ahora podemos navegar a las otras celdas con find
                                      
                    //trDelResultado.find("td:eq(0)").html(cResultado);
                    trDelResultado.find("td:eq(1)").html(cResultado);
                    trDelResultado.find("td:eq(2)").html(cLC);
                    trDelResultado.find("td:eq(3)").html(cLMP);
                    trDelResultado.find("td:eq(4)").html(cTecnica);
 
                    //mostramos el resultado en el div
                    //$("#mostrarResultado").html("El nombre es: "+nombre+", la edad es: "+edad)
 
                    encontradoResultado=true; 
               } 
        })
 
        //si no se encontro resultado mostramos que no existe.
        if(!encontradoResultado) {
			alert('no se encontro el metal ['+cMetal +']');
		}
        //$("#mostrarResultado").html("No existe el código: "+buscar)		
	})
	/*************************************************************************/
	$(".GrabaIDRMetales").click(function(){
		
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var accion 			= $(this).val(); // toma el value del boton que llamo a este procedimiento // ALTA o EDICION
			
		
		//var iniciales 	= $("#idInicialesAnalista").val();
		var fechafinal	= $("#idFechaFinal").val();		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();
		var analisis 	= $("#idAnalisisSolicitado").val();
		var metodo 		= $("#idMetodoPrueba").val();
		
		//2017-07-10
		//obteniendo el nombre y cargo de quien este como signataria seleccionada del combo que les puse nuevo
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		
		
		var idIDR 			= 0;
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
		
		//2017-07-20
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		//2017-08-28
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr
		
		
		$nFilas = $("#idTablaIDRMetales >tbody >tr").length;
		
		// pasos previos a la validacion..!		
		if (!idUserSignatario){
			alert('Seleccionar usuario Signatario');
		}else if (accion == 'EDICION'  && !causas) {
			alert('Debe Indicar las Causas de porque se hizo la corrección ...!');			
		} else if ( $nFilas == 0 ){
			alert('Informacion incompleta en el Resultado (Debe Especificar Metales al IDR)');
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Metales')) {
				// EMPIEZA LO BUENO AJUA..!
				//recorrer las filas de las tablas y ponerlas en un array				
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_mercurio':metodo,'referencia_mercurio':referencia,'observacion_mercurio':obs,'condiciones_mercurio':condiciones,'resultado_mercurio':resultado,'fecha_final_mercurio':idFechaFinal,'iniciales_analista_mercurio':iniciales_analista,'lc_mercurio':lc, 'lmp_mercurio':lmp,'tecnica_mercurio':tecnica,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				  var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,'referencia_metales':referencia,'observacion_metales':obs,'condiciones_metales':condiciones, 'analisis_solicitado_metales':analisis,'metodo_prueba_metales':metodo,'fecha_final_metales':fechafinal,'iniciales_analista_metales':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas},det:[]}
				
				var detallado = new Array();
				var nPos = 0;
				$('#idTablaIDRMetales tr').each(function () {

					var metal = $(this).find("td").eq(0).html();
					var resultado = $(this).find("td").eq(1).html();
					var lc = $(this).find("td").eq(2).html();
					var lmp = $(this).find("td").eq(3).html();
					var tecnica = $(this).find("td").eq(4).html();
					if (metal) {
						detallado.push( metal,resultado,lc,lmp,tecnica);	
					}			

				});
				console.log( detallado);
				data.det.push(detallado);		
				
				console.log(data);
				//alert('Entrando al ajax para grabar el idr de plagicidas');
				console.log('Entrando al ajax para grabar el idr de Metales');
				//dataType: "json",
				$.ajax({			
					data: data,
					method: 'POST',
					url: baseUrlCortaCorta+'/graba_idr_metales2',
					success: function (htmlResponse){
						console.log('entro a la funcion sucesso de grabar idr_metales');
						console.log(htmlResponse);
						//alert(htmlResponse);
						
						if (htmlResponse['SITUACION_REGISTRO']=='EXITO'){
							$("#BtnActualizaIDRMetales").hide();
							$("#divBtnGrabaIDRMetales").hide();
							alert('Informe de Resultado Grabado');
							
						}
						
					},
				}); // fin del ajax
				//alert('saleidno del ajax para grabar idr de plagicidas');
				console.log('salio del ajax para grabar idr de metales');
			
			}  // FIN DEL CONFIRM
		}// FIN DEL IF PASOS PREVIOS A LA VALIDACION
				
		
	});
	/**********************************************************/
	$("#BtnAgregaAnalitosxMetodoLC").click(function(){ // agrega todos los analitos a la tabla x metodo LC
		//obtener las variables que participan USANDO UN AJAX
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_analitos_x_metodo_lc",
			success: function(htmlResponse){
				var objAnalitos = htmlResponse['RESULTADO'];				
				//console.log(objAnalitos);
				
				$.each(objAnalitos,function(i,contenido){			
					
					console.log(contenido);
					
					var cAnalito 	= objAnalitos[i].NOMBRE_ANALITO;					
					var cResultado 	= objAnalitos[i].RESULTADO_ANALITO;
					var cLC 		= objAnalitos[i].LC_ANALITO;
					var cLMP 		= objAnalitos[i].LMP_ANALITO;
					//var cTecnica	= objAnalitos[i].TECNICA_ANALITO;
					var cTecnica	= 'LC';
					
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cAnalito+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';					
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRPlaguicidas >tbody >tr').length == 0){
			 			$("#idTablaIDRPlaguicidas").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRPlaguicidas tbody").append(cHtml);
				});			
			},				
		}); // fin del ajax	
	}) // fin de BtnAgregaAnalitosxMetodoLC
	/**********************************************************************************************************/
	$("#BtnAgregaAnalitosxMetodoGC").click(function(){ // agrega todos los analitos a la tabla x metodo LC
		//obtener las variables que participan USANDO UN AJAX
		$.ajax({
			dataType:"json",
			url:baseUrlCortaCorta+"obtener_todos_los_analitos_x_metodo_gc",
			success: function(htmlResponse){
				var objAnalitos = htmlResponse['RESULTADO'];				
				//console.log(objAnalitos);
				
				$.each(objAnalitos,function(i,contenido){			
					
					console.log(contenido);
					
					var cAnalito 	= objAnalitos[i].NOMBRE_ANALITO;					
					var cResultado 	= objAnalitos[i].RESULTADO_ANALITO;
					var cLC 		= objAnalitos[i].LC_ANALITO;
					var cLMP 		= objAnalitos[i].LMP_ANALITO;
					//var cTecnica	= objAnalitos[i].TECNICA_ANALITO;
					var cTecnica	= 'GC';
					
					//ANEXANDOLO AHORA A LA TABLA
					cHtml = '<tr id="'+getRandomChars(6)+'">';
					cHtml += '<td>'+cAnalito+'</td><td>'+cResultado+'</td><td>'+cLC+'</td><td>'+cLMP+'</td><td>'+cTecnica+'</td>';
					cHtml += '<td>';
					
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-trash"></span></button>';
					cHtml += '<button type="button" class="btn btn-info btn-xs" onclick="EditaRowDetalladoIdrPlagicidas(this)" ><span class="glyphicon glyphicon-pencil"></span></button>';					
					cHtml += '</td>';
					cHtml += '</tr>';
					if ($('#idTablaIDRPlaguicidas >tbody >tr').length == 0){
			 			$("#idTablaIDRPlaguicidas").append("<tbody></tbody>");
			 				//alert("Agregando un tbody a la tabla");
					}
					$("#idTablaIDRPlaguicidas tbody").append(cHtml);
				});			
			},				
		}); // fin del ajax	
	}) // fin de BtnAgregaAnalitosxMetodoGC
	/**************************************************************************************************/
	$(".GrabaIDRPlaguicidasAgua").click(function(){ //2018-01-30 --> plagucidas en agua
		
		var idMuestra 		= $("#idMuestra").val();
		var idMetodologia 	= $("#idMetodologia").val();
		var accion 			= $(this).val(); // toma el value del boton que llamo a este procedimiento // ALTA o EDICION
			
		
		//var iniciales 	= $("#idInicialesAnalista").val();
		var fechafinal	= $("#idFechaFinal").val();		
		
		var referencia	= $("#idReferencia").val();
		var obs			= $("#idObsResultado").val();
		var condiciones	= $("#idCondMuestra").val();
		var analisis 	= $("#idAnalisisSolicitado").val();
		var metodo 		= $("#idMetodoPrueba").val();
		
		//2017-07-10
		//obteniendo el nombre y cargo de quien este como signataria seleccionada del combo que les puse nuevo
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		
		var idIDR 			= 0;
		if ($("#idGeneraFolioIDR").is(':checked')) {  // cambio 2017-08-17
            console.log('No debe de Generar IDR (osea no actualizar cpo IDR en afaltoxinas');       
        } else 	{
			idIDR = $("#idIDR").val();
			console.log('Generar IDR (flujo normal del programa');       
		}
		
		//2017-08-17 --> saber la fecha de terminacion del informe.
		var idFechaFinal = $("#idFechaFinal").val() ; // fecha de terminacion caturada por el user
		var iniciales_analista = $("#idInicialesAnalista").val(); // nuevo campo agregado para agregar todo..!
		
		//2017-07-20
		var idUserSignatario = $("#idSignatarioCombo option:selected").prop('value');
		
		//2017-08-28
		var causas			= $("#idCausasCorreccion").val(); // posible causas de porque se corrige
		var idTabla			= $('#idTabla').val(); // represente el id autonumerico de cada tabla idr
		
		
		$nFilas = $("#idTablaIDRPlaguicidas >tbody >tr").length;
		
		// pasos previos a la validacion..!		
		if (!idUserSignatario){
			alert('Seleccionar usuario Signatario');
		}else if (accion == 'EDICION'  && !causas) {
			alert('Debe Indicar las Causas de porque se hizo la corrección ...!');			
		} else if ( $nFilas == 0 ){
			alert('Informacion incompleta en el Resultado (Debe Especificar Analitos al IDR)');
		}else {
			
			if (confirm('Proceder con el Grabado del IDR de Microbiologia')) {
				// EMPIEZA LO BUENO AJUA..!
				//recorrer las filas de las tablas y ponerlas en un array				
				//var datos={'idIDR':idIDR, 'id_muestra':idMuestra, 'id_metodologia': idMetodologia, 'analisis_solicitado_mercurio':analisis,'metodo_prueba_mercurio':metodo,'referencia_mercurio':referencia,'observacion_mercurio':obs,'condiciones_mercurio':condiciones,'resultado_mercurio':resultado,'fecha_final_mercurio':idFechaFinal,'iniciales_analista_mercurio':iniciales_analista,'lc_mercurio':lc, 'lmp_mercurio':lmp,'tecnica_mercurio':tecnica,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas };
				  var data = { enc:{'id_idr':idIDR,'id_muestra':idMuestra,'id_metodologia': idMetodologia,'referencia_plaguicidas':referencia,'observacion_plaguicidas':obs,'condiciones_plaguicidas':condiciones, 'analisis_solicitado_plaguicidas':analisis,'metodo_prueba_plaguicidas':metodo,'fecha_final_plaguicidas':fechafinal,'iniciales_analista_plaguicidas':iniciales_analista,'id_usuario_signatario':idUserSignatario,'accion':accion,'causas_correccion':causas},det:[]}
				
				var detallado = new Array();
				var nPos = 0;
				$('#idTablaIDRPlaguicidas tr').each(function () {

					var analito = $(this).find("td").eq(0).html();
					var resultado = $(this).find("td").eq(1).html();
					var lc = $(this).find("td").eq(2).html();
					var lmp = $(this).find("td").eq(3).html();
					var tecnica = $(this).find("td").eq(4).html();
					if (analito) {
						detallado.push( analito,resultado,lc,lmp,tecnica);	
					}			

				});
				console.log( detallado);
				data.det.push(detallado);		
				
				console.log(data);
				//alert('Entrando al ajax para grabar el idr de plagicidas');
				console.log('Entrando al ajax para grabar el idr de plagicidas');
				//dataType: "json",
				$.ajax({			
					data: data,
					method: 'POST',
					url: baseUrlCortaCorta+'/graba_idr_Plagicidas',
					success: function (htmlResponse){
						console.log('entro a la funcion sucesso de grabar idr_plagicidas');
						console.log(htmlResponse);
						//alert(htmlResponse);
						
						if (htmlResponse['SITUACION_REGISTRO']=='EXITO'){
							$("#divBtnGrabaIDRPlaguicidas").hide();
							alert('Informe de Resultado Grabado');
						}
						
					},
				}); // fin del ajax
				//alert('saleidno del ajax para grabar idr de plagicidas');
				console.log('saleidno del ajax para grabar idr de plagicidas');
			
			}  // FIN DEL CONFIRM
		}// FIN DEL IF PASOS PREVIOS A LA VALIDACION
				
		
	});
	/***************************************************************/
	
}); // FIN DEL JQUERY