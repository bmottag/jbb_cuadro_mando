<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/metas_proyectos.js"); ?>"></script>
<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'settings/cargarModalMetasProyectos',
                data: {'idMetaProyecto': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
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
					<i class="fa fa-gear fa-fw"></i> CONFIGURACIÓN - METAS PROYECTOS INVERSIÓN
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-thumb-tack"></i> LISTA METAS PROYECTOS INVERSIÓN
					<div class="pull-right">
						<div class="btn-group">																				
							<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="x">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Meta Proyectos Inversión
							</button>
						</div>
					</div>
				</div>
				<div class="panel-body small">

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
				<ul class="nav nav-tabs">
					<li <?php if($vigencia == 2020){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/metas_proyectos/2020"); ?>"><b>2020</b></a>
					</li>
					<li <?php if($vigencia == 2021){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/metas_proyectos/2021"); ?>"><b>2021</b></a>
					</li>
					<li <?php if($vigencia == 2022){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/metas_proyectos/2022"); ?>"><b>2022</b></a>
					</li>
					<li <?php if($vigencia == 2023){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/metas_proyectos/2023"); ?>"><b>2023</b></a>
					</li>
					<li <?php if($vigencia == 2024){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/metas_proyectos/2024"); ?>"><b>2024</b></a>
					</li>
				</ul>
				<br>
				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Proyecto Inversión</th>
								<th class="text-center">Vigencia</th>
								<th class="text-center">Meta Proyecto Inversión</th>
								<th class="text-center">Presupuesto Meta</th>
								<th class="text-center">Recurso Ejecutado Meta</th>
								<th class="text-center">Programado Meta anual</th>
								<th class="text-center">Ejecutado Meta</th>
								<th class="text-center">Unidad</th>
								<th class="text-center">Tipología de Anualidad</th>
								<th class="text-center" style="width: 8%">Editar</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['numero_meta_proyecto'] . "</td>";
									echo "<td>" . $lista['numero_proyecto_inversion'] . " " . $lista['nombre_proyecto_inversion']  . "</td>";
									echo "<td class='text-center'>" . $lista['vigencia_meta_proyecto'] . "</td>";
									echo "<td>" . $lista['numero_meta_proyecto'] . " " . $lista['meta_proyecto'] . "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['presupuesto_meta']) . "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['recurso_ejecutado_meta']) . "</td>";
									echo "<td class='text-right'>" . $lista['programado_meta_proyecto'] . "</td>";
									echo "<td class='text-right'>" . $lista['ejecutado_meta'] . "</td>";
									echo "<td>" . $lista['unidad_meta_proyecto'] . "</td>";
									echo "<td>" . $lista['tipologia'] . "</td>";
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_meta_proyecto_inversion']; ?>" >
										Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
									<button type="button" id="<?php echo $lista['id_meta_proyecto_inversion']; ?>" class='btn btn-danger btn-xs' title="Eliminar">
											<i class="fa fa-trash-o"></i>
									</button>
						<?php
									echo "</td>";
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
		"pageLength": 100,
		paging: false
	});
});
</script>