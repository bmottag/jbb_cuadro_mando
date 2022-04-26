<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/metas_proyectos.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario Meta Proyectos Inversión
	<br><small>Adicionar/Editar Meta Proyectos Inversión.</small>
	</h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_meta_proyecto_inversion"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="numero_meta_proyecto">No. Meta Proyecto Inversión: *</label>
					<input type="number" min="1" max="9999" id="numero_meta_proyecto" name="numero_meta_proyecto" class="form-control" value="<?php echo $information?$information[0]["numero_meta_proyecto"]:""; ?>" placeholder="No. Meta Proyecto Inversión" required >
				</div>
			</div>
		</div>

		<div class="row">				
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="meta_proyecto">Meta Proyecto Inversión: *</label>
					<textarea id="meta_proyecto" name="meta_proyecto" placeholder="Meta Proyecto Inversión" class="form-control" rows="3" required><?php echo $information?$information[0]["meta_proyecto"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="presupuesto_meta">Presupuesto Meta: *</label>
					<input type="text" id="presupuesto_meta" name="presupuesto_meta" class="form-control" value="<?php echo $information?$information[0]["presupuesto_meta"]:""; ?>" placeholder="Presupuesto Meta" required >
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="proceso_calidad">Proceso Calidad: *</label>
					<input type="text" id="proceso_calidad" name="proceso_calidad" class="form-control" value="<?php echo $information?$information[0]["proceso_calidad"]:""; ?>" placeholder="Proyecto de Inversión" required >
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