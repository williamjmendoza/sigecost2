<?php

	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_OFIMATICA_INSERTAR_MODIFICAR);
	$aplicacion = $form->getAplicacionPrograma();
	$esAdministradorOntologia = $GLOBALS['SigecostRequestVars']['esAdministradorOntologia'];

?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo SIGECOST_PATH_URL_BASE ?>">Inicio</a></li>
				<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista"><?php
					if($esAdministradorOntologia) {
						echo "Administraci&oacute;n de los elementos tecnol&oacute;gicos";
					} else {
						echo "Consultas de los elementos tecnol&oacute;gicos";
					}
				?></a></li>
				<li class="active"><?php
					if($esAdministradorOntologia) {
						echo "Instancia de aplicaci&oacute;n ofim&aacute;tica";
					} else {
						echo "Aplicaci&oacute;n ofim&aacute;tica";
					}
				?></li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li 
					<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
				 	><a href="aplicacionOfimatica.php?accion=insertar">Insertar</a></li>
				<li><a href="aplicacionOfimatica.php?accion=Buscar">Consultar</a></li>
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>
					Instancia de aplicaci&oacute;n ofim&aacute;tica<?php
						if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) {
					?>: <small><?php echo $aplicacion->getNombre() . ' - ' . $aplicacion->getVersion() ?></small>
					<?php } ?>
				</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="aplicacionOfimatica.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<input type="hidden" name="iri" value="<?php echo $form->getAplicacionPrograma()->getIri() ?>">
			 		<?php } ?>
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
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) { ?>
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('guardar');">Guardar</button>
					</div>
					
					<?php } else if ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('actualizar');">Actualizar</button>
					</div>
					<?php } ?>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>