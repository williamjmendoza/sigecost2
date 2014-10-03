<?php

	require_once( dirname(__FILE__) . '/../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/archivo.php' );
	
	class ControladorArchivo extends Controlador
	{
		public function exportar()
		{
			require ( SIGECOST_PATH_VISTA . '/archivo/exportar.php' );
		}
		
		public function exportarOntologiaAOwl()
		{
			try
			{
				if(($datos = Modeloarchivo::exportarOntologiaAOwl()) === false)
					throw new Exception("Error al exportar la ontolog&iacute;a.");
				
				header('Content-type: application/owl+xml');
				header('Content-Disposition: attachment; filename="soporte_tecnico.owl"');
				
				echo $datos;
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->exportar();
			}
		}
	}
	
	new ControladorArchivo();
?>