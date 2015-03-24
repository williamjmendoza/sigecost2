<?php

	require_once( dirname(__FILE__) . '/../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/sesion.php' );
	
	class ControladorSesion extends Controlador
	{
		public function iniciarSesion()
		{
			try
			{
				if(!isset($_POST['usuario']) || ($usuario=trim($_POST['usuario'])) == '')
					throw new Exception('Debe introducir un usuario.');
				
				if(!isset($_POST['contrasenaCod']) || ($contrasena=trim($_POST['contrasenaCod'])) == '')
					throw new Exception('Debe introducir una contrase&ntilde;a.');
				
				if( ModeloSesion::iniciarSesion($usuario, $contrasena) === false)
					throw new Exception('Usuario y/o contrase&ntilde;a iv&aacute;lida.');
				
				require ( SIGECOST_PATH_BASE . '/index.php');
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				require ( SIGECOST_PATH_BASE . '/index.php');
			}
		}
		
		public function finalizarSesion()
		{
			try
			{

				ModeloSesion::finalizarSesion();
				
				require ( SIGECOST_PATH_BASE . '/index.php');
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				require ( SIGECOST_PATH_BASE . '/index.php');
			}
		}
	}
	
	new ControladorSesion();
?>