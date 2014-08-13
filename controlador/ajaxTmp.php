<?php

	require_once (dirname(__FILE__) . "/../init.php");
	require_once (SIGECOST_PATH_CONTROLADOR . "/ajaxControlador.php");

	class AjaxTmpControlador extends AjaxControlador 
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function getSubclassesOf(){
			
			$datos = array();
			
			if(isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != '' ){
				if($iri != "0"){
					// Llamada al modelo
					$query = '
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				
				  		SELECT DISTINCT
							?iri ?label
						WHERE {
							?iri rdf:type owl:Class .
							?iri rdfs:subClassOf <' . $iri . '> .
							?iri rdfs:label ?label
						}
						ORDER BY ?iri
					';
					
					$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
						
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
						/*
						 error_log("arc2sparql error:\n" . join("\n", $errors));
						echo "arc2sparql error:\n" . join("\n", $errors);
						*/
						// Manejar el error e indicar al ajax
					} else {
						if($rows){
								
							foreach ($rows as $row)
							{
								$datos[] = array('iri' => $row['iri'], 'label' => $row['label']);
							}
						} else {
							// Indicar que no se encontraron resultados
						}
					}
				}
				$GLOBALS['SigecostRequestVars']['datos'] = $datos;
			}
			
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
				
		}
		
		function getInstancesSTEquipoReproduccion ()
		{
			$datos = array();
			
			if(isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != '' ){
			
				if ($iri == "http://www.owl-ontologies.com/OntologySoporteTecnico.owl#InstalacionImpresora")
				{
					// Llamada al modelo
					
					$query = "
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						
						SELECT DISTINCT
							?marcaEquipoReproduccion ?modeloEquipoReproduccion ?nombreSistemaOperativo ?versionSistemaOperativo
						WHERE {
							?impresora rdf:type <". $iri ."> .
							?impresora kb:enImpresora ?enImpresora .
							?enImpresora kb:marcaEquipoReproduccion ?marcaEquipoReproduccion .	
							?enImpresora kb:modeloEquipoReproduccion ?modeloEquipoReproduccion .
							?sistemaOperativo kb:sobreSistemaOperativo ?sobreSistemaOperativo .
							?sobreSistemaOperativo kb:nombreSistemaOperativo ?nombreSistemaOperativo .
							?sobreSistemaOperativo kb:versionSistemaOperativo ?versionSistemaOperativo .
						}
					 	ORDER BY
							?marcaEquipoReproduccion ?modeloEquipoReproduccion ?nombreSistemaOperativo ?versionSistemaOperativo
					";
					
				}
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
					
					//error_log("arc2sparql error:\n" . join("\n", $errors));
					//echo "arc2sparql error:\n" . join("\n", $errors);
					
					// Manejar el error e indicar al ajax
				} else {
					
					if($rows){
					
						foreach ($rows as $row)
						{
							$datos[] = array(
								'marcaEquipoReproduccion' => $row['marcaEquipoReproduccion'],
								'modeloEquipoReproduccion' => $row['modeloEquipoReproduccion'],
								'nombreSistemaOperativo' => $row['nombreSistemaOperativo'],
								'versionSistemaOperativo' => $row['versionSistemaOperativo']
							);
						}
					} else {
						// Indicar que no se encontraron resultados
					}
				}
			}
			
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
		}
		
		function getInstancesSTEquipoReproduccionImpresoras ()
		{
			$datos = array();
				
			if(isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != '' ){
					
				if ($iri == "http://www.owl-ontologies.com/OntologySoporteTecnico.owl#InstalacionImpresora")
				{
					// Llamada al modelo
						
					$query = "
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
		
						SELECT DISTINCT
							?marcaEquipoReproduccion
						WHERE {
							?impresora rdf:type <". $iri ."> .
							?impresora kb:enImpresora ?enImpresora .
							?enImpresora kb:marcaEquipoReproduccion ?marcaEquipoReproduccion .
						}
					 	ORDER BY
							?marcaEquipoReproduccion
					";
						
				}
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
						
					//error_log("arc2sparql error:\n" . join("\n", $errors));
					//echo "arc2sparql error:\n" . join("\n", $errors);
						
					// Manejar el error e indicar al ajax
				} else {
						
					if($rows){
							
						foreach ($rows as $row)
						{
							$datos[] = array(
									'marcaEquipoReproduccion' => $row['marcaEquipoReproduccion']
							);
						}
					} else {
						// Indicar que no se encontraron resultados
					}
				}
			}
				
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
		}
		
		function getInstancesSTEquipoReproduccionModelo ()
		{
			$datos = array();
		
			if(
				isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != ''
				&& isset($_REQUEST["marca"]) && ($marca = trim($_REQUEST["marca"])) != ''
			){
					
				
				// Llamada al modelo
	
				$query = "
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
	
					SELECT DISTINCT
						?modeloEquipoReproduccion
					WHERE {
						?impresora rdf:type <". $iri ."> .
						?impresora kb:enImpresora ?enImpresora .
						?enImpresora kb:marcaEquipoReproduccion \"".$marca."\" .
						?enImpresora kb:modeloEquipoReproduccion ?modeloEquipoReproduccion .
					}
				 	ORDER BY
						?modeloEquipoReproduccion
				";
	
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
		
					//error_log("arc2sparql error:\n" . join("\n", $errors));
					//echo "arc2sparql error:\n" . join("\n", $errors);
		
					// Manejar el error e indicar al ajax
				} else {
		
					if($rows){
							
						foreach ($rows as $row)
						{
							$datos[] = array(
									'modeloEquipoReproduccion' => $row['modeloEquipoReproduccion']
							);
						}
					} else {
						// Indicar que no se encontraron resultados
					}
				}
			}
		
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
		}
		
		
		function getInstancesSTEquipoReproduccionSONombre ()
		{
			$datos = array();
		
			if(
					isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != ''
					&& isset($_REQUEST["marca"]) && ($marca = trim($_REQUEST["marca"])) != ''
					&& isset($_REQUEST["modelo"]) && ($modelo = trim($_REQUEST["modelo"])) != ''
			){
					
		
				// Llamada al modelo
		
				$query = "
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
		
					SELECT DISTINCT
						?nombreSistemaOperativo
					WHERE {
						?impresora rdf:type <". $iri ."> .
						?impresora kb:enImpresora ?enImpresora .
						?enImpresora kb:marcaEquipoReproduccion \"".$marca."\" .
						?enImpresora kb:modeloEquipoReproduccion \"".$modelo."\" .
						?sistemaOperativo kb:sobreSistemaOperativo ?sobreSistemaOperativo .
						?sobreSistemaOperativo kb:nombreSistemaOperativo ?nombreSistemaOperativo .
					}
				 	ORDER BY
						?nombreSistemaOperativo
				";
		
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
		
					//error_log("arc2sparql error:\n" . join("\n", $errors));
					//echo "arc2sparql error:\n" . join("\n", $errors);
		
					// Manejar el error e indicar al ajax
				} else {
		
					if($rows){
							
						foreach ($rows as $row)
						{
							$datos[] = array(
									'nombreSistemaOperativo' => $row['nombreSistemaOperativo']
							);
						}
					} else {
						// Indicar que no se encontraron resultados
					}
				}
			}
		
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
		}
		
		function getInstancesSTEquipoReproduccionSOVersion ()
		{
			$datos = array();
			
			if(
					isset($_REQUEST["iri"]) && ($iri = trim($_REQUEST["iri"])) != ''
					&& isset($_REQUEST["marca"]) && ($marca = trim($_REQUEST["marca"])) != ''
					&& isset($_REQUEST["modelo"]) && ($modelo = trim($_REQUEST["modelo"])) != ''
					&& isset($_REQUEST["nombreSO"]) && ($nombreSO = trim($_REQUEST["nombreSO"])) != ''
			){
		
				// Llamada al modelo
		
				$query = "
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
		
					SELECT DISTINCT
						?versionSistemaOperativo
					WHERE {
						?impresora rdf:type <". $iri ."> .
						?impresora kb:enImpresora ?enImpresora .
						?enImpresora kb:marcaEquipoReproduccion \"".$marca."\" .
						?enImpresora kb:modeloEquipoReproduccion \"".$modelo."\" .
						?sistemaOperativo kb:sobreSistemaOperativo ?sobreSistemaOperativo .
						?sobreSistemaOperativo kb:nombreSistemaOperativo \"".$nombreSO."\" .
						?sobreSistemaOperativo kb:versionSistemaOperativo ?versionSistemaOperativo .
						
					}
				 	ORDER BY
						?versionSistemaOperativo
				";
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
		
					error_log("arc2sparql error:\n" . join("\n", $errors));
					//echo "arc2sparql error:\n" . join("\n", $errors);
		
					// Manejar el error e indicar al ajax
				} else {
		
					if($rows){
							
						foreach ($rows as $row)
						{
							$datos[] = array(
									'versionSistemaOperativo' => $row['versionSistemaOperativo']
							);
						}
					} else {
						// Indicar que no se encontraron resultados
					}
				}
			}
		
			header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
			echo json_encode(array('datos' => $datos));
		}
	}
	
	
	
	/*
	// Código para imprimir un resul set de arc2
	
	$vars = $result['result']['variables'];
	$rows = $result['result']['rows'];
	
	echo "<table><tr>";
	foreach ($vars as $var) {
		echo "<td>" . $var . "</td>"; 
	}
	echo "</tr>";
	foreach ($rows as $row) {
		echo "<tr>";
		foreach ($vars as $var) {
			echo "<td>" . $row[$var] . "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	*/
	
	new AjaxTmpControlador();
?>