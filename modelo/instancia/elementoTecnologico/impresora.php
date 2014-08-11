<?php

	// Entidades	
	require_once( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php' );
	
	// Lib
	require_once( SIGECOST_LIB_PATH . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/general.php' );

	class ModeloInstanciaETImpresora
	{
		public static function existeImpresora(EntidadInstanciaETImpresora $impresora)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de impresora.';
			
			try
			{
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');
		
				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');
		
				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');
		
				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');
		
				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');
		
				// Verificar si existe una impresora con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			
						ASK
						{
							_:instanciaImpresora rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							_:instanciaImpresora :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							_:instanciaImpresora :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de impresora " .
							"(marca = '".$impresora->getMarca()."', modelo = '".$impresora->getModelo()."'). Detalles:\n" . join("\n", $errors));
		
					return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de impresora, y retorno su iri
		public static function guardarImpresora(EntidadInstanciaETImpresora $impresora)
		{
			$preMsg = 'Error al guardar la impresora.';
			
			try
			{
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');
				
				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');
				
				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');
				
				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');
				
				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');
				
				// Consultar el número de secuencia para la siguiente instancia de impresora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_IMPRESORA);
				
				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de impresora. No se pudo obtener el número de la siguiente secuencia '.  
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_IMPRESORA.'\'');
				
				// Construir el fragmento de la nueva instancia de impresora
				// conctenando el framento de la clase impresora "SIGECOST_FRAGMENTO_IMPRESORA"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_IMPRESORA . '_' . $secuencia;
				
				// Guardar la nueva instancia de impresora
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
						
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .		
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de impresora. Detalles:\n". join("\n", $errors));
				
				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarImpresora($rows)
		{
			try {
				$impresora = null;
				
				if(!is_array($rows))
					throw new Exception('Error al intentar llenar la instancia de impresora. Detalles: el parámetro \'$rows\' no es un arreglo.');
				
				$impresora = new EntidadInstanciaETImpresora();
				$impresora->setIri($rows['iri']);
				$impresora->setMarca($rows['marca']);
				$impresora->setModelo($rows['modelo']);
				
				return $impresora;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerImpresoraPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de impresora dado el iri.';
			
			try {
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
				
				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				
				// Obtener la instancia de impresora dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						SELECT
							?iri ?marca ?modelo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?iri :marcaEquipoReproduccion ?marca .
							?iri :modeloEquipoReproduccion ?modelo .
							FILTER regex(str(?iri), "'.$iri.'")
						}
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarImpresora(current($rows));
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