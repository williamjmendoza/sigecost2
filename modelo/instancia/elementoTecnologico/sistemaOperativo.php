<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETSistemaOperativo
	{
		public static function actualizarInstancia(EntidadInstanciaETSistemaOperativo $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico sistema operativo.';
		
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
		
				if ($instancia ->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getIri()\' está vacío.');

				if ($instancia->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getNombre()\' es nulo.');
				
				if ($instancia->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getNombre()\' está vacío.');
				
				if ($instancia->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getVersion()\' es nulo.');
				
				if ($instancia->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getVersion()\' está vacío.');
				
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
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
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
							<'.$instancia->getIri().'> :nombreSistemaOperativo "' .$instancia->getNombre().'"^^xsd:string .
							<'.$instancia->getIri().'> :versionSistemaOperativo  "' .$instancia->getVersion().'"^^xsd:string .
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
									
		public static function buscarSistemasOperativos(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de sistemas operativos.';
			$sistemasOperativos = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener las instancias de los sistemas operativos
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?nombre ?version
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?nombre ?version
						'.$desplazamiento.'
						'.$limite.'
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$sistemasOperativos[$row['iri']] = self::llenarSistemaOperativo($row);
					}
				}

				return $sistemasOperativos;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de sistemas operativos.';

			// Buscar la cantidad de instancias de elemento tecnologico sistemas operativos.
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
				?iri :nombreSistemaOperativo ?nombre .
				?iri :versionSistemaOperativo ?version .
			';
		}

		public static function existeSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de sistema operativo.';
			try
			{
				if ($sistemaOperativo === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo\' es nulo.');

				if ($sistemaOperativo->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' es nulo.');

				if ($sistemaOperativo->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' está vacío.');

				if ($sistemaOperativo->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' es nulo.');

				if ($sistemaOperativo->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' está vacío.');

				// Si $sistemaOperativo->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($sistemaOperativo->getIri() !== null && $sistemaOperativo->getIri() != '')
					? 'FILTER (?instanciaSistemaOperativo != <'.$sistemaOperativo->getIri().'>) .' : '';

				// Verificar si existe un sistema operativo con el mismo nombre y versión, que el pasado por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaSistemaOperativo rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?instanciaSistemaOperativo :nombreSistemaOperativo "'.$sistemaOperativo->getNombre().'"^^xsd:string .
							?instanciaSistemaOperativo :versionSistemaOperativo "'.$sistemaOperativo->getVersion().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia del sistema operativo " .
							"(nombre = '".$sistemaOperativo->getNombre()."', versión = '".$sistemaOperativo->getVersion().
							"'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico sistema operativo.';
		
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
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
		
		public static function esInstanciaUtilizada($iri)
		{
			$preMsg = 'Error al verificar si está siendo utilizada una instancia de sistema operativo.';
		
			try
			{
				if($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if($iri == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
		
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						ASK
						{
							?instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?instanciaST :sobreSistemaOperativo ?instanciaAplicacion .
							FILTER (?instanciaAplicacion = <'.$iri.'>) .
						}
								
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al verificar si está siendo utilizada la instancia de sistema operativo." .
						" Detalles:\n" . join("\n", $errors));
		
				return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de sistema operativo, y retorno su iri
		public static function guardarSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo)
		{
			$preMsg = 'Error al guardar el sistema operativo.';

			try
			{
				if ($sistemaOperativo === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo\' es nulo.');

				if ($sistemaOperativo->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' es nulo.');

				if ($sistemaOperativo->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' está vacío.');

				if ($sistemaOperativo->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' es nulo.');

				if ($sistemaOperativo->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de sistema operativo a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO);

				// Validar si hubo errores obteniendo el siguiente número de secuencia de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de sistema operativo. No se pudo obtener el número '.
							'de la siguiente secuencia para la instancia de la clase \''.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.'\'');

					// Construir el fragmento de la nueva instancia de sistema operativo
					// conctenando el framento de la clase sistema operativo "SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO"
					// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
					$fragmentoIriInstancia = SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO . '_' . $secuencia;

					// Guardar la nueva instancia de sistema operativo
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							:'.$fragmentoIriInstancia.' :nombreSistemaOperativo "'.$sistemaOperativo->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionSistemaOperativo "'.$sistemaOperativo->getVersion().'"^^xsd:string .
						}
				';

					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception("Error al guardar la instancia de sistema operativo. Detalles:\n". join("\n", $errors));
					
					// Agregar la instancia a una colección
					if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
						throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

					return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarSistemaOperativo($row)
		{
			try {
				$sistemaOperativo = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de sistema operativo. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$sistemaOperativo = new EntidadInstanciaETSistemaOperativo();
				$sistemaOperativo->setIri($row['iri']);
				$sistemaOperativo->setNombre($row['nombre']);
				$sistemaOperativo->setVersion($row['version']);

				return $sistemaOperativo;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodosSitemasOperativos()
		{
			$preMsg = 'Error al obtener todas las instancias de sistemas operativos.';
			$sistemasOperativos = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener todas las instancias de los sistemas operativos
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?nombre ?version
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
						}
						ORDER BY
							?nombre ?version
						'.$desplazamiento.'
						'.$limite.'
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$sistemasOperativos[$row['iri']] = self::llenarSistemaOperativo($row);
					}
				}

				return $sistemasOperativos;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerSistemaOperativoPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de sistema operativo dado el iri.';

			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de sistema operativo dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?nombre ?version
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
							FILTER (?iri = <'.$iri.'>) .
						}
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarSistemaOperativo(current($rows));
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