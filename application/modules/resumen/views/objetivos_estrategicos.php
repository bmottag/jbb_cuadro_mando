<script>
$(function(){
    $(".btn-primary").click(function () {
        var oID = $(this).attr("id");
        $.ajax ({
            type: 'POST',
            url: base_url + 'resumen/cargarModalEvaluacionObjetivosEstrategicos',
            data: {'numeroObjetivoEstrategico': oID},
            cache: false,
            success: function (data) {
                $('#tablaDatos').html(data);
            }
        });
    });

    $(".btn-default").click(function () {   
        var oID = $(this).attr("id");
        $.ajax ({
            type: 'POST',
            url: base_url + 'resumen/cargarModalHistorialComentarios',
            data: {'numeroObjetivoEstrategico': oID},
            cache: false,
            success: function (data) {
                $('#tablaDatosComentarios').html(data);
            }
        });
    });
});
</script>

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
								<th width='7%'>No.</th>
								<th width='40%'>Objetivo Estratégico</th>
								<th width='10%' class="text-center">No. Actividades</th>
								<th width='43%' class="text-center">Promedio de Cumplimiento</th>
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
								$calificacion = $this->general_model->get_evaluacion_calificacion($arrParam);
								$calificacion_0 = isset($calificacion[0]['calificacion']);
								$calificacion_1 = isset($calificacion[1]['calificacion']);
	                            $promedioCumplimiento = 0;
	                            if ($nroActividades) {
	                            	$promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }
	                            
	                            if ($calificacion_0){
	                            	if ($promedioCumplimiento > $calificacion[0]['calificacion']) {
	                            		$promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
		                            } else {
		                            	if ($calificacion[0]['estado'] == 2) {
				                            $promedioCumplimiento = $calificacion[0]['calificacion'];
		                            	}
		                            	if ($calificacion[0]['estado'] == 1 || $calificacion[0]['estado'] == 3) {
		                            		if ($calificacion_1) {
		                            			if ($calificacion[1]['estado'] == 2) {
		                            				$promedioCumplimiento = $calificacion[1]['calificacion'];
		                            			}
		                            		} else {
					                            $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
		                            		}
		                            	}
		                            	if ($calificacion[0]['estado'] == 4) {
				                            $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
		                            	}
		                            }
	                            } else {
	                            	$promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
	                            }

	                            if (!$promedioCumplimiento) {
	                                $promedioCumplimiento = 0;
	                                $estilos = "bg-warning";
	                            } else {
	                                if ($promedioCumplimiento > 70) {
	                                    $estilos = "progress-bar-success";
	                                } elseif ($promedioCumplimiento > 40 && $promedioCumplimiento <= 70) {
	                                    $estilos = "progress-bar-warning";
	                                } else {
	                                    $estilos = "progress-bar-danger";
	                                }
	                            }

	                            // deshabilita la calificacion a los supervisores que no tienen asignado el objetivo estrategico 
	                            $habilitar = '';
	                            $warning = '';
								$userRol = $this->session->userdata("role");
								if ($userRol == ID_ROL_SUPERVISOR) {
									$habilitar = ' disabled';
									$idUser = $this->session->userdata("id");
									$arrParam = array(
		                                "numeroObjetivoEstrategico" => $lista["numero_objetivo_estrategico"],
		                                "vigencia" => date("Y")
		                            );
									$supervisores = $this->general_model->get_objetivos_estrategicos_supervisores($arrParam);
									$comentario = $this->general_model->get_comentario_supervisor($arrParam);
									for ($i=0; $i<count($supervisores); $i++) {
										if ($idUser == $supervisores[$i]['id_user']) {
											$habilitar = '';
										}
									}
									if ($calificacion_0 == 1 && $comentario[0]['comentario_supervisor'] == NULL) {
										$warning = '<span class="fa fa-exclamation-triangle fa-lg" style="color: orange"; aria-hidden="true"></span>';
									}
								}

								echo "<tr>";
								echo "<td>";
								?>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEvaluacion" id="<?php echo $lista['numero_objetivo_estrategico']; ?>" <?php echo $habilitar; ?>>
                                    <?php echo $lista['numero_objetivo_estrategico'] ?>&nbsp;
                                    <span class="fa fa-pencil" aria-hidden="true"></span>
                                </button>
                                <?php
                                echo $warning;
                                if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR) {
                                ?>
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalComentarios" id="<?php echo $lista['numero_objetivo_estrategico']; ?>" title="Historial Comentarios">
									<i class="fa fa-comments fa-fw"></i>
								</button>
								<?php
								}
                                echo "</td>";
								echo "<td>" . $lista['objetivo_estrategico'] .  "</td>";
								echo "<td class='text-center'>" . $nroActividades . "</td>";
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
<div class="modal fade text-center" id="modalEvaluacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="tablaDatos">
		</div>
	</div>
</div>
<!--FIN Modal  -->

<!--INICIO Modal -->
<div class="modal fade text-center" id="modalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="tablaDatosComentarios">
        </div>
    </div>
</div>
<!--FIN Modal -->

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