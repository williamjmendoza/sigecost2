<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php' );

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
		
		public static function construirNodosPadresyHojasET()
		{
			$datos = array();
		
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL."ElementoTecnologico", $datos);
		
			return $datos;
		}
		
		public static function construirNodosPadresyHojasST()
		{
			$datos = array();
			
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL."SoporteTecnico", $datos);
			
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
		
		public static function agregarHojasCoincidentesET($iriConsultado, $nodosPadresyHojas, array &$hojasCoincidentes)
		{
			if(isset($nodosPadresyHojas[SIGECOST_IRI_ONTOLOGIA_NUMERAL."ElementoTecnologico"][$iriConsultado]))
				$hojasCoincidentes[$iriConsultado] = true;
			else {
				foreach ($nodosPadresyHojas[$iriConsultado] AS $iriHoja => $elemento)
				{
					$hojasCoincidentes[$iriHoja] = true;
				}
			}
		}
		
		public static function agregarHojasCoincidentesST($iriConsultado, $nodosPadresyHojas, array &$hojasCoincidentes)
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
		
		// Búsquedas en clases de elemento tecnológico
		public static function getFiltroClaseElementoTecnologico(array $parametros = null)
		{
			$preMsg = 'Error al obtener los filtros de clase elemento tecnológico.';
			
			try
			{
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if ($parametros['clave'] === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' es nulo.');
				
				if (($clave = trim($parametros['clave'])) === '')
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' está vacío.');
				
				
				// Construir un arreglo de nodos padres con sus respectivas hojas para los elementos tecnológicos
				$nodosPadresyHojasET = ModeloBuscar::construirNodosPadresyHojasET();
					
				$filtroClaseET = '';
				
				// Filtro con los nodos padres de elemento tecnológico
				foreach ($nodosPadresyHojasET AS $iriPadre => $elemento)
				{
					$filtroClaseET .= '
									'.( $filtroClaseET != '' ? '|| ' : '' ).'?claseET = <'.$iriPadre.'>';
				}
				
				// Filtro con los nodos hojas de elemento tecnológico
				foreach ($nodosPadresyHojasET[SIGECOST_IRI_ONTOLOGIA_NUMERAL.'ElementoTecnologico'] AS $iriPadre => $elemento)
				{
					$filtroClaseET .= '
									'.( $filtroClaseET != '' ? '|| ' : '' ).'?claseET = <'.$iriPadre.'>';
				}
				
				$filtroClaseET = '# Filtros sobre clases solo de elemento tecnológico
								FILTER (
									'.$filtroClaseET.'
								) .
						';
				
				/* Fin de Filtros sobre clases de soporte técnico */
				
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
		
					SELECT DISTINCT
						?claseET
					WHERE
					{
						?claseET rdf:type owl:Class .
						?claseET rdfs:label ?labelClaseET .
						OPTIONAL  { ?claseET rdfs:comment ?commentClaseET . } .
						FILTER (
							regex(?labelClaseET, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el label de la clase elemento tecnológico
							|| regex(?commentClaseET, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el comment de la clase elemento tecnológico
						) .
						'.$filtroClaseET.'
					}
					ORDER BY
						?claseET
				';
				
				$hojasCoincidentesET = array();
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0)
				{
					foreach ($rows AS $row)
					{
						self::agregarHojasCoincidentesET ($row['claseET'], $nodosPadresyHojasET, $hojasCoincidentesET);
					}
				}
					
				$filtroHojasCoincidentesClaseET = '';
				// Filtro con las hojas de elementos tecnológicos coincidentes en la búsqueda por clase de elementos tecnológicos
				foreach ($hojasCoincidentesET AS $iriHojaCoincidente => $elemento)
				{
					$filtroHojasCoincidentesClaseET .= '
									|| ?claseET = <'.$iriHojaCoincidente.'>';
				}
				
				return $filtroHojasCoincidentesClaseET;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function getFiltroClaseSoporteTecnico(array $parametros = null)
		{
			$preMsg = 'Error al obtener los filtros de clase soporte técnico.';
				
			try
			{
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if ($parametros['clave'] === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' es nulo.');
				
				if (($clave = trim($parametros['clave'])) === '')
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' está vacío.');
					
				// Construir un arreglo de nodos padres con sus respectivas hojas para los soportes técnicos
				$nodosPadresyHojasST = ModeloBuscar::construirNodosPadresyHojasST();
				
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
					
				$hojasCoincidentesST = array();
					
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row)
					{
						self::agregarHojasCoincidentesST ($row['claseST'], $nodosPadresyHojasST, $hojasCoincidentesST);
					}
				}
					
				$filtroHojasCoincidentesClaseST = '';
				// Filtro con las hojas se soporte técnico coincidentes en la búsqueda por clase de soporte técnico
				foreach ($hojasCoincidentesST AS $iriHojaCoincidente => $elemento)
				{
					$filtroHojasCoincidentesClaseST .= '
								|| ?claseST = <'.$iriHojaCoincidente.'>';
				}
					
				return $filtroHojasCoincidentesClaseST;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		public static function buscar(array $parametros = null)
		{
			$preMsg = 'Error al buscar patrones en la ontología';
			$datos = array();
			$instancias = null;
			$limite = '';
			$desplazamiento = '';
			
			try
			{
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es un arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if ($parametros['clave'] === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' es nulo.');
				
				if (($clave = trim($parametros['clave'])) === '')
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' está vacío.');
				
				if(!isset($parametros['filtroClaseET']))
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'filtroClaseET\']\' no está presente.');
				
				if(!isset($parametros['filtroClaseST']))
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'filtroClaseST\']\' no está presente.');
				
				$filtroClaseET = trim($parametros['filtroClaseET']);
				$filtroClaseST = trim($parametros['filtroClaseST']);
				
				if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
				if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				
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
						'.self::buscarInstanciasSubQuery($clave, $filtroClaseET, $filtroClaseST).'
					}
					ORDER BY
						?claseST
						?instanciaST
					'.$desplazamiento.'
					'.$limite.'
				';
				
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
						
						/*
						switch ($row['claseST']){
							case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
								
								
								
								break;
						}
						*/
						
						$instancias[] = $row;
					}
				}
				
				$claseSTAnterior = '';
				$irisInstanciasST = array();
				
				foreach ($instancias AS $instancia)
				{
					$claseSTActual = $instancia['claseST'];
					
					if ($claseSTAnterior == '')
					{
						$irisInstanciasST[] = $instancia['instanciaST'];
						
					} else {
						
						if(strcmp($claseSTAnterior, $claseSTActual) == 0 )
						{
							// Borrar
							//error_log("Igual: Anterior: " . $claseSTAnterior . ", Actual: " . $claseSTActual);
							$irisInstanciasST[] = $instancia['instanciaST'];
							
						} else {
						
							// Consultar las instancias en el array $irisInstanciasST
							$instanciasConsultadas = self::consultarInstancias($claseSTAnterior, $irisInstanciasST);
							$datos[$claseSTAnterior] = $instanciasConsultadas;
							// Borrar
							//error_log("Diferente: Anterior: " . $claseSTAnterior . ", Actual: " . $claseSTActual);
							//error_log(print_r($irisInstanciasST, true));
							
							$irisInstanciasST = array();
							$irisInstanciasST[] = $instancia['instanciaST'];
						}
					}
					
					$claseSTAnterior = $claseSTActual;
				}
				
				// Consultar las instancias en el array $irisInstanciasST
				$instanciasConsultadas = self::consultarInstancias($claseSTAnterior, $irisInstanciasST);
				$datos[$claseSTAnterior] = $instanciasConsultadas;
				// Borrar
				//error_log(print_r($irisInstanciasST, true));
				
				
				// Borrar
				//error_log(print_r($datos, true));
				
				
				return $datos;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function consultarInstancias($iriPadre, array $hijos)
		{
			try
			{
				switch ($iriPadre)
				{
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:

						$instancias = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::buscarInstancias(array('iris' => $hijos));
						
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						
						break;
						
					default:
						
						return array('datosClaseST' => array(), 'instanciasClaseST' => array());
						
						break;
				}
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function buscarTotalElementos(array $parametros = null)
		{
			
			$preMsg = 'Error al buscar el contador de las búsquedas.';
			
			try {
				
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es un arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if ($parametros['clave'] === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' es nulo.');
				
				if (($clave = trim($parametros['clave'])) === '')
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'clave\']\' está vacío.');
				
				if(!isset($parametros['filtroClaseET']))
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'filtroClaseET\']\' no está presente.');
				
				if(!isset($parametros['filtroClaseST']))
					throw new Exception($preMsg . ' El parámetro \'$parametros[\'filtroClaseST\']\' no está presente.');
				
				$filtroClaseET = trim($parametros['filtroClaseET']);
				$filtroClaseST = trim($parametros['filtroClaseST']);
				
					
				// Buscar la cantidad de instancias según los criterios
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	
					SELECT DISTINCT
						(COUNT(?instanciaST) AS ?totalElementos)
					WHERE
					{
						'.self::buscarInstanciasSubQuery($clave, $filtroClaseET, $filtroClaseST).'
					}
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return current($rows)['totalElementos'];
				}
				else return false;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
				
		}
		
		public static function buscarInstanciasSubQuery($clave, $filtroHojasCoincidentesClaseET = '', $filtroHojasCoincidentesClaseST = '')
		{
			return
			'
							?claseST rdf:type owl:Class .
							?instanciaST rdf:type ?claseST .
							?instanciaST :uRLSoporteTecnico ?uRLSoporteTecnico .
							?instanciaST ?intanciaST_ET ?instanciaET .
							?instanciaET rdf:type ?claseET .
							?claseET rdf:type owl:Class .
							?instanciaET ?propiedadET ?valorPropiedadET .
							?propiedadET rdfs:label ?labelPropiedadET .
							FILTER (
		
								# Filtro sobre las instancias
		
								regex(?valorPropiedadET, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre el valor del dato
								|| regex(?labelPropiedadET, "'.$clave.'"^^xsd:string,  "i") # Filtro sobre propiedad de datos
		
								# Filtro sobre clases de elemento tecnológico
								'.$filtroHojasCoincidentesClaseET.'
		
								# Filtro sobre clases de soporte técnico
								'.$filtroHojasCoincidentesClaseST.'
							) .
							FILTER isLiteral(?valorPropiedadET) .
			';
		
		}
		
	}
?>