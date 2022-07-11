<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Auditoría cambios Programación/Ejecución Actividad
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
					<th class="column-title">Fecha</th>
					<th class="column-title">Trimestre</th>
					<th class="column-title">Usuario</th>
					<th class="column-title">Valores</th>
				</tr>
			</thead>

			<tbody>
			<?php
				foreach ($information as $data):
					echo "<tr>";
			?>		
					<td class='text-center'><?php echo $data["fecha_registro"]; ?></td>
					<td class='text-left'><?php echo $data["numero_trimestre"]!=0?"Trimestre " . $data["numero_trimestre"]:""; ?></td>
					<td class='text-left'><?php echo $data["usuario"]; ?></td>
					<td class='text-left'>
						<small>
						<?php 
				            echo "<pre>";
				            print_r(json_decode($data["valores"]));
				            echo "</pre>";
						?>
						</small>
					</td>
			<?php
					echo "</tr>";
				endforeach;
			?>
			</tbody>
		</table>
<?php } ?>
</div>