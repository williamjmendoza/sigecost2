<?php

	require_once( dirname(__FILE__) . '/../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	class ControladorArchivo extends Controlador
	{
		public function exportar()
		{
			require ( SIGECOST_PATH_VISTA . '/archivo/exportar.php' );
		}
	}
	
	new ControladorArchivo();
?>