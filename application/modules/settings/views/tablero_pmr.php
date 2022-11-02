<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/tablero_pmr.js"); ?>"></script>
<script>
$(function(){ 
    $(".btn-primary").click(function () {
            $.ajax ({
                type: 'POST',
                url: base_url + 'settings/cargarModalTableroPMR',
                data: {'idPMR': 'x'},
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
                url: base_url + 'settings/cargarModalTableroPMR',
                data: {'idPMR': oID},
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
                    <i class="fa fa-cogs fa-fw"></i> <b>TABLERO PMR</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-9">                                        
                                        </div>
                                        <div class="col-lg-3">
                                        <?php
                                            $userRol = $this->session->userdata("role");          
                                            if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ADMINISTRADOR){
                                        ?>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="x">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Adicionar Indicador PMR
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        </div>
                                    </div>                      
                                </div>
                                <div class="panel-body small">
                                    <?php 
                                        if(!$info){
                                            echo "<small>No hay definidas las relaciones para esta estretegia.</small>";
                                        }else{
                                    ?>
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                            <thead>
                                                <tr>
                                                    <th style='width: 7%'><small>Opciones</small></th>
                                                    <th><small>Indicador PMR</small></th>
                                                    <th><small>Actividades Relacionadas</small></th>
                                                    <th><small>Meta 2022</small></th>
                                                    <th><small>Unidad Medida</small></th>
                                                    <th><small>Avance Enero 2022</small></th>
                                                    <th><small>Avance Febrero 2022</small></th>
                                                    <th><small>Avance Marzo 2022</small></th>
                                                    <th><small>Avance Abril 2022</small></th>
                                                    <th><small>Avance Mayo 2022</small></th>
                                                    <th><small>Avance Junio 2022</small></th>
                                                    <th><small>Avance Julio 2022</small></th>
                                                    <th><small>Avance Agosto 2022</small></th>
                                                    <th><small>Avance Septiembre 2022</small></th>
                                                    <th><small>Avance Octubre 2022</small></th>
                                                    <th><small>Avance Noviembre 2022</small></th>
                                                    <th><small>Avance Diciembre 2022</small></th>
                                                    <th><small>Total Avance 2022</small></th>
                                                    <th><small>Naturaleza</small></th>
                                                    <th><small>Periodicidad</small></th>
                                                    <th><small>Elemento PEP</small></th>
                                                    <th><small>Producto</small></th>
                                                    <th><small>Objetivo</small></th>
                                                    <th><small>Proyecto de Inversión</small></th>
                                                </tr>
                                            </thead>
                                            <?php
                                            foreach ($info as $lista):
                                                $arrayActividades = $this->settings_model->get_actividades($lista['fk_numero_indicador_pmr']);
                                                $actividades = $arrayActividades[0]['numero_actividad'];
                                                if (count($arrayActividades) > 1) {
                                                    for($i=1; $i<count($arrayActividades); $i++){
                                                        $actividades .= ' - ' . $arrayActividades[$i]['numero_actividad'];
                                                    }
                                                }

                                                $arraySumatoriaMetas = $this->settings_model->sumatoria_metas($lista['fk_numero_indicador_pmr']);
                                                $sumatoriaMetas = $arraySumatoriaMetas[0]['meta_plan_operativo_anual'];
                                                if (count($arraySumatoriaMetas) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaMetas); $i++){
                                                        $sumatoriaMetas .= $arraySumatoriaMetas[$i]['meta_plan_operativo_anual'];
                                                    }
                                                }

                                                $sumatoriaTotal = 0;
                                                $arraySumatoriaEnero = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 1);
                                                $sumatoriaEnero = $arraySumatoriaEnero[0]['ejecutado'];
                                                if (count($arraySumatoriaEnero) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaEnero); $i++){
                                                        $sumatoriaEnero .= $arraySumatoriaEnero[$i]['ejecutado'];
                                                        
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaEnero;

                                                $arraySumatoriaFebrero = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 2);
                                                $sumatoriaFebrero = $arraySumatoriaFebrero[0]['ejecutado'];
                                                if (count($arraySumatoriaFebrero) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaFebrero); $i++){
                                                        $sumatoriaFebrero .= $arraySumatoriaFebrero[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaFebrero;

                                                $arraySumatoriaMarzo = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 3);
                                                $sumatoriaMarzo = $arraySumatoriaMarzo[0]['ejecutado'];
                                                if (count($arraySumatoriaMarzo) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaMarzo); $i++){
                                                        $sumatoriaMarzo .= $arraySumatoriaMarzo[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaMarzo;

                                                $arraySumatoriaAbril = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 4);
                                                $sumatoriaAbril = $arraySumatoriaAbril[0]['ejecutado'];
                                                if (count($arraySumatoriaAbril) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaAbril); $i++){
                                                        $sumatoriaAbril .= $arraySumatoriaAbril[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaAbril;

                                                $arraySumatoriaMayo = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 5);
                                                $sumatoriaMayo = $arraySumatoriaMayo[0]['ejecutado'];
                                                if (count($arraySumatoriaMayo) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaMayo); $i++){
                                                        $sumatoriaMayo .= $arraySumatoriaMayo[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaMayo;

                                                $arraySumatoriaJunio = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 6);
                                                $sumatoriaJunio = $arraySumatoriaJunio[0]['ejecutado'];
                                                if (count($arraySumatoriaJunio) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaJunio); $i++){
                                                        $sumatoriaJunio .= $arraySumatoriaJunio[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaJunio;

                                                $arraySumatoriaJulio = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 7);
                                                $sumatoriaJulio = $arraySumatoriaJulio[0]['ejecutado'];
                                                if (count($arraySumatoriaJulio) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaJulio); $i++){
                                                        $sumatoriaJulio .= $arraySumatoriaJulio[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaJulio;

                                                $arraySumatoriaAgosto = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 8);
                                                $sumatoriaAgosto = $arraySumatoriaAgosto[0]['ejecutado'];
                                                if (count($arraySumatoriaAgosto) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaAgosto); $i++){
                                                        $sumatoriaAgosto .= $arraySumatoriaAgosto[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaAgosto;

                                                $arraySumatoriaSeptiembre = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 9);
                                                $sumatoriaSeptiembre = $arraySumatoriaSeptiembre[0]['ejecutado'];
                                                if (count($arraySumatoriaSeptiembre) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaSeptiembre); $i++){
                                                        $sumatoriaSeptiembre .= $arraySumatoriaSeptiembre[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaSeptiembre;

                                                $arraySumatoriaOctubre = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 10);
                                                $sumatoriaOctubre = $arraySumatoriaOctubre[0]['ejecutado'];
                                                if (count($arraySumatoriaOctubre) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaOctubre); $i++){
                                                        $sumatoriaOctubre .= $arraySumatoriaOctubre[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaOctubre;

                                                $arraySumatoriaNoviembre = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 11);
                                                $sumatoriaNoviembre = $arraySumatoriaNoviembre[0]['ejecutado'];
                                                if (count($arraySumatoriaNoviembre) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaNoviembre); $i++){
                                                        $sumatoriaNoviembre .= $arraySumatoriaNoviembre[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaNoviembre;

                                                $arraySumatoriaDiciembre = $this->settings_model->sumatoria_mes($lista['fk_numero_indicador_pmr'], 12);
                                                $sumatoriaDiciembre = $arraySumatoriaDiciembre[0]['ejecutado'];
                                                if (count($arraySumatoriaDiciembre) > 1) {
                                                    for($i=1; $i<count($arraySumatoriaDiciembre); $i++){
                                                        $sumatoriaDiciembre .= $arraySumatoriaDiciembre[$i]['ejecutado'];
                                                    }
                                                }
                                                $sumatoriaTotal += $sumatoriaDiciembre;
                                                echo "<tr>";
                                                echo "<td class='text-center'>";
                                            ?>
                                                <button title="Editar Tablero PMR" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_pmr']; ?>" >
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                                </button>&nbsp;
                                                <button title="Eliminar Tablero PMR" type="button" id="<?php echo $lista['id_pmr']; ?>" class='btn btn-danger btn-xs' title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            <?php
                                                echo "</td>";
                                                echo "<td><small>" . $lista["fk_numero_indicador_pmr"] . " " . $lista["indicador_pmr"] . "</small></td>";
                                                echo "<td><small>" . $actividades . "</small></td>";
                                                echo "<td><small>" . $sumatoriaMetas . "</small></td>";
                                                echo "<td><small>" . $lista["unidad_medida_pmr"] . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaEnero, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaFebrero, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaMarzo, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaAbril, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaMayo, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaJunio, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaJulio, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaAgosto, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaSeptiembre, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaOctubre, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaNoviembre, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaDiciembre, 3) . "</small></td>";
                                                echo "<td><small>" . round($sumatoriaTotal, 3) . "</small></td>";
                                                echo "<td><small>" . $lista["naturaleza_pmr"] . "</small></td>";
                                                echo "<td><small>" . $lista["periodicidad_pmr"] . "</small></td>";
                                                echo "<td><small>" . $lista["elemento_pep_pmr"] . "</small></td>";
                                                echo "<td><small>" . $lista["fk_numero_producto_pmr"] . " " . $lista["producto_pmr"] . "</small></td>";
                                                echo "<td><small>" . $lista["fk_numero_objetivo_pmr"] . " " . $lista["objetivo_pmr"] . "</small></td>";
                                                echo "<td><small>" . $lista["fk_numero_proyecto_inversion"] . " " . $lista["nombre_proyecto_inversion"] . "</small></td>";
                                                echo "</tr>";
                                            endforeach
                                            ?>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
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
<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
        "pageLength": 100,
        paging: false
    });
});
</script>