<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/patron.php' );

	class ModeloPatron
	{
		public static function getSiguienteSecuenciaPatron()
		{
			$preMsg = "Error al consultar el número de siguiente secuencia del patrón.";
			$prefijoPatron = GetConfig("prefijoPatronSoporteTecnico");
			// Se inicializa la secuencia en 1, en caso de que no existan patrones, por lo tanto se estaría creando el primer patrón.
			$secuencia = 1;
			
			try
			{
				$query = "
					SELECT
						SUBSTRING( codigo, LENGTH('".$prefijoPatron."') + 1) AS consecutivo
					FROM
						patrones
					WHERE
						codigo LIKE '".$prefijoPatron."%'
					ORDER BY
						 CONVERT( SUBSTRING( codigo, LENGTH('".$prefijoPatron."') + 1), UNSIGNED) DESC
					LIMIT
						 1
				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				
				if( ($secuenciaTmp = $GLOBALS['PATRONES_CLASS_DB']->FetchOne($result)) !== false ){
					$secuencia = intval($secuenciaTmp, 10) + 1;
				}
				
				return $secuencia;
				
			} catch (Exception $e){
				error_log($e, 0);
				return false;
			}
		}
		
		public static function guardarPatron(EntidadPatron $patron)
		{
			$preMsg = 'Error al guardar el patrón.';
			$prefijoPatron = GetConfig("prefijoPatronSoporteTecnico");
			$resultTransaction = null;
			
			try
			{
				if ($patron === null)
					throw new Exception($preMsg . ' El parámetro \'$patron\' es nulo.');
				
				if($patron->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getNombre()\' está vacío.');
				
				if($patron->getSolucion() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getSolucion()\' está vacío.');
				
				if($patron->getUsuarioCreador() === null)
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioCreador()\' es nulo.');
				
				if($patron->getUsuarioCreador()->getId() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioCreador()->getId()\' está vacío.');
				
				// Iniciar la transacción
				$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransaction === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Obtener el siguiente número de secuencia del patrón
				if(($secuencia = self::getSiguienteSecuenciaPatron()) === false)
					throw new Exception($preMsg . ' No se pudo obtener el siguiente número de secuencia de patrón.');
				
				$codigo = $prefijoPatron . $secuencia;
				
				$query = "
					INSERT INTO patrones
						(
							codigo,
							nombre,
							solucion,
							autor_crea,
							fecha_crea
						)
					VALUES
						(
							'".$codigo."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($patron->getNombre())."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($patron->getSolucion())."',
							".$patron->getUsuarioCreador()->getId().",
							NOW()
						)
				";
					
				if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
					throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Commit de la transacción
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// retornar el código con el que se guardó el patrón de soporte técnico
				return $codigo;
				
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
	}
?>