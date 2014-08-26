<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/impresora/impresora.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/impresora.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/instalacionImpresora.php' );
	
	class ControladorInstanciaSTImpresoraInstalacionImpresora extends ControladorInstanciaSTImpresora
	{
		use ControladorTraitPaginacion;
		
		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_INSTALACION_IMPRESORA_BUSCAR);
			
			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaSTImpresoraInstalacionImpresora::buscarInstanciasTotalElementos();
			
			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("instalacionImpresora.php?accion=buscar");
			}
			
			// Realizar la consulta de la búsqueda estableciendo los parámetros para la navegación
			$parametros = array();
			// Establecer los parámetros de la navegación para la consulta de la búsqueda 
			if($totalElementos !== false)
			{
				$parametros = array(
					'desplazamiento' => $form->getPaginacion()->getDesplazamiento(),
					'limite' => $form->getPaginacion()->getTamanoPagina()
				);
			}
			// Realizar la consulta de la búsuqeda
			$instancias = ModeloInstanciaSTImpresoraInstalacionImpresora::buscarInstancias($parametros);
			
			
			
				
			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/instalacionImpresoraBuscar.php' );
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
			$this->__validarIriEquipoReproduccion($form);
			$this->__validarIriSistemaOperativo($form);
			$this->__validarUrlSoporteTecnico($form);
			
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