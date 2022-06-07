<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/plan_estrategico.js"); ?>"></script>
<script>
$(function(){ 
    $(".btn-primary").click(function () {  
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'settings/cargarModalCuadroBase',
                data: {'numeroEstrategia': oID, 'idCuadroBase': 'x'},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
    }); 
        
    $(".btn-info").click(function () {  
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'settings/cargarModalCuadroBase',
                data: {'numeroEstrategia': '', 'idCuadroBase': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
    });
});
</script>

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
                                        echo '<strong>Estrategia: </strong><br>' . $lista['objetivo_estrategico'] . ' ' . $lista['descripcion_objetivo_estrategico'];
                                        echo '<br><br><strong>Objetivo Estratégico: </strong><br>' . $lista['numero_estrategia'] . ' ' . $lista['estrategia']; 
                                    ?>
                                    </a>
                                    </small>
<?php
    $userRol = $this->session->userdata("role");          
    if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
?>
                                    <div class="pull-right">
                                        <div class="btn-group">                                                                             
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['numero_estrategia']; ?>">
                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Plan de Desarrollo Distrital
                                            </button>
                                        </div>
                                    </div>
<?php } ?>
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

                                        $arrParam = array('numeroEstrategia' => $lista['numero_estrategia']);
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
                                                    <th><small>Meta Proyecto Inversión</small></th>
                                                    <th><small>Proyecto Inversión</small></th>
                                                    <th><small>Meta PDD</small></th>
                                                    <th><small>Programa Estratégico</small></th>
                                                    <th><small>Logro</small></th>
                                                    <th><small>Propósito</small></th>
                                                    <th><small>ODS</small></th>
                                                    <th><small>Responsable</small></th>
                                                    <th><small>Enlaces</small></th>
                                                </tr>
                                            </thead>

                                            <?php
                                            foreach ($cuadroBase as $lista):
                                                echo "<tr>";
                                                echo "<td><small>" . $lista["meta_proyecto"] . "</small></td>";
                                                echo "<td><small>" . $lista["proyecto_inversion"] . "</small></td>";
                                                echo "<td><small>" . $lista["meta_pdd"] . "</small></td>";
                                                echo "<td><small>" . $lista["programa"] . "</small></td>";
                                                echo "<td><small>" . $lista["logro"] . "</small></td>";
                                                echo "<td><small>" . $lista["proposito"] . "</small></td>";
                                                echo "<td><small>" . $lista["ods"] . "</small></td>";
                                                echo "<td><small>" . $lista["dependencia"] . "</small></td>";
                                                echo "<td>";
                                                echo "<a class='btn btn-success btn-xs' href='" . base_url('dashboard/actividades/' . $lista["id_cuadro_base"]) . "'> Actividades <span class='glyphicon glyphicon-edit' aria-hidden='true'></a>";
?>
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_cuadro_base']; ?>" >
                                                    Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                                </button>

                                                <button type="button" id="<?php echo $lista['id_cuadro_base']; ?>" class='btn btn-danger btn-xs' title="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                </button>
<?php
                                                echo "</td>";
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