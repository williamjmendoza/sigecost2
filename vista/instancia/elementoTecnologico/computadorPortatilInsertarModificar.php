<?php

	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR);
	$equipoComputacion = $form->getEquipoComputacion();
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
						echo "Instancia de computador port&aacute;til";
					} else {
						echo "Computador port&aacute;til";
					}
				?></li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li 
				<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
				 	><a href="computadorPortatil.php?accion=insertar">Insertar</a></li>
				<li><a href="computadorPortatil.php?accion=Buscar">Consultar</a></li>				
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancia de computador port&aacute;til<?php
						if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) {
					?>: <small><?php echo $equipoComputacion->getMarca() . ' - ' . $equipoComputacion->getModelo() ?></small>
					<?php } ?>
				</h1>
			</div>

			<form class="form-horizontal" role="form" method="post" action="computadorPortatil.php">
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
							type="text" class="form-control" id="marca" name="marca" placeholder="Introduzca la marca del Computador Portatil"
							value="<?php echo $equipoComputacion != null ? $equipoComputacion->getMarca() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="modelo">Modelo del Computador Port&aacute;til:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="modelo" name="modelo" placeholder="Introduzca el modelo Computador Portatil"
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
