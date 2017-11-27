  <div class="container">

    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div><!--fin panel heading -->
      
      <div class="panel-body">

        <h1>CRUD Recepcion de Muestras</h1>

        <h3>Usuarios del Sistema</h3>
        <br />
        <?php if ( $this->utilerias->permiso( 'USUARIOS','REGISTRAR' )  ) { ?>
        <button class="btn btn-success" onclick="add_usuario()"><i class="glyphicon glyphicon-plus"></i> Agrega usuario</button>
        <?php } ?>
        
        <br />
        
        <br />
        <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th>Alias</th>
              <!--<th>Password</th>-->
              <th>Correo</th>
              <th>Tipo</th>
              <th>Signatario</th>
              <th>Status</th>              
              <th style="width:125px;">Acción</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($usuarios as $usuario){?>
                 <tr>
                    <td><?php echo $usuario->ID_USUARIO;?></td>
                    <td><?php echo $usuario->NOMBRE_USUARIO;?></td>
                    <td><?php echo $usuario->CARGO_USUARIO;?></td>
                    <td><?php echo $usuario->ALIAS_USUARIO;?></td>
                    <!--<td><?php echo $usuario->CLAVE_USUARIO;?></td>-->
                    <td><?php echo $usuario->EMAIL_USUARIO;?></td>
                    <td><?php echo $usuario->TIPO_USUARIO;?></td>
                    <td><?php echo $usuario->SIGNATARIO_USUARIO;?></td>
                    <td><?php echo $usuario->STATUS_USUARIO;?></td>                    
                    <td>
                    <?php if ( $this->utilerias->permiso( 'USUARIOS','CORREGIR' )  ) { ?>
                      <button class="btn btn-warning btn-xs" onclick="edit_usuario(<?php echo $usuario->ID_USUARIO;?>)"><i class="glyphicon glyphicon-pencil"></i></button>
                      <?php } ?>
                      <?php if ( $this->utilerias->permiso( 'USUARIOS','ELIMINAR' )  ) { ?>
                      <button class="btn btn-danger btn-xs" onclick="delete_usuario(<?php echo $usuario->ID_USUARIO;?>)"><i class="glyphicon glyphicon-remove"></i></button>
                      <?php } ?>
                    </td>
                  </tr>
                 <?php }?>
     
     
     
          </tbody>
     
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th>Alias</th>
              <th>Password</th>
              <!--<th>Correo</th>-->
              <th>Tipo</th>
              <th>Status</th>              
              <th>Acción</th>
            </tr>
          </tfoot>
        </table>
     
      </div> <!-- fin del container -->
    </div> <!-- fin del panel body-->
  </div> <!-- fin del panel -->
 
  <!--<script src="<?php echo base_url('assets/jquery/jquery-3.1.0.min.js')?>"></script>-->
  <!--<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
  
  <!--<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
  <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
  <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>-->
 
 
  <script type="text/javascript">
  
    var save_method; //for save method string
    var table;
 
 
    function add_usuario()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }
 
    function edit_usuario(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals
 
      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('/usuarios_crud/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        { 
            $('[name="ID_USUARIO"]').val(data.ID_USUARIO);
            $('[name="NOMBRE_USUARIO"]').val(data.NOMBRE_USUARIO);
            $('[name="CARGO_USUARIO"]').val(data.CARGO_USUARIO);
            $('[name="ALIAS_USUARIO"]').val(data.ALIAS_USUARIO);
            $('[name="CLAVE_USUARIO"]').val(data.CLAVE_USUARIO);
            $('[name="EMAIL_USUARIO"]').val(data.EMAIL_USUARIO);
            $('[name="TIPO_USUARIO"]').val(data.TIPO_USUARIO);
            $('[name="SIGNATARIO_USUARIO"]').val(data.SIGNATARIO_USUARIO);
            $('[name="STATUS_USUARIO"]').val(data.STATUS_USUARIO); 
            
            $('[name="TITULO_USUARIO"]').val(data.TITULO_USUARIO); 
            $('[name="INICIALES_USUARIO"]').val(data.INICIALES_USUARIO); 
            //$('[name="SIGNATARIO_USUARIO"]').val(data.SIGNATARIO_USUARIO);
            $('[name="FECHA_NACIMIENTO_USUARIO"]').val(data.FECHA_NACIMIENTO_USUARIO);
                        
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Modificación Analisis'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
    }// fin de funcion edit_usuario
 
 
 
    function save()
    {
      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('/usuarios_crud/usuario_add')?>";
      }
      else
      {
        url = "<?php echo site_url('/usuarios_crud/usuario_update')?>";
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
                alert( eval(errorThrown));
                //console.log(textStatus, errorThrown,jqXHR);
                console.log(jqXHR);
            }
        });
    } // fin de funcion save
 
    function delete_usuario(id)
    {
      if(confirm('Seguro de Borrar el registro del usuario?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('/usuarios_crud/usuario_delete')?>/"+id,
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
        <h3 class="modal-title">Formulario usuario</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="ID_USUARIO"/>
          <div class="form-body">

            <div class="form-group">
              <label class="control-label col-md-3">Nombre:</label>
              <div class="col-md-9">
                <input name="NOMBRE_USUARIO" placeholder="Nombre Completo del Usuario" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Cargo:</label>
              <div class="col-md-9">
                <input name="CARGO_USUARIO" placeholder="Cargo" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Alias:</label>
              <div class="col-md-9">
                <input name="ALIAS_USUARIO" placeholder="Nombre y un apellido" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Password:</label>
              <div class="col-md-9">
                <input name="CLAVE_USUARIO" placeholder="Abrebiatura" class="form-control" type="text"> 
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Correo:</label>
              <div class="col-md-9">
                <input name="EMAIL_USUARIO" placeholder="Correo Electronico" class="form-control" type="text"> 
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Tipo:</label>
              <div class="col-md-9">
                <input name="TIPO_USUARIO" placeholder="Tipo de Usuario" class="form-control" type="text"> 
              </div>
            </div>
    
            <div class="form-group">
              <label class="control-label col-md-3">Status:</label>
              <div class="col-md-9">
                <input name="STATUS_USUARIO" placeholder="Status del Usuario" class="form-control" type="text"> 
            </div>
            </div>
                        
            <div class="form-group">
              <label class="control-label col-md-3">Signatario:</label>
              <div class="col-md-9">
                <input name="SIGNATARIO_USUARIO" placeholder="Es Usuario Signatario" class="form-control" type="text"> 
            </div>
            </div>
            
            
            <div class="form-group">
              <label class="control-label col-md-3">Iniciales:</label>
              <div class="col-md-9">
                <input name="INICIALES_USUARIO" placeholder="Iniciales del Usuario" class="form-control" type="text"> 
            </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Fecha Nacimiento:</label>
              <div class="col-md-9">
                <input name="FECHA_NACIMIENTO_USUARIO" placeholder="Fecha de Nacimiento del Usuario" class="form-control" type="text"> 
            </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Titulo:</label>
              <div class="col-md-9">
                <input name="TITULO_USUARIO" placeholder="Titulo Universitario o Maximo Logrado" class="form-control" type="text"> 
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