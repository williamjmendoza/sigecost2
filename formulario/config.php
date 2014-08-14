<?php

	define('FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO', 1);
	define('FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR', 2);
	define('FORM_INSTANCIA_ET_FOTOCOPIADORA_INSERTAR_MODIFICAR', 3);
	define('FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR', 4);
	define('FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR', 5);
	define('FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR', 6);
	define('FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR', 7);
	define('FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_INSERTAR_MODIFICAR', 8);
	define('FORM_INSTANCIA_ST_IMPRESORA_REPARAR_IMPRESION_CORRIDA_INSERTAR_MODIFICAR', 9);
		
	$GLOBALS['Safi']['__Forms']['__List'] = array();
	
	$GLOBALS['Sigecost']['__Forms']['__Config'] = array(
		FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO => array(
			'File' => 'instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php',
			'ClassName' => 'FormularioInstanciaETAplicacionGraficaDigitalDibujoDiseno',
			'GlobalName' => 'ClassFormularioInstanciaETAplicacionGraficaDigitalDibujoDiseno'
		),
		FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/escaner.php',
			'ClassName' => 'FormularioInstanciaETEscaner',
			'GlobalName' => 'ClassFormularioInstanciaETEscaner'
		),
		FORM_INSTANCIA_ET_FOTOCOPIADORA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/fotocopiadora.php',
			'ClassName' => 'FormularioInstanciaETFotocopiadora',
			'GlobalName' => 'ClassFormularioInstanciaETFotocopiadora'
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
		FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/impresora/desatascarPapel.php',
			'ClassName' => 'FormularioInstanciaSTImpresoraDesatascarPapel',
			'GlobalName' => 'ClassFormularioInstanciaSTImpresoraDesatascarPapel'
		),
		FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/impresora/instalacionImpresora.php',
			'ClassName' => 'FormularioInstanciaSTImpresoraInstalacionImpresora',
			'GlobalName' => 'ClassFormularioInstanciaSTImpresoraInstalacionImpresora'
		),
		FORM_INSTANCIA_ST_IMPRESORA_REPARAR_IMPRESION_CORRIDA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/impresora/repararImpresionCorrida.php',
			'ClassName' => 'FormularioInstanciaSTImpresoraRepararImpresionCorrida',
			'GlobalName' => 'ClassFormularioInstanciaSTImpresoraRepararImpresionCorrida'
		),
	);

?>