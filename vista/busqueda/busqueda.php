<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<form class="form-horizontal" role="form" method="post" action="busqueda.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="buscar">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="buscar">Buscar</label>
					<input type="text" id="buscar" name="buscar" autocomplete="off">
				</div>
			
			</form>
			
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>