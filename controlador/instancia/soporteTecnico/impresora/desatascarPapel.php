<?php

	require_once( dirname(__FILE__) . '/../../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/impresora/impresora.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/impresora.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/impresora/desatascarPapel.php' );
	
	class ControladorInstanciaSTImpresoraDesatascarPapel extends ControladorInstanciaSTImpresora
	{
		public function buscar()
		{
			$instancias = ModeloInstanciaSTImpresoraDesatascarPapel::buscarInstancias();
			
			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelBuscar.php' );
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_DESATASCAR_PAPEL_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarIriEquipoReproduccion($form);
			$this->__validarUrlSoporteTecnico($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Consultar si existe una instancia de soporte técnico en impresora para desatascar papel, con las mismas características
					if(($existeInstancia = ModeloInstanciaSTImpresoraDesatascarPapel::existeInstancia($form->getSoporteTecnico())) === null)
						throw new Exception("La instancia no pudo ser guardada.");
					
					// Validar si existe una instancia de soporte técnico en impresora para desatascar papel, con las mismas características
					if ($existeInstancia === true)
						throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Guardar instancia de soporte técnico en impresora para corregir impresión manchada, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTImpresoraDesatascarPapel::guardarInstancia($form->getSoporteTecnico());
					
					// Verificar si ocurrio algún error mientras se guardaba la instancia
					if ($iriNuevaInstancia === false)
						throw new Exception("La instancia de no pudo ser guardada.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles de la instancia de guardada
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
			$instancia = ModeloInstanciaSTImpresoraDesatascarPapel::obtenerInstanciaPorIri($iriInstancia);
				
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			$impresoras = ModeloInstanciaETImpresora::obtenerTodasImpresoras();
			
			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;
			
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/desatascarPapelInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaSTImpresoraDesatascarPapel();
?>
