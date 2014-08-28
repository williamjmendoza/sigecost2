<?php

	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_INSERTAR_MODIFICAR);
	$instancia = $form->getSoporteTecnico();
	$instanciaAplicacion = $instancia->getAplicacionPrograma();

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
				<li class="active"><a href="restablecerBarraHerramientasFFD.php?accion=insertar">Insertar</a></li>
				<li><a href="restablecerBarraHerramientasFFD.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en aplicaci&oacute;n ofim&aacute;tica:&nbsp;
					<small>Restablecer barra herramientas funci&oacute;n formato dibujo</small>
				</h1>
			</div>

			<form class="form-horizontal" role="form" method="post" action="restablecerBarraHerramientasFFD.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="iriAplicacionPrograma">En aplicaci&oacute;n de programa:</label>
					<div class="col-sm-7">
						<select class="form-control" id="iriAplicacionPrograma"  name="iriAplicacionPrograma">
							<option value="0">Seleccionar aplicaci&oacute;n...</option>
							<?php
								if(is_array($aplicaciones) && count($aplicaciones) > 0)
								{
									foreach ($aplicaciones AS $aplicacion)
									{
										$seledted = strcmp($instanciaAplicacion->getIri(), $aplicacion->getIri()) == 0 ? ' selected="selected"' : "";

							?>
							<option value="<?php echo $aplicacion->getIri() ?>"<?php echo $seledted ?>>
								<?php echo $aplicacion->getNombre() . ' - ' . $aplicacion->getVersion() ?>
							</option>
							<?php
									}
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="urlSoporteTecnico">Url soporte t&eacute;cnico:</label>
					<div class="col-sm-7">
						<input
							type="text" class="form-control" id="urlSoporteTecnico" name="urlSoporteTecnico" placeholder="Introduzca el url de S.T."
							value="<?php echo $instancia != null ? $instancia->getUrlSoporteTecnico() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-7">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
