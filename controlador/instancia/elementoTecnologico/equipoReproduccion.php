<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class ControladorInstanciaETEquipoReproduccion extends ControladorInstanciaElementoTecnologico
	{
		
		// Obtener y validar la marca
		protected function __validarMarca(FormularioInstanciaETEquipoReproduccion $form)
		{
			if(!isset($_POST['marca']) || ($marca=trim($_POST['marca'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una marca.';
			} else {
				$form->getEquipoReproduccion()->setMarca($marca);
			}
		}
		
		// Obtener y validar el modelo
		protected function __validarModelo(FormularioInstanciaETEquipoReproduccion $form)
		{
			if(!isset($_POST['modelo']) || ($modelo=trim($_POST['modelo'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un modelo.';
			} else {
				$form->getEquipoReproduccion()->setModelo($modelo);
			}
		}
	}
	
?>