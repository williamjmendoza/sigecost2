<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/computadorPortatil.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETComputadorPortatil
	{
		
		public static function actualizarInstancia(EntidadInstanciaETComputadorPortatil $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico computador portátil.';
		
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
				
				// Borrar los datos anteriores de la instancia}
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
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
				
				
		public static function buscarPortatiles(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de computadoras portatiles.';
			$computadoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener las instancias de las computadoras portatiles
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
						$portatiles[$row['iri']] = self::llenarComputadorPortatil($row);
					}
				}

				return $portatiles;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico computador portatil.';

			// Buscar la cantidad de instancias de elemento tecnologico computador portatil.
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
				?iri :marcaEquipoComputacion ?marca .
				?iri :modeloEquipoComputacion ?modelo .
			';

		}

		public static function existePortatil(EntidadInstanciaETComputadorPortatil $portatil)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de computador portatil.';

			try
			{
				if ($portatil === null)
					throw new Exception($preMsg . ' El parámetro \'$portatil\' es nulo.');

				if ($portatil->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'portatil->getMarca()\' es nulo.');

				if ($portatil->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$portatil->getMarca()\' está vacío.');

				if ($portatil->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$portatil->getModelo()\' es nulo.');

				if ($portatil->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$portatil->getModelo()\' está vacío.');

				// Si $portatil->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($portatil->getIri() !== null && $portatil->getIri() != '')
					? 'FILTER (?instanciaPortatil != <'.$portatil->getIri().'>) .' : '';

				// Verificar si existe una instancia de computador portatil con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaPortatil rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
							?instanciaPortatil :marcaEquipoComputacion "'.$portatil->getMarca().'"^^xsd:string .
							?instanciaPortatil :modeloEquipoComputacion "'.$portatil->getModelo().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de computador portatil " .
							"(marca = '".$portatil->getMarca()."', modelo = '".$portatil->getModelo()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico computador portátil.';
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
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
		

		// Guarda una nueva instancia de computador portatil, y retorna su iri
		public static function guardarPortatil(EntidadInstanciaETComputadorPortatil $portatil)
		{
			$preMsg = 'Error al guardar la instancia de computador portatil.';

			try
			{
				if ($portatil === null)
					throw new Exception($preMsg . ' El parámetro \'$portatil\' es nulo.');

				if ($portatil->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'portatil->getMarca()\' es nulo.');

				if ($portatil->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$portatil->getMarca()\' está vacío.');

				if ($portatil->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$portatil->getModelo()\' es nulo.');

				if ($portatil->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$portatil->getModelo()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de computador de escritorio a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de computador portatil. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.'\'');

				// Construir el fragmento de la nueva instancia de computador portatil
				// conctenando el framento de la clase portatil "SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL . '_' . $secuencia;

				// Guardar la nueva instancia de coputador portatil
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoComputacion "'.$portatil->getMarca().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :modeloEquipoComputacion "'.$portatil->getModelo().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de computador portatil. Detalles:\n". join("\n", $errors));
				
				// Agregar la instancia a una colección
				if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarComputadorPortatil($row)
		{
			try {
				$portatil = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de computador portatil. Detalles: el parámetro \'$row\' no es un arreglo.');

				$portatil = new EntidadInstanciaETComputadorPortatil();
				$portatil->setIri($row['iri']);
				$portatil->setMarca($row['marca']);
				$portatil->setModelo($row['modelo']);

				return $portatil;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerPortatilPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de computador portatil dado el iri.';

			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de computador portatil dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
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
					return self::llenarComputadorPortatil(current($rows));
				}
				else
					return null;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodasComputadorasPortatil()
		{
			$preMsg = 'Error al obtener todas las instancias de computadoras portatil.';
			$portatiles = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener todas las instancias de las computadoras portatiles
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_COMPUTADOR_PORTATIL.' .
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
						$portatiles [$row['iri']] = self::llenarComputadorPortatil($row);
					}
				}

				return $portatiles ;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>