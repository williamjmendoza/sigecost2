<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/consumible.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaETConsumible
	{
		public static function buscarConsumibles()
		{
			$preMsg = 'Error al buscar las instancias de consumibles.';
			$consumibles = array();
			try
			{
				// Obtener las instancias de consumibles
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?especificacion ?tipo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							?iri :especificacionConsumible ?especificacion .
							?iri :tipoConsumible ?tipo .
						}
						ORDER BY
							?especificacion ?tipo
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$consumibles[$row['iri']] = self::llenarConsumible($row);
					}
				}
				
				return $consumibles;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function existeConsumible(EntidadInstanciaETConsumible $consumible)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de consumible.';
			try
			{
				if ($consumible === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible\' es nulo.');
		
				if ($consumible->getEspecificacion() === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible->getEspecificacion()\' es nulo.');
		
				if ($consumible->getEspecificacion() == "")
					throw new Exception($preMsg . ' El parámetro \'$consumible->getEspecificacion()\' está vacío.');
		
				if ($consumible->getTipo() === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible->getTipo()\' es nulo.');
		
				if ($consumible->getTipo() == "")
					throw new Exception($preMsg . ' El parámetro \'$consumible->getTipo()\' está vacío.');
				
				// Si $consumible->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($consumible->getIri() !== null && $consumible->getIri() != '')
					? 'FILTER (?instanciaConsumible != <'.$consumible->getIri().'>) .' : '';
		
				// Verificar si existe un consumible con la misma especificacion y tipo, que el pasado por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							?instanciaConsumible rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							?instanciaConsumible :especificacionConsumible "'.$consumible->getEspecificacion().'"^^xsd:string .
							?instanciaConsumible :tipoConsumible "'.$consumible->getTipo().'"^^xsd:string .
							'.$filtro.'
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de consumible " .
							"(especificacion = '".$consumible->getEspecificacion()."', tipo = '".$consumible->getTipo().
							"'). Detalles:\n" . join("\n", $errors));
		
					return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de consumible, y retorno su iri
		public static function guardarConsumible(EntidadInstanciaETConsumible $consumible)
		{
			$preMsg = 'Error al guardar el consumible.';
				
			try
			{
				if ($consumible === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible\' es nulo.');
		
				if ($consumible->getEspecificacion() === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible->getEspecificacion()\' es nulo.');
		
				if ($consumible->getEspecificacion() == "")
					throw new Exception($preMsg . ' El parámetro \'$consumible->getEspecificacion()\' está vacío.');
		
				if ($consumible->getTipo() === null)
					throw new Exception($preMsg . ' El parámetro \'$consumible->getTipo()\' es nulo.');
		
				if ($consumible->getTipo() == "")
					throw new Exception($preMsg . ' El parámetro \'$consumible->getTipo()\' está vacío.');
		
				// Consultar el número de secuencia para la siguiente instancia de consumible a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_CONSUMIBLE);
		
				// Validar si hubo errores obteniendo el siguiente número de secuencia de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de consumible. No se pudo obtener el número '.
							'de la siguiente secuencia para la instancia de la clase \''.SIGECOST_FRAGMENTO_CONSUMIBLE.'\'');
		
					// Construir el fragmento de la nueva instancia de consumible
					// conctenando el framento de la clase sistema operativo "SIGECOST_FRAGMENTO_CONSUMIBLE"
					// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
					$fragmentoIriInstancia = SIGECOST_FRAGMENTO_CONSUMIBLE . '_' . $secuencia;
		
					// Guardar la nueva instancia de consumible
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							:'.$fragmentoIriInstancia.' :especificacionConsumible "'.$consumible->getEspecificacion().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :tipoConsumible "'.$consumible->getTipo().'"^^xsd:string .
						}
				';
		
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception("Error al guardar la instancia de consumible. Detalles:\n". join("\n", $errors));
									
					return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarConsumible($row)
		{
			try {
				$consumible = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de consumible. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$consumible = new EntidadInstanciaETConsumible();
				$consumible->setIri($row['iri']);
				$consumible->setEspecificacion($row['especificacion']);
				$consumible->setTipo($row['tipo']);
		
				return $consumible;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerTodosConsumibles()
		{
			$preMsg = 'Error al obtener todas las instancias de consumibles.';
			$consumibles = array();
			try
			{
				// Obtener todas las instancias de consumibles
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			
						SELECT
							?iri ?especificacion ?tipo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							?iri :especificacionConsumible ?especificacion .
							?iri :tipoConsumible ?tipo .
						}
						ORDER BY
							?especificacion ?tipo
				';
			
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
			
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
			
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$consumibles[$row['iri']] = self::llenarConsumible($row);
					}
				}
			
				return $consumibles;
			
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerConsumiblePorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de consumible dado el iri.';
				
			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
		
				// Obtener la instancia de consumible dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?especificacion ?tipo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							?iri :especificacionConsumible ?especificacion .
							?iri :tipoConsumible ?tipo  .
							FILTER (?iri = <'.$iri.'>) .
						}
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarConsumible(current($rows));
				}
				else
					return null;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>