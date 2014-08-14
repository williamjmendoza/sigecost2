<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaSTImpresoraRepararImpresionCorrida
	{
		public static function buscarInstancias()
		{
			$preMsg = 'Error al buscar las instancias de soporte técnico en impresoras para repara impresión corrida.';
			$instancias = array();
			
			try
			{
				// Buscar las instancias de soporte técnico en impresora para repara impresión corrida
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
							?iriEquipoReproduccion rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?iriEquipoReproduccion :marcaEquipoReproduccion ?marcaEquipoReproduccion .
							?iriEquipoReproduccion :modeloEquipoReproduccion ?modeloEquipoReproduccion .
						}
						ORDER BY
							?urlSoporteTecnico ?marcaEquipoReproduccion ?modeloEquipoReproduccion
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
				
				// Verificar si existe una instancia de soporte técnico en impresora para corregir impresión manchada
				// con el mismo url de soporte técnico y la misma impresora, que la instancia pasada por parámetros 
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							_:instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
							_:instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							_:instanciaST :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
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
		
		// Guarda una nueva instancia de soporte técnico en impresora para corregir impresión manchada, y retorna su iri
		public static function guardarInstancia(EntidadInstanciaSTImpresoraRepararImpresionCorrida $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte técnico en impresora para REPARAR IMPRESION CORRIDA.';
			
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
				
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_REPARAR_IMPRESION_CORRIDA.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :enImpresora <'.$instancia->getEquipoReproduccion()->getIri().'> .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte técnico en impresora para reapara impresión corrida. Detalles:\n" .
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
					throw new Exception('Error al intentar llenar la instancia de soporte técnico en impresora parareapara impresión corrida. '.
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
