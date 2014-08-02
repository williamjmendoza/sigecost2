<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	
	// Modelos

	class ControladorETImpresora extends ControladorETEquipoReproduccion
	{
		public function insertar()
		{
			$this->desplegarFormulario();
		}
		
		public function guardar()
		{
			$form = FormularioManejador::GetFormulario(FORM_ET_IMPRESORA_INSERTAR_MODIFICAR);
			
			$this->validarMarca($form);
			$this->validarModelo($form);
			
			$this->desplegarFormulario();
		}
		
		private function desplegarFormulario()
		{
			$form = FormularioManejador::GetFormulario(FORM_ET_IMPRESORA_INSERTAR_MODIFICAR);
			
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/insertarModificar.php' );
		}
	}
	
	new ControladorETImpresora();
?>