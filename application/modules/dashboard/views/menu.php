<div class="col-lg-3">
	<div class="panel panel-info">
		<div class="panel-heading small">
			<i class="fa fa-thumb-tack"></i> 
				<strong>Estrategia: </strong>
				<br><?php echo $listaEstrategias[0]['numero_estrategia'] . ' ' . $listaEstrategias[0]['estrategia']; ?>
		</div>
		<div class="panel-body small">
			<strong>Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['proyecto_inversion']; ?><br>
			<strong>Meta Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['meta_proyecto']; ?><br>
			<strong>Propósito: </strong><br><?php echo $infoCuadroBase[0]['proposito']; ?><br>
			<strong>Logro: </strong><br><?php echo $infoCuadroBase[0]['logro']; ?><br>
			<strong>Programa Estretégico: </strong><br><?php echo $infoCuadroBase[0]['programa']; ?><br>
			<strong>Meta PDD: </strong><br><?php echo $infoCuadroBase[0]['meta_pdd']; ?><br>
			<strong>ODS: </strong><br><?php echo $infoCuadroBase[0]['ods']; ?><br>
			<strong>Dependencia: </strong><br><?php echo $infoCuadroBase[0]['dependencia']; ?>
		</div>

		<?php
			$classInactivo = "btn btn-outline btn-default btn-block";
			$classActivo = "btn btn-info btn-block";
		?>
	</div>

</div>