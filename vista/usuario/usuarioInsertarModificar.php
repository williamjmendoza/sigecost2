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
					Usuarios
				</li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li 
					<?php echo ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) ? ' class="active"' : ''; ?>
				><a href="usuario.php?accion=insertar">Insertar</a></li>
				<li><a href="usuario.php?accion=Buscar">Consultar</a></li>
				<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
				<li class="active"><a href="#">Modificar</a></li>
				<?php } ?>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Usuario</h1>
			</div>
			
			<form class="form-horizontal" role="form" method="post" action="usuario.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
					<input type="hidden" name="idUsuario" value="<?php echo $form->getUsuario()->getId() ?>">
					<?php } ?>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="cedulalUsuario">C&eacute;dula:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="cedulalUsuario" name="cedulalUsuario" placeholder="Introduzca la c&eacute;dula del usuario"
							value="<?php echo $usuario != null ? $usuario->getCedula() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioUsuario">Usuario:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioUsuario" name="usuarioUsuario" placeholder="Introduzca el identificador del usuario"
							value="<?php echo $usuario != null ? $usuario->getUsuario() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioNombre">Nombre:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioNombre" name="usuarioNombre" placeholder="Introduzca el nombre del usuario"
							value="<?php echo $usuario != null ? $usuario->getNombre() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="usuarioApellido">Apellido:</label>
					<div class="col-sm-5">
						<input
							type="text" class="form-control" id="usuarioApellido" name="usuarioApellido" placeholder="Introduzca el apellido del usuario"
							value="<?php echo $usuario != null ? $usuario->getApellido() : "" ?>"
						>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="esAdministradorIncidencias"></label>
					<div class="col-sm-5">
						<input id="esAdministradorIncidencias" name="esAdministradorIncidencias" type="checkbox" value="true"<?php
							if($usuario != null)
							{
								$roles = $usuario->getRoles();
								if(is_array($roles)){
									if(array_key_exists(SIGECOST_USUARIO_ADMINISTRADOR_ONTOLOGIA, $roles)){
										echo ' checked="checked"';
									}
								}
							}
						?>>
							Es administrador de incidencias
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="esAdministradorUsuarios"></label>
					<div class="col-sm-5">
						<input id="esAdministradorUsuarios" name="esAdministradorUsuarios" type="checkbox" value="true"<?php
							if($usuario != null)
							{
								$roles = $usuario->getRoles();
								if(is_array($roles)){
									if(array_key_exists(SIGECOST_USUARIO_ADMINISTRADOR_USUARIOS, $roles)){
										echo ' checked="checked"';
									}
								}
							}
						?>>
							Es administrador de usuarios
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<?php if($form->getTipoOperacion() == Formulario::TIPO_OPERACION_INSERTAR) { ?>
						<button type="submit" class="btn btn-primary" onclick="setAccion('guardar');">Guardar</button>
						<?php } else if ($form->getTipoOperacion() == Formulario::TIPO_OPERACION_MODIFICAR) { ?>
						<button type="submit" class="btn btn-primary" onclick="setAccion('actualizar');">Actualizar</button>
						<?php } ?>
					</div>
				</div>
			</form>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
