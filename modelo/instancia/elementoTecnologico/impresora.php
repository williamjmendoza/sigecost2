<?php

	// Entidades	
	require_once( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php' );

	class ModeloInstanciaETImpresora
	{
		public static function guardarImpresora(EntidadInstanciaETImpresora $impresora)
		{
			try
			{

				if($impresora == null)
					throw new Exception('Error al guardar la instancia de impresora. Detalles: El parámetro \'$impresora\' es nulo.');
				
				// Iniciar la transacción
				//$resultTransaction = $GLOBALS['ONTOLOGIA_STORE']->StartTransaction();
					
				//if($resultTransaction === false)
					//throw new Exception('Error al iniciar la transacción. Detalles: ' . $GLOBALS['ONTOLOGIA_STORE']->GetErrorMsg());
				
				'
					PREFIX libro: <http://ejemplo.org/libro/>
					PREFIX elemento: <http://purl.org/dc/elements/1.1/>
					INSERT INTO <http://ejemplo.org/>
					{ ?libro elemento:references ?titulo .}
					WHERE
					{?libro elemento:title ?titulo .}
				';
				
				$query = '
						PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						PREFIX owl: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
						
						INSERT INTO <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						{
							owl:Impresora_numeroConsecutivo rdf:type kb:Impresora .
							owl:Impresora_numeroConsecutivo owl:modeloEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							owl:Impresora_numeroConsecutivo owl:marcaEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
				//$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de impresora. Detalles:\n". join("\n", $errors));
				
				//$result = $GLOBALS['ONTOLOGIA_STORE']->CommitTransaction();
					
				//if($result === false)
					//throw new Exception("Error en el commit al guardar la instancia de impresora. Detalles: ".
						//$GLOBALS['ONTOLOGIA_STORE']->GetErrorMsg());
				
				//return $iri;
				
				// Borrar
				return true;
				
			} catch (Exception $e) {
				//if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['ONTOLOGIA_STORE']->RollbackAllTransactions();
				//error_log($e, 0);
				//return false;
			}
		}
	}
?>