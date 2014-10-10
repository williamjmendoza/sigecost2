<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/fotocopiadora.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETFotocopiadora
	{
		
		public static function actualizarInstancia(EntidadInstanciaETFotocopiadora $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico fotocopiadora.';
		
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
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
		
		public static function buscarFotocopiadoras(array $parametros = null)
		{
			$preMsg = 'Error al buscar fotocopiadoras.';
			$fotocopiadoras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener las instancias de fotocopiadoras
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
						$fotocopiadoras[$row['iri']] = self::llenarFotocopiadora($row);
					}
				}

				return $fotocopiadoras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de fotocopiadoras.';

			// Buscar la cantidad de instancias de elemento tecnologico fotocopiadoras.
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
				?iri :marcaEquipoReproduccion ?marca .
				?iri :modeloEquipoReproduccion ?modelo .
			';
		}

		public static function existeFotocopiadora(EntidadInstanciaETFotocopiadora $fotocopiadora)
		{
			$preMsg = 'Error al verificar la existencia de una fotocopiadora.';
			try
			{
				if ($fotocopiadora=== null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora\' es nulo.');

				if ($fotocopiadora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getMarca()\' es nulo.');

				if ($fotocopiadora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getMarca()\' está vacío.');

				if ($fotocopiadora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getModelo()\' es nulo.');

				if ($fotocopiadora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getModelo()\' está vacío.');

				// Si $fotocopiadora->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($fotocopiadora->getIri() !== null && $fotocopiadora->getIri() != '')
					? 'FILTER (?instanciaFotocopiadora != <'.$fotocopiadora->getIri().'>) .' : '';

				// Verificar si existe una fotocopiadora con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaFotocopiadora rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
							?instanciaFotocopiadora :marcaEquipoReproduccion "'.$fotocopiadora->getMarca().'"^^xsd:string .
							?instanciaFotocopiadora :modeloEquipoReproduccion "'.$fotocopiadora->getModelo().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de fotocopiadora " .
							"(marca = '".$fotocopiadora->getMarca()."', modelo = '".$fotocopiadora->getModelo()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico fotocopiador.';
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
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
		
		

		// Guarda una nueva instancia de fotocopiadora, y retorno su iri
		public static function guardarfotocopiadora(EntidadInstanciaETFotocopiadora $fotocopiadora)
		{
			$preMsg = 'Error al guardar el fotocopiadora.';

			try
			{
				if ($fotocopiadora=== null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora\' es nulo.');

				if ($fotocopiadora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getMarca()\' es nulo.');

				if ($fotocopiadora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getMarca()\' está vacío.');

				if ($fotocopiadora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getModelo()\' es nulo.');

				if ($fotocopiadora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$fotocopiadora->getModelo()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de fotocopiadora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_FOTOCOPIADORA);

				// Validar si hubo errores obteniendo el siguiente número de secuencia de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de fotocopiadora. No se pudo obtener el número '.
							'de la siguiente secuencia para la instancia de la clase \''.SIGECOST_FRAGMENTO_FOTOCOPIADORA.'\'');

					// Construir el fragmento de la nueva instancia de fotocopiadora
					// conctenando el framento de la clase fotocopiadorar "SIGECOST_FRAGMENTO_FOTOCOPIADORA"
					// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
					$fragmentoIriInstancia = SIGECOST_FRAGMENTO_FOTOCOPIADORA . '_' . $secuencia;

					// Guardar la nueva instancia de fotocopiadora
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$fotocopiadora->getMarca().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$fotocopiadora->getModelo().'"^^xsd:string .
						}
				';

					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception("Error al guardar la instancia de fotocopiadora. Detalles:\n". join("\n", $errors));

					return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarFotocopiadora($row)
		{
			try {
				$fotocopiadora = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de fotocopiadora. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$fotocopiadora = new EntidadInstanciaETFotocopiadora();
				$fotocopiadora->setIri($row['iri']);
				$fotocopiadora->setMarca($row['marca']);
				$fotocopiadora->setModelo($row['modelo']);

				return $fotocopiadora;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerFotocopiadoraPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de fotocopiadora dado el iri.';

			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de fotocopiadora dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
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
					return self::llenarFotocopiadora(current($rows));
				}
				else
					return null;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodasFotocopiadoras()
		{
			$preMsg = 'Error al obtener todas las instancias de fotocopiadoras.';
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
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
						$fotocopiadoras[$row['iri']] = self::llenarfotocopiadora($row);
					}
				}

				return $fotocopiadoras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>