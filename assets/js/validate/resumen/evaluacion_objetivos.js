$( document ).ready( function () {

	$( "#form" ).validate( {
		rules: {
			calificacion: 			{ required: true, minlength: 1, maxlength:10 },
			observacion: 			{ required: true },
			comentario: 			{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-4" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-4" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmit").click(function(){
		var calificacion = $("#calificacion").val();
		var cumplimiento = $("#hddCumplimientoPOA").val();
		if (calificacion > 0 && calificacion <= cumplimiento) {
			alert('la calificacion no puede ser menor o igual al promedio de cumplimiento actual.')
		} else {
			if ($("#form").valid() == true){
				//Activa icono guardando
				$('#btnSubmit').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
				$.ajax({
					type: "POST",	
					url: base_url + "resumen/guardar_evaluacion_objetivos",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					success: function(data){
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.mensaje);
							$('#btnSubmit').removeAttr('disabled');
							return false;
						} 
						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');
							var url = base_url + "resumen/objetivos_estrategicos";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmit').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmit').removeAttr('disabled');
					}
				});	
			}
		}
	});

	$("#btnAprobar").click(function(){
		if ($("#form").valid() == true){
				//Activa icono guardando
				$('#btnAprobar').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
				$.ajax({
					type: "POST",
					url: base_url + "resumen/aprobar_evaluacion_objetivos",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					success: function(data){
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.mensaje);
							$('#btnAprobar').removeAttr('disabled');
							return false;
						} 
						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnAprobar').removeAttr('disabled');
							var url = base_url + "resumen/objetivos_estrategicos";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnAprobar').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnAprobar').removeAttr('disabled');
					}
				});	
		}
	});

	$("#btnRechazar").click(function(){
		if ($("#form").valid() == true){
				//Activa icono guardando
				$('#btnRechazar').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
				$.ajax({
					type: "POST",	
					url: base_url + "resumen/rechazar_evaluacion_objetivos",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					success: function(data){
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.mensaje);
							$('#btnRechazar').removeAttr('disabled');
							return false;
						} 
						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnRechazar').removeAttr('disabled');
							var url = base_url + "resumen/objetivos_estrategicos";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnRechazar').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnRechazar').removeAttr('disabled');
					}
				});	
		}
	});

	$("#btnDevolver").click(function(){
		if ($("#form").valid() == true){
				//Activa icono guardando
				$('#btnDevolver').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
				$.ajax({
					type: "POST",	
					url: base_url + "resumen/devolver_evaluacion_objetivos",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					success: function(data){
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$("#span_msj").html(data.mensaje);
							$('#btnDevolver').removeAttr('disabled');
							return false;
						} 
						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnDevolver').removeAttr('disabled');
							var url = base_url + "resumen/objetivos_estrategicos";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnDevolver').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnDevolver').removeAttr('disabled');
					}
				});	
		}
	});
});