<?php
	require_once( dirname(__FILE__) . '/../../../init.php' );

	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/barraHerramientas.php' );

	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/barraDibujo.php' );

	class ControladorInstanciaETBarraDibujo extends ControladorInstanciaETBarraHerramientas
	{
		public function buscar()
		{
			$barras = ModeloInstanciaETBarraDibujo::buscarBarras();

			$GLOBALS['SigecostRequestVars']['barras'] = $barras;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraDibujoBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_BARRA_DIBUJO_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de barra de dibujo con las mismas características
					if( ($existeBarra = ModeloInstanciaETBarraDibujo::existeBarra($form->getBarraHerramientas())) === null )
						throw new Exception("La instancia de barra de dibujo no pudo ser guardada.");

					// Validar si existe una instancia de  barra de dibujo  con las mismas características
					if ($existeBarra === true)
						throw new Exception("Ya existe una instancia de barra de dibujo con las mismas caracter&iacute;sticas.");

					// Guardar la instancia de  barra de dibujo en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETBarraDibujo::guardarBarra($form->getBarraHerramientas());

					// Verificar si ocurrio algún error mientras se guardaba la instancia de la barra de dibujo
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de barra de dibujo no pudo ser guardada.");

					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia debarra de dibujo  guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de barra de dibujo guardada satisfactoriamente.";
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
			$barra = ModeloInstanciaETBarraDibujo::obtenerBarraPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['barra'] = $barra;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraDibujoDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/barraDibujoInsertarModificar.php' );
		}
	}

	new ControladorInstanciaETBarraDibujo();

?>