<?php

define('FORM_ET_IMPRESORA', 1);
define('FORM_ST_IMPRESORA', 2);

$GLOBALS['Safi']['__Forms']['__List'] = array();

$GLOBALS['Sigecost']['__Forms']['__Config'][FORM_ET_IMPRESORA_INSERTAR_MODIFICAR] = array(
	'File' => 'instancia/elementoTecnologico/impresora.php',
	'ClassName' => 'FormularioETImpresora',
	'GlobalName' => 'ClassFormularioETImpresora'
);
$GLOBALS['Sigecost']['__Forms']['__Config'][FORM_ST_IMPRESORA] = array(
		'File' => 'instancia/soporteTecnico/impresora.php',
		'ClassName' => 'FormularioSTImpresora',
		'GlobalName' => 'ClassFormularioSTImpresora'
);
?>