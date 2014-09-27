<?php
	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	require_once ( SIGECOST_PATH_MODELO . '/patron.php' );

// restablecer barra herramientas funcion formato dibujo
	class ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD
	{
		
		public static function actualizarInstancia(EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD $instancia)
		{
			$preMsg = 'Error al actualizar la instancia de soporte técnico en aplicacion de programa para restablecer barra de herramientas funcion formato dibujo.';
		
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if($instancia->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getIri()\' está vacío.');
				
				if($instancia->getAplicacionPrograma() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()\' es nulo.');
				
				if($instancia->getAplicacionPrograma()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()->getIri()\' está vacío.');
				
				if($instancia->getPatron() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getPatron()\' es nulo.');
				
				// Consultar la instancia, para obtener el código (urlSoporteTecnico) del patrón asociado a la instancia
				$instanciaGuardada = self::obtenerInstanciaPorIri($instancia->getIri());
				
				if($instanciaGuardada === false)
					throw new Exception($preMsg . ' No se pudo obtener la instancia guardada.');
				
				if($instanciaGuardada === null)
					throw new Exception($preMsg . ' La instancia que estaba guardada ya no existe en la base de datos.');
				
				if($instanciaGuardada->getUrlSoporteTecnico() != "" && $instanciaGuardada->getPatron() === null)
					throw new Exception($preMsg . ' No se pudo obtener el patrón asociado a la instancia que se desea actualizar.');
				
				// Crear el nombre del patrón de soporte técnico
				if(self::establecerNombrePatron($instancia) === false)
					throw new Exception($preMsg . ' No se pudo establecer el nombre del patrón de soporte técnico.');
				
				// Iniciar la transacción de patrones
				$resultTransactionPatrones = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransactionPatrones === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// No existe un patrón para la instancia que se desea actualizar, por lo que se debe crear un nuevo patrón
				if($instanciaGuardada->getPatron() === null)
				{
					$patron = new EntidadPatron();
					$patron->setNombre($instancia->getPatron()->getNombre());
					$patron->setSolucion($instancia->getPatron()->getSolucion());
					$patron->setUsuarioCreador($instancia->getPatron()->getUsuarioUltimaModificacion());
				
					// Guardar el patrón de soporte técnico
					if(($codigoPatron = ModeloPatron::guardarPatron($patron)) === false)
						throw new Exception($preMsg . " No se pudo crear y guardar el patrón de soporte técnico.");
				
				} else { // Ya existe un patrón para la instancia, en este caso solo se actualizará el patrón
				
					// Establecer el código del patrón de soporte técnico para la instancia que se desea actualizar
					$instancia->getPatron()->setCodigo($instanciaGuardada->getPatron()->getCodigo());
				
					// Actualizar el patrón de soporte técnico
					if(($codigoPatron = ModeloPatron::actualizarPatron($instancia->getPatron())) === false)
						throw new Exception($preMsg . " No se pudo actualizar el patrón de soporte técnico.");
				}
								
				// Iniciar la transacción
				// Borrar los datos anteriores de la instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?iri :aplicacionOfimatica ?iriAplicacion .
							?iri :uRLSoporteTecnico ?urlSoporteTecnico .
						}
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							?iri :aplicacionOfimatica ?iriAplicacion .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
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
				// Guardar los datos actualizados de la instancia
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							<'.$instancia->getIri().'> :uRLSoporteTecnico "'.$codigoPatron.'"^^xsd:string .
							<'.$instancia->getIri().'> :aplicacionOfimatica <'.$instancia->getAplicacionPrograma()->getIri().'> .
						}
				';
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					// Excepción porque no se pudieron guardar los datos actualizados de la instancia, para que se ejecute el Rollback
					throw new Exception($preMsg . " No se pudieron guardar los datos actualizados de la instancia. Detalles:\n" . join("\n", $errors));
				
				// Commit de la transacción
				
				// Commit de la transacción de patrones
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				return $instancia->getIri();
				
			} catch (Exception $e) {
				// Rollback de la transacción
				if(isset($resultTransactionPatrones) && $resultTransactionPatrones === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}			
		
		public static function buscarInstancias(array $parametros = null)
		{
			$preMsg = 'Error al buscar las instancias de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática.';

			$instancias = array();
			$limite = '';
			$desplazamiento = '';
			$codigosPatrones = array();

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
						if(isset($row['urlSoporteTecnico']) && $row['urlSoporteTecnico'] != "")
							$codigosPatrones[] = $row['urlSoporteTecnico'];
					}
				}
				
				// Buscar los patrones asociados a cada instancia
				if(count($codigosPatrones) > 0)
					$patrones = ModeloPatron::obtenerPatronesPorCodigos($codigosPatrones);
				
				// Establecer el patrón encontrado, a su respectiva instancia
				if(is_array($patrones) && count($patrones) > 0)
				{
					foreach ($instancias AS $instancia){
						if($instancia->getUrlSoporteTecnico() && isset($patrones[$instancia->getUrlSoporteTecnico()]))
							$instancia->setPatron($patrones[$instancia->getUrlSoporteTecnico()]);
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
				
				// Si $instancia->getIri() está presente, dicho iri de instancia será ignorado en la verificación
				$filtro = ($instancia->getIri() !== null && $instancia->getIri() != '') ? 'FILTER (?instanciaST != <'.$instancia->getIri().'>) .' : '';

				// Verificar si existe una instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática,
				// con el mismo url de soporte técnico y con la misma aplicación; que la instancia pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

						ASK
						{
							?instanciaST rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							?instanciaST :uRLSoporteTecnico "'.$instancia->getUrlSoporteTecnico().'"^^xsd:string .
							?instanciaST :aplicacionOfimatica <'.$instancia->getAplicacionPrograma()->getIri().'> .
							'.$filtro.'
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

		public static function eliminarInstancia($iri)
		{
			$preMsg = 'Error al eliminar la instancia de soporte técnico en aplicación ofimática para restablecer barra de función formato dibujo.';
				
			try
			{
				if($iri === null)
					throw new Exception($preMsg . ' El parámetro \'$iri\' es nulo.');
		
				if($iri == "")
					throw new Exception($preMsg . ' El parámetro \'$iri\' está vacío.');
				
				// Consultar la instancia, para obtener el código (urlSoporteTecnico) del patrón asociado a la instancia
				$instanciaGuardada = self::obtenerInstanciaPorIri($iri);
				
				if($instanciaGuardada === false)
					throw new Exception($preMsg . ' No se pudo obtener la instancia guardada.');
				
				if($instanciaGuardada === null)
					throw new Exception($preMsg . ' La instancia que estaba guardada ya no existe en la base de datos.');
				
				if($instanciaGuardada->getUrlSoporteTecnico() != "" && $instanciaGuardada->getPatron() === null)
					throw new Exception($preMsg . ' No se pudo obtener el patrón asociado a la instancia que se desea actualizar.');
				
				// Iniciar la transacción de patrones
				$resultTransactionPatrones = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransactionPatrones === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Existe un patrón asociado a la instancia que se desea eliminar, por lo que se debe eliminar el patrón
				if($instanciaGuardada->getPatron() !== null)
				{
					// Eliminar el patrón de soporte técnico
					if(($codigoPatron = ModeloPatron::eliminarPatron($instanciaGuardada->getPatron()->getCodigo())) === false)
						throw new Exception($preMsg . " No se pudo eliminar el patrón de soporte técnico.");
				}
				
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
						?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
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
				
				// Commit de la transacción de patrones
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit de la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());

				return $iri;
				
			} catch (Exception $e) {
				// Rollback de la transacción
				if(isset($resultTransactionPatrones) && $resultTransactionPatrones === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
		
		public static function establecerNombrePatron(EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD $instancia)
		{
			$preMsg = "Error al establecer el nombre del patrón de soporte técnico para la instancia" .
				" de s. t. para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática.";
		
			try
			{
				$aplicacion = ModeloInstanciaETAplicacionOfimatica::obtenerAplicacionPorIri($instancia->getAplicacionPrograma()->getIri());
		
				if($aplicacion === null || $aplicacion === false)
					throw new Exception($preMsg . ' Los datos de la aplicación ofimática no pudieron ser consultados.');
		
				$nombre = SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO .
					" aplicaci&oacute;n " . $aplicacion->getNombre() . " " . $aplicacion->getVersion();
		
				$instancia->getPatron()->setNombre($nombre);
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

		public static function guardarInstancia(EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD $instancia)
		{
			$preMsg = 'Error al guardar la instancia de soporte técnico para restablecer barra herramientas ' .
				'funcion formato dibujo de una aplicación ofimática.';

			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');

				if($instancia->getAplicacionPrograma() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()\' es nulo.');

				if($instancia->getAplicacionPrograma()->getIri() == "")
					throw new Exception($preMsg . ' El parámetro \'$instancia->getAplicacionPrograma()->getIri()\' está vacío.');
				
				if($instancia->getPatron() === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia->getPatron()\' es nulo.');
				
				// Crear el nombre del patrón de soporte técnico
				if(self::establecerNombrePatron($instancia) === false)
					throw new Exception($preMsg . ' No se pudo establecer el nombre del patrón de soporte técnico.');
				
				// Iniciar la transacción de patrones
				$resultTransactionPatrones = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransactionPatrones === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción de patrones. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Guardar el patrón de soporte técnico
				if(($codigoPatron = ModeloPatron::guardarPatron($instancia->getPatron())) === false)
					throw new Exception($preMsg . " No se pudo guardar el patrón.");

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

						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_S_T_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO.' .
							:'.$fragmentoIriInstancia.' :uRLSoporteTecnico "'.$codigoPatron.'"^^xsd:string .
							:'.$fragmentoIriInstancia.' :aplicacionOfimatica <'.$instancia->getAplicacionPrograma()->getIri().'> .
						}
				';

				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de soporte técnico para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática. Detalles:\n" .
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
			$patron = null;

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
				
				if (!is_array($rows) || count($rows) <= 0)
					return null;
				
				reset($rows);
				$row = current($rows);
				$instancia = self::llenarInstancia($row);
				
				if($instancia === false)
					throw new Exception($preMsg . "  No se pudo llenar la instancia.");
				
				if(isset($row['urlSoporteTecnico']) && $row['urlSoporteTecnico'] != "")
					$patron = ModeloPatron::obtenerPatronPorCodigo($row['urlSoporteTecnico']);
				
				$instancia->setPatron($patron);
				
				return $instancia;

			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}

	}
?>
