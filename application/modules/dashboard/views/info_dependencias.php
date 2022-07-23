<script type="text/javascript" src="<?php echo base_url("assets/js/validate/resumen/form_estado_actividad.js"); ?>"></script>

<div id="page-wrapper">
    <br>
    <h2><strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?></h2>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i>
                    <strong>No. Actividades: </strong><?php echo $nroActividadesDependencia; ?></br>
                    <strong>Avance Dependencia: </strong><?php echo number_format($avance["avance_poa"],2); ?>
                </div>
                <div class="panel-body small">

                    <?php 
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
                    echo "<b>Avance: " . $avancePOA ."%</b>";
                    echo '<div class="progress progress-striped">
                              <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $avancePOA .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $avancePOA . '%</div>
                            </div>';
                    ?>

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

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-crosshairs"></i>
                    <strong>Objetivos Estratégicos </strong>
                </div>
                <div class="panel-body small">
                <?php
                    if($listaObjetivosEstrategicos){
                ?>              

                    <table width="100%" class="table table-hover">
                        <thead>
                            <tr>
                                <th width='45%'>Objetivo Estratégico</th>
                                <th width='10%' class="text-center">No. Actividades</th>
                                <th width='45%' class="text-center">Promedio Cumplimiento</th>
                            </tr>
                        </thead>
                        <tbody>                         
                        <?php
                            foreach ($listaObjetivosEstrategicos as $lista):
                                $arrParam = array(
                                    "idDependencia" => $infoDependencia[0]['id_dependencia'],
                                    "numeroObjetivoEstrategico" => $lista["numero_objetivo_estrategico"],
                                    "vigencia" => date("Y")
                                );
                                $nroActividades = $this->general_model->countActividades($arrParam);
                                $cumplimiento = $this->general_model->sumCumplimiento($arrParam);
                                $promedioCumplimiento = 0;
                                if($nroActividades){
                                    $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,2);
                                }
                                             
                                if(!$promedioCumplimiento){
                                    $promedioCumplimiento = 0;
                                    $estilos = "bg-warning";
                                }else{
                                    if($promedioCumplimiento > 70){
                                        $estilos = "progress-bar-success";
                                    }elseif($promedioCumplimiento > 40 && $promedioCumplimiento <= 70){
                                        $estilos = "progress-bar-warning";
                                    }else{
                                        $estilos = "progress-bar-danger";
                                    }
                                }
                                echo "<tr>";
                                echo "<td>" . $lista['numero_objetivo_estrategico'] . ' ' . $lista['objetivo_estrategico'] .  "</td>";
                                echo "<td class='text-center'>" . $nroActividades . "</td>";
                                echo "<td class='text-center'>";
                                echo "<b>" . $promedioCumplimiento ."%</b>";
                                echo '<div class="progress progress-striped">
                                          <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $promedioCumplimiento .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $promedioCumplimiento . '%</div>
                                        </div>';
                                echo "</td>";
                                echo "</tr>";
                            endforeach;
                        ?>
                        </tbody>
                    </table>
                <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>