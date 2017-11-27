<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>T&iacute;tulo de la p&aacute;gina</TITLE>
 		<!-- 		
 			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.css"/>
			<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.js"></script>
		-->	
		

        
    </HEAD>

    <BODY>
    
        
		<?php 
			echo $this->table->generate($datos);
		?>        
    </BODY>
</HTML>
<script type="text/javascript">


if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery');
}

$(function () {

//	alert($.fn.jquery);
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
});

</script>