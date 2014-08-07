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
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');
				
				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');
				
				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');
				
				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');
				
				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');

				if($impresora == null)
					throw new Exception('Error al guardar la instancia de impresora. Detalles: El parámetro \'$impresora\' es nulo.');
				
				// Iniciar la transacción
				//$resultTransaction = $GLOBALS['ONTOLOGIA_STORE']->StartTransaction();
					
				//if($resultTransaction === false)
					//throw new Exception('Error al iniciar la transacción. Detalles: ' . $GLOBALS['ONTOLOGIA_STORE']->GetErrorMsg());
				
				// Consultar el número de secuencia para la siguiente instancia de impresora a crear.
				$secuencia = ModeloGeneral::getSiguienteSecuenciaInstancia(SIGECOST_FRAGMENTO_IMPRESORA);
				
				// Validar si hubo errores obteniendo el siguiente número de instancia
				if($secuencia === false)
					throw new Exception('Error al guardar la instancia de impresora. No se pudo obtener el número de la siguiente secuencia '.  
							'para la instancia de la clase \''.SIGECOST_FRAGMENTO_IMPRESORA.'\'');
				
				// Construir el fragmento de la nueva instancia de impresora
				// conctenando el framento de la clase impresora "SIGECOST_FRAGMENTO_IMPRESORA"
				// con el el caracater underscore "_" y el número de secuencia obtenido "$secuencia"
				$fragmentoIriInstancia = SIGECOST_FRAGMENTO_IMPRESORA . '_' . $secuencia;
				
				// Guardar la nueva instancia de impresora
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
						
						INSERT INTO <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						{
							:'.$fragmentoIriInstancia.' rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							:'.$fragmentoIriInstancia.' :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .		
							:'.$fragmentoIriInstancia.' :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
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
		
		public static function existeImpresora(EntidadInstanciaETImpresora $impresora)
		{
			try
			{
				if ($impresora === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');
				
				if ($impresora->getMarca() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' es nulo.');
				
				if ($impresora->getMarca() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getMarca()\' está vacío.');
				
				if ($impresora->getModelo() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' es nulo.');
				
				if ($impresora->getModelo() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getModelo()\' está vacío.');
				
				// Verificar si existe una impresora con la misma marca y modelos que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
					
						ASK
						{
							?instanciaImpresora rdf:type :'.SIGECOST_FRAGMENTO_IMPRESORA.' .
							?instanciaImpresora :marcaEquipoReproduccion "'.$impresora->getMarca().'"^^xsd:string .
							?instanciaImpresora :modeloEquipoReproduccion "'.$impresora->getModelo().'"^^xsd:string .
						}
				';
		
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
		
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de impresora " .
							"(marca = '".$impresora->getMarca()."', modelo = '".$impresora->getModelo()."'). Detalles:\n" . join("\n", $errors));
				
				return $result['result'];
		
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
	}
?>