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
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{	
					// Consultar si existe una instancia de impresora con las mismas características
					if( ($existeImpresora = ModeloInstanciaETImpresora::existeImpresora($form->getEquipoReproduccion())) === null )
						throw new Exception("La instancia de impresora no pudo ser guardada.");
				
					// Validar si existe una instancia de impresora con las mismas características
					if ($existeImpresora === true)
						throw new Exception("Ya existe una instancia de impresora con las mismas caracter&iacute;sticas.");
	
					// Guardar la instancia de impresora en la base de datos
					$fragmentoNuevaInstancia = ModeloInstanciaETImpresora::guardarImpresora($form->getEquipoReproduccion());
			
					// Verificar si ocurrio algún error mientras se guardaba la instancia de impresora
					if ($fragmentoNuevaInstancia === false) 
						throw new Exception("La instancia de impresora no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente y mostrar los detalles
					// de la instancia de impresora guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de impresora guardada satisfactoriamente.";
					$this->__desplegarDetalles();
					
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					$this->__desplegarFormulario();
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