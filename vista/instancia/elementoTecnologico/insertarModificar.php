<?php

?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<meta charset="utf-8">
		<!-- Para estar seguro de que se utiliza el modo de generación de gráficos más reciente de IE. -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- En dispositivos móviles, para garantizar la generación de gráficos correcta y un funcionamiento correcto de zoom de toque de pantalla. -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Prueba de bootstrap</title>
		
		<!-- Bootstrap -->
    	<link href="<?php echo GetConfig('siteURL'); ?>/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    	
    	<style type="text/css">
    	/*
    		div > div > div {
    			border-width: 1px; border-color: red; border-style: solid;
    		}
    		*/
    		
    		body { padding-top: 70px; }
    	</style>
    	
    	<?php 
    	/*
    	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
		*/				
		?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_VISTA_PATH . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<h1>Instancia del elemento tecnol&oacute;gico impresora</h1>
			<br>
			
			<form class="form-horizontal" role="form">
				<div style="display:none;">
					<input type="hidden" name="accion" value="guardar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca de la impresora:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="marca" name="marca" placeholder="Introduzca la marca de la impresora">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="modelo">Modelo de la impresora:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="modelo" name="modelo" placeholder="Introduzca el modelo de la impresora">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default">Guardar</button>
					</div>
				</div>
			</form>
		
		</div>

	
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="<?php echo GetConfig('siteURL'); ?>/lib/jquery/jquery-1.11.1.min.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="<?php echo GetConfig('siteURL'); ?>/lib/bootstrap/js/bootstrap.min.js"></script>
	
	</body>

</html>