<?php

define('FORM_ET_EQUIPO_REPRODUCCION', 1);
define('FORM_ST_IMPRESORA', 2);

$GLOBALS['Safi']['__Forms']['__List'] = array();

$GLOBALS['Safi']['__Forms']['__Config'][FORM_ET_EQUIPO_REPRODUCCION] = array(
	'File' => 'instancia/elementoTecnologico/equipoReproduccion.php',
	'ClassName' => 'FormularioETEquipoReproduccion',
	'GlobalName' => 'ClassFormularioETEquipoReproduccion'
);
$GLOBALS['Safi']['__Forms']['__Config'][FORM_ST_IMPRESORA] = array(
		'File' => 'instancia/soporteTecnico/impresora.php',
		'ClassName' => 'FormularioSTImpresora',
		'GlobalName' => 'ClassFormularioSTImpresora'
);
?>