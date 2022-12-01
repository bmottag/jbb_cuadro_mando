<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/actividades_enlace.js"); ?>"></script>
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
									<a class="btn btn-primary btn-xs" href=" <?php echo base_url($urlBotonRegresar); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a> 
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
		foreach ($listaActividades as $lista):
            //buscar SUPERVISORES
			$arrParam = array(
				"idDependencia" => $lista['fk_id_dependencia'],
				"idRole" => ID_ROL_SUPERVISOR,
				"filtroState" => TRUE
			);
            $supervisores = $this->general_model->get_user($arrParam);
            //buscar ENLACES
            $arrParam["idRole"] =  ID_ROL_ENLACE;
            $enlaces = $this->general_model->get_user($arrParam);
?>
					<div class="alert alert-info ">
						<div class="row">
							<div class="col-lg-8">
								<h4><b>No. Actividad: <?php echo $lista['numero_actividad']; ?></b></h4>
								<b>Actividad:</b><br> <?php echo $lista['descripcion_actividad']; ?>	
							</div>
							<div class="col-lg-4">
								<div class="pull-right">
									<h4><b><?php echo $lista['dependencia']; ?></b></h4>
									<b>Supervisor:</b><br> 
									<?php
										if($supervisores){
	                                        foreach ($supervisores as $datos):
	                                            echo "<li>" . $datos["first_name"] . " " . $datos["last_name"] . "</li>";
	                                        endforeach;										
										} 
                                    ?>
									<br><b>Enlace:</b><br>
									<?php
										if($enlaces){
	                                        foreach ($enlaces as $datos):
	                                            echo "<li>" . $datos["first_name"] . " " . $datos["last_name"] . "</li>";
	                                        endforeach;
	                                    }
                                    ?>
								</div>
							</div>
						</div>
					</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Meta Plan Operativo Anual</th>
								<th class="text-center">Unidad Medida</th>
								<th>Nombre Indicador</th>
								<th>Tipo Indicador</th>
								<th>Ponderación</th>
								<th>Fechas</th>
								<th>Responsable</th>
								<th class="text-center" style="width: 10%">Enlaces</th>
							</tr>
						</thead>
						<tbody>							
						<?php
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
								if(!$infoEjecucion){
									echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'> <span class='fa fa-eye' aria-hidden='true'></a>";
								}else{
									echo "---";
								}
								echo "</td>";
								echo "</tr>";
								echo "</tbody></table>";

								$arrParam = array("numeroActividad" => $lista["numero_actividad"]);
								$estadoActividad = $this->general_model->get_estados_actividades($arrParam);
								$sumaProgramado = $this->general_model->sumarProgramado($arrParam);

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
								
								$sumaEjecutado['ejecutado'] = 0;
								if ($estadoActividad[0]['estado_trimestre_1'] == 5){
									$sumaEjecutado['ejecutado'] += $sumaEjecutadoTrimestre1['ejecutado'];
								}
								if ($estadoActividad[0]['estado_trimestre_2'] == 5){
									$sumaEjecutado['ejecutado'] += $sumaEjecutadoTrimestre2['ejecutado'];
								}
								if ($estadoActividad[0]['estado_trimestre_3'] == 5){
									$sumaEjecutado['ejecutado'] += $sumaEjecutadoTrimestre3['ejecutado'];
								}
								if ($estadoActividad[0]['estado_trimestre_4'] == 5){
									$sumaEjecutado['ejecutado'] += $sumaEjecutadoTrimestre4['ejecutado'];
								}

								$cumplimiento1 = 0;
								$cumplimiento2 = 0;
								$cumplimiento3 = 0;
								$cumplimiento4 = 0;

								$avancePOA = 0;
								if($sumaProgramado['programado'] > 0){
									$avancePOA = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * $ponderacion,3);
								}
								if ($estadoActividad[0]['estado_trimestre_1'] == 5){
									if($sumaProgramadoTrimestre1['programado'] > 0) {
										$cumplimiento1 = round($sumaEjecutadoTrimestre1['ejecutado'] / $sumaProgramadoTrimestre1['programado'] * 100,3);
									} else {
										if($sumaEjecutadoTrimestre1['ejecutado'] > 0) {
											$cumplimiento1 = 100;
										} else {
											$cumplimiento1 = 0;
										}
									}
								} else {
									$cumplimiento1 = 0;
								}
								if ($estadoActividad[0]['estado_trimestre_2'] == 5){
									if($sumaProgramadoTrimestre2['programado'] > 0) {
										$cumplimiento2 = round($sumaEjecutadoTrimestre2['ejecutado'] / $sumaProgramadoTrimestre2['programado'] * 100,3);
									} else {
										if($sumaEjecutadoTrimestre2['ejecutado'] > 0) {
											$cumplimiento2 = 100;
										} else {
											$cumplimiento2 = 0;
										}
									}
								} else {
									$cumplimiento2 = 0;
								}
								if ($estadoActividad[0]['estado_trimestre_3'] == 5){
									if($sumaProgramadoTrimestre3['programado'] > 0) {
										$cumplimiento3 = round($sumaEjecutadoTrimestre3['ejecutado'] / $sumaProgramadoTrimestre3['programado'] * 100,3);
									} else {
										if($sumaEjecutadoTrimestre3['ejecutado'] > 0) {
											$cumplimiento3 = 100;
										} else {
											$cumplimiento3 = 0;
										}
									}
								} else {
									$cumplimiento3 = 0;
								}
								if ($estadoActividad[0]['estado_trimestre_4'] == 5){
									if($sumaProgramadoTrimestre4['programado'] > 0) {
										$cumplimiento4 = round($sumaEjecutadoTrimestre4['ejecutado'] / $sumaProgramadoTrimestre4['programado'] * 100,3);
									} else {
										if($sumaEjecutadoTrimestre4['ejecutado'] > 0) {
											$cumplimiento4 = 100;
										} else {
											$cumplimiento4 = 0;
										}
									}
								} else {
									$cumplimiento4 = 0;
								}
?>
							<table class='table table-hover'>
								<thead>
									<tr>
										<th>
											Plan Institucional
										</th>
									</tr>
								</thead>
									<tr>
										<td>
											<?php if($lista['plan_archivos'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Institucional de Archivos de la Entidad <br>
											<?php } ?>
											<?php if($lista['plan_adquisiciones'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Anual de Adquisiciones <br>
											<?php } ?>
											<?php if($lista['plan_vacantes'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Anual de Vacantes <br>
											<?php } ?>
											<?php if($lista['plan_recursos'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan de Previsión de Recursos Humanos <br>
											<?php } ?>
											<?php if($lista['plan_talento'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Estratégico de Talento Humano <br>
											<?php } ?>
											<?php if($lista['plan_capacitacion'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Institucional de Capacitación <br>
											<?php } ?>
											<?php if($lista['plan_incentivos'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan de Incentivos Institucionales <br>
											<?php } ?>
											<?php if($lista['plan_trabajo'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan de Trabajo Anual en Seguridad y Salud en el Trabajo <br>
											<?php } ?>
											<?php if($lista['plan_anticorrupcion'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Anticorrupción y de Atención al Ciudadano <br>
											<?php } ?>
											<?php if($lista['plan_tecnologia'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan Estratégico de Tecnologías de la Información y las Comunicaciones <br>
											<?php } ?>
											<?php if($lista['plan_riesgos'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan de Tratamiento de Riesgos de Seguridad y Privacidad de la Información <br>
											<?php } ?>
											<?php if($lista['plan_informacion'] == 1 ){ ?>
											<span class="glyphicon glyphicon-ok"></span> Plan de Seguridad y Privacidad de la Información <br>
											<?php } ?>
										</tr>
									</tr>
							</table>
					<table class='table table-hover'>
						<thead>
							<tr class="text-primary">
								<td>
									<h2>Programado Año: <?php echo number_format($sumaProgramado['programado'],3); ?></h2>
									<small>(Suma Programado)</small>
								</td>
								<td class="text-right">
									<h2>Avance POA: <?php echo $avancePOA . '%'; ?></h2>
									<small>(Suma Ejecutado /Suma Programado * Ponderación)</small>
								</td>
							</tr>
						</thead>
					</table>

					<form name="form" id="form" role="form" method="post" >
						<input type="hidden" id="idCuadroBase" name="idCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
						<input type="hidden" id="idActividad" name="idActividad" value="<?php echo $lista["id_actividad"]; ?>"/>
						<input type="hidden" id="numeroActividad" name="numeroActividad" value="<?php echo $lista["numero_actividad"]; ?>"/>
						<input type="hidden" id="cumplimiento1" name="cumplimiento1" value="<?php echo $cumplimiento1; ?>"/>
						<input type="hidden" id="cumplimiento2" name="cumplimiento2" value="<?php echo $cumplimiento2; ?>"/>
						<input type="hidden" id="cumplimiento3" name="cumplimiento3" value="<?php echo $cumplimiento3; ?>"/>
						<input type="hidden" id="cumplimiento4" name="cumplimiento4" value="<?php echo $cumplimiento4; ?>"/>
						<input type="hidden" id="avancePOA" name="avancePOA" value="<?php echo $avancePOA; ?>"/>

						<table class='table table-hover info'>
							<thead>

								<!-- INFO TRIMESTRE I -->

								<tr class="headings default">
									<th class="column-title">
										<p>Programado Trimestre I: <?php echo number_format($sumaProgramadoTrimestre1['programado'],3); ?></p>
									</th>
									<th class="column-title">
										<p>Cumplimiento Trimestre I: <?php echo $cumplimiento1 . '%'; ?></p>
									</th>
									<th class="column-title small text-center <?php if($estadoActividad){ echo $estadoActividad[0]['primer_clase']; } ?>">
										<?php if($estadoActividad){ ?>
										<p class="<?php echo 'text-' . $estadoActividad[0]['primer_clase']; ?>"><b><?php echo $estadoActividad[0]['primer_estado']; ?></b></p>
										<?php } ?>
									</th>
									<th class="column-title text-center">
<?php if($estadoActividad){ ?>
										<!-- si esta CERRADO o APROBADO POR EL SUPERVISOR o APROBADO POR PLANEACION o con OBSERVACION DE CONTROL INTERNO, no muestre el boton -->
										<?php if($estadoActividad[0]['estado_trimestre_1'] != 2 && $estadoActividad[0]['estado_trimestre_1'] != 3 && $estadoActividad[0]['estado_trimestre_1'] != 5 && $estadoActividad[0]['estado_trimestre_1'] != 7 && $estadoActividad[0]['estado_trimestre_1'] != 8){ ?>
											<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/1') ?>' title="Registrar Ejecución I">
													Registrar Ejecución I <span class="fa fa-edit" aria-hidden="true"> </span>
											</a></p>
										<?php } ?>

										<?php if($estadoActividad[0]['estado_trimestre_1'] == 1){ ?>
											<p>
												<button type="button" id="1" class='btn btn-danger btn-xs' title="Cerrar Trimestre I">
														Cerrar Trimestre I  <i class="fa fa-arrow-right"></i>
												</button>
											</p>
										<?php } ?>
<?php } ?>										
									</th>
<!--
									<th class="column-title text-right">
										<p>Avance POA I: <?php echo $avancePOA1; ?></p>
									</th>
-->
								</tr>

								<!-- INFO TRIMESTRE II -->

								<tr class="headings">
									<th class="column-title">
										<p>Programado Trimestre II: <?php echo number_format($sumaProgramadoTrimestre2['programado'],3); ?></p>
									</th>
									<th class="column-title">
										<p>Cumplimiento Trimestre II: <?php echo $cumplimiento2 . '%'; ?></p>
									</th>
									<th class="column-title small text-center <?php if($estadoActividad){ echo $estadoActividad[0]['segundo_clase']; } ?>">
										<?php if($estadoActividad){ ?>
										<p class="<?php echo 'text-' . $estadoActividad[0]['segundo_clase']; ?>"><b><?php echo $estadoActividad[0]['segundo_estado']; ?></b></p>
										<?php } ?>
									</th>
									<th class="column-title text-center">
<?php if($estadoActividad){ ?>
										<!-- si esta CERRADO o APROBALDO POR EL SUPERVISOR o APROBADO POR PLANEACION, no muestre el boton -->
										<?php if($estadoActividad[0]['estado_trimestre_2'] != 2 && $estadoActividad[0]['estado_trimestre_2'] != 3 && $estadoActividad[0]['estado_trimestre_2'] != 5 && $estadoActividad[0]['estado_trimestre_2'] != 8 && $estadoActividad[0]['estado_trimestre_2'] != 8){ ?>
											<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/2') ?>' title="Registrar Ejecución II">
													Registrar Ejecución II <span class="fa fa-edit" aria-hidden="true"> </span>
											</a></p>
										<?php } ?>

										<?php if($estadoActividad[0]['estado_trimestre_2'] == 1 || $estadoActividad[0]['estado_trimestre_2'] == 4){ ?>
											<p>
												<button type="button" id="2" class='btn btn-danger btn-xs' title="Cerrar Trimestre II">
														Cerrar Trimestre II  <i class="fa fa-arrow-right"></i>
												</button>
											</p>
										<?php } ?>
<?php } ?>										
									</th>
<!--
									<th class="column-title text-right">
										<p>Avance POA II: <?php echo $avancePOA2; ?></p>
									</th>
-->
								</tr>

								<!-- INFO TRIMESTRE III -->

								<tr class="headings">
									<th class="column-title">
										<p>Programado Trimestre III: <?php echo number_format($sumaProgramadoTrimestre3['programado'],3); ?></p>
									</th>
									<th class="column-title">
										<p>Cumplimiento Trimestre III: <?php echo $cumplimiento3 . '%'; ?></p>
									</th>
									<th class="column-title small text-center <?php if($estadoActividad){ echo $estadoActividad[0]['tercer_clase']; } ?>">
										<?php if($estadoActividad){ ?>
										<p class="<?php echo 'text-' . $estadoActividad[0]['tercer_clase']; ?>"><b><?php echo $estadoActividad[0]['tercer_estado']; ?></b></p>
										<?php } ?>
									</th>
									<th class="column-title text-center">
<?php if($estadoActividad){ ?>
										<!-- si esta CERRADO o APROBALDO POR EL SUPERVISOR o APROBADO POR PLANEACION, no muestre el boton -->
										<?php if($estadoActividad[0]['estado_trimestre_3'] != 2 && $estadoActividad[0]['estado_trimestre_3'] != 3 && $estadoActividad[0]['estado_trimestre_3'] != 5 && $estadoActividad[0]['estado_trimestre_3'] != 8 && $estadoActividad[0]['estado_trimestre_3'] != 8){ ?>
											<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/3') ?>' title="Registrar Ejecución III">
													Registrar Ejecución III <span class="fa fa-edit" aria-hidden="true"> </span>
											</a></p>
										<?php } ?>

										<?php if($estadoActividad[0]['estado_trimestre_3'] == 1 || $estadoActividad[0]['estado_trimestre_3'] == 4){ ?>
											<p>
												<button type="button" id="3" class='btn btn-danger btn-xs' title="Cerrar Trimestre III">
														Cerrar Trimestre III  <i class="fa fa-arrow-right"></i>
												</button>
											</p>
										<?php } ?>
<?php } ?>											
									</th>
<!--
									<th class="column-title text-right">
										<p>Avance POA III: <?php echo $avancePOA3; ?></p>
									</th>
-->
								</tr>

								<!-- INFO TRIMESTRE IV -->

								<tr class="headings">
									<th class="column-title">
										<p>Programado Trimestre IV: <?php echo number_format($sumaProgramadoTrimestre4['programado'],3); ?></p>
									</th>
									<th class="column-title">
										<p>Cumplimiento Trimestre IV: <?php echo $cumplimiento4 . '%'; ?></p>
									</th>
									<th class="column-title small text-center <?php if($estadoActividad){ echo $estadoActividad[0]['cuarta_clase']; } ?>">
										<?php if($estadoActividad){ ?>
										<p class="<?php echo 'text-' . $estadoActividad[0]['cuarta_clase']; ?>"><b><?php echo $estadoActividad[0]['cuarta_estado']; ?></b></p>
										<?php } ?>
									</th>
									<th class="column-title text-center">
<?php if($estadoActividad){ ?>
										<!-- si esta CERRADO o APROBALDO POR EL SUPERVISOR o APROBADO POR PLANEACION, no muestre el boton -->
										<?php if($estadoActividad[0]['estado_trimestre_4'] != 2 && $estadoActividad[0]['estado_trimestre_4'] != 3 && $estadoActividad[0]['estado_trimestre_4'] != 5 && $estadoActividad[0]['estado_trimestre_4'] != 8 && $estadoActividad[0]['estado_trimestre_4'] != 8){ ?>
											<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/4') ?>' title="Registrar Ejecución IV">
													Registrar Ejecución IV <span class="fa fa-edit" aria-hidden="true"> </span>
											</a></p>
										<?php } ?>

										<?php if($estadoActividad[0]['estado_trimestre_4'] == 1 || $estadoActividad[0]['estado_trimestre_4'] == 4){ ?>
											<p>
												<button type="button" id="4" class='btn btn-danger btn-xs' title="Cerrar Trimestre IV">
														Cerrar Trimestre IV  <i class="fa fa-arrow-right"></i>
												</button>
											</p>
										<?php } ?>
<?php } ?>										
									</th>
<!--
									<th class="column-title text-right">
										<p>Avance POA IV: <?php echo $avancePOA4; ?></p>
									</th>
-->
								</tr>
									<tr class="headings default">
										<td width="28%"><small>Suma Programado Trimestre</small></td>
										<td width="29%"><small>Suma Ejecutado Trimestre /Suma Programado Trimestre * 100</small></td>
										<td width="20%"></td>
										<td width="23%"></td>
<!--
										<td width="20%" class="text-right"><small>Suma Ejecutado Trimestre /Suma Programado Trimestre * Ponderación</small></td>
-->
									</tr>
							</thead>				
						</table>
					</form>
						<?php
							endforeach;
						?>
				<?php } ?>

<!-- INICIO HISTORICO -->
		<?php
			if($infoEjecucion){
		?>
					<div class="table-responsive">
						<form  name="ejecucion" id="ejecucion" method="post" action="<?php echo base_url("dashboard/update_ejecucion"); ?>">

							<input type="hidden" id="hddNumeroActividad" name="hddNumeroActividad" value="<?php echo $numeroActividad; ?>"/>
							<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>	
							<h2 class="text-primary">-- Ejecución Actividad --</h2>

							<table id="dataTablesWorker" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
								<thead>
							<?php
								$deshabilidar = '';
								if($numeroTrimestre){
									$variable = 'estado_trimestre_' . $numeroTrimestre;
									
									if($estadoActividad){
										$estado = $estadoActividad[0][$variable];
										//si esta cerrado o aprobado por el supervisor o aprobado por planeacion, debe bloquear la edicion
										if($estado == 2 || $estado == 3 || $estado == 5 ){
											$deshabilidar = 'disabled';
										}
									}
							?>
									<tr class="text-primary">
										<th colspan="4">
											<h4>Registrar la informacion para el <b>TRIMESTRE <?php echo $numeroTrimestre; ?></b>.
											<?php
												$fechaActual = date("Y-m-d");

												$fecha1= new DateTime($fechaActual);
												$fecha2= new DateTime($infoFechaLimite[0]["fecha"]);
												$diff = $fecha1->diff($fecha2);

												if($infoFechaLimite[0]["fecha"] >= $fechaActual){
													echo "Fecha limite de registro de información: <b>" . $infoFechaLimite[0]["fecha"] . "</b>.";
													echo "<br>" . $diff->days . " días para la entrega.";
												}else{
													echo "No es posible registrar la ejecución de la actividad, la fecha limite era <b>" . $infoFechaLimite[0]["fecha"] . "</b>.";
												}
											?>
											</h4>
										</th>
									</tr>

							<?php
								$fechaActual = date("Y-m-d");
								if($infoFechaLimite[0]["fecha"] >= $fechaActual){
							?>
									<tr class="info">
										<input type="hidden" id="hddNumeroTrimestre" name="hddNumeroTrimestre" value="<?php echo $numeroTrimestre; ?>"/>	
										<th class="column-title text-right" colspan="4">
											<textarea id="observacion" name="observacion" class="form-control" rows="2" placeholder="Observación" required ></textarea><br>
											<button type="submit" class="btn btn-primary" id="btnSubmit2" name="btnSubmit2" <?php echo $deshabilidar; ?> >
												Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
											</button>
										</th>
									</tr>
							<?php
								}
							?>
							<?php
								}else{
									$deshabilidar = 'disabled';
								}
							?>	
									<tr class="headings">
										<th class="column-title" style="width: 10%">Mes</th>
										<th class="column-title" style="width: 10%">Programado (<?php echo $unidadMedida; ?>)</th>
										<th class="column-title" style="width: 15%">Ejecutado (<?php echo $unidadMedida; ?>)</th>
										<th class="column-title" style="width: 65%">Descripción / Evidencias</th>
									</tr>
								</thead>

								<tbody>
								<?php
									foreach ($infoEjecucion as $data):
										$variable = 'estado_trimestre_' . $data['numero_trimestre'];
										
										if($estadoActividad){
											$estado = $estadoActividad[0][$variable];
											//si esta cerrado o aprobado por el supervisor o aprobado por planeacion, debe bloquear la edicion
											if($estado == 2 || $estado == 3 || $estado == 5 ){
												$deshabilidar = 'disabled';
											}
										}						
										$idRecord = $data['id_ejecucion_actividad'];
										$numeroActividad = $data['fk_numero_actividad'];
								?>		
										<input type="hidden" name="form[id][]" value="<?php echo $idRecord; ?>"/>
										<tr>
											<td ><?php echo $data['mes']; ?></td>
											<td ><?php echo $data['programado']; ?></td>
											<?php 
												if($deshabilidar){
											?>
												<td><?php echo $data['ejecutado']; ?></td>
												<td>
													<small>
													<?php 
														if($data['descripcion_actividades']!=""){
															echo "<b>Descripción: </b><br>". $data['descripcion_actividades'] . "<br>";
														}
														if($data['evidencias']!=""){
															echo "<b>Evidencias: </b><br>". $data['evidencias'] . "<br>";
														}
													?>
													</small>
												</td>
											<?php }else{ ?>
												<td>
													<input type="number" step="any" min="0" max="500000" name="form[ejecutado][]" class="form-control" placeholder="Ejecutado" value="<?php echo $data['ejecutado']; ?>"  <?php echo $deshabilidar; ?> >
												</td>
												<td>
													<textarea name="form[descripcion][]" placeholder="Descripción" class="form-control" rows="3" <?php echo $deshabilidar; ?>><?php echo $data['descripcion_actividades']; ?></textarea>
													<br>
													<textarea name="form[evidencia][]" placeholder="Evidencia" class="form-control" rows="3" <?php echo $deshabilidar; ?>><?php echo $data['evidencias']; ?></textarea>
												</td>
											<?php } ?>
										</tr>

								<?php
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