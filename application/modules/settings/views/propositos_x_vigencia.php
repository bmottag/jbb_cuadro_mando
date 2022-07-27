<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/propositos_x_vigencia.js"); ?>"></script>
<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'settings/cargarModalPropositosXVigencia',
                data: {'idPropositoVigencia': oID},
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
					<i class="fa fa-gear fa-fw"></i> CONFIGURACIÓN - PROPOSITOS POR VIGENCIA
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
					<i class="fa fa-thumb-tack"></i> LISTA PROPOSITOS POR VIGENCIA
					<div class="pull-right">
						<div class="btn-group">																				
							<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="x">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Proposito
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
					<li <?php if($vigencia == 2020){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/propositos_x_vigencia/2020"); ?>"><b>2020</b></a>
					</li>
					<li <?php if($vigencia == 2021){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/propositos_x_vigencia/2021"); ?>"><b>2021</b></a>
					</li>
					<li <?php if($vigencia == 2022){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/propositos_x_vigencia/2022"); ?>"><b>2022</b></a>
					</li>
					<li <?php if($vigencia == 2023){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/propositos_x_vigencia/2023"); ?>"><b>2023</b></a>
					</li>
					<li <?php if($vigencia == 2024){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/propositos_x_vigencia/2024"); ?>"><b>2024</b></a>
					</li>
				</ul>
				<br>
				<?php
					if($info){
				?>				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th width="5%" class="text-center">No.</th>
								<th width="24%" class="text-center">Proposito</th>
								<th width="5%" class="text-center">Vigencia</th>
								<th width="30%" class="text-center">Recurso programado</th>
								<th width="10%" class="text-center">Recurso ejecutado</th>
								<th width="8%" class="text-center">Porcentaje cumplimiento</th>
								<th width="10%" class="text-center">Editar</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['numero_proposito'] . "</td>";
									echo "<td>" . $lista['proposito'] . "</td>";
									echo "<td class='text-center'>" . $lista['vigencia_proposito'] . "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['recurso_programado_proposito']) . "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['recurso_ejecutado_proposito']) . "</td>";
									echo "<td class='text-right'>" . round($lista['porcentaje_cumplimiento_proposito'],2) . " %</td>";
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_proposito_vigencia']; ?>" >
										Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
									<button type="button" id="<?php echo $lista['id_proposito_vigencia']; ?>" class='btn btn-danger btn-xs' title="Eliminar">
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