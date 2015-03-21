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
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionVerDetalles.php' );
			break;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO:
?>
			<div class="page-header">
				<h1>
					<small>Corregir cierre inesperado de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Desinstalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimaticaVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimaticaVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO:
?>
			<div class="page-header">
				<h1>
					<small>Reestablecer las barras de herramientas, funci&oacute;n, formato y/o dibujo de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA:
?>
			<div class="page-header">
				<h1>
					<small>Corregir impresi&oacute;n manchada</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/corregirImpresionManchadaVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL:
?>
			<div class="page-header">
				<h1>
					<small>Desatascar papel en impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraVerDetalles.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA:
?>
			<div class="page-header">
				<h1>
					<small>Reparar impresi&oacute;n corrida</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/repararImpresionCorridaVerDetalles.php' );
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