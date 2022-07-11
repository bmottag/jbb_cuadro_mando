<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Comentarios Monitoreo OAP
	<br><b>Actividad No.: </b> <?php echo $numeroActividad; ?>
	</h4>
</div>

<div class="modal-body small">
<?php 
	if(!$information){ 
?>
	<p class="text-danger text-left">No hay registros.</p>
<?php 
	}else{ 
?>
		<table class='table table-hover'>
			<thead>
				<tr class="headings">
					<th><small>No. Trimestre</small></th>
					<th><small>Fecha</small></th>
					<th><small>Estado</small></th>
					<th><small>Usuario</small></th>
					<th><small>Observación</small></th>
				</tr>
			</thead>

			<tbody>
                <?php 
                    foreach ($information as $data):     
                ?>
                    <tr>
                        <td><small><?php echo "Trimestre " .  $data['numero_trimestre']; ?></small></td>
                        <td><small><?php echo $data['fecha_cambio']; ?></small></td>
                        <td><small><?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?></small></td>
                        <td><small><?php echo $data['usuario']; ?></small></td>
                        <td><small><?php echo $data['observacion']; ?></small></td>
                    </tr>
                <?php
                    endforeach;
                ?>
			</tbody>
		</table>
<?php } ?>
</div>