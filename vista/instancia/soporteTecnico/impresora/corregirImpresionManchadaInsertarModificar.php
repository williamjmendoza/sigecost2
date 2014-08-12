<?php

	$impresoras = $GLOBALS['SigecostRequestVars']['impresoras'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR);
	$sTEquipoReproduccion = $form->getSoporteTecnico();
	$equipoReproduccion = $sTEquipoReproduccion->getEquipoReproduccion();
	
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
				<li class="active"><a href="corregirImpresionManchada.php?accion=insertar">Insertar</a></li>
				<li><a href="corregirImpresionManchada.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>corregir impresi&oacute;n manchada</small></h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="corregirImpresionManchada.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="urlSoporteTecnico">Url soporte t&eacute;cnico:</label>
					<div class="col-sm-7">
						<input
							type="text" class="form-control" id="urlSoporteTecnico" name="urlSoporteTecnico" placeholder="Introduzca el url de S.T."
							value="<?php echo $sTEquipoReproduccion != null ? $sTEquipoReproduccion->getUrlSoporteTecnico() : "" ?>"
						>
					</div>
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
										//<?php echo $equipoReproduccion != null ? $equipoReproduccion->getIri() . " --Algo" : "Nada"
										//select - HTML selected="selected"
										$seledted = strcmp($equipoReproduccion->getIri(), $impresora->GetIri()) == 0 ? ' selected="selected"' : "";  
										
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
					<div class="col-sm-offset-3 col-sm-7">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>
