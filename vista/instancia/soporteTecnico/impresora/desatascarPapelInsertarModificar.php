<?php

	$impresoras = $GLOBALS['SigecostRequestVars']['impresoras'];
	$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR);
	$instancia = $form->getSoporteTecnico();
	$patron = $instancia != null ? $instancia->getPatron() : null;
	$instanciaImpresora = $instancia->getEquipoReproduccion();
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
		<script type="text/javascript" src="<?php echo SIGECOST_PATH_URL_JAVASCRIPT ?>/lib/ckeditor/ckeditor.js"></script>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li 
					<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
					><a href="desatascarPapel.php?accion=insertar">Insertar</a></li>
				<li><a href="desatascarPapel.php?accion=Buscar">Consultar</a></li>
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
					<label class="control-label col-sm-2" for="iriEquipoReproduccion">En impresora:</label>
					<div class="col-sm-10">
						<select class="form-control" id="iriEquipoReproduccion"  name="iriEquipoReproduccion"
							<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) ? ' disabled' : ''?>
						>
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
					<label class="control-label col-sm-2" for="solucionSoporteTecnico">Patr&oacute;n soporte t&eacute;cnico:</label>
					<div class="col-sm-10">
						<div class="panel panel-default">
							<div class="panel-heading">Detalles del patr&oacute;n de soporte t&eacute;cnico</div>
							<ul class="list-group">
								<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
								<li class="list-group-item"><strong>Nombre: </strong><?php echo $patron != null ? $patron->getNombre() : "" ?></li>
								<?php }?>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-6">
											<strong>Creado por: </strong>
											<?php
												echo $patron != null
													? 	(	$patron->getUsuarioCreador() != null
															? $patron->getUsuarioCreador()->getNombre() . " " . $patron->getUsuarioCreador()->getApellido() : ""
														)
													: ""
											?>
										</div>
										<div class="col-sm-6">
											<strong>Fecha de creaci&oacute;n: </strong>
											<?php 
												echo $patron != null ? $patron->getFechaCreacion() : "" 
											?>
										</div>
									</div>
								</li>
								<?php if($patron != null && $patron->getUsuarioUltimaModificacion() != null) { ?>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-6">
											<strong>Modificado por: </strong>
											<?php
												echo $patron != null
													? 	(	$patron->getUsuarioUltimaModificacion() != null
															? $patron->getUsuarioUltimaModificacion()->getNombre() . " " . $patron->getUsuarioUltimaModificacion()->getApellido() : ""
														)
													: ""
											?>
										</div>
										<div class="col-sm-6">
											<strong>Fecha de modificaci&oacute;n: </strong>
											<?php 
												echo $patron != null ? $patron->getFechaultimaModificacion() : "" 
											?>
										</div>
									</div>
								</li>
								<?php  } ?>
								<li class="list-group-item">
									<strong>Soluci&oacute;n:</strong>
									<br><br>
									<textarea id="solucionSoporteTecnico" name="solucionSoporteTecnico" rows="3">
										<?php echo $patron != null ? $patron->getSolucion() : "" ?>
									</textarea>
									<script>
										CKEDITOR.replace( 'solucionSoporteTecnico' );
									</script>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
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
