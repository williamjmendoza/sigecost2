<?php
	
	$form = FormularioManejador::GetFormulario(FORM_ET_IMPRESORA_INSERTAR_MODIFICAR);
	$impresora = $form->getImpresora();
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/head.php' ); ?>
		
    	<style type="text/css">
    	/*
    		div > div > div {
    			border-width: 1px; border-color: red; border-style: solid;
    		}
    		*/
    		
    		body { padding-top: 70px; }
    	</style>
	
	</head>
	
	<body>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/topMenu.php' ); ?>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<h1>Instancia del elemento tecnol&oacute;gico impresora</h1>
			<br>
			
			<form class="form-horizontal" role="form" method="post">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca de la impresora:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="marca" name="marca" placeholder="Introduzca la marca de la impresora"
							value="<?php echo $impresora != null ? $impresora->getMarca() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="modelo">Modelo de la impresora:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="modelo" name="modelo" placeholder="Introduzca el modelo de la impresora"
							value="<?php echo $impresora != null ? $impresora->getModelo() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default">Guardar</button>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>