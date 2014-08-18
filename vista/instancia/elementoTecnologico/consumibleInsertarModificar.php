<?php

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
				<li class="active"><a href="consumible.php?accion=insertar">Insertar</a></li>
				<li><a href="consumible.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico consumible</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="especificacion">Especificacion del consumible:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="especificacion" name="especificacion" placeholder="Introduzca la Especificacion del consumible"
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
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
