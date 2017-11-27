<?php 
// definicion de variables
?>

<div class="container">
<div class="panel panel-default">
			<div class="panel-heading">Permisos de Usuario</div>
			<!-- las oopciones de busqueda-->
			<br/>
			<div class="row" class="form-group">
			    <div class="form-group col-xs-6 col-sm-3"> 
			      <label for="usuario">    Usuario:</label>
			      <?php echo form_dropdown('idUser',$UserCombo,$selected = 8,'class="form-control" id="idUser"'); ?>
			    </div>
			    <div class="form-group col-xs-6 col-sm-3"> 
			      <label for="usuario">Modulo:</label>
			      <?php echo form_dropdown('idModulo',$ModuloCombo,$selected = 8,'class="form-control" id="idModulo"'); ?>
			    </div>		    
			    <div class="form-group col-xs-6 col-sm-3"> 
			      <label for="usuario">Permiso:</label>			      
			      <?php echo form_dropdown('idPermiso',$PermisoCombo,$selected = 8,'class="form-control" id="idPermiso"'); ?>
			    </div>

  			    <div class="form-group col-xs-6 col-sm-1">
  			      <br>
			      <button type="button" value="Buscar" id='idBtnAgregarPermiso' class="btn btn-primary">Agregar</button>
			    </div>
			    
			</div> <!-- fin del row -->
			


			<div class="panel-body">
				<table id = "idTablaCrudPermisos" class="table  table-hover table-responsive">
					<thead>
						<tr>
							<th>Id</th>	<!--Columna Oculta -->
							<th>Fecha</th>
							<th>Usuario</th>
							<th>Modulo</th>
							<th>Permiso</th>
							<th>Acciones</th>
							
						</tr>
					</thead>
					<tbody>
					<!--//crud_permiso-->
						<?php
						foreach ($crud_permiso as $row ) { ?>
							<tr>	
								<td><?php echo $row['id_permisos_x_usuario']; ?></td>
								<td><?php echo $row['fecha_permisos_x_usuario']; ?></td>
								<td><?php echo $row['nombre_usuario']; ?></td>
								<td><?php echo $row['nombre_modulo']; ?></td>
								<td><?php echo $row['nombre_permiso']; ?></td>
								<!-- Acciones -->
								<td>
									<!--<span class="glyphicon glyphicon-trash" style="color:blue"></span>-->	
									<!--  cHtml += '<td><button type="button" class="btn btn-info btn-xs" onclick="deleteRowDetalladoResultado(this)" ><span class="glyphicon glyphicon-trash"></span></button></td>';
									
									onclick="deleteRowCrudPermiso(<?php echo $row['id_permisos_x_usuario'];?>,this)"
									-->
									<button type="button" id='idBtnBorraPermisoCrud' class="btn btn-info btn-xs esBorrable" value="<?php echo $row['id_permisos_x_usuario'];?>" ><span class="glyphicon glyphicon-trash" ></span></button>
								</td>
								
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div> <!-- fin del panel boddy -->
			
		</div>
		</div>

		
