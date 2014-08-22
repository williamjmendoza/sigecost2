<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoComputacion.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/computadorEscritorio.php' );

	class ControladorInstanciaETComputadorEscritorio extends ControladorInstanciaETEquipoComputacion
	{
		public function buscar()
		{
			$computadoras = ModeloInstanciaETComputadorEscritorio::buscarComputadoras();

			$GLOBALS['SigecostRequestVars']['computadoras'] = $computadoras;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorEscritorioBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_COMPUTADOR_ESCRITORIO_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de computador de escritorio con las mismas características
					if( ($existeComputador = ModeloInstanciaETComputadorEscritorio::existeComputador($form->getEquipoComputacion())) === null )
						throw new Exception("La instancia de computador de escritorio no pudo ser guardada.");

					// Validar si existe una instancia de computador de escritorio con las mismas características
					if ($existeComputador === true)
						throw new Exception("Ya existe una instancia de computador de escritorio con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de computador de escritorio en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETComputadorEscritorio::guardarComputador($form->getEquipoComputacion());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de computador de escritorio
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de computador de escritorio no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de computador de escritorio guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de computador de escritorio guardada satisfactoriamente.";
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

			$computador= ModeloInstanciaETComputadorEscritorio::obtenerComputadorPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['computador'] = $computador;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorEscritorioDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/computadorEscritorioInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETComputadorEscritorio();
?>