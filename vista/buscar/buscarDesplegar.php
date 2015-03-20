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
					<small>Reestablecer barra de herramientas, funci&oacute;n, formato y dibujo de aplicaci&oacute;n ofim&aacute;tica</small>
				</h1>
			</div>
<?php
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDDesplegar.php' );
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
