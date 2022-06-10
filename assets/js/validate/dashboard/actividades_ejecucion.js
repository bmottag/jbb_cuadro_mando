$( document ).ready( function () {

	$(".btn-danger").click(function () {				
			//Activa icono guardando
			if(window.confirm('Por favor confirmar que desea cerrar el Trimestre, \n se notificará al Supervisor para que realice la revisión del Trimestre.'))
			{
					$(".btn-danger").attr('disabled','-1');
					var numeroTrimestre = $(this).attr("id");
					var idCuadroBase = $('#idCuadroBase').val();
				    var numeroActividad = $('#numeroActividad').val();
				    var avancePOA = $('#avancePOA').val();
					if(numeroTrimestre == 1){
						var cumplimientoTrimestre = $('#cumplimiento1').val();
					}else if(numeroTrimestre == 2){
						var cumplimientoTrimestre = $('#cumplimiento2').val();
					}else if(numeroTrimestre == 3){
						var cumplimientoTrimestre = $('#cumplimiento3').val();
					}else if(numeroTrimestre == 4){
						var cumplimientoTrimestre = $('#cumplimiento4').val();
					}

					$.ajax ({
						type: 'POST',
						url: base_url + 'dashboard/update_trimestre',
						data: {'numeroTrimestre': numeroTrimestre,'idCuadroBase': idCuadroBase,'numeroActividad': numeroActividad,'cumplimientoTrimestre': cumplimientoTrimestre,'avancePOA': avancePOA },
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
								var url = base_url + "dashboard/actividades/" + data.idCuadroBase + "/" + data.numeroActividad;
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
	
});