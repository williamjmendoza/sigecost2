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
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Instancias <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li class="dropdown-header">Elemento tecnol&oacute;gico</li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php?accion=insertar">
							Aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/aplicacionOfimatica.php?accion=insertar">
							Aplicaci&oacute;n ofim&aacute;tica
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php?accion=insertar">
							Aplicaci&oacute;n producci&oacute;n audiovisual y m&uacute;sica
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php?accion=insertar">
							Aplicaci&oacute;n reproducci&oacute;n sonido y video
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/barraDibujo.php?accion=insertar">
							Barra de Dibujo
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/impresora.php?accion=insertar">Impresora</a></li>
					    <li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/escaner.php?accion=insertar">Escaner</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/fotocopiadora.php?accion=insertar">Fotocopiadora</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/consumible.php?accion=insertar">Consumible</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/elementoTecnologico/sistemaOperativo.php?accion=insertar">Sistema operativo</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">Soporte t&eacute;cnico</li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php?accion=insertar">
							Desinstalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php?accion=insertar">
							Corregir cierre inesperado de aplicaci&oacute;n ofim&aacute;tica
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/impresora/corregirImpresionManchada.php?accion=insertar">
							Corregir impresi&oacute;n manchada
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/impresora/desatascarPapel.php?accion=insertar">
							Desatascar Papel
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/impresora/instalacionImpresora.php?accion=insertar">
							Instalaci&oacute;n impresora
						</a></li>
						<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/instancia/soporteTecnico/impresora/repararImpresionCorrida.php?accion=insertar">
							Reparar impresi&oacute;n corrida
						</a></li>
					</ul>
				</li>

				<li class="active"><a href="#">Enlace 1</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Enlace 3 <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li class="dropdown-header">Elemento tecnol&oacute;gico</li>
						<li><a href="#">Insertar impresora</a></li>
						<li><a href="#">Modificar</a></li>
						<li><a href="#">Enlace 3.3</a></li>
						<li class="divider"></li>
						<li><a href="#">Enlace 3.2.1</a></li>
						<li class="divider"></li>
						<li><a href="#">Enlace 3.3.2</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->

	</div>
</nav>
