<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	class ModeloBuscar
	{
		public static function buscar(array $parametros = null)
		{
			$preMsg = 'Error al buscar patrones en la ontología';
			$instancias = null;
			
			try
			{
				if ($parametros === null)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' es nulo.');
				
				if (!is_array($parametros))
					throw new Exception($preMsg . ' El parámetro \'$parametros\' no es un arreglo.');
				
				if (count($parametros) == 0)
					throw new Exception($preMsg . ' El parámetro \'$parametros\' está vacío.');
				
				if (isset($parametros['clave']) && ($clave = $parametros['clave']) != "")
				{
					
					
					
					$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					
						SELECT
							?iri
							?urlSoporteTecnico
						WHERE
						{
							?iri rdf:type :'.SIGECOST_FRAGMENTO_S_T_INSTALACION_IMPRESORA.' .
							OPTIONAL { ?iri :uRLSoporteTecnico ?urlSoporteTecnico } .
						}
						LIMIT
							4
					';
					
					// Borrar
					//error_log($query);
					
					$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
					
					if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
						throw new Exception($preMsg . "  Detalles:\n". join("\n", $errors));
					
					if (is_array($rows) && count($rows) > 0){
						$instancias = array();
						foreach ($rows AS $row){
							$instancias[] = array(
								'iri' => $row['iri'],
								'urlSoporteTecnico' => $row['urlSoporteTecnico']
							);
						}
					}
					
					// Borrar
					error_log(print_r($instancias, true));
					
					// FILTER (?iri = <'.$instancia->getIri().'>) .
					
				}
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
?>