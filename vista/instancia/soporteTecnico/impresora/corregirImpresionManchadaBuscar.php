<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_BUSCAR);
	$instancias = $GLOBALS['SigecostRequestVars']['instancias'];
	$truncamiento = $GLOBALS['SigecostRequestVars']['truncamiento'];
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
				<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista"><?php
					if($esAdministradorOntologia) {
						echo "Administraci&oacute;n de las incidencias de soporte t&eacute;cnico";
					} else {
						echo "Consultas de las incidencias de soporte t&eacute;cnico";
					}
				?></a></li>
				<li class="active"><?php
					if($esAdministradorOntologia) {
						echo "Instancias de corregir impresi&oacute;n manchada";
					} else {
						echo "Corregir impresi&oacute;n manchada";
					}
				?></li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<?php if($esAdministradorOntologia) {?>
				<li><a href="corregirImpresionManchada.php?accion=insertar">Insertar</a></li>
				<?php } ?>
				<li class="active"><a href="corregirImpresionManchada.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1><?php
					if($esAdministradorOntologia) {
						echo "Instancias de corregir impresi&oacute;n manchada";
					} else {
						echo "Corregir impresi&oacute;n manchada";
					}
				?></h1>
			</div>

			<?php
				if (is_array($instancias) && count($instancias) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th rowspan="2">#</th>
							<th colspan="2">Impresora</th>
							<th <?php echo $esAdministradorOntologia ? 'colspan="2"' : 'rowspan="2"' ?>>Soluci&oacute;n de incidencia de soporte t&eacute;cnico</th>
							<th rowspan="2">Opciones</th>
						</tr>
						<tr>
							<th>Marca</th>
							<th>Modelo</th>
							<?php if($esAdministradorOntologia) { ?>
							<th>Fecha creaci&oacute;n</th>
							<th>Descripci&oacute;n</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($instancias AS $instancia)
					{
						$patron = $instancia->getPatron();
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $instancia->getEquipoReproduccion()->getMarca() ?> </td>
							<td><?php echo $instancia->getEquipoReproduccion()->getModelo() ?></td>
							<?php if($esAdministradorOntologia) { ?>
							<td><?php echo $patron != null ? $patron->getSoloFechaCreacion() : "" ?></td>
							<?php } ?>
							<td><?php echo $patron != null ? $patron->getSolucionTruncada($truncamiento) : '';?></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="corregirImpresionManchada.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
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
			<p>No existen instancias que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
