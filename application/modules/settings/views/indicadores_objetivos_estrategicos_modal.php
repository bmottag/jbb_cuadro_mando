<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/indicador_objetivos_estrategicos.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario Indicadores Objetivos Estratégicos
	<br><small>Adicionar/Editar Indicadores Objetivos Estratégicos</small>
	</h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_indicador"]:""; ?>"/>
			
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="numeroObjetivoEstrategico">Objetivo Estratégico: *</label>
					<select name="numeroObjetivoEstrategico" id="numeroObjetivoEstrategico" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaObjetivosEstrategicos); $i++) { ?>
							<option value="<?php echo $listaObjetivosEstrategicos[$i]["numero_objetivo_estrategico"]; ?>" <?php if($information && $information[0]["fk_numero_objetivo_estrategico"] == $listaObjetivosEstrategicos[$i]["numero_objetivo_estrategico"]) { echo "selected"; }  ?>><?php echo $listaObjetivosEstrategicos[$i]["numero_objetivo_estrategico"] . " " . $listaObjetivosEstrategicos[$i]["objetivo_estrategico"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">				
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="indicador">Indicador: *</label>
					<textarea id="indicador" name="indicador" placeholder="Indicador" class="form-control" rows="3" required><?php echo $information?$information[0]["indicador"]:""; ?></textarea>
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