<?php

	// Librerías
	require_once ( SIGECOST_PATH_LIB . '/definiciones.php' );

	
	$GLOBALS['SIGECOST_VAO'] = array(
		// Elementos tecnológicos
		'ET' => array(
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO 
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_APLICACION_OFIMATICA
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionOfimatica.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionOfimatica.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_APLICACION_PRODUCCION_AUDIOVISUAL_MUSICA
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/computadorEscritorio.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/computadorEscritorio.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/computadorPortatil.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/computadorPortatil.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_CONSUMIBLE
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/consumible.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/consumible.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_ESCANER
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/escaner.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/escaner.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_FOTOCOPIADORA
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/fotocopiadora.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/fotocopiadora.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_IMPRESORA
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/impresora.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/impresora.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO
				=> array (
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/sistemaOperativo.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/elementoTecnologico/sistemaOperativo.php?accion=buscar'
				)
		),
		// Soporte técnico
		'ST' => array(
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDD.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDD.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimatica.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimatica.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimatica.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimatica.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/corregirImpresionManchada.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/corregirImpresionManchada.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/desatascarPapel.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/desatascarPapel.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/instalacionImpresora.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/instalacionImpresora.php?accion=buscar'
				),
			SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA
				=> array(
					'insertar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php?accion=insertar',
					'buscar' => SIGECOST_PATH_URL_CONTROLADOR . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php?accion=buscar'
				)
		)
	);
	
?>