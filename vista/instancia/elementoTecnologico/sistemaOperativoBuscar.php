<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_BUSCAR);
	$sistemasOperativos = $GLOBALS['SigecostRequestVars']['sistemasOperativos'];

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
				<li><a href="sistemaOperativo.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="sistemaOperativo.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancias del elemento tecnol&oacute;gico sistema operativo</h1>
			</div>

			<?php
				if (is_array($sistemasOperativos) && count($sistemasOperativos) > 0)
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
					foreach ($sistemasOperativos AS $sistemaOperativo)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $sistemaOperativo->getNombre() ?> </td>
							<td><?php echo $sistemaOperativo->getVersion() ?></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="sistemaOperativo.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $sistemaOperativo->getIri() ?>">
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
			<p>No existen sistemas operativos que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
