<?php

	$aplicacion = $GLOBALS['SigecostRequestVars']['aplicacion'];
	
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
				<li><a href="aplicacionOfimatica.php?accion=insertar">Insertar</a></li>
				<li><a href="aplicacionOfimatica.php?accion=Buscar">Consultar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico aplicaci&oacute;n ofim&aacute;tica</h1>
			</div>
			
			<form id="formAplicacionOfimatica" class="form-horizontal" role="form" method="post" action="aplicacionOfimatica.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<input type="hidden" name="iri" value="<?php echo $aplicacion->getIri() ?>">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="nombre">Nombre de la aplicación:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $aplicacion != null ? $aplicacion->getNombre() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="version">Versi&oacute;n de la aplicaci&oacute;n:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $aplicacion != null ? $aplicacion->getVersion() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('modificar');">Modificar</button>
						<button type="button" class="btn btn-primary" onclick="eliminarInstancia('formAplicacionOfimatica');">Eliminar</button>
					</div>
				</div>
			</form>
			
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>