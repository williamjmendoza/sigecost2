<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/computadorEscritorio.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETComputadorEscritorio
	{
		public static function buscarComputadoras()
		{
			$preMsg = 'Error al buscar las instancias de computadoras de escritorio.';
			$computadoras = array();
			try
			{
				// Obtener las instancias de las computadoras de escritorio
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
				
				// Si $computador->getIri() está presente, dicho iri de instancia será igniorado en la verificación
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
			try
			{
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