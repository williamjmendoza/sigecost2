<?php
	class ModeloGeneral
	{
		public static function getSiguienteSecuenciaInstancia($fragmentoIriClase)
		{
			$preMsg = "Error al consultar el número de siguiente secuencia.";
			$fragmentoIriInstancia = $fragmentoIriClase . "_";
			// Se inicializa la secuancia en 1, en caso de que no existan instancias para la clase dada, en cuyo caso se estaría creando la primera instancia. 
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
						))
					LIMIT 1
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . ' Instancia consultada \'<' . SIGECOST_IRI_ONTOLOGIA_NUMERAL . $fragmentoIriClase . '>\'. Detalles:\n'. join('\n', $errors));
				
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
	}
?>