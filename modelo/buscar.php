<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDD.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/instalacionAplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/corregirImpresionManchada.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/desatascarPapel.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/instalacionImpresora.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php' );

	class ModeloBuscar
	{
		public static function construirNodosPadresyHojasET()
		{
			$datos = array();
		
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_ELEMENTO_TECNOLOGICO, $datos);
		
			return $datos;
		}
		
		public static function construirNodosPadresyHojasST()
		{
			$datos = array();
			
			self::construirNodosPadresyHojasRecursivo(SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T, $datos);
			
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
			if(isset($nodosPadresyHojas[SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_ELEMENTO_TECNOLOGICO][$iriConsultado]))
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
			if(isset($nodosPadresyHojas[SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T][$iriConsultado]))
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
				foreach ($nodosPadresyHojasET[SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_ELEMENTO_TECNOLOGICO] AS $iriPadre => $elemento)
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
								' . ($filtroHojasCoincidentesClaseET != '' ? '|| ' : '') . ' ?claseET = <'.$iriHojaCoincidente.'>';
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
				foreach ($nodosPadresyHojasST[SIGECOST_IRI_ONTOLOGIA_NUMERAL . SIGECOST_FRAGMENTO_S_T] AS $iriPadre => $elemento)
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
								' . ($filtroHojasCoincidentesClaseST != '' ? '|| ' : '') . ' ?claseST = <'.$iriHojaCoincidente.'>';
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
				
				$buscarEnPropiedades = ( isset($parametros['buscarEnPropiedades']) ? ( $parametros['buscarEnPropiedades'] === true ? true : false ) : true);
				$buscarEnInstancias = ( isset($parametros['buscarEnInstancias']) ? ( $parametros['buscarEnInstancias'] === true ? true : false ) : true); 
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
						'.self::buscarInstanciasSubQuery($clave, $buscarEnPropiedades, $buscarEnInstancias, $filtroClaseET, $filtroClaseST).'
					}
					ORDER BY
						?claseST
						?instanciaST
					'.$desplazamiento.'
					'.$limite.'
				';
				
				error_log("Filtro: " .print_r($query, true));
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
				if (is_array($rows) && count($rows) > 0){
					$instancias = array();
					
					foreach ($rows AS $row){
						$instancias[] = $row;
					}
				}
				
				$claseSTAnterior = '';
				$irisInstanciasST = array();
				
				if(is_array($instancias))
				{
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
				}
				
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
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
						$instancias = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO:
						$instancias = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA:
						$instancias = ModeloInstanciaSTAplicacionOfimaticaDesinstalacionAplicacionOfimatica::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA:
						$instancias = ModeloInstanciaSTAplicacionOfimaticaInstalacionAplicacionOfimatica::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO:
						$instancias = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA:
						$instancias = ModeloInstanciaSTImpresoraCorregirImpresionManchada::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL:
						$instancias = ModeloInstanciaSTImpresoraDesatascarPapel::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA:
						$instancias = ModeloInstanciaSTImpresoraInstalacionImpresora::buscarInstancias(array('iris' => $hijos));
						return array('datosClaseST' => array(), 'instanciasClaseST' => $instancias);
						break;
						
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA:
						$instancias = ModeloInstanciaSTImpresoraRepararImpresionCorrida::buscarInstancias(array('iris' => $hijos));
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
				
				$buscarEnPropiedades = ( isset($parametros['buscarEnPropiedades']) ? ( $parametros['buscarEnPropiedades'] === true ? true : false ) : true);
				$buscarEnInstancias = ( isset($parametros['buscarEnInstancias']) ? ( $parametros['buscarEnInstancias'] === true ? true : false ) : true);
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
						'.self::buscarInstanciasSubQuery($clave, $buscarEnPropiedades, $buscarEnInstancias, $filtroClaseET, $filtroClaseST).'
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
		
		public static function buscarInstanciasSubQuery($clave, $buscarEnPropiedades = true, $buscarEnInstancias = true, $filtroHojasCoincidentesClaseET = '',
				$filtroHojasCoincidentesClaseST = ''
		){
			$where = '';
			$arrayClaves = explode(" ", $clave);
			
			if ($buscarEnPropiedades === true)
			{
				$where = '
								# Filtro sobre las propiedades
								regex(?labelPropiedadET, "'.$clave.'"^^xsd:string,  "i")
								|| regex(?commentPropiedadET, "'.$clave.'"^^xsd:string,  "i")
				';
			}
			
			if($buscarEnInstancias === true)
			{
				/*
				foreach ($arrayClaves AS $index => $palabra)
				{
					if($index == 0)
					{
						$where .= '
								# Filtro sobre las instancias (valor de propiedad)
								'.($where != '' ? '|| ' : '').'regex(?valorPropiedadET, "'.$palabra.'"^^xsd:string,  "i")';
					}
					else{
						$where .= '
								|| regex(?valorPropiedadET, "'.$palabra.'"^^xsd:string,  "i")';
					}
				}*/
				
				$where .= '
								# Filtro sobre las instancias (valor de propiedad)
								'.($where != '' ? '|| ' : '').'regex(?valorPropiedadET, "'.$clave.'"^^xsd:string,  "i")
				';
			}
			
			if($filtroHojasCoincidentesClaseET != '')
			{
				$where .= '
						
								# Filtro sobre clases de elemento tecnológico
								'.($where != '' ? '|| ' : '').$filtroHojasCoincidentesClaseET.'
				';
			}
			
			if($filtroHojasCoincidentesClaseST != '')
			{
				$where .= '
						
								# Filtro sobre clases de soporte técnico
								'.($where != '' ? '|| ' : '').$filtroHojasCoincidentesClaseST.'
				';
			}
			
			if($where != '')
				$where = 'FILTER(
								'.$where.'
							) . ';
			
			/*
			if($where == '')
				$where = '
								1 = 2 # Provocar un filtro false, de manera que las coincidencias sean provocadas por los otros filtros 
				';
			*/
			
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
							?propiedadET rdfs:comment ?commentPropiedadET .
							'.$where.'
							FILTER isLiteral(?valorPropiedadET) .
			';
			
		}
		
		public static function verDetalles($iriClaseST, $iriIstanciaST)
		{
			try
			{
				switch ($iriClaseST)
				{
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
						$instancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO:
						$instancia = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_CIERRE_INESPERADO:
						$instancia = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_OFIMATICA:
						$instancia = ModeloInstanciaSTAplicacionOfimaticaDesinstalacionAplicacionOfimatica::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_APLICACION_OFIMATICA:
						$instancia = ModeloInstanciaSTAplicacionOfimaticaInstalacionAplicacionOfimatica::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO:
						$instancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA:
						$instancia = ModeloInstanciaSTImpresoraCorregirImpresionManchada::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_DESATASCAR_PAPEL:
						$instancia = ModeloInstanciaSTImpresoraDesatascarPapel::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA:
						$instancia = ModeloInstanciaSTImpresoraInstalacionImpresora::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					case SIGECOST_IRI_ONTOLOGIA_NUMERAL.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA:
						$instancia = ModeloInstanciaSTImpresoraRepararImpresionCorrida::obtenerInstanciaPorIri($iriIstanciaST);
						return array('iriClaseST' => $iriClaseST, 'instancia' => $instancia);
						break;
			
					default:
						return array('iriClaseST' => '', 'instancia' => array());
						break;
				}
			
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>