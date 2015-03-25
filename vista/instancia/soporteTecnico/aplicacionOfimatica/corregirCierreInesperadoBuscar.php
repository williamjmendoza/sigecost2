<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_CORREGIR_CIERRE_INESPERADO_BUSCAR);
	$instancias = $GLOBALS['SigecostRequestVars']['instancias'];

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
				<li><a href="corregirCierreInesperado.php?accion=insertar">Insertar</a></li>
				<?php } ?>
				<li class="active"><a href="corregirCierreInesperado.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancias de soporte t&eacute;cnico en aplicaci&oacute;n ofim&aacute;tica:&nbsp;
					<small>corregir cierre inesperado</small>
				</h1>
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
							<th colspan="2">Patr&oacute;n soporte t&eacute;cnico</th>
							<th rowspan="2">Opciones</th>
						</tr>
						<tr>
							<th>Nombre</th>
							<th>Versi&oacute;n</th>
							<th>Fecha creaci&oacute;n</th>
							<th>Soluci&oacute;n</th>
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
							<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
							<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
							<td><?php echo $patron != null ? $patron->getFechaCreacion() : "" ?></td>
							<td><samp><?php
								$strSolucion = "";
								$truncamiento = GetConfig("truncamientoSolucionPatronSoporteTecnico");
								
								if($patron != null)
								{
									if(strlen($patron->getSolucion()) <= $truncamiento)
										$strSolucion = strip_tags($patron->getSolucion());
									else {
										$strSolucion = strip_tags(substr($patron->getSolucion(), 0, $truncamiento - 3)) . '...';
									}

								}
								echo trim($strSolucion);
							
							?></samp></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="corregirCierreInesperado.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs">Ver Detalles</button>
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
