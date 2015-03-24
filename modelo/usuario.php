<?php
	
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/usuario.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/rol.php' );

	class ModeloUsuario
	{
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
				//$usuario->setContrasena($row['usuario_constrasena']);
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
						#usuario.contrasena AS usuario_constrasena,
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
		
		//Borrar
		/*
		public static function obtenerUsuarioPorDescripcionUsuario($descripcionUsuario)
		{
			$preMsg = 'Error al obtener un usuario, dado el usuario.';
			$usuario = null;
		
			try
			{
				if ($descripcionUsuario === null)
					throw new Exception($preMsg . ' El parámetro \'$descripcionUsuario\' es nulo.');
		
				if (($descripcionUsuario=trim($descripcionUsuario)) == "")
					throw new Exception($preMsg . ' El parámetro \'$descripcionUsuario\' está vacío.');
		
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
					WHERE
						usuario.usuario = '".$descripcionUsuario."'
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
		*/
		
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
	}
?>