<div class="col-lg-3">
	<div class="panel panel-info">
		<div class="panel-heading small">
			<i class="fa fa-thumb-tack"></i> 
				<strong>Objetivos Estratégicos: </strong>
				<br><?php echo $listaEstrategias[0]['numero_estrategia'] . ' ' . $listaEstrategias[0]['estrategia']; ?>
		</div>
		<div class="panel-body small">
			<strong>Meta Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['meta_proyecto']; ?><br>
			<strong>Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['proyecto_inversion']; ?><br>
			<strong>Meta PDD: </strong><br><?php echo $infoCuadroBase[0]['meta_pdd']; ?><br>
			<strong>Programa Estratégico: </strong><br><?php echo $infoCuadroBase[0]['programa']; ?><br>
			<strong>Logro: </strong><br><?php echo $infoCuadroBase[0]['logro']; ?><br>
			<strong>Propósito: </strong><br><?php echo $infoCuadroBase[0]['proposito']; ?><br>
			<strong>ODS: </strong><br><?php echo $infoCuadroBase[0]['ods']; ?><br>
			<strong>Dependencia: </strong>
<?php 
            $x=0;
            foreach ($infoDependencias as $datos):
                $x++;
                echo "<p class='text-primary'>" . $x . " " . $datos["dependencia"] . "</p>";
            endforeach;
?>
		</div>

		<?php
			$classInactivo = "btn btn-outline btn-default btn-block";
			$classActivo = "btn btn-info btn-block";
		?>
	</div>

</div>