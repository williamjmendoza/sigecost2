<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once( SIGECOST_CONTROLADOR_PATH . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	
	// Modelos
	require_once( SIGECOST_MODELO_PATH . '/instancia/elementoTecnologico/impresora.php' );

	class ControladorInstanciaETImpresora extends ControladorInstanciaETEquipoReproduccion
	{
		public function desplegarDetalles()
		{
			$this->__desplegarDetalles();
		}
		
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR);
				
			$this->__validarMarca($form);
			$this->__validarModelo($form);
				
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				$resultado = ModeloInstanciaETImpresora::guardarImpresora($form->getEquipoReproduccion());
		
				if ($resultado === false) {
					$GLOBALS['SigecostErrors']['general'][] = "La instancia de impresora no pudo ser guardada.";
					$this->__desplegarFormulario();
				} else {
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de impresora '' guardada satisfactoriamente.";
					$this->__desplegarDetalles();
				}
			} else {
				$this->__desplegarFormulario();
			}
		}
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarDetalles()
		{
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/impresoraDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/impresoraInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaETImpresora();
?>