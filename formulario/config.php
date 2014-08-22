<?php

	define('FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR', 1);
	define('FORM_INSTANCIA_ET_APLICACION_OFIMATICA_INSERTAR_MODIFICAR', 2);
	define('FORM_INSTANCIA_ET_APLICACION_PRODUCCION_AUDIOVISUAL_MUSICA_INSERTAR_MODIFICAR', 3);
	define('FORM_INSTANCIA_ET_APLICACION_REPRODUCCION_SONIDO_VIDEO_INSERTAR_MODIFICAR', 4);
	define('FORM_INSTANCIA_ET_BARRA_DIBUJO_INSERTAR_MODIFICAR', 5);
	define('FORM_INSTANCIA_ET_BARRA_FORMATO_INSERTAR_MODIFICAR', 6);
	define('FORM_INSTANCIA_ET_COMPUTADOR_ESCRITORIO_INSERTAR_MODIFICAR', 7);
	define('FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR', 8);
	define('FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR', 5);
	define('FORM_INSTANCIA_ET_FOTOCOPIADORA_INSERTAR_MODIFICAR', 6);
	define('FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR', 7);
	define('FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR', 8);
	define('FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR', 9);
	define('FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR', 10);
	define('FORM_INSTANCIA_ST_APLICACION_OFIMATICA_CORREGIR_CIERRE_INESPERADO_INSERTAR_MODIFICAR', 11);
	define('FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR', 12);
	define('FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR', 13);
	define('FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_BUSCAR', 14);
	define('FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_INSERTAR_MODIFICAR', 15);
	define('FORM_INSTANCIA_ST_IMPRESORA_REPARAR_IMPRESION_CORRIDA_INSERTAR_MODIFICAR', 16);
	define('FORM_INSTANCIA_ET_BARRA_DIBUJO_INSERTAR_MODIFICAR', 17);
	define('FORM_INSTANCIA_ET_BARRA_FORMATO_INSERTAR_MODIFICAR', 18);
	define('FORM_INSTANCIA_ET_COMPUTADOR_ESCRITORIO_INSERTAR_MODIFICAR', 19);
	define('FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR', 20);
	define('FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR', 21);
	define('FORM_INSTANCIA_ET_FOTOCOPIADORA_INSERTAR_MODIFICAR', 22);
	define('FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR', 23);
	define('FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR', 24);
	define('FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR', 25);
	define('FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR', 26);
	define('FORM_INSTANCIA_ST_APLICACION_OFIMATICA_CORREGIR_CIERRE_INESPERADO_INSERTAR_MODIFICAR', 27);
	define('FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR', 28);
	define('FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR', 29);
	define('FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_INSERTAR_MODIFICAR', 30);
	define('FORM_INSTANCIA_ST_IMPRESORA_REPARAR_IMPRESION_CORRIDA_INSERTAR_MODIFICAR', 31);

	$GLOBALS['Safi']['__Forms']['__List'] = array();

	$GLOBALS['Sigecost']['__Forms']['__Config'] = array(
		FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php',
			'ClassName' => 'FormularioInstanciaETAplicacionGraficaDigitalDibujoDiseno',
			'GlobalName' => 'ClassFormularioInstanciaETAplicacionGraficaDigitalDibujoDiseno'
		),
		FORM_INSTANCIA_ET_APLICACION_OFIMATICA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/aplicacionOfimatica.php',
			'ClassName' => 'FormularioInstanciaETAplicacionOfimatica',
			'GlobalName' => 'ClassFormularioInstanciaETAplicacionOfimatica'
		),
		FORM_INSTANCIA_ET_APLICACION_PRODUCCION_AUDIOVISUAL_MUSICA_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php',
			'ClassName' => 'FormularioInstanciaETAplicacionProduccionAudiovisualMusica',
			'GlobalName' => 'ClassFormularioInstanciaETAplicacionProduccionAudiovisualMusica'
		),
		FORM_INSTANCIA_ET_APLICACION_REPRODUCCION_SONIDO_VIDEO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php',
			'ClassName' => 'FormularioInstanciaETAplicacionReproduccionSonidoVideo',
			'GlobalName' => 'ClassFormularioInstanciaETAplicacionReproduccionSonidoVideo'
		),
		FORM_INSTANCIA_ET_BARRA_DIBUJO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/barraDibujo.php',
			'ClassName' => 'FormularioInstanciaETBarraDibujo',
			'GlobalName' => 'ClassFormularioInstanciaETBarraDibujo'
		),
		FORM_INSTANCIA_ET_BARRA_FORMATO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/barraFormato.php',
			'ClassName' => 'FormularioInstanciaETBarraFormato',
			'GlobalName' => 'ClassFormularioInstanciaETBarraFormato'
		),
		FORM_INSTANCIA_ET_COMPUTADOR_ESCRITORIO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/computadorEscritorio.php',
			'ClassName' => 'FormularioInstanciaETcomputadorEscritorio',
			'GlobalName' => 'ClassFormularioInstanciaETcomputadorEscritorio'
		),
		FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/computadorPortatil.php',
			'ClassName' => 'FormularioInstanciaETcomputadorPortatil',
			'GlobalName' => 'ClassFormularioInstanciaETcomputadorPortatil'
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
		FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/consumible.php',
			'ClassName' => 'FormularioInstanciaETConsumible',
			'GlobalName' => 'ClassFormularioInstanciaETConsumible'
		),
		FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/elementoTecnologico/sistemaOperativo.php',
			'ClassName' => 'FormularioInstanciaETSistemaOperativo',
			'GlobalName' => 'ClassFormularioInstanciaETSistemaOperativo'
		),
		FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php',
			'ClassName' => 'FormularioInstanciaSTAplicacionGDDDDesinstalacionAplicacion',
			'GlobalName' => 'ClassFormularioInstanciaSTAplicacionGDDDDesinstalacionAplicacion'
		),
		FORM_INSTANCIA_ST_APLICACION_OFIMATICA_CORREGIR_CIERRE_INESPERADO_INSERTAR_MODIFICAR => array(
			'File' => 'instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php',
			'ClassName' => 'FormularioInstanciaSTAplicacionOfimaticaCorregirCierreInesperado',
			'GlobalName' => 'ClassFormularioInstanciaSTAplicacionOfimaticaCorregirCierreInesperado'
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
		FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_BUSCAR => array(
			'File' => 'instancia/soporteTecnico/impresora/instalacionImpresoraBuscar.php',
			'ClassName' => 'FormularioInstanciaSTImpresoraInstalacionImpresoraBuscar',
			'GlobalName' => 'ClassFormularioInstanciaSTImpresoraInstalacionImpresoraBuscar'
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
