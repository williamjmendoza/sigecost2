<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );

	class ControladorInstanciaETBarraHerramientas extends Controlador
	{

		// Obtener y validar el nombre
		protected function __validarNombre(FormularioInstanciaETBarraHerramientas $form)
		{
			if(!isset($_POST['nombre']) || ($nombre=trim($_POST['nombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un nombre.';
			} else {
				$form->getBarraHerramientas()->setNombre($nombre);
			}
		}

		// Obtener y validar la versión
		protected function __validarVersion(FormularioInstanciaETBarraHerramientas $form)
		{
			if(!isset($_POST['version']) || ($version=trim($_POST['version'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una versi&oacute;n.';
			} else {
				$form->getBarraHerramientas()->setVersion($version);
			}
		}
	}

?>