<?php
	include(dirname(__FILE__)."/init.php");
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
    	
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
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<div class="jumbotron">
			
				<h1>Bienvenido a Sigecost</h1>
				<p>
					Sistema de gesti&oacute;n de conocimiento de soporte t&eacute;cnico
					para los infocentros del pa&iacute;s.
				</p>
				<p><a class="btn btn-primary btn-lg" role="button">Leer m&aacute;s</a></p>
			</div>
		</div>
		
		
		
		<div class="container">
			<h1>B&uacute;squedas</h1>
			<ul>
				<li><a href="controlador/todasTripletas.php">Todas las tripletas</a></li>
				<li><a href="controlador/busqueda.php">B&uacute;squeda</a></li>
			</ul>
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>