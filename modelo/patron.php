<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/patron.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/usuario.php' );

	class ModeloPatron
	{
		public static function actualizarPatron(EntidadPatron $patron)
		{
			$preMsg = 'Error al actualizar el patrón.';
			$resultTransaction = null;
			
			try
			{
				if ($patron === null)
					throw new Exception($preMsg . ' El parámetro \'$patron\' es nulo.');
				
				if($patron->getCodigo() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getCodigo()\' está vacío.');
				
				if($patron->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getNombre()\' está vacío.');
				
				if($patron->getSolucion() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getSolucion()\' está vacío.');
				
				if($patron->getUsuarioUltimaModificacion() === null)
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioUltimaModificacion()\' es nulo.');
				
				if($patron->getUsuarioUltimaModificacion()->getId() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioUltimaModificacion()->getId()\' está vacío.');
				
				// Iniciar la transacción
				$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransaction === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				$query = "
					UPDATE
						patrones
					SET
						nombre = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($patron->getNombre())."',
						solucion = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($patron->getSolucion())."',
						autor_mod = ".$patron->getUsuarioUltimaModificacion()->getId().",
						fecha_mod = NOW()
					WHERE
						codigo = '".$patron->getCodigo()."'
				";
				
				if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
					throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Commit de la transacción
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// retornar el código del patrón de soporte técnico
				return $patron->getCodigo();
				
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
		
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
		
		public static function llenarPatron($row)
		{
			try {
				$patron = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar el patrón de soporte técnico. Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$patron = new EntidadPatron();
				$patron->setCodigo($row['patron_codigo']);
				$patron->setFechaCreacion($row['patron_fecha_creacion']);
				$patron->setFechaUltimaModificacion($row['patron_fecha_ultima_modificacion']);
				$patron->setNombre($row['patron_nombre']);
				$patron->setSolucion($row['patron_solucion']);
				//$patron->setUsuarioCreador();
				//$patron->setUsuarioUltimaModificacion();
	
				return $patron;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerPatronPorCodigo($codigo)
		{
			$preMsg = 'Error al obtener un patrón de soporte técnico, dado el código.';
			$patron = null;
			
			try
			{
				if ($codigo === null)
					throw new Exception($preMsg . ' El parámetro \'$codigo\' es nulo.');
				
				if (($codigo=trim($codigo)) == "")
					throw new Exception($preMsg . ' El parámetro \'$codigo\' está vacío.');
				
				$query = "
					SELECT
						patrones.codigo AS patron_codigo,
						patrones.nombre AS patron_nombre,
						patrones.solucion AS patron_solucion,
						patrones.autor_crea,
						DATE_FORMAT(
							CONVERT_TZ(patrones.fecha_crea,'".GetConfig('timeZone')."','".GetConfig('displayTimeZone')."'), '%d/%m/%Y %H:%i:%s'
						) AS patron_fecha_creacion,
						patrones.autor_mod,
						DATE_FORMAT(
								CONVERT_TZ(patrones.fecha_mod,'".GetConfig('timeZone')."','".GetConfig('displayTimeZone')."'), '%d/%m/%Y %H:%i:%s'
						) AS patron_fecha_ultima_modificacion
					FROM
						patrones
					WHERE
						patrones.codigo = '".$codigo."'
				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				
				if ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					$patron = self::llenarPatron($row);
				
				if($patron === false)
					throw new Exception($preMsg . ' No se pudo llenar el patrón.');
				
				// Obtener el usuario creador
				$usuarioCreador = ModeloUsuario::obtenerUsuarioPorId($row['autor_crea']);
				if($usuarioCreador === false)
					throw new Exception($preMsg . ' No se pudo obtener el usuario creador.');
				if($usuarioCreador === null)
					throw new Exception($preMsg . ' El usuario creador con id = "'.$row['autor_crea'].'", no existe.');
				$patron->setUsuarioCreador($usuarioCreador);
				
				// Obtener el usuario de la última modificación
				if($row['autor_mod'] != "" && $row['autor_mod'] != "0"){
					$usuarioUltimaModificacion = ModeloUsuario::obtenerUsuarioPorId($row['autor_mod']);
					if($usuarioUltimaModificacion === false)
						throw new Exception($preMsg . ' No se pudo obtener el usuario de la última modificación.');
					if($usuarioUltimaModificacion === null)
						throw new Exception($preMsg . ' El usuario de la última modificación con id = "'.$row['autor_mod'].'", no existe.');
					$patron->setUsuarioUltimaModificacion($usuarioUltimaModificacion);
				}
				
				return $patron;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerPatronesPorCodigos(array $codigos)
		{
			$preMsg = 'Error al obtener un listado de patrones de soporte técnico, dado sus códigos.';
			$patrones = null;
				
			try
			{
				if(!is_array($codigos))
					throw new Exception($preMsg . ' El parámetro \'$codigos\' no es un arreglo.');
		
				$query = "
					SELECT
						patrones.codigo AS patron_codigo,
						patrones.nombre AS patron_nombre,
						patrones.solucion AS patron_solucion,
						patrones.autor_crea,
						DATE_FORMAT(
							CONVERT_TZ(patrones.fecha_crea,'".GetConfig('timeZone')."','".GetConfig('displayTimeZone')."'), '%d/%m/%Y %H:%i:%s'
						) AS patron_fecha_creacion,
						patrones.autor_mod,
						DATE_FORMAT(
								CONVERT_TZ(patrones.fecha_mod,'".GetConfig('timeZone')."','".GetConfig('displayTimeZone')."'), '%d/%m/%Y %H:%i:%s'
						) AS patron_fecha_ultima_modificacion
					FROM
						patrones
					WHERE
						patrones.codigo IN ('".implode("', '", $codigos)."')
				";
		
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
		
				while ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					$patrones[$row['patron_codigo']] = self::llenarPatron($row);
		
				return $patrones;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>