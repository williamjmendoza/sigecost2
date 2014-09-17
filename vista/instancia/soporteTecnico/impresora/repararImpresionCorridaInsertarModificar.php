<?php

	$impresoras = $GLOBALS['SigecostRequestVars']['impresoras'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_REPARAR_IMPRESION_CORRIDA_INSERTAR_MODIFICAR);
	$instancia = $form->getSoporteTecnico();
	$patron = $instancia != null ? $instancia->getPatron() : null;
	$instanciaImpresora = $instancia != null ? $instancia->getEquipoReproduccion() : null;
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<script type="text/javascript" src="<?php echo SIGECOST_PATH_URL_JAVASCRIPT ?>/lib/ckeditor/ckeditor.js"></script>
		
		<script src="<?php echo SIGECOST_PATH_URL_JAVASCRIPT ?>/lib/jquery/jquery-1.11.1.min.js"></script>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="repararImpresionCorrida.php?accion=insertar">Insertar</a></li>
				<li><a href="repararImpresionCorrida.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>reparar impresi&oacute;n corrida</small></h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="repararImpresionCorrida.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="iriEquipoReproduccion">En impresora:</label>
					<div class="col-sm-7">
						<select class="form-control" id="iriEquipoReproduccion"  name="iriEquipoReproduccion">
							<option value="0">Seleccionar impresora...</option>
							<?php
								if(is_array($impresoras) && count($impresoras) > 0)
								{
									foreach ($impresoras AS $impresora)
									{
										$seledted = strcmp($instanciaImpresora->getIri(), $impresora->getIri()) == 0 ? ' selected="selected"' : "";  
										
							?>
							<option value="<?php echo $impresora->GetIri() ?>"<?php echo $seledted ?>>
								<?php echo $impresora->getMarca() . ' - ' . $impresora->getModelo() ?>
							</option>
							<?php
									}
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="solucionSoporteTecnico">Soluci&oacute;n soporte t&eacute;cnico:</label>
					<div class="col-sm-7">
						<textarea id="solucionSoporteTecnico" name="solucionSoporteTecnico" rows="3">
							<?php echo $patron != null ? $patron->getSolucion() : "" ?>
						</textarea>
						<script>
							CKEDITOR.replace( 'solucionSoporteTecnico' );
						</script>
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
