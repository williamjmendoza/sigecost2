<?php

	// Entidades
	require_once( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	
	// Lib
	require_once( SIGECOST_LIB_PATH . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/general.php' );
	
	class ModeloInstanciaETSistemaOperativo
	{
		public static function buscarSistemasOperativos()
		{
			$preMsg = 'Error al buscar las instancias de sistemas operativos.';
			$sistemasOperativos = array();
			try
			{
				// Obtener las instancias de los sistemas operativos
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?nombre ?version
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
						}
						ORDER BY
							?nombre ?version
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$sistemasOperativos[$row['iri']] = self::llenarSistemaOperativo($row);
					}
				}
				
				return $sistemasOperativos;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function existeSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de sistema operativo.';
			try
			{
				if ($sistemaOperativo === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo\' es nulo.');
		
				if ($sistemaOperativo->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' es nulo.');
		
				if ($sistemaOperativo->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' está vacío.');
		
				if ($sistemaOperativo->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' es nulo.');
		
				if ($sistemaOperativo->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' está vacío.');
		
				// Verificar si existe un sistema operativo con el mismo nombre y versión, que el pasado por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							_:instanciaSistemaOperativo rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							_:instanciaSistemaOperativo :nombreSistemaOperativo "'.$sistemaOperativo->getNombre().'"^^xsd:string .
							_:instanciaSistemaOperativo :versionSistemaOperativo "'.$sistemaOperativo->getVersion().'"^^xsd:string .
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia del sistema operativo " .
							"(nombre = '".$sistemaOperativo->getNombre()."', versión = '".$sistemaOperativo->getVersion().
							"'). Detalles:\n" . join("\n", $errors));
		
					return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de sistema operativo, y retorno su iri
		public static function guardarSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo)
		{
			$preMsg = 'Error al guardar el sistema operativo.';
				
			try
			{
				if ($sistemaOperativo === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo\' es nulo.');
		
				if ($sistemaOperativo->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' es nulo.');
		
				if ($sistemaOperativo->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getNombre()\' está vacío.');
		
				if ($sistemaOperativo->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' es nulo.');
		
				if ($sistemaOperativo->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$sistemaOperativo->getVersion()\' está vacío.');
		
				// Consultar el número de secuencia para la siguiente instancia de sistema operativo a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO);
		
				// Validar si hubo errores obteniendo el siguiente número de secuencia de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de sistema operativo. No se pudo obtener el número '.
							'de la siguiente secuencia para la instancia de la clase \''.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.'\'');
		
					// Construir el fragmento de la nueva instancia de sistema operativo
					// conctenando el framento de la clase sistema operativo "SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO"
					// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
					$fragmentoIriInstancia = SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO . '_' . $secuencia;
		
					// Guardar la nueva instancia de sistema operativo
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							:'.$fragmentoIriInstancia.' :nombreSistemaOperativo "'.$sistemaOperativo->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionSistemaOperativo "'.$sistemaOperativo->getVersion().'"^^xsd:string .
						}
				';
		
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception("Error al guardar la instancia de sistema operativo. Detalles:\n". join("\n", $errors));
		
					return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarSistemaOperativo($row)
		{
			try {
				$impresora = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de sistema operativo. '.
						'Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$sistemaOperativo = new EntidadInstanciaETSistemaOperativo();
				$sistemaOperativo->setIri($row['iri']);
				$sistemaOperativo->setNombre($row['nombre']);
				$sistemaOperativo->setVersion($row['version']);
		
				return $sistemaOperativo;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerSistemaOperativoPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de sistema operativo dado el iri.';
				
			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
		
				// Obtener la instancia de sistema operativo dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri ?nombre ?version
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iri :nombreSistemaOperativo ?nombre .
							?iri :versionSistemaOperativo ?version .
							FILTER regex(str(?iri), "'.$iri.'")
						}
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarSistemaOperativo(current($rows));
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