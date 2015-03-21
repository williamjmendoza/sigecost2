<?php

	$iriClaseST = $GLOBALS['SigecostRequestVars']['iriClaseST'];

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
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionDesplegar.php' );
			break;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO:
?>
			<div class="page-header">
				<h1>
					<small>Corregir cierre inesperado de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Desinstalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimaticaDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimaticaDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO:
?>
			<div class="page-header">
				<h1>
					<small>Reestablecer las barras de herramientas, funci&oacute;n, formato y/o dibujo de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA:
?>
			<div class="page-header">
				<h1>
					<small>Corregir impresi&oacute;n manchada</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/corregirImpresionManchadaDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL:
?>
			<div class="page-header">
				<h1>
					<small>Desatascar papel en impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA:
?>
			<div class="page-header">
				<h1>
					<small>Instalaci&oacute;n de impresora</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraDesplegar.php' );
			breaK;
			
		case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA:
?>
			<div class="page-header">
				<h1>
					<small>Reparar impresi&oacute;n corrida</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/repararImpresionCorridaDesplegar.php' );
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