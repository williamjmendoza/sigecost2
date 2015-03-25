<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_FOTOCOPIADORA_BUSCAR);
	$fotocopiadoras = $GLOBALS['SigecostRequestVars']['fotocopiadoras'];
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
			<ul class="nav nav-tabs" role="tablist">
				<?php if($esAdministradorOntologia) {?>
				<li><a href="fotocopiadora.php?accion=insertar">Insertar</a></li>
				<?php } ?>
				<li class="active"><a href="fotocopiadora.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1><?php
					if($esAdministradorOntologia) {
						echo "Instancias de fotocopiadora";
					} else {
						echo "Fotocopiadora";
					}
				?></h1>
			</div>

			<?php
				if (is_array($fotocopiadoras) && count($fotocopiadoras) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th>Marca</th>
							<th>Modelo</th>
							<?php if($esAdministradorOntologia) { ?>
							<th>Opciones</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($fotocopiadoras AS $fotocopiadora)
					{
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $fotocopiadora->getMarca() ?> </td>
							<td><?php echo $fotocopiadora->getModelo() ?></td>
							<?php if($esAdministradorOntologia) { ?>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="fotocopiadora.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="iri" value="<?php echo $fotocopiadora->getIri() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs">Ver detalles</button>
									</div>
								</form>
							</td>
							<?php } ?>
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
			<p>No existen fotocopiadoras que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
