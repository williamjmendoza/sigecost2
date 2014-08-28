<?php
	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );

// restablecer barra herramientas funcion formato dibujo
	class ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD
	{
		public static function buscarInstancias(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática.';

			$instancias = array();
			$limite = '';
			$desplazamiento = '';

			try
			{
				if($parametros !== null && count($parametros) > 0){
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
				}
				// Buscar las instancias de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

						SELECT
							?iri
							?urlSoporteTecnico
							?iriAplicacion
							?nombreAplicacion
							?versionAplicacion
						WHERE
						{
							'.self::buscarInstanciasSubQuery().'
						}
						ORDER BY
							?nombreAplicacion
							?versionAplicacion
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
			$preMsg = 'Error al buscar el contador de las instancias de soporte técnico en aplicacion ofimatica para restablecer barra herramientas funcion formato dibujo de aplicacion ofimatica.';

			// Buscar la cantidad de instancias de soporte técnico en aplicacion ofimatica para restablecer barra herramientas funcion formato dibujo de aplicacion ofimatica
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
				?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
				OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
				?iri :aplicacionOfimatica ?iriAplicacion .
				?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_OFIMATICA.' .
				?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
				?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
			';
		}

		public static function existeInstancia(EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD $instancia)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de soporte técnico pararestablecer barra herramientas funcion formato dibujo de una aplicación ofimática. ';

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

				// Verificar si existe una instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática,
				// con el mismo url de soporte técnico y con la misma aplicación; que la instancia pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							_:instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							_:instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							_:instanciaST :aplicacionOfimatica <'.$instancia->getAplicacionPrograma()->getIri().'> .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación" .
							" ofimática (uRLSoporteTecnico = '" . $instancia->getUrlSoporteTecnico() . "', aplicacionOfimatica = '" .
							$instancia->getAplicacionPrograma()->getIri() . "'). Detalles:\n" . join("\n", $errors));

				return $result['result'];

			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}

		public static function guardarInstancia(EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática.';

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

				// Consultar el número de secuencia para la siguiente instancia de soporte técnico pararestablecer barra herramientas funcion formato dibujo de una aplicación ofimática, a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO);

				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática. ' .
						'No se pudo obtener el número de la siguiente secuencia para la instancia de la clase \'' .
						SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.'\'');

				// Construir el fragmento de la nueva instancia concatenando el framento de la clase "SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO . '_' . $secuencia;

				// Guardar la nueva instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :aplicacionOfimatica <'.$instancia->getAplicacionPrograma()->getIri().'> .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática. Detalles:\n" .
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
					throw new Exception('Error al intentar llenar la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática. '.
							'Detalles: el parámetro \'$row\' no es un arreglo.');

					$instancia = new EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD();
					$instancia->setIri($row['iri']);
					$instancia->setUrlSoporteTecnico($row['urlSoporteTecnico']);

					$aplicacion = new EntidadInstanciaETAplicacionOfimatica();
					$aplicacion->setIri($row['iriAplicacion']);
					$aplicacion->setNombre($row['nombreAplicacion']);
					$aplicacion->setVersion($row['versionAplicacion']);
					$instancia->setAplicacionPrograma($aplicacion);

					return $instancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function obtenerInstanciaPorIri($iri)
		{
			$preMsg = 'Error al obtener una instancia de soporte técnico  para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática, dado el iri.';

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
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
							?iri :aplicacionOfimatica ?iriAplicacion .
							FILTER (?iri = <'.$iri.'>) .
							?iriAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_OFIMATICA.' .
							?iriAplicacion :nombreAplicacionPrograma ?nombreAplicacion .
							?iriAplicacion :versionAplicacionPrograma ?versionAplicacion .
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