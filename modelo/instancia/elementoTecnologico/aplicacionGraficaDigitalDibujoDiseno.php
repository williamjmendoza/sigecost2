<?php
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php' );

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

	class ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno
	{		
		public static function actualizarInstancia(EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de elemento tecnológico en instalacion de aplicacion gráfica dibujo digital y diseño.';
		
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
							?iri rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
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
		
		public static function buscarAplicaciones(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de aplicación gráfica digital, dibujo y diseño.';
			$aplicaciones = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}

				// Obtener la instancia de aplicación gráfica digital, dibujo y diseño, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?nombreAplicacion
							?versionAplicacion
						'.$desplazamiento.'
						'.$limite.'
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

		public static function buscarInstanciasTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de las instancias elemento tecnologico de aplicación gráfica digital, dibujo y diseño..';

			// Buscar la cantidad de instancias de elemento tecnologico aplicación gráfica digital, dibujo y diseño.
			$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

					SELECT
						(COUNT(?iriAplicacion) AS ?totalElementos)
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
				?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
				?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
				?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
			';

		}

		public static function existeAplicacion(EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno $aplicacion)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de aplicación gráfica digital, dibujo y diseño.';

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

				// Si $aplicacion->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($aplicacion->getIri() !== null && $aplicacion->getIri() != '')
					? 'FILTER (?instanciaAplicacion != <'.$aplicacion->getIri().'>) .' : '';

				// Verificar si existe una instancia de la aplicación gráfica digital, dibujo y diseño con el mismo nombre y versión,
				// que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							?instanciaAplicacion :nombreAplicacionPrograma "'.$aplicacion->getNombre().'"^^xsd:string .
							?instanciaAplicacion :versionAplicacionPrograma "'.$aplicacion->getVersion().'"^^xsd:string .
							'.$filtro.'
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de la aplicación gráfica digital, dibujo y diseño " .
							"(nombre = '".$aplicacion->getNombre()."', versión = '".$aplicacion->getVersion()."'). Detalles:\n" . join("\n", $errors));

				return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de elemento tecnol&oacute;ico aplicaci&oacute;n gr&aacute;fica digital dibujo y dise&ntilde;o.';
				
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
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
		
		public static function esInstanciaUtilizada($iri)
		{
			$preMsg = 'Error al verificar si está siendo utilizada una instancia de aplicación gráfica digital, dibujo y diseño.';
			
			try
			{
				if($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
				
				if($iri == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						ASK
						{
							?instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							?instanciaST :aplicacionGraficaDigitalDibujoDiseno ?instanciaAplicacion .
							FILTER (?instanciaAplicacion = <'.$iri.'>) .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al verificar si está siendo utilizada la instancia de la aplicación gráfica digital, dibujo y diseño." .
						" Detalles:\n" . join("\n", $errors));
				
				return $result['result'];
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de aplicación gráfica digital, dibujo y diseño, y retorna su iri
		public static function guardarAplicacion(EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno $aplicacion)
		{
			$preMsg = 'Error al guardar la instancia de aplicación gráfica digital, dibujo y diseño .';

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
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de aplicación gráfica digital, dibujo y diseño. No se pudo obtener el número de la siguiente secuencia '.
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.'\'');

				// Construir el fragmento de la nueva instancia de aplicación gráfica digital, dibujo y diseño
				// conctenando el framento de la clase aplicación "SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO . '_' . $secuencia;

				// Guardar la nueva instancia de aplicación gráfica digital, dibujo y diseño
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							:'.$fragmentoIriInstancia.' :nombreAplicacionPrograma "'.$aplicacion->getNombre().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :versionAplicacionPrograma "'.$aplicacion->getVersion().'"^^xsd:string .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de aplicación gráfica digital, dibujo y diseño. Detalles:\n". join("\n", $errors));
				
				// Agregar la instancia a una colección
				if(($result = ModeloGeneral::agregarInstanciaAColeccion(SIGECOST_IRI_ONTOLOGIA_NUMERAL.$fragmentoIriInstancia)) !== true)
					throw new Exception($preMsg . ' Error al intentar agregar la instancia a una colección.');

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
					throw new Exception('Error al intentar llenar la instancia de aplicación gráfica digital, dibujo y diseño. ' .
						'Detalles: el parámetro \'$row\' no es un arreglo.');

				$aplicacion = new EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno();
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
			$preMsg = 'Error al obtener una instancia de aplicación gráfica digital, dibujo y diseño, dado el iri.';

			try
			{
				if ($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');

				if (($iri=trim($iri)) == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');

				// Obtener la instancia de aplicación gráfica digital, dibujo y diseño, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
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

		public static function obtenerTodasAplicaciones()
		{
			$preMsg = 'Error al obtener todas las instancias de aplicaciones gráfica digital, dibujo y diseño.';
			$aplicaciones = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}

				// Obtener la instancia de aplicación gráfica digital, dibujo y diseño, dado el iri
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iriAplicacion ?nombreAplicacion ?versionAplicacion
						WHERE
						{
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
						}
						ORDER BY
							?nombreAplicacion ?versionAplicacion
						'.$desplazamiento.'
						'.$limite.'
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
