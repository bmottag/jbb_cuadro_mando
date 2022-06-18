/**
 * Numero actividades filtrado por Numero objetivo estrategico
 * @author bmottag
 * @since  17/06/2022
 */

$(document).ready(function () {
	
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
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'numero_objetivo': numero_objetivo},
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
				url: base_url + 'dashboard/numeroActividadesList',
				data: {'numero_objetivo': numero_objetivo, 'numero_proyecto': numero_proyecto},
				cache: false,
				success: function (data)
				{
					$('#numero_actividad').html(data);
				}
			});
        });
    });

});