<?php

	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$patron = $instancia != null ? $instancia->getPatron() : null;
	$instanciaImpresora = $instancia != null ? $instancia->getEquipoReproduccion() : null;
	$esAdministradorOntologia = $GLOBALS['SigecostRequestVars']['esAdministradorOntologia'];
	
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
				<?php if($esAdministradorOntologia) {?>
				<li><a href="repararImpresionCorrida.php?accion=insertar">Insertar</a></li>
				<?php } ?>
				<li><a href="repararImpresionCorrida.php?accion=Buscar">Consultar</a></li>
				<li class="active"><a href="#">Ver Detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1><?php
					if($esAdministradorOntologia) {
						echo "Instancia de reparar impresi&oacute;n corrida";
					} else {
						echo "Reparar impresi&oacute;n corrida";
					}
				?></h1>
			</div>
			
			<form id="formRepararImpresionCorrida" class="form-horizontal" role="form" method="post" action="repararImpresionCorrida.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="iriEquipoReproduccion">En impresora:</label>
					<div class="col-sm-10">
						<p class="form-control-static">
							<?php echo $instanciaImpresora != null ? $instanciaImpresora->getMarca() . ' - ' .$instanciaImpresora->getModelo() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="solucionSoporteTecnico">Patr&oacute;n soporte t&eacute;cnico:</label>
					<div class="col-sm-10">
						<div class="panel panel-default">
							<div class="panel-heading">Detalles del patr&oacute;n de soporte t&eacute;cnico</div>
							<ul class="list-group">
								<li class="list-group-item"><strong>Nombre: </strong><?php echo $patron != null ? $patron->getNombre() : "" ?></li>
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
									<div class="well well-sm">
										<?php echo $patron != null ? $patron->getSolucion() : "" ?>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<?php if($esAdministradorOntologia) { ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary" onclick="setAccion('modificar');">Modificar</button>
						<button type="button" class="btn btn-primary" onclick="eliminarInstancia('formRepararImpresionCorrida');">Eliminar</button>
					</div>
				</div>
				<?php } ?>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
