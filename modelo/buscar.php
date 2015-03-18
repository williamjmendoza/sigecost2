<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	class ModeloBuscar
	{
		// Función recursiva de Anibal para construir e árbol
		public static function consultar_clases($clase,$store)
		{
		
		
			$q =' 	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX kb: <http://protege.stanford.edu/kb#>
				SELECT ?subclase ?label
				WHERE { 	?subclase rdfs:subClassOf kb:' . $clase . ' .
							?subclase rdfs:label ?label .
		}
			';
		
			$rows = $store->query($q, 'rows');
			$r = '';
			$h = '';
			if ($rows = $store->query($q, 'rows')) {
		
				$params = array();
		
				foreach ($rows as $row) {
		
					$x = consultar_clases($row['label'],$store);
					$params[$row['label']] = $x != false?  $x : array();
					 
		
		
				}
		
				return $params;
			}
				
			else{
				return false;
			}
				
		}
		
		public static function sn2()
		{
			return self::sn(SIGECOST_IRI_ONTOLOGIA_NUMERAL."SoporteTecnico");
		}
		
		public static function sn($iriPadre, $contador = 1, &$datos = array())
		{
			$preMsg = 'Error al buscar patrones en la ontología';
			$hijos = null;
				
			try
			{
				if ($iriPadre === null)
					throw new Exception($preMsg . ' El parámetro \'$iriPadre\' es nulo.');
			
				if ( ($iriPadre = trim($iriPadre)) == '')
					throw new Exception($preMsg . ' El parámetro \'$iriPadre\' está vacío.');
			
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			
					SELECT
						?clasePadre ?claseHijo
					WHERE
					{
						?clasePadre rdf:type owl:Class .
						?claseHijo rdfs:subClassOf ?clasePadre .
						?claseHijo rdf:type owl:Class .
						FILTER (?clasePadre = <'.$iriPadre.'>)
					}
					ORDER BY
						?clasePadre ?claseHijo
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
				if (is_array($rows) && count($rows) > 0){
					$hijos = array();
					foreach ($rows AS $row){
						/*
						 $instancias[] = array(
						 		'iri' => $row['iri'],
						 		'urlSoporteTecnico' => $row['urlSoporteTecnico']
						 );
						*/
						$hijos[] = $row;
					}
				}
				
				return $hijos;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
			
		}
		
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
					
					
					// Query de búsquedas en instancias
					/*
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
					
						SELECT
							?claseST ?labelClaseST ?instanciaST ?urlSoporteTecnico ?claseET ?instanciaET ?propiedadET ?labelPropiedadET ?objeto
						WHERE
						{
							?claseST rdfs:label ?labelClaseST .
							?instanciaST rdf:type ?claseST .
							?instanciaST :uRLSoporteTecnico ?urlSoporteTecnico .
							?instanciaST ?IntanciaST_ET ?instanciaET .
							?claseET rdf:type owl:Class .
							?instanciaET rdf:type ?claseET .
							?propiedadET rdfs:label ?labelPropiedadET .
							?instanciaET ?propiedadET ?objeto .
							FILTER (
								regex(?objeto, "'.$clave.'"^^xsd:string,  "i") ||	# Filtro para instancia
								regex(?labelClaseST, "'.$clave.'"^^xsd:string,  "i") || # Filtro para clase
								regex(?labelPropiedadET, "'.$clave.'"^^xsd:string,  "i") # Filtro para propiedad de datos
							) .
							FILTER isLiteral(?objeto) .
						}
						LIMIT
							20
					';
					*/
					
					// Query de búsquedas en instancias
					/*
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			
						SELECT
							?claseST ?labelClaseST ?instanciaST ?urlSoporteTecnico ?claseET ?instanciaET ?propiedadET ?labelPropiedadET ?objeto
						WHERE
						{
							?claseST rdfs:label ?labelClaseST .
							?instanciaST rdf:type ?claseST .
							?instanciaST :uRLSoporteTecnico ?urlSoporteTecnico .
							?instanciaST ?IntanciaST_ET ?instanciaET .
							?claseET rdf:type owl:Class .
							?instanciaET rdf:type ?claseET .
							?propiedadET rdfs:label ?labelPropiedadET .
							?instanciaET ?propiedadET ?objeto .
							FILTER (
								regex(?objeto, "'.$clave.'"^^xsd:string,  "i") ||	# Filtro para instancia
								regex(?labelClaseST, "'.$clave.'"^^xsd:string,  "i") || # Filtro para clase
								regex(?labelPropiedadET, "'.$clave.'"^^xsd:string,  "i") # Filtro para propiedad de datos
							) .
							FILTER isLiteral(?objeto) .
						}
						LIMIT
							20
					';
					*/
					
					// Query de búsquedas en clases
					/*
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			
						SELECT DISTINCT
							?claseNivel1 ?claseNivel2 ?claseNivel3 ?claseNivel4
						WHERE
						{
							?claseNivel1 rdfs:subClassOf :SoporteTecnico .
							OPTIONAL {
								?claseNivel2 rdfs:subClassOf ?claseNivel1 .
								OPTIONAL {
									?claseNivel3 rdfs:subClassOf ?claseNivel2 .
									OPTIONAL { ?claseNivel4 rdfs:subClassOf ?claseNivel3 . } .
								} .
							} .
							
							#OPTIONAL { ?claseNivel4 rdfs:subClassOf ?claseNivel3 . } .
							
								
								
							#FILTER (?clasePadre = :SoporteTecnico ) .
						}
						
					';
					*/
					// Query para obtener el árbol de soporte técnico
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
						
						SELECT
							?clasePadre ?claseHijo
						WHERE
						{
							?clasePadre rdf:type owl:Class .
							?claseHijo rdfs:subClassOf ?clasePadre .
							?claseHijo rdf:type owl:Class .
							
						}
						ORDER BY
							?clasePadre ?claseHijo
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
					//error_log(print_r($instancias, true));
					
					/*
					echo "<pre>";
					print_r($instancias);
					echo "</pre>";
					*/
					
					// FILTER (?iri = <'.$instancia->getIri().'>) .
					
					return $instancias;
				}
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
	
	/*
	Búsqueda:
		1 - Buscar la palabra clave en las instancias
		2 - Buscar la palabra clave en las clases
			2.1 - Si la coincidencia es en una clase padre, todas sus clase hijos (e instancias) apareceran
					En los resultados de la búsquea.
			
	*/
?>