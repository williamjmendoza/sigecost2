<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/soporteTecnico.php' );

	class ControladorInstanciaSTAplicacionPrograma extends ControladorInstanciaSoporteTecnico
	{
		// Obtener y validar el iri de la instancia de aplicación de programa
		protected function __validarIriAplicacionPrograma(FormularioInstanciaSTAplicacionPrograma $form)
		{
			if(!isset($_POST['iriAplicacionPrograma']) || ($iriAplicacionPrograma=trim($_POST['iriAplicacionPrograma'])) == ''
				|| $iriAplicacionPrograma == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar una aplicaci&oacute;n de programa';
			} else {
				$form->getSoporteTecnico()->getAplicacionPrograma()->setIri($iriAplicacionPrograma);
			}
		}
	}
?>