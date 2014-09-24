<?php
	$portatil = $GLOBALS['SigecostRequestVars']['portatil'];
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
				<li><a href="computadorPortatil.php?accion=insertar">Insertar</a></li>
				<li><a href="computadorPortatil.php?accion=Buscar">Buscar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Instancia del elemento tecnol&oacute;gico computador portatil</h1>
			</div>

			<form id="formComputadorPortatil" class="form-horizontal" role="form" method="post" action="computadorPortatil.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<input type="hidden" name="iri" value="<?php echo $portatil->getIri() ?>">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Marca del Computador:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $portatil != null ? $portatil->getMarca() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Modelo del Computador:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $portatil != null ? $portatil->getModelo() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('modificar');">Modificar</button>
						<button type="button" class="btn btn-primary" onclick="eliminarInstancia('formComputadorPortatil');">Eliminar</button>
					</div>
				</div>
			</form>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>