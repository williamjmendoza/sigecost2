<?php

	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_ESCRITORIO_INSERTAR_MODIFICAR);
	$equipoComputacion = $form->getEquipoComputacion();

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
				 	><a href="computadorEscritorio.php?accion=insertar">Insertar</a></li>
				<li><a href="computadorEscritorio.php?accion=Buscar">Buscar</a></li>				
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>
					Instancia del elemento tecnol&oacute;gico computador de escritorio<?php
						if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) {
					?>: <small><?php echo $equipoComputacion->getMarca() . ' - ' . $equipoComputacion->getModelo() ?></small>
					<?php } ?>
				</h1>
			</div>

			<form class="form-horizontal" role="form" method="post" action="computadorEscritorio.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<input type="hidden" name="iri" value="<?php echo $form->getEquipoComputacion()->getIri() ?>">
			 		<?php } ?>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca del Computador:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="marca" name="marca" placeholder="Introduzca la marca del Computador de Escritorio"
							value="<?php echo $equipoComputacion != null ? $equipoComputacion->getMarca() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="modelo">Modelo del Computador de Escritorio:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="modelo" name="modelo" placeholder="Introduzca el modelo Computador de Escritorio"
							value="<?php echo $equipoComputacion != null ? $equipoComputacion->getModelo() : "" ?>"
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
