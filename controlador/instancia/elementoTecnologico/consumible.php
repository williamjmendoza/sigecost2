<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/consumible.php' );
	
	class ControladorInstanciaETConsumible extends Controlador
	{
		public function buscar()
		{
			$consumibles = ModeloInstanciaETConsumible::buscarConsumibles();
					
			$GLOBALS['SigecostRequestVars']['consumibles'] = $consumibles;
			
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/consumibleBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarEspecificacion($form);
			$this->__validarTipo($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de consumible con las mismas características
					if( ($existeConsumible = ModeloInstanciaETConsumible::existeConsumible($form->getConsumible())) === null )
						throw new Exception("La instancia de consumible no pudo ser guardada.");
					
					// Validar si existe una instancia de consumible con las mismas características
					if ($existeConsumible === true)
						throw new Exception("Ya existe una instancia de consumible con las mismas caracter&iacute;sticas.");
					
					// Guardar la instancia de consumible en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETConsumible::guardarConsumible($form->getConsumible());
					
					// Verificar si ocurrio algún error mientras se guardaba la instancia de consumible
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de consumible no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de consumible guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de consumible guardada satisfactoriamente.";
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
				
			$consumible = ModeloInstanciaETConsumible::obtenerConsumiblePorIri($iriInstancia);
				
			$GLOBALS['SigecostRequestVars']['consumible'] = $consumible;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/consumibleDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/consumibleInsertarModificar.php' );
		}
		
		private function __validarEspecificacion(FormularioInstanciaETConsumible $form)
		{
			if(!isset($_POST['especificacion']) || ($nombre=trim($_POST['especificacion'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una especificacion.';
			} else {
				$form->getConsumible()->setEspecificacion($especificacion);
			}
		}
		
		private function __validarTipo(FormularioInstanciaETConsumible $form)
		{
			if(!isset($_POST['tipo']) || ($version=trim($_POST['tipo'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un tipo.';
			} else {
				$form->getConsumible()->setTipo($tipo);
			}
		}
	}
	
	new ControladorInstanciaETConsumible();
?>