<?php

	$datosInstancia = isset($GLOBALS['SigecostRequestVars']['datosInstancia']) ? $GLOBALS['SigecostRequestVars']['datosInstancia'] : null;
	$instancia = $datosInstancia != null ? $datosInstancia['instancia'] : null;
	$patron = $instancia != null ? $instancia->getPatron() : null;
	$instanciaImpresora = $instancia != null ? $instancia->getEquipoReproduccion() : null;
	
?>
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
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button type="button" class="btn btn-primary" onclick="regresarBusqueda();">Regresar a la búsqueda</button>
	</div>
</div>