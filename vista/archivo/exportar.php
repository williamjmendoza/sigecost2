<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo SIGECOST_PATH_URL_BASE ?>">Inicio</a></li>
				<li class="active">Exportar la ontolog&iacute;a</li>
			</ol>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Exportar la ontolog&iacute;a</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="archivo.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"  for="exportarOwl">Exportar ontolog&iacute;a a owl:</label>
					<div class="col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('exportarOntologiaAOwl');">Exportar</button>
					</div>
				</div>
			</form>
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>