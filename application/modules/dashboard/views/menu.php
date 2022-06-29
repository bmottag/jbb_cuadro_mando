<div class="col-lg-3 small">
	<div class="panel panel-info">
		<div class="panel-heading small">
			<i class="fa fa-thumb-tack"></i> 
				<strong>Objetivos Estratégicos: </strong>
				<br><?php echo $listaObjetivosEstrategicos[0]['numero_objetivo_estrategico'] . ' ' . $listaObjetivosEstrategicos[0]['objetivo_estrategico']; ?>
		</div>
		<div class="panel-body small">
			<strong>Meta Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['meta_proyecto']; ?><br>
			<strong>Proyecto Inversión: </strong><br><?php echo $infoCuadroBase[0]['proyecto_inversion']; ?><br>
			<strong>Meta PDD: </strong><br><?php echo $infoCuadroBase[0]['meta_pdd']; ?><br>
			<strong>Programa Estratégico: </strong><br><?php echo $infoCuadroBase[0]['programa']; ?><br>
			<strong>Logro: </strong><br><?php echo $infoCuadroBase[0]['logro']; ?><br>
			<strong>Propósito: </strong><br><?php echo $infoCuadroBase[0]['proposito']; ?><br>
			<strong>ODS: </strong><br><?php echo $infoCuadroBase[0]['ods']; ?><br>

		<?php
			$userRol = $this->session->userdata("role");
			if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
		?>
			<br>
			<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $infoCuadroBase[0]['id_cuadro_base']; ?>">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Actividad
			</button>
		<?php
			}
		?>
		</div>


	</div>

<?php 
	if($listaHistorial)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i><b> Historial Trimestre <?php echo $numeroTrimestre; ?></b>
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
						<?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
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


<?php 
	if($listaHistorial1)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i> <b>Historial Trimestre I</b>
		</div>
		<div class="panel-body">
			<ul class="chat">
			<?php 
				foreach ($listaHistorial1 as $data):		
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
						<?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
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

<?php 
	if($listaHistorial2)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i>  <b>Historial Trimestre II </b>
		</div>
		<div class="panel-body">
			<ul class="chat">
			<?php 
				foreach ($listaHistorial2 as $data):		
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
						<?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
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

<?php 
	if($listaHistorial3)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i>  <b>Historial Trimestre III </b>
		</div>
		<div class="panel-body">
			<ul class="chat">
			<?php 
				foreach ($listaHistorial3 as $data):		
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
						<?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
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

<?php 
	if($listaHistorial4)
	{
?>
	<div class="chat-panel panel panel-primary">
		<div class="panel-heading">
			<i class="fa fa-comments fa-fw"></i>  <b>Historial Trimestre IV </b>
		</div>
		<div class="panel-body">
			<ul class="chat">
			<?php 
				foreach ($listaHistorial4 as $data):		
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
						<?php echo '<p class="text-' . $data['clase'] . '"><strong><i class="fa ' . $data['icono']  . ' fa-fw"></i>' . $data['estado'] . '</strong></p>'; ?>
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