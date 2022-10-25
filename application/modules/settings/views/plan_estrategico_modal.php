<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/plan_estrategico.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/ajaxMetaProyecto.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Plan de Desarrollo Distrital
	<br><small><b>Objetivos Estratégicos: </b><?php echo $infoObjetivoEstrategico[0]['numero_objetivo_estrategico'] . ' ' . $infoObjetivoEstrategico[0]['objetivo_estrategico']; ?></small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddObjetivoEstrategico" name="hddObjetivoEstrategico" value="<?php echo $numeroObjetivoEstrategico; ?>"/>
		<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_proposito">Propósitos: *</label>
					<select name="id_proposito" id="id_proposito" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaPropositos); $i++) { ?>
							<option value="<?php echo $listaPropositos[$i]["numero_proposito"]; ?>" <?php if($information && $information[0]["fk_numero_proposito"] == $listaPropositos[$i]["numero_proposito"]) { echo "selected"; }  ?>><?php echo $listaPropositos[$i]["numero_proposito"] . ' ' . $listaPropositos[$i]["proposito"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_logros">Logro: *</label>
					<select name="id_logros" id="id_logros" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaLogros); $i++) { ?>
							<option value="<?php echo $listaLogros[$i]["numero_logro"]; ?>" <?php if($information && $information[0]["fk_numero_logro"] == $listaLogros[$i]["numero_logro"]) { echo "selected"; }  ?>><?php echo $listaLogros[$i]["numero_logro"] . ' ' . $listaLogros[$i]["logro"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_programa_sp">Programa SEGPLAN: *</label>
					<select name="id_programa_sp" id="id_programa_sp" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaProgramaSEGPLAN); $i++) { ?>
							<option value="<?php echo $listaProgramaSEGPLAN[$i]["numero_programa"]; ?>" <?php if($information && $information[0]["fk_numero_programa"] == $listaProgramaSEGPLAN[$i]["numero_programa"]) { echo "selected"; }  ?>><?php echo $listaProgramaSEGPLAN[$i]["numero_programa"] . ' ' . $listaProgramaSEGPLAN[$i]["programa"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_programa_estrategico">Programa Estratégico: *</label>
					<select name="id_programa_estrategico" id="id_programa_estrategico" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaPrograma); $i++) { ?>
							<option value="<?php echo $listaPrograma[$i]["numero_programa_estrategico"]; ?>" <?php if($information && $information[0]["fk_numero_programa_estrategico"] == $listaPrograma[$i]["numero_programa_estrategico"]) { echo "selected"; }  ?>><?php echo $listaPrograma[$i]["numero_programa_estrategico"] . ' ' . $listaPrograma[$i]["programa_estrategico"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_meta_pdd">Meta PDD: *</label>
					<select name="id_meta_pdd" id="id_meta_pdd" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaMetasPDD); $i++) { ?>
							<option value="<?php echo $listaMetasPDD[$i]["numero_meta_pdd"]; ?>" <?php if($information && $information[0]["fk_numero_meta_pdd"] == $listaMetasPDD[$i]["numero_meta_pdd"]) { echo "selected"; }  ?>><?php echo $listaMetasPDD[$i]["numero_meta_pdd"] . ' ' . $listaMetasPDD[$i]["meta_pdd"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_proyecto_inversion">Proyecto Inversión: *</label>
					<select name="id_proyecto_inversion" id="id_proyecto_inversion" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaProyectos); $i++) { ?>
							<option value="<?php echo $listaProyectos[$i]["numero_proyecto_inversion"]; ?>" <?php if($information && $information[0]["fk_numero_proyecto_inversion"] == $listaProyectos[$i]["numero_proyecto_inversion"]) { echo "selected"; }  ?>><?php echo $listaProyectos[$i]["numero_proyecto_inversion"] . ' ' . $listaProyectos[$i]["nombre_proyecto_inversion"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_meta_proyecto_inversion">Metas Proyectos de Inversión: *</label>
					<select name="id_meta_proyecto_inversion" id="id_meta_proyecto_inversion" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaMetasProyectos); $i++) { ?>
							<option value="<?php echo $listaMetasProyectos[$i]["nu_meta_proyecto"]; ?>" <?php if($information && $information[0]["fk_nu_meta_proyecto_inversion"] == $listaMetasProyectos[$i]["nu_meta_proyecto"]) { echo "selected"; }  ?>><?php echo $listaMetasProyectos[$i]["numero_meta_proyecto"] . ' ' . $listaMetasProyectos[$i]["meta_proyecto"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="id_ods">ODS: *</label>
					<select name="id_ods" id="id_ods" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaODS); $i++) { ?>
							<option value="<?php echo $listaODS[$i]["numero_ods"]; ?>" <?php if($information && $information[0]["fk_numero_ods"] == $listaODS[$i]["numero_ods"]) { echo "selected"; }  ?>><?php echo $listaODS[$i]["numero_ods"] . ' ' . $listaODS[$i]["ods"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="id_dimension">Dimensiones MIPG: *</label>
					<select name="id_dimension" id="id_dimension" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaDimensionesMIPG); $i++) { ?>
							<option value="<?php echo $listaDimensionesMIPG[$i]["id_dimension"]; ?>" <?php if($information && $information[0]["fk_id_dimension"] == $listaDimensionesMIPG[$i]["id_dimension"]) { echo "selected"; }  ?>><?php echo $listaDimensionesMIPG[$i]["id_dimension"] . ' ' . $listaDimensionesMIPG[$i]["nombre_dimension"]; ?></option>		
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