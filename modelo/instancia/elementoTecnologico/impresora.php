<?php

	// Entidades	
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETImpresora
	{
		public static function buscarImpresoras()
		{
			$preMsg = 'Error al buscar las instancias de impresoras.';
			$impresoras = array();
			try
			{
				// Obtener las instancias de las impresoras
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
						}
						ORDER BY
							?marca ?modelo
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$impresoras[$row['iri']] = self::llenarImpresora($row);
					}
				}
				
				return $impresoras;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
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
				
				// Si $impresora->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($impresora->getIri() !== null && $impresora->getIri() != '')
					? 'FILTER (?instanciaImpresora != <'.$impresora->getIri().'>) .' : '';
		
				// Verificar si existe una instancia de impresora con la misma marca y modelo, que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			
						ASK
						{
							?instanciaImpresora rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?instanciaImpresora :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							?instanciaImpresora :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
							'.$filtro.'
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
		
		// Guarda una nueva instancia de impresora, y retorna su iri
		public static function guardarImpresora(EntidadInstanciaETImpresora $impresora)
		{
			$preMsg = 'Error al guardar la instancia de impresora.';
			
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
						
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
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
		
		public static function llenarImpresora($row)
		{
			try {
				$impresora = null;
				
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de impresora. Detalles: el parámetro \'$row\' no es un arreglo.');
				
				$impresora = new EntidadInstanciaETImpresora();
				$impresora->setIri($row['iri']);
				$impresora->setMarca($row['marca']);
				$impresora->setModelo($row['modelo']);
				
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
							FILTER (?iri = <'.$iri.'>) .
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
		
		public static function obtenerTodasImpresoras()
		{
			$preMsg = 'Error al obtener todas las instancias de impresoras.';
			$impresoras = array();
			try
			{
				// Obtener todas las instancias de las impresoras
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
						}
						ORDER BY
							?marca ?modelo
				';
		
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
		
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$impresoras[$row['iri']] = self::llenarImpresora($row);
					}
				}
		
				return $impresoras;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>