<div id="page-wrapper">
    <div class="row"><br>
        <div class="col-md-12">
            <p class="text-primary">
                <strong>Bienvenido(a) </strong><?php echo $this->session->firstname; ?></br>
                <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?>
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
                            echo $lista["dependencia"];
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
                    <h3>ACTIVIDADES A CARGO</h3>
                    <h5>
                        <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?></br>
                        <strong>No. Actividades: </strong><?php echo $nroActividadesDependencia; ?></br>
                        <strong>Avance Dependencia: </strong><?php echo number_format($avance["avance_poa"],2); ?>
                    </h5>
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