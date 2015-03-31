<?php

	$datosInstancia = isset($GLOBALS['SigecostRequestVars']['datosInstancia']) ? $GLOBALS['SigecostRequestVars']['datosInstancia'] : false;
	$iriClaseST = is_array($datosInstancia) ? $datosInstancia['iriClaseST'] : null;

	switch ($iriClaseST)
	{
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
?>
			<div class="page-header">
				<h1>
					<small>Desinstalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</small>
				</h1>
			</div>
<?php			
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionDetallesEnBusqueda.php' );
			break;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO:
?>
			<div class="page-header">
				<h1>
					<small>Corregir el cierre inesperado de una aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Desinstalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimaticaDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimaticaDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO:
?>
			<div class="page-header">
				<h1>
					<small>Restablecer las barras herramientas funci&oacute;n, formato y/o dibujo en aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA:
?>
			<div class="page-header">
				<h1>
					<small>Corregir impresi&oacute;n manchada</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/corregirImpresionManchadaDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL:
?>
			<div class="page-header">
				<h1>
					<small>Desatascar el papel en una impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraDetallesEnBusqueda.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA:
?>
			<div class="page-header">
				<h1>
					<small>Reparar impresi&oacute;n corrida</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/repararImpresionCorridaDetallesEnBusqueda.php' );
			breaK;

		default:
?>
			<div class="page-header">
				<h1>
					<small>Vista no disponible</small>
				</h1>
			</div>
<?php
			break;
	}
?>