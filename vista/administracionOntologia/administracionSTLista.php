<?php

	$clasesST = $GLOBALS['SigecostRequestVars']['clasesST'];
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="administracionOntologia.php?accion=administrarSTLista">Lista</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Administraci&oacute;n de la ontolog&iacute;a</h1>
			</div>
			
			<div class="page-header">
				<h1><small>Soporte t&eacute;cnico</small></h1>
			</div>
			<?php
				if (is_array($clasesST) && count($clasesST) > 0)
				{
					$contador = 0;
			?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php
					foreach ($clasesST AS $claseST)
					{
				?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading<?php echo $contador ?>">
						<h4 class="panel-title">
							<a
								class="<?php echo $contador == 0 ? 'collapsed' : '' ?>"
								data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $contador ?>"
								aria-expanded="true" aria-controls="collapse<?php echo $contador ?>"
							>
								<?php echo $claseST['labelClase'] ?>
							</a>
						</h4>
					</div>
					<div
						id="collapse<?php echo $contador ?>" class="panel-collapse collapse<?php echo $contador == 0 ? ' in' : '' ?>"
						role="tabpanel" aria-labelledby="heading<?php echo $contador ?>"
					>
						<div class="panel-body">
							<?php echo $claseST['commentClase'] ?><br>
							<br>
							<a class="btn btn-primary btn-xs" role="button"
								href="<?php echo isset($GLOBALS['SIGECOST_VAO']['ST'][$claseST['clase']]['insertar']) ? $GLOBALS['SIGECOST_VAO']['ST'][$claseST['clase']]['insertar'] : "#"  ?>"
							>Insertar</a>
							<a class="btn btn-primary btn-xs" role="button"
								href="<?php echo isset($GLOBALS['SIGECOST_VAO']['ST'][$claseST['clase']]['buscar']) ? $GLOBALS['SIGECOST_VAO']['ST'][$claseST['clase']]['buscar'] : "#"  ?>"
							>Buscar</a>
							
						</div>
					</div>
				</div>
				<?php
						$contador++;
					}
				?>
			</div>
			<?php
				}
			?>
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>