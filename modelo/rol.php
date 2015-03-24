<?php
	
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/rol.php' );

	class ModeloRol
	{
		public static function llenarRol($row)
		{
			try {
				$rol = null;
		
				if(!is_array($row))
					throw new Exception('Error al intentar llenar el rol. Detalles: el parámetro \'$row\' no es un arreglo.');
		
				$rol = new EntidadRol();
				
				$rol->setId($row['rol_id']);
				$rol->setNombre($row['rol_nombre']);
				$rol->setEstatus($row['rol_estatus']);
		
				return $rol;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function obtenerRolesPorIdUsuario($idUsuario)
		{
			$preMsg = 'Error al obtener la lista de roles, dado el id del usuario.';
			$roles = null;
				
			try
			{
				if ($idUsuario === null)
					throw new Exception($preMsg . ' El parámetro \'$idUsuario\' es nulo.');
				
				if (($idUsuario=trim($idUsuario)) == "")
					throw new Exception($preMsg . ' El parámetro \'$$idUsuario\' está vacío.');
				
				$query = "
					SELECT
						rol.id AS rol_id,
						rol.nombre AS rol_nombre,
						rol.estatus AS rol_estatus
					FROM
						usuario_rol
						INNER JOIN rol ON (rol.id = usuario_rol.id_rol)
					WHERE
						usuario_rol.id_usuario = '".$idUsuario."'
				";
				
				if(($result = $GLOBALS['PATRONES_CLASS_DB']->Query($query)) === false)
					throw new Exception($preMsg." Detalles: ".($GLOBALS['PATRONES_CLASS_DB']->GetErrorMsg()));
				
				$roles = array();
				while ($row = $GLOBALS['PATRONES_CLASS_DB']->Fetch($result))
					$roles[$row['rol_id']] = self::llenarRol($row);
				
				return $roles;
			
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
?>