<html>
	<head>
		<title>.:SIGECOST:. Mensajes</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
	<?php
		$anyMessage = false;
	
		if(is_array($GLOBALS['SigecostErrors']['general']) && count($GLOBALS['SigecostErrors']['general']) > 0){
			$anyMessage = true;
		}
		if(is_array($GLOBALS['SigecostInfo']['general']) && count($GLOBALS['SigecostInfo']['general']) > 0){
			$anyMessage = true;
		}
		
		if($anyMessage == false){
			echo '
			<ul class= "mensajeError">
				<li>
					'."Se ha producido un error. P&oacute;ngase en contacto con el administrador del sistema.".'
				</li>
			</ul>	
			';
		} else {
			include(SIGECOST_VISTA_PATH . "/mensajes.php");
		}
	?>		
	</body>
</html>

