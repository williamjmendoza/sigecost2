<?php
	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/barraHerramientas.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/barraFormato.php' );

	class ControladorInstanciaETBarraFormato extends ControladorInstanciaETBarraHerramientas
	{
		use ControladorTraitPaginacion;

		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_BARRA_FORMATO_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETBarraFormato::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("barraFormato.php?accion=buscar");
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

			$barras = ModeloInstanciaETBarraFormato::buscarBarras($parametros);

			$GLOBALS['SigecostRequestVars']['barras'] = $barras;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraFormatoBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_BARRA_FORMATO_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de barra de dibujo con las mismas características
					if( ($existeBarra = ModeloInstanciaETBarraFormato::existeBarra($form->getBarraHerramientas())) === null )
						throw new Exception("La instancia de barra de formato no pudo ser guardada.");

					// Validar si existe una instancia de  barra de formato  con las mismas características
					if ($existeBarra === true)
						throw new Exception("Ya existe una instancia de barra de formato con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de  barra de formato en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETBarraFormato::guardarBarra($form->getBarraHerramientas());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de la barra de formato
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de barra de formato no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia debarra de dibujo  guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de barra de formato guardada satisfactoriamente.";
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
			$barra = ModeloInstanciaETBarraFormato::obtenerBarraPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['barra'] = $barra;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraFormatoDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$barras = ModeloInstanciaETAplicacionOfimatica::obtenerTodasBarras();
			$GLOBALS['SigecostRequestVars']['barras'] = $barras;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraFormatoInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETBarraFormato();

?>