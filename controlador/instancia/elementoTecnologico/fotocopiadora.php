<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once( SIGECOST_PATH_CONTROLADOR . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	
	// Modelos
	require_once( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/fotocopiadora.php' );
	
	class ControladorInstanciaETFotocopiadora extends ControladorInstanciaETEquipoReproduccion
	{
		public function buscar()
		{
			$fotocopiadoras = ModeloInstanciaETFotocopiadora::buscarFotocopiadoras();
				
			$GLOBALS['SigecostRequestVars']['fotocopiadoras'] = $fotocopiadoras;

			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/fotocopiadoraBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_FOTOCOPIADORA_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarMarca($form);
			$this->__validarModelo($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{	
					// Consultar si existe una instancia de fotocopiadora con las mismas características
					if( ($existeFotocopiadora = ModeloInstanciaETFotocopiadora::existeFotocopiadora($form->getEquipoReproduccion())) === null )
						throw new Exception("La instancia de fotocopiadora no pudo ser guardada.");
				
					// Validar si existe una instancia de fotocopiadora con las mismas características
					if ($existeFotocopiadora === true)
						throw new Exception("Ya existe una instancia de fotocopiadora con las mismas caracter&iacute;sticas.");
	
					// Guardar la instancia de fotocopiadora en la base de datos
					$iriNuevaInstancia = ModeloInstanciaETFotocopiadora::guardarFotocopiadora($form->getEquipoReproduccion());
			
					// Verificar si ocurrio algún error mientras se guardaba la instancia de escaner
					if ($iriNuevaInstancia === false) 
						throw new Exception("La instancia de fotocopiadora no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles
					// de la instancia de fotocopiadora guardada
					$GLOBALS['SigecostInfo']['general'][] = "Instancia de fotocopiadora guardada satisfactoriamente.";
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
			
			$fotocopiadora = ModeloInstanciaETFotocopiadora::obtenerFotocopiadoraPorIri($iriInstancia);
			
			$GLOBALS['SigecostRequestVars']['fotocopiadora'] = $fotocopiadora;
			
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/fotocopiadoraDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/instancia/elementoTecnologico/fotocopiadoraInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaETFotocopiadora();
?>