<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	class ControladorInstanciaElementoTecnologico extends Controlador
	{
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'administracionOntologia';
			
			parent::__construct();
		}
	}

?>