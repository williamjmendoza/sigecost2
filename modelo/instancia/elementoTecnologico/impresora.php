<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETImpresora
	{
		
		public static function actualizarInstancia(EntidadInstanciaETImpresora $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico impresora.';
		
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
				
				// Agregar la instancia a una colección, si no pertenece
				if(($result = ModeloGeneral::agregarInstanciaAColeccion($instancia->getIri())) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');
				
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
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
					
				// Commit de la transacción
				return $instancia->getIri();
				
			} catch (Exception $e) {
				// Rollback de la transacción
				error_log($e, 0);
				return false;
			}
		}
		
		public static function buscarImpresoras(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de impresoras.';
			$impresoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}

				// Obtener las instancias de las impresoras
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
						$impresoras[$row['iri']] = self::llenarImpresora($row);
					}
				}

				return $impresoras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de impresoras.';

			// Buscar la cantidad de instancias de elemento tecnologico impresoras.
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
				?iri :marcaEquipoReproduccion ?marca .
				?iri :modeloEquipoReproduccion ?modelo .
			';
		}

		public static function existeImpresora(EntidadInstanciaETImpresora $impresora)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de impresora.';

			try
			{
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');

				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');

				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');

				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');

				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');

				// Si $impresora->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($impresora->getIri() !== null && $impresora->getIri() != '')
					? 'FILTER (?instanciaImpresora != <'.$impresora->getIri().'>) .' : '';

				// Verificar si existe una instancia de impresora con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaImpresora rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?instanciaImpresora :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							?instanciaImpresora :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de impresora " .
							"(marca = '".$impresora->getMarca()."', modelo = '".$impresora->getModelo()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico impresora.';
		
			try
			{
				if($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if($iri == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				
				// Eliminar la instancia de la colección a la que pertenece
				if(ModeloGeneral::eliminarInstanciaDeColeccion($iri) !== true)
					throw new Exception($preMsg . ' Error al intentar eliminar la instancia de una colección.');
				
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
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
			$preMsg = 'Error al verificar si está siendo utilizada una instancia de impresora.';
				
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
							?instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?instanciaST :enImpresora ?instanciaAplicacion .
							FILTER (?instanciaAplicacion = <'.$iri.'>) .
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al verificar si está siendo utilizada la instancia de impresora." .
							" Detalles:\n" . join("\n", $errors));
		
					return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
	
		// Guarda una nueva instancia de impresora, y retorna su iri
		public static function guardarImpresora(EntidadInstanciaETImpresora $impresora)
		{
			$preMsg = 'Error al guardar la instancia de impresora.';

			try
			{
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');

				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');

				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');

				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');

				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de impresora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_IMPRESORA);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de impresora. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_IMPRESORA.'\'');

				// Construir el fragmento de la nueva instancia de impresora
				// conctenando el framento de la clase impresora "SIGECOST_FRAGMENTO_IMPRESORA"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_IMPRESORA . '_' . $secuencia;

				// Guardar la nueva instancia de impresora
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de impresora. Detalles:\n". join("\n", $errors));
				
				// Agregar la instancia a una colección
				if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarImpresora($row)
		{
			try {
				$impresora = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de impresora. Detalles: el parámetro \'$row\' no es un arreglo.');

				$impresora = new EntidadInstanciaETImpresora();
				$impresora->setIri($row['iri']);
				$impresora->setMarca($row['marca']);
				$impresora->setModelo($row['modelo']);

				return $impresora;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerImpresoraPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de impresora dado el iri.';

			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de impresora dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
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
					return self::llenarImpresora(current($rows));
				}
				else
					return null;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodasImpresoras()
		{
			$preMsg = 'Error al obtener todas las instancias de impresoras.';
			$impresoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener todas las instancias de las impresoras
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
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
						$impresoras[$row['iri']] = self::llenarImpresora($row);
					}
				}

				return $impresoras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>