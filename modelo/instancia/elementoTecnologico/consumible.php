<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/consumible.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETConsumible
	{
		
		public static function actualizarInstancia(EntidadInstanciaETConsumible $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico consumible.';
		
			try
			{
				if($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if($instancia ->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getIri()\' está vacío.');
				
				if ($instancia->getEspecificacion() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEspecificacion()\' es nulo.');
				
				if ($instancia->getEspecificacion() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEspecificacion()\' está vacío.');
				
				if ($instancia->getTipo() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getTipo()\' es nulo.');
				
				if ($instancia->getTipo() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getTipo()\' está vacío.');
				
				// Iniciar la transacción
				
				// Agregar la instancia a una colección, si no pertenece
				if(($result = ModeloGeneral::agregarInstanciaAColeccion($instancia->getIri())) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');
				
				// Borrar los datos anteriores de la instancia}
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?iri :especificacionConsumible  ?especificacion.
							?iri :tipoConsumible  ?tipo .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
							?iri :especificacionConsumible ?especificacion .
							?iri :tipoConsumible ?tipo .
							FILTER (?iri = <'.$instancia->getIri().'>) .
						}
				';
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . " No se pudieron eliminar los datos anteriores de la instancia. Detalles:\n" . join("\n", $errors));
				
				if($result["result"]["t_count"] == 0) {
					// Excepción porque no se pudieron borrar los datos anteriores de la instancia, para que se ejecute el Rollback
					throw new Exception($preMsg . ' Detalles: No se eliminó ningún registro.');
				}
				
				/*
				 // Descomentar cuando se utilicen transacciones
				if($result["result"]["t_count"] != 2 && $result["result"]["t_count"] != 3) {
				// Excepción porque no se pudieron borrar los datos anteriores de la instancia, para que se ejecute el Rollback
				throw new Exception($preMsg . ' Detalles: El número de registros eliminados es incorrecto.' .
						'Número de registros eliminados: ' . $result["result"]["t_count"] . '.'
				);
				}
				*/
				// Guardar los datos actualizados de la instancia de aplicacion
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							<'.$instancia->getIri().'> :especificacionConsumible  "' .$instancia->getEspecificacion().'"^^xsd:string .
							<'.$instancia->getIri().'> :tipoConsumible  "' .$instancia->getTipo().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					// Excepción porque no se pudieron guardar los datos actualizados de la instancia, para que se ejecute el Rollback
					throw new Exception($preMsg . " No se pudieron guardar los datos actualizados de la instancia. Detalles:\n" . join("\n", $errors));
					
				// Commit de la transacción
				return $instancia->getIri();
				
			} catch (Exception $e) {
				// Rollback de la transacción
				error_log($e, 0);
				return false;
			}
		}
		
		public static function buscarConsumibles(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de consumibles.';
			$consumibles = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener las instancias de consumibles
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?especificacion ?tipo
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?especificacion ?tipo
						'.$desplazamiento.'
						'.$limite.'
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

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de aplicación gráfica digital, dibujo y diseño..';

			// Buscar la cantidad de instancias de elemento tecnologico aplicación gráfica digital, dibujo y diseño.
			$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

					SELECT
						(COUNT(?iri) AS ?totalElementos)
					WHERE
					{
						'.self::buscarInstanciasSubQuery().'
					}
			';

			$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

			if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
				throw new Exception($preMsg . " Detalles:\n". join("\n", $errors));

			if (is_array($rows) && count($rows) > 0){
				reset($rows);
				return current($rows)['totalElementos'];
			}
			else return false;
		}

		public static function buscarInstanciasSubQuery(array $parametros = null)
		{
			return
			'
				?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
				?iri :especificacionConsumible ?especificacion .
				?iri :tipoConsumible ?tipo .
			';
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

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico consumible.';
			try
			{
				if($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if($iri == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				// Borrar los datos de la instancia desde la base de datos
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
							PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
					DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
					{
						?iri ?predicado ?objeto .
					}
					WHERE
					{
						?iri rdf:type :'.SIGECOST_FRAGMENTO_CONSUMIBLE.' .
						?iri ?predicado ?objeto .
						FILTER (?iri = <'.$iri.'>) .
					}
				';
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . " No se pudieron eliminar los datos de la instancia. Detalles:\n" . join("\n", $errors));
				
				if($result["result"]["t_count"] == 0) {
					// Excepción porque no se pudieron borrar los datos de la instancia, para que se ejecute el Rollback
					throw new Exception($preMsg . ' Detalles: No se eliminó ningún registro.');
				}
		
				return $iri;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
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
					
					// Agregar la instancia a una colección
					if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
						throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

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
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
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
						'.$desplazamiento.'
						'.$limite.'
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