<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/actividades.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Actividad
	<br><small>Adicionar/Editar Actividad</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadrobase; ?>"/>
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_actividad"]:""; ?>"/>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="numero">No. Actividad: *</label>
					<input type="number" id="numero_actividad" name="numero_actividad" class="form-control" value="<?php echo $information?$information[0]["numero_actividad"]:""; ?>" placeholder="No. Actividad" required >
				</div>
			</div>
			<div class="col-sm-4"></div>
			<div class="col-sm-4">		
				<div class="form-group text-left">
					<label class="control-label" for="id_dependencia">Dependencia: *</label>
					<select name="id_dependencia" id="id_dependencia" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaDependencia); $i++) { ?>
							<option value="<?php echo $listaDependencia[$i]["id_dependencia"]; ?>" <?php if($information && $information[0]["fk_id_dependencia"] == $listaDependencia[$i]["id_dependencia"]) { echo "selected"; }  ?>><?php echo $listaDependencia[$i]["dependencia"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">				
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="descripcion">Actividad: *</label>
					<textarea id="descripcion" name="descripcion" placeholder="Descripción" class="form-control" rows="2" required><?php echo $information?$information[0]["descripcion_actividad"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="meta_plan">Meta Plan Operativo Anual: *</label>
					<input type="number" id="meta_plan" name="meta_plan" class="form-control" value="<?php echo $information?$information[0]["meta_plan_operativo_anual"]:""; ?>" placeholder="Meta Plan Operativo Anual" required >
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="unidad_medida">Unidad de Medida: *</label>
					<input type="text" id="unidad_medida" name="unidad_medida" class="form-control" value="<?php echo $information?$information[0]["unidad_medida"]:""; ?>" placeholder="Unidad de Medida" required >
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="nombre_indicador">Nombre Indicador: *</label>
					<input type="text" id="nombre_indicador" name="nombre_indicador" class="form-control" value="<?php echo $information?$information[0]["nombre_indicador"]:""; ?>" placeholder="Nombre Indicador" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="tipo_indicador">Tipo Indicador: *</label>
					<select name="tipo_indicador" id="tipo_indicador" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["tipo_indicador"] == 1) { echo "selected"; }  ?>>Eficacia</option>
						<option value=2 <?php if($information && $information[0]["tipo_indicador"] == 2) { echo "selected"; }  ?>>Eficiencia</option>
						<option value=3 <?php if($information && $information[0]["tipo_indicador"] == 3) { echo "selected"; }  ?>>Efectividad</option>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="ponderacion">Ponderación: *</label>
					<input type="number" id="ponderacion" name="ponderacion" class="form-control" value="<?php echo $information?$information[0]["ponderacion"]:""; ?>" placeholder="Ponderación" required >
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="id_responsable">Responsable: *</label>
					<select name="id_responsable" id="id_responsable" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaAreaResponsable); $i++) { ?>
							<option value="<?php echo $listaAreaResponsable[$i]["id_area_responsable"]; ?>" <?php if($information && $information[0]["fk_id_area_responsable"] == $listaAreaResponsable[$i]["id_area_responsable"]) { echo "selected"; }  ?>><?php echo $listaAreaResponsable[$i]["area_responsable"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">	
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="fecha_inicial">Fecha Inicial: *</label>
					<select name="fecha_inicial" id="fecha_inicial" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaMeses); $i++) { ?>
							<option value="<?php echo $listaMeses[$i]["id_mes"]; ?>" <?php if($information && $information[0]["fecha_inicial"] == $listaMeses[$i]["id_mes"]) { echo "selected"; }  ?>><?php echo $listaMeses[$i]["mes"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="col-sm-4">		
				<div class="form-group text-left">
					<label class="control-label" for="fecha_final">Fecha Final: *</label>
					<select name="fecha_final" id="fecha_final" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($listaMeses); $i++) { ?>
							<option value="<?php echo $listaMeses[$i]["id_mes"]; ?>" <?php if($information && $information[0]["fecha_final"] == $listaMeses[$i]["id_mes"]) { echo "selected"; }  ?>><?php echo $listaMeses[$i]["mes"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-4">		
				<div class="form-group text-left">
					<label class="control-label" for="proceso_calidad">Proceso Calidad: *</label>
					<select name="proceso_calidad" id="proceso_calidad" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($proceso_calidad); $i++) { ?>
							<option value="<?php echo $proceso_calidad[$i]["id_proceso_calidad"]; ?>" <?php if($information && $information[0]["fk_id_proceso_calidad"] == $proceso_calidad[$i]["id_proceso_calidad"]) { echo "selected"; }  ?>><?php echo $proceso_calidad[$i]["proceso_calidad"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-lef">
					<p class="text-primary"><span class="glyphicon glyphicon-info-sign"></span> Planes Institucionales</p>	
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_archivos"><small><small>Plan Institucional de Archivos de la Entidad: *</small></small></label>
					<select name="plan_archivos" id="plan_archivos" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_archivos"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_archivos"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_adquisiciones"><small><small>Plan Anual de Adquisiciones: *</small></small></label>
					<select name="plan_adquisiciones" id="plan_adquisiciones" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_adquisiciones"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_adquisiciones"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_vacantes"><small><small>Plan Anual de Vacantes: *</small></small></label>
					<select name="plan_vacantes" id="plan_vacantes" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_vacantes"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_vacantes"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_recursos"><small><small>Plan de Previsión de Recursos Humanos: *</small></small></label>
					<select name="plan_recursos" id="plan_recursos" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_recursos"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_recursos"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_talento"><small><small>Plan Estratégico de Talento Humano: *</small></small></label>
					<select name="plan_talento" id="plan_talento" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_talento"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_talento"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_capacitacion"><small><small>Plan Institucional de Capacitación: *</small></small></label>
					<select name="plan_capacitacion" id="plan_capacitacion" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_capacitacion"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_capacitacion"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_incentivos"><small><small>Plan de Incentivos Institucionales: *</small></small></label>
					<select name="plan_incentivos" id="plan_incentivos" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_incentivos"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_incentivos"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_trabajo"><small><small>Plan de Trabajo Anual en Seguridad y Salud en el Trabajo: *</small></small></label>
					<select name="plan_trabajo" id="plan_trabajo" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_trabajo"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_trabajo"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_anticorrupcion"><small><small>Plan Anticorrupción y de Atención al Ciudadano: *</small></small></label>
					<select name="plan_anticorrupcion" id="plan_anticorrupcion" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_anticorrupcion"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_anticorrupcion"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_tecnologia"><small><small>Plan Estratégico de Tecnologías de la Información y las Comunicaciones: *</small></small></label>
					<select name="plan_tecnologia" id="plan_tecnologia" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_tecnologia"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_tecnologia"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_riesgos"><small><small>Plan de Tratamiento de Riesgos de Seguridad y Privacidad de la Información: *</small></small></label>
					<select name="plan_riesgos" id="plan_riesgos" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_riesgos"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_riesgos"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
			<div class="col-sm-3">		
				<div class="form-group text-left">
					<label class="control-label" for="plan_informacion"><small><small>Plan de Seguridad y Privacidad de la Información: *</small></small></label>
					<select name="plan_informacion" id="plan_informacion" class="form-control" required>
						<option value=''>Seleccione...</option>
						<option value=1 <?php if($information && $information[0]["plan_informacion"] == 1) { echo "selected"; }  ?>>Si</option>
						<option value=0 <?php if($information && $information[0]["plan_informacion"] == 0) { echo "selected"; }  ?>>N/A</option>
					</select>
				</div>
			</div>
		</div>

		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-lef">
					<p class="text-primary"><span class="glyphicon glyphicon-info-sign"></span> SEGPLAN</p>	
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
							<option value="<?php echo $listaIndicadorPMR[$i]["id_indicador_pmr"]; ?>" <?php if($information && $information[0]["fk_numero_indicador_pmr"] == $listaIndicadorPMR[$i]["numero_indicador_pmr"]) { echo "selected"; }  ?>><?php echo $listaIndicadorPMR[$i]["numero_indicador_pmr"] . ' ' . $listaIndicadorPMR[$i]["indicador_pmr"]; ?></option>	
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