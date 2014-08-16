<?php
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaETAplicacionReproduccionSonidoVideo
	{
		public static function buscarAplicaciones()
		{
			$preMsg = 'Error al buscar las instancias de aplicación reproduccion sonido video.';
			$aplicaciones = array();
			
			try
			{
				// Obtener la instancia de aplicación reproduccion sonido video, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
						}
						ORDER BY
							?nombreAplicacion ?versionAplicacion
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$aplicaciones[$row['iriAplicacion']] = self::llenarAplicacion($row);
					}
				}
				
				return $aplicaciones;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function existeAplicacion(EntidadInstanciaETAplicacionReproduccionSonidoVideo $aplicacion)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de aplicación reproduccion sonido video.';
			
			try
			{
				if($aplicacion === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion\' es nulo.');
				
				if($aplicacion->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getNombre()\' es nulo.');
				
				if($aplicacion->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getNombre()\' está vacío.');
				
				if($aplicacion->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getVersion()\' es nulo.');
				
				if($aplicacion->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getVersion()\' está vacío.');
				
				// Verificar si existe una instancia de la aplicación reproduccion sonido video con el mismo nombre y versión,
				// que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							_:instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.' .
							_:instanciaAplicacion :nombreAplicacionPrograma "'.$aplicacion->getNombre().'"^^xsd:string .
							_:instanciaAplicacion :versionAplicacionPrograma "'.$aplicacion->getVersion().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de la aplicación reproduccion sonido video " .
							"(nombre = '".$aplicacion->getNombre()."', versión = '".$aplicacion->getVersion()."'). Detalles:\n" . join("\n", $errors));
				
					return $result['result'];
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de aplicación produccion reproduccion sonido video, y retorna su iri
		public static function guardarAplicacion(EntidadInstanciaETAplicacionReproduccionSonidoVideo $aplicacion)
		{
			$preMsg = 'Error al guardar la instancia de aplicación de reproduccion sonido y video .';
			
			try
			{
				if($aplicacion === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion\' es nulo.');
				
				if($aplicacion->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getNombre()\' es nulo.');
					
				if($aplicacion->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getNombre()\' está vacío.');
				
				if($aplicacion->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getVersion()\' es nulo.');
				
				if($aplicacion->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$aplicacion->getVersion()\' está vacío.');
					
				// Consultar el número de secuencia para la siguiente instancia de aplicación a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO);
				
				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de aplicación reproduccion sonido video. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.'\'');
					
				// Construir el fragmento de la nueva instancia de aplicación reproduccion sonido video
				// conctenando el framento de la clase aplicación "SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO . '_' . $secuencia;
				
				// Guardar la nueva instancia de aplicación reproduccion sonido y video
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.' .
							:'.$fragmentoIriInstancia.' :nombreAplicacionPrograma "'.$aplicacion->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionAplicacionPrograma "'.$aplicacion->getVersion().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de aplicación reproduccion sonido y video. Detalles:\n". join("\n", $errors));
				
				return SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarAplicacion($row)
		{
			try {
				$aplicacion = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de aplicación reproduccion sonido y video ' .
						'Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$aplicacion = new EntidadInstanciaETAplicacionReproduccionSonidoVideo();
				$aplicacion->setIri($row['iriAplicacion']);
				$aplicacion->setNombre($row['nombreAplicacion']);
				$aplicacion->setVersion($row['versionAplicacion']);
				
				return $aplicacion;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerAplicacionPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de aplicación reproduccion sonido y video, dado el iri.';
			
			try
			{
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
				
				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				
				// Obtener la instancia de aplicación Reproduccion Sonido y Video, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
							FILTER (?iriAplicacion = <'.$iri.'>) .
						}
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					return self::llenarAplicacion(current($rows));
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
			$preMsg = 'Error al obtener todas las instancias de aplicaciones reproduccion sonido y video.';
			$aplicaciones = array();
			
			try
			{
				// Obtener la instancia de aplicación reproduccion sonido y video, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_REPRODUCCION_SONIDO_VIDEO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
						}
						ORDER BY
							?nombreAplicacion ?versionAplicacion
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0){
					foreach ($rows AS $row){
						$aplicaciones[$row['iriAplicacion']] = self::llenarAplicacion($row);
					}
				}
				
				return $aplicaciones;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>