<?php

	// Entidades	
	require_once( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/general.php' );

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
				
				$fragmentoIriClase = 'Impresora';
				
				// Consultar el número de secuencia para la siguiente instancia de impresora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia($fragmentoIriClase);
				
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de impresora. No se pudo obtener el número de la siguiente secuencia '.  
							'para la instancia de la clase \''.$fragmentoIriClase.'\'');
					
				$fragmentoIriInstancia = $fragmentoIriClase . '_' . $secuencia;
				
				$query = '
						PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
						
						INSERT INTO <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
						{
							kb:'.$fragmentoIriInstancia.' rdf:type kb:Impresora .
							kb:'.$fragmentoIriInstancia.' kb:modeloEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							kb:'.$fragmentoIriInstancia.' kb:marcaEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
				$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al guardar la instancia de impresora. Detalles:\n". join("\n", $errors));
				
				//$result = $GLOBALS['ONTOLOGIA_STORE']->CommitTransaction();
					
				//if($result === false)
					//throw new Exception("Error en el commit al guardar la instancia de impresora. Detalles: ".
						//$GLOBALS['ONTOLOGIA_STORE']->GetErrorMsg());
				
				return $fragmentoIriInstancia;
				
			} catch (Exception $e) {
				//if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['ONTOLOGIA_STORE']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
	}
?>