<?php

	$barra = $GLOBALS['SigecostRequestVars']['barra'];

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
				<li><a href="barraDibujo.php?accion=insertar">Insertar</a></li>
				<li><a href="barraDibujo.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico barra de dibujo</h1>
			</div>

			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="nombre">Nombre de la aplicación:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $barra != null ? $barra->getNombre() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="version">Versi&oacute;n de la aplicaci&oacute;n:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $barra != null ? $barra->getVersion() : "" ?></p>
					</div>
				</div>
			</div>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>