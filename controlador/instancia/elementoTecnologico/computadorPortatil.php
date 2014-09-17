<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoComputacion.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/computadorPortatil.php' );

	class ControladorInstanciaETComputadorPortatil extends ControladorInstanciaETEquipoComputacion
	{
		use ControladorTraitPaginacion;

		public function actualizar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
		
				$form->getEquipoComputacion()->setIri($iri);
		
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarMarca($form);
				$this->__validarModelo($form);
				
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de elemento tecnológico en computador portátil, con las mismas características
					if(($existeInstancia = ModeloInstanciaETComputadorPortatil::existePortatil($form->getEquipoComputacion())) === null)
						throw new Exception("La instancia no pudo ser actualizada.");
				
					// Validar si existe una instancia de elemento tecnológico en computador portátil con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
				
					// Actualizar la instancia de elemento tecnológico computador portátil, en la base de datos
					$resultado =  ModeloInstanciaETComputadorPortatil::actualizarInstancia($form->getEquipoComputacion());
					
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaETcomputadorPortatil::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("computadorPortatil.php?accion=buscar");
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

			$portatiles = ModeloInstanciaETcomputadorPortatil::buscarPortatiles($parametros);

			$GLOBALS['SigecostRequestVars']['portatiles'] = $portatiles;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorPortatilBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de computador de escritorio con las mismas características
					if( ($existePortatil= ModeloInstanciaETComputadorPortatil::existePortatil($form->getEquipoComputacion())) === null )
						throw new Exception("La instancia de computador portatil no pudo ser guardada.");

					// Validar si existe una instancia de computador portatil con las mismas características
					if ($existePortatil === true)
						throw new Exception("Ya existe una instancia de computador portatil con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de computador portatil en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETComputadorPortatil::guardarPortatil($form->getEquipoComputacion());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de computador portatil
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de computador portatil no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de computador  portatil guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de computador portatil guardada satisfactoriamente.";
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
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_PORTATIL_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");
		
				if( ($instancia = ModeloInstanciaETComputadorPortatil::obtenerPortatilPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
		
				$form->setEquipoComputacion($instancia);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
		}
		
		
		private function __desplegarDetalles($iriInstancia)
		{

			$portatil= ModeloInstanciaETComputadorPortatil::obtenerPortatilPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['portatil'] = $portatil;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorPortatilDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$portatiles = ModeloInstanciaETcomputadorPortatil::obtenerTodasComputadorasPortatil();
			$GLOBALS['SigecostRequestVars']['portatiles'] = $portatiles;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorPortatilInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETComputadorPortatil();
?>