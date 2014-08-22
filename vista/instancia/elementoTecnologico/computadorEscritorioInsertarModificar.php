<?php

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
				<li class="active"><a href="computadorEscritorio.php?accion=insertar">Insertar</a></li>
				<li><a href="computadorEscritorio.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico computador de escritorio</h1>
			</div>

			<form class="form-horizontal" role="form" method="post" action="computadorEscritorio.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
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
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
