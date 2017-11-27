  <div class="container">

    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div><!--fin panel heading -->
      
      <div class="panel-body">

        <h1>Recepcion de Muestras</h1>

        <h3>CRUD Clientes del Sistema</h3>
        <br />
        <?php if ( $this->utilerias->permiso( 'CLIENTES','REGISTRAR' )  ) { ?>
        <button class="btn btn-success" onclick="add_cliente()"><i class="glyphicon glyphicon-plus"></i> Agrega Cliente</button>        
        <?php } ?> 
        
        
        <br />
        <br />
        <table id="table_id_clientes" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Domicilio</th>
              <th>RFC</th>
              <th>Ciudad</th>
              <th>Estado</th>
              <th>Telefono</th>
              
              <th>Correo</th>
              
              <th style="width:125px;">Accion</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($clientes as $clientes){?>
                 <tr>
                    <td><?php echo $clientes->ID_CLIENTE;?></td>
                    <td><?php echo $clientes->NOMBRE_CLIENTE;?></td>
                    <td><?php echo $clientes->DOMICILIO_CLIENTE;?></td>
                    <td><?php echo $clientes->RFC_CLIENTE;?></td>
                    <td><?php echo $clientes->CIUDAD_CLIENTE;?></td>
                    <td><?php echo $clientes->ESTADO_CLIENTE;?></td>
                    <td><?php echo $clientes->TELEFONO_CLIENTE;?></td>
                    <td><?php echo $clientes->EMAIL_CLIENTE;?></td>
                    
                    <td>
                    <?php if ( $this->utilerias->permiso( 'CLIENTES','CORREGIR' )  ) { ?>
                      <button class="btn btn-warning btn-xs" onclick="edit_cliente(<?php echo $clientes->ID_CLIENTE;?>)"><i class="glyphicon glyphicon-pencil"></i></button>
                      <?php } ?>
                      <?php if ( $this->utilerias->permiso( 'CLIENTES','ELIMINAR' )  ) { ?>
                      <button class="btn btn-danger btn-xs" onclick="delete_cliente(<?php echo $clientes->ID_CLIENTE;?>)"><i class="glyphicon glyphicon-remove"></i></button>
                      <?php } ?>
                    </td>
                  </tr>
                 <?php }?>
     
     
     
          </tbody>
     
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Domicilio</th>
              <th>RFC</th>
              <th>Ciudad</th>
              <th>Estado</th>
              <th>Telefono</th>             
              <th>Correo</th>              
              <th>Accion</th>
            </tr>
          </tfoot>
        </table>
     
      </div> <!-- fin del container -->
    </div> <!-- fin del panel body-->
  </div> <!-- fin del panel -->
 
  <script type="text/javascript">
  
    var save_method; //for save method string
    var table;
 
 
    function add_cliente()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }
 
    function edit_cliente(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals
 
      
      //Ajax Load data from ajax
      // obtiene la informacion del cliente  $this->clientes_crud_model->get_cliente_by_id($id); 
      $.ajax({
        url : "<?php echo site_url('/clientes_crud/ajax_edit/')?>/" + id, 
        type: "GET",
        dataType: "JSON",
        success: function(data)
        { 
            $('[name="ID_CLIENTE"]').val(data.ID_CLIENTE);
            $('[name="NOMBRE_CLIENTE"]').val(data.NOMBRE_CLIENTE);
            $('[name="DOMICILIO_CLIENTE"]').val(data.DOMICILIO_CLIENTE);
            $('[name="RFC_CLIENTE"]').val(data.RFC_CLIENTE);
            $('[name="CIUDAD_CLIENTE"]').val(data.CIUDAD_CLIENTE);
            $('[name="ESTADO_CLIENTE"]').val(data.ESTADO_CLIENTE);
            $('[name="TELEFONO_CLIENTE"]').val(data.TELEFONO_CLIENTE); 
            $('[name="CONTACTO_CLIENTE"]').val(data.CONTACTO_CLIENTE); 
            $('[name="EMAIL_CLIENTE"]').val(data.EMAIL_CLIENTE); 
            $('[name="EMAIL_ALTERNO_CLIENTE"]').val(data.EMAIL_ALTERNO_CLIENTE); 
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Modificación Usuario'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqxhr.responseText);
        }
    });
    }// fin de funcion edit_cliente
 
 
 
    function save()
    {
      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('/clientes_crud/cliente_add')?>";
      }
      else
      {
        url = "<?php echo site_url('/clientes_crud/cliente_update')?>";
      }
 
       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
              $('#modal_form').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //alert('Error al Agregar o Actualizar [ ' + errorThrown +']');
                alert( jqXHR.responseText);
                //alert( eval(jqXHR));
                //alert( eval(errorThrown));
                console.log(textStatus, errorThrown,jqXHR);
            }
        });
    } // fin de funcion save
 
    function delete_cliente(id)
    {
      if(confirm('Seguro de Borrar el registro del Cliente?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('/clientes_crud/cliente_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {               
               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              var win = window.open('', '_blank');
              win.document.getElementsByTagName('html')[0].innerText = jqXHR.responseText;
              //$(win).innerText = jqXHR.responseText;
              //win.innerText = jqXHR.responseText ;
              
              //errtext = 'data:text/html;base64,' + window.open(jqXHR.responseText);
              //window.open(errtext, '_self');
              //document.open('text/html', true);
              //document.write(jqXHR.responseText);
              //document.close()

              //window.open('', '_self');
             // $(document).html(jqXHR.responseText);

              //window.open(jqXHR.responseText, "ventana1" , "width=500,height=500,scrollbars=NO") ;
                //window.open(jqXHR.responseText);  
                //alert('Error deleting data');
                console.log( jqXHR);
                console.log(errorThrown);
                console.log(textStatus);
            }
        });
 
      } // fin del confirmar borrado
    }// fin funcion delete id
 
  </script>
 
  <!-- Bootstrap modal -->
  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Formulario Cliente</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="ID_CLIENTE"/>
          <div class="form-body">

            <div class="form-group">
              <label class="control-label col-md-3">Nombre:</label>
              <div class="col-md-9">
                <input name="NOMBRE_CLIENTE" placeholder="Nombre del cliente" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Domicilio:</label>
              <div class="col-md-9">
                <input name="DOMICILIO_CLIENTE" placeholder="Domicilio Cliente" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">RFC:</label>
              <div class="col-md-9">
                <input name="RFC_CLIENTE" placeholder="Rfc del Cliente" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Ciudad:</label>
              <div class="col-md-9">
                <input name="CIUDAD_CLIENTE" placeholder="Ciudad del Cliente" class="form-control" type="text"> 
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Estado:</label>
              <div class="col-md-9">
                <input name="ESTADO_CLIENTE" placeholder="Estado del Cliente" class="form-control" type="text"> 
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Telefono:</label>
              <div class="col-md-9">
                <input name="TELEFONO_CLIENTE" placeholder="Telefono del Cliente" class="form-control" type="text"> 
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Contacto:</label>
              <div class="col-md-9">
                <input name="CONTACTO_CLIENTE" placeholder="Contacto del Cliente" class="form-control" type="text"> 
              </div>
            </div>
    
            <div class="form-group">
              <label class="control-label col-md-3">Correo:</label>
              <div class="col-md-9">
                <input name="EMAIL_CLIENTE" placeholder="Email del Cliente" class="form-control" type="text"> 
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Correo Alterno:</label>
              <div class="col-md-9">
                <input name="EMAIL_ALTERNO_CLIENTE" placeholder="Email Alterno del Cliente" class="form-control" type="text"> 
              </div>
            </div>
            
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Grabar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
 
  <!--
  </body>
</html>-->