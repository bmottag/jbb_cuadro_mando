<div id="page-wrapper">
<?php 
    $userRol = $this->session->userdata("role");
?>
    <div class="row"><br>
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-thumb-tack fa-fw"></i> <b>PLAN ESTRATÉGICO - <?php echo date("Y"); ?></b>
                    <br><br>
                    <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?></br>
                    <strong>No. Actividades: </strong><?php echo $nroActividadesDependencia; ?></br>
                    <strong>Avance Dependencia: </strong><?php echo number_format($avance["avance_poa"],2); ?>
                </div>
                <div class="panel-body">
                    <?php         
                    if(!$listaEstrategias){ 
                        echo '<div class="row">';
                        echo '<div class="col-lg-12">
                                <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No le han asignado actividades.</p>
                            </div>';
                        echo '</div>';
                    }else{
                        foreach ($listaEstrategias as $infoEstrategia):
                             $arrParam = array(
                                "numeroEstrategia" => $infoEstrategia["numero_estrategia"],
                                "idDependencia" => $infoDependencia[0]['id_dependencia']
                            );                               
                            $listaActividades = $this->general_model->get_actividades_full_by_dependencia($arrParam);

                            echo '<div class="row">';
                    ?>
                            <div class="col-lg-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                            <strong>Estrategia: </strong><?php echo $infoEstrategia['objetivo_estrategico']; ?></br>
                                            <strong>Objetivo Estratégico: </strong><?php echo $infoEstrategia['numero_estrategia'] . ' ' . $infoEstrategia['estrategia']; ?>
                                    </div>
                                    <div class="panel-body small">
                                    <?php
                                        if(!$listaActividades){
                                            echo "<small>No hay actividades.</small>";
                                        }else{
                                    ?>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"><small>No.</small></th>
                                                        <th><small>Actividad</small></th>
                                                        <th><small>Meta Plan Operativo Anual</small></th>
                                                        <th><small>Avance</small></th>
                                                        <th><small>Meta Proyecto Inversión</small></th>
                                                        <th><small>Proyecto Inversión</small></th>
                                                        <th><small>Meta PDD</small></th>
                                                        <th><small>Programa Estratégico</small></th>
                                                        <th><small>Logro</small></th>
                                                        <th><small>Propósito</small></th>
                                                        <th><small>ODS</small></th>
                                                    </tr>
                                                </thead>

                                                <?php
                                                foreach ($listaActividades as $lista):
                                                    //buscar las dependencias relacionadas
                                                    $arrParam = array('idCuadroBase' => $lista['fk_id_cuadro_base']);
                                                    $dependencias = $this->general_model->get_dependencias($arrParam);

                                                    echo "<tr>";
                                                    echo "<td class='text-center'><small>" . $lista['numero_actividad'] . "</small>";
                                                    echo "<a class='btn btn-primary btn-xs' title='Ver Detalle' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'> <span class='fa fa-eye' aria-hidden='true'></a>";
                                                    echo "</td>";
                                                    echo "<td><small>" . $lista['descripcion_actividad'] . "</small></td>";
                                                    echo "<td class='text-right'><small>" . $lista['meta_plan_operativo_anual'] . "</small></td>";
                                                    echo "<td class='text-center'><small>";
                                                    if($lista["avance_poa"]){
                                                        echo $lista["avance_poa"] . "%";
                                                    }else{
                                                        echo 0;
                                                    }
                                                    echo "</small></td>";
                                                    echo "<td><small>";
                                                    echo $lista["meta_proyecto"] . "<br><b>Vigencia: " . $lista["vigencia_meta_proyecto"] . "</b>";  
                                                    echo "</small></td>";
                                                    echo "<td><small>" . $lista["proyecto_inversion"] . "</small></td>";
                                                    echo "<td><small>" . $lista["meta_pdd"] . "</small></td>";
                                                    echo "<td><small>" . $lista["programa"] . "</small></td>";
                                                    echo "<td><small>" . $lista["logro"] . "</small></td>";
                                                    echo "<td><small>" . $lista["proposito"] . "</small></td>";
                                                    echo "<td><small>" . $lista["ods"] . "</small></td>";
                                                    echo "</tr>";

                                                    if($lista['estado_trimestre_1'] == 6 || $lista['estado_trimestre_2'] == 6  || $lista['estado_trimestre_3'] == 6 || $lista['estado_trimestre_4'] == 6 ){
                                                        echo "<tr class='text-danger danger'>";
                                                        echo "<td colspan='11'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
                                                            if($userRol == ID_ROL_ENLACE){
                                                                echo "Debe revisar esta actividad porque se encuentra Rechazada.";
                                                            }else{
                                                                echo "Actividad Rechazada por Planeación.";
                                                            }
                                                        echo "</b></small></td>";
                                                        echo "</tr>";
                                                    }
                                                   
                                                    if($lista['estado_trimestre_1'] == 4 || $lista['estado_trimestre_2'] == 4  || $lista['estado_trimestre_3'] == 4 || $lista['estado_trimestre_4'] == 4 ){
                                                        echo "<tr class='text-danger danger'>";
                                                        echo "<td colspan='11'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
                                                            if($userRol == ID_ROL_ENLACE){
                                                                echo "Debe revisar esta actividad porque se encuentra Rechazada.";
                                                            }else{
                                                                echo "Actividad Rechazada por el Supervisor.";
                                                            }
                                                        echo "</b></small></td>";
                                                        echo "</tr>";
                                                    }

                                                    if($lista['estado_trimestre_1'] == 3 || $lista['estado_trimestre_2'] == 3  || $lista['estado_trimestre_3'] == 3 || $lista['estado_trimestre_4'] == 3 ){
                                                        echo "<tr class='text-success success'>";
                                                        echo "<td colspan='11'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
                                                        echo "Actividad Aprobada por el Supervidor.";
                                                        echo "</b></small></td>";
                                                        echo "</tr>";
                                                    }

                                                    if($lista['estado_trimestre_1'] == 2 || $lista['estado_trimestre_2'] == 2  || $lista['estado_trimestre_3'] == 2 || $lista['estado_trimestre_4'] == 2 ){
                                                        echo "<tr class='text-warning warning'>";
                                                        echo "<td colspan='11'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
                                                            if($userRol == ID_ROL_SUPERVISOR){
                                                                echo "Debe revisar esta actividad porque se encuentra Cerrada.";
                                                            }else{
                                                                echo "Actividad Cerrada.";
                                                            }
                                                        echo "</b></small></td>";
                                                        echo "</tr>";
                                                    }

                                                    $arrParam = array("numeroActividad" => $lista["numero_actividad"]);
                                                    $estadoActividad = $this->general_model->get_estados_actividades($arrParam);
                                                    if($estadoActividad){ 
                                                    echo "<tr>";
                                                    echo "<td colspan='11'>";
                                                    echo "<p class=" . $estadoActividad[0]['primer_clase'] . "><strong>Trimestre I: " . $estadoActividad[0]['primer_estado'] . "</strong></p>";
                                                    echo "<p class=" . $estadoActividad[0]['segundo_clase'] . "><strong>Trimestre II: " . $estadoActividad[0]['segundo_estado'] . "</strong></p>";
                                                    echo "<p class=" . $estadoActividad[0]['tercer_clase'] . "><strong>Trimestre III: " . $estadoActividad[0]['tercer_estado'] . "</strong></p>";
                                                    echo "<p class=" . $estadoActividad[0]['cuarta_clase'] . "><strong>Trimestre IV: " . $estadoActividad[0]['cuarta_estado'] . "</strong></p>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                    }
                                                endforeach
                                                ?>
                                            </table>
                                    <?php 
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>            

                    <?php
                            echo '</div>';
                        endforeach;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>