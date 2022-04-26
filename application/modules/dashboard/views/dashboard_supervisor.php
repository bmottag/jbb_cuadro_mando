<div id="page-wrapper">
    <div class="row"><br>
		<div class="col-md-12">
            <p class="text-primary"><strong>Bienvenido(a): </strong><?php echo $this->session->firstname; ?></p>
		</div>
		<!-- /.col-lg-12 -->
    </div>
								
<?php
$retornoExito = $this->session->flashdata('retornoExito');
if ($retornoExito) {
    ?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-success ">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				<strong><?php echo $this->session->userdata("firstname"); ?></strong> <?php echo $retornoExito ?>		
			</div>
		</div>
	</div>
    <?php
}

$retornoError = $this->session->flashdata('retornoError');
if ($retornoError) {
    ?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-danger ">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<?php echo $retornoError ?>
			</div>
		</div>
	</div>
    <?php
}
?> 
			
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-thumb-tack fa-fw"></i> <b>Plan Estratégico</b>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="panel-group" id="accordion">
                    <?php         
                    if(!$listaEstrategias){ 
                        echo '<div class="col-lg-12">
                                <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No le han asignado actividades.</p>
                            </div>';
                    }else{
                        foreach ($listaEstrategias as $lista):
                    ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <small>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $lista['id_estrategia']; ?>">
                                    <?php 
                                        echo '<strong>Objetivo Estratégico: </strong>' . $lista['objetivo_estrategico'] . ' ' . $lista['estrategia'];
                                        echo '<br><strong>Estrategia: </strong>' . $lista['numero_estrategia'] . ' ' . $lista['estrategia']; 
                                    ?>
                                    </a>
                                    </small>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $lista['id_estrategia']; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php 
                                        $idEstrategia = $lista['id_estrategia'];
                                        $arrParam['idEstrategia'] = $idEstrategia;
                                        $metas = $this->general_model->get_lista_metas($arrParam);
                                        $indicadores = $this->general_model->get_lista_indicadores($arrParam);
                                        $resultados = $this->general_model->get_lista_resultados($arrParam);
                                        //consulto los ID de cuadro base para los que es responsable
                                        $filtroCuadroBase = $this->general_model->get_cuadro_base_by_responsable($arrParam);
                                        $valor = '';
                                        if($filtroCuadroBase){
                                            $tot = count($filtroCuadroBase);
                                            for ($i = 0; $i < $tot; $i++) {
                                                $valor = $valor . $filtroCuadroBase[$i]['fk_id_cuadro_base'];
                                                if($i != ($tot-1)){
                                                    $valor .= ",";
                                                }
                                            }
                                        }
                                        $arrParam = array("filtroCuadroBase" => $valor);
                                        $cuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

                                        if($metas){
                                    ?>
                                            <div class="col-lg-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <i class="fa fa-signal"></i> <strong><small>Meta</small></strong>
                                                    </div>
                                                    <div class="panel-body">
                                                    <?php
                                                    foreach ($metas as $lista):
                                                        echo "<small>" . $lista["meta"] . "</small><br>";
                                                    endforeach
                                                    ?>
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

                                        if(!$cuadroBase){
                                            echo "<small>No hay definidas las relaciones para esta estretegia.</small>";
                                        }else{
                                    ?>                              
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><small>Proyecto Inversión</small></th>
                                                    <th><small>Meta Proyecto Inversión</small></th>
                                                    <th><small>Propósito</small></th>
                                                    <th><small>Logro</small></th>
                                                    <th><small>Programa Estretégico</small></th>
                                                    <th><small>Meta PDD</small></th>
                                                    <th><small>ODS</small></th>
                                                    <th><small>Responsable</small></th>
                                                    <th><small>Ver Actividades</small></th>

                                                </tr>
                                            </thead>

                                            <?php
                                            foreach ($cuadroBase as $lista):
                                                echo "<tr>";
                                                echo "<td><small>" . $lista["proyecto_inversion"] . "</small></td>";
                                                echo "<td><small>" . $lista["meta_proyecto"] . "</small></td>";
                                                echo "<td><small>" . $lista["proposito"] . "</small></td>";
                                                echo "<td><small>" . $lista["logro"] . "</small></td>";
                                                echo "<td><small>" . $lista["programa"] . "</small></td>";
                                                echo "<td><small>" . $lista["meta_pdd"] . "</small></td>";
                                                echo "<td><small>" . $lista["ods"] . "</small></td>";
                                                echo "<td><small>" . $lista["dependencia"] . "</small></td>";
                                                echo "<td><a class='btn btn-success btn-xs' href='" . base_url('dashboard/actividades/' . $lista["id_cuadro_base"]) . "'> Actividades <span class='glyphicon glyphicon-edit' aria-hidden='true'></a></td>";
                                                echo "</tr>";
                                            endforeach
                                            ?>
                                        </table>
                                    <?php } ?>
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
        </div>
    </div>
</div>