<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/proyectos_x_vigencia.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario Proyectos de Inversión por Vigencia
	<br><small>Adicionar/Editar Proyectos de Inversió por Vigencia.</small>
	<?php if($information){
		echo "<br><small>Número Único: ". $information[0]["nu_proyecto_vigencia"] . "</small>";
	} ?>
	</h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_proyecto_vigencia"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="proyecto">Proyecto Inversión: </label>
					<select name="proyecto" id="proyecto" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaProyecto); $i++) { ?>
							<option value="<?php echo $listaProyecto[$i]["numero_proyecto_inversion"]; ?>" <?php if($information && $information[0]["fk_numero_proyecto_inversion"] == $listaProyecto[$i]["numero_proyecto_inversion"]) { echo "selected"; }  ?>><?php echo $listaProyecto[$i]["numero_proyecto_inversion"] . ' ' . $listaProyecto[$i]["nombre_proyecto_inversion"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="vigencia">Vigencia: *</label>
					<select name="vigencia" id="vigencia" class="form-control" required>
						<option value='' >Select...</option>
						<?php
							$year = date('Y');
							$lastYear = $year - 2;
							$nextYear = $year + 3;
							for ($i = $lastYear; $i < $nextYear; $i++) {
						?>
							<option value='<?php echo $i; ?>' <?php
							if ($information && $i == $information[0]["vigencia_proyecto"]) {
								echo 'selected="selected"';
							}
							?>><?php echo $i; ?></option>
						<?php } ?>									
					</select>
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="recurso_programado_proyecto">Recurso Programado: *</label>
					<input type="number" min="0" id="recurso_programado_proyecto" name="recurso_programado_proyecto" class="form-control" value="<?php echo $information?$information[0]["recurso_programado_proyecto"]:""; ?>" placeholder="Recurso Programado" required >
				</div>
			</div>
		</div>

		<div class="form-group">
			<div id="div_load" style="display:none">		
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% completado</span>
					</div>
				</div>
			</div>
			<div id="div_error" style="display:none">			
				<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
			</div>	
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>