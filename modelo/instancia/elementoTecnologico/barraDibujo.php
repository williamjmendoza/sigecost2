<?php
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/barraDibujo.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETBarraDibujo
	{
		
		public static function actualizarInstancia(EntidadInstanciaETBarraDibujo $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico barra de dibujo.';
		
			try
			{
				if($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
		
				if($instancia ->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getIri()\' está vacío.');
		
				if($instancia ->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getNombre()\' es nulo.');
		
				if($instancia ->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getNombre()\' está vacío.');
		
				if($instancia ->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getVersion()\' es nulo.');
		
				if($instancia ->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia ->getVersion()\' está vacío.');
		
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
							?iri :nombreAplicacionPrograma ?nombreAplicacion .
							?iri :versionAplicacionPrograma ?versionAplicacion .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
							?iri :nombreAplicacionPrograma ?nombreAplicacion .
						 	?iri :versionAplicacionPrograma ?versionAplicacion .
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
							<'.$instancia->getIri().'> :nombreAplicacionPrograma "' .$instancia->getNombre().'"^^xsd:string .
							<'.$instancia->getIri().'> :versionAplicacionPrograma "' .$instancia->getVersion().'"^^xsd:string .
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

		public static function buscarBarras(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de barras de dibujo.';
			$barras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener la instancia de barras de dibujo, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriBarra ?nombreBarra ?versionBarra
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?nombreBarra ?versionBarra
						'.$desplazamiento.'
						'.$limite.'
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$barras[$row['iriBarra']] = self::llenarBarra($row);
					}
				}

				return $barras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de barra de dibujo..';

			// Buscar la cantidad de instancias de elemento tecnologico barra de dibujo.
			$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
							PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

					SELECT
						(COUNT(?iriBarra) AS ?totalElementos)
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
				?iriBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
				?iriBarra :nombreAplicacionPrograma ?nombreBarra .
				?iriBarra :versionAplicacionPrograma ?versionBarra .
			';
		}

		public static function existeBarra(EntidadInstanciaETBarraDibujo $barra)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de barra de dibujo.';

			try
			{
				if($barra === null)
					throw new Exception($preMsg . ' El parámetro \'$barra\' es nulo.');

				if($barra->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$barra->getNombre()\' es nulo.');

				if($barra->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$barra->getNombre()\' está vacío.');

				if($barra->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$barra->getVersion()\' es nulo.');

				if($barra->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$barra->getVersion()\' está vacío.');

				// Si $barra->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($barra->getIri() !== null && $barra->getIri() != '')
					? 'FILTER (?instanciaBarra != <'.$barra->getIri().'>) .' : '';

				// Verificar si existe una instancia de la barra de dibujo con el mismo nombre y versión,
				// que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
							?instanciaBarra :nombreAplicacionPrograma "'.$barra->getNombre().'"^^xsd:string .
							?instanciaBarra :versionAplicacionPrograma "'.$barra->getVersion().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de la barra de dibujo" .
							"(nombre = '".$barra->getNombre()."', versión = '".$barra->getVersion()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico barra de dibujo.';
		
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
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
				
		// Guarda una nueva instancia de barra de dibujo, y retorna su iri
		public static function guardarBarra(EntidadInstanciaETBarraDibujo $barra)
		{
			$preMsg = 'Error al guardar la instancia de barra de dibujo.';

			try
			{
				if($barra === null)
					throw new Exception($preMsg . ' El parámetro \'$barra\' es nulo.');

				if($barra->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$barra->getNombre()\' es nulo.');

				if($barra->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$barra->getNombre()\' está vacío.');

				if($barra->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$barra->getVersion()\' es nulo.');

				if($barra->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$barra->getVersion()\' está vacío.');

				// Consultar el número de secuencia para la siguiente instancia de barra a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_BARRA_DIBUJO);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de barra de dibujo. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_BARRA_DIBUJO.'\'');

				// Construir el fragmento de la nueva instancia de barra de dibujo
				// concatenando el framento de la clase barra de "SIGECOST_FRAGMENTO_BARRA_DIBUJO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_BARRA_DIBUJO . '_' . $secuencia;

				// Guardar la nueva instancia de barra de dibujo
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
							:'.$fragmentoIriInstancia.' :nombreAplicacionPrograma "'.$barra->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionAplicacionPrograma "'.$barra->getVersion().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de barra de dibujo. Detalles:\n". join("\n", $errors));
				
				// Agregar la instancia a una colección
				if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarBarra($row)
		{
			try {
				$barra = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de barra de dibujo. ' .
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$barra = new EntidadInstanciaETBarraDibujo();
				$barra->setIri($row['iriBarra']);
				$barra->setNombre($row['nombreBarra']);
				$barra->setVersion($row['versionBarra']);

				return $barra;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerBarraPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de barra de dibujo, dado el iri.';

			try
			{
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de barra de dibujo, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriBarra ?nombreBarra ?versionBarra
						WHERE
						{
							?iriBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_DIBUJO.' .
							?iriBarra :nombreAplicacionPrograma ?nombreBarra .
							?iriBarra :versionAplicacionPrograma ?versionBarra .
							FILTER (?iriBarra = <'.$iri.'>) .
						}
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarBarra(current($rows));
				}
				else
					return null;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerTodasBarras()
		{
			$preMsg = 'Error al obtener todas las instancias de barra de dibujos.';
			$barras = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Obtener la instancia de barra de dibujo, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriBarra ?nombreBarra ?versionBarra
						WHERE
						{
							?iriBarra rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_OFIMATICA.' .
							?iriBarra :nombreAplicacionPrograma ?nombreBarra .
							?iriBarra :versionAplicacionPrograma ?versionBarra .
						}
						ORDER BY
							?nombreAplicacionBarra ?versionBarra
						'.$desplazamiento.'
						'.$limite.'
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$barras[$row['iriBarra']] = self::llenarBarra($row);
					}
				}

				return $barras;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>