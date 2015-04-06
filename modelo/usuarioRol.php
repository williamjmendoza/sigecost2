<?php
	
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/usuario.php' );
	require_once( SIGECOST_PATH_ENTIDAD . '/rol.php' );

	class ModeloUsuarioRol
	{
		public static function actualizarUsuarioRol(EntidadUsuario $usuario)
		{
			$preMsg = 'Error al actualizar los roles asociados a un usuario';
			$resultTransaction = null;
		
			try
			{
				if($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
		
				if($usuario->getId() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' es nulo.');
		
				if($usuario->getId() == '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' está vacío.');
		
				// Iniciar la transacción
				$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransaction === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Borrar los roles del usuario que están almacenados en la base de datos
				$query = "
					DELETE FROM
						usuario_rol
					WHERE
						id_usuario = '".$usuario->getId()."'
				";
				
				if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
					throw new Exception($preMsg . ' No se pudieron borrar los roles anteriores desde la base de datos para este usuario. Detalles: '
						. $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				if(is_array($usuario->getRoles()) && count($usuario->getRoles()) > 0)
				{
					foreach ($usuario->getRoles() AS $index  => $rol)
					{
						if(!($rol instanceof EntidadRol))
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']\' no es del tipo EntidadRol.');
		
						if($rol->getId() === null)
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']->getId()\' es nulo.');
		
						if($rol->getId() == '')
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']->getId()\' está vacío.');
		
						$query = "
							INSERT INTO usuario_rol
								(
									id_usuario,
									id_rol
								)
							VALUES
								(
									'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getId())."',
									'".$GLOBALS['PATRONES_CLASS_DB']->Quote($rol->getId())."'
								)
						";
		
						if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
							throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
					}
				}
				
				// Commit de la transacción
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
		
				return true;
		
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
		
		public static function guardarUsuarioRol(EntidadUsuario $usuario)
		{
			$preMsg = 'Error al guardar los roles asociados a un usuario';
			$resultTransaction = null;
				
			try
			{
				if($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
				
				if($usuario->getId() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' es nulo.');
				
				if($usuario->getId() == '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' está vacío.');
				
				if(is_array($usuario->getRoles()) && count($usuario->getRoles()) > 0)
				{
					
					// Iniciar la transacción
					$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
					
					if($resultTransaction === false)
						throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
					
					foreach ($usuario->getRoles() AS $index  => $rol)
					{
						if(!($rol instanceof EntidadRol))
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']\' no es del tipo EntidadRol.');
						
						if($rol->getId() === null)
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']->getId()\' es nulo.');
						
						if($rol->getId() == '')
							throw new Exception($preMsg . ' El parámetro \'$usuario->getRoles()[\''.$index.'\']->getId()\' está vacío.');
						
						$query = "
							INSERT INTO usuario_rol
								(
									id_usuario,
									id_rol
								)
							VALUES
								(
									'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getId())."',
									'".$GLOBALS['PATRONES_CLASS_DB']->Quote($rol->getId())."'
								)
						";
						
						if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
							throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
					}
					
					// Commit de la transacción
					if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
						throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				}
				
				return true;
				
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
	}
?>