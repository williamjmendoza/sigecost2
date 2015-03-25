<?php

	$clasesET = $GLOBALS['SigecostRequestVars']['clasesET'];
	$esAdministradorOntologia = $GLOBALS['SigecostRequestVars']['esAdministradorOntologia'];
	
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
				<li class="active"><a href="administracionOntologia.php?accion=administrarETLista">Lista</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1><?php
					if($esAdministradorOntologia) {
						echo "Administraci&oacute;n de las instancias de los elementos tecnol&oacute;gicos";
					} else {
						echo "Consultas de los elementos tecnol&oacute;gicos";
					}
				?></h1>
			</div>
			<?php
				if (is_array($clasesET) && count($clasesET) > 0)
				{
					$contador = 0;
			?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php
					foreach ($clasesET AS $claseET)
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
								<?php echo $claseET['labelClase'] ?>
							</a>
						</h4>
					</div>
					<div
						id="collapse<?php echo $contador ?>" class="panel-collapse collapse<?php echo $contador == 0 ? ' in' : '' ?>"
						role="tabpanel" aria-labelledby="heading<?php echo $contador ?>"
					>
						<div class="panel-body">
							<?php echo $claseET['commentClase'] ?><br>
							<br>
							<?php if($esAdministradorOntologia) { ?>
							<a class="btn btn-primary btn-xs" role="button"
								href="<?php echo isset($GLOBALS['SIGECOST_VAO']['ET'][$claseET['clase']]['insertar']) ? $GLOBALS['SIGECOST_VAO']['ET'][$claseET['clase']]['insertar'] : "#"  ?>"
							>Insertar</a>
							<?php } ?>
							<a class="btn btn-primary btn-xs" role="button"
								href="<?php echo isset($GLOBALS['SIGECOST_VAO']['ET'][$claseET['clase']]['buscar']) ? $GLOBALS['SIGECOST_VAO']['ET'][$claseET['clase']]['buscar'] : "#"  ?>"
							>Consultar</a>
							
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
