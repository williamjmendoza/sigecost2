<?php
	
	$GLOBALS['SIGECOST_CFG']["siteURL"] = 'http://localhost/sigecost2';

	// Configuración de la base de datos de patrones de soporte técnico
	$GLOBALS['SIGECOST_CFG']["dbType"] = "mysql";
	$GLOBALS['SIGECOST_CFG']["dbEncoding"] = "UTF8";
	$GLOBALS['SIGECOST_CFG']["dbServer"] = "localhost";
	$GLOBALS['SIGECOST_CFG']["dbPort"] = '3306';
	$GLOBALS['SIGECOST_CFG']["dbUser"] = "root";
	$GLOBALS['SIGECOST_CFG']["dbPass"] = "987654321";
	$GLOBALS['SIGECOST_CFG']["dbDatabase"] = "patrones_st";
	$GLOBALS['SIGECOST_CFG']["tablePrefix"] = "";
	$GLOBALS['SIGECOST_CFG']["characterSet"] = "UTF-8";
	$GLOBALS['SIGECOST_CFG']["timeZone"] = "+0:00";
	$GLOBALS['SIGECOST_CFG']["displayTimeZone"] = "-04:30";
	
	// Configuración de la base de datos de arc2 (Ontología)
	$GLOBALS['SIGECOST_CFG']["ontoDbServer"] = $GLOBALS['SIGECOST_CFG']["dbServer"];
	$GLOBALS['SIGECOST_CFG']["ontoDbUser"] = $GLOBALS['SIGECOST_CFG']["dbUser"];
	$GLOBALS['SIGECOST_CFG']["ontoDbPass"] = $GLOBALS['SIGECOST_CFG']["dbPass"];
	$GLOBALS['SIGECOST_CFG']["ontoDbDatabase"] = "onto_st";
	$GLOBALS['SIGECOST_CFG']["StoreName"] = "st";
	
	// Array de configuración de la base de datos de arc2
	$GLOBALS['SIGECOST_CFG']["ontoArc2Config"] = array(
		// Configuración de la base de datos MySQL
		'db_host' => $GLOBALS['SIGECOST_CFG']["ontoDbServer"],
		'db_user' => $GLOBALS['SIGECOST_CFG']["ontoDbUser"],
		'db_pwd' => $GLOBALS['SIGECOST_CFG']["dbPass"],
		'db_name' => $GLOBALS['SIGECOST_CFG']["ontoDbDatabase"],
		// Configuración del arc2 store
		'store_name' => $GLOBALS['SIGECOST_CFG']["StoreName"], 
		// Configuración del SPARQL endpoint
		'endpoint_features' => array(
			'select', 'construct', 'ask', 'describe', // permite leer
			'load', 'insert', 'delete',               // permite actualizar
			'dump'                                    // permite respaldos
		),
		'endpoint_timeout' => 60, /* no implementado en arc2 previamente */
		'endpoint_read_key' => '', /* opcional */
		'endpoint_write_key' => '', /* opcional */
		'endpoint_max_limit' => 250, /* opcional */
	);
	
	/* Paginación */
	// Tamaño por defecto de la página
	$GLOBALS['SIGECOST_CFG']["tamanoPaginaPorDefecto"] = 10;
	$GLOBALS['SIGECOST_CFG']["tamanoVentana"] = 5;
	/* Fin de Paginación */
	
	$GLOBALS['SIGECOST_CFG']["prefijoPatronSoporteTecnico"] = "PST_";
	$GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSoporteTecnico"] = 30;
	$GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSTBusqueda"] = 300;
	
?>