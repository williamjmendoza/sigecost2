<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	
	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/escaner.php' );
	
	class ControladorInstanciaETEscaner extends ControladorInstanciaETEquipoReproduccion
	{
		public function buscar()
		{
			$escaners = ModeloInstanciaETEscaner::buscarEscaners();
				
			$GLOBALS['SigecostRequestVars']['escaners'] = $escaners;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/escanerBuscar.php' );
		}
		
		public function desplegarDetalles()
		{
			if(!isset($_REQUEST['iri']) || ($iri=trim($_REQUEST['iri'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un iri.';
			} else {
				$this->__desplegarDetalles($iri);
			}
		}
		
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{	
					// Consultar si existe una instancia deEscaner con las mismas características
					if( ($existeEscaner = ModeloInstanciaETEscaner::existeEscaner($form->getEquipoReproduccion())) === null )
						throw new Exception("La instancia de escaner no pudo ser guardada.");
				
					// Validar si existe una instancia de Escaner con las mismas características
					if ($existeEscaner === true)
						throw new Exception("Ya existe una instancia de escaner con las mismas caracter&iacute;sticas.");
	
					// Guardar la instancia de escaner en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETEscaner::guardarEscaner($form->getEquipoReproduccion());
			
					// Verificar si ocurrio algún error mientras se guardaba la instancia de escaner
					if ($iriNuevaInstancia === false) 
						throw new Exception("La instancia de escaner no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de escaner guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de escaner guardada satisfactoriamente.";
					$this->__desplegarDetalles($iriNuevaInstancia);
					
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
		
		private function __desplegarDetalles($iriInstancia)
		{
			
			$escaner = ModeloInstanciaETEscaner::obtenerEscanerPorIri($iriInstancia);
			
			$GLOBALS['SigecostRequestVars']['escaner'] = $escaner;
			
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/escanerDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/escanerInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaETEscaner();
?>