<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	require_once ( SIGECOST_PATH_MODELO . '/patron.php' );

	class ModeloInstanciaSTImpresoraRepararImpresionCorrida
	{
		public static function buscarInstancias(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de soporte técnico en impresoras para repara impresión corrida.';
			$instancias = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Buscar las instancias de soporte técnico en impresora para repara impresión corrida
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
			$preMsg = 'Error al buscar el contador de las instancias de soporte t�cnico en impresoras para reparar impresion corrida.';

			// Buscar la cantidad de instancias de soporte t�cnico en impresora para reparar impresion corrida de impresora
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
				OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
				?iri :enImpresora ?iriEquipoReproduccion .
				?iriEquipoReproduccion rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
				?iriEquipoReproduccion :marcaEquipoReproduccion ?marcaEquipoReproduccion .
				?iriEquipoReproduccion :modeloEquipoReproduccion ?modeloEquipoReproduccion .
			';
		}

		public static function existeInstancia(EntidadInstanciaSTImpresoraRepararImpresionCorrida $instancia)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de soporte técnico en impresora para repara impresión corrida.';

			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');

				if($instancia->getUrlSoporteTecnico() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getUrlSoporteTecnico()\' está vacío.');

				if($instancia->getEquipoReproduccion() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEquipoReproduccion()\' es nulo.');

				if($instancia->getEquipoReproduccion()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEquipoReproduccion()->getIri()\' está vacío.');

				// Si $instancia->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($instancia->getIri() !== null && $instancia->getIri() != '') ? 'FILTER (?instanciaST != <'.$instancia->getIri().'>) .' : '';

				// Verificar si existe una instancia de soporte técnico en impresora para corregir impresión manchada
				// con el mismo url de soporte técnico y la misma impresora, que la instancia pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
							?instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							?instanciaST :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de soporte técnico en impresora para repara" .
						" impresión corrida (uRLSoporteTecnico = '" . $instancia->getUrlSoporteTecnico() . "', enImpresion = '" .
						$instancia->getEquipoReproduccion()->getIri()."'). Detalles:\n" . join("\n", $errors));

				return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function establecerNombrePatron(EntidadInstanciaSTImpresoraRepararImpresionCorrida $instancia)
		{
			$preMsg = "Error al establecer el nombre del patrón de soporte técnico para la instancia de s. t. en impresora para reparar impresión corrida";
			
			try
			{
				$impresora = ModeloInstanciaETImpresora::obtenerImpresoraPorIri($instancia->getEquipoReproduccion()->getIri());
				
				if($impresora === null || $impresora === false)
					throw new Exception($preMsg . ' Los datos de la impresora no pudieron ser consultados.');
				
				$instancia->getPatron()->setNombre(SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA . " enImpresora " . $impresora->getMarca() . " " . $impresora->getModelo());
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
			
		}

		// Guarda una nueva instancia de soporte técnico en impresora para corregir impresión manchada, y retorna su iri
		public static function guardarInstancia(EntidadInstanciaSTImpresoraRepararImpresionCorrida $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte técnico en impresora para reparar impresión corrida.';
			$resultTransactionPatrones = null;

			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');

				if($instancia->getEquipoReproduccion() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEquipoReproduccion()\' es nulo.');

				if($instancia->getEquipoReproduccion()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getEquipoReproduccion()->getIri()\' está vacío.');
				
				if($instancia->getPatron() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getPatron()\' es nulo.');
				
				if(self::establecerNombrePatron($instancia) === false)
					throw new Exception($preMsg . ' No se pudo establecer el nombre del patrón de soporte técnico.');
				
				// Iniciar la transacción de patrones
				$resultTransactionPatrones = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransactionPatrones === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Guardar el patrón de soporte técnico 
				if(($codigoPatron = ModeloPatron::guardarPatron($instancia->getPatron())) === false)
					throw new Exception($preMsg . " No se pudo guardar el patrón.");

				// Consultar el número de secuencia para la siguiente instancia de soporte técnico en impresora para corregir impresión manchada a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de soporte técnico en impresora para reparar impresión corrida. ' .
						'No se pudo obtener el número de la siguiente secuencia para la instancia de la clase \'' .
						SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.'\'');

				// Construir el fragmento de la nueva instancia de concatenando el framento de la clase "SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA . '_' . $secuencia;
				
				// Guardar la nueva instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$codigoPatron.'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte técnico en impresora para reapara impresión corrida. Detalles:\n" .
						join("\n", $errors));
					
				// Commit de la transacción de patrones
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());

				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;

			} catch (Exception $e) {
				if(isset($resultTransactionPatrones) && $resultTransactionPatrones === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}

		public static function llenarInstancia($row)
		{
			try {
				$impresora = null;

				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de soporte técnico en impresora para reparar impresión corrida. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$instancia = new EntidadInstanciaSTImpresoraRepararImpresionCorrida();
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
			$preMsg = 'Error al obtener una instancia de soporte técnico en impresora para corregir impresión manchada, dado el iri.';

			try
			{
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri ?urlSoporteTecnico ?iriEquipoReproduccion ?marcaEquipoReproduccion ?modeloEquipoReproduccion
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
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
