<?php

	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR);
	$instancia = $form->getAplicacionPrograma();
	
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
				<li 
					<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
				 ><a href="aplicacionGraficaDigitalDibujoDiseno.php?accion=insertar">Insertar</a></li>
				<li><a href="aplicacionGraficaDigitalDibujoDiseno.php?accion=Buscar">Buscar</a></li>
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>
					Instancia del elemento tecnol&oacute;gico aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o<?php
						if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) {
					?>: <small><?php echo $instancia->getNombre() . ' - ' . $instancia->getVersion() ?></small>
					<?php } ?>
				</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="aplicacionGraficaDigitalDibujoDiseno.php">
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
							value="<?php echo $instancia != null ? $instancia->getNombre() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="version">Versi&oacute;n de la aplicaci&oacute;n:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="version"
							name="version" placeholder="Introduzca la versi&oacute;n de la aplicaci&oacute;n"
							value="<?php echo $instancia != null ? $instancia->getVersion() : "" ?>"
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