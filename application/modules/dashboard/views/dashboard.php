<div id="page-wrapper">
    <div class="row"><br>
		<div class="col-md-12">
            <p class="text-primary"><strong>Bienvenido(a): </strong><?php echo $this->session->firstname; ?></p>
		</div>
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
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    MISIÓN
                </div>
                <div class="panel-body">
                    <p>Investigar y conservar la flora de los ecosistemas alto andinos y de páramo y gestionar las coberturas vegetales urbanas, contribuyendo a la generación, aplicación y apropiación social del conocimiento para la adaptación al cambio climático, al mejoramiento de la calidad de vida y al desarrollo sostenible en el Distrito Capital y la Región.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VISIÓN
                </div>
                <div class="panel-body">
                    <p>En el 2038 seremos reconocidos nacional e internacionalmente como un centro de investigación de referencia en los ecosistemas alto andinos y de páramo y como destino de naturaleza, que contribuye a la transformación del pensamiento ambiental para la sostenibilidad del territorio.
                    <br>
                    </p>
                </div>
            </div>
        </div>        
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
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
                            $nroActividades = $this->general_model->countActividades($arrParam);
                            $avance = $this->general_model->sumAvance($arrParam);
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
                            echo "<a class='btn btn-info btn-xs' href='" . base_url('dashboard/dependencias/' . $lista["id_dependencia"]) . "' >" . $lista["dependencia"] . "</a>";
                            echo "</small></td>";
                            echo "<td class='text-center'>";
                            echo "<b>" . $avancePOA ."%</b>";
                            echo '<div class="progress progress-striped">
                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. $avancePOA .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . $avancePOA . '%</div>
                                    </div>';

?>
<!--
<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" style="width:40%">
    Free Space
  </div>
  <div class="progress-bar progress-bar-warning" role="progressbar" style="width:10%">
    Warning
  </div>
  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:20%">
    Danger
  </div>
</div>


<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
  aria-valuemin="0" aria-valuemax="100" style="width:40%">
    40% Complete (success)
  </div>
</div>

<div class="progress">
  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50"
  aria-valuemin="0" aria-valuemax="100" style="width:50%">
    50% Complete (info)
  </div>
</div>

<div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
  aria-valuemin="0" aria-valuemax="100" style="width:60%">
    60% Complete (warning)
  </div>
</div>

<div class="progress">
  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:70%">
    70% Complete (danger)
  </div>
</div>
-->
<?php
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
            <div class="panel panel-default">
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
                        foreach ($listaObjetivosEstrategicos as $lista):
                            $arrParam = array(
                                "idObjetivo" => $lista["id_objetivo_estrategico"],
                                "vigencia" => date("Y")
                            );
                            $nroActividades = $this->general_model->countActividades($arrParam);
                            $avance = $this->general_model->sumAvance($arrParam);
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
<!--
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Avances de Objetivo Estratégico años anteriores
                </div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">



        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Resultados de la Administración</b>
                </div>
                <div class="panel-body small">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Administración</th>
                                <th>Avance Plan Estratégico <b><?php echo date("Y"); ?></b></th>
                            </tr>
                        </thead>
                        <?php
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Centro de investigación reconocido</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">80%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Consolidación de alianzas estratégicas para la conservación de las coberturas vegetales</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 90%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="90">50%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Cultura organizacional</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 70%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">70%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Enriquecimiento de colecciones vivas  y de referencia uso sostenible de flora en la ciudad región</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">100%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Fortalecimiento de la gestión y el desempeño institucional</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">50%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Gestión de coberturas vegetales</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">100%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Centro de investigación reconocido</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">100%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td style='width: 50%'><small>Gestión del conocimiento trasformación del pensamiento</small></td>";
                            echo "<td>";
                            echo '<div class="progress">
                                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">80%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                        ?>
                    </table>
                </div>
            </div>
        </div>
-->


    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-thumb-tack fa-fw"></i> <b>Plan Estratégico</b>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="panel-group" id="accordion">
                    <?php 
                        foreach ($listaEstrategias as $lista):
                    ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <small>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $lista['id_estrategia']; ?>">
                                    <?php 
                                        echo '<strong>Estrategia: </strong><br>' . $lista['estrategia'] . ' ' . $lista['descripcion_estrategia'];
                                        echo '<br><br><strong>Objetivo Estratégico: </strong><br>' . $lista['numero_objetivo_estrategico'] . ' ' . $lista['objetivo_estrategico']; 
                                    ?>
                                    </a>
                                    </small>

                                </h4>
                            </div>
                            <div id="collapse<?php echo $lista['id_estrategia']; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php 
                                        $idEstrategia = $lista['id_estrategia'];
                                        $arrParam = array('idEstrategia' => $idEstrategia);
                                        $metas = $this->general_model->get_lista_metas($arrParam);
                                        $indicadores = $this->general_model->get_lista_indicadores($arrParam);
                                        $resultados = $this->general_model->get_lista_resultados($arrParam);

                                        $arrParam = array('numeroEstrategia' => $lista['numero_objetivo_estrategico']);
                                        $actividades = $this->general_model->get_actividades_full($arrParam);

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

                                        if(!$actividades){
                                            echo "<small>No hay definidas las relaciones para esta estretegia.</small>";
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
                                            foreach ($actividades as $lista):

                                                //buscar las dependencias relacionadas
                                                $arrParam = array('idCuadroBase' => $lista['fk_id_cuadro_base']);
                                                $dependencias = $this->general_model->get_dependencias($arrParam);

                                                echo "<tr>";
                                                echo "<td class='text-center'><small>" . $lista['numero_actividad'] . "</small>";
                                                echo "<a class='btn btn-primary btn-xs' href='" . base_url('dashboard/actividades/' . $lista["fk_id_cuadro_base"] .  '/' . $lista["numero_actividad"]) . "'> <span class='fa fa-eye' aria-hidden='true'></a>";
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
                                                $x=0;
                                                foreach ($dependencias as $datos):
                                                    $x++;
                                                    echo "<p class='text-primary'>" . $x . " " . $datos["dependencia"] . "</p>";
                                                endforeach;
                                                echo "</small></td>";
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
                    ?>
                    </div>          
                </div>
            </div>
        </div>
    </div>
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="tablaDatos">

        </div>
    </div>
</div>                       
<!--FIN Modal  -->