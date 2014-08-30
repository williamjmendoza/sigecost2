<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_PRODUCCION_AUDIOVISUAL_MUSICA_BUSCAR);
	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];

?>
<!DOCTYPE html>
<html lang="es">

	<head>

		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>

    	<script type="text/javascript">

			function setAccion(accion) {
				$('input[type="hidden"][name="accion"]').val(accion);
			}

    	</script>

	</head>

	<body>

		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>

		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="aplicacionProduccionAudiovisualMusica.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="aplicacionProduccionAudiovisualMusica.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
			<h1>Instancias del elemento tecnol&oacute;gico aplicaci&oacute;n producci&oacute;n audiovisual y m&uacute;sica</h1>
			</div>

			<?php
				if (is_array($aplicaciones) && count($aplicaciones) > 0)
				{
					$contador =  ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
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
								<form class="form-horizontal" role="form" action="aplicacionProduccionAudiovisualMusica.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $aplicacion->getIri() ?>">
									</div>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Ver detalles</button>
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
			<p>No existen aplicaciones producci&oacute;n audiovisual y m&uacute;sica que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
