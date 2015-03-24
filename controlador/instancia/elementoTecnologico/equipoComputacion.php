<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class ControladorInstanciaETEquipoComputacion extends ControladorInstanciaElementoTecnologico
	{

		// Obtener y validar la marca
		protected function __validarMarca(FormularioInstanciaETEquipoComputacion $form)
		{
			if(!isset($_POST['marca']) || ($marca=trim($_POST['marca'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una marca.';
			} else {
				$form->getEquipoComputacion()->setMarca($marca);
			}
		}

		// Obtener y validar el modelo
		protected function __validarModelo(FormularioInstanciaETEquipoComputacion $form)
		{
			if(!isset($_POST['modelo']) || ($modelo=trim($_POST['modelo'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un modelo.';
			} else {
				$form->getEquipoComputacion()->setModelo($modelo);
			}
		}
	}

?>