<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/importar_actividad.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Importar Actividad
	<small>
		<br><b>Objetivo Estratégicos: </b><?php echo $infoObjetivoEstrategico[0]['numero_objetivo_estrategico'] . ' ' . $infoObjetivoEstrategico[0]['objetivo_estrategico']; ?>
		<br><b>Propósito: </b><?php echo $information[0]['proposito']; ?>
		<br><b>Logro: </b><?php echo $information[0]['logro']; ?>
		<br><b>Programa Estratégico: </b><?php echo $information[0]['programa']; ?>
		<br><b>Meta PDD: </b><?php echo $information[0]['meta_pdd']; ?>
		<br><b>Proyecto Inversión: </b><?php echo $information[0]['proyecto_inversion']; ?>
		<br><b>Metas Proyectos de Inversión: </b><?php echo $information[0]['meta_proyecto']; ?>
	</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddObjetivoEstrategico" name="hddObjetivoEstrategico" value="<?php echo $numeroObjetivoEstrategico; ?>"/>
		<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_actividad">Actividad: *</label>
					<select name="id_actividad" id="id_actividad" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaActividades); $i++) { ?>
							<option value="<?php echo $listaActividades[$i]["id_actividad"]; ?>"><?php echo $listaActividades[$i]["numero_actividad"] . ' ' . $listaActividades[$i]["descripcion_actividad"]; ?></option>		
						<?php } ?>
					</select>
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