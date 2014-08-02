<?php
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );

	class ControladorETEquipoReproduccion extends Controlador
	{
		
		// Obtener y validar la marca
		protected function validarMarca(FormularioETImpresora $form)
		{
			if(!isset($_POST['marca']) || ($marca=trim($_POST['marca'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una marca.';
			} else {
				$form->getImpresora()->setMarca($marca);
			}
		}
		
		protected function validarModelo(FormularioETImpresora $form)
		{
			if(!isset($_POST['modelo']) || ($modelo=trim($_POST['modelo'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un modelo.';
			} else {
				$form->getImpresora()->setModelo($modelo);
			}
		}
	}
	
?>