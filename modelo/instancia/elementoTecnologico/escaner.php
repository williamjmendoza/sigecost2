<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/escaner.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaETEscaner
	{
		public static function buscarEscaners()
		{
			$preMsg = 'Error al buscar escaners.';
			$escaners = array();
			try
			{
				// Obtener las instancias de escaners
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?marca ?modelo 
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
						}
						ORDER BY
							?marca ?modelo
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$escaners[$row['iri']] = self::llenarEscaner($row);
					}
				}
				
				return $escaners;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function existeEscaner(EntidadInstanciaETEscaner $escaner)
		{
			$preMsg = 'Error al verificar la existencia de un escaner.';
			try
			{
				if ($escaner === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner\' es nulo.');
		
				if ($escaner->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner->getMarca()\' es nulo.');
		
				if ($escaner->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$escaner->getMarca()\' está vacío.');
		
				if ($escaner->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner->getModelo()\' es nulo.');
		
				if ($escaner->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$escaner->getModelo()\' está vacío.');
		
				// Verificar si existe un escaner con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							_:instanciaEscaner rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							_:instanciaEscaner :marcaEquipoReproduccion "'.$escaner->getMarca().'"^^xsd:string .
							_:instanciaEscaner :modeloEquipoReproduccion "'.$escaner->getModelo().'"^^xsd:string .
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de escaner " .
							"(marca = '".$escaner->getMarca()."', modelo = '".$escaner->getModelo()."'). Detalles:\n" . join("\n", $errors));
		
					return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de escaner, y retorno su iri
		public static function guardarEscaner(EntidadInstanciaETEscaner $escaner)
		{
			$preMsg = 'Error al guardar el escaner.';
				
			try
			{
				if ($escaner === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner\' es nulo.');
		
				if ($escaner->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner->getMarca()\' es nulo.');
		
				if ($escaner->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$escaner->getMarca()\' está vacío.');
		
				if ($escaner->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$escaner->getModelo()\' es nulo.');
		
				if ($escaner->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$escaner->getModelo()\' está vacío.');
		
				// Consultar el número de secuencia para la siguiente instancia de escaner a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_ESCANER);
		
				// Validar si hubo errores obteniendo el siguiente número de secuencia de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de ESCANER. No se pudo obtener el número '.
							'de la siguiente secuencia para la instancia de la clase \''.SIGECOST_FRAGMENTO_ESCANER.'\'');
		
					// Construir el fragmento de la nueva instancia de escaner
					// conctenando el framento de la clase escaner "SIGECOST_FRAGMENTO_ESCANER"
					// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
					$fragmentoIriInstancia = SIGECOST_FRAGMENTO_ESCANER . '_' . $secuencia;
		
					// Guardar la nueva instancia de escaner
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$escaner->getMarca().'"^^xsd:string .		
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$escaner->getModelo().'"^^xsd:string .
						}
				';
		
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception("Error al guardar la instancia de escaner. Detalles:\n". join("\n", $errors));
		
					return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarEscaner($row)
		{
			try {
				$escaner = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de escaner. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$escaner = new EntidadInstanciaETEscaner();
				$escaner->setIri($row['iri']);
				$escaner->setMarca($row['marca']);
				$escaner->setModelo($row['modelo']);
		
				return $escaner;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerEscanerPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de escaner dado el iri.';
				
			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
		
				// Obtener la instancia de escaner dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
							FILTER (?iri = <'.$iri.'>) .
						}
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarEscaner(current($rows));
				}
				else
					return null;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerTodosEscaners()
		{
			$preMsg = 'Error al obtener todas las instancias de escaners.';
			$escaners = array();
			try
			{
				// Obtener todas las instancias de las escaners
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
						}
						ORDER BY
							?marca ?modelo
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$escaners[$row['iri']] = self::llenarEscaner($row);
					}
				}
		
				return $escaners;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
?>