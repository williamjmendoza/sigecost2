<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/impresora/corregirImpresionManchada.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaSTImpresoraCorregirImpresionManchada
	{
		public static function buscarInstancias(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de soporte t�cnico en impresoras para corregir impresi�n manchada.';
			$instancias = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}

				// Buscar las instancias de soporte t�cnico en impresora para corregir impresi�n manchada
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri
							?urlSoporteTecnico
							?iriEquipoReproduccion
							?marcaEquipoReproduccion
							?modeloEquipoReproduccion
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?marcaEquipoReproduccion
							?modeloEquipoReproduccion
							?urlSoporteTecnico
						'.$desplazamiento.'
						'.$limite.'
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$instancias[$row['iri']] = self::llenarInstancia($row);
					}
				}

				return $instancias;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias de soporte t�cnico en impresoras para la Corregir Impresion Manchada.';

			// Buscar la cantidad de instancias de soporte t�cnico en impresora para CorregirImpresionManchada de impresora
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
				throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA.' .
				OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
				?iri :enImpresora ?iriEquipoReproduccion .
				?iriEquipoReproduccion rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
				?iriEquipoReproduccion :marcaEquipoReproduccion ?marcaEquipoReproduccion .
				?iriEquipoReproduccion :modeloEquipoReproduccion ?modeloEquipoReproduccion .
			';
		}

		public static function existeInstancia(EntidadInstanciaSTImpresoraCorregirImpresionManchada $instancia)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de soporte t�cnico en impresora para corregir impresi�n manchada.';

			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El par�metro \'$instancia\' es nulo.');

				if($instancia->getUrlSoporteTecnico() == "")
					throw new Exception($preMsg . ' El par�metro \'$instancia->getUrlSoporteTecnico()\' est� vac�o.');

				if($instancia->getEquipoReproduccion() === null)
					throw new Exception($preMsg . ' El par�metro \'$instancia->getEquipoReproduccion()\' es nulo.');

				if($instancia->getEquipoReproduccion()->getIri() == "")
					throw new Exception($preMsg . ' El par�metro \'$instancia->getEquipoReproduccion()->getIri()\' est� vac�o.');
				
				// Si $instancia->getIri() está presente, dicho iri de instancia será igniorado en la verificación
				$filtro = ($instancia->getIri() !== null && $instancia->getIri() != '') ? 'FILTER (?instanciaST != <'.$instancia->getIri().'>) .' : '';

				// Verificar si existe una instancia de soporte t�cnico en impresora para corregir impresi�n manchada
				// con el mismo url de soporte t�cnico y la misma impresora, que la instancia pasada por par�metros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA.' .
							?instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							?instanciaST :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de soporte t�cnico en impresora para corregir" .
						" impresi�n manchada (uRLSoporteTecnico = '" . $instancia->getUrlSoporteTecnico() . "', enImpresion = '" .
						$instancia->getEquipoReproduccion()->getIri()."'). Detalles:\n" . join("\n", $errors));

				return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		// Guarda una nueva instancia de soporte t�cnico en impresora para corregir impresi�n manchada, y retorna su iri
		public static function guardarInstancia(EntidadInstanciaSTImpresoraCorregirImpresionManchada $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte t�cnico en impresora para corregir impresi�n manchada.';

			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El par�metro \'$instancia\' es nulo.');

				if($instancia->getUrlSoporteTecnico() == "")
					throw new Exception($preMsg . ' El par�metro \'$instancia->getUrlSoporteTecnico()\' est� vac�o.');

				if($instancia->getEquipoReproduccion() === null)
					throw new Exception($preMsg . ' El par�metro \'$instancia->getEquipoReproduccion()\' es nulo.');

				if($instancia->getEquipoReproduccion()->getIri() == "")
					throw new Exception($preMsg . ' El par�metro \'$instancia->getEquipoReproduccion()->getIri()\' est� vac�o.');

				// Consultar el n�mero de secuencia para la siguiente instancia de soporte t�cnico en impresora para corregir impresi�n manchada a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA);

				// Validar si hubo errores obteniendo el siguiente n�mero de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de soporte t�cnico en impresora para corregir impresi�n manchada. ' .
						'No se pudo obtener el n�mero de la siguiente secuencia para la instancia de la clase \'' .
						SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA.'\'');

				// Construir el fragmento de la nueva instancia de concatenando el framento de la clase "SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA"
				// con el el caracater underscore "_" y el n�mero de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA . '_' . $secuencia;

				// Guardar la nueva instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte t�cnico en impresora para corregir impresi�n manchada. Detalles:\n" .
						join("\n", $errors));

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarInstancia($row)
		{
			try {
				$impresora = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de soporte t�cnico en impresora para corregir impresi�n manchada. '.
						'Detalles: el par�metro \'$row\' no es un arreglo.');

				$instancia = new EntidadInstanciaSTImpresoraCorregirImpresionManchada();
				$instancia->setIri($row['iri']);
				$instancia->setUrlSoporteTecnico($row['urlSoporteTecnico']);

				$impresora = new EntidadInstanciaETImpresora();
				$impresora->setIri($row['iriEquipoReproduccion']);
				$impresora->setMarca($row['marcaEquipoReproduccion']);
				$impresora->setModelo($row['modeloEquipoReproduccion']);
				$instancia->setEquipoReproduccion($impresora);

				return $instancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerInstanciaPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de soporte t�cnico en impresora para corregir impresi�n manchada, dado el iri.';

			try
			{
				if ($iri === null)
					throw new Exception($preMsg . ' El par�metro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El par�metro \'$iri\' est� vac�o.');

				// Obtener la instancia dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?urlSoporteTecnico ?iriEquipoReproduccion ?marcaEquipoReproduccion ?modeloEquipoReproduccion
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_CORREGIR_IMPRESION_MANCHADA.' .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
							?iri :enImpresora ?iriEquipoReproduccion .
							FILTER (?iri = <'.$iri.'>) .
							?iriEquipoReproduccion rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?iriEquipoReproduccion :marcaEquipoReproduccion ?marcaEquipoReproduccion .
							?iriEquipoReproduccion :modeloEquipoReproduccion ?modeloEquipoReproduccion .
						}
				';

				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));

				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarInstancia(current($rows));
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
