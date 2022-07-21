<script type="text/javascript" src="<?php echo base_url("assets/js/validate/resumen/evaluacion.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Evaluación OCI
	<br><b>Actividad No.: </b> <?php echo $numeroActividad; ?>
	</h4>
</div>

<div class="modal-body small">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group text-left">
				<small>
				<label class="control-label" for="calificacion">Descrición Actividades Trimeste I: </label><br>
				<?php echo $infoActividad[0]["descripcion_actividad_trimestre_1"]; ?>
				<br>
				<label class="control-label" for="calificacion">Descrición Actividades Trimeste II: </label><br>
				<?php echo $infoActividad[0]["descripcion_actividad_trimestre_2"]; ?>
				</small>
			</div>
		</div>
	</div>

	<hr>
<?php 
	if(!$information){ 
?>
	<p class="text-danger text-left">No hay registros.</p>
<?php 
	}else{ 
?>
		<table class='table table-hover'>
			<thead>
				<tr class="headings">
					<th><small>Fecha</small></th>
					<th><small>Usuario</small></th>
					<th><small>Observación</small></th>
					<th><small>Comentario</small></th>
					<th class="text-right"><small>Calificación</small></th>
				</tr>
			</thead>

			<tbody>
                <?php 
                    foreach ($information as $data):     
                ?>
                    <tr>
                        <td class="text-left"><small><?php echo $data['fecha_cambio']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['usuario']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['observacion']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['comentario']; ?></small></td>
                        <td class="text-right"><small><?php echo $data['calificacion']; ?></small></td>
                    </tr>
                <?php
                    endforeach;
                ?>
			</tbody>
		</table>
<?php } ?>

	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $infoActividad?$infoActividad[0]["numero_actividad"]:""; ?>"/>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="calificacion">Calificación: *</label>
					<input type="number" id="calificacion" name="calificacion" class="form-control" value="<?php echo $infoActividad?$infoActividad[0]["calificacion_semestre_1"]:""; ?>" placeholder="Calificación" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="observacion">Observación: *</label>
					<textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" required><?php echo $infoActividad?$infoActividad[0]["observacion_semestre_1"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="comentario">Comentario adicional: *</label>
					<textarea id="comentario" name="comentario" class="form-control" rows="3" placeholder="Estos comentarios son unicamente de la Oficina de Control Interno"></textarea>
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