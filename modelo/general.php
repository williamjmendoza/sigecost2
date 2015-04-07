<?php
	class ModeloGeneral
	{
		public static function getSiguienteSecuenciaInstancia($fragmentoIriClase)
		{
			$preMsg = "Error al consultar el número de siguiente secuencia.";
			$fragmentoIriInstancia = $fragmentoIriClase . "_";
			// Se inicializa la secuencia en 1, en caso de que no existan instancias para la clase dada, por lo tanto se estaría creando la primera instancia. 
			$secuencia = 1;
			
			try {
				
				if ($fragmentoIriClase === null)
					throw new Exception($preMsg . ' El parámetro \'$fragmentoIriClase\' es nulo.');
				
				if (($fragmentoIriClase = trim($fragmentoIriClase)) == "")
					throw new Exception($preMsg . ' El parámetro \'$fragmentoIriClase\' está vacío.');
				
				// Obtener el iri para la instancia de la clase indicada, con mayor número consecutivo
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX mysql: <http://web-semantics.org/ns/mysql/>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
					SELECT
						?iriInstanciaMayorConsecutivo
					WHERE
					{
						?iriInstanciaMayorConsecutivo rdf:type :'.$fragmentoIriClase.'
						FILTER regex(
									mysql:substring(
										?iriInstanciaMayorConsecutivo,
										(mysql:length(:) + 1),
										(mysql:length(?iriInstanciaMayorConsecutivo) - mysql:length(:))
									),
									"'.$fragmentoIriInstancia.'"
						)
					}
					ORDER BY
						DESC(mysql:substring(
							?iriInstanciaMayorConsecutivo,
							( mysql:length(:) + mysql:length("'.$fragmentoIriInstancia.'") + 1 ),
							( mysql:length(?iriInstanciaMayorConsecutivo) - mysql:length(:) - mysql:length("'.$fragmentoIriInstancia.'") )
						) + 0)
					LIMIT 1
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . ' Instancia consultada \'<' . SIGECOST_IRI_ONTOLOGIA_NUMERAL .
							$fragmentoIriClase . '>\'. Detalles:\n'. join('\n', $errors));
				
				// Validar que se encontró al menos una instancia para la clase $fragmentoIriClase
				if($rows)
				{
					reset($rows);
					$iriInstancia = current($rows)['iriInstanciaMayorConsecutivo'];
					
					// Descomponer el iri de instancia de la clase indicada con mayor número consecutivo,
					// para obtener dicho número consecutivo, y luego sumarle el valor de uno (1)
					$secuencia = intval(substr(
							$iriInstancia,
							strlen(SIGECOST_IRI_ONTOLOGIA_NUMERAL) + strlen($fragmentoIriInstancia),
							strlen($iriInstancia) - strlen(SIGECOST_IRI_ONTOLOGIA_NUMERAL) - strlen($fragmentoIriInstancia)
					)) + 1;
				}
				
				return $secuencia;
				
			} catch (Exception $e){
				error_log($e, 0);
				return false;
			}
		}
		
		public static function getDatosBasicosClases(array $iriClases = null)
		{
			$preMsg = "Error al consultar los datos básicos de las clases.";
			$clases = array();
			$filtroClase = '';
			
			try
			{
				if ($iriClases === null)
					throw new Exception($preMsg . ' El parámetro \'$iriClases\' es nulo.');
				
				if (!is_array($iriClases))
					throw new Exception($preMsg . ' El parámetro \'$iriClases\' no es un arreglo.');
				
				if (count($iriClases) == 0)
					throw new Exception($preMsg . ' El parámetro \'$iriClases\' está vacío.');
				
				foreach ($iriClases AS $iriClase)
				{
					$filtroClase .= '
							'.( $filtroClase != '' ? '|| ' : '' ).'?clase = <'.$iriClase.'>';
				}
				
				if ($filtroClase == '') $filtroClase = '1 = 2';
				
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				
					SELECT
						?clase ?labelClase ?commentClase ?clasePadre
					WHERE
					{
						?clase rdf:type owl:Class .
						?clase rdfs:label ?labelClase .
						OPTIONAL  { ?clase rdfs:comment ?commentClase . } .
						OPTIONAL  { ?clase rdfs:subClassOf ?clasePadre . } .
						FILTER (
							'.$filtroClase.'
						) .
					}
					ORDER BY
						?labelClase
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				$clases = array();
				
				if (is_array($rows) && count($rows) > 0)
				{
					foreach ($rows AS $row)
					{
						$clases[$row['clase']] = $row;
					}
				}
					
				return $clases;
				
			} catch (Exception $e)
			{
				error_log($e, 0);
				return false;
			}
		}
		
		public static function getConfInitial($var)
		{
			if (array_key_exists($var, $GLOBALS['SigecostInitialVars'])) {
				return $GLOBALS['SigecostInitialVars'][$var];
			}
			return null;
		}
		
		public static function agregarInstanciaAColeccion($instancia)
		{
			$preMsg = "Error al agregar una instancia a una colección.";
			$perteneceAColeccion = null;
			$ultimoNodo = null;
			
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if (($instancia=trim($instancia)) == '')
					throw new Exception($preMsg . ' El parámetro \'$instancia\' está vacío.');
				
				// Consultar si la instancia pertenece a una colección
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						ASK
						{
							?nodo ?property <'.$instancia.'> .
							FILTER (
								?property = rdf:first
								|| ?property = rdf:rest
							) .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if($result['result'] === true) // Ya pertenece a una colección
				 return true;
				
				// Buscar el último miembro de la colección
				$query = '
					PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					SELECT
						?nodo
					WHERE
					{
						<'.$instancia.'> rdf:type ?clase .
						?ultimaInstancia rdf:type ?clase .
						?nodo rdf:first ?ultimaInstancia .
						?nodo rdf:rest rdf:nil .
					}
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . " Error al buscar el último miembro de la colección. Detalles:\n". join("\n", $errors));
				
				if (is_array($rows) && count($rows) > 0)
				{
					$row = current($rows);
					$ultimoNodo = $row['nodo'];
				}
				
				if(!$ultimoNodo || $ultimoNodo == null || $ultimoNodo == '')
					throw new Exception($preMsg . " Error al cargar el último miembro de la colección. Detalles:\n". join("\n", $errors));
				
				// Guardar los registros para agregar la instancia como último mimbro de la colección
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			
					INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
					{
						?nodo rdf:rest [ rdf:first <'.$instancia.'>; rdf:rest rdf:nil ]
					}
					WHERE
					{
						<'.$instancia.'> rdf:type ?clase .
						?ultimaInstancia rdf:type ?clase .
						?nodo rdf:first ?ultimaInstancia .
						?nodo rdf:rest rdf:nil .
					}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg. "Error al agregar la instancia como ultimo miembro de la coleccion. Detalles:\n". join("\n", $errors));
				
				// Borrar el registro con el rdf:nill del último nodo anterior
				$query = '
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
					DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
					{
						'.$ultimoNodo.' rdf:rest rdf:nil
					}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . " No se pudo eliminar el registro con el rdf:nil. Detalles:\n" . join("\n", $errors));
				
				if($result["result"]["t_count"] == 0)
					throw new Exception($preMsg . ' Detalles: No se eliminó ningún registro.');
				
				return true;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
	}
?>