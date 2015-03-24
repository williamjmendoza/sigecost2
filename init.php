<?php

	require_once(dirname(__FILE__).'/lib/init.php');

	define("APP_ROOT", dirname(__FILE__));
	
	// Inicializar la sesión
	if (!isset($_SESSION)) {
		session_start();
		
		// Modelos
		require_once ( SIGECOST_PATH_MODELO . '/sesion.php' );
		
		// cargar los datos del usuario que ha iniciado sesión
		ModeloSesion::cargarUsuario();
	}

?>