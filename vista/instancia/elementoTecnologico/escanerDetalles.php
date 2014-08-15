<?php
	$escaner = $GLOBALS['SigecostRequestVars']['escaner']; 
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
				<li><a href="escaner.php?accion=insertar">Insertar</a></li>
				<li><a href="escaner.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico escaner</h1>
			</div>
			
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca del Escaner:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $escaner != null ? $escaner->getMarca() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Modelo del Escaner:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $escaner != null ? $escaner->getModelo() : "" ?></p>
					</div>
				</div>
			</div>
			
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>