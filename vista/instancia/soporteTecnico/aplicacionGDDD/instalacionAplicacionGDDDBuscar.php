<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_INSTALACION_APLICACION_BUSCAR);
	$instancias = $GLOBALS['SigecostRequestVars']['instancias'];

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
				<li><a href="instalacionAplicacionGDDD.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="instalacionAplicacionGDDD.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancias de soporte t&eacute;cnico en aplicaci&oacute;n de programa:&nbsp;
				<small>instalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</small></h1>
			</div>

			<?php
				if (is_array($instancias) && count($instancias) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;

			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th rowspan="2">#</th>
							<th colspan="2">Aplicaci&oacute;n</th>
							<th colspan="2">Sistema operativo</th>
							<th rowspan="2">Url soporte t&eacute;cnico</th>
							<th rowspan="2">Opciones</th>
						</tr>
						<tr>
							<th>Nombre</th>
							<th>Versi&oacute;n</th>
							<th>Nombre</th>
							<th>Versi&oacute;n</th>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($instancias AS $instancia)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
							<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
							<td><?php echo $instancia->getSistemaOperativo()->getNombre() ?></td>
							<td><?php echo $instancia->getSistemaOperativo()->getVersion() ?></td>
							<td><?php echo $instancia->getUrlSoporteTecnico() ?></td>
							<td>
								<form class="form-horizontal" role="form" action="instalacionAplicacionGDDD.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
									</div>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Ver Detalles</button>
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
			<p>No existen instancias que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
