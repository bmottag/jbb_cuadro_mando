<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/tablero_pmr.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Tablero PMR</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddIdPMR" name="hddIdPMR" value="<?php echo $idPMR; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_objetivo_pmr">Objetivo: *</label>
					<select name="id_objetivo_pmr" id="id_objetivo_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaObjetivoPMR); $i++) { ?>
							<option value="<?php echo $listaObjetivoPMR[$i]["numero_objetivo_pmr"]; ?>" <?php if($information && $information[0]["fk_numero_objetivo_pmr"] == $listaObjetivoPMR[$i]["numero_objetivo_pmr"]) { echo "selected"; }  ?>><?php echo $listaObjetivoPMR[$i]["numero_objetivo_pmr"] . ' ' . $listaObjetivoPMR[$i]["objetivo_pmr"]; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_elemento_pep_pmr">Elemento PEP: *</label>
					<select name="id_elemento_pep_pmr" id="id_elemento_pep_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaElementoPEP); $i++) { ?>
							<option value="<?php echo $listaElementoPEP[$i]["id_elemento_pep_pmr"]; ?>" <?php if($information && $information[0]["fk_id_elemento_pep_pmr"] == $listaElementoPEP[$i]["id_elemento_pep_pmr"]) { echo "selected"; }  ?>><?php echo $listaElementoPEP[$i]["id_elemento_pep_pmr"] . ' ' . $listaElementoPEP[$i]["elemento_pep_pmr"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_producto_pmr">Producto: *</label>
					<select name="id_producto_pmr" id="id_producto_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaProductoPMR); $i++) { ?>
							<option value="<?php echo $listaProductoPMR[$i]["numero_producto_pmr"]; ?>" <?php if($information && $information[0]["fk_numero_producto_pmr"] == $listaProductoPMR[$i]["numero_producto_pmr"]) { echo "selected"; }  ?>><?php echo $listaProductoPMR[$i]["numero_producto_pmr"] . ' ' . $listaProductoPMR[$i]["producto_pmr"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_proyecto_inversion">Proyecto de Inversión: *</label>
					<select name="id_proyecto_inversion" id="id_proyecto_inversion" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaProyectoInversion); $i++) { ?>
							<option value="<?php echo $listaProyectoInversion[$i]["numero_proyecto_inversion"]; ?>" <?php if($information && $information[0]["fk_numero_proyecto_inversion"] == $listaProyectoInversion[$i]["numero_proyecto_inversion"]) { echo "selected"; }  ?>><?php echo $listaProyectoInversion[$i]["numero_proyecto_inversion"] . ' ' . $listaProyectoInversion[$i]["nombre_proyecto_inversion"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_indicador_pmr">Indicador PMR: *</label>
					<select name="id_indicador_pmr" id="id_indicador_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaIndicadorPMR); $i++) { ?>
							<option value="<?php echo $listaIndicadorPMR[$i]["numero_indicador_pmr"]; ?>" <?php if($information && $information[0]["fk_numero_indicador_pmr"] == $listaIndicadorPMR[$i]["numero_indicador_pmr"]) { echo "selected"; }  ?>><?php echo $listaIndicadorPMR[$i]["numero_indicador_pmr"] . ' ' . $listaIndicadorPMR[$i]["indicador_pmr"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="id_unidad_medida_pmr">Unidad de Medida: *</label>
					<select name="id_unidad_medida_pmr" id="id_unidad_medida_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaUnidadMedida); $i++) { ?>
							<option value="<?php echo $listaUnidadMedida[$i]["id_unidad_medida_pmr"]; ?>" <?php if($information && $information[0]["fk_id_unidad_medida_pmr"] == $listaUnidadMedida[$i]["id_unidad_medida_pmr"]) { echo "selected"; }  ?>><?php echo $listaUnidadMedida[$i]["unidad_medida_pmr"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="id_naturaleza_pmr">Naturaleza: *</label>
					<select name="id_naturaleza_pmr" id="id_naturaleza_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaNaturalezaPMR); $i++) { ?>
							<option value="<?php echo $listaNaturalezaPMR[$i]["id_naturaleza_pmr"]; ?>" <?php if($information && $information[0]["fk_id_naturaleza_pmr"] == $listaNaturalezaPMR[$i]["id_naturaleza_pmr"]) { echo "selected"; }  ?>><?php echo $listaNaturalezaPMR[$i]["naturaleza_pmr"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="id_periodicidad_pmr">Periodicidad: *</label>
					<select name="id_periodicidad_pmr" id="id_periodicidad_pmr" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaPeriodicidadPMR); $i++) { ?>
							<option value="<?php echo $listaPeriodicidadPMR[$i]["id_periodicidad_pmr"]; ?>" <?php if($information && $information[0]["fk_id_periodicidad_pmr"] == $listaPeriodicidadPMR[$i]["id_periodicidad_pmr"]) { echo "selected"; }  ?>><?php echo $listaPeriodicidadPMR[$i]["periodicidad_pmr"]; ?></option>		
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