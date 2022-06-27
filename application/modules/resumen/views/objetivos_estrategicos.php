<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> RESUMEN - OBJETIVOS ESTRATÉGICOS
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
					<i class="fa fa-crosshairs"></i> RESUMEN OBJETIVOS ESTRATÉGICOS
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
					if($info){
				?>				

					<table width="100%" class="table table-hover">
						<thead>
							<tr>
								<th width='45%'>Objetivo Estratégico</th>
								<th width='10%' class="text-center">No. Actividades</th>
								<th width='45%' class="text-center">Promedio de Cumplimiento</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
	                            $arrParam = array(
	                                "numeroObjetivoEstrategico" => $lista["numero_objetivo_estrategico"],
	                                "vigencia" => date("Y")
	                            );
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
								echo "<td>" . $lista['numero_objetivo_estrategico'] . ' ' . $lista['objetivo_estrategico'] .  "</td>";
								echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
	                            echo "<td class='text-center'>";
	                            echo "<b>" . $promedioCumplimiento ."%</b>";
	                            echo '<div class="progress progress-striped">
	                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
	                                    </div>';
	                            echo "</td>";
	                            echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
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
<!--FIN Modal  -->

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