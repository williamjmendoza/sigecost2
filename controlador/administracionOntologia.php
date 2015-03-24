<?php

	require_once( dirname(__FILE__) . '/../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Librerías
	require_once ( SIGECOST_PATH_LIB . '/vinculosAdministracionOntologia.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ControladorAdministracionOntologia extends Controlador
	{
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'administracionOntologia';
			
			parent::__construct();
		}
		
		public function administrarETLista()
		{
			// Obtener todos los iris de las clases, de los elementos tecnológicos implementados
			$irisET = array_keys($GLOBALS['SIGECOST_VAO']['ET']);
			
			$clasesET = ModeloGeneral::getDatosBasicosClases($irisET);
			
			$GLOBALS['SigecostRequestVars']['clasesET'] = $clasesET;
			
			require ( SIGECOST_PATH_VISTA . '/administracionOntologia/administracionETLista.php' );
		}
		
		public function administrarSTLista()
		{
			// Obtener todos los iris de las clases, de los elementos tecnológicos implementados
			$irisST = array_keys($GLOBALS['SIGECOST_VAO']['ST']);
				
			$clasesST = ModeloGeneral::getDatosBasicosClases($irisST);
				
			$GLOBALS['SigecostRequestVars']['clasesST'] = $clasesST;
			
			require ( SIGECOST_PATH_VISTA . '/administracionOntologia/administracionSTLista.php' );
		}
		
	}
	
	new ControladorAdministracionOntologia();
?>