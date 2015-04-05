<?php

	$usuario = $GLOBALS['SigecostRequestVars']['usuario'];
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo SIGECOST_PATH_URL_BASE ?>">Inicio</a></li>
				<li class="active">
					Usuarios
				</li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="usuario.php?accion=insertar">Insertar</a></li>
				<li><a href="usuario.php?accion=Buscar">Consultar</a></li>
				<li class="active"><a href="#">Ver Detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Usuario</h1>
			</div>
			
			<form id="formUsuario" class="form-horizontal" role="form" method="get" action="usuario.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<input type="hidden" name="idUsuario" value="<?php echo $usuario->getId() ?>">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="cedulaUsuario">C&eacute;dula:</label>
					<div class="col-sm-10">
						<p class="form-control-static">
							<?php echo $usuario != null ? $usuario->getCedula() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioUsuario">Usuario:</label>
					<div class="col-sm-10">
						<p class="form-control-static">
							<?php echo $usuario != null ? $usuario->getUsuario() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioNombre">Nombre:</label>
					<div class="col-sm-10">
						<p class="form-control-static">
							<?php echo $usuario != null ? $usuario->getNombre() : "" ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioApellido">Apellido:</label>
					<div class="col-sm-10">
						<p class="form-control-static">
							<?php echo $usuario != null ? $usuario->getApellido() : "" ?>
						</p>
					</div>
				</div>
				<?php //if($esAdministradorOntologia) { ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary" onclick="setAccion('modificar');">Modificar</button>
					</div>
				</div>
				<?php //} ?>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
