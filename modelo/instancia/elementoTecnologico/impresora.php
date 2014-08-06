<?php

	// Entidades	
	require_once( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php' );
	
	// Lib
	require_once( SIGECOST_LIB_PATH . '/definiciones.php' );
	
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
				
				// Consultar el número de secuencia para la siguiente instancia de impresora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_IMPRESORA);
				
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de impresora. No se pudo obtener el número de la siguiente secuencia '.  
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_IMPRESORA.'\'');
					
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_IMPRESORA . '_' . $secuencia;
				
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
						
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :Impresora .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .		
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
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