<?php
	
	$GLOBALS['SIGECOST_CFG']["siteURL"] = '<URL completo del sigecost>';

	// Revisar el archivo lib/definiciones y estrablecer la siguiente definición
	// define('SIGECOST_IRI_GRAFO_POR_DEFECTO', 'http://localhost/sigecost2/controlador/soporte_tecnico.owl');
	// Iri del grafo por defecto. El grafo por defecto puede ser verificado ejecutando el siguiente query
	// en la base de datos de la ontología:
	/*
	SELECT DISTINCT
	 onto_st.st_id2val.val AS iri_grafo_por_defecto
	FROM
	onto_st.st_g2t
	INNER JOIN onto_st.st_id2val ON (onto_st.st_id2val.id = onto_st.st_g2t.g)
	WHERE
	onto_st.st_g2t.g = 1
	*/
	
	// Configuración de la base de datos de patrones de soporte técnico
	$GLOBALS['SIGECOST_CFG']["dbType"] = "<Tipo de manejador de base de datos>"; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbType"] = 'mysql'
	$GLOBALS['SIGECOST_CFG']["dbEncoding"] = "<Codificación de caracteres de base de datos>"; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbEncoding"] = "UTF8";
	$GLOBALS['SIGECOST_CFG']["dbServer"] = "<Servidor de base de datos>"; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbServer"] = "localhost";
	$GLOBALS['SIGECOST_CFG']["dbPort"] = '<Puerto del servidor de base de datos>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbPort"] = '3306';
	$GLOBALS['SIGECOST_CFG']["dbUser"] = "<Usuario de la base de datos>"; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbUser"] = "root";
	$GLOBALS['SIGECOST_CFG']["dbPass"] = "<Password de la base de datos>"; // Ejemplo: $GLOBALS['SIGECOST_CFG']["dbPass"] = "123456789";
	$GLOBALS['SIGECOST_CFG']["dbDatabase"] = "<Nombre de la base de datos>"; // $GLOBALS['SIGECOST_CFG']["dbDatabase"] = "patrones_st";
	$GLOBALS['SIGECOST_CFG']["tablePrefix"] = "";
	$GLOBALS['SIGECOST_CFG']["characterSet"] = "UTF-8";
	$GLOBALS['SIGECOST_CFG']["timeZone"] = "+0:00";
	$GLOBALS['SIGECOST_CFG']["displayTimeZone"] = "-04:30";
	
	// Configuración de la base de datos de arc2 (Ontología)
	$GLOBALS['SIGECOST_CFG']["ontoDbServer"] = '<Servidor de base de datos de la ontología>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["ontoDbServer"] = $GLOBALS['SIGECOST_CFG']["dbServer"];
	$GLOBALS['SIGECOST_CFG']["ontoDbUser"] = '<Usuario de la base de datos de la ontología>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["ontoDbUser"] = $GLOBALS['SIGECOST_CFG']["dbUser"]
	$GLOBALS['SIGECOST_CFG']["ontoDbPass"] = '<Password de la base de datos de la ontología>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["ontoDbPass"] = $GLOBALS['SIGECOST_CFG']["dbPass"];
	$GLOBALS['SIGECOST_CFG']["ontoDbDatabase"] = '<Nombre de la base de datos de la ontología>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["ontoDbDatabase"] = "onto_st";
	$GLOBALS['SIGECOST_CFG']["StoreName"] = '<Store name de la base de datos de la ontología (Prefijo)>'; // Ejemplo: $GLOBALS['SIGECOST_CFG']["StoreName"] = "st";
	
	// Array de configuración de la base de datos de arc2
	$GLOBALS['SIGECOST_CFG']["ontoArc2Config"] = array(
		// Configuración de la base de datos MySQL
		'db_host' => '<Servidor de base de datos de la ontología>', // Ejemplo: 'db_host' => $GLOBALS['SIGECOST_CFG']["ontoDbServer"],
		'db_user' => '<Usuario de la base de datos de la ontología>', // Ejemplo: 'db_user' => $GLOBALS['SIGECOST_CFG']["ontoDbUser"], 
		'db_pwd' => '<Password de la base de datos de la ontología>', // Ejemplo: 'db_pwd' => $GLOBALS['SIGECOST_CFG']["dbPass"], 
		'db_name' => $GLOBALS['SIGECOST_CFG']["ontoDbDatabase"], // Ejemplo: 'db_name' => $GLOBALS['SIGECOST_CFG']["ontoDbDatabase"], 
		// Configuración del arc2 store
		'store_name' => '<Store name de la base de datos de la ontología (Prefijo)>',  // Ejemplo: 'store_name' => $GLOBALS['SIGECOST_CFG']["StoreName"],
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
	$GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSoporteTecnicoAdministrador"] = 40;
	$GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSoporteTecnico"] = $GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSoporteTecnicoAdministrador"] + 20;
	$GLOBALS['SIGECOST_CFG']["truncamientoSolucionPatronSTBusqueda"] = 200;
	
	require_once ("myconfig.php");
?>