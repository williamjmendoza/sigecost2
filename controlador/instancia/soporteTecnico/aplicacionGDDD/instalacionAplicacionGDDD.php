<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/aplicacionGDDD.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDD.php' );

	class ControladorInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD extends ControladorInstanciaSTAplicacionGDDD
	{
		use ControladorTraitPaginacion;

		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_INSTALACION_APLICACION_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("instalacionAplicacionGDDD.php?accion=buscar");
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
			$instancias = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::buscarInstancias($parametros);

			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_INSTALACION_APLICACION_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarUrlSoporteTecnico($form);
			$this->__validarIriAplicacionPrograma($form);
			$this->__validarIriSistemaOperativo($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de soporte técnico en instalación de una aplicación gráfica digital, dibujo y diseño, con las mismas características
					if(($existeInstancia = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::existeInstancia($form->getSoporteTecnico())) === null)
						throw new Exception("La instancia no pudo ser guardada.");

					// Validar si existe una instancia de soporte técnico en instalación de una aplicación gráfica digital, dibujo y diseño, con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de soporte técnico en  aplicaion instalación de una aplicación gráfica digital, dibujo y diseño, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::guardarInstancia($form->getSoporteTecnico());

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
			$instancia = ModeloInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD::obtenerInstanciaPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::obtenerTodasAplicaciones();
			$sistemasOperativos = ModeloInstanciaETSistemaOperativo::obtenerTodosSitemasOperativos();

			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
			$GLOBALS['SigecostRequestVars']['sistemasOperativos'] = $sistemasOperativos;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDDInsertarModificar.php' );
		}

		// Obtener y validar el iri de la instancia de sistema operativo
		private function __validarIriSistemaOperativo(FormularioInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD $form)
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

	new ControladorInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD();
?>