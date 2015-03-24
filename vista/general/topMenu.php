<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Archivo<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/archivo.php?accion=exportar">
							Exportar
						</a></li>
					</ul>
				</li>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administaci&oacute;n de la ontolog&iacute;a<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista">
							Elemento tecnol&oacute;gico
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarSTLista">
							Soporte t&eacute;cnico
						</a></li>
					</ul>
				</li>
				<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/buscar.php?accion=buscar">B&uacute;squeda</a></li>
				<li class="active"><a href="#">Opciones</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->

	</div>
</nav>
