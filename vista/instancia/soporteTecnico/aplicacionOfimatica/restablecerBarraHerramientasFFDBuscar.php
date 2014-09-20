<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_BUSCAR);
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
				<li><a href="restablecerBarraHerramientasFFD.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="restablecerBarraHerramientasFFD.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancias de soporte t&eacute;cnico en aplicaci&oacute;n ofim&aacute;tica:&nbsp;
					<small>Restablecer barra herramientas funci&oacute;n formato dibujo</small>
				</h1>
			</div>

			<?php
				if (is_array($instancias) && count($instancias) > 0)
				{
					$contador  = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
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
										$strSolucion = htmlentities($patron->getSolucion());
									else {
										$strSolucion = htmlentities(substr($patron->getSolucion(), 0, $truncamiento - 3)) . '...';
									}

								}
								echo $strSolucion;
							
							?></samp></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="restablecerBarraHerramientasFFD.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
										<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Ver Detalles</button>
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
