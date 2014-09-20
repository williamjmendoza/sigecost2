<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_BUSCAR);
	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];

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
				<li class="active"><a href="aplicacionGraficaDigitalDibujoDiseno.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
			<h1>Instancias del elemento tecnol&oacute;gico aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</h1>
			</div>

			<?php
				if (is_array($aplicaciones) && count($aplicaciones) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;

			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th>Nombre</th>
							<th>Versi&oacute;n</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($aplicaciones AS $aplicacion)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $aplicacion->getNombre() ?> </td>
							<td><?php echo $aplicacion->getVersion() ?></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="aplicacionGraficaDigitalDibujoDiseno.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $aplicacion->getIri() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
										<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Ver detalles</button>
									</div>
								</form>
							</td>
						</tr>
			<?php
					}
			?>
					</tbody>
				</table>
			</div>
			<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
			<?php
				} else {
			?>
			<p>No existen aplicaciones gr&aacute;fica digital, dibujo y dise&ntilde;o que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
