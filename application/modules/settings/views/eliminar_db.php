<script>
$(function(){ 
	$(".btn-danger").click(function () {	
			
			//Activa icono guardando
			if(window.confirm('Esta seguro de eliminar los registros de la Base de Datos?'))
			{
					$(".btn-danger").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'settings/eliminar_db',
						cache: false,
						success: function(data){
												
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-danger").removeAttr('disabled');							
								return false;
							} 
											
							if( data.result )//true
							{	                                                        
								$(".btn-danger").removeAttr('disabled');

								var url = base_url + "settings/atencion_eliminar";
								$(location).attr("href", url);
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-danger").removeAttr('disabled');
							}	
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-danger").removeAttr('disabled');
						}

					});
			}
	});
	
	$(".btn-warning").click(function () {	
			
			//Activa icono guardando
			if(window.confirm('Esta seguro de eliminar los registros de la Base de Datos?'))
			{
					$(".btn-warning").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'settings/eliminar_metas_objetivos_db',
						cache: false,
						success: function(data){
												
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-warning").removeAttr('disabled');							
								return false;
							} 
											
							if( data.result )//true
							{	                                                        
								$(".btn-warning").removeAttr('disabled');

								var url = base_url + "settings/atencion_eliminar";
								$(location).attr("href", url);
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-warning").removeAttr('disabled');
							}	
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-warning").removeAttr('disabled');
						}

					});
			}
	});
	
	$(".btn-info").click(function () {	
			
			//Activa icono guardando
			if(window.confirm('Esta seguro de eliminar los registros de la Base de Datos?'))
			{
					$(".btn-info").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'settings/eliminar_indicadores_objetivos_db',
						cache: false,
						success: function(data){
												
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-info").removeAttr('disabled');							
								return false;
							} 
											
							if( data.result )//true
							{	                                                        
								$(".btn-info").removeAttr('disabled');

								var url = base_url + "settings/atencion_eliminar";
								$(location).attr("href", url);
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-info").removeAttr('disabled');
							}	
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-info").removeAttr('disabled');
						}

					});
			}
	});
	
	$(".btn-atencion").click(function () {	
			
			//Activa icono guardando
			if(window.confirm('Esta seguro de eliminar los registros de la Base de Datos?'))
			{
					$(".btn-atencion").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'settings/eliminar_resultados_objetivos_db',
						cache: false,
						success: function(data){
												
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-atencion").removeAttr('disabled');							
								return false;
							} 
											
							if( data.result )//true
							{	                                                        
								$(".btn-atencion").removeAttr('disabled');

								var url = base_url + "settings/atencion_eliminar";
								$(location).attr("href", url);
							}
							else
							{
								alert('Error. Reload the web page.');
								$(".btn-atencion").removeAttr('disabled');
							}	
						},
						error: function(result) {
							alert('Error. Reload the web page.');
							$(".btn-atencion").removeAttr('disabled');
						}

					});
			}
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
						<i class="fa fa-gear fa-fw"></i> CONFIGURACIONES - ELIMINAR REGISTROS DE LA BASE DE DATOS
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
					<i class="fa fa-gears"></i> ELIMINAR REGISTROS DE LA BASE DE DATOS
				</div>
				<div class="panel-body">
				
<?php
$retornoExito = $this->session->flashdata('retornoExito');
if ($retornoExito) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-success ">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo $retornoExito ?>		
		</div>
	</div>
    <?php
}

$retornoError = $this->session->flashdata('retornoError');
if ($retornoError) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo $retornoError ?>
		</div>
	</div>
    <?php
}
?> 

					<div class="row">
						<div class="col-lg-3">	
							<div class="alert alert-danger">
								<strong>Atención:</strong> <br>Al aceptar borrará toda la información de Actividades, Historial de Actividades, Estados de las Actividades y Ejecución de las actividades.
								
								<br><br>
								<button type="button" class="btn btn-danger btn-xs" >
									Aceptar <span class="fa fa-times fa-fw" aria-hidden="true">
								</button>						
							</div>
						</div>

						<div class="col-lg-3">	
							<div class="alert alert-warning">
								<strong>Borrar Metas Objetivos Estratégicos:</strong> <br>Al aceptar borrará los registros de la tabla  objetivos_estrategicos_metas.
								<br><br>
								<button type="button" class="btn btn-warning btn-xs" >
									Aceptar <span class="fa fa-times fa-fw" aria-hidden="true">
								</button>						
							</div>
						</div>
						
						<div class="col-lg-3">	
							<div class="alert alert-info">
								<strong>Borrar Indicadores Objetivos Estratégicos:</strong> <br>Al aceptar borrará los registros de la tabla  objetivos_estrategicos_indicadores.
								
								<br><br>
								<button type="button" class="btn btn-info btn-xs" >
									Aceptar <span class="fa fa-times fa-fw" aria-hidden="true">
								</button>						
							</div>
						</div>
						
						<div class="col-lg-3">	
							<div class="alert alert-atencion">
								<strong>Borrar Resultados Objetivos Estratégicos:</strong> <br>Al aceptar borrará los registros de la tabla  objetivos_estrategicos_resultados.
								
								<br><br>
								<button type="button" class="btn btn-atencion btn-xs" >
									Aceptar <span class="fa fa-times fa-fw" aria-hidden="true">
								</button>						
							</div>
						</div>
						
					</div>
					
				</div>
				<!-- /.row (nested) -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
	
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-gears"></i> SUBIR REGISTROS A LA BASE DE DATOS
				</div>
				<div class="panel-body">
									
					<div class="row">
						
						<div class="col-lg-3">	
							<div class="alert alert-success">
								Subir registros a la tabla <strong>Actividades</strong>.
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_actividades'; ?> ">
Subir Actividades <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 
								
								<br><br>
								Subir registros a la tabla <strong>Actividades Ejecución</strong>.
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_actividades_ejecucion'; ?> ">
Subir Actividades Ejecución <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 

								<br><br>
								Actualizar mensaje de POA para primer trimestre
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_mensaje_poa'; ?> ">
Subir Mensaje POA TRIM. I <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 

								<br><br>
								Actualizar Plan Institucional
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_plan_institucional'; ?> ">
Actualizar Plan Institucional <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 

						
							</div>
						</div>
						
						<div class="col-lg-3">	
							<div class="alert alert-success">
								Subir registros a la tabla <strong>Metas Objetivos Estratégicos</strong>. 
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_metas_objetivos_estrategicos'; ?> ">
Subir Metas <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 
						
							</div>
						</div>
						
						<div class="col-lg-3">	
							<div class="alert alert-success">
								Subir registros a la tabla <strong>Indicadores Objetivos Estratégicos</strong>. 
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_indicadores_objetivos_estrategicos'; ?> ">
Subir Indicadores <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 

							</div>
						</div>
					
						<div class="col-lg-3">	
							<div class="alert alert-success">
								Subir registros a la tabla <strong>Resultados Objetivos Estratégicos</strong>. 
								<br><br>
<a class="btn btn-success btn-xs" href=" <?php echo base_url(). 'settings/subir_archivo/cargar_resultados_objetivos_estrategicos'; ?> ">
Subir Resultados <span class="glyphicon glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a> 
						
							</div>
						</div>
						
					</div>
					
					
				</div>
			</div>
		</div>
	</div>
</div>