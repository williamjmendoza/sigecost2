<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/consumible.php' );

	class ControladorInstanciaETConsumible extends Controlador
	{
		use ControladorTraitPaginacion;
		
		public function actualizar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
		
				$form->getConsumible()->setIri($iri);
		
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarEspecificacion($form);
				$this->__validarTipo($form);
				
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de elemento tecnológico en consumible, con las mismas características
					if(($existeInstancia = ModeloInstanciaETConsumible::existeConsumible($form->getConsumible())) === null)
						throw new Exception("La instancia no pudo ser actualizada.");
				
					// Validar si existe una instancia de elemento tecnológico en consumible con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Actualizar la instancia de elemento tecnológico consumible, en la base de datos
					$resultado = ModeloInstanciaETConsumible::actualizarInstancia($form->getConsumible());
						
					if($resultado === false)
						throw new Exception("La instancia no pudo ser actualizada");
						
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de aplicación ofimática guardada satisfactoriamente.";
					$this->__desplegarDetalles($iri);
						
				} else {
					$this->__desplegarFormulario();
					}
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					$this->__desplegarFormulario();
				}
			}
				
			
		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_CONSUMIBLE_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETConsumible::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("consumible.php?accion=buscar");
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

			$consumibles = ModeloInstanciaETConsumible::buscarConsumibles($parametros);

			$GLOBALS['SigecostRequestVars']['consumibles'] = $consumibles;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

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

		public function eliminar()
		{
			try
			{
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea eliminar.");
		
				// Eliminar la instancia de elemento tecnológico consumible, de la base de datos
				$resultado = ModeloInstanciaETConsumible::eliminarInstancia($iri);
					
				if($resultado === false)
					throw new Exception("La instancia no pudo ser eliminada.");
		
				$GLOBALS['SigecostInfo']['general'][] = "Instancia eliminada satisfactoriamente.";
		
				$this->buscar();
		
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->buscar();
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

		public function modificar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_CONSUMIBLE_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");
				
				if( ($instancia = ModeloInstanciaETConsumible::obtenerConsumiblePorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setConsumible($instancia);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
		}
			
		
		private function __desplegarDetalles($iriInstancia)
		{

			$consumible = ModeloInstanciaETConsumible::obtenerConsumiblePorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['consumible'] = $consumible;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/consumibleDetalles.php' );
		}

		private function __desplegarFormulario()
		{

			$consumibles = ModeloInstanciaETConsumible::obtenerTodosConsumibles();
			$GLOBALS['SigecostRequestVars']['consumibles'] = $consumibles;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/consumibleInsertarModificar.php' );
		}

		private function __validarEspecificacion(FormularioInstanciaETConsumible $form)
		{
			if(!isset($_POST['especificacion']) || ($especificacion=trim($_POST['especificacion'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una especificacion.';
			} else {
				$form->getConsumible()->setEspecificacion($especificacion);
			}
		}

		private function __validarTipo(FormularioInstanciaETConsumible $form)
		{
			if(!isset($_POST['tipo']) || ($tipo=trim($_POST['tipo'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un tipo.';
			} else {
				$form->getConsumible()->setTipo($tipo);
			}
		}
	}

	new ControladorInstanciaETConsumible();
?>