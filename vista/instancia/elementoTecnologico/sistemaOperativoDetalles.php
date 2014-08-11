<?php
	$sistemaOperativo = $GLOBALS['SigecostRequestVars']['sistemaOperativo']; 
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
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico sistema operativo</h1>
			</div>
			
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Nombre del sistema operativo:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $sistemaOperativo != null ? $sistemaOperativo->getNombre() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Versi&oacute;n del sistema operativo:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $sistemaOperativo != null ? $sistemaOperativo->getVersion() : "" ?></p>
					</div>
				</div>
			</div>
			
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>