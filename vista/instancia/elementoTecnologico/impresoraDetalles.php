<?php
	$impresora = $GLOBALS['SigecostRequestVars']['impresora']; 
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
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="impresora.php?accion=insertar">Insertar</a></li>
				<li><a href="impresora.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Detallar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico impresora</h1>
			</div>
			
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca de la impresora:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $impresora != null ? $impresora->getMarca() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Modelo de la impresora:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $impresora != null ? $impresora->getModelo() : "" ?></p>
					</div>
				</div>
			</div>
			
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>