<?php

	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$instanciaImpresora = $instancia->getEquipoReproduccion();
	
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
				<li><a href="corregirImpresionManchada.php?accion=insertar">Insertar</a></li>
				<li><a href="corregirImpresionManchada.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Ver Detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>corregir impresi&oacute;n manchada</small></h1>
			</div>
			
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="iriEquipoReproduccion">En impresora:</label>
					<div class="col-sm-7">
						<p class="form-control-static">
							<?php echo $instanciaImpresora != null ? $instanciaImpresora->getMarca() . ' - ' .$instanciaImpresora->getModelo() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="urlSoporteTecnico">Url soporte t&eacute;cnico:</label>
					<div class="col-sm-7">
						<p class="form-control-static"><?php echo $instancia != null ? $instancia->getUrlSoporteTecnico() : "" ?></p>
					</div>
				</div>
			</div>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
