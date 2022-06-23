<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/actividad_programar.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Programar Actividad
	<br><small><b>Actividad: </b> <?php echo $information[0]["numero_actividad"] . '-' . $information[0]["descripcion_actividad"]; ?>
	<br><b>Duración: </b> <?php echo $information[0]["mes_inicial"] . '-' . $information[0]["mes_final"]; ?></small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadrobase; ?>"/>
		<input type="hidden" id="hddNumeroActividad" name="hddNumeroActividad" value="<?php echo $information?$information[0]["numero_actividad"]:""; ?>"/>
		<input type="hidden" id="hddId" name="hddId" value=""/>

		<div class="row">	
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="mes">Mes: *</label>
					<select name="mes" id="mes" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaMeses); $i++) { ?>
							<option value="<?php echo $listaMeses[$i]["id_mes"]; ?>" ><?php echo $listaMeses[$i]["mes"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="programado">Programado: *</label>
					<input type="number" step="any" min="0" id="programado" name="programado" class="form-control" value="" placeholder="Programado" required >
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