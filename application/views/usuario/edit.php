<?php echo form_open('usuario/edit/'.$usuario['ID_USUARIO'],array("class"=>"form-horizontal")); ?>

	<div class="form-group">
		<label for="NOMBRE_USUARIO" class="col-md-4 control-label">NOMBRE USUARIO</label>
		<div class="col-md-8">
			<input type="text" name="NOMBRE_USUARIO" value="<?php echo ($this->input->post('NOMBRE_USUARIO') ? $this->input->post('NOMBRE_USUARIO') : $usuario['NOMBRE_USUARIO']); ?>" class="form-control" id="NOMBRE_USUARIO" />
		</div>
	</div>
	<div class="form-group">
		<label for="ALIAS_USUARIO" class="col-md-4 control-label">ALIAS USUARIO</label>
		<div class="col-md-8">
			<input type="text" name="ALIAS_USUARIO" value="<?php echo ($this->input->post('ALIAS_USUARIO') ? $this->input->post('ALIAS_USUARIO') : $usuario['ALIAS_USUARIO']); ?>" class="form-control" id="ALIAS_USUARIO" />
		</div>
	</div>
	<div class="form-group">
		<label for="CLAVE_USUARIO" class="col-md-4 control-label">CLAVE USUARIO</label>
		<div class="col-md-8">
			<input type="text" name="CLAVE_USUARIO" value="<?php echo ($this->input->post('CLAVE_USUARIO') ? $this->input->post('CLAVE_USUARIO') : $usuario['CLAVE_USUARIO']); ?>" class="form-control" id="CLAVE_USUARIO" />
		</div>
	</div>
	<div class="form-group">
		<label for="EMAIL_USUARIO" class="col-md-4 control-label">EMAIL USUARIO</label>
		<div class="col-md-8">
			<input type="text" name="EMAIL_USUARIO" value="<?php echo ($this->input->post('EMAIL_USUARIO') ? $this->input->post('EMAIL_USUARIO') : $usuario['EMAIL_USUARIO']); ?>" class="form-control" id="EMAIL_USUARIO" />
		</div>
	</div>
	<div class="form-group">
			<label for="TIPO_USUARIO" class="col-md-4 control-label">TIPO USUARIO</label>
			<div class="col-md-8">
				<select name="TIPO_USUARIO" class="form-control">
					<option value="">select</option>
					<?php 
					$TIPO_USUARIO_values = array(
						'Q'=>'Quimicos',
						'B'=>'Biologos',
						'C'=>'Capturista',
						'A'=>'Administrador',
						'E'=>'Encargado',
					);

					foreach($TIPO_USUARIO_values as $value => $display_text)
					{
						$selected = ($value == $usuario['TIPO_USUARIO']) ? ' selected="selected"' : null;

						echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
					} 
					?>
				</select>
			</div>
		</div>
	<div class="form-group">
		<label for="STATUS_USUARIO" class="col-md-4 control-label">STATUS USUARIO</label>
		<div class="col-md-8">
			<input type="text" name="STATUS_USUARIO" value="<?php echo ($this->input->post('STATUS_USUARIO') ? $this->input->post('STATUS_USUARIO') : $usuario['STATUS_USUARIO']); ?>" class="form-control" id="STATUS_USUARIO" />
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<button type="submit" class="btn btn-success">Save</button>
        </div>
	</div>
	
<?php echo form_close(); ?>