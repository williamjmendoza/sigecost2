<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelo

	class ControladorSTImpresora extends Controlador
	{
		
		public function prueba()
		{
			echo "Holita 2";	
			
		}
	}
	
	new ControladorSTImpresora();
	
	
?>