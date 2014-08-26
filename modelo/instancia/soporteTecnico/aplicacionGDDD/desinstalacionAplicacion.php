<?php
	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion
	{
		public static function buscarInstancias(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño.';
			
			$instancias = array();
			
			try
			{
				// Buscar las instancias de soporte técnico en impresora para la instalación de impresora
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						SELECT
							?iri
							?urlSoporteTecnico
							?iriAplicacion
							?nombreAplicacion
							?versionAplicacion
							?iriSistemaOperativo
							?nombreSistemaOperativo
							?versionSistemaOperativo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
							?iri :aplicacionGraficaDigitalDibujoDiseno ?iriAplicacion .
							?iri :sobreSistemaOperativo ?iriSistemaOperativo .
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
							?iriSistemaOperativo rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iriSistemaOperativo :nombreSistemaOperativo ?nombreSistemaOperativo .
							?iriSistemaOperativo :versionSistemaOperativo ?versionSistemaOperativo .
						}
						ORDER BY
							?nombreAplicacion
							?versionAplicacion
							?nombreSistemaOperativo
							?versionSistemaOperativo
							?urlSoporteTecnico
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
		public static function existeInstancia(EntidadInstanciaSTAplicacionGDDDDesinstalacionAplicacion $instancia)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño.';
				
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if($instancia->getUrlSoporteTecnico() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getUrlSoporteTecnico()\' está vacío.');
				
				if($instancia->getAplicacionPrograma() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()\' es nulo.');
				
				if($instancia->getAplicacionPrograma()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()->getIri()\' está vacío.');
				
				if($instancia->getSistemaOperativo() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getSistemaOperativo()\' es nulo.');
				
				if($instancia->getSistemaOperativo()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getSistemaOperativo()->getIri()\' está vacío.');
				
				// Verificar si existe una instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño;
				// con el mismo url de soporte técnico, con la misma aplicación y el mismo sistema operativo; que la instancia pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				
						ASK
						{
							_:instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							_:instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							_:instanciaST :aplicacionGraficaDigitalDibujoDiseno <'.$instancia->getAplicacionPrograma()->getIri().'> .
							_:instanciaST :sobreSistemaOperativo <'.$instancia->getSistemaOperativo()->getIri().'> .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de soporte técnico para la desinstalación de una aplicación gráfica digital," .
							" dibujo y diseño (uRLSoporteTecnico = '" . $instancia->getUrlSoporteTecnico() . "', aplicacionGraficaDigitalDibujoDiseno = '" .
							$instancia->getAplicacionPrograma()->getIri()."', sobreSistemaOperativo = '".$instancia->getSistemaOperativo()->getIri() .
							"'). Detalles:\n" . join("\n", $errors));
				
				return $result['result'];
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		// Guarda una nueva instancia de soporte técnico para la desinstalación de una aplicacion gráfica digital, dibujo y diseño, y retorna su iri
		public static function guardarInstancia(EntidadInstanciaSTAplicacionGDDDDesinstalacionAplicacion $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño.';
				
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if($instancia->getUrlSoporteTecnico() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getUrlSoporteTecnico()\' está vacío.');
				
				if($instancia->getAplicacionPrograma() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()\' es nulo.');
				
				if($instancia->getAplicacionPrograma()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()->getIri()\' está vacío.');
				
				if($instancia->getSistemaOperativo() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getSistemaOperativo()\' es nulo.');
				
				if($instancia->getSistemaOperativo()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getSistemaOperativo()->getIri()\' está vacío.');
				
				// Consultar el número de secuencia para la siguiente instancia de soporte técnico para la desinstalación de una aplicacion gráfica digital, dibujo y diseño, a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO);
				
				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de soporte técnico para la desinstalación de una palicación gráfica digital, dibujo y diseño. ' .
						'No se pudo obtener el número de la siguiente secuencia para la instancia de la clase \'' .
						SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.'\'');
					
				// Construir el fragmento de la nueva instancia concatenando el framento de la clase "SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO . '_' . $secuencia;
					
				// Guardar la nueva instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
					
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :aplicacionGraficaDigitalDibujoDiseno <'.$instancia->getAplicacionPrograma()->getIri().'> .
							:'.$fragmentoIriInstancia.' :sobreSistemaOperativo <'.$instancia->getSistemaOperativo()->getIri().'> .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño. Detalles:\n" .
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
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar la instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño. '.
							'Detalles: el parámetro \'$row\' no es un arreglo.');
		
					$instancia = new EntidadInstanciaSTAplicacionGDDDDesinstalacionAplicacion();
					$instancia->setIri($row['iri']);
					$instancia->setUrlSoporteTecnico($row['urlSoporteTecnico']);
		
					$aplicacion = new EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno();
					$aplicacion->setIri($row['iriAplicacion']);
					$aplicacion->setNombre($row['nombreAplicacion']);
					$aplicacion->setVersion($row['versionAplicacion']);
					$instancia->setAplicacionPrograma($aplicacion);
						
					$sistemaOperativo = new EntidadInstanciaETSistemaOperativo();
					$sistemaOperativo->setIri($row['iriSistemaOperativo']);
					$sistemaOperativo->setNombre($row['nombreSistemaOperativo']);
					$sistemaOperativo->setVersion($row['versionSistemaOperativo']);
					$instancia->setSistemaOperativo($sistemaOperativo);
		
					return $instancia;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerInstanciaPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño, dado el iri.';
				
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
							?iri
							?urlSoporteTecnico
							?iriAplicacion
							?nombreAplicacion
							?versionAplicacion
							?iriSistemaOperativo
							?nombreSistemaOperativo
							?versionSistemaOperativo
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_DESINSTALACION_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
							?iri :aplicacionGraficaDigitalDibujoDiseno ?iriAplicacion .
							?iri :sobreSistemaOperativo ?iriSistemaOperativo .
							FILTER (?iri = <'.$iri.'>) .
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
							?iriSistemaOperativo rdf:type :'.SIGECOST_FRAGMENTO_SISTEMA_OPERATIVO.' .
							?iriSistemaOperativo :nombreSistemaOperativo ?nombreSistemaOperativo .
							?iriSistemaOperativo :versionSistemaOperativo ?versionSistemaOperativo .
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