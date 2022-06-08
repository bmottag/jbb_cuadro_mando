/**
 * Meta proyecto 
 * @author bmottag
 * @since  08/06/2022
 */

$(document).ready(function () {
	
    $('#id_proyecto_inversion').change(function () {
        $('#id_proyecto_inversion option:selected').each(function () {
			var numeroProyecto = $('#id_proyecto_inversion').val();
			if (numeroProyecto > 0 || numeroProyecto != '') {
				$.ajax ({
					type: 'POST',
					url: base_url + 'settings/metaProyectoList',
					data: {'numeroProyecto': numeroProyecto},
					cache: false,
					success: function (data)
					{
						$('#id_meta_proyecto_inversion').html(data);
					}
				});
			} else {
				var data = '';
				$('#id_meta_proyecto_inversion').html(data);
			}
        });
    });
});