<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/form_estado_actividad.js"); ?>"></script>

<script>
$(function(){ 
	$(".btn-info").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'dashboard/cargarModalActividad',
				data: {'idCuadrobase': oID, 'idActividad': 'x'},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	

	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'dashboard/cargarModalActividad',
				data: {'idCuadrobase': '', 'idActividad': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});

	$(".btn-warning").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'dashboard/cargarModalProgramarActividad',
				data: {'idActividad': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatosEjecucion').html(data);
                }
            });
	});	
});
</script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<!-- Start of menu -->
		<?php
			$this->load->view('menu');
		?>
		<!-- End of menu -->
		<div class="col-lg-9">
			<div class="panel panel-info small">
				<div class="panel-heading">
					<i class="fa fa-thumb-tack"></i> <strong>ACTIVIDADES </strong>
					<div class="pull-right">
						<div class="btn-group">
							<?php
								$userRol = $this->session->userdata("role");
								if($numeroActividad != 'x' ){
							?>
									<a class="btn btn-primary btn-xs" href=" <?php echo base_url('dashboard/actividades/' . $idCuadroBase); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a> 
							<?php
								}elseif($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
							?>
									<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $infoCuadroBase[0]['id_cuadro_base']; ?>">
											<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Actividades
									</button>
							<?php
								}
							?>
						</div>
					</div>
				</div>
				<div class="panel-body">
				
<?php
	$retornoExito = $this->session->flashdata('retornoExito');
	if ($retornoExito) {
?>
		<div class="alert alert-success ">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo $retornoExito ?>		
		</div>
<?php
	}
	$retornoError = $this->session->flashdata('retornoError');
	if ($retornoError) {
?>
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo $retornoError ?>
		</div>
<?php
	}
?> 

<?php 										
	if(!$listaActividades){ 
		echo '<div class="col-lg-12">
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No hay actividades en el sistema.</p>
			</div>';
	}else{
?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th>Actividad</th>
								<th>Meta Plan Operativo Anual</th>
								<th class="text-center">Unidad Medida</th>
								<th>Nombre Indicador</th>
								<th>Tipo Indicador</th>
								<th>Ponderación</th>
								<th>Fechas</th>
								<th>Responsable</th>
								<th class="text-center">Enlaces</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($listaActividades as $lista):
								$unidadMedida = $lista['unidad_medida'];
								$clase = "text-danger";

								switch ($lista['tipo_indicador']) {
									case 1:
										$valor2 = 'Eficacia';
										$clase2 = "text-success";
										break;
									case 2:
										$valor2 = 'Eficiencia';
										$clase2 = "text-danger";
										break;
									case 3:
										$valor2 = 'Efectividad';
										$clase2 = "text-primary";
										break;
								}
								$ponderacion = $lista['ponderacion'];
								echo "<tr>";
								echo "<td class='text-center'>" . $lista['numero_actividad'] . "</td>";
								echo "<td>" . $lista['descripcion_actividad'] . "</td>";
								echo "<td>" . $lista['meta_plan_operativo_anual'] . "</td>";
								echo "<td class='text-center'>";
								echo '<p class="' . $clase . '"><strong>' . $unidadMedida . '</strong></p>';
								echo "</td>";
								echo "<td>" . $lista['nombre_indicador'] . "</td>";
								echo "<td class='text-center'>";
								echo '<p class="' . $clase2 . '"><strong>' . $valor2 . '</strong></p>';
								echo "</td>";
								echo "<td class='text-right'>" . $lista['ponderacion'] . "%</td>";
								echo "<td class='text-center'>";
								echo $lista['mes_inicial'] . '-' . $lista['mes_final'];
								echo "</td>";
								echo "<td>" . $lista['responsable'] . "</td>";
								echo "<td class='text-center'>";
        
    							if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_actividad']; ?>" title="Editar Actividad">
										<span class="fa fa-pencil" aria-hidden="true">
									</button>
						<?php
							if($numeroActividad != 'x') {
						?>
									<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEjecucion" id="<?php echo $lista['id_actividad']; ?>" title="Adicionar Fecha a la Actividad">
											<i class="fa fa-signal"></i>
									</button>

									<button type="button" id="<?php echo $lista["id_actividad"]; ?>" class='btn btn-danger btn-xs' title="Eliminar Actividad">
											<span class="fa fa-trash-o" aria-hidden="true"> </span>
									</button>
						<?php
								}
							}

							if($numeroActividad == 'x') {
								echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "' title='Ver Detalle Actividad'> <span class='fa fa-eye' aria-hidden='true'></a>";
							}
								echo "</td>";
								echo "</tr>";

								$arrParam = array("numeroActividad" => $lista["numero_actividad"]);
								$estadoActividad = $this->general_model->get_estados_actividades($arrParam);

								$sumaProgramado = $this->general_model->sumarProgramado($arrParam);
								$sumaEjecutado = $this->general_model->sumarEjecutado($arrParam);
								$arrParam['numeroTrimestre'] = 1;
								$sumaProgramadoTrimestre1 = $this->general_model->sumarProgramado($arrParam);
								$sumaEjecutadoTrimestre1 = $this->general_model->sumarEjecutado($arrParam);
								$arrParam['numeroTrimestre'] = 2;
								$sumaProgramadoTrimestre2 = $this->general_model->sumarProgramado($arrParam);
								$sumaEjecutadoTrimestre2 = $this->general_model->sumarEjecutado($arrParam);
								$arrParam['numeroTrimestre'] = 3;
								$sumaProgramadoTrimestre3 = $this->general_model->sumarProgramado($arrParam);
								$sumaEjecutadoTrimestre3 = $this->general_model->sumarEjecutado($arrParam);
								$arrParam['numeroTrimestre'] = 4;
								$sumaProgramadoTrimestre4 = $this->general_model->sumarProgramado($arrParam);
								$sumaEjecutadoTrimestre4 = $this->general_model->sumarEjecutado($arrParam);

								$cumplimiento1 = 0;
								$cumplimiento2 = 0;
								$cumplimiento3 = 0;
								$cumplimiento4 = 0;
								$avancePOA1 = 0;
								$avancePOA2 = 0;
								$avancePOA3 = 0;
								$avancePOA4 = 0;
								$avancePOA = 0;
								if($sumaProgramado['programado'] > 0){
									$avancePOA = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * $ponderacion,2);
								}
								if($sumaProgramadoTrimestre1['programado'] > 0){
									$cumplimiento1 = round($sumaEjecutadoTrimestre1['ejecutado'] / $sumaProgramadoTrimestre1['programado'] * 100, 2);
									$avancePOA1 = round($sumaEjecutadoTrimestre1['ejecutado'] / $sumaProgramadoTrimestre1['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre2['programado'] > 0){
									$cumplimiento2 = round($sumaEjecutadoTrimestre2['ejecutado'] / $sumaProgramadoTrimestre2['programado'] * 100,2);
									$avancePOA2 = round($sumaEjecutadoTrimestre2['ejecutado'] / $sumaProgramadoTrimestre2['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre3['programado'] > 0){
									$cumplimiento3 = round($sumaEjecutadoTrimestre3['ejecutado'] / $sumaProgramadoTrimestre3['programado'] * 100,2);
									$avancePOA3 = round($sumaEjecutadoTrimestre3['ejecutado'] / $sumaProgramadoTrimestre3['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre4['programado'] > 0){
									$cumplimiento4 = round($sumaEjecutadoTrimestre4['ejecutado'] / $sumaProgramadoTrimestre4['programado'] * 100,2);
									$avancePOA4 = round($sumaEjecutadoTrimestre4['ejecutado'] / $sumaProgramadoTrimestre4['programado'] * $ponderacion, 2) . '%';
								}

?>
								<thead>
									<tr class="headings info">
										<th class="column-title" colspan="2">
											<p>Programado Año: <?php echo number_format($sumaProgramado['programado'],2); ?></p>
											<p>Acance POA: <?php echo $avancePOA . '%'; ?></p>
										</th>
										<th class="column-title" colspan="2">
											<p>Programado Trimestre I: <?php echo number_format($sumaProgramadoTrimestre1['programado'],2); ?></p>
											<p>Programado Trimestre II: <?php echo number_format($sumaProgramadoTrimestre2['programado'],2); ?></p>
											<p>Programado Trimestre III: <?php echo number_format($sumaProgramadoTrimestre3['programado'],2); ?></p>
											<p>Programado Trimestre IV: <?php echo number_format($sumaProgramadoTrimestre4['programado'],2); ?></p>
										</th>
										<th class="column-title" colspan="2">
											<p>Cumplimiento Trimestre I: <?php echo $cumplimiento1 . '%'; ?></p>
											<p>Cumplimiento Trimestre II: <?php echo $cumplimiento2 . '%'; ?></p>
											<p>Cumplimiento Trimestre III: <?php echo $cumplimiento3 . '%'; ?></p>
											<p>Cumplimiento Trimestre IV: <?php echo $cumplimiento4 . '%'; ?></p>
										</th>

										<th class="column-title small">
											<?php if($estadoActividad){ ?>
											<p class="<?php echo $estadoActividad[0]['primer_clase']; ?>"><strong><?php echo $estadoActividad[0]['primer_estado']; ?></strong></p>
											<p class="<?php echo $estadoActividad[0]['segundo_clase']; ?>"><strong><?php echo $estadoActividad[0]['segundo_estado']; ?></strong></p>
											<p class="<?php echo $estadoActividad[0]['tercer_clase']; ?>"><strong><?php echo $estadoActividad[0]['tercer_estado']; ?></strong></p>
											<p class="<?php echo $estadoActividad[0]['cuarta_clase']; ?>"><strong><?php echo $estadoActividad[0]['cuarta_estado']; ?></strong></p>
											<?php } ?>
										</th>
										<th class="column-title">
											<p><a class='btn btn-warning btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/1') ?>' title="Seguimiento I">
													Revisión I <span class="fa fa-tag" aria-hidden="true"> </span>
											</a></p>
											<p><a class='btn btn-warning btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/2') ?>' title="Seguimiento II">
													Revisión II <span class="fa fa-tag" aria-hidden="true"> </span>
											</a></p>
											<p><a class='btn btn-warning btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/3') ?>' title="Seguimiento III">
													Revisión III <span class="fa fa-tag" aria-hidden="true"> </span>
											</a></p>
											<p><a class='btn btn-warning btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/4') ?>' title="Seguimiento IV">
													Revisión IV <span class="fa fa-tag" aria-hidden="true"> </span>
											</a></p>											
										</th>					
										<th class="column-title" colspan="2">
											<p>Avance POA I: <?php echo $avancePOA1; ?></p>
											<p>Avance POA II: <?php echo $avancePOA2; ?></p>
											<p>Avance POA III: <?php echo $avancePOA3; ?></p>
											<p>Avance POA IV: <?php echo $avancePOA4; ?></p>
										</th>
									</tr>
								</thead>

<?php
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>

<!-- INICIO HISTORICO -->
		<?php
			if($infoEjecucion){
				if($numeroTrimestre){
		?>
<!--INICIO ADDITIONAL INFORMATION -->
	<div class="row">
		<div class="col-lg-6">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					REVISIÓN EJECUCIÓN TRIMESTRE <?php echo $numeroTrimestre; ?>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">	
						<form name="formEstado" id="formEstado" class="form-horizontal" method="post">
							<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
							<input type="hidden" id="hddNumeroActividad" name="hddNumeroActividad" value="<?php echo $lista['numero_actividad']; ?>"/>
							<input type="hidden" id="hddNumeroTrimestre" name="hddNumeroTrimestre" value="<?php echo $numeroTrimestre; ?>"/>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="estado">Estado:</label>
								<div class="col-sm-8">
									<select name="estado" id="estado" class="form-control" required >
										<option value="">Seleccione...</option>
										<option value=3 >Aprobada (Escalar a planeación)</option>
										<option value=4 >Rechazada (Devolver al enlace)</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="information">Observación:</label>
								<div class="col-sm-8">
								<textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" required ></textarea>
								</div>
							</div>
							
							<div class="form-group">
								<div class="row" align="center">
									<div style="width:100%;" align="center">
										<button type="button" id="btnEstado" name="btnEstado" class="btn btn-primary" >
											Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" />
										</button> 
										
									</div>
								</div>
							</div>							
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-6">	
			<div class="chat-panel panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-comments fa-fw"></i> Historial
				</div>
				<div class="panel-body">
					<ul class="chat">
<?php 
	if($listaHistorial)
	{
		foreach ($listaHistorial as $data):		
?>
			<li class="right clearfix">
				<span class="chat-img pull-right">
					<small class="pull-right text-muted">
						<i class="fa fa-clock-o fa-fw"></i> <?php echo $data['fecha_cambio']; ?>
					</small>
				</span>
				<div class="chat-body clearfix">
					<div class="header">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						<strong class="primary-font"><?php echo $data['first_name']; ?></strong>
					</div>
					<p>
						<?php echo $data['observacion']; ?>
					</p>
					<?php echo '<p class="' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
				</div>
			</li>
<?php
		endforeach;
	}
?>
					</ul>
					
				</div>
			</div>
		</div>		
	</div>
<!--FIN ADDITIONAL INFORMATION -->
		<?php
				}
		?>
					<div class="table-responsive">
						<form  name="ejecucion" id="ejecucion" method="post" action="<?php echo base_url("dashboard/update_programacion"); ?>">

							<input type="hidden" id="hddIdActividad" name="hddIdActividad" value="<?php echo $numeroActividad; ?>"/>
							<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>		

							<table id="dataTablesWorker" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
								<thead>
									<tr class="headings">
										<th class="column-title" colspan="4">-- EJECUCIÓN ACTIVIDAD --
											<?php
												if($numeroTrimestre){
													echo '<p class="text-primary">Trimestre ' . $numeroTrimestre. '</p>'; 
												}
											?>
										</th>
									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
									?>
										<th class="column-title" >
											<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2" >
												Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
										</th>
									<?php
										}
									?>
									</tr>
									
									<tr class="headings">
										<th class="column-title" style="width: 7%">Mes</th>
										<th class="column-title" style="width: 10%">Programado (<?php echo $unidadMedida; ?>)</th>
										<th class="column-title" style="width: 13%">Ejecutado (<?php echo $unidadMedida; ?>)</th>
										<th class="column-title" style="width: 50%">Descripción / Evidencias</th>
										<th class="column-title text-center" style="width: 10%">Enlaces</th>
									</tr>
								</thead>

								<tbody>
								<?php
									foreach ($infoEjecucion as $data):
										echo "<tr>";
										echo "<td >$data[mes]</td>";							
										$idRecord = $data['id_ejecucion_actividad'];
										$idActividad = $data['fk_numero_actividad'];
								?>		
										<input type="hidden" name="form[id][]" value="<?php echo $idRecord; ?>"/>
										<td ><?php echo $data['programado']; ?></td>
										<td>
											<?php
												echo $data['ejecutado']; 
											?>
										</td>
										<td>
											<?php
												if($data['descripcion_actividades'] != ''){
													echo "<b>Descripción:</b></br>" . $data['descripcion_actividades'];
												}
												if($data['evidencias'] != ''){
													echo "<br><b>Evidencias:</b></br>" . $data['evidencias'];
												}
											?>
										</td>
										<td class='text-center'>
									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
									?>
											<a class='btn btn-violeta btn-xs' href='<?php echo base_url('dashboard/deleteEjecucion/' . $idCuadroBase . '/' . $idActividad . '/' . $idRecord) ?>' id="btn-delete" title="Eliminar Fecha" >
													<span class="fa fa-trash-o" aria-hidden="true"> </span>
											</a>
									<?php
										}
									?>
										</td>
								<?php
										echo "</tr>";
									endforeach;
								?>
								</tbody>
							</table>
						</form>
					</div>	
		<?php
			}
		?>
<!-- FIN HISTORICO -->
				</div>
			</div>
		</div>
	</div>
</div>
		
<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal -->

<!--INICIO Modal -->
<div class="modal fade text-center" id="modalEjecucion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosEjecucion">

		</div>
	</div>
</div>                       
<!--FIN Modal -->

<!-- Tables -->
<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
		 "ordering": false,
		 paging: false,
		"searching": false,
		"info": false
    });
});
</script>