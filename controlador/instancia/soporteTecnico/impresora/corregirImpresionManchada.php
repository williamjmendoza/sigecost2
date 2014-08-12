<?php

	require_once( dirname(__FILE__) . '/../../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/instancia/soporteTecnico/impresora/impresora.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/instancia/elementoTecnologico/impresora.php' );
	
	class ControladorInstanciaSTImpresoraCorregirImpresionManchada extends ControladorInstanciaSTImpresora
	{
		public function guardar()
		{
			
		}
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarFormulario()
		{
			$impresoras = ModeloInstanciaETImpresora::obtenerTodasImpresoras();
			
			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;
			
			require ( SIGECOST_VISTA_PATH . '/instancia/soporteTecnico/impresora/corregirImpresionManchadaInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaSTImpresoraCorregirImpresionManchada();
?>