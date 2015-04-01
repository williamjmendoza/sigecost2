<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/aplicacionGDDD/aplicacionGDDD.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/sobreSistemaOperativo.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php' );

	class ControladorInstanciaSTAplicacionGDDDDesinstalacionAplicacion extends ControladorInstanciaSTAplicacionGDDD
	{
		use ControladorTraitPaginacion;
		use ControladorTraitInstanciaSTSobreSistemaOperativo;

		public function actualizar()
		{
			try
			{
				$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				if($usuario === null)
					throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
				
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea actualizar.");
				
				if( ($instancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::obtenerInstanciaPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setSoporteTecnico($instancia);
		
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarSolucionSoporteTecnico($form);
				
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Consultar si existe una instancia de soporte técnico en desinstalacion de aplicacion gráfica dibujo digital y diseño, con las mismas características
					//if(($existeInstancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::existeInstancia($form->getSoporteTecnico())) === null)
						//throw new Exception("La instancia no pudo ser actualizada.");
						
					// Validar si existe una instancia de soporte técnico en  desinstalacion de aplicacion gráfica dibujo digital y diseño, con las mismas características
					//if ($existeInstancia === true)
						//throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
					
					// Establecer el usuario que realiza la modificación del patrón
					$patron = $form->getSoporteTecnico()->getPatron();
					$usuarioUltimaModificacion = clone $usuario;
					$patron->setUsuarioUltimaModificacion($usuarioUltimaModificacion);
					
					// Actualizar la instancia de soporte técnico en  desinstalacion de aplicacion gráfica dibujo digital y diseñodesinstalacion de aplicacion ofimatica, en la base de datos
					$resultado = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::actualizarInstancia($form->getSoporteTecnico());
						
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_BUSCAR);

			// Obtener la cantidad total de elementos de instancias que se obtendrán en la bśuqueda
			$totalElementos = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::buscarInstanciasTotalElementos();


			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Tamaño página>, <Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("desinstalacionAplicacion.php?accion=buscar");
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
			// Realizar la consulta de la búsuqeda
			$instancias = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::buscarInstancias($parametros);
			
			if($GLOBALS['SigecostRequestVars']['esAdministradorOntologia'])
				$truncamiento = (int)GetConfig("truncamientoSolucionPatronSoporteTecnicoAdministrador");
			else
				$truncamiento = (int)GetConfig("truncamientoSolucionPatronSoporteTecnico");

			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
			$GLOBALS['SigecostRequestVars']['truncamiento'] = $truncamiento;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionBuscar.php' );
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
		
				// Eliminar la instancia de soporte técnico en aplicación de programa para instalación de aplicación gráfica digital,dibujo y diseño, de la base de datos
				$resultado = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::eliminarInstancia($iri);
					
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
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR);

			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarIriAplicacionPrograma($form);
			$this->__validarIriSistemaOperativo($form);
			$this->__obtenerSolucionSoporteTecnico($form);

			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
					if($usuario === null)
						throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
					
					// Consultar si existe una instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño, con las mismas características
					//if(($existeInstancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::existeInstancia($form->getSoporteTecnico())) === null)
						//throw new Exception("La instancia no pudo ser guardada.");

					// Validar si existe una instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño, con las mismas características
					//if ($existeInstancia === true)
						//throw new Exception("Ya existe una instancia con las mismas caracter&iacute;sticas.");
						
					// Establecer el usuario que crea el patrón
					$patron = $form->getSoporteTecnico()->getPatron();
					$usuarioCreador = clone $usuario;
					$patron->setUsuarioCreador($usuarioCreador);

					// Guardar la instancia de soporte técnico para la desinstalación de una aplicación gráfica digital, dibujo y diseño, en la base de datos
					$iriNuevaInstancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::guardarInstancia($form->getSoporteTecnico());

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

		public function insertar()
		{
			try
			{
				$usuario = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				if($usuario === null)
					throw new Exception("Se debe iniciar sesi&oacute;n para poder realizar esta operaci&oacute;n");
				
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR);
				
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
				$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_APLICACION_G_D_D_D_DESINSTALACION_APLICACION_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
		
				if( (!isset($_POST['iri'])) || (($iri=trim($_POST['iri'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para la instancia que desea modificar.");

				if( ($instancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::obtenerInstanciaPorIri($iri)) === null )
					throw new Exception("La instancia no pudo ser cargada.");
				
				$form->setSoporteTecnico($instancia);
					
				$this->__desplegarFormulario();
				
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					$this->__desplegarFormulario();
			}
		}
		
		public function generarPDF()
		{

			if(!isset($_REQUEST['iri']) || ($iri=trim($_REQUEST['iri'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un iri.';
			} else {
				$this->__generarPDF($iri);
			}
		}
		
		private function __generarPDF($iriInstancia)
		{
			
			$instancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::obtenerInstanciaPorIri($iriInstancia);
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
			
			
			// Librerías
			require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
			require_once( SIGECOST_PATH_LIB . '/html2pdf/html2pdf.class.php' );
			
				
			$html2pdf = new HTML2PDF('P', 'Letter', 'es', true, 'UTF-8', array(0, 0, 0, 0));
			
			
			
			
			// Parte de la vista
			$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
			$patron = $instancia != null ? $instancia->getPatron() : null;
			$instanciaAplicacion = $instancia->getAplicacionPrograma();
			$instanciaSistemaOperativo = $instancia->getSistemaOperativo();
			
			ob_start();
		?>
		<style type="text/css">
			<!--
			table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
			table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
			div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }
			ul.main { width: 95%; list-style-type: square; }
			ul.main li { padding-bottom: 2mm; }
			/*h1 { text-align: center; font-size: 5mm}*/
			h3 { text-align: center; font-size: 14mm}
    		-->
		</style>
		<page backtop="32mm" backbottom="14mm" backleft="14mm" backright="10mm" style="font-size: 12pt">
			<page_header>
				<table class="page_header">
					<tr>
						<td style="width: 50%; text-align: left">
							A propos de ...
						</td>
						<td style="width: 50%; text-align: right">
							HTML2PDF v<?php echo __CLASS_HTML2PDF__; ?>
						</td>
					</tr>
				</table>
				<div style="padding: 2mm 10mm 2mm 10mm; width: 100%;">
					<h1>
						Desinstalaci&oacute;n de aplicaci&oacute;n gr&aacute;fica digital, dibujo y dise&ntilde;o
					</h1>
				</div>
			</page_header>
			
		
			<table style="width: 100%;" align="center">
				<tr>
					<td style="width: 30%;"><strong>En apliaci&oacute;n de programa:</strong></td>
					<td style="width: 70%;"><?php echo $instanciaAplicacion != null ? $instanciaAplicacion->getNombre() . ' - ' .$instanciaAplicacion->getVersion() : "" ?></td>
				</tr>
				<tr>
					<td style="width: 30%;"><strong>Sobre sistema operativo:</strong></td>
					<td style="width: 70%;">
						<?php echo $instanciaSistemaOperativo != null ? $instanciaSistemaOperativo->getNombre() . ' - ' . $instanciaSistemaOperativo->getVersion() : "" ?>
					</td>
				</tr>
			</table>
			<br>
			
			<table style="width: 100%; border: solid 1px #5544DD; border-collapse: collapse" align="center">
				<thead>
					<tr>
						<th colspan="2" style="width: 100%; text-align: left; border: solid 1px #337722; background: #CCFFCC">
							Soluci&oacute;n de incidencia de soporte t&eacute;cnico
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width: 20%; border: solid 1px #55DD44; vertical-align: top;"><strong>Nombre:</strong></td>
						<td style="width: 80%; border: solid 1px #55DD44"><?php echo $patron != null ? $patron->getNombre() : "" ?></td>
					</tr>
					<tr>
						<td style="width: 20%; border: solid 1px #55DD44; vertical-align: top;"><strong>Descripci&oacute;n:</strong></td>
						<td style="width: 80%; border: solid 1px #55DD44"><?php //echo $patron != null ? $patron->getSolucion() : "" ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2" style="width: 100%; text-align: left; border: solid 1px #337722; background: #CCFFCC">
							Creada por
							<?php
								echo $patron != null
									? 	(	$patron->getUsuarioCreador() != null
											? $patron->getUsuarioCreador()->getNombre() . " " . $patron->getUsuarioCreador()->getApellido() : ""
										)
									: ""
							?>
							<?php echo $patron != null ? " el " . $patron->getFechaCreacion() : "" ?>
						</th>
					</tr>
				</tfoot>
			</table>
		
			<br>
		
			<div style="padding: 2mm; width: 90%;">
				<?php echo $patron != null ? $patron->getSolucion() : "" ?>
			</div>
		
		</page>
		<?php
			$contenido = ob_get_clean();
			
			$html2pdf->writeHTML($contenido);
			$html2pdf->Output('instancia_'.date('Ymd_His').'.pdf');
		}
		
		private function __desplegarDetalles($iriInstancia)
		{
			$instancia = ModeloInstanciaSTAplicacionGDDDDesinstalacionAplicacion::obtenerInstanciaPorIri($iriInstancia);

			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionDetalles.php' );
		}

		private function __desplegarFormulario()
		{
			$aplicaciones = ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno::obtenerTodasAplicaciones();
			$sistemasOperativos = ModeloInstanciaETSistemaOperativo::obtenerTodosSitemasOperativos();

			$GLOBALS['SigecostRequestVars']['aplicaciones'] = $aplicaciones;
			$GLOBALS['SigecostRequestVars']['sistemasOperativos'] = $sistemasOperativos;

			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacionInsertarModificar.php' );
		}

		// Obtener y validar el iri de la instancia de sistema operativo
		private function __validarIriSistemaOperativo(FormularioInstanciaSTAplicacionGDDDDesinstalacionAplicacion $form)
		{
			if(!isset($_POST['iriSistemaOperativo']) || ($iriSistemaOperativo=trim($_POST['iriSistemaOperativo'])) == ''
					|| $iriSistemaOperativo == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar un sistema operativo.';
			} else {
				$form->getSoporteTecnico()->getSistemaOperativo()->setIri($iriSistemaOperativo);
			}
		}
	}

	new ControladorInstanciaSTAplicacionGDDDDesinstalacionAplicacion();
	
	/*
	 * 
	
	
	<page backtop="14mm" backbottom="14mm" backleft="14mm" backright="10mm" style="font-size: 12pt">
		
			<table border="1">
				<tr>
					<td colspan="2">Incidencia de soporte t&eacute;cnico</td>
				</tr>
				<tr>
					<td>En apliaci&oacute;n de programa</td>
					<td><?php echo $instanciaAplicacion != null ? $instanciaAplicacion->getNombre() . ' - ' .$instanciaAplicacion->getVersion() : "" ?></td>
				</tr>
				<tr>
					<td>Sobre sistema operativo</td>
					<td>
						<?php echo $instanciaSistemaOperativo != null ? $instanciaSistemaOperativo->getNombre() . ' - ' . $instanciaSistemaOperativo->getVersion() : "" ?>
					</td>
				</tr>
			</table>
			
			
			
			<table style="width: 80%;border: solid 1px #5544DD;" align="center">
				<tbody>
					<tr>
						<td colspan="2">Soluci&oacute;n de incidencia de soporte t&eacute;cnico</td>
					</tr>
					<tr>
						<td>Nombre:</td>
						<td><?php echo $patron != null ? $patron->getNombre() : "" ?></td>
					</tr>
					<tr>
						<td>Descripci&oacute;n:</td>
						<td><?php echo $patron != null ? $patron->getSolucion() : "" ?></td>
					</tr>
				</tbody>	
			</table>
		
		</page>
	
	
	 * 
	 * */
	
?>