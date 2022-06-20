<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/objetivos_estrategicos.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario Objetivos Estratégicos
	<br><small>Adicionar/Editar Objetivos Estratégicos</small>
	</h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_estrategia"]:""; ?>"/>
			
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="idEstrategia">Estrategia: *</label>
					<select name="idEstrategia" id="idEstrategia" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($estrategias); $i++) { ?>
							<option value="<?php echo $estrategias[$i]["id_estrategia"]; ?>" <?php if($information && $information[0]["fk_id_estrategia"] == $estrategias[$i]["id_estrategia"]) { echo "selected"; }  ?>><?php echo $estrategias[$i]["estrategia"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="numero_objetivo_estrategico">No. Objetivo Estratégico: *</label>
					<input type="number" min="1" max="9999" step="any" id="numero_objetivo_estrategico" name="numero_objetivo_estrategico" class="form-control" value="<?php echo $information?$information[0]["numero_objetivo_estrategico"]:""; ?>" placeholder="No. Objetivo Estratégico" required >
				</div>
			</div>
		</div>

		<div class="row">				
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="objetivo_estrategico">Objetivo Estratégico: *</label>
					<textarea id="objetivo_estrategico" name="objetivo_estrategico" placeholder="Objetivo Estratégico" class="form-control" rows="3" required><?php echo $information?$information[0]["objetivo_estrategico"]:""; ?></textarea>
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