<?php
	
	$form = FormularioManejador::getFormulario(FORM_USUARIO_BUSCAR);
	$usuarios = $GLOBALS['SigecostRequestVars']['usuarios'];

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
				<li class="active"><a href="usuario.php?accion=Buscar">Consultar</a></li>
			</ul>
		</div>

		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>

		<div class="container">

			<div class="page-header">
				<h1>Usuarios</h1>
			</div>

			<?php
				if (is_array($usuarios) && count($usuarios) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Usuario</th>
							<th>C&eacute;dula</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($usuarios AS $usuario)
					{
						
			?>
						<tr>
							<td><?php echo (++$contador) ?></td>
							<td><?php echo $usuario->getUsuario() ?></td>
							<td><?php echo $usuario->getCedula() ?> </td>
							<td><?php echo $usuario->getNombre() ?></td>
							<td><?php echo $usuario->getApellido() ?></td>
							<td>
								<form class="form-horizontal buscarOpciones" role="form" action="usuario.php" method="get">
									<div style="display:none;">
										<input type="hidden" name="accion" value="desplegarDetalles">
										<input type="hidden" name="idUsuario" value="<?php echo $usuario->getId() ?>">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-xs">Ver Detalles</button>
									</div>
								</form>
							</td>
						</tr>
			<?php
					}
			?>
					</tbody>
				</table>
			</div>
			<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
			<?php
				} else {
			?>
			<p>No existen usuarios que mostrar.</p>
			<?php
				}
			?>

		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>

	</body>

</html>
