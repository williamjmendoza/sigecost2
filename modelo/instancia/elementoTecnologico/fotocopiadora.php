<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/fotocopiadora.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaETFotocopiadora
	{
		public static function buscarFotocopiadoras()
		{
			$preMsg = 'Error al buscar fotocopiadoras.';
			$fotocopiadoras = array();
			try
			{
				// Obtener las instancias de fotocopiadoras
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_FOTOCOPIADORA.' .
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