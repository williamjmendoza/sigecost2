<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	class ModeloBuscar
	{
		public static function buscar(array $parametros = null)
		{
			$preMsg = 'Error al buscar patrones en la ontología';
			$instancias = null;
			
			try
			{
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es un arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if (isset($parametros['clave']) && ($clave = $parametros['clave']) != "")
				{
					
					
					/*
					$graph->setPrefix('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
					$graph->setPrefix('protege', 'http://protege.stanford.edu/plugins/owl/protege#');
					$graph->setPrefix('xsp', 'http://www.owl-ontologies.com/2005/08/07/xsp.owl#');
					$graph->setPrefix('owl', 'http://www.w3.org/2002/07/owl#');
					$graph->setPrefix('xsd', 'http://www.w3.org/2001/XMLSchema#');
					$graph->setPrefix('swrl', 'http://www.w3.org/2003/11/swrl#');
					$graph->setPrefix('swrlb', 'http://www.w3.org/2003/11/swrlb#');
					$graph->setPrefix('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
					$graph->setPrefix('base', 'http://www.owl-ontologies.com/OntologySoporteTecnico.owl#');
					*/
					
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
					
						SELECT
							?instanciaST ?claseST ?urlSoporteTecnico ?claseET ?instanciaET ?propiedad ?objeto
						WHERE
						{
							?instanciaST rdf:type ?claseST .
							?instanciaST :uRLSoporteTecnico ?urlSoporteTecnico .
							?instanciaST ?IntanciaST_ET ?instanciaET .
							?claseET rdf:type owl:Class .
							?instanciaET rdf:type ?claseET .
							?instanciaET ?propiedad ?objeto .
							FILTER regex(?objeto, "'.$clave.'"^^xsd:string,  "i") .
							FILTER isLiteral(?objeto) .
						}
						LIMIT
							10
					';
					
					// Borrar
					//error_log($query);
					
					$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
					if (is_array($rows) && count($rows) > 0){
						$instancias = array();
						foreach ($rows AS $row){
							/*
							$instancias[] = array(
								'iri' => $row['iri'],
								'urlSoporteTecnico' => $row['urlSoporteTecnico']
							);
							*/
							$instancias[] = $row;
						}
					}
					
					// Borrar
					error_log(print_r($instancias, true));
					
					// FILTER (?iri = <'.$instancia->getIri().'>) .
					
				}
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
?>