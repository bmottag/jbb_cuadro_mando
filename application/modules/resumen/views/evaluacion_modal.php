<script type="text/javascript" src="<?php echo base_url("assets/js/validate/resumen/evaluacion_objetivos.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Evaluación OCI
	<br><b>Actividad No.: </b> <?php echo $numeroActividad; ?>
	<br><b>Semestre: </b> <?php echo $numeroSemestre; ?>
	</h4>
</div>

<div class="modal-body small">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group text-left">
				<small>
				<?php if($numeroSemestre == 1){ ?>
					<label class="control-label" for="calificacion">Descrición Actividades Trimeste I: </label><br>
					<?php echo $infoActividad[0]["descripcion_actividad_trimestre_1"]; ?>
					<br>
					<label class="control-label" for="calificacion">Descrición Actividades Trimeste II: </label><br>
					<?php echo $infoActividad[0]["descripcion_actividad_trimestre_2"]; ?>
				<?php }else{ ?>
					<label class="control-label" for="calificacion">Descrición Actividades Trimeste III: </label><br>
					<?php echo $infoActividad[0]["descripcion_actividad_trimestre_3"]; ?>
					<br>
					<label class="control-label" for="calificacion">Descrición Actividades Trimeste IV: </label><br>
					<?php echo $infoActividad[0]["descripcion_actividad_trimestre_4"]; ?>
				<?php } ?>
				</small>
			</div>
		</div>
	</div>

	<hr>
<?php 
	if(!$information){ 
?>
	<p class="text-danger text-left">No hay registros.</p>
	<hr>
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
					<th><small>Unidad de Medida</small></th>
					<th><small>Avance de la Vigencia</small></th>
				</tr>
			</thead>

			<tbody>
                <?php
                    foreach ($information as $data):
                    	// Cambiar a un select con las opciones de porcentaje y otro cual en el futuro
                    	if ($data['unidad_medida'] == "porcentaje") {
                    		$avanceSemestral = round(($data['calificacion'] / 100) * $infoActividad[0]["meta_plan_operativo_anual"], 2);
                    	} else {
                    		$avanceSemestral = round(($data['calificacion'] / $infoActividad[0]["meta_plan_operativo_anual"]) * 100, 2);
                    	}
                ?>
                    <tr>
                        <td class="text-left"><small><?php echo $data['fecha_cambio']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['usuario']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['observacion']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['comentario']; ?></small></td>
                        <td class="text-right"><small><?php echo $data['calificacion']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['unidad_medida']; ?></small></td>
                        <td class="text-left"><small><?php echo $avanceSemestral; ?></small></td>
                    </tr>
                <?php
                    endforeach;
                ?>
			</tbody>
		</table>
		<hr>
<?php } ?>


<?php 

	$calificacion = "";
	$observacion = "";
	$unidadMedida = "";
	if($infoActividad && $numeroSemestre == 1){
		$calificacion = $infoActividad[0]["calificacion_semestre_1"];
		$unidadMedida = $infoActividad[0]["unidad_medida_semestre_1"];
		$observacion = $infoActividad[0]["observacion_semestre_1"];
	}else{
		$calificacion = $infoActividad[0]["calificacion_semestre_2"];
		$unidadMedida = $infoActividad[0]["unidad_medida_semestre_2"];
		$observacion = $infoActividad[0]["observacion_semestre_2"];		
	}
	$metaAnual = $infoActividad[0]["meta_plan_operativo_anual"];
	$descripcion = $infoActividad[0]["descripcion_actividad"];
?>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $infoActividad?$infoActividad[0]["numero_actividad"]:""; ?>"/>
		<input type="hidden" id="numeroSemestre" name="numeroSemestre" value="<?php echo $numeroSemestre; ?>"/>
		<input type="hidden" id="bandera" name="bandera" value="<?php echo $bandera; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<p><b>Meta Anual de la Actividad:</b> <?php echo $metaAnual; ?></p>
					<p><b>Descripción de la Actividad:</b> <?php echo $descripcion ?></p>
				</div>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="calificacion">Total evidenciado evaluación: *</label>
					<input type="number" id="calificacion" name="calificacion" class="form-control" value="<?php echo $calificacion; ?>" placeholder="Total evidenciado evaluación" required >
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="unidadMedida">Unidad de medida evaluación: *</label>
					<input type="text" id="unidadMedida" name="unidadMedida" class="form-control" value="<?php echo $unidadMedida; ?>" placeholder="Unidad de medida" style="text-transform:lowercase;" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="observacion">Observación de la actividad: *</label>
					<textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" required><?php echo $observacion; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="comentario">Comentario interno adicional OCI:</label>
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