<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>B&uacute;squeda de patrones
				</h1>
			</div>
		
			<form class="form-horizontal" role="form" method="post" action="busqueda.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="buscar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="clave">Palabra clave:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="clave" name="clave" autocomplete="off" placeholder="Introduzca una(s) palabra(s) clave(es)" value="">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Buscar</button>
					</div>
				</div>
			</form>
			
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>