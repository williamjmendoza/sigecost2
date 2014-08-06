<?php
	class ModeloGeneral
	{
		public static function getSiguienteSecuenciaInstancia($fragmentoIriClase)
		{
			$preMsg = "Error al consultar el número de siguiente secuencia.";
			$fragmentoIriInstancia = $fragmentoIriClase . "_";
			// Borrar
			//$fragmentoIriInstancia = "ontologiasoportetecnicov1_Class";
			// Se inicializa la secuancia en 1, en caso de que no existan instancias para la clase dada, en cuyo caso se estaría creando la primera instancia. 
			$secuencia = 1;
			$iriOntologia = 'http://www.owl-ontologies.com/OntologySoporteTecnico.owl#';
			
			try {
				
				if ($fragmentoIriClase === null)
					throw new Exception($preMsg . ' El parámetro \'$fragmentoIriClase\' es nulo.');
				
				if (($fragmentoIriClase = trim($fragmentoIriClase)) == "")
					throw new Exception($preMsg . ' El parámetro \'$fragmentoIriClase\' está vacío.');
				
				$query = '
					PREFIX kb: <'.$iriOntologia.'>
					PREFIX mysql: <http://web-semantics.org/ns/mysql/>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
					SELECT
						?iriUltimaInstancia
					WHERE
					{
						?iriUltimaInstancia rdf:type kb:'.$fragmentoIriClase.'
						FILTER regex(mysql:substring(?iriUltimaInstancia, (mysql:length(kb:) + 1), (mysql:length(?iriUltimaInstancia) - mysql:length(kb:)) ),
							"'.$fragmentoIriInstancia.'")
					}
					ORDER BY
						DESC(mysql:substring(
							?iriUltimaInstancia,
							( mysql:length(kb:) + mysql:length("'.$fragmentoIriInstancia.'") + 1 ),
							( mysql:length(?iriUltimaInstancia) - mysql:length(kb:) - mysql:length("'.$fragmentoIriInstancia.'") )
						))
					LIMIT 1
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . ' Instancia consultada \'<' . $iriOntologia . $fragmentoIriClase . '>\'. Detalles:\n'. join('\n', $errors));
				
				// Validar que se encontró al menos una instancia para la clase $fragmentoIriClase
				if($rows)
				{
					reset($rows);
					$iriInstancia = current($rows)['iriUltimaInstancia'];
					
					$secuencia = intval(substr(
							$iriInstancia,
							strlen($iriOntologia) + strlen($fragmentoIriInstancia),
							strlen($iriInstancia) - strlen($iriOntologia) - strlen($fragmentoIriInstancia)
					)) + 1;
				}
				
				// Borrar
				/*
				echo "<pre>";
				print_r($secuencia);
				echo "</pre>";
				*/
				
				return $secuencia;
				
			} catch (Exception $e){
				error_log($e, 0);
				return false;
			}
		}
	}
?>