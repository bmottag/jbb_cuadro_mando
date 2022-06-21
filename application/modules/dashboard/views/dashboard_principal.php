<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dashboard/ajaxSearch.js"); ?>"></script>

<div id="page-wrapper">
    <div class="row"><br>
        <div class="col-md-12">
            <p class="text-primary">
                <strong>Bienvenido(a) </strong><?php echo $this->session->firstname; ?></br>
                <?php 
                    $userRol = $this->session->userdata("role");
                    if($userRol == ID_ROL_ENLACE ||  $userRol == ID_ROL_SUPERVISOR){
                ?>
                        <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?>
                <?php 
                    }
                ?>
            </p>
        </div>
    </div>

    <?php if(!$_POST){ ?>
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
                            if($userRol == ID_ROL_PLANEACION || $userRol == ID_ROL_ADMINISTRADOR || $userRol == ID_ROL_SUPER_ADMIN){
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
                        foreach ($listaEstretegias as $lista):
                            $arrParam = array(
                                "idEstrategia" => $lista["id_estrategia"],
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
                            echo "<td style='width: 50%'><small>" . $lista["estrategia"] . "</small></td>";
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
    <?php } ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php 
                        if($userRol == ID_ROL_PLANEACION || $userRol == ID_ROL_ADMINISTRADOR || $userRol == ID_ROL_SUPER_ADMIN){
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
                    if(!$listaObjetivosEstrategicos){ 
                        echo '<div class="row">';
                        echo '<div class="col-lg-12">
                                <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No le han asignado actividades.</p>
                            </div>';
                        echo '</div>';
                    }else{
                            $arrParam2 = array();
                            if($_POST && $_POST["numero_objetivo"] != ""){
                                $arrParam2["numeroEstrategia"] = $_POST["numero_objetivo"];
                            }
                            if($_POST && $_POST["numero_proyecto"] != ""){
                                $arrParam2["numeroProyecto"] = $_POST["numero_proyecto"];
                            }
                            if($_POST && $_POST["id_dependencia"] != ""){
                                $arrParam2["idDependencia"] = $_POST["id_dependencia"];
                            }elseif($userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENLACE){
                                $arrParam2["idDependencia"] = $infoDependencia[0]['id_dependencia'];  
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
                                                        <label class="control-label" for="numero_objetivo">No. Objetivo Estratégico:</label>             
                                                        <select name="numero_objetivo" id="numero_objetivo" class="form-control" >
                                                            <option value="">Todas...</option>
                                                            <?php for ($i = 0; $i < count($listaNumeroObjetivoEstrategicos); $i++) { ?>
                                                                <option value="<?php echo $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]; ?>" <?php if($_POST && $_POST["numero_objetivo"] == $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]) { echo "selected"; }  ?>><?php echo $listaNumeroObjetivoEstrategicos[$i]["numero_objetivo_estrategico"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group input-group-sm"> 
                                                        <label class="control-label" for="numero_proyecto">No. Proyecto Inversión:</label>             
                                                        <select name="numero_proyecto" id="numero_proyecto" class="form-control" >
                                                            <option value="">Todas...</option>
                                                            <?php 
                                                            if($listaProyectos){
                                                                for ($i = 0; $i < count($listaProyectos); $i++) { ?>
                                                                    <option value="<?php echo $listaProyectos[$i]["numero_proyecto"]; ?>" <?php if($_POST && $_POST["numero_proyecto"] == $listaProyectos[$i]["numero_proyecto"]) { echo "selected"; }  ?>><?php echo $listaProyectos[$i]["numero_proyecto"]; ?></option>
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
                                                                    <option value="<?php echo $listaNumeroDependencia[$i]["id_dependencia"]; ?>" <?php if($_POST && $_POST["id_dependencia"] == $listaNumeroDependencia[$i]["id_dependencia"]) { echo "selected"; }  ?>><?php echo $listaNumeroDependencia[$i]["dependencia"]; ?></option>
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
                                                            if($listaNumeroDependencia){
                                                                for ($i = 0; $i < count($listaTodasActividades); $i++) { ?>
                                                                    <option value="<?php echo $listaTodasActividades[$i]["numero_actividad"]; ?>" <?php if($_POST && $_POST["numero_actividad"] == $listaTodasActividades[$i]["numero_actividad"]) { echo "selected"; }  ?>><?php echo $listaTodasActividades[$i]["numero_actividad"]; ?></option>
                                                            <?php 
                                                                } 
                                                            }
                                                            ?>
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
                        foreach ($listaObjetivosEstrategicos as $infoObjetivoEstrategico):

                            $numeroObjetivoEstrategico = $infoObjetivoEstrategico['numero_objetivo_estrategico'];
                            $arrParam = array('numeroObjetivoEstrategico' => $numeroObjetivoEstrategico);
                            $metas = $this->general_model->get_lista_metas($arrParam);
                            $indicadores = $this->general_model->get_lista_indicadores($arrParam);
                            $resultados = $this->general_model->get_lista_resultados($arrParam);

                            $arrParam = array(
                                "numeroObjetivoEstrategico" => $infoObjetivoEstrategico["numero_objetivo_estrategico"]
                            );
                            if($userRol == ID_ROL_ENLACE ||  $userRol == ID_ROL_SUPERVISOR){
                                 $arrParam = array(
                                    "numeroEstrategia" => $infoObjetivoEstrategico["numero_objetivo_estrategico"],
                                    "idDependencia" => $infoDependencia[0]['id_dependencia']
                                ); 
                            }
                            if($_POST){
                                if($_POST && $_POST["numero_actividad"] != ""){
                                    $arrParam["numeroActividad"] = $this->input->post('numero_actividad');
                                }
                                if($_POST && $_POST["numero_proyecto"] != ""){
                                    $arrParam["numeroProyecto"] = $this->input->post('numero_proyecto');
                                }
                                if($_POST && $_POST["id_dependencia"] != ""){
                                    $arrParam["idDependencia"] = $this->input->post('id_dependencia');
                                }
                            }
                            $listaActividades = $this->general_model->get_actividades_full_by_dependencia($arrParam);

                            echo '<div class="row">';
                    ?>

                            <div class="col-lg-12">
                                <?php
                                    if($listaActividades){
                                ?>

                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>Estrategia: </strong><?php echo $infoObjetivoEstrategico['estrategia']; ?></br>
                                        <strong>Objetivo Estratégico: </strong><?php echo $infoObjetivoEstrategico['numero_objetivo_estrategico'] . ' ' . $infoObjetivoEstrategico['objetivo_estrategico']; ?>
                                    </div>
                                    <div class="panel-body small">
                                    <?php
                                        if($metas){
                                    ?>
                                            <div class="col-lg-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <i class="fa fa-signal"></i> <strong><small>Meta</small></strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <ul>
                                                        <?php
                                                        foreach ($metas as $lista):
                                                            echo "<li><small>" . $lista["meta"] . "</small></li>";
                                                        endforeach
                                                        ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                    }
                                        if($indicadores){
                                    ?>
                                            <div class="col-lg-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <i class="fa fa-tasks"></i> <strong><small>Indicador</small></strong>
                                                    </div>
                                                    <div class="panel-body">
                                                    <?php
                                                    foreach ($indicadores as $lista):
                                                        echo "<small>" . $lista["indicador"] . "</small><br>";
                                                    endforeach
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                    }
                                        if($resultados){
                                    ?>
                                            <div class="col-lg-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <i class="fa fa-check"></i> <strong><small>Resultado</small></strong>
                                                    </div>
                                                    <div class="panel-body">
                                                    <?php
                                                    foreach ($resultados as $lista):
                                                        echo "<small>" . $lista["resultado"] . "</small><br>";
                                                    endforeach
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
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
                                                echo "<tr>";
                                                echo "<td class='text-center'>";
                                                echo "<a class='btn btn-primary btn-xs' title='Ver Detalle Actividad No. " . $lista["numero_actividad"] . "' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'>". $lista['numero_actividad'] . " <span class='fa fa-eye' aria-hidden='true'></span></a>";
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
                                                echo "<td><small class='text-primary'>" . $lista["dependencia"] . "</small></td>";
                                                echo "</tr>";

                                                if($lista['estado_trimestre_1'] == 6 || $lista['estado_trimestre_2'] == 6  || $lista['estado_trimestre_3'] == 6 || $lista['estado_trimestre_4'] == 6 ){
                                                    echo "<tr class='text-danger danger'>";
                                                    echo "<td colspan='12'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
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
                                                    echo "<td colspan='12'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
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
                                                    echo "<td colspan='12'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
                                                    echo "Actividad Aprobada por el Supervidor.";
                                                    echo "</b></small></td>";
                                                    echo "</tr>";
                                                }

                                                if($lista['estado_trimestre_1'] == 2 || $lista['estado_trimestre_2'] == 2  || $lista['estado_trimestre_3'] == 2 || $lista['estado_trimestre_4'] == 2 ){
                                                    echo "<tr class='text-warning warning'>";
                                                    echo "<td colspan='12'><small><b><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ";
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
                                                echo "<td colspan='12'>";
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
                                    </div>
                                </div>
                                <?php 
                                    }
                                ?>
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