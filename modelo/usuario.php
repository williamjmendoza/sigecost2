<?php
	
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/usuario.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/usuarioRol.php' );
	require_once( SIGECOST_PATH_MODELO . '/rol.php' );

	class ModeloUsuario
	{
		public static function actualizarUsuario(EntidadUsuario $usuario, $actualizarRoles = true)
		{
			$preMsg = 'Error al actualizar el usuario.';
			$resultTransaction = null;
			
			try
			{
				if($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
				
				if($usuario->getId() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' es nulo.');
				
				if($usuario->getId() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getId()\' está vacío.');
				
				if($usuario->getContrasena() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getContrasena()\' es nulo.');
				
				if($usuario->getContrasena() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getContrasena()\' está vacío.');
				
				if($usuario->getCedula() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getCedula()\' es nulo.');
				
				if($usuario->getCedula() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getCedula()\' está vacío.');
				
				if($usuario->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getNombre()\' es nulo.');
				
				if($usuario->getNombre() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getNombre()\' está vacío.');
				
				if($usuario->getApellido() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getApellido()\' es nulo.');
				
				if($usuario->getApellido() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getApellido()\' está vacío.');
				
				// Iniciar la transacción
				$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransaction === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				$query = "
					UPDATE
						usuario
					SET
						usuario = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getUsuario())."',
						contrasena = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getContrasena())."',
						cedula = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getCedula())."',
						nombre = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getNombre())."',
						apellido = '".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getApellido())."'
					WHERE
						id = '".$usuario->getId()."'
				";
				
				if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
					throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				if($actualizarRoles === true)
				{
					// Actualizar los roles del usuario
					if(ModeloUsuarioRol::actualizarUsuarioRol($usuario) === false)
						throw new Exception($preMsg . ' No se pudieron actualizar los roles del usuario.');
				}
				
				// Commit de la transacción
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// Retornar el código con el que se guardó el patrón de soporte técnico
				return $usuario->getId();
				
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
		
		public static function llenarUsuario($row)
		{
			try {
				$usuario = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar el usuario. Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$usuario = new EntidadUsuario();
				$usuario->setId($row['usuario_id']);
				$usuario->setCedula($row['usuario_cedula']);
				$usuario->setUsuario($row['usuario_usuario']);
				$usuario->setContrasena($row['usuario_constrasena']);
				$usuario->setNombre($row['usuario_nombre']);
				$usuario->setApellido($row['usuario_apellido']);
				$usuario->setEstatus($row['usuario_estatus']);
				
				// Obtener todos los roles del usuario
				if($row['usuario_id'] != null && $row['usuario_id'] != "")
				{
					$roles = ModeloRol::obtenerRolesPorIdUsuario($row['usuario_id']);
					
					if(is_array($roles))
						$usuario->setRoles($roles);	
				}
		
				return $usuario;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerUsuarioPorId($id)
		{
			$preMsg = 'Error al obtener un usuario, dado el id.';
			$usuario = null;
			
			try
			{
				if ($id === null)
					throw new Exception($preMsg . ' El parámetro \'$id\' es nulo.');
				
				if (($id=trim($id)) == "")
					throw new Exception($preMsg . ' El parámetro \'$id\' está vacío.');
				
				$query = "
					SELECT
						usuario.id AS usuario_id,
						usuario.cedula AS usuario_cedula,
						usuario.usuario AS usuario_usuario,
						usuario.contrasena AS usuario_constrasena,
						usuario.nombre AS usuario_nombre,
						usuario.apellido AS usuario_apellido,
						usuario.estatus AS usuario_estatus
					FROM
						usuario
					WHERE
						id = '".$id."'
				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				
				if ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					$usuario = self::llenarUsuario($row);
				
				if($usuario === false)
					throw new Exception($preMsg . ' No se pudo llenar el usuario.');
				
				return $usuario;
			
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function validarUsuario($usuario, $contrasena)
		{
			$preMsg = 'Error al validar un usuario.';
		
			try
			{
				if ($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
		
				if (($usuario=trim($usuario)) == "")
					throw new Exception($preMsg . ' El parámetro \'$usuario\' está vacío.');
				
				if ($contrasena === null)
					throw new Exception($preMsg . ' El parámetro \'$contrasena\' es nulo.');
				
				if (($contrasena=trim($contrasena)) == "")
					throw new Exception($preMsg . ' El parámetro \'$contrasena\' está vacío.');
		
				$query = "
					SELECT
						usuario.id AS usuario_id
					FROM
						usuario
					WHERE
						usuario.usuario = '".$usuario."'
						AND usuario.contrasena = '".$contrasena."'
				";
		
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
		
				if ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					return $row['usuario_id'];
				
				return false;
					
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
		}
		
		public static function tieneRol(int $idRol, $usuario)
		{
			$preMsg = 'Error al consultar si un usuario posee determinado rol.';
			
			try
			{
				if(idRol <= 0)
					throw new Exception($preMsg . ' El parámetro \'$idRol\' no contiene un id de rol válido.');
				
				if($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
				
				if(!($usuario instanceof EntidadUsuario))
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es inválido.');
				
				if(!is_array($usuario->getRoles()))
					return false;
				
				foreach ($usuario->getRoles() AS $rol)
				{
					if($rol->getId() == $idRol) return true;
				}
				
				return false;
				
			} catch (Exception $e)
			{
				error_log($e, 0);
				return null;
			}
		}
		
		public static function buscarUsuarios(array $parametros = null)
		{
			$preMsg = 'Error al buscar los usuarios.';
			$usuarios = null;
			$limite = '';
			$desplazamiento = '';
			
			try
			{
				
				if($parametros !== null && count($parametros) > 0)
				{
					if(isset($parametros['limite'])) $limite = 'LIMIT ' . $parametros['limite'];
					if(isset($parametros['desplazamiento'])) $desplazamiento = 'OFFSET ' . $parametros['desplazamiento'];
				}
				
				$query = "
					SELECT
						usuario.id AS usuario_id,
						usuario.cedula AS usuario_cedula,
						usuario.usuario AS usuario_usuario,
						#usuario.contrasena AS usuario_constrasena,
						usuario.nombre AS usuario_nombre,
						usuario.apellido AS usuario_apellido,
						usuario.estatus AS usuario_estatus
					FROM
						usuario
					ORDER BY
						usuario.nombre,
						usuario.apellido
					".$limite."
					".$desplazamiento."
				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				$usuarios = array();
				
				while ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					$usuarios[$row['usuario_id']] = self::llenarUsuario($row);
				
				return $usuarios;
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function buscarUsuariosTotalElementos(array $parametros = null)
		{
			$preMsg = 'Error al buscar el contador de los usuarios.';
			
			try
			{
				$query = "
					SELECT
						count(*) AS totalElementos
					FROM
						usuario		

				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				
				if ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
				{
					return $row['totalElementos'];
				}
				else return false;
				
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		// Guarda un nuevo usuario, y retorna su id
		public static function guardarUsuario(EntidadUsuario $usuario)
		{
			$preMsg = 'Error al guardar el usuario.';
			$resultTransaction = null;
			
			try
			{
				if($usuario === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario\' es nulo.');
				
				if($usuario->getContrasena() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getContrasena()\' es nulo.');
				
				if($usuario->getContrasena() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getContrasena()\' está vacío.');
				
				if($usuario->getCedula() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getCedula()\' es nulo.');
				
				if($usuario->getCedula() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getCedula()\' está vacío.');
				
				if($usuario->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getNombre()\' es nulo.');
				
				if($usuario->getNombre() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getNombre()\' está vacío.');
				
				if($usuario->getApellido() === null)
					throw new Exception($preMsg . ' El parámetro \'$usuario->getApellido()\' es nulo.');
				
				if($usuario->getApellido() === '')
					throw new Exception($preMsg . ' El parámetro \'$usuario->getApellido()\' está vacío.');
				
				// Iniciar la transacción
				$resultTransaction = $GLOBALS['PATRONES_CLASS_DB']->StartTransaction();
				
				if($resultTransaction === false)
					throw new Exception($preMsg . ' No se pudo iniciar la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				$query = "
					INSERT INTO usuario
						(
							usuario,
							contrasena,
							cedula,
							nombre,
							apellido
						)
					VALUES
						(
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getUsuario())."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getContrasena())."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getCedula())."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getNombre())."',
							'".$GLOBALS['PATRONES_CLASS_DB']->Quote($usuario->getApellido())."'
						)
				";
				
				if($GLOBALS['PATRONES_CLASS_DB']->Query($query) === false)
					throw new Exception($preMsg . ' Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				if(($idUsuario = $GLOBALS['PATRONES_CLASS_DB']->LastId()) == 0)
					throw new Exception($preMsg . ' El id del último usuario guardado no pudo ser obtenido .Más detalles: '
						. $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
					
				$usuario->setId($idUsuario);
				
				// Guardar los roles del usuario
				if(ModeloUsuarioRol::guardarUsuarioRol($usuario) === false)
					throw new Exception($preMsg . ' No se pudieron guardar los roles del usuario.');
							
				// Commit de la transacción
				if($GLOBALS['PATRONES_CLASS_DB']->CommitTransaction() === false)
					throw new Exception($preMsg . ' No se pudo realizar el commit  de la transacción. Detalles: ' . $GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg());
				
				// retornar el código con el que se guardó el patrón de soporte técnico
				return $idUsuario;
				
			} catch (Exception $e) {
				if(isset($resultTransaction) && $resultTransaction === true) $GLOBALS['PATRONES_CLASS_DB']->RollbackAllTransactions();
				error_log($e, 0);
				return false;
			}
		}
		
	}
?>