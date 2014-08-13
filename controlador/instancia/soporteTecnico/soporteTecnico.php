<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/soporteTecnico.php' );
	
	class ControladorInstanciaSoporteTecnico extends Controlador
	{
		// Obtener y validar el url de soporte técnico
		protected function __validarUrlSoporteTecnico(FormularioInstanciaSoporteTecnico $form)
		{
			if(!isset($_POST['urlSoporteTecnico']) || ($urlSoporteTecnico=trim($_POST['urlSoporteTecnico'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un url de soporte t&eacute;cnico.';
			} else {
				$form->getSoporteTecnico()->setUrlSoporteTecnico($urlSoporteTecnico);
			}
		}
	}

?>