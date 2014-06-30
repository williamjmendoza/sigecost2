<?php

	echo "No hace nada";
	echo exit;

	require_once (dirname(__FILE__) . "/../init.php");
	
	$store->query('LOAD <http://localhost/sigecost2/controlador/soporte_tecnico.owl>');
	
	if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
		//error_log("arc2sparql error:\n" . join("\n", $errors));
		echo "arc2sparql error:\n" . join("\n", $errors);
	} else {
		echo "Ontología cargada exitosamente.";
	}

?>
