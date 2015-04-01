<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_REPRODUCCION_SONIDO_VIDEO_BUSCAR);
	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
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
						echo "Instancias de aplicaci&oacute;n reproducci&oacute;n sonido y video";
					} else {
						echo "Aplicaci&oacute;n reproducci&oacute;n sonido y video";
					}
				?></li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<?php if($esAdministradorOntologia) {?>
				<li><a href="aplicacionReproduccionSonidoVideo.php?accion=insertar">Insertar</a></li>
				<?php } ?>
				<li class="active"><a href="aplicacionReproduccionSonidoVideo.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
			<h1><?php
				if($esAdministradorOntologia) {
					echo "Instancias de aplicaci&oacute;n reproducci&oacute;n sonido y video";
				} else {
					echo "Aplicaci&oacute;n reproducci&oacute;n sonido y video";
				}
			?></h1>
			</div>

			<?php
				if (is_array($aplicaciones) && count($aplicaciones) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Nombre</th>
							<th>Versi&oacute;n</th>
							<?php if($esAdministradorOntologia) {?>
							<th>Opciones</th>
							<?php } ?>
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
							<?php if($esAdministradorOntologia) {?>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="aplicacionReproduccionSonidoVideo.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="iri" value="<?php echo $aplicacion->getIri() ?>">
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
			<p>No existen aplicaciones reproducci&oacute;n sonido y video que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
