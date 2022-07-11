<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> RESUMEN - PLANES INSTITUCIONALES
					</h4>
				</div>
			</div>
		</div>			
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-crosshairs"></i> RESUMEN PLANES INSTITUCIONALES
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
					<table width="100%" class="table table-hover">
						<thead>
							<tr>
								<th width='45%'>Plan Institucional</th>
								<th width='10%' class="text-center">No. Actividades</th>
								<th width='45%' class="text-center">Promedio de Cumplimiento</th>
							</tr>
						</thead>
						<tbody>							
						<?php
	                            $arrParam = array("planArchivos" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Institucional de Archivos de la Entidad</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planAdquisiciones" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Anual de Adquisiciones</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planVacantes" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Anual de Vacantes </td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planRecursos" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan de Previsión de Recursos Humanos</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planTalento" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Estratégico de Talento Humano</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planCapacitacion" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Institucional de Capacitación</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planIncentivos" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan de Incentivos Institucionales</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planTrabajo" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan de Trabajo Anual en Seguridad y Salud en el Trabajo </td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planAnticorrupcion" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan Anticorrupción y de Atención al Ciudadano </td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planTecnologia" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan estratégico de Tecnologías de la Información y las Comunicaciones</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planRiesgos" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan de Tratamiento de Riesgos de Seguridad y Privacidad de la Información </td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";

	                            $arrParam = array("planInformacion" => true);
	                            $nroActividades = $this->general_model->countActividades($arrParam);
								$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
	                            $promedioCumplimiento = 0;
	                            if($nroActividades){
	                                $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                                         
	                            if(!$promedioCumplimiento){
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            }else{
	                                if($promedioCumplimiento > 70){
	                                    $estilos = "progress-bar-success";
	                                }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
	                                    $estilos = "progress-bar-warning";
	                                }else{
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }
								echo "<tr>";
								echo "<td>Plan de Seguridad y Privacidad de la Información </td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";
						?>
						</tbody>
					</table>
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
		"order": [[ 1, "asc" ]],
		"pageLength": 100
	});
});
</script>