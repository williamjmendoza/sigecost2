<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelo

	class ControladorETEquipoReproduccion extends Controlador
	{
		
		public function prueba()
		{
			echo "Holita";	
			
		}
	}
	
	new ControladorETEquipoReproduccion();
?>