<?php

	$impresoras = $GLOBALS['SigecostRequestVars']['impresoras'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR);
	$instancia = $form->getSoporteTecnico();
	$instanciaImpresora = $instancia->getEquipoReproduccion();
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
		<script type="text/javascript">

			function setAccion(accion) {
				$('input[type="hidden"][name="accion"]').val(accion);
			}

    	</script>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li 
					<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
					><a href="desatascarPapel.php?accion=insertar">Insertar</a></li>
				<li><a href="desatascarPapel.php?accion=Buscar">Buscar</a></li>
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>desatascar papel</small></h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="desatascarPapel.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
					<input type="hidden" name="iri" value="<?php echo $form->getSoporteTecnico()->getIri() ?>">
					<?php } ?>
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
						<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) { ?>
						<button type="submit" class="btn btn-primary" onclick="setAccion('guardar');">Guardar</button>
						<?php } else if ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<button type="submit" class="btn btn-primary" onclick="setAccion('actualizar');">Actualizar</button>
						<?php } ?>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
