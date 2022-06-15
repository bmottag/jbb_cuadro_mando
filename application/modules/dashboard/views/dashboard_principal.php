<div id="page-wrapper">
    <div class="row"><br>
        <div class="col-md-12">
            <p class="text-primary">
                <strong>Bienvenido(a) </strong><?php echo $this->session->firstname; ?></br>
                <?php 
                    $userRol = $this->session->userdata("role");
                    if($userRol != ID_ROL_PLANEACION){
                ?>
                <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?>
                <?php 
                    }
                ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Avance Dependencias <b><?php echo date("Y"); ?></b>
                </div>
                <div class="panel-body small">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Dependencia</th>
                                <th>Avance Plan Estratégico <b><?php echo date("Y"); ?></b></th>
                                <th>No. Actividades</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($listaDependencia as $lista):
                            $arrParam = array(
                                "idDependencia" => $lista["id_dependencia"],
                                "vigencia" => date("Y")
                            );
                            $nroActividades = $this->dashboard_model->countActividades($arrParam);
                            $avance = $this->dashboard_model->sumAvance($arrParam);
                            $avancePOA = number_format($avance["avance_poa"],2);
             
                            if(!$avancePOA){
                                $avancePOA = 0;
                                $estilos = "bg-warning";
                            }else{
                                if($avancePOA > 70){
                                    $estilos = "progress-bar-success";
                                }elseif($avancePOA > 40 && $avancePOA <= 70){
                                    $estilos = "progress-bar-warning";
                                }else{
                                    $estilos = "progress-bar-danger";
                                }
                            }
                            echo "<tr>";
                            echo "<td style='width: 40%'><small>";
                            if($userRol == ID_ROL_PLANEACION){
                                echo "<a class='btn btn-info btn-xs' href='" . base_url('dashboard/dependencias/' . $lista["id_dependencia"]) . "' >" . $lista["dependencia"] . "</a>";
                            }else{
                                echo $lista["dependencia"];
                            }
                            echo "</small></td>";
                            echo "<td class='text-center'>";
                            echo "<b>" . $avancePOA ."%</b>";
                            echo '<div class="progress progress-striped">
                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $avancePOA .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $avancePOA . '%</div>
                                    </div>';
                            echo "</td>";
                            echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
                            echo "</tr>";
                        endforeach
                        ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Avance Estrategias <b><?php echo date("Y"); ?></b>
                </div>
                <div class="panel-body small">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estrategia</th>
                                <th>% Avance Vigencia <b><?php echo date("Y"); ?></b></th>
                                <th>No. Actividades</th>
                            </tr>
                        </thead>
                        <?php
                        $i=0;
                        foreach ($listaObjetivos as $lista):
                            $arrParam = array(
                                "idObjetivo" => $lista["id_objetivo_estrategico"],
                                "vigencia" => date("Y")
                            );
                            $nroActividades = $this->dashboard_model->countActividades($arrParam);
                            $avance = $this->dashboard_model->sumAvance($arrParam);
                            $promedio = 0;
                            if($nroActividades){
                                $promedio = number_format($avance["avance_poa"]/$nroActividades,2);
                            }
                                         
                            if(!$promedio){
                                $promedio = 0;
                                $estilos = "bg-warning";
                            }else{
                                if($promedio > 70){
                                    $estilos = "progress-bar-success";
                                }elseif($promedio > 40 && $promedio <= 70){
                                    $estilos = "progress-bar-warning";
                                }else{
                                    $estilos = "progress-bar-danger";
                                }
                            }

                            echo "<tr>";
                            echo "<td style='width: 50%'><small>" . $lista["objetivo_estrategico"] . "</small></td>";
                            echo "<td class='text-center'>";
                            echo "<b>" . $promedio ."%</b>";
                            echo '<div class="progress progress-striped">
                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedio .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedio . '%</div>
                                    </div>';
                            echo "</td>";
                            echo "<td class='text-center'><small>" . $nroActividades . "</small></td>";
                            echo "</tr>";
                        endforeach
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php 
                        if($userRol == ID_ROL_PLANEACION){
                    ?>
                            <i class="fa fa-thumb-tack fa-fw"></i> <b>PLAN ESTRATÉGICO - <?php echo date("Y"); ?></b>
                    <?php
                        }else{
                    ?>
                            <i class="fa fa-thumb-tack fa-fw"></i> <b>ACTIVIDADES A CARGO</b>
                            <br><br>
                            <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?></br>
                            <strong>No. Actividades: </strong><?php echo $nroActividadesDependencia; ?></br>
                            <strong>Avance Dependencia: </strong><?php echo number_format($avance["avance_poa"],2); ?>
                    <?php
                        }
                    ?>
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
                            $arrParam2 = array();
                            if($userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENLACE){
                                $arrParam2 = array(
                                    "idDependencia" => $infoDependencia[0]['id_dependencia']
                                );  
                            }
                            $listaTodasActividades = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam2);
                    ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <form name="formCheckin" id="formCheckin" method="post">
                                    <div class="panel panel-default">
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="form-group input-group-sm"> 
                                                        <label class="control-label" for="numero_actividad">No. Actividad: *</label>                             
                                                        <select name="numero_actividad" id="numero_actividad" class="form-control" required >
                                                            <option value="">Seleccione...</option>
                                                            <?php for ($i = 0; $i < count($listaTodasActividades); $i++) { ?>
                                                                <option value="<?php echo $listaTodasActividades[$i]["numero_actividad"]; ?>" <?php if($_POST && $_POST["idTipoEquipoSearch"] == $listaTodasActividades[$i]["numero_actividad"]) { echo "selected"; }  ?>><?php echo $listaTodasActividades[$i]["numero_actividad"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
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
                    <?php
                        foreach ($listaEstrategias as $infoEstrategia):

                            if($userRol == ID_ROL_PLANEACION){
                                $arrParam = array(
                                    "numeroEstrategia" => $infoEstrategia["numero_estrategia"]
                                );
                            }else{
                                 $arrParam = array(
                                    "numeroEstrategia" => $infoEstrategia["numero_estrategia"],
                                    "idDependencia" => $infoDependencia[0]['id_dependencia']
                                ); 
                            }
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
                                                        <th><small>Dependencia</small></th>
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
                                                    echo "<td><small>";
                                                    if($dependencias){
                                           
                                                        foreach ($dependencias as $datos):
                                                            echo "<li class='text-primary'>" . $datos["dependencia"] . "</li>";
                                                        endforeach;
                                         
                                                    echo "</small></td>";                                                      
                                                    }


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