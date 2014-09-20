<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_ESCANER_BUSCAR);
	$escaners = $GLOBALS['SigecostRequestVars']['escaners'];

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
				<li><a href="escaner.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="escaner.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancias del elemento tecnol&oacute;gico escaner</h1>
			</div>

			<?php
				if (is_array($escaners) && count($escaners) > 0)
				{
					$contador =  ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($escaners AS $escaner)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $escaner->getMarca() ?> </td>
							<td><?php echo $escaner->getModelo() ?></td>
							<td>
								<form class="form-horizonta buscarOpcionesl" role="form" action="escaner.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $escaner->getIri() ?>">
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
			<p>No existen escaners que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
