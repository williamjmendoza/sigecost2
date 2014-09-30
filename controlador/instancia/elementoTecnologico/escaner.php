<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/escaner.php' );

	class ControladorInstanciaETEscaner extends ControladorInstanciaETEquipoReproduccion
	{
		use ControladorTraitPaginacion;
		
		public function actualizar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
		
				$form->getEquipoReproduccion()->setIri($iri);
				
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarMarca($form);
				$this->__validarModelo($form);
		
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de elemento tecnológico en escaner, con las mismas características
					if(($existeInstancia = ModeloInstanciaETEscaner::existeEscaner($form->getEquipoReproduccion())) === null)
						throw new Exception("La instancia no pudo ser actualizada.");
				
					// Validar si existe una instancia de elemento tecnológico escaner con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Actualizar la instancia de elemento tecnológico escaner, en la base de datos
					$resultado = ModeloInstanciaETEscaner::actualizarInstancia($form->getEquipoReproduccion());
					
					if($resultado === false)
						throw new Exception("La instancia no pudo ser actualizada");
					
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de escaner guardada satisfactoriamente.";
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_ESCANER_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETEscaner::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("escaner.php?accion=buscar");
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

			$escaners = ModeloInstanciaETEscaner::buscarEscaners($parametros);

			$GLOBALS['SigecostRequestVars']['escaners'] = $escaners;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

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

		public function eliminar()
		{
			try
			{
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea eliminar.");
		
				// Eliminar la instancia de elemento tecnológico escáner, de la base de datos
				$resultado =  ModeloInstanciaETEscaner::eliminarInstancia($iri);
					
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

		public function modificar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_ESCANER_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");
		
				if( ($instancia = ModeloInstanciaETEscaner::obtenerEscanerPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setEquipoReproduccion($instancia);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
		}
		
		private function __desplegarDetalles($iriInstancia)
		{

			$escaner = ModeloInstanciaETEscaner::obtenerEscanerPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['escaner'] = $escaner;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/escanerDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$escaners = ModeloInstanciaETEscaner::obtenerTodosEscaners();
			$GLOBALS['SigecostRequestVars']['escaners'] = $escaners;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/escanerInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETEscaner();
?>