<div id="page-wrapper">
    <div class="row">
		<div class="col-md-12">
            <h2 class="page-header">
                Listado de Actividades por Dependencia
                <br><br>
                <small>
                    <p class="text-default">
                        <strong>Dependencia: </strong><?php echo $infoDependencia[0]['dependencia']; ?></br>
                        <strong>No. Actividades: </strong><?php echo $nroActividades; ?></br>
                        <strong>Avance: </strong><?php echo number_format($avance["avance_poa"],2); ?>
                    </p>
                </small>
            </h2>
		</div>
    </div>
								
    
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
                        if($listaActividades){
                            foreach ($listaActividades as $lista):
                    ?>
                                <div class="col-lg-6">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <i class="fa fa-thumb-tack"></i> <strong>ACTIVIDAD No.  <?php echo $lista["numero_actividad"]; ?></strong>
                                        </div>
                                        <div class="panel-body">
                                            <strong>Actividad: </strong><br><?php echo $lista["descripcion_actividad"]; ?><br>
                                            <strong>Meta Plan Operativo Anual: </strong><br><?php echo $lista["meta_plan_operativo_anual"]; ?><br>
                                            <strong>Avance: </strong><br>
                                            <?php 
                                                if($lista["avance_poa"]){
                                                    echo $lista["avance_poa"] . "%";
                                                }else{
                                                    echo 0;
                                                }
                                            ?><br>
                                            <strong>Meta Proyecto Inversión: </strong><br>
                                            <?php echo $lista["meta_proyecto"] . "<br><b>Vigencia: " . $lista["vigencia_meta_proyecto"] . "</b>";  ?><br>
                                            <strong>Proyecto Inversión: </strong><br><?php echo $lista["proyecto_inversion"]; ?><br>
                                            <strong>Meta PDD: </strong><br><?php echo $lista["meta_pdd"]; ?><br>
                                            <strong>Programa Estratégico: </strong><br><?php echo $lista["programa"]; ?><br>
                                            <strong>Logro: </strong><br><?php echo $lista["logro"]; ?><br>
                                            <strong>Propósito: </strong><br><?php echo $lista["proposito"]; ?><br>
                                            <strong>ODS: </strong><br><?php echo $lista["ods"]; ?>
                                        </div>
                                    </div>
                                </div>
                    <?php 
                            endforeach;
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

