<?php

	$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
	$usuario = $form->getUsuario();
	
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
					Mi cuenta
				</li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#">Datos de usuario</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Mi Cuenta</h1>
			</div>
			
			<form id="formUsuario" class="form-horizontal" role="form" method="post" action="usuario.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="actualizarMiCuenta">
					<input type="hidden" name="idUsuario" value="<?php echo $usuario != null ? $usuario->getId() : ''; ?>">
					<input type="hidden" id="contrasenaCodUsuario" name="contrasenaCodUsuario">
					<input type="hidden" id="contrasenaConfirmacionCodUsuario" name="contrasenaConfirmacionCodUsuario">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioUsuario">Usuario:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioUsuario" name="usuarioUsuario" placeholder="Introduzca el identificador del usuario"
							value="<?php echo $usuario != null ? $usuario->getUsuario() : "" ?>" autocomplete="off"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="contrasenaUsuario">Contraseña:</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="contrasenaUsuario" placeholder="Introduzca una contraseña" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="contrasenaConfirmacionUsuario"></label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="contrasenaConfirmacionUsuario" placeholder="Confirme la contraseña" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="cedulalUsuario">C&eacute;dula:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="cedulalUsuario" name="cedulalUsuario" placeholder="Introduzca la c&eacute;dula del usuario"
							value="<?php echo $usuario != null ? $usuario->getCedula() : "" ?>" readonly="readonly"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioNombre">Nombre:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioNombre" name="usuarioNombre" placeholder="Introduzca el nombre del usuario"
							value="<?php echo $usuario != null ? $usuario->getNombre() : "" ?>" readonly="readonly"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioApellido">Apellido:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioApellido" name="usuarioApellido" placeholder="Introduzca el apellido del usuario"
							value="<?php echo $usuario != null ? $usuario->getApellido() : "" ?>" readonly="readonly"
						>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="button" class="btn btn-primary" onclick="guardarUsuario('formUsuario')">Actualizar</button>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>