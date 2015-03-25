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
	}
?>