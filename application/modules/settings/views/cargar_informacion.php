<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/cargue_archivo.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-legal fa-fw"></i> CARGAR <?php echo $model; ?>
					</h4>
				</div>
			</div>
		</div>		
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-legal"></i> Seleccionar archivo para cargue de <?php echo $model; ?>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-info">
						Seleccionar archivo para cargue de <?php echo $model; ?>
					</div>				
					
		            <div class="row">
		                <?php if (!empty($error)) {
		                    echo '<div class="col-md-12 alert text-center alert-danger"><label>ERROR :</label> <span>' . $error . '</span></div>';
		                } 
		                if (!empty($success)) {
		                    echo '<div class="col-md-12 alert text-center alert-success"><label>' . $success . '</label></div>';
		                } ?>
		            </div>

			        <form name="formCargue" id="formCargue" role="form" method="post" enctype="multipart/form-data" action="<?php echo site_url("/settings/do_upload/" . $model); ?>">
			            <div class="row">
			                <div class="form-group col-md-4">
			                    <label>Ajuntar Archivo:</label>
			                    <input type="file" name="userfile" />
			                </div>				
			            </div>
			            <div class="row">
			                <div class="col-md-12 text-center">
			                    <input type="button" id="btnSubir" name="btnSubir" value="Subir Archivo" class="btn btn-primary" />
			                </div>
			            </div>
			            <br/>
			            <div class="row">
			                <div class="col-md-12 alert alert-info">
			                    <label>Tener en cuenta :</label><br/>
			                    Subir una sola fecha por archivo<br>
			                    Formato permitido : csv<br>
			                    Tamaño máximo : 4096 KB<br>
			                </div>
			            </div>
			        </form>					
					
				</div>
			</div>
		</div>
	</div>
</div>