<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/actividades_ejecucion.js"); ?>"></script>
<div id="page-wrapper">
	<br>
	
	<div class="row">
		<?php
			$this->load->view('menu');
		?>
		<div class="col-lg-9">
			<div class="panel panel-primary small">
				<div class="panel-heading">
					<i class="fa fa-thumb-tack"></i> <strong>ACTIVIDADES <?php 	if($infoEjecucion){ echo ' - REGISTRO EJECUCIÓN'; } ?></strong>
					<div class="pull-right">
						<div class="btn-group">
							<?php
								if($numeroActividad != 'x'){
							?>
									<a class="btn btn-primary btn-xs" href=" <?php echo base_url('dashboard/actividades/' . $idCuadroBase); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a> 
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
								echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'> <span class='fa fa-eye' aria-hidden='true'></a>";
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
									<tr class="headings">
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

											<form name="form" id="form" role="form" method="post" >
												<input type="hidden" id="idCuadroBase" name="idCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
												<input type="hidden" id="idActividad" name="idActividad" value="<?php echo $lista["id_actividad"]; ?>"/>
												<input type="hidden" id="numeroActividad" name="numeroActividad" value="<?php echo $lista["numero_actividad"]; ?>"/>
												<input type="hidden" id="cumplimiento1" name="cumplimiento1" value="<?php echo $cumplimiento1; ?>"/>
												<input type="hidden" id="cumplimiento2" name="cumplimiento2" value="<?php echo $cumplimiento2; ?>"/>
												<input type="hidden" id="cumplimiento3" name="cumplimiento3" value="<?php echo $cumplimiento3; ?>"/>
												<input type="hidden" id="cumplimiento4" name="cumplimiento4" value="<?php echo $cumplimiento4; ?>"/>
												<input type="hidden" id="avancePOA" name="avancePOA" value="<?php echo $avancePOA; ?>"/>
<?php if($estadoActividad){ ?>
												<p>
													<?php if($cumplimiento1 > 0 && $estadoActividad[0]['estado_trimestre_1'] == 1){ ?>
														<button type="button" id="1" class='btn btn-danger btn-xs' title="Cerrar Trimestre I">
																Cerrar Trimestre I  <i class="fa fa-arrow-right"></i>
														</button>
													<?php } ?>
												</p>

												<p>
													<?php if($cumplimiento2 > 0 && $estadoActividad[0]['estado_trimestre_2'] == 1){ ?>
														<button type="button" id="2" class='btn btn-danger btn-xs' title="Cerrar Trimestre II">
																Cerrar Trimestre II  <i class="fa fa-arrow-right"></i>
														</button>
													<?php } ?>												
												</p>

												<p>
													<?php if($cumplimiento3 > 0 && $estadoActividad[0]['estado_trimestre_3'] == 1){ ?>
														<button type="button" id="3" class='btn btn-danger btn-xs' title="Cerrar Trimestre III">
																Cerrar Trimestre III  <i class="fa fa-arrow-right"></i>
														</button>
													<?php } ?>
												</p>
												<p>
													<?php if($cumplimiento4 > 0 && $estadoActividad[0]['estado_trimestre_4'] == 1){ ?>
														<button type="button" id="4" class='btn btn-danger btn-xs' title="Cerrar Trimestre IV">
																Cerrar Trimestre IV  <i class="fa fa-arrow-right"></i>
														</button>
													<?php } ?>
												</p>
<?php } ?>
											</form>
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
		?>
					<div class="table-responsive">
						<table id="dataTablesWorker" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
							<thead>
								<tr class="headings">
									<th class="column-title" colspan="6">-- EJECUCIÓN ACTIVIDAD --</th>
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
									$variable = 'estado_trimestre_' . $data['numero_trimestre'];
									$deshabilidar = '';
									if($estadoActividad){
										switch ($estadoActividad[0][$variable]) {
											case 0:
												$valor = 'No Iniciada';
												$clase = "text-primary";
												$deshabilidar = '';
												break;
											case 1:
												$valor = 'En Proceso';
												$clase = "text-warning";
												$deshabilidar = '';
												break;
											case 2:
												$valor = 'Cerrada';
												$clase = "text-warning";
												$deshabilidar = 'disabled';
												break;
											case 3:
												$valor = 'Aprobada';
												$clase = "text-success";
												$deshabilidar = 'disabled';
												break;
											case 4:
												$valor = 'Devuelta';
												$clase = "text-danger";
												$deshabilidar = 'disabled';
												break;
										}
									}						
									$idRecord = $data['id_ejecucion_actividad'];
									$numeroActividad = $data['fk_numero_actividad'];
							?>		
									
								<form  name="ejecucion_<?php echo $idRecord ?>" id="ejecucion_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("dashboard/update_ejecucion"); ?>">

									<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>"/>
									<input type="hidden" id="hddnumeroActividad" name="hddnumeroActividad" value="<?php echo $numeroActividad; ?>"/>
									<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
									<input type="hidden" id="hddNumeroTrimestre" name="hddNumeroTrimestre" value="<?php echo $data['numero_trimestre']; ?>"/>
									<input type="hidden" id="hddMes" name="hddMes" value="<?php echo $data['mes']; ?>"/>
									<tr>
										<td ><?php echo $data['mes']; ?></td>
										<td ><?php echo $data['programado']; ?></td>
										<td>
											<input type="number" id="ejecutado" name="ejecutado" class="form-control" placeholder="Ejecutado" value="<?php echo $data['ejecutado']; ?>" step="any" min="0" required <?php echo $deshabilidar; ?> >
										</td>
										<td>
											<textarea id="descripcion" name="descripcion" placeholder="Descripción" class="form-control" rows="2" required <?php echo $deshabilidar; ?>><?php echo $data['descripcion_actividades']; ?></textarea>
											<br>
											<textarea id="evidencia" name="evidencia" placeholder="Evidencia" class="form-control" rows="2" required <?php echo $deshabilidar; ?>><?php echo $data['evidencias']; ?></textarea>
										</td>
										<td class='text-center'>
											<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit_<?php echo $idRecord; ?>" name="btnSubmit_<?php echo $idRecord; ?>" <?php echo $deshabilidar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
										</td>
									</tr>
								</form>
							<?php
								endforeach;
							?>
							</tbody>
						</table>

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