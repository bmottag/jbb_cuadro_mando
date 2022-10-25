<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/plan_estrategico.js"); ?>"></script>
<script>
$(function(){ 
    $(".btn-primary").click(function () {  
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'settings/cargarModalCuadroBase',
                data: {'numeroObjetivoEstrategico': oID, 'idCuadroBase': 'x'},
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
                data: {'numeroObjetivoEstrategico': '', 'idCuadroBase': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
    });

    $(".btn-violeta").click(function () {  
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'settings/cargarModalImportarActividad',
                data: {'idCuadroBase': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
    });
});
</script>

<div id="page-wrapper">
    <br>
								
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
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-cogs fa-fw"></i> <b>PLAN ESTRATÉGICO</b>
                </div>
                <div class="panel-body">
                    <?php 
                        foreach ($listaObjetivosEstrategicos as $lista):
                    ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <h4 class="panel-title">
                                            <small>
                                            <strong>Estrategia: </strong><br><?php echo $lista['estrategia'] . ' ' . $lista['descripcion_estrategia']; ?></br>
                                            <strong>Objetivo Estratégico: </strong><br><?php echo $lista['numero_objetivo_estrategico'] . ' ' . $lista['objetivo_estrategico']; ?>
                                            </small>
                                        </h4>
                                    </div>

                                    <div class="col-lg-3">
<?php
    $userRol = $this->session->userdata("role");          
    if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
?>
                                        <div class="pull-right">
                                            <div class="btn-group">                                                                             
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['numero_objetivo_estrategico']; ?>">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Plan de Desarrollo Distrital
                                                </button>
                                            </div>
                                        </div>
<?php } ?>
                                    </div>
                                </div>                      
                            </div>
                            <div class="panel-body small">
                                <?php 
                                    $numeroObjetivoEstrategico = $lista['numero_objetivo_estrategico'];
                                    $arrParam = array('numeroObjetivoEstrategico' => $numeroObjetivoEstrategico);
                                    $metas = $this->general_model->get_lista_metas($arrParam);
                                    $indicadores = $this->general_model->get_lista_indicadores($arrParam);
                                    $resultados = $this->general_model->get_lista_resultados($arrParam);
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
                                                <th><small>Programa SEGPLAN</small></th>
                                                <th><small>Programa Estratégico</small></th>
                                                <th><small>Logro</small></th>
                                                <th><small>Propósito</small></th>
                                                <th><small>ODS</small></th>
                                                <th><small>Dimensiones MIPG</small></th>
                                                <th><small>Dependencia</small></th>
                                                <th><small>Enlaces</small></th>
                                            </tr>
                                        </thead>

                                        <?php
                                        foreach ($cuadroBase as $lista):
                                            //buscar las dependencias relacionadas
                                            $arrParam = array('idCuadroBase' => $lista['id_cuadro_base']);
                                            $dependencias = $this->general_model->get_dependencias($arrParam);

                                            echo "<tr>";
                                            echo "<td><small>" . $lista["meta_proyecto"] . "</small></td>";
                                            echo "<td><small>" . $lista["proyecto_inversion"] . "</small></td>";
                                            echo "<td><small>" . $lista["meta_pdd"] . "</small></td>";
                                            echo "<td><small>" . $lista["programa"] . "</small></td>";
                                            echo "<td><small>" . $lista["programa_estrategico"] . "</small></td>";
                                            echo "<td><small>" . $lista["logro"] . "</small></td>";
                                            echo "<td><small>" . $lista["proposito"] . "</small></td>";
                                            echo "<td><small>" . $lista["ods"] . "</small></td>";
                                            echo "<td><small>" . $lista["dimension"] . "</small></td>";
                                            echo "<td><small>";
                                            if($dependencias){
                                                foreach ($dependencias as $datos):
                                                    echo "<li class='text-primary'>" . $datos["dependencia"] . "</li>";
                                                endforeach;
                                            }
                                            echo "</small></td>";
                                            echo "<td class='text-center'>";
                                            echo "<p><a title='Ver Actividades' class='btn btn-success btn-xs' href='" . base_url('dashboard/actividades/' . $lista["id_cuadro_base"]) . "'> Ver Actividades <span class='glyphicon glyphicon-eye-open' aria-hidden='true'></a></p>";
?>
                                            <p>
                                            <button title="Editar Plan Estratégico" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_cuadro_base']; ?>" >
                                                Editar Plan <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                            </button>
                                            </p>
                                            <p>
                                            <button title="Importar Actividad" type="button" class="btn btn-violeta btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_cuadro_base']; ?>" >
                                                Importar Actividad <span class="glyphicon glyphicon-plus" aria-hidden="true">
                                            </button>
                                            </p>
                                            <button title="Eliminar Plan Estratégico" type="button" id="<?php echo $lista['id_cuadro_base']; ?>" class='btn btn-danger btn-xs' title="Eliminar">
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
                </div>
                    <?php
                        endforeach;
                    ?>      
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