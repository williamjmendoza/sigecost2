<?php

	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR);
	$sistemaOperativo = $form->getSistemaOperativo();
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="sistemaOperativo.php?accion=insertar">Insertar</a></li>
				<li><a href="sistemaOperativo.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico sistema operativo</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="nombre">Nombre del sistema operativo:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduzca el nombre del S.O."
							value="<?php echo $sistemaOperativo != null ? $sistemaOperativo->getNombre() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="version">Versi&oacute;n del sistema operativo:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="version" name="version" placeholder="Introduzca la versi&oacute;n del S.O."
							value="<?php echo $sistemaOperativo != null ? $sistemaOperativo->getVersion() : "" ?>"
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
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>
