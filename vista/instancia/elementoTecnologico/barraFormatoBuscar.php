<?php
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_BARRA_FORMATO_BUSCAR);
	$barras = $GLOBALS['SigecostRequestVars']['barras'];

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
				<li><a href="barraFormato.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="barraFormato.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
			<h1>Instancias del elemento tecnol&oacute;gico barra de formato</h1>
			</div>

			<?php
				if (is_array($barras) && count($barras) > 0)
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
					foreach ($barras AS $barra)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $barra->getNombre() ?> </td>
							<td><?php echo $barra->getVersion() ?></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="barraFormato.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="iri" value="<?php echo $barra->getIri() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs">Ver detalles</button>
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
			<p>No existen barras de formato que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
