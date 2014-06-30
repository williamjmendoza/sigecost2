<?php

	define('SIGECOST_BASE_PATH', dirname(realpath(dirname(__FILE__).'/../index.php')));
	define('SIGECOST_CONTROLADOR_PATH', SIGECOST_BASE_PATH.'/controlador');
	define('SIGECOST_LIB_PATH', SIGECOST_BASE_PATH.'/lib');
	
	define('SIGECOST_CONFIG_FILE', SIGECOST_BASE_PATH . '/config/config.php');

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
	
	require_once (SIGECOST_LIB_PATH . '/general.php');
	
	require (SIGECOST_CONFIG_FILE);
	
	require (SIGECOST_LIB_PATH . '/database/mysql.php');
	
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
	require_once (SIGECOST_LIB_PATH . '/semsol-arc2/ARC2.php');
	
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