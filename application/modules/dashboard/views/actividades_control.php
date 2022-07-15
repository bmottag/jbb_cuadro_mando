<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/form_observacion_actividad.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<!-- Start of menu -->
		<?php
			$userRol = $this->session->userdata("role");
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
								if($numeroActividad != 'x' ){
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
								<th class="text-center">Enlaces</th>
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
        
							if($numeroActividad == 'x') {
								echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "' title='Ver Detalle Actividad'> <span class='fa fa-eye' aria-hidden='true'></a>";
							}
								echo "</td>";
								echo "</tr>";
								echo "</tbody></table>";

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

								if(strtolower(trim($unidadMedida)) == "porcentaje"){
									$valorProgramadoTotal = $sumaProgramado['programado']/100;
									$valorProgramadoTrimestre1 = $sumaProgramadoTrimestre1['programado']/100;
									$valorProgramadoTrimestre2 = $sumaProgramadoTrimestre2['programado']/100;
									$valorProgramadoTrimestre3 = $sumaProgramadoTrimestre3['programado']/100;
									$valorProgramadoTrimestre4 = $sumaProgramadoTrimestre4['programado']/100;
								}else{
									$valorProgramadoTotal = $sumaProgramado['programado'];
									$valorProgramadoTrimestre1 = $sumaProgramadoTrimestre1['programado'];
									$valorProgramadoTrimestre2 = $sumaProgramadoTrimestre2['programado'];
									$valorProgramadoTrimestre3 = $sumaProgramadoTrimestre3['programado'];
									$valorProgramadoTrimestre4 = $sumaProgramadoTrimestre4['programado'];
								}

								$cumplimiento1 = 0;
								$cumplimiento2 = 0;
								$cumplimiento3 = 0;
								$cumplimiento4 = 0;
								//$avancePOA1 = 0;
								//$avancePOA2 = 0;
								//$avancePOA3 = 0;
								//$avancePOA4 = 0;
								$avancePOA = 0;
								if($sumaProgramado['programado'] > 0){
									$avancePOA = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * $ponderacion,2);
								}
								if($sumaProgramadoTrimestre1['programado'] > 0){
									$cumplimiento1 = round($sumaEjecutadoTrimestre1['ejecutado'] / $sumaProgramadoTrimestre1['programado'] * 100, 2);
									//$avancePOA1 = round($sumaEjecutadoTrimestre1['ejecutado'] / $sumaProgramadoTrimestre1['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre2['programado'] > 0){
									$cumplimiento2 = round($sumaEjecutadoTrimestre2['ejecutado'] / $sumaProgramadoTrimestre2['programado'] * 100,2);
									//$avancePOA2 = round($sumaEjecutadoTrimestre2['ejecutado'] / $sumaProgramadoTrimestre2['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre3['programado'] > 0){
									$cumplimiento3 = round($sumaEjecutadoTrimestre3['ejecutado'] / $sumaProgramadoTrimestre3['programado'] * 100,2);
									//$avancePOA3 = round($sumaEjecutadoTrimestre3['ejecutado'] / $sumaProgramadoTrimestre3['programado'] * $ponderacion, 2) . '%';
								}
								if($sumaProgramadoTrimestre4['programado'] > 0){
									$cumplimiento4 = round($sumaEjecutadoTrimestre4['ejecutado'] / $sumaProgramadoTrimestre4['programado'] * 100,2);
									//$avancePOA4 = round($sumaEjecutadoTrimestre4['ejecutado'] / $sumaProgramadoTrimestre4['programado'] * $ponderacion, 2) . '%';
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
											<h2>Programado Año: <?php echo number_format($valorProgramadoTotal,2); ?></h2>
											<small>(Suma Programado)</small>
										</td>
										<td class="text-right">
											<h2>Avance POA: <?php echo $avancePOA . '%'; ?></h2>
											<small>(Suma Ejecutado /Suma Programado * Ponderación)</small>
										</td>
									</tr>
								</thead>
							</table>						
							<table class='table table-hover info'>
								<thead>
									<tr class="headings default">
										<th class="column-title">
											<p>Programado Trimestre I: <?php echo number_format($valorProgramadoTrimestre1,2); ?></p>
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
											<?php if($estadoActividad && $estadoActividad[0]['estado_trimestre_1'] == 5){ ?>
												<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/1') ?>' title="Revisar Trim. I">
														Revisar Trim. I <span class="fa fa-tag" aria-hidden="true"> </span>
												</a></p>	
											<?php } ?>										
										</th>
									</tr>
									<tr class="headings">
										<th class="column-title">
											<p>Programado Trimestre II: <?php echo number_format($valorProgramadoTrimestre2,2); ?></p>
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
											<?php if($estadoActividad && $estadoActividad[0]['estado_trimestre_2'] == 5){ ?>
												<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/2') ?>' title="Revisar Trim. II">
														Revisar Trim. II <span class="fa fa-tag" aria-hidden="true"> </span>
												</a></p>
											<?php } ?>										
										</th>	
									</tr>
									<tr class="headings">
										<th class="column-title">
											<p>Programado Trimestre III: <?php echo number_format($valorProgramadoTrimestre3,2); ?></p>
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
											<?php if($estadoActividad && $estadoActividad[0]['estado_trimestre_3'] == 5){ ?>
												<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/3') ?>' title="Revisar Trim. III">
														Revisar Trim. III <span class="fa fa-tag" aria-hidden="true"> </span>
												</a></p>
											<?php } ?>										
										</th>
									</tr>
									<tr class="headings">
										<th class="column-title">
											<p>Programado Trimestre IV: <?php echo number_format($valorProgramadoTrimestre4,2); ?></p>
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
											<?php if($estadoActividad && $estadoActividad[0]['estado_trimestre_1'] == 4){ ?>
												<p><a class='btn btn-primary btn-xs' href='<?php echo base_url('dashboard/actividades/' . $idCuadroBase . '/' . $lista['id_actividad'] . '/4') ?>' title="Revisar Trim. IV">
														Revisar Trim. IV <span class="fa fa-tag" aria-hidden="true"> </span>
												</a></p>
											<?php } ?>											
										</th>
									</tr>
									<tr class="headings default">
										<td width="28%"><small>Suma Programado Trimestre</small></td>
										<td width="29%"><small>Suma Ejecutado Trimestre /Suma Programado Trimestre * 100</small></td>
										<td width="20%"></td>
										<td width="23%"></td>
									</tr>
								</thead>
				
					</table>
						<?php
							endforeach;
						?>
				<?php } ?>

<!-- INICIO HISTORICO -->
		<?php
			if($infoEjecucion){
				if($numeroTrimestre && $listaHistorial && $listaHistorial[0]['fk_id_estado'] == 5){
		?>
<!--INICIO ADDITIONAL INFORMATION -->
	<div class="row">
		<div class="col-lg-6">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					ADICIONAR OBERVACIÓN AL TRIMESTRE <?php echo $numeroTrimestre; ?>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">	
						<form name="formEstado" id="formEstado" class="form-horizontal" method="post">
							<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
							<input type="hidden" id="hddNumeroActividad" name="hddNumeroActividad" value="<?php echo $lista['numero_actividad']; ?>"/>
							<input type="hidden" id="hddNumeroTrimestre" name="hddNumeroTrimestre" value="<?php echo $numeroTrimestre; ?>"/>
							
							<div class="form-group">
								<label class="col-sm-4 control-label" for="information">Observación:</label>
								<div class="col-sm-8">
								<textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" ></textarea>
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
	</div>
<!--FIN ADDITIONAL INFORMATION -->
		<?php
				}
		?>
					<div class="table-responsive">
						<table class='table table-hover'>
							<thead>
								<tr class="text-primary">
									<th colspan="4">
										<h2>-- Ejecución Actividad --</h2>
										<?php
											$deshabilidarCampos = "disabled";
											if($numeroTrimestre){
												echo '<p class="text-primary">Trimestre ' . $numeroTrimestre. '</p>'; 
											}
										?>
									</th>
								</tr>
								
								<tr class="headings">
									<th class="column-title" style="width: 7%">Mes</th>
									<th class="column-title" style="width: 10%">Programado (<?php echo $unidadMedida; ?>)</th>
									<th class="column-title" style="width: 13%">Ejecutado (<?php echo $unidadMedida; ?>)</th>
									<th class="column-title" style="width: 70%">Descripción / Evidencias</th>
								</tr>
							</thead>

							<tbody>
							<?php
								foreach ($infoEjecucion as $data):						
							?>		
									<tr>
										<td><?php echo $data['mes']; ?></td>
										<td><?php echo $data['programado']; ?></td>
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
									</tr>
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
		
<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog modal-lg" role="document">
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