<?php
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/barraFormato.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETBarraFormato
	{
		public static function buscarBarras()
		{
			$preMsg = 'Error al buscar las instancias de barras de formato.';
			$barras = array();

			try
			{
				// Obtener la instancia de barras de formato, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriBarra ?nombreBarra ?versionBarra
						WHERE
						{
							?iriBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_FORMATO.' .
							?iriBarra :nombreAplicacionPrograma ?nombreBarra .
							?iriBarra :versionAplicacionPrograma ?versionBarra .
						}
						ORDER BY
							?nombreBarra ?versionBarra
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

		public static function existeBarra(EntidadInstanciaETBarraFormato $barra)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de barra de formato.';

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

				// Verificar si existe una instancia de la barra de formato con el mismo nombre y versión,
				// que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_FORMATO.' .
							?instanciaBarra :nombreAplicacionPrograma "'.$barra->getNombre().'"^^xsd:string .
							?instanciaBarra :versionAplicacionPrograma "'.$barra->getVersion().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de la barra de formato" .
							"(nombre = '".$barra->getNombre()."', versión = '".$barra->getVersion()."'). Detalles:\n" . join("\n", $errors));

					return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		// Guarda una nueva instancia de barra de dibujo, y retorna su iri
		public static function guardarBarra(EntidadInstanciaETBarraFormato $barra)
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
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_BARRA_FORMATO);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de barra de formato. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_BARRA_FORMATO.'\'');

				// Construir el fragmento de la nueva instancia de barra de formato
				// concatenando el framento de la clase barra de "SIGECOST_FRAGMENTO_BARRA_FORMATO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_BARRA_FORMATO . '_' . $secuencia;

				// Guardar la nueva instancia de barra de dibujo
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_BARRA_FORMATO.' .
							:'.$fragmentoIriInstancia.' :nombreAplicacionPrograma "'.$barra->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionAplicacionPrograma "'.$barra->getVersion().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de barra de formato. Detalles:\n". join("\n", $errors));

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
					throw new Exception('Error al intentar llenar la instancia de barra de formato. ' .
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$barra = new EntidadInstanciaETBarraFormato();
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
			$preMsg = 'Error al obtener una instancia de barra de formato, dado el iri.';

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
							?iriBarra rdf:type :'.SIGECOST_FRAGMENTO_BARRA_FORMATO.' .
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
			$preMsg = 'Error al obtener todas las instancias de barra de formatos.';
			$barras = array();

			try
			{
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