 
  <div class="container">

    <div class="panel panel-primary">
      
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $panel_title; ?></h3>
      </div><!--fin panel heading -->
      
      <div class="panel-body">

        <h1>CRUD Recepcion de Muestras</h1>

        <h3>Ensayos de Metodologias</h3>
        <br />
        <?php if ( $this->utilerias->permiso( 'ESTUDIOS','REGISTRAR' )  ) { ?>
        <button class="btn btn-success" onclick="add_estudio()"><i class="glyphicon glyphicon-plus"></i> Agrega ensayo</button>
        <?php } ?>
        
        <br />
        <br />
        <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ensayo</th>
              <th>Metodologia</th>
              <th>Area</th>
              <th>Alias</th>
              <th>Min</th>
              <th>Max</th>
              <th>Tope</th>
              <th>Precio</th>
              <th>Validado</th>
              <th>Acreditado</th>
              <th>Reconocido</th>
              <th style="width:125px;">Accion</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($estudios as $estudio){?>
                 <tr align="center">
                    <td><?php echo $estudio->ID_ESTUDIO;?></td>
                    <td><?php echo $estudio->ANALISIS_SOLICITADO;?></td>
                    <td><?php echo $estudio->METODOLOGIA_ESTUDIO;?></td>
                    <td><?php echo $estudio->AREA_ESTUDIO;?></td>
                    <td><?php echo $estudio->ALIAS_ESTUDIO;?></td>
                    <td><?php echo $estudio->DURACION_MIN_ESTUDIO;?></td>
                    <td><?php echo $estudio->DURACION_MAX_ESTUDIO;?></td>
                    <td><?php echo $estudio->TOPE_ESTUDIO;?></td>
                    <td align="right"><?php echo number_format($estudio->PRECIO_ESTUDIO,2);?></td>
                    <td align="center"><?php echo $estudio->VALIDADO_ESTUDIO;?></td>
                    <td align="center"><?php echo $estudio->ACREDITADO_ESTUDIO;?></td>
                    <td align="center"><?php echo $estudio->RECONOCIDO_ESTUDIO;?></td>
                    <td>
                    <?php if ( $this->utilerias->permiso( 'ESTUDIOS','CORREGIR' )  ) { ?>
                      <button class="btn btn-warning btn-xs" onclick="edit_estudio(<?php echo $estudio->ID_ESTUDIO;?>)"><i class="glyphicon glyphicon-pencil"></i></button>
                      <?php } ?>
                      <?php if ( $this->utilerias->permiso( 'ESTUDIOS','ELIMINAR' )  ) { ?>
                      <button class="btn btn-danger btn-xs" onclick="delete_estudio(<?php echo $estudio->ID_ESTUDIO;?>)"><i class="glyphicon glyphicon-remove"></i></button>
                      <?php } ?>
                    </td>
                  </tr>
                 <?php }?>
     
     
     
          </tbody>
     
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Ensayo</th>
              <th>Metodologia</th>
              <th>Area</th>
              <th>Alias</th>
              <th>Min</th>
              <th>Max</th>
              <th>Tope</th>
              <th>Precio</th>
              <th>Validado</th>
              <th>Acreditado</th>
              <th>Reconocido</th>
              <th>Accion</th>
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
 
 
    function add_estudio()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }
 
    function edit_estudio(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals
 
      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('/estudios_crud/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        { 
            $('[name="ID_ESTUDIO"]').val(data.ID_ESTUDIO);
            $('[name="ANALISIS_SOLICITADO"]').val(data.ANALISIS_SOLICITADO);
            $('[name="METODOLOGIA_ESTUDIO"]').val(data.METODOLOGIA_ESTUDIO);
            $('[name="AREA_ESTUDIO"]').val(data.AREA_ESTUDIO);
            $('[name="ALIAS_ESTUDIO"]').val(data.ALIAS_ESTUDIO);
            $('[name="DURACION_MIN_ESTUDIO"]').val(data.DURACION_MIN_ESTUDIO);
            $('[name="DURACION_MAX_ESTUDIO"]').val(data.DURACION_MAX_ESTUDIO); 
            $('[name="TOPE_ESTUDIO"]').val(data.TOPE_ESTUDIO); 
            $('[name="PRECIO_ESTUDIO"]').val(data.PRECIO_ESTUDIO);
            $('[name="VALIDADO_ESTUDIO"]').val(data.VALIDADO_ESTUDIO);
            $('[name="ACREDITADO_ESTUDIO"]').val(data.ACREDITADO_ESTUDIO);
            $('[name="RECONOCIDO_ESTUDIO"]').val(data.RECONOCIDO_ESTUDIO);
            $('[name="REFERENCIA_ESTUDIO"]').val(data.REFERENCIA_ESTUDIO);
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Modificación Ensayo'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
    }// fin de funcion edit_estudio
 
 
 
    function save()
    {
      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('/estudios_crud/estudio_add')?>";
      }
      else
      {
        url = "<?php echo site_url('/estudios_crud/estudio_update')?>";
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
                //alert( jqXHR.responseText);
                //alert( eval(jqXHR));
                alert( eval(errorThrown));
                console.log(textStatus, errorThrown,jqXHR);
            }
        });
    } // fin de funcion save
 
    function delete_estudio(id)
    {
      if(confirm('Seguro de Borrar el registro del estudio?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('/estudios_crud/estudio_delete')?>/"+id,
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
        <h3 class="modal-title">Formulario Ensayo</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="ID_ESTUDIO"/>
          <div class="form-body">

            <div class="form-group">
              <label class="control-label col-md-3">Nombre del Ensayo:</label>
              <div class="col-md-9">
                <input name="ANALISIS_SOLICITADO" placeholder="Analisis Solicitado del estudio" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Metodología:</label>
              <div class="col-md-9">
                <input name="METODOLOGIA_ESTUDIO" placeholder="Metodologia aplicada" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Area:</label>
              <div class="col-md-9">
                <input name="AREA_ESTUDIO" placeholder="Q = Quimicos, M = Microbiologia" class="form-control" type="text">
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Alias:</label>
              <div class="col-md-9">
                <input name="ALIAS_ESTUDIO" placeholder="Abrebiatura" class="form-control" type="text"> 
              </div>
            </div>
          
            <div class="form-group">
              <label class="control-label col-md-3">Extimación Min:</label>
              <div class="col-md-9">
                <input name="DURACION_MIN_ESTUDIO" placeholder="tiempo minimo realización" class="form-control" type="text"> 
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Extimación Max:</label>
              <div class="col-md-9">
                <input name="DURACION_MAX_ESTUDIO" placeholder="tiempo maximo realización" class="form-control" type="text"> 
              </div>
            </div>
    
            <div class="form-group">
              <label class="control-label col-md-3">Tope:</label>
              <div class="col-md-9">
                <input name="TOPE_ESTUDIO" placeholder="Dias Tope Realización" class="form-control" type="text"> 
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio:</label>
              <div class="col-md-9">
                <input name="PRECIO_ESTUDIO" placeholder="Costo del Estudio" class="form-control" type="text"> 
              </div>
            </div>
          </div>
          <!-- AGREGADO 15/05/2017 -->    
          <div class="form-group">
	          <label class="control-label col-md-3">Validado:</label>
	          <div class="col-md-9">                   
		        <select class="selectpicker form-control" name="VALIDADO_ESTUDIO">
					  <option value = "S" selected="selected">Si</option>
					  <option value= "N">No</option>					  
				</select>
			  </div>
		  </div>
		  
		  <div class="form-group">
	          <label class="control-label col-md-3">Acreditado:</label>
	          <div class="col-md-9">                   
		        <select class="selectpicker form-control" name="ACREDITADO_ESTUDIO">
					  <option value="S" selected="selected">Si</option>
					  <option Value="N">No</option>					  
				</select>
			  </div>
		  </div>    
		  
		  <!-- 2017-08-02 -->
		  <div class="form-group">
	          <label class="control-label col-md-3">Reconocido:</label>
	          <div class="col-md-9">                   
		        <select class="selectpicker form-control" name="RECONOCIDO_ESTUDIO">
					  <option value="S" selected="selected">Si</option>
					  <option Value="N">No</option>					  
				</select>
			  </div>
		  </div>    
          
          <!-- 2017-09-06 -->
		  <div class="form-group">
	          <label class="control-label col-md-3">Referencia:</label>
              <div class="col-md-9">
                <input name="REFERENCIA_ESTUDIO" placeholder="Referencia de Aplicacion" class="form-control" type="text">
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