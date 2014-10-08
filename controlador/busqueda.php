<?php

	require_once( dirname(__FILE__) . '/../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );

	// Modelos
	//require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/impresora.php' );

	class ControladorBusqueda extends Controlador
	{
		public function buscar()
		{
			require ( SIGECOST_PATH_VISTA . '/busqueda/busqueda.php' );
		}
	}
	
	new ControladorBusqueda();
	
?>