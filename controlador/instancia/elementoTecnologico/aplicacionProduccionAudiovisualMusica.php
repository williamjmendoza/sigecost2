<?php
	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/aplicacionPrograma.php' );
	
	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php' );

	class ControladorInstanciaETAplicacionProduccionAudiovisualMusica extends ControladorInstanciaETAplicacionPrograma
	{
		public function buscar()
		{
			$aplicaciones = ModeloInstanciaETAplicacionProduccionAudiovisualMusica::buscarAplicaciones();
		
			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusicaBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_APLICACION_PRODUCCION_AUDIOVISUAL_MUSICA);
				
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarVersion($form);
			
			// Verificar que no hubo nig煤n error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de aplicaci贸n Produccion Audiovisual Musica con las mismas caracter铆sticas
					if( ($existeAplicacion = ModeloInstanciaETAplicacionProduccionAudiovisualMusica::existeAplicacion($form->getAplicacionPrograma())) === null )
						throw new Exception("La instancia de aplicaci&oacute;n no pudo ser guardada.");
					
					// Validar si existe una instancia de aplicaci贸n Produccion Audiovisual Musica  con las mismas caracter铆sticas
					if ($existeAplicacion === true)
						throw new Exception("Ya existe una instancia de aplicaci&oacute;n con las mismas caracter&iacute;sticas.");
					
					// Guardar la instancia de aplicaci贸n ofim谩tica en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETAplicacionProduccionAudiovisualMusica::guardarAplicacion($form->getAplicacionPrograma());
					
					// Verificar si ocurrio alg煤n error mientras se guardaba la instancia de la aplicaci贸n Produccion Audiovisual Musica
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de aplicaci贸n no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de aplicaci贸n Produccion Audiovisual Musica  guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de aplicaci贸n producci髇 audiovisual m鷖ica guardada satisfactoriamente.";
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
			$aplicacion = ModeloInstanciaETAplicacionProduccionAudiovisualMusica::obtenerAplicacionPorIri($iriInstancia);
				
			$GLOBALS['SigecostRequestVars']['aplicacion'] = $aplicacion;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusicaDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusicaInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaETAplicacionProduccionAudiovisualMusica();
	
?>