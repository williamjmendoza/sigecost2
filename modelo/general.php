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
					$iriInstancia = current($rows);
					$iriInstancia = $iriInstancia['iriInstanciaMayorConsecutivo'];
					
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
		
		public static function perteneceInstanciaAColeccion($instancia)
		{
			$preMsg = "Error al verificar si una instancia pertenece a una colección.";
				
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
		
				return $result['result'];
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
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
				
				if(($perteneceAColeccion = self::perteneceInstanciaAColeccion($instancia)) === null)
					throw new Exception($preMsg . "  Error al verificar si la instancia pertenece a una colección");
				
				if($perteneceAColeccion === true) // Ya pertenece a una colección
				 return true;
				
				// Consultar si existe una colección de la clase de la instancia
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			
					ASK
					{
						<'.$instancia.'> rdf:type ?clase .
						?instanciaMiembro rdf:type ?clase .		
						?nodo ?property ?instanciaMiembro .
						FILTER (
							?property = rdf:first
							|| ?property = rdf:rest
						) .
					}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
				
				if($result['result'] === true) // Existe una colección donde puede ser agregada la instancia
				{
					
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
						reset($rows);
						$row = current($rows);
						$ultimoNodo = $row['nodo'];
					}
					
					if(!$ultimoNodo || $ultimoNodo == null || $ultimoNodo == '')
						throw new Exception($preMsg . " Error al cargar el último miembro de la colección. Detalles:\n". join("\n", $errors));
					
					// Guardar los registros para agregar la instancia como último mimbro de la colección
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?nodo rdf:rest (<'.$instancia.'>)
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
						throw new Exception($preMsg. " Error al agregar la instancia como ultimo miembro de la coleccion. Detalles:\n". join("\n", $errors));
					
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
					
				} else  // No existe una colección donde pueda ser agregada la instancia, así que se debe crear la colección
				{ 
					// Crear la colección y agregarle la instancia como primer miembro
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
					
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							
							[] rdf:type owl:AllDifferent; owl:distinctMembers (<'.$instancia.'>)
						}
					';
					
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
						
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg. " Error al crear la colección y agregar la instancia a esta. Detalles:\n". join("\n", $errors));
				}
				
				return true;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function eliminarInstanciaDeColeccion($instancia)
		{
			$preMsg = "Error al eliminar una instancia de una colección.";
			$perteneceAColeccion = null;
			$totalMiembros = null;
			$datos = null;
			
			try
			{
				if ($instancia === null)
					throw new Exception($preMsg . ' El parámetro \'$instancia\' es nulo.');
				
				if (($instancia=trim($instancia)) == '')
					throw new Exception($preMsg . ' El parámetro \'$instancia\' está vacío.');
				
				// Verificar si la instancia pertenece a una colección
				if(($perteneceAColeccion = self::perteneceInstanciaAColeccion($instancia)) === null)
					throw new Exception($preMsg . "  Error al verificar si la instancia pertenece a una colección");
				
				
				if($perteneceAColeccion !== true) // No pertenece a ninguna colección
					return true;
				
				// Contar el número de miembros de la colección
				$query = '
					PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
					SELECT DISTINCT
						(COUNT(?instanciaMiembro) AS ?totalMiembros)
					WHERE
					{
						<'.$instancia.'> rdf:type ?clase .
						?instanciaMiembro rdf:type ?clase .
						?nodo ?property ?instanciaMiembro .
						FILTER (
							?property = rdf:first
							|| ?property = rdf:rest
						) .
					}
				';
				
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');

				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
				if (is_array($rows) && count($rows) > 0){
					reset($rows);
					$totalMiembros = current($rows);
					$totalMiembros = $totalMiembros['totalMiembros'];
				} else {
					$totalMiembros = 0;
				}
				
				if($totalMiembros <= 0)
					throw new Exception($preMsg . "  No se pudo obtener el número de miembros de la colección de la clase de la instancia.");
				
				if($totalMiembros == 1) // Se debe eliminar la instancia miembro y toda la estructura de la colección
				{
					
					// Borrar las instancia miembro y la estructura de la colección
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX owl: <http://www.w3.org/2002/07/owl#>
						
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?nodo1 rdf:type owl:AllDifferent .
							?nodo1 owl:distinctMembers ?nodo2 .
							?nodo2 rdf:first <'.$instancia.'> .
							?nodo2 rdf:rest rdf:nil .
						}
						WHERE
						{
							?nodo1 rdf:type owl:AllDifferent .
							?nodo1 owl:distinctMembers ?nodo2 .
							?nodo2 rdf:first <'.$instancia.'> .
							?nodo2 rdf:rest rdf:nil .
						}
					';
					
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
					
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg . " . Detalles:\n" . join("\n", $errors));
					
					if($result["result"]["t_count"] == 0) {
						// Excepción porque no se pudieron borrar los datos de la instancia, para que se ejecute el Rollback
						throw new Exception($preMsg . ' Detalles: No se eliminó ningún registro.');
					}
					
				} else // Se debe eliminar la instancia miembro y corregir los apuntadores
				{
					
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						
						SELECT
							?nodoPrevio ?propiedadPrevia ?nodo ?nodoSiguiente 
						WHERE
						{
							?nodoPrevio ?propiedadPrevia ?nodo .
							?nodo rdf:first <'.$instancia.'> .
							?nodo rdf:rest ?nodoSiguiente .
							FILTER (
								?propiedadPrevia = owl:distinctMembers
								|| ?propiedadPrevia = rdf:rest
							) .
						}
					';
					
					$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
						
					if (is_array($rows) && count($rows) > 0){
						reset($rows);
						$datos = current($rows);
					} else 
						throw new Exception($preMsg . "  No se pudieron consultar los datos de la instancia miembro de la colección");
					
					// Guardar los registros para acomodar los apuntadores de los miembros de la colección, tras eliminar una instancia miembro
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					
						INSERT INTO <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							?nodoPrevio ?propiedadPrevia ?nodoSiguiente .
						}
						WHERE
						{
							?nodoPrevio ?propiedadPrevia ?nodo .
							?nodo rdf:first <'.$instancia.'> .
							?nodo rdf:rest ?nodoSiguiente .
							FILTER (
								?propiedadPrevia = owl:distinctMembers
								|| ?propiedadPrevia = rdf:rest
							) .	
						}
					';
						
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
						
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg. " Detalles:\n". join("\n", $errors));
						
					// Borrar la instacia miembro de la colección
					$query = '
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		
						DELETE FROM <'.SIGECOST_IRI_GRAFO_POR_DEFECTO.'>
						{
							'.$datos['nodoPrevio'].' <'.$datos['propiedadPrevia'].'> '.$datos['nodo'].' .
							'.$datos['nodo'].' rdf:first <'.$instancia.'> .
							'.$datos['nodo'].' rdf:rest '
								.($datos['nodoSiguiente']=='http://www.w3.org/1999/02/22-rdf-syntax-ns#nil' ? '<'.$datos['nodoSiguiente'].'>' : $datos['nodoSiguiente']).'
						}
					';
						
					$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
						
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg . " Detalles:\n" . join("\n", $errors));
						
					if($result["result"]["t_count"] == 0)
						throw new Exception($preMsg . ' Detalles: No se eliminó ningún registro.');
				}
				
				return true;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
			
		}
	}
?>