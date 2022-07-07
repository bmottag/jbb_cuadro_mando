/**
 * Numero actividades filtrado por Numero objetivo estrategico
 * @author bmottag
 * @since  17/06/2022
 */

$(document).ready(function () {

    $('#id_estrategia').change(function () {
        $('#id_estrategia option:selected').each(function () {
			var id_estrategia = $('#id_estrategia').val();
			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroObjetivosEstrategicosList',
				data: {'id_estrategia': id_estrategia},
				cache: false,
				success: function (data)
				{
					$('#numero_objetivo').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroProyectosList',
				data: {'id_estrategia': id_estrategia, 'numero_objetivo': ''},
				cache: false,
				success: function (data)
				{
					$('#numero_proyecto').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/dependenciaList',
				data: {'id_estrategia': id_estrategia, 'numero_objetivo': '', 'numero_proyecto': ''},
				cache: false,
				success: function (data)
				{
					$('#id_dependencia').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'id_estrategia': id_estrategia, 'numero_objetivo': '', 'numero_proyecto': '', 'id_dependencia': ''},
				cache: false,
				success: function (data)
				{
					$('#numero_actividad').html(data);
				}
			});
        });
    });
	
    $('#numero_objetivo').change(function () {
        $('#numero_objetivo option:selected').each(function () {
			var numero_objetivo = $('#numero_objetivo').val();
			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroProyectosList',
				data: {'numero_objetivo': numero_objetivo},
				cache: false,
				success: function (data)
				{
					$('#numero_proyecto').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/dependenciaList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': ''},
				cache: false,
				success: function (data)
				{
					$('#id_dependencia').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': '', 'id_dependencia': ''},
				cache: false,
				success: function (data)
				{
					$('#numero_actividad').html(data);
				}
			});
        });
    });

    $('#numero_proyecto').change(function () {
        $('#numero_proyecto option:selected').each(function () {
			var numero_proyecto = $('#numero_proyecto').val();
			var numero_objetivo = $('#numero_objetivo').val();
			
			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/dependenciaList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': numero_proyecto},
				cache: false,
				success: function (data)
				{
					$('#id_dependencia').html(data);
				}
			});

			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': numero_proyecto, 'id_dependencia': ''},
				cache: false,
				success: function (data)
				{
					$('#numero_actividad').html(data);
				}
			});
        });
    });

    $('#id_dependencia').change(function () {
        $('#id_dependencia option:selected').each(function () {
			var id_dependencia = $('#id_dependencia').val();
			var numero_proyecto = $('#numero_proyecto').val();
			var numero_objetivo = $('#numero_objetivo').val();
			$.ajax ({
				type: 'POST',
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': numero_proyecto, 'id_dependencia': id_dependencia},
				cache: false,
				success: function (data)
				{
					$('#numero_actividad').html(data);
				}
			});
        });
    });

});