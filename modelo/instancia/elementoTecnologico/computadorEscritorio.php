<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/computadorEscritorio.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETComputadorEscritorio
	{
		public static function actualizarInstancia(EntidadInstanciaETComputadorEscritorio $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico computador de escritorio.';
		
			try
			{
				if($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
		
				if($instancia ->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getIri()\' está vacío.');
		
				if($instancia ->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getMarca()\' es nulo.');
		
				if($instancia ->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getMarca()\' está vacío.');
		
				if($instancia ->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getModelo()\' es nulo.');
		
				if($instancia ->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getModelo()\' está vacío.');
		
				// Iniciar la transacción
				
				// Agregar la instancia a una colección, si no pertenece
				if(($result = ModeloGeneral::agregarInstanciaAColeccion($instancia->getIri())) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');
				
				// Borrar los datos anteriores de la instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?iri :marcaEquipoComputacion  ?marca.
							?iri :modeloEquipoComputacion  ?modelo .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
							?iri :marcaEquipoComputacion  ?marca .
						 	?iri :modeloEquipoComputacion  ?modelo .
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
							<'.$instancia->getIri().'> :marcaEquipoComputacion  "' .$instancia->getMarca().'"^^xsd:string .
							<'.$instancia->getIri().'> :modeloEquipoComputacion  "' .$instancia->getModelo().'"^^xsd:string .
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
		
		public static function buscarComputadoras(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de computadoras de escritorio.';
			$computadoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}

				// Obtener las instancias de las computadoras de escritorio
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
						$computadoras[$row['iri']] = self::llenarComputadorEscritorio($row);
					}
				}

				return $computadoras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico computador de escritorio.';

			// Buscar la cantidad de instancias de elemento tecnologico computador de escritorio.
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
				?iri :marcaEquipoComputacion ?marca .
				?iri :modeloEquipoComputacion ?modelo .
			';

		}

		public static function existeComputador(EntidadInstanciaETComputadorEscritorio $computador)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de computador de escritorio.';

			try
			{
				if ($computador === null)
					throw new Exception($preMsg . ' El parámetro \'$computador\' es nulo.');

				if ($computador->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'computador->getMarca()\' es nulo.');

				if ($computador->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$computador->getMarca()\' está vacío.');

				if ($computador->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$computador->getModelo()\' es nulo.');

				if ($computador->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$computador->getModelo()\' está vacío.');

				// Si $computador->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($computador->getIri() !== null && $computador->getIri() != '')
					? 'FILTER (?instanciaComputador != <'.$computador->getIri().'>) .' : '';

				// Verificar si existe una instancia de computador de escritorio con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaComputador rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
							?instanciaComputador :marcaEquipoComputacion "'.$computador->getMarca().'"^^xsd:string .
							?instanciaComputador :modeloEquipoComputacion "'.$computador->getModelo().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de computador de escritorio " .
							"(marca = '".$computador->getMarca()."', modelo = '".$computador->getModelo()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico computador de escritorio.';
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
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
		
		// Guarda una nueva instancia decomputador de escritorio, y retorna su iri
		public static function guardarComputador(EntidadInstanciaETComputadorEscritorio $computador)
		{
			$preMsg = 'Error al guardar la instancia de computador de escritorio.';

			try
			{
				if ($computador === null)
					throw new Exception($preMsg . ' El parámetro \'$computador\' es nulo.');

				if ($computador->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'computador->getMarca()\' es nulo.');

				if ($computador->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$computador->getMarca()\' está vacío.');

				if ($computador->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$computador->getModelo()\' es nulo.');

				if ($computador->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$computador->getModelo()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de computador de escritorio a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de computador de escritorio. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.'\'');

				// Construir el fragmento de la nueva instancia de computador de escritorio
				// conctenando el framento de la clase impresora "SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO . '_' . $secuencia;

				// Guardar la nueva instancia de coputador de escritorio
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoComputacion "'.$computador->getMarca().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :modeloEquipoComputacion "'.$computador->getModelo().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de computador de escritorio. Detalles:\n". join("\n", $errors));
				
				// Agregar la instancia a una colección
				if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarComputadorEscritorio($row)
		{
			try {
				$computador = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de computador de escritorio. Detalles: el parámetro \'$row\' no es un arreglo.');

				$computador = new EntidadInstanciaETComputadorEscritorio();
				$computador->setIri($row['iri']);
				$computador->setMarca($row['marca']);
				$computador->setModelo($row['modelo']);

				return $computador;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerComputadorPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de computador de ecritorio dado el iri.';

			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de computador de ecritorio dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
							?iri :marcaEquipoComputacion ?marca .
							?iri :modeloEquipoComputacion ?modelo .
							FILTER (?iri = <'.$iri.'>) .
						}
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarComputadorEscritorio(current($rows));
				}
				else
					return null;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodasComputadorasEscritorio()
		{
			$preMsg = 'Error al obtener todas las instancias de computadoras de escritorio.';
			$computadoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener todas las instancias de las computadoras de escritorio
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_ESCRITORIO.' .
							?iri :marcaEquipoComputacion ?marca .
							?iri :modeloEquipoComputacion ?modelo .
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
						$computadoras [$row['iri']] = self::llenarComputadorEscritorio($row);
					}
				}

				return $computadoras ;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>