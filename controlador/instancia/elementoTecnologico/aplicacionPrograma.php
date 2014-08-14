<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );

	class ControladorInstanciaETAplicacionPrograma extends Controlador
	{
		
		// Obtener y validar el nombre
		protected function __validarNombre(FormularioInstanciaETAplicacionPrograma $form)
		{
			if(!isset($_POST['nombre']) || ($nombre=trim($_POST['nombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un nombre.';
			} else {
				$form->getAplicacionPrograma()->setNombre($nombre);
			}
		}
		
		// Obtener y validar la versión
		protected function __validarVersion(FormularioInstanciaETAplicacionPrograma $form)
		{
			if(!isset($_POST['version']) || ($version=trim($_POST['version'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una versi&oacute;n.';
			} else {
				$form->getAplicacionPrograma()->setVersion($version);
			}
		}
	}
	
?>