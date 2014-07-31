<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelos

	class ControladorETImpresora extends Controlador
	{
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarFormulario()
		{
			$form = FormularioManejador::GetFormulario(FORM_ET_IMPRESORA_INSERTAR_MODIFICAR);
			
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/insertarModificar.php' );
		}
	}
	
	new ControladorETImpresora();
?>