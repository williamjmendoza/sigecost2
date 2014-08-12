<?php

	define('FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR', 1);
	define('FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR', 2);
	define('FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR', 3);
	define('FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR', 4);
		
	$GLOBALS['Safi']['__Forms']['__List'] = array();
	
	$GLOBALS['Sigecost']['__Forms']['__Config'] = array(
		FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/escaner.php',
			'ClassName' => 'FormularioInstanciaETEscaner',
			'GlobalName' => 'ClassFormularioInstanciaETEscaner'
		),
		FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/impresora.php',
			'ClassName' => 'FormularioInstanciaETImpresora',
			'GlobalName' => 'ClassFormularioInstanciaETImpresora'
		),			
		FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/sistemaOperativo.php',
			'ClassName' => 'FormularioInstanciaETSistemaOperativo',
			'GlobalName' => 'ClassFormularioInstanciaETSistemaOperativo'
		),
		FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/impresora/corregirImpresionManchada.php',
			'ClassName' => 'FormularioInstanciaSTImpresoraCorregirImpresionManchada',
			'GlobalName' => 'ClassFormularioInstanciaSTImpresoraCorregirImpresionManchada'
		),
	);

?>