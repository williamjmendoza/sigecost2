<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php' );
	
	class ControladorInstanciaSTAplicacionOfimaticaCorregirCierreInesperado extends ControladorInstanciaSTAplicacionOfimatica
	{
		public function buscar()
		{
			$instancias = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::buscarInstancias();
		
			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_CORREGIR_CIERRE_INESPERADO_INSERTAR_MODIFICAR);
				
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarUrlSoporteTecnico($form);
			$this->__validarIriAplicacionPrograma($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de soporte técnico  para corregir el cierre inesperado de una aplicación ofimática
					if(($existeInstancia = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::existeInstancia($form->getSoporteTecnico())) === null)
						throw new Exception("La instancia no pudo ser guardada.");
					
					// Validar si existe una instancia de soporte técnico para corregir el cierre inesperado de una aplicación ofimática, con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Guardar la instancia de soporte técnico para corregir el cierre inesperado de una aplicación ofimática, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::guardarInstancia($form->getSoporteTecnico());
					
					// Verificar si ocurrio algún error mientras se guardaba la instancia
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles de la instancia guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de guardada satisfactoriamente.";
					$this->__desplegarDetalles($iriNuevaInstancia);
					
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					$this->__desplegarFormulario();
				}
			} else {
				$this->__desplegarFormulario();
			}
		}
		
		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionOfimatica::obtenerTodasAplicaciones();
		
			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoInsertarModificar.php' );
		}
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarDetalles($iriInstancia)
		{
			$instancia = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::obtenerInstanciaPorIri($iriInstancia);
		
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoDetalles.php' );
		}
		
	}
	
	new ControladorInstanciaSTAplicacionOfimaticaCorregirCierreInesperado();
	
?>