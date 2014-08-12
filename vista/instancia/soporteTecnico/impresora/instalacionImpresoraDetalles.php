<?php

	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$instanciaImpresora = $instancia->getEquipoReproduccion();
	$instanciaSistemaOperativo = $instancia->getSistemaOperativo();
	
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
				<li><a href="instalacionImpresora.php?accion=insertar">Insertar</a></li>
				<li><a href="instalacionImpresora.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Detallar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>instalaci&oacute;n de impresora</small></h1>
			</div>
			
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="urlSoporteTecnico">Url soporte t&eacute;cnico:</label>
					<div class="col-sm-7">
						<p class="form-control-static"><?php echo $instancia != null ? $instancia->getUrlSoporteTecnico() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="iriEquipoReproduccion">En impresora:</label>
					<div class="col-sm-7">
						<p class="form-control-static">
							<?php echo $instanciaImpresora != null ? $instanciaImpresora->getMarca() . ' - ' .$instanciaImpresora->getModelo() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="iriSistemaOperativo">Sobre sistema operativo:</label>
					<div class="col-sm-7">
						<p class="form-control-static">
							<?php echo $instanciaSistemaOperativo != null ? $instanciaSistemaOperativo->getNombre() . ' - ' . $instanciaSistemaOperativo->getVersion() : "" ?>
						</p>
					</div>
				</div>
			</div>
		
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>
