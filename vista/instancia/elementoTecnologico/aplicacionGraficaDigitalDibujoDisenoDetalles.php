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
				<li><a href="aplicacionGraficaDigitalDibujoDiseno.php?accion=insertar">Insertar</a></li>
				<li><a href="aplicacionGraficaDigitalDibujoDiseno.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</h1>
			</div>
			
			<div class="form-horizontal" role="form">
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
			</div>
			
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>