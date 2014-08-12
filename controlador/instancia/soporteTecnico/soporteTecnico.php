<?php

	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	class ControladorInstanciaSoporteTecnico extends Controlador
	{
		// Obtener y validar el url de soporte técnico
		/*
		protected function __validarUrlSoporteTecnico(FormularioInstancia $form)
		{
			if(!isset($_POST['urlSoporteTecnico']) || ($urlSoporteTecnico=trim($_POST['urlSoporteTecnico'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un url de soporte t&eacute;cnico.';
			} else {
				$form->get()->setUrlSoporteTecnico($marca);
			}
		}
		*/
	}

?>