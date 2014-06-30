<?php

	require_once (dirname(__FILE__) . "/../init.php");
	require_once (SIGECOST_CONTROLADOR_PATH . "/ajaxControlador.php");

	class AjaxTmpControlador extends AjaxControlador 
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function getSoporteTecnicoSubclasses(){
	
			$query = '
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
					
			  		SELECT DISTINCT
						?subject ?label
					WHERE {
						?subject rdf:type owl:Class .
						?subject rdfs:subClassOf kb:SoporteTecnico .
						?subject rdfs:label ?label
					}
				';
			
			
			$datos = array();
			
			$r = '';
			
			$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
			
			if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
				error_log("arc2sparql error:\n" . join("\n", $errors));
				echo "arc2sparql error:\n" . join("\n", $errors);
			} else {
				if($rows){
					
					foreach ($rows as $row)
					{
						$datos[] = array('iri' => $row['subject'], 'label' => $row['label']);
					}
					
					header("Content-Type: application/json; charset=" . GetConfig('CharacterSet'));
					
					echo json_encode(array('datos' => $datos));
					/*
					echo "<pre>";
					print_r($datos);
					echo "</pre>";
					*/
					
					/*
					$r = '<table border=1> <th>N</th><th>Subject</th><th>Property</th><th>Object</th>'."\n";
					$count = 0;
					foreach ($rows as $row) {
						$r .= '<tr><td>'.(++$count).'</td><td>'.$row['subject'] .  '</td><td>'.$row['property'] . '</td><td>'.$row['object'] . '</td></tr>'."\n";
					}
					$r .='</table>'."\n";
					*/
					
				} else {
					$r = '<em>No data returned</em>';
				}
			}
			
			echo $r;
				
		}
		
	}
	
	new AjaxTmpControlador();
?>