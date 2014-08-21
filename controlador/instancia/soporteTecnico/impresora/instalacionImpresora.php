<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/impresora/impresora.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );
	
	// Libs
	require_once ( SIGECOST_PATH_LIB . '/paginacion.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/impresora.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/instalacionImpresora.php' );
	
	class ControladorInstanciaSTImpresoraInstalacionImpresora extends ControladorInstanciaSTImpresora
	{
		use ControladorTraitPaginacion;
		
		public function buscar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_BUSCAR);
			
			$this->__validarPagina($form);
			
			$contador = 0;
			
			$parametros = array(
				'desplazamiento' => 4,
				'limite' => 10
			);
			
			$instancias = ModeloInstanciaSTImpresoraInstalacionImpresora::buscarInstancias($parametros);
			
			$contador = ModeloInstanciaSTImpresoraInstalacionImpresora::buscarInstanciasContador();
			
			$paginacion = new LibPaginacion(10, 50);
			$paginacion->leerParametrosRequest();
			
			echo "<pre>";
			print_r($paginacion);
			echo "</pre>";
			
			echo "<pre>";
			print_r($contador);
			echo "</pre>";
				
			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraBuscar.php' );
			
			echo "<pre>";
			print_r($form);
			echo "</pre>";
					
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_INSERTAR_MODIFICAR);
				
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarUrlSoporteTecnico($form);
			$this->__validarIriEquipoReproduccion($form);
			$this->__validarIriSistemaOperativo($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de soporte técnico en impresora para instalación de impresora, con las mismas características
					if(($existeInstancia = ModeloInstanciaSTImpresoraInstalacionImpresora::existeInstancia($form->getSoporteTecnico())) === null)
						throw new Exception("La instancia no pudo ser guardada.");
					
					// Validar si existe una instancia de soporte técnico en impresora para la instalación de impresora, con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
						
					// Guardar la instancia de soporte técnico en impresora para instalación de impresora, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTImpresoraInstalacionImpresora::guardarInstancia($form->getSoporteTecnico());
					
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
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarDetalles($iriInstancia)
		{
			$instancia = ModeloInstanciaSTImpresoraInstalacionImpresora::obtenerInstanciaPorIri($iriInstancia);
			
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			$impresoras = ModeloInstanciaETImpresora::obtenerTodasImpresoras();
			$sistemasOperativos = ModeloInstanciaETSistemaOperativo::obtenerTodosSitemasOperativos();
				
			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;
			$GLOBALS['SigecostRequestVars']['sistemasOperativos'] = $sistemasOperativos;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraInsertarModificar.php' );
		}
		
		// Obtener y validar el iri de la instancia de sistema operativo
		private function __validarIriSistemaOperativo(FormularioInstanciaSTImpresoraInstalacionImpresora $form)
		{
			if(!isset($_POST['iriSistemaOperativo']) || ($iriSistemaOperativo=trim($_POST['iriSistemaOperativo'])) == ''
					|| $iriSistemaOperativo == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar un sistema operativo.';
			} else {
				$form->getSoporteTecnico()->getSistemaOperativo()->setIri($iriSistemaOperativo);
			}
		}
	}
	
	new ControladorInstanciaSTImpresoraInstalacionImpresora();
?>