<script type="text/javascript" src="<?php echo base_url("assets/js/validate/resumen/form_estado_actividad.js"); ?>"></script>

<div id="page-wrapper">
    <br>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> No. Actividades: <b><?php echo $nroActividades; ?></b>
                </div>
                <div class="panel-body small">

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
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!--INICIO ADDITIONAL INFORMATION -->
    <div class="row">
        <div class="col-lg-8"> 
             <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> No. Actividades: <b><?php echo $nroActividades; ?></b>
                </div>
                <div class="panel-body small">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No. Actividad</th>
                                <th>Actividad</th>
                                <th class="text-center">Cumplimiento Trim. I</th>
                                <th class="text-center">Cumplimiento Trim. II</th>
                                <th class="text-center">Cumplimiento Trim. III</th>
                                <th class="text-center">Cumplimiento Trim. IV</th>
                                <th class="text-center">Avance POA</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if($listaActividades){
                                foreach ($listaActividades as $lista):
                                    $trim1 = "0%";
                                    $trim2 = "0%";
                                    $trim3 = "0%";
                                    $trim4 = "0%";
                                    $avancePoa = "0%";
                                    if($lista["trimestre_1"] != '' && $lista["trimestre_1"] > 0){
                                        $trim1 = $lista["trimestre_1"] . "%";
                                    }
                                    if($lista["trimestre_2"] != '' && $lista["trimestre_2"] > 0){
                                        $trim2 = $lista["trimestre_2"] . "%";
                                    }
                                    if($lista["trimestre_3"] != '' && $lista["trimestre_3"] > 0){
                                        $trim3 = $lista["trimestre_3"] . "%";
                                    }
                                    if($lista["trimestre_4"] != '' && $lista["trimestre_4"] > 0){
                                        $trim4 = $lista["trimestre_4"] . "%";
                                    }
                                    if($lista["avance_poa"] != '' && $lista["avance_poa"] > 0){
                                        $avancePoa = $lista["avance_poa"] . "%";
                                    }
                                     
                        ?>
                                <tr>
                                    <td class="text-center"><?php echo $lista["numero_actividad"] ?></td>
                                    <td><?php echo $lista["descripcion_actividad"] ?></td>
                                    <td class="text-right"><?php echo $trim1 ?></td>
                                    <td class="text-right"><?php echo $trim2; ?></td>
                                    <td class="text-right"><?php echo $trim3; ?></td>
                                    <td class="text-right"><?php echo $trim4; ?></td>
                                    <td class="text-right"><?php echo $avancePoa; ?></td>
                                </tr>
                        <?php
                                endforeach;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>           
        </div>
        <div class="col-lg-4">              
            <div class="panel panel-primary">
                <div class="panel-heading">
                    SEGUIMIENTO EJECUCIÓN POR TRIMESTRE
                </div>
                <div class="panel-body">
<?php
    $retornoExito = $this->session->flashdata('retornoExito');
    if ($retornoExito) {
?>
        <div class="alert alert-success ">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <?php echo $retornoExito ?>     
        </div>
<?php
    }
    $retornoError = $this->session->flashdata('retornoError');
    if ($retornoError) {
?>
        <div class="alert alert-danger ">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <?php echo $retornoError ?>
        </div>
<?php
    }
?>
                    <div class="col-lg-12"> 
                        <form name="formEstado" id="formEstado" method="post">
                            <div class="form-group">
                                <label for="trimestre">Trimestre:</label>
                                <div>
                                    <select name="trimestre" Iid="trimestre" class="form-control" required >
                                        <option value="">Seleccione...</option>
                                        <option value=1 >Trimestre I</option>
                                        <option value=2 >Trimestre II</option>
                                        <option value=3 >Trimestre III</option>
                                        <option value=4 >Trimestre IV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado:</label>
                                <div>
                                    <select name="valorEstado" id="valorEstado" class="form-control" required >
                                        <option value="">Seleccione...</option>
                                        <?php for ($i = 0; $i < count($listaEstados); $i++) { ?>
                                            <option value="<?php echo $listaEstados[$i]["valor"]; ?>" ><?php echo $listaEstados[$i]["estado"]; ?></option>        
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="information">Observación:</label>
                                <div>
                                <textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Observación" required ></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="row" align="center">
                                    <div style="width:100%;" align="center">
                                        <button type="button" id="btnEstado" name="btnEstado" class="btn btn-primary" >
                                            Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" />
                                        </button> 
                                    </div>
                                </div>
                            </div>                          
                        </form>
                    </div>
                </div>
            </div>
        </div>      
    </div>
<!--FIN ADDITIONAL INFORMATION -->


</div>