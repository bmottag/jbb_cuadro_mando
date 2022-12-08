<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Historial Comentarios Objetivos Estratégicos
	<br><b>Objetivo Estratégico No.: </b> <?php echo $numeroObjetivoEstrategico; ?>
	</h4>
</div>

<div class="modal-body small">
<?php 
	if(!$comentarios){ 
?>
	<p class="text-danger text-left">No hay registros.</p>
<?php 
	} else { 
?>
		<table class='table table-hover'>
			<thead>
				<tr class="headings">
					<th><small>Fecha Envío</small></th>
					<th><small>Usuario</small></th>
					<th><small>Comentario</small></th>
					<th><small>Fecha Respuesta</small></th>
					<th><small>Supervisor</small></th>
					<th><small>Comentario Supervisor</small></th>
					<th class="text-right"><small>Cumplimiento POA</small></th>
					<th class="text-right"><small>Cumplimiento</small></th>
					<th><small>Estado</small></th>
				</tr>
			</thead>

			<tbody>
                <?php 
                    foreach ($comentarios as $data):
                    	$usuario = $this->general_model->get_usuarios($data['fk_id_usuario']);
                    	$supervisor = $this->general_model->get_usuarios($data['fk_id_supervisor']);
                ?>
                    <tr>
                        <td class="text-left"><small><?php echo $data['fecha_cambio']; ?></small></td>
                        <td class="text-left"><small><?php echo $usuario['first_name'] . ' ' . $usuario['last_name']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['comentario']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['fecha_comentario']; ?></small></td>
                        <td class="text-left"><small><?php echo $supervisor['first_name'] . ' ' . $supervisor['last_name']; ?></small></td>
                        <td class="text-left"><small><?php echo $data['comentario_supervisor']; ?></small></td>
                        <td class="text-right"><small><?php echo $data['cumplimiento_poa']; ?></small></td>
                        <td class="text-right"><small><?php echo $data['calificacion']; ?></small></td>
                        <td class="text-left"><small><?php if ($data['estado'] == 1) { echo 'Enviada'; } else if ($data['estado'] == 2) { echo 'Aprobada'; } else if ($data['estado'] == 3) { echo 'Rechazada'; } else if ($data['estado'] == 4) { echo 'Devuelta'; } ?></small></td>
                    </tr>
                <?php
                    endforeach;
                ?>
			</tbody>
		</table>
<?php } ?>
</div>