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
		
		public static function construirNodosPadresyHojasST()
		{
			$datos = array();
			
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL."SoporteTecnico", $datos);
			
			return $datos;
		}
		
		public static function construirNodosPadresyHojasET()
		{
			$datos = array();
				
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL."ElementoTecnologico", $datos);
				
			return $datos;
		}
		
		private static function construirNodosPadresyHojasRecursivo($iriPadre, &$datos = array())
		{
			$preMsg = 'Error al construir los nodos padres y hojas recursivo';
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
						?claseHijo
					WHERE
					{
						?clasePadre rdf:type owl:Class .
						?claseHijo rdfs:subClassOf ?clasePadre .
						?claseHijo rdf:type owl:Class .
						FILTER (?clasePadre = <'.$iriPadre.'>) .
					}
					ORDER BY
						?claseHijo
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if(is_array($rows))
				{
					if(count($rows) == 0)
					{
						return 1; // Es hoja
							
					} else if (count($rows) > 0){
						foreach ($rows AS $row){
							$respuesta = self::construirNodosPadresyHojasRecursivo($row['claseHijo'], $datos);
							
							if($respuesta === 1)
							{
								$datos[$iriPadre][$row['claseHijo']] = true;
							} else if($respuesta === 2)
							{
								foreach ($datos[$row['claseHijo']] AS $index => $dato)
								{
									$datos[$iriPadre][$index] = true;
								}
							}
						}
						
						return 2; // Es padre
					}
				}
		
				return false;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function agregarHojasCoincidentes($iriConsultado, $nodosPadresyHojas, array &$hojasCoincidentes)
		{
			if(isset($nodosPadresyHojas[SIGECOST_IRI_ONTOLOGIA_NUMERAL."SoporteTecnico"][$iriConsultado]))
				$hojasCoincidentes[$iriConsultado] = true;
			else {
				foreach ($nodosPadresyHojas[$iriConsultado] AS $iriHoja => $elemento)
				{
					$hojasCoincidentes[$iriHoja] = true;
				}
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
					
					
					// Construir un arreglo de nodos padres con sus respectivas hojas para los soportes técnicos
					$nodosPadresyHojasST = ModeloBuscar::construirNodosPadresyHojasST();
						
					// Borrar
					//error_log(print_r($nodosPadresyHojasST, true)."\n\n\n");
					
					// Construir un arreglo de nodos padres con sus respectivas hojas para los elementos tecnológicos
					$nodosPadresyHojasET = ModeloBuscar::construirNodosPadresyHojasET();
					
					// Borrar
					//error_log(print_r($nodosPadresyHojasST, true)."\n\n\n");
					
					
					/**********************************************
					 Búsquedas en clases de elemento tecnológico
					**********************************************/
					
					
					/**********************************************
					 Fin de Búsquedas en clases de elemento tecnológico
					**********************************************/
					
				
					/*******************************************
					 Búsquedas en clases de soporte técnico
					 *******************************************/
					
					/* Filtros sobre clases de soporte técnico */
					
					$filtroClaseST = '';
					
					// Filtro con los nodos padres de soporte técnico 
					foreach ($nodosPadresyHojasST AS $iriPadre => $elemento)
					{
						$filtroClaseST .= '
								'.( $filtroClaseST != '' ? '|| ' : '' ).'?claseST = <'.$iriPadre.'>';
					}
					
					// Filtro con los nodos hojas de soporte técnico
					foreach ($nodosPadresyHojasST[SIGECOST_IRI_ONTOLOGIA_NUMERAL.'SoporteTecnico'] AS $iriPadre => $elemento)
					{
						$filtroClaseST .= '
								'.( $filtroClaseST != '' ? '|| ' : '' ).'?claseST = <'.$iriPadre.'>';
					}
					
					$filtroClaseST = '# Filtros sobre clases solo de soporte tecnico
							FILTER (
								'.$filtroClaseST.'
							) .
					';
					
					/* Fin de Filtros sobre clases de soporte técnico */
					
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			
						SELECT DISTINCT
							?claseST
						WHERE
						{
							?claseST rdf:type owl:Class .
							?claseST rdfs:label ?labelClaseST .
							?claseST rdfs:comment ?commentClaseST .
							FILTER (
								regex(?labelClaseST, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el label de la clase soporte técnico
								|| regex(?commentClaseST, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el comment de la clase soporte técnico
							) .	
							'.$filtroClaseST.'
						}
						ORDER BY
							?claseST
					';
					
					
					// Borrar
					//error_log(print_r($nodosPadresyHojasST, true)."\n\n\n");
					
					$hojasCoincidentes = array();
					
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
							
							self::agregarHojasCoincidentes($row['claseST'], $nodosPadresyHojasST, $hojasCoincidentes);
						}
					}
					
					$filtroHojasCoincidentesClaseST = '';
					// Filtro con las hojas se soporte técnico coincidentes en la búsqueda por clase de soporte técnico
					foreach ($hojasCoincidentes AS $iriHojaCoincidente => $elemento)
					{
						$filtroHojasCoincidentesClaseST .= '
								|| ?claseST = <'.$iriHojaCoincidente.'>';
					}
					
					/*******************************************
					 Fin de Búsquedas en clases de soporte técnico
					*******************************************/
					
					// Query para consultar las instancias
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			
						SELECT DISTINCT
							?claseST
							?instanciaST
						WHERE
						{
							?claseST rdf:type owl:Class .
							?instanciaST rdf:type ?claseST .
							?instanciaST ?intanciaST_ET ?instanciaET .
							?instanciaET rdf:type ?claseET .
							?claseET rdf:type owl:Class .
							?claseET rdfs:label ?labelClaseET .
							?claseET rdfs:comment ?commentClaseET .
							?instanciaET ?propiedadET ?objeto .
							?propiedadET rdfs:label ?labelPropiedadET .
							FILTER (
								regex(?objeto, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el valor del dato
								|| regex(?labelPropiedadET, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre propiedad de datos
								|| regex(?labelClaseET, "'.$clave.'"^^xsd:string,  "i") # Filtro para el label de la clase de elemento téc.
								|| regex(?commentClaseET, "'.$clave.'"^^xsd:string,  "i") # Filtro para el comment de la clase de elemento téc.
								
								# Filtro sobre clases de soporte técnico
								'.$filtroHojasCoincidentesClaseST.'
							) .
							FILTER isLiteral(?objeto) .
						}
						ORDER BY
							?claseST
							?instanciaST
					';
					
					// Borrar
					$instancias = array();
					
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
					//error_log(print_r($hojasCoincidencias, true)."\n\n\n");
					
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