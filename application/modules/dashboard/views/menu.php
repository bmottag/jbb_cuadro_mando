<div class="col-lg-3 small">
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
			if($infoDependencias){
				echo "<ul>";
	            foreach ($infoDependencias as $datos):
	                echo "<li class='text-primary'>" . $datos["dependencia"] . "</li>";
	            endforeach;
	            echo "</ul>";
	        }
?>
		</div>

		<?php
			$classInactivo = "btn btn-outline btn-default btn-block";
			$classActivo = "btn btn-info btn-block";
		?>
	</div>

<?php 
	if($listaHistorial)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i> Historial
		</div>
		<div class="panel-body">
			<ul class="chat">
			<?php 
				foreach ($listaHistorial as $data):		
			?>
				<li class="right clearfix">
					<span class="chat-img pull-right">
						<small class="pull-right text-muted">
							<i class="fa fa-clock-o fa-fw"></i> <?php echo $data['fecha_cambio']; ?>
						</small>
					</span>
					<div class="chat-body clearfix">
						<div class="header">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							<strong class="primary-font"><?php echo $data['first_name']; ?></strong>
						</div>
						<p>
							<?php echo $data['observacion']; ?>
						</p>
						<?php echo '<p class="' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
					</div>
				</li>
			<?php
				endforeach;
			?>
			</ul>		
		</div>
	</div>
<?php
	}
?>
</div>	