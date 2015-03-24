<?php

	include(dirname(__FILE__)."/init.php");
	
	// Modelos
	include_once(SIGECOST_PATH_MODELO . '/sesion.php');
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<div class="jumbotron">
				<h1>Bienvenido a Sigecost</h1>
				<p>
					Sistema de gesti&oacute;n de conocimiento de soporte t&eacute;cnico
					para los infocentros del pa&iacute;s.
				</p>
			</div>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="row">
				<div class="col-md-4">
					<h2>B&uacute;squeda</h2>
					<p>
						Permite encontrar un patr&oacute;n de soluci&oacute;n de soporte t&eacute;cnico proporcionando una o mas palabras claves.
						Se pueden seleccionar los elementos sobre los cuales se realizaran las b&uacute;squedas:
					</p>
					<ul>
						<li>Clases de elemento tecnol&oacute;gico</li>
						<li>Clases de soporte t&eacute;cnico</li>
						<li>Propiedades</li>
						<li>Instancias</li>
					</ul>
					<p><a class="btn btn-primary btn-xs" role="button"  href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/buscar.php?accion=buscar">Buscar</a></p>
				</div>
				<div class="col-md-4">
					<h2>Administraci&oacute;n de elemento tecnol&oacute;gico</h2>
					<p>
						Permite realizar una serie de operaciones sobre ciertas instancias, pertenecientes al grupo de elemento tecnol&oacute;gico,
						definidas en la ontolog&iacute;a. Estás operaciones incluyen insertar, modificar y/o eliminar una instancia.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista">Administrar</a></p>
				</div>
				<div class="col-md-4">
					<h2>Administraci&oacute;n de soporte t&eacute;cnico</h2>
					<p>
						Permite realizar una serie de operaciones sobre ciertas instancias, pertenecientes al grupo de soporte t&eacute;cnico,
						definidas en la ontolog&iacute;a. Estás operaciones incluyen insertar, modificar y/o eliminar una instancia. Adem&aacute;s,
						se pueden establecer los patrones de soluci&oacute;n de soporte t&eacute;cnico para cada una de estas instancias.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista">Administrar</a></p>
				</div>
			</div>
		
		
			
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>