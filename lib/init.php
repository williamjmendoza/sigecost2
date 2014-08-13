<?php

	define('SIGECOST_PATH_BASE', dirname(realpath(dirname(__FILE__).'/../index.php')));
	define('SIGECOST_PATH_CONTROLADOR', SIGECOST_PATH_BASE.'/controlador');
	define('SIGECOST_PATH_ENTIDAD', SIGECOST_PATH_BASE.'/entidad');
	define('SIGECOST_PATH_ESTILOS', SIGECOST_PATH_BASE.'/estilos');
	define('SIGECOST_PATH_FORMULARIO', SIGECOST_PATH_BASE.'/formulario');
	define('SIGECOST_PATH_JAVASCRIPT', SIGECOST_PATH_BASE.'/javaScript');
	define('SIGECOST_PATH_LIB', SIGECOST_PATH_BASE.'/lib');
	define('SIGECOST_PATH_MODELO', SIGECOST_PATH_BASE.'/modelo');
	define('SIGECOST_PATH_VISTA', SIGECOST_PATH_BASE.'/vista');
	
	define('SIGECOST_CONFIG_FILE', SIGECOST_PATH_BASE . '/config/config.php');

	// Versión mínima de PHP requerida para ejecutar Sigecost
	define("PHP_VERSION_REQUIRED", "5.3");
	
	// Versión mínima de MySQL requerida para ejecutar Sigecost
	define("MYSQL_VERSION_REQUIRED", "4.0.4");
	//return $this->addError('MySQL version not supported. ARC requires version 4.0.4 or higher.');
	/*
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * Pendiente
	 * 
	 * 
	 * 
	 * 
	 * 
	 * */

	if (version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<')) {
		die("<h1>Se requiere PHP ".PHP_VERSION_REQUIRED." o superior para ejecutar Sigecost.</h1>");
	}
	
	require_once (SIGECOST_PATH_LIB . '/general.php');
	
	require (SIGECOST_CONFIG_FILE);
	
	// The url root to sigecost
	define('SIGECOST_PATH_URL_BASE', GetConfig('siteURL'));
	define('SIGECOST_PATH_URL_CONTROLADOR', SIGECOST_PATH_URL_BASE . substr(SIGECOST_PATH_CONTROLADOR, strlen(SIGECOST_PATH_BASE)));
	define('SIGECOST_PATH_URL_ESTILOS', SIGECOST_PATH_URL_BASE . substr(SIGECOST_PATH_ESTILOS, strlen(SIGECOST_PATH_BASE)));
	define('SIGECOST_PATH_URL_JAVASCRIPT', SIGECOST_PATH_URL_BASE . substr(SIGECOST_PATH_JAVASCRIPT, strlen(SIGECOST_PATH_BASE)));
	
	require (SIGECOST_PATH_LIB . '/database/mysql.php');
	
	header("Content-Type: text/html; charset=" . GetConfig('CharacterSet'));
	
	// Realizar la conexión a la base de datos que contiene los patrones de soporte técnico
	$db_type = 'MySQLDb';
	$db = new $db_type();
	
	$db->TablePrefix = GetConfig('tablePrefix');
	$db->charset = GetConfig('dbEncoding');
	$db->timezone = '+0:00';
	
	$connection = $db->Connect(
			GetConfig('dbServer'),
			GetConfig('dbUser'),
			GetConfig('dbPass'),
			GetConfig('dbDatabase')
	);
	
	if (!$connection) {
		list($error, $level) = $db->GetError();
		
		$error = str_replace(GetConfig('dbServer'), "[database server]", $error);
		$error = str_replace(GetConfig('dbUser'), "[database user]", $error);
		$error = str_replace(GetConfig('dbPass'), "[database pass]", $error);
		$error = str_replace(GetConfig('dbDatabase'), "[database]", $error);

		echo "<strong>Ocurrieron errores al realizar la conexi&oacute;n con la base de datos de patrones: </strong>".$error;
		exit;
	
	}
	
	// Crear una referencia al objeto de base de datos
	$GLOBALS['PATRONES_CLASS_DB'] = &$db;
	
	// Realizar la conexión a la base de datos que contiene la ontología (Mediante arc2)
	
	/*
	 * Arc2 introduce una clase estática, que es todo lo que necesita ser incluido. Cualquier otro componente puede
	 * ser cargado vía arc2, sin necesidad de conocer la ruta exacta del archivo con la clase.
	 */
	require_once (SIGECOST_PATH_LIB . '/semsol-arc2/ARC2.php');
	
	$store = ARC2::getStore(GetConfig('ontoArc2Config'));
	
	if (!$store->isSetUp()) {
		
		if ($errors = $store->getErrors()) {
			
			list($error, $level) = $errors;
			
			$error = str_replace(GetConfig('ontoDbServer'), "[onto database server]", $error);
			$error = str_replace(GetConfig('ontoDbUser'), "[onto database user]", $error);
			$error = str_replace(GetConfig('ontoDbPass'), "[onto database pass]", $error);
			$error = str_replace(GetConfig('ontoDbDatabase'), "[onto database]", $error);
			
			echo "<strong>Ocurrieron errores al realizar la conexi&oacute;n con la base de datos de la ontolog&iacute;a: </strong>".$error;
			
			exit;
		}
	
		$store->setUp();
		
	}
	
	// Crear una referencia al objeto store de arc2
	$GLOBALS['ONTOLOGIA_STORE'] = &$store;
	
	
?>