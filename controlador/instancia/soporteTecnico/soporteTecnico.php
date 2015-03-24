<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/soporteTecnico.php' );
	
	class ControladorInstanciaSoporteTecnico extends Controlador
	{
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'administracionOntologia';
			
			parent::__construct();
		}
		
		protected function __validarSolucionSoporteTecnico(FormularioInstanciaSoporteTecnico $form)
		{
			if(!isset($_POST['solucionSoporteTecnico']) || ($solucion=trim($_POST['solucionSoporteTecnico'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una soluci&oacute;n de soporte t&eacute;cnico.';
			} else {
				
				if( $form->getSoporteTecnico()->getPatron() === null )
					$form->getSoporteTecnico()->setPatron(new EntidadPatron());
				
				$form->getSoporteTecnico()->getPatron()->setSolucion($solucion);
			}
		}
		
		protected function __obtenerSolucionSoporteTecnico(FormularioInstanciaSoporteTecnico $form)
		{
			if(isset($_POST['solucionSoporteTecnico']) && ($solucion=trim($_POST['solucionSoporteTecnico'])) != ''){
				$form->getSoporteTecnico()->getPatron()->setSolucion($solucion);
			}
		}
	}

?>