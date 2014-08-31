<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/impresora.php' );

	class ControladorInstanciaETImpresora extends ControladorInstanciaETEquipoReproduccion
	{
		use ControladorTraitPaginacion;

		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_IMPRESORA_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETImpresora::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("impresora.php?accion=buscar");
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

			$impresoras = ModeloInstanciaETImpresora::buscarImpresoras($parametros);

			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/impresoraBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_IMPRESORA_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de impresora con las mismas características
					if( ($existeImpresora = ModeloInstanciaETImpresora::existeImpresora($form->getEquipoReproduccion())) === null )
						throw new Exception("La instancia de impresora no pudo ser guardada.");

					// Validar si existe una instancia de impresora con las mismas características
					if ($existeImpresora === true)
						throw new Exception("Ya existe una instancia de impresora con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de impresora en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETImpresora::guardarImpresora($form->getEquipoReproduccion());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de impresora
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de impresora no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de impresora guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de impresora guardada satisfactoriamente.";
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

			$impresora = ModeloInstanciaETImpresora::obtenerImpresoraPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['impresora'] = $impresora;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/impresoraDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$impresoras = ModeloInstanciaETImpresora::obtenerTodasImpresoras();
			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/impresoraInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETImpresora();
?>