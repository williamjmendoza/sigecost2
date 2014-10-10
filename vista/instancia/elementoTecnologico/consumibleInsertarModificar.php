<?php
	
	$aplicaciones = $GLOBALS['SigecostRequestVars']['aplicaciones'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR);
	$consumible = $form->getConsumible();
	
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
				<li 
				<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
				 	><a href="consumible.php?accion=insertar">Insertar</a></li>
				<li><a href="consumible.php?accion=Buscar">Buscar</a></li>							
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>
					Instancia del elemento tecnol&oacute;gico consumible<?php
						if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) {
					?>: <small><?php echo $consumible->getEspecificacion() . ' - ' . $consumible->getTipo() ?></small>
					<?php } ?>
				</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="consumible.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<input type="hidden" name="iri" value="<?php echo $form->getConsumible()->getIri() ?>">
			 		<?php } ?>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="especificacion">Especificaci&oacute;n del consumible:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="especificacion" name="especificacion" placeholder="Introduzca la Especificaci&oacute;n del consumible"
							value="<?php echo $consumible != null ? $consumible->getEspecificacion() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="tipo">Tipo del consumible:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="tipo" name="tipo" placeholder="Introduzca el tipo del consumible"
							value="<?php echo $consumible != null ? $consumible->getTipo() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) { ?>
						<div class="col-sm-offset-3 col-sm-5">
								<button type="submit" class="btn btn-primary" onclick="setAccion('guardar');">Guardar</button>
						</div>
					<?php } else if ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-primary" onclick="setAccion('actualizar');">Actualizar</button>
						</div>
					<?php } ?>
				</div>
			</form>
			
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>