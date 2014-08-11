<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	
	class ControladorInstanciaETSistemaOperativo extends Controlador
	{
		public function buscar()
		{
			$sistemasOperativos = ModeloInstanciaETSistemaOperativo::buscarSistemasOperativos();
		
			$GLOBALS['SigecostRequestVars']['sistemasOperativos'] = $sistemasOperativos;
			
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/sistemaOperativoBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de sistema operativo con las mismas características
					if( ($existeSistemaOperativo = ModeloInstanciaETSistemaOperativo::existeSistemaOperativo($form->getSistemaOperativo())) === null )
						throw new Exception("La instancia de sistema operativo no pudo ser guardada.");
					
					// Validar si existe una instancia de sistema operativo con las mismas características
					if ($existeSistemaOperativo === true)
						throw new Exception("Ya existe una instancia de sistema operativo con las mismas caracter&iacute;sticas.");
					
					// Guardar la instancia de sistema operativo en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETSistemaOperativo::guardarSistemaOperativo($form->getSistemaOperativo());
					
					// Verificar si ocurrio algún error mientras se guardaba la instancia de sistema operativo
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de sistema operativo no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de impresora guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de sistema operativo guardada satisfactoriamente.";
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
				
			$sistemaOperativo = ModeloInstanciaETSistemaOperativo::obtenerSistemaOperativoPorIri($iriInstancia);
				
			$GLOBALS['SigecostRequestVars']['sistemaOperativo'] = $sistemaOperativo;
				
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/sistemaOperativoDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/sistemaOperativoInsertarModificar.php' );
		}
		
		private function __validarNombre(FormularioInstanciaETSistemaOperativo $form)
		{
			if(!isset($_POST['nombre']) || ($nombre=trim($_POST['nombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un nombre.';
			} else {
				$form->getSistemaOperativo()->setNombre($nombre);
			}
		}
		
		private function __validarVersion(FormularioInstanciaETSistemaOperativo $form)
		{
			if(!isset($_POST['version']) || ($version=trim($_POST['version'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una versi&oacute;n.';
			} else {
				$form->getSistemaOperativo()->setVersion($version);
			}
		}
	}
	
	new ControladorInstanciaETSistemaOperativo();
?>