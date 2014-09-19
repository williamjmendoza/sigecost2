<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/escaner.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETEscaner
	{
		
		public static function actualizarInstancia(EntidadInstanciaETEscaner $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico escáner.';
		
			try
			{
				if($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
		
				if($instancia ->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getIri()\' está vacío.');
				
				if ($instancia->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getMarca()\' es nulo.');
				
				if ($instancia->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getMarca()\' está vacío.');
				
				if ($instancia->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getModelo()\' es nulo.');
				
				if ($instancia->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getModelo()\' está vacío.');
				
				// Iniciar la transacción
				// Borrar los datos anteriores de la instancia}
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
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
							<'.$instancia->getIri().'> :marcaEquipoReproduccion "' .$instancia->getMarca().'"^^xsd:string .
							<'.$instancia->getIri().'> :modeloEquipoReproduccion  "' .$instancia->getModelo().'"^^xsd:string .
						}
				';
							
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				


				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					// Excepción porque no se pudieron guardar los datos actualizados de la instancia, para que se ejecute el Rollback
					throw new Exception($preMsg . " No se pudieron guardar los datos actualizados de la instancia. Detalles:\n" . join("\n", $errors));
				
				$GLOBALS['SigecostInfo']['general'][] = "Instancia de esc&aacute;ner modificada satisfactoriamente.";
					
				// Commit de la transacción
				return $instancia->getIri();
				
			} catch (Exception $e) {
				// Rollback de la transacción
				error_log($e, 0);
				return false;
			}
		}
				
		
		public static function buscarEscaners(array $parametros = null)
		{
			$preMsg = 'Error al buscar escaners.';
			$escaners = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener las instancias de escaners
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?marca ?modelo
						'.$desplazamiento.'
						'.$limite.'
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
				?iri :marcaEquipoReproduccion ?marca .
				?iri :modeloEquipoReproduccion ?modelo .
			';
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

				// Si $escaner->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($escaner->getIri() !== null && $escaner->getIri() != '')
					? 'FILTER (?instanciaEscaner != <'.$escaner->getIri().'>) .' : '';

				// Verificar si existe un escaner con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaEscaner rdf:type :'.SIGECOST_FRAGMENTO_ESCANER.' .
							?instanciaEscaner :marcaEquipoReproduccion "'.$escaner->getMarca().'"^^xsd:string .
							?instanciaEscaner :modeloEquipoReproduccion "'.$escaner->getModelo().'"^^xsd:string .
							'.$filtro.'
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

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
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
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
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
						'.$desplazamiento.'
						'.$limite.'
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