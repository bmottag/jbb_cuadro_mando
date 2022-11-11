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
                data: {'numeroActividad': oID, 'numeroSemestre': 1, 'bandera': 1},
                cache: false,
                success: function (data) {
                    $('#tablaDatosEvaluacion').html(data);
                }
            });
    });

    $(".btn-warning").click(function () {   
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'resumen/cargarModalEvaluacionOCI',
                data: {'numeroActividad': oID, 'numeroSemestre': 2, 'bandera': 1},
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

<?php         
    $userRol = $this->session->userdata("role");

    if ($retornoExito) {
?>
        <div class="alert alert-success ">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <?php echo $retornoExito ?>     
        </div>
<?php
    }
?>
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="formCheckin" id="formCheckin" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group"><br>
                                                <?php
                                                    if($listaActividades && ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_JEFEOCI)){
                                                        $valor = $listaActividades[0]["publicar_calificacion_1"];
                                                        if($valor == 0){
                                                            $textoBoton = "Publicar Calificación Primer Semestre";
                                                            $estilos = "btn-primary";
                                                        }else{
                                                            $textoBoton = "Despublicar Calificación Primer Semestre";
                                                            $estilos = "btn-danger";
                                                        }
                                                ?>
                                                    <input type="hidden" id="estado1" name="estado1" value="<?php echo $listaActividades[0]["publicar_calificacion_1"]; ?>" >
                                                    <button type="submit" id="btnPrimerSemestre" name="btnPrimerSemestre" class="btn <?php echo $estilos; ?> btn-sm" value="1">
                                                        <?php echo $textoBoton; ?> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                    </button> 
                                                <?php
                                                    }
                                                ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group"><br>
                                                <?php
                                                    if($listaActividades && ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_JEFEOCI)){
                                                        $valor = $listaActividades[0]["publicar_calificacion_2"];
                                                        if($valor == 0){
                                                            $textoBoton = "Publicar Calificación Segundo Semestre";
                                                            $estilos = "btn-primary";
                                                        }else{
                                                            $textoBoton = "Despublicar Calificación Segundo Semestre";
                                                            $estilos = "btn-danger";
                                                        }
                                                ?>
                                                    <input type="hidden" id="estado2" name="estado2" value="<?php echo $listaActividades[0]["publicar_calificacion_2"]; ?>" >
                                                    <button type="submit" id="btnSegundoSemestre" name="btnSegundoSemestre" class="btn <?php echo $estilos; ?> btn-sm" value="1">
                                                        <?php echo $textoBoton; ?> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                    </button> 
                                                <?php
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center"><small>No. Actividad</small></th>
                                <th width="25%"><small>Actividad</small></th>
                                <th width="8%" class="text-right"><small>Cump. Trim. I</small></th>
                                <th width="8%" class="text-right"><small>Cump. Trim. II</small></th>
                                <th width="8%" class="text-right"><small>Cump. Trim. III</small></th>
                                <th width="8%" class="text-right"><small>Cump. Trim. IV</small></th>
                                <th width="8%" class="text-right"><small>Avance POA</small></th>
                                <th width="10%" class="text-right"><small>Calificación OCI</small></th>
                                <th width="15%"><small>Observación OCI</small></th>
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
                                    ?><br><br>
                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalEvaluacion" id="<?php echo $lista['numero_actividad']; ?>" title="Evaluación OCI" <?php if($lista["publicar_calificacion_1"] == 1) { ?> disabled <?php } ?>>
                                        Evaluación OCI <span class="fa fa-pencil" aria-hidden="true"></span>
                                    </button><br><br>
                                    <?php } ?>

                                    <?php
                                        if(($lista["estado_trimestre_3"] == 5 || $lista["estado_trimestre_3"] == 6 || $lista["estado_trimestre_3"] == 7) && ($lista["estado_trimestre_4"] == 5 || $lista["estado_trimestre_4"] == 6 || $lista["estado_trimestre_4"] == 7) && ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_CONTROL_INTERNO || $userRol == ID_ROL_JEFEOCI)){
                                    ?>
                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEvaluacion" id="<?php echo $lista['numero_actividad']; ?>" title="Evaluación OCI" <?php if($lista["publicar_calificacion_2"] == 1) { ?> disabled <?php } ?>>
                                        Evaluación OCI II <span class="fa fa-pencil" aria-hidden="true"></span>
                                    </button>
                                    <?php } ?>
                                    </td>
                                    <td><small><?php echo $lista["descripcion_actividad"] ?></small></td>
                                    <td class="text-right"><small><?php echo $trim1 ?></small></td>
                                    <td class="text-right"><small><?php echo $trim2; ?></small></td>
                                    <td class="text-right"><small><?php echo $trim3; ?></small></td>
                                    <td class="text-right"><small><?php echo $trim4; ?></small></td>
                                    <td class="text-right"><small><?php echo $avancePoa; ?></small></td>
                                    <td class="text-right"><small>
                                        <?php 
                                            if($lista["calificacion_semestre_1"]){
                                                echo "<b>Primer Semestre:</b><br>". $lista["calificacion_semestre_1"] . "<br>";
                                            }
                                            if($lista["calificacion_semestre_2"]){
                                                echo "<b>Segundo Semestre:</b><br>" . $lista["calificacion_semestre_2"];
                                            }
                                        ?>
                                    </small></td>
                                    <td><small>
                                        <?php 
                                            if($lista["observacion_semestre_1"]){
                                                echo "<b>Primer Semestre:</b><br>". $lista["observacion_semestre_1"] . "<br>";
                                            }
                                            if($lista["observacion_semestre_2"]){
                                                echo "<b>Segundo Semestre:</b><br>" . $lista["observacion_semestre_2"];
                                            }
                                        ?>
                                    </small></td>
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
<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
        "pageLength": 100,
        paging: false
    });
});
</script>