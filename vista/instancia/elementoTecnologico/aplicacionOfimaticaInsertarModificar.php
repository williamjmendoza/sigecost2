<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_OFIMATICA);
	$aplicacion = $form->getAplicacionPrograma();

?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="aplicacionOfimatica.php?accion=insertar">Insertar</a></li>
				<li><a href="aplicacionOfimatica.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico aplicaci&oacute;n ofim&aacute;tica</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="aplicacionOfimatica.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="nombre">Nombre de la aplicaci&oacute;n:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduzca el nombre de la aplicaci&oacute;n"
							value="<?php echo $aplicacion != null ? $aplicacion->getNombre() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="version">Versi&oacute;n de la aplicaci&oacute;n:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="version"
							name="version" placeholder="Introduzca la versi&oacute;n de la aplicaci&oacute;n"
							value="<?php echo $aplicacion != null ? $aplicacion->getVersion() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>