<script type="text/javascript" src="<?php echo base_url("assets/js/validate/resumen/form_estado_actividad.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/ajaxSearch.js"); ?>"></script>

<script>
$(function(){ 
    $(".btn-default").click(function () {   
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'resumen/cargarModalComentariosPOA',
                data: {'numeroActividad': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatosComentarios').html(data);
                }
            });
    }); 

    $(".btn-success").click(function () {   
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'resumen/cargarModalEvaluacionOCI',
                data: {'numeroActividad': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatosEvaluacion').html(data);
                }
            });
    }); 

});
</script>

<?php
    $userRol = $this->session->userdata("role");           
?>
<div id="page-wrapper">
    <br>




    <div class="row">
        <div class="col-lg-12"> 
             <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Actividades seleccionadas para evaluación por la Oficina de Control Interno
                </div>
                <div class="panel-body small">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="15%" class="text-center"><small>No. Actividad</small></th>
                                <th width="30%"><small>Actividad</small></th>
                                <th width="7%" class="text-right"><small>Cump. Trim. I</small></th>
                                <th width="7%" class="text-right"><small>Cump. Trim. II</small></th>
                                <th width="7%" class="text-right"><small>Cump. Trim. III</small></th>
                                <th width="7%" class="text-right"><small>Cump. Trim. IV</small></th>
                                <th width="7%" class="text-right"><small>Avance POA</small></th>
                                <th width="10%" class="text-right"><small>Calificación OCI</small></th>
                                <th width="10%"><small>Observación OCI</small></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if($listaActividades){
                                foreach ($listaActividades as $lista):
                                    $trim1 = "0%";
                                    $trim2 = "0%";
                                    $trim3 = "0%";
                                    $trim4 = "0%";
                                    $avancePoa = "0%";
                                    if($lista["trimestre_1"] != '' && $lista["trimestre_1"] > 0){
                                        $trim1 = $lista["trimestre_1"] . "%";
                                    }
                                    if($lista["trimestre_2"] != '' && $lista["trimestre_2"] > 0){
                                        $trim2 = $lista["trimestre_2"] . "%";
                                    }
                                    if($lista["trimestre_3"] != '' && $lista["trimestre_3"] > 0){
                                        $trim3 = $lista["trimestre_3"] . "%";
                                    }
                                    if($lista["trimestre_4"] != '' && $lista["trimestre_4"] > 0){
                                        $trim4 = $lista["trimestre_4"] . "%";
                                    }
                                    if($lista["avance_poa"] != '' && $lista["avance_poa"] > 0){
                                        $avancePoa = $lista["avance_poa"] . "%";
                                    }
                                     
                        ?>
                                <tr>
                                    <td>
                                    <?php
                                        echo "<a class='btn btn-primary btn-xs' title='Ver Detalle Actividad No. " . $lista["numero_actividad"] . "' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'>". $lista['numero_actividad'] . " <span class='fa fa-eye' aria-hidden='true'></span></a>";
                                     ?>
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalComentarios" id="<?php echo $lista['numero_actividad']; ?>" title="Comentarios Monitoreo OAP">
                                                    <i class="fa fa-comments fa-fw"></i>
                                            </button>

                                    <?php
                                        if(($lista["estado_trimestre_1"] == 5 || $lista["estado_trimestre_1"] == 6 || $lista["estado_trimestre_1"] == 7) && ($lista["estado_trimestre_2"] == 5 || $lista["estado_trimestre_2"] == 6 || $lista["estado_trimestre_2"] == 7) && ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_CONTROL_INTERNO || $userRol == ID_ROL_JEFEOCI)){
                                    ?>
                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalEvaluacion" id="<?php echo $lista['numero_actividad']; ?>" title="Evaluación OCI">
                                        Evaluación OCI <span class="fa fa-pencil" aria-hidden="true"></span>
                                    </button>
                                    <?php } ?>
                                    </td>
                                    <td><small><?php echo $lista["descripcion_actividad"] ?></small></td>
                                    <td class="text-right"><small><?php echo $trim1 ?></small></td>
                                    <td class="text-right"><small><?php echo $trim2; ?></small></td>
                                    <td class="text-right"><small><?php echo $trim3; ?></small></td>
                                    <td class="text-right"><small><?php echo $trim4; ?></small></td>
                                    <td class="text-right"><small><?php echo $avancePoa; ?></small></td>
                                    <td class="text-right"><small><?php echo $lista["calificacion_semestre_1"] ?></small></td>
                                    <td><small><?php echo $lista["observacion_semestre_1"] ?></small></td>
                                </tr>
                        <?php
                                endforeach;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>           
        </div>     
    </div>
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="tablaDatosComentarios">

        </div>
    </div>
</div>                       
<!--FIN Modal -->

<!--INICIO Modal -->
<div class="modal fade text-center" id="modalEvaluacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="tablaDatosEvaluacion">

        </div>
    </div>
</div>                       
<!--FIN Modal -->