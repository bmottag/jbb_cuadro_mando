$( document ).ready( function () {
	
	$( "#form" ).validate( {
		rules: {
			id_objetivo_pmr:			{ required: true },
			id_elemento_pep_pmr:		{ required: true },
			id_producto_pmr:			{ required: true },
			id_proyecto_inversion:		{ required: true },
			id_indicador_pmr:			{ required: true },
			id_unidad_medida_pmr:		{ required: true },
			id_naturaleza_pmr:			{ required: true },
			id_periodicidad_pmr: 		{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			error.addClass( "help-block" );
			error.insertAfter( element );
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});

	$(".btn-danger").click(function () {
			var oID = $(this).attr("id");
			if(window.confirm('Por favor confirmar si desea eliminar el indicador del tablero PMR.'))
			{
					$(".btn-danger").attr('disabled','-1');
					$.ajax ({
						type: 'POST',
						url: base_url + 'settings/delete_tablero_pmr',
						data: {'identificador': oID},
						cache: false,
						success: function(data){
							if( data.result == "error" )
							{
								alert(data.mensaje);
								$(".btn-danger").removeAttr('disabled');							
								return false;
							} 
							if( data.result )
							{	                                                        
								$(".btn-danger").removeAttr('disabled');
								var url = base_url + "settings/tablero_pmr";
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
	
	$("#btnSubmit").click(function(){		
		if ($("#form").valid() == true){
				$('#btnSubmit').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
				$.ajax({
					type: "POST",
					url: base_url + "settings/save_tablero_pmr",
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					success: function(data){
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');
							return false;
						}
						if( data.result )
						{
							$("#div_load").css("display", "none");
							$('#btnSubmit').removeAttr('disabled');
							var url = base_url + "settings/tablero_pmr";
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
	});
});