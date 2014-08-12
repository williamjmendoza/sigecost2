<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelo

	class ControladorSTImpresora extends Controlador
	{
		
		public function insertar()
		{
			$this->__desplegarFormulario();			
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_VISTA_PATH . '/instancia/soporteTecnico/impresoraInsertarModificar.php' );
		}
	}
	
	new ControladorSTImpresora();
	
	
?>