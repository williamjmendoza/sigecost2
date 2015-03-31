<?php

	include(dirname(__FILE__)."/init.php");
	
	// Modelos
	include_once(SIGECOST_PATH_MODELO . '/general.php');
	include_once(SIGECOST_PATH_MODELO . '/sesion.php');
	
	$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
	$esAdministradorOntologia = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuarioEsAdministradorOntologia') : null;
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li class="active">Inicio</li>
			</ol>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
			<div class="jumbotron">
				<h1>Bienvenidos a SigecoST</h1>
				<p>
					Sistema de gesti&oacute;n de conocimiento de soporte t&eacute;cnico
					para los infocentros del pa&iacute;s.
				</p>
			</div>
		</div>
		
		<div class="container">
		
			<div class="row">
				<div class="col-md-4">
					<h2>B&uacute;squedas de soluciones de incidencias de soporte t&eacute;cnico</h2>
					<p>
						Permite buscar soluciones de incidencias de soporte t&eacute;cnico a partir de una o mas palabras claves.
						Se pueden seleccionar los elementos sobre los cuales se realizaran las b&uacute;squedas:
					</p>
					<ul>
						<li>Tipos de elementos tecnol&oacute;gicos</li>
						<li>Tipos de incidencias de soporte t&eacute;cnico</li>
						<li>Ejemplos</li>
					</ul>
					<p>
						<a class="btn btn-primary btn-xs" role="button"  href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/buscar.php?accion=buscar">Buscar</a>
					</p>
					
				</div>
				<?php if($esAdministradorOntologia) { ?>
				<div class="col-md-4">
					<h2>Administraci&oacute;n de las instancias de elementos tecnol&oacute;gicos</h2>
					<p>
						Permite consultar y realizar un conjunto de operaciones sobre las instancias de elementos tecnol&oacute;gicos
						definidas en la ontolog&iacute;a. Estas operaciones incluyen insertar, modificar y/o eliminar las instancias.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista">Administrar</a></p>
				</div>
				<?php } else {?>
				<div class="col-md-4">
					<h2>Consultas de los elementos tecnol&oacute;gicos</h2>
					<p>
						Permite consultar los diferentes elementos tecnol&oacute;gicos definidos en el sistema. Se pueden visualizar tanto sus descripciones como los detalles
						t&eacute;cnicos de marca, modelo, versi&oacute;n, etc.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista">Consultar</a></p>
				</div>
				<?php } ?>
				<?php if($esAdministradorOntologia) { ?>
				<div class="col-md-4">
					<h2>Administraci&oacute;n de las instancias de incidencias de soporte t&eacute;cnico y sus soluciones</h2>
					<p>
						Permite consultar y realizar un conjunto de operaciones sobre las instancias de las incidencias de soporte t&eacute;cnico
						definidas en la ontolog&iacute;a. Estas operaciones incluyen insertar, modificar y/o eliminar las instancias. Adem&aacute;s,
						se pueden consultar e ingresar las soluciones de incidencias de soporte t&eacute;cnico, para cada una de estas instancias.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista">Administrar</a></p>
				</div>
				<?php } else { ?>
				<div class="col-md-4">
					<h2>Consultas de las incidencias de soporte t&eacute;cnico y sus soluciones</h2>
					<p>
						Permite consultar las incidencias de soporte t&eacute;cnico definidas en el sistema. Se pueden visualizar tanto sus descipciones como los detalles
						de los elementos tecnol&oacute;gicos involucrados en las incidencias, y las soluciones a estas.
					</p>
					<p><a class="btn btn-primary btn-xs" role="button" href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista">Consultar</a></p>
				</div>
				<?php }?>
			</div>
		
		
			
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>