<?php

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/usuario.php' );

	class ModeloSesion
	{
		public static function iniciarSesion($usuario, $contrasena)
		{
			$preMsg = 'Error al iniciar la sesión';
	
			try
			{
				if( ($idUsuario = ModeloUsuario::validarUsuario($usuario, $contrasena)) === null )
					throw new Exception($preMsg . " Usuario y/o contraseña inválida.");
				
				if($idUsuario === false)
					return false;
				
				$_SESSION['sesionIniciada'] = true;
				$_SESSION['idUsuario'] = $idUsuario;
				
				if( self::cargarUsuario() === false)
					throw new Exception($preMsg . " No se pudo obtener el objeto usuario con el id dado: " . $idUsuario);
				
				return true;
				
			} catch (Exception $e) {
				error_log($e, 0);
				self::finalizarSesion();
				return false;
			}
		}
		
		public static function finalizarSesion()
		{
			$preMsg = 'Error al finalizar la sesión';
		
			try
			{
				// Destruir todas las variables de sesión.
				$_SESSION = array();
				
				$GLOBALS['SigecostInitialVars'] = array();
					
				// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
				// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
				if (ini_get("session.use_cookies")) {
					$params = session_get_cookie_params();
					setcookie(
					session_name(),
					'',
					time() - 42000,
					$params["path"],
					$params["domain"],
					$params["secure"],
					$params["httponly"]
					);
				}
					
				// Finalmente, destruir la sesión.
				session_destroy();
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function estaSesionIniciada()
		{
			$preMsg = 'Error al vefiricar si existe un usuario que haya iniciado sesión';
		
			try
			{
				return isset($_SESSION['sesionIniciada']) && $_SESSION['sesionIniciada'] === true;
		
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
		
		public static function cargarUsuario()
		{
			try
			{
				// if($usuario == false || $usuario == null || !($usuario instanceof EntidadUsuario) )
				if(isset($_SESSION) && isset($_SESSION['sesionIniciada']) && $_SESSION['sesionIniciada'] === true && isset($_SESSION['idUsuario']))
				{
					$usuario = ModeloUsuario::obtenerUsuarioPorId($_SESSION['idUsuario']);
					
					if($usuario == false || $usuario == null || !($usuario instanceof EntidadUsuario) )
						throw new Exception("Error al intentar cargar los datos de un usuario.");
				
					$GLOBALS['SigecostInitialVars']['usuario'] = &$usuario;
					
				}
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}