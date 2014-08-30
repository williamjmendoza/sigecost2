<?php
	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionPrograma.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );

	class ControladorInstanciaETAplicacionOfimatica extends ControladorInstanciaETAplicacionPrograma
	{
		use ControladorTraitPaginacion;

		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_OFIMATICA_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETAplicacionOfimatica::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("aplicacionOfimatica.php?accion=buscar");
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
			$aplicaciones = ModeloInstanciaETAplicacionOfimatica::buscarAplicaciones($parametros);

			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionOfimaticaBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_OFIMATICA_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de aplicación ofimática con las mismas características
					if( ($existeAplicacion = ModeloInstanciaETAplicacionOfimatica::existeAplicacion($form->getAplicacionPrograma())) === null )
						throw new Exception("La instancia de aplicaci&oacute;n no pudo ser guardada.");

					// Validar si existe una instancia de aplicación ofimática  con las mismas características
					if ($existeAplicacion === true)
						throw new Exception("Ya existe una instancia de aplicaci&oacute;n con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de aplicación ofimática en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETAplicacionOfimatica::guardarAplicacion($form->getAplicacionPrograma());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de la aplicación ofimática
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de aplicación no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de aplicación ofimática  guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de aplicación ofimática guardada satisfactoriamente.";
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
			$aplicacion = ModeloInstanciaETAplicacionOfimatica::obtenerAplicacionPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['aplicacion'] = $aplicacion;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionOfimaticaDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionOfimatica::obtenerTodasAplicaciones();
			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionOfimaticaInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETAplicacionOfimatica();

?>