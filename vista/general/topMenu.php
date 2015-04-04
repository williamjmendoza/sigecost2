<?php

	$menuActivo = $GLOBALS['SigecostRequestVars']['menuActivo'];
	$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
	$esAdministradorOntologia = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuarioEsAdministradorOntologia') : null;
	
?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo SIGECOST_PATH_URL_BASE; ?>">Sigecost</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php if($esAdministradorOntologia) {?>
				<li class="dropdown<?php echo $menuActivo=='archivo' ? ' active' : '' ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Archivo<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/archivo.php?accion=exportar">
							Exportar la ontolog&iacute;a
						</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="dropdown<?php echo $menuActivo=='administracionOntologia' ? ' active' : '' ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if($esAdministradorOntologia) {
							echo "Administaci&oacute;n de la ontolog&iacute;a";
						} else {
							echo "Consultas";
						}
						?><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista">
							Elementos tecnol&oacute;gicos
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista">
							Incidencias de soporte t&eacute;cnico
						</a></li>
					</ul>
				</li>
				<li class="<?php echo $menuActivo=='busqueda' ? ' active' : '' ?>"><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/buscar.php?accion=buscar">B&uacute;squedas</a></li>
			</ul>
			<?php
				if (ModeloSesion::estaSesionIniciada() !== true)
				{
			?>
			<form id="formIngreso" class="navbar-form navbar-right" role="search" action="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/sesion.php" method="post">
				<div style="display:none;">
					<input type="hidden" name="accion" value="iniciarSesion">
					<input id="contrasenaCod" type="hidden" name="contrasenaCod">
				</div>
				<div class="form-group">
					<input name="usuario" type="text" class="form-control" placeholder="Usuario">
				</div>
				<div class="form-group">
					<input id="contrasena" type="password" class="form-control" placeholder="Contrase&ntilde;a">
				</div>
				<button type="button" class="btn btn-primary" onclick="ingresar();">Ingresar</button>
			</form>
			<?php
				} else {
			?>
			<form class="navbar-form navbar-right" role="search" action="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/sesion.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="finalizarSesion">
				</div>
				<button type="submit" class="btn btn-primary">Salir</button>
			</form>
			<p class="navbar-text navbar-right"><?php echo $usuario != null ? $usuario->getNombre() . " " . $usuario->getapellido() : '' ?></p>
			<?php
				}
			?>
		</div><!-- /.navbar-collapse -->

	</div>
</nav>
