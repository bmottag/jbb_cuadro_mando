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
					<i class="fa fa-thumb-tack"></i> <strong>ACTIVIDADES</strong>
					<div class="pull-right">
						<div class="btn-group">
							<?php
								if($idActividad != 'x'){
							?>
									<a class="btn btn-primary btn-xs" href=" <?php echo base_url('dashboard/actividades/' . $idCuadroBase); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar</a> 
							<?php
								}else{
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
								<th>Fecha Inicial</th>
								<th>Fecha Final</th>
								<th class="text-center">Editar</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($listaActividades as $lista):
								switch ($lista['unidad_medida']) {
									case 1:
										$valor = 'Número';
										$clase = "text-success";
										break;
									case 2:
										$valor = 'Porcentaje';
										$clase = "text-danger";
										break;
								}

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
								echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
								echo "</td>";
								echo "<td>" . $lista['nombre_indicador'] . "</td>";
								echo "<td class='text-center'>";
								echo '<p class="' . $clase2 . '"><strong>' . $valor2 . '</strong></p>';
								echo "</td>";
								echo "<td class='text-right'>" . $lista['ponderacion'] . "%</td>";
								echo "<td class='text-center'>" . $lista['mes_inicial'] . "</td>";
								echo "<td class='text-center'>" . $lista['mes_final'] . "</td>";
								echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_actividad']; ?>" >
										<span class="fa fa-pencil" aria-hidden="true">
									</button>
						<?php
							if($idActividad != 'x') {
						?>
									<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEjecucion" id="<?php echo $lista['id_actividad']; ?>">
											<i class="fa fa-signal"></i>
									</button>
						<?php
							}
								echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["id_actividad"]) . "'> <span class='fa fa-eye' aria-hidden='true'></a>";
								echo "</td>";
								echo "</tr>";
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
									<th class="column-title">
										<?php
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
										Programado Año: <?php echo $sumaProgramado['programado']; ?>
										<br>Acance POA: <?php echo $avancePOA . '%'; ?>
									</th>
									<th class="column-title" colspan="2">
										Programado Trimestre I: <?php echo $sumaProgramadoTrimestre1['programado']; ?>
										<br>Programado Trimestre II: <?php echo $sumaProgramadoTrimestre2['programado']; ?>
										<br>Programado Trimestre III: <?php echo $sumaProgramadoTrimestre3['programado']; ?>
										<br>Programado Trimestre IV: <?php echo $sumaProgramadoTrimestre4['programado']; ?>
									</th>
									<th class="column-title">
										Cumplimiento Trimestre I: <?php echo $cumplimiento1 . '%'; ?>
										<?php if($cumplimiento1 > 0){ ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('dashboard/update_trimestre/' . $idCuadroBase . '/' . $idActividad . '/' . $cumplimiento1 . '/' . $avancePOA . '/1') ?>' id="btn-delete" title="Cerrar">
												Cerrar <span class="fa fa-times" aria-hidden="true"> </span>
										</a>
										<?php } ?>
										<br>Cumplimiento Trimestre II: <?php echo $cumplimiento2 . '%'; ?>
										<?php if($cumplimiento2 > 0){ ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('dashboard/update_trimestre/' . $idCuadroBase . '/' . $idActividad . '/' . $cumplimiento2 . '/' . $avancePOA . '/2') ?>' id="btn-delete" title="Cerrar">
												Cerrar <span class="fa fa-times" aria-hidden="true"> </span>
										</a>
										<?php } ?>
										<br>Cumplimiento Trimestre III: <?php echo $cumplimiento3 . '%'; ?>
										<?php if($cumplimiento3 > 0){ ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('dashboard/update_trimestre/' . $idCuadroBase . '/' . $idActividad . '/' . $cumplimiento3 . '/' . $avancePOA . '/3') ?>' id="btn-delete" title="Cerrar">
												Cerrar <span class="fa fa-times" aria-hidden="true"> </span>
										</a>
										<?php } ?>
										<br>Cumplimiento Trimestre IV: <?php echo $cumplimiento4 . '%'; ?>
										<?php if($cumplimiento4 > 0){ ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('dashboard/update_trimestre/' . $idCuadroBase . '/' . $idActividad . '/' . $cumplimiento4 . '/' . $avancePOA . '/4') ?>' id="btn-delete" title="Cerrar">
												Cerrar <span class="fa fa-times" aria-hidden="true"> </span>
										</a>
										<?php } ?>
									</th>
									<th class="column-title" colspan="2">
										Avance POA I: <?php echo $avancePOA1; ?>
										<br>Avance POA II: <?php echo $avancePOA2; ?>
										<br>Avance POA III: <?php echo $avancePOA3; ?>
										<br>Avance POA IV: <?php echo $avancePOA4; ?>
									</th>
								</tr>
							</thead>
						</table>

						<table id="dataTablesWorker" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
							<thead>
								<tr class="headings">
									<th class="column-title" colspan="5">-- EJECUCIÓN ACTIVIDAD --</th>
								</tr>
								
								<tr class="headings">
									<th class="column-title" style="width: 10%">Mes</th>
									<th class="column-title" style="width: 10%">Programado</th>
									<th class="column-title" style="width: 10%">Ejecutado</th>
									<th class="column-title" style="width: 60%">Descripción</th>
									<th class="column-title text-center" style="width: 10%">Links</th>
								</tr>
							</thead>

							<tbody>
										
							<?php
								foreach ($infoEjecucion as $data):
									$deshabilidar = '';
									$variable = 'estado_trimestre_' . $data['numero_trimestre'];
									if($estadoActividad && $estadoActividad[0][$variable] == 1){
										$deshabilidar = 'disabled';
									}

									echo "<tr>";
									echo "<td >$data[mes]</td>";
									echo "<td >$data[programado]</td>";
									
									$idRecord = $data['id_ejecucion_actividad'];
									$idActividad = $data['fk_id_actividad'];
							?>		
									
						<form  name="ejecucion_<?php echo $idRecord ?>" id="ejecucion_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("dashboard/update_ejecucion"); ?>">

							<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>"/>
							<input type="hidden" id="hddIdActividad" name="hddIdActividad" value="<?php echo $idActividad; ?>"/>
							<input type="hidden" id="hddIdCuadroBase" name="hddIdCuadroBase" value="<?php echo $idCuadroBase; ?>"/>
							<td>
								<input type="text" id="ejecutado" name="ejecutado" class="form-control" placeholder="Ejecutado" value="<?php echo $data['ejecutado']; ?>" required <?php echo $deshabilidar; ?> >
							</td>
							<td>
								<textarea id="descripcion" name="descripcion" placeholder="Descripción" class="form-control" rows="2" required <?php echo $deshabilidar; ?>><?php echo $data['descripcion_actividades']; ?></textarea>
							</td>
								
							<td class='text-center'>
								<input type="submit" id="btnSubmit2" name="btnSubmit2" value="Guardar" class="btn btn-primary btn-xs" <?php echo $deshabilidar; ?> />
							</form>
								<br><br>
								<a class='btn btn-danger btn-xs' href='<?php echo base_url('dashboard/deleteEjecucion/' . $idCuadroBase . '/' . $idActividad . '/' . $idRecord) ?>' id="btn-delete" title="Delete" <?php echo $deshabilidar; ?> >
										<span class="fa fa-trash-o" aria-hidden="true"> </span>
								</a>
							</td>
							<?php
									echo "</tr>";
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
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
		
				
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