<?php

	require_once( dirname(__FILE__) . '/../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/buscar.php' );

	class ControladorBusqueda extends Controlador
	{
		public function buscar()
		{
			
			if(isset($_POST['clave']) && ($clave = trim($_POST['clave'])) != "")
			{
				//$datos = ModeloBuscar::buscar(array('clave' => $clave));
				
				// Borrar
				$datos = ModeloBuscar::sn2();
				
				$GLOBALS['SigecostRequestVars']['datos'] = $datos;
				$GLOBALS['SigecostRequestVars']['clave'] = $clave;
			}
			
			require ( SIGECOST_PATH_VISTA . '/buscar/buscar.php' );
		}
	}
	
	new ControladorBusqueda();
	
?>