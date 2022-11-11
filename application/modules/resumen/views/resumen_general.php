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
                data: {'numeroActividad': oID, 'numeroSemestre': 1, 'bandera': 0},
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
                data: {'numeroActividad': oID, 'numeroSemestre': 2, 'bandera': 0},
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
                    <div class="row">
                        <div class="col-lg-12">
                            <i class="fa fa-bell fa-fw"></i> No. Actividades: <b><?php echo $nroActividades; ?></b>
                            <div class="pull-right">
                                <div class="btn-group">
                                <?php 
                                    if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR || $userRol == ID_ROL_PLANEACION){
                                ?>
                                    <a href="<?php echo base_url("resumen/reporte"); ?>" class="btn btn-primary btn-xs" target="_blank"><span class="fa fa-file-excel-o" aria-hidden="true" ></span> Descargar Consolidado POA</a>
                                <?php 
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body small">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Trimestre</th>
                                <th class="text-right">No Iniciada</th>
                                <th class="text-right">En Proceso</th>
                                <th class="text-right">Cerrada</th>
                                <th class="text-right">Aprobada Supervisor</th>
                                <th class="text-right">Rechazada Supervisor</th>
                                <th class="text-right">Aprobada Planeación</th>
                                <th class="text-right">Rechazada Planeación</th>
                                <th class="text-right">Incumplida</th>
                            </tr>
                        </thead>
                        <tr>
                            <th>Trimestre I</th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreNoIniciada; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreEnProceso; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreCerrado; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreAprobadoSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreRechazadaSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreAprobadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreRechazadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesPrimerTrimestreIncumplidas; ?></th>
                        </tr>
                        <tr>
                            <th>Trimestre II</th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreNoIniciada; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreEnProceso; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreCerrado; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreAprobadoSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreRechazadaSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreAprobadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreRechazadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesSegundoTrimestreIncumplidas; ?></th>
                        </tr>
                        <tr>
                            <th>Trimestre III</th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreNoIniciada; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreEnProceso; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreCerrado; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreAprobadoSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreRechazadaSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreAprobadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreRechazadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesTercerTrimestreIncumplidas; ?></th>
                        </tr>
                        <tr>
                            <th>Trimestre IV</th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreNoIniciada; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreEnProceso; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreCerrado; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreAprobadoSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreRechazadaSupervisor; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreAprobadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreRechazadaPlaneacion; ?></th>
                            <th class="text-right"><?php echo $nroActividadesCuartoTrimestreIncumplidas; ?></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php          
    if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
?>
<!--INICIO ADDITIONAL INFORMATION -->
    <div class="row">
        <div class="col-lg-12">              
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-cogs fa-fw"></i> Formulario para cambiar el estado por trimestre de todas las actividades
                </div>
                <div class="panel-body">
<?php
    $retornoExito = $this->session->flashdata('retornoExito');
    if ($retornoExito) {
?>
        <div class="alert alert-success ">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <?php echo $retornoExito ?>     
        </div>
<?php
    }
    $retornoError = $this->session->flashdata('retornoError');
    if ($retornoError) {
?>
        <div class="alert alert-danger ">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <?php echo $retornoError ?>
        </div>
<?php
    }
?>

                    <form name="formEstado" id="formEstado" method="post">
                        <div class="panel panel-default">
                            <div class="panel-footer">
                                <div class="row">

                                    <div class="col-lg-2">
                                        <div class="form-group input-group-sm"> 
                                            <label class="control-label" for="idTipoEquipoSearch">Trimestre: *</label>                             
                                            <select name="trimestre" Iid="trimestre" class="form-control" required >
                                                <option value="">Seleccione...</option>
                                                <option value=1 >Trimestre I</option>
                                                <option value=2 >Trimestre II</option>
                                                <option value=3 >Trimestre III</option>
                                                <option value=4 >Trimestre IV</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group input-group-sm"> 
                                            <label class="control-label" for="idTipoEquipoSearch">Estado: *</label>                             
                                            <select name="valorEstado" id="valorEstado" class="form-control" required >
                                                <option value="">Seleccione...</option>
                                                <?php for ($i = 0; $i < count($listaEstados); $i++) { ?>
                                                    <option value="<?php echo $listaEstados[$i]["valor"]; ?>" ><?php echo $listaEstados[$i]["estado"]; ?></option>        
                                                <?php } ?>
                                                    <option value="99" >Realizar Cálculo</option>
                                            </select>
                                        </div>
                                    </div>

                                   <div class="col-lg-6">
                                        <div class="form-group input-group-sm"> 
                                            <label class="control-label" for="idTipoEquipoSearch">Observación: *</label>                             
                                            <textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" required ></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group"><br>
                                            <button type="button" id="btnEstado" name="btnEstado" class="btn btn-primary" >
                                                Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" />
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </form>
<?php          
    if($userRol == ID_ROL_SUPER_ADMIN && 1==2){
?>
                    <form  name="formEvidencias" id="formEvidencias" method="post" action="<?php echo base_url("resumen/save_evidencias"); ?>">
                        <div class="panel panel-default">
                            <div class="panel-footer">
                                <div class="row">

                                    <div class="col-lg-2">
                                        <div class="form-group input-group-sm"> 
                                            <label class="control-label" for="idTipoEquipoSearch">Trimestre: *</label>                             
                                            <select name="trimestre" Iid="trimestre" class="form-control" required >
                                                <option value="">Seleccione...</option>
                                                <option value=1 >Trimestre I</option>
                                                <option value=2 >Trimestre II</option>
                                                <option value=3 >Trimestre III</option>
                                                <option value=4 >Trimestre IV</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group"><br>
                                            <button type="submit" id="btnEvidencia" name="btnEvidencia" class="btn btn-primary" >
                                                Guardar Evidencias <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" />
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </form>
<?php
    }
?>
                </div>
            </div>
        </div> 
    </div>
<!--FIN ADDITIONAL INFORMATION -->
<?php
    }
?>
    <div class="row">
        <div class="col-lg-12"> 
             <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> No. Actividades: <b><?php echo $nroActividades; ?></b>
                </div>
                <div class="panel-body small">

                    <div class="row">
                        <div class="col-lg-12">
                            <form name="formCheckin" id="formCheckin" method="get">
                                <div class="panel panel-default">
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group input-group-sm"> 
                                                    <label class="control-label" for="numero_objetivo">No. Objetivo Estratégico:</label>             
                                                    <select name="numero_objetivo" id="numero_objetivo" class="form-control" >
                                                        <option value="">Todas...</option>
                                                        <?php for ($i = 0; $i < count($listaNumeroObjetivoEstrategicos); $i++) { ?>
                                                            <option value="<?php echo $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]; ?>" <?php if($_GET && $_GET["numero_objetivo"] == $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]) { echo "selected"; }  ?>><?php echo $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group input-group-sm"> 
                                                    <label class="control-label" for="numero_proyecto">No. Proyecto Inversión:</label>             
                                                    <select name="numero_proyecto" id="numero_proyecto" class="form-control" >
                                                        <option value="">Todas...</option>
                                                        <?php 
                                                        if($listaProyectos){
                                                            for ($i = 0; $i < count($listaProyectos); $i++) { ?>
                                                                <option value="<?php echo $listaProyectos[$i]["numero_proyecto"]; ?>" <?php if($_GET && $_GET["numero_proyecto"] == $listaProyectos[$i]["numero_proyecto"]) { echo "selected"; }  ?>><?php echo $listaProyectos[$i]["numero_proyecto"]; ?></option>
                                                        <?php 
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group input-group-sm"> 
                                                    <label class="control-label" for="id_dependencia">Dependencia:</label>                        
                                                    <select name="id_dependencia" id="id_dependencia" class="form-control" >
                                                        <option value="">Todas...</option>
                                                        <?php
                                                        if($listaNumeroDependencia){
                                                            for ($i = 0; $i < count($listaNumeroDependencia); $i++) { ?>
                                                                <option value="<?php echo $listaNumeroDependencia[$i]["id_dependencia"]; ?>" <?php if($_GET && $_GET["id_dependencia"] == $listaNumeroDependencia[$i]["id_dependencia"]) { echo "selected"; }  ?>><?php echo $listaNumeroDependencia[$i]["dependencia"]; ?></option>
                                                        <?php 
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group input-group-sm"> 
                                                    <label class="control-label" for="numero_actividad">No. Actividad:</label>                        
                                                    <select name="numero_actividad" id="numero_actividad" class="form-control" >
                                                        <option value="">Todas...</option>
                                                        <?php 
                                                        if($listaTodasActividades){
                                                            for ($i = 0; $i < count($listaTodasActividades); $i++) { ?>
                                                                <option value="<?php echo $listaTodasActividades[$i]["numero_actividad"]; ?>" <?php if($_GET && $_GET["numero_actividad"] == $listaTodasActividades[$i]["numero_actividad"]) { echo "selected"; }  ?>><?php echo $listaTodasActividades[$i]["numero_actividad"]; ?></option>
                                                        <?php 
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group"><br>
                                                    <button type="submit" id="btnSearch" name="btnSearch" class="btn btn-primary btn-sm" >
                                                        Buscar <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                    </button> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-hover">
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
                                        Evaluación OCI I <span class="fa fa-pencil" aria-hidden="true"></span>
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
                                            if($lista["publicar_calificacion_1"] == 1 && $lista["calificacion_semestre_1"]){
                                                echo "<b>Primer Semestre:</b><br>". $lista["calificacion_semestre_1"] . "<br>";
                                            }
                                            if($lista["publicar_calificacion_2"] == 1 && $lista["calificacion_semestre_2"]){
                                                echo "<b>Segundo Semestre:</b><br>" . $lista["calificacion_semestre_2"];
                                            }
                                        ?>
                                    </small></td>
                                    <td><small>
                                        <?php 
                                            if($lista["publicar_calificacion_1"] == 1 && $lista["observacion_semestre_1"]){
                                                echo "<b>Primer Semestre:</b><br>". $lista["observacion_semestre_1"] . "<br>";
                                            }
                                            if($lista["publicar_calificacion_2"] == 1 && $lista["observacion_semestre_2"]){
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