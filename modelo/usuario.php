<?php
	
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/usuario.php' );

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
				$usuario->setLogin($row['usuario_login']);
				//$usuario->setContrasena($row['usuario_constrasena']);
				$usuario->setNombre($row['usuario_nombre']);
				$usuario->setApellido($row['usuario_apellido']);
		
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
						usuario.login AS usuario_login,
						#usuario.contrasena AS usuario_constrasena,
						usuario.nombre AS usuario_nombre,
						usuario.apellido AS usuario_apellido
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
	}
?>