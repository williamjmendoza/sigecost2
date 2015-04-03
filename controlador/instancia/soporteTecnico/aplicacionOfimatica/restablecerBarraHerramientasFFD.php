<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php' );

	class ControladorInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD extends ControladorInstanciaSTAplicacionOfimatica
	{
		use ControladorTraitPaginacion;
		
		public function actualizar()
		{
			try
			{
				$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				if($usuario === null)
					throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
				
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
				
				if( ($instancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::obtenerInstanciaPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setSoporteTecnico($instancia);
				
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarSolucionSoporteTecnico($form);
				
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de soporte técnico en aplicacion de programa para restablecer barra de herramientas funcion formato dibujo, con las mismas características
					//if(($existeInstancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::existeInstancia($form->getSoporteTecnico())) === null)
						//throw new Exception("La instancia no pudo ser actualizada.");
					
					// Validar si existe una instancia de soporte técnico en aplicacion de programa para restablecer barra de herramientas funcion formato dibujo, con las mismas características
					//if ($existeInstancia === true)
						//throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
						
					// Establecer el usuario que realiza la modificación del patrón
					$patron = $form->getSoporteTecnico()->getPatron();
					$usuarioUltimaModificacion = clone $usuario;
					$patron->setUsuarioUltimaModificacion($usuarioUltimaModificacion);
					
					// Actualizar la instancia de soporte técnico en  aplicacion de programa para restablecer barra de herramientas funcion formato dibujo, en la base de datos
					$resultado = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::actualizarInstancia($form->getSoporteTecnico());
				
					if($resultado === false)
						throw new Exception("La instancia no pudo ser actualizada");
						
					$GLOBALS['SigecostInfo']['general'][] = "Instancia actualizada satisfactoriamente.";
						
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::buscarInstanciasTotalElementos();

			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("restablecerBarraHerramientasFFD.php?accion=buscar");
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

			$instancias = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::buscarInstancias($parametros);
			
			if($GLOBALS['SigecostRequestVars']['esAdministradorOntologia'])
				$truncamiento = (int)GetConfig("truncamientoSolucionPatronSoporteTecnicoAdministrador");
			else
				$truncamiento = (int)GetConfig("truncamientoSolucionPatronSoporteTecnico");

			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
			$GLOBALS['SigecostRequestVars']['truncamiento'] = $truncamiento;
			
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDBuscar.php' );
		}

		public function desplegarDetalles()
		{
			if(!isset($_REQUEST['iri']) || ($iri=trim($_REQUEST['iri'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un iri.';
			} else {
				$this->__desplegarDetalles($iri);
			}
		}
		
		public function eliminar()
		{
			try
			{
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea eliminar.");
		
				// Eliminar la instancia de soporte técnico en aplicación ofimática para restablecer barra de función formato dibujo, de la base de datos
				$resultado = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::eliminarInstancia($iri);
					
				if($resultado === false)
					throw new Exception("La instancia no pudo ser eliminada.");
		
				$GLOBALS['SigecostInfo']['general'][] = "Instancia eliminada satisfactoriamente.";
		
				$this->buscar();
		
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->buscar();
			}
		}
					
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarIriAplicacionPrograma($form);
			$this->__obtenerSolucionSoporteTecnico($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
					if($usuario === null)
						throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
					
					// Consultar si existe una instancia de soporte técnico  para restablecer barra herramientas funcion formato dibujo de una aplicación ofimática
					//if(($existeInstancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::existeInstancia($form->getSoporteTecnico())) === null)
						//throw new Exception("La instancia no pudo ser guardada.");

					// Validar si existe una instancia de soporte técnico para  restablecer barra herramientas funcion formato dibujo de una aplicación ofimática, con las mismas características
					//if ($existeInstancia === true)
						//throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
						
					// Establecer el usuario que crea el patrón
					$patron = $form->getSoporteTecnico()->getPatron();
					$usuarioCreador = clone $usuario;
					$patron->setUsuarioCreador($usuarioCreador);

					// Guardar la instancia de soporte técnico para  restablecer barra herramientas funcion formato dibujo  de una aplicación ofimática, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::guardarInstancia($form->getSoporteTecnico());

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

		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionOfimatica::obtenerTodasAplicaciones();

			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDInsertarModificar.php' );
		}

		public function insertar()
		{
			try
			{
				$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				if($usuario === null)
					throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
				
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_INSERTAR_MODIFICAR);
				
				// Establecer el usuario que crea el patrón
				$patron = $form->getSoporteTecnico()->getPatron();
				$usuarioCreador = clone $usuario;
				$patron->setUsuarioCreador($usuarioCreador);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->buscar();
			}
		}
		
		public function modificar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_OFIMATICA_RESTABLECER_BARRA_HERRAMIENTAS_FUNCION_FORMATO_DIBUJO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");
				
				if( ($instancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::obtenerInstanciaPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setSoporteTecnico($instancia);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
		}
		

		private function __desplegarDetalles($iriInstancia)
		{
			$instancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::obtenerInstanciaPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFDDetalles.php' );
		}
		
		protected function __generarPDF($iriInstancia)
		{
			$instancia = ModeloInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD::obtenerInstanciaPorIri($iriInstancia);
			$titulo = "Restablecer las barras herramientas funci&oacute;n, formato y/o dibujo en aplicaci&oacute;n ofim&aacute;tica";
		
			$this->__generarContenidoPDF($instancia, $titulo);
		}

	}

	new ControladorInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD();

?>