<?php
	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionPrograma.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php' );

	class ControladorInstanciaETAplicacionGraficaDigitalDibujoDiseno extends ControladorInstanciaETAplicacionPrograma
	{
		use ControladorTraitPaginacion;

		public function actualizar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
				
				$form->getAplicacionPrograma()->setIri($iri);
				
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarNombre($form);
				$this->__validarVersion($form);
				
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de elemento tecnológico en aplicación gráfica dibujo digital y diseño, con las mismas características
					if(($existeInstancia = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::existeAplicacion($form->getAplicacionPrograma())) === null)
						throw new Exception("La instancia no pudo ser actualizada.");
					
					// Validar si existe una instancia de elemento tecnológico en aplicación gráfica dibujo digital y diseño, con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Actualizar la instancia de soporte técnico en elemento tecnológico en aplicación gráfica dibujo digital y diseño, en la base de datos
					$resultado = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::actualizarInstancia($form->getAplicacionPrograma());
					
					if($resultado === false)
						throw new Exception("La instancia no pudo ser actualizada");
					
					$GLOBALS['SigecostErrors']['general'] = "Instancia actualizada satisfactoriamente";
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("aplicacionGraficaDigitalDibujoDiseno.php?accion=buscar");
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
			$aplicaciones = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::buscarAplicaciones($parametros);

			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDisenoBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de aplicación gráfica digital, dibujo y diseño con las mismas características
					if( ($existeAplicacion = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::existeAplicacion($form->getAplicacionPrograma())) === null )
						throw new Exception("La instancia de aplicaci&oacute;n no pudo ser guardada.");

					// Validar si existe una instancia de aplicación gráfica digital, dibujo y diseño con las mismas características
					if ($existeAplicacion === true)
						throw new Exception("Ya existe una instancia de aplicaci&oacute;n con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de aplicación en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::guardarAplicacion($form->getAplicacionPrograma());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de la aplicación gráfica digital, dibujo y diseño
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de aplicación no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de aplicación gráfica digital, dibujo y diseño guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de aplicación guardada satisfactoriamente.";
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
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");
				
				if( ($instancia = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::obtenerAplicacionPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setAplicacionPrograma($instancia);
					
				$this->__desplegarFormulario();
				
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					$this->__desplegarFormulario();
			}
		}
				
		
		private function __desplegarDetalles($iriInstancia)
		{
			$aplicacion = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::obtenerAplicacionPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['aplicacion'] = $aplicacion;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDisenoDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::obtenerTodasAplicaciones();
			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDisenoInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETAplicacionGraficaDigitalDibujoDiseno();

?>