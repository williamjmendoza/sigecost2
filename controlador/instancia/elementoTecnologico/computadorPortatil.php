<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoComputacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/computadorPortatil.php' );

	class ControladorInstanciaETComputadorPortatil extends ControladorInstanciaETEquipoComputacion
	{
		public function buscar()
		{
			$portatiles = ModeloInstanciaETcomputadorPortatil::buscarPortatiles();

			$GLOBALS['SigecostRequestVars']['portatiles'] = $portatiles;

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

		private function __desplegarDetalles($iriInstancia)
		{

			$portatil= ModeloInstanciaETComputadorPortatil::obtenerPortatilPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['portatil'] = $portatil;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorPortatilDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorPortatilInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETComputadorPortatil();
?>