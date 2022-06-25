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
        <div class="col-lg-6">              
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
                        <form name="formEstado" id="formEstado" class="form-horizontal" method="post">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="trimestre">Trimestre:</label>
                                <div class="col-sm-8">
                                    <select name="trimestre" Iid="trimestre" class="form-control" >
                                        <option value="">Seleccione...</option>
                                        <option value=1 >Trimestre I</option>
                                        <option value=2 >Trimestre II</option>
                                        <option value=3 >Trimestre III</option>
                                        <option value=4 >Trimestre IV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="estado">Estado:</label>
                                <div class="col-sm-8">
                                    <select name="valorEstado" id="valorEstado" class="form-control" required >
                                        <option value="">Seleccione...</option>
                                        <?php for ($i = 0; $i < count($listaEstados); $i++) { ?>
                                            <option value="<?php echo $listaEstados[$i]["valor"]; ?>" ><?php echo $listaEstados[$i]["estado"]; ?></option>        
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="information">Observación:</label>
                                <div class="col-sm-8">
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