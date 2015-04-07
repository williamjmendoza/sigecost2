<?php

	require_once( dirname(__FILE__) . '/../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/usuario.php' );

	class ControladorUsuario extends Controlador
	{
		use ControladorTraitPaginacion;
		
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'administracionUsuarios';
				
			parent::__construct();
		}

		public function actualizar()
		{
			try
			{
				$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				// Validar, obtener y guardar el input de idUsuario desde el formulario
				$this->__validarIdUsuario($form);
				
				$usuario = ModeloUsuario::obtenerUsuarioPorId($form->getUsuario()->getId());
				
				if($usuario === null || $usuario === false)
					throw new Exception("El usuario no pudo ser cargado");
				
				// Borrar los roles en el usuario que se cargo desde la base de datos, para que se establezcan solo los roles indicados en el formulario 
				$usuario->setRoles(array());
				// Cargar en el form los datos del usuario que están en la base de datos (sin actualizar)
				$form->setUsuario($usuario);
				
				// Validar, obtener y guardar todos los inputs desde el formulario
				$this->__validarUsuario($form);
				$this->__validarContrasenaActualizar($form);
				$this->__validarCedula($form);
				$this->__validarNombre($form);
				$this->__validarApellido($form);
				$this->__validarEsAdministradorIncidencias($form);
				$this->__validarEsAdministradorUsuarios($form);
				
				// Verificar que no hubo nigún error con los datos suministrados en el formulario
				if(count($GLOBALS['SigecostErrors']['general']) == 0)
				{
					// Actualizar el usuario en la base de datos
					$isUsuario = ModeloUsuario::actualizarUsuario($form->getUsuario());
					
					if($isUsuario === false)
						throw new Exception("El usuario no pudo ser actualizado");
						
					$GLOBALS['SigecostInfo']['general'][] = "Usuario actualizado satisfactoriamente.";
					
					$this->__desplegarDetalles($isUsuario);
					
				} else {
					$this->__desplegarFormulario();
				}
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
			
		}
		
		public function actualizarMiCuenta()
		{
			try
			{
				$GLOBALS['SigecostRequestVars']['menuActivo'] = 'miCuenta';
				
				$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
			
				$usuarioActual = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				
				if( $usuarioActual === null )
					throw new Exception("No posee permisos para realizar esta operacion.");
			
				if( ($usuario = ModeloUsuario::obtenerUsuarioPorId($usuarioActual->getId())) === null || $usuario === false)
					throw new Exception("El usuario no pudo ser cargado.");
			
				$form->setUsuario($usuario);
				
				try
				{
					$this->__validarUsuario($form);
					$this->__validarContrasenaActualizar($form);
					
					// Verificar que no hubo nigún error con los datos suministrados en el formulario
					if(count($GLOBALS['SigecostErrors']['general']) == 0)
					{
						// Actualizar los dtos del usuario en la base de datos
						$isUsuario = ModeloUsuario::actualizarUsuario($form->getUsuario(), false);
							
						if($isUsuario === false)
							throw new Exception("Los datos no pudieron ser actualizados");
						
						$GLOBALS['SigecostInfo']['general'][] = "Datos actualizados satisfactoriamente.";
					}
				} catch (Exception $e){
					$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
					require ( SIGECOST_PATH_VISTA . '/usuario/miCuentaModificar.php' );
				}
				
				require ( SIGECOST_PATH_VISTA . '/usuario/miCuentaModificar.php' );
			
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				require ( SIGECOST_PATH_BASE . '/index.php' );
			}
		}
		
		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_USUARIO_BUSCAR);
			
			// Obtener la cantidad total de elementos de usuarios que se obtendrán en la búsqueda
			$totalElementos = ModeloUsuario::buscarUsuariosTotalElementos();
			
			// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
			if($totalElementos !== false)
			{
				// Configurar el objeto de paginación
				$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Total elementos>)
				$this->__validarParametrosPaginacion($form);
				$form->getPaginacion()->setUrlObjetivo("usuario.php?accion=buscar");
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
			
			$usuarios = ModeloUsuario::buscarUsuarios($parametros);
			
			$GLOBALS['SigecostRequestVars']['usuarios'] = $usuarios;
			$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
			
			require ( SIGECOST_PATH_VISTA . '/usuario/usuarioBuscar.php' );
		}
		
		public function desplegarDetalles()
		{
			if(!isset($_REQUEST['idUsuario']) || ($idUsuario=trim($_REQUEST['idUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un id de usuario.';
			} else {
				$this->__desplegarDetalles($idUsuario);
			}
		}
		
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarUsuario($form);
			$this->__validarContrasena($form);
			$this->__validarCedula($form);
			$this->__validarNombre($form);
			$this->__validarApellido($form);
			$this->__validarEsAdministradorIncidencias($form);
			$this->__validarEsAdministradorUsuarios($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					// Guardar el usuario en la base de datos
					$idUsuario = ModeloUsuario::guardarUsuario($form->getUsuario());
					
					// Verificar si ocurrio algún error mientras se guardaba el usuario
					if ($idUsuario === false)
						throw new Exception("El usuario de no pudo ser guardado.");
					
					// Mostrar un mensaje indicando que se ha guardado satisfactoriamente, y mostrar los detalles de la instancia guardada
					$GLOBALS['SigecostInfo']['general'][] = "Usuario guardado satisfactoriamente.";
					$this->__desplegarDetalles($idUsuario);
					
					
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
				$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
		
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
				$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				if( (!isset($_REQUEST['idUsuario'])) || (($idUsuario=trim($_REQUEST['idUsuario'])) == '') )
					throw new Exception("No se encontr&oacute; ning&uacute;n identificador para el usuario que desea modificar.");
				
				if( ($usuario = ModeloUsuario::obtenerUsuarioPorId($idUsuario)) === null || $usuario === false)
					throw new Exception("El usuario no pudo ser cargado.");
				
				$form->setUsuario($usuario);
				
				$this->__desplegarFormulario();
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				$this->__desplegarFormulario();
			}
		}
		
		public function modificarMiCuenta()
		{
			try
			{
				$GLOBALS['SigecostRequestVars']['menuActivo'] = 'miCuenta';
				
				$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
				$form->SetTipoOperacion(Formulario::TIPO_OPERACION_MODIFICAR);
				
				$usuarioActual = ModeloSesion::estaSesionIniciada() === true ? ModeloGeneral::getConfInitial('usuario') : null;
				
				if( $usuarioActual === null )
					throw new Exception("No posee permisos para realizar esta operacion.");
				
				if( ($usuario = ModeloUsuario::obtenerUsuarioPorId($usuarioActual->getId())) === null || $usuario === false)
					throw new Exception("El usuario no pudo ser cargado.");
				
				$form->setUsuario($usuario);
				
				require ( SIGECOST_PATH_VISTA . '/usuario/miCuentaModificar.php' );
				
			} catch (Exception $e){
				$GLOBALS['SigecostErrors']['general'][] = $e->getMessage();
				require ( SIGECOST_PATH_BASE . '/index.php' );
			}
		}
		
		private function __desplegarDetalles($idUsuario)
		{
			$usuario = ModeloUsuario::obtenerUsuarioPorId($idUsuario);
		
			$GLOBALS['SigecostRequestVars']['usuario'] = $usuario;
		
			require ( SIGECOST_PATH_VISTA . '/usuario/usuarioDetalles.php' );
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_PATH_VISTA . '/usuario/usuarioInsertarModificar.php' );
		}
		
		// Obtener y validar el id del usuario
		private function __validarIdUsuario(FormularioUsuario $form)
		{
			if(!isset($_REQUEST['idUsuario']) || ($idUsuario=trim($_REQUEST['idUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'El id del usuario no pudo ser encontrado';
			} else {
				$form->getUsuario()->setId($idUsuario);
			}
		}
		
		// Obtener y validar la contraseña
		private function __validarContrasena($form)
		{
			if(!isset($_POST['contrasenaCodUsuario']) || ($contrasena=trim($_POST['contrasenaCodUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una contrase&ntilde;a.';
			} else if(!isset($_POST['contrasenaConfirmacionCodUsuario']) || ($contrasenaConfirmacion=trim($_POST['contrasenaConfirmacionCodUsuario'])) == ''){
					$GLOBALS['SigecostErrors']['general'][] = 'Debe confirmar la contrase&ntilde;a.';
			} else if($contrasena != $contrasenaConfirmacion) {
				$GLOBALS['SigecostErrors']['general'][] = 'Las contrase&ntilde;as no coinciden.';
			} else {
				$form->getUsuario()->setContrasena($contrasena);
			}
				
		}
		
		// Obtener y validar la contraseña, en la acción actualizar el usuario
		private function __validarContrasenaActualizar($form)
		{
			if(isset($_POST['contrasenaCodUsuario']) && ($contrasena=trim($_POST['contrasenaCodUsuario'])) != ''){
				if(!isset($_POST['contrasenaConfirmacionCodUsuario']) || ($contrasenaConfirmacion=trim($_POST['contrasenaConfirmacionCodUsuario'])) == ''){
					$GLOBALS['SigecostErrors']['general'][] = 'Debe confirmar la contrase&ntilde;a.';
				} else if($contrasena != $contrasenaConfirmacion) {
					$GLOBALS['SigecostErrors']['general'][] = 'Las contrase&ntilde;as no coinciden.';
				} else {
					$form->getUsuario()->setContrasena($contrasena);
				}
			}
		}
		
		// Obtener y validar la cédula
		private function __validarCedula(FormularioUsuario $form)
		{
			if(!isset($_POST['cedulalUsuario']) || ($cedula=trim($_POST['cedulalUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una c&eacute;dula.';
			} else {
				$form->getUsuario()->setCedula($cedula);
			}
		}
		
		// Obtener y validar el identificador del usuario
		private function __validarUsuario(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioUsuario']) || ($usuario=trim($_POST['usuarioUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un identificador de usuario.';
			} else {
				$form->getUsuario()->setUsuario($usuario);
			}
		}
		
		// Obtener y validar el nombre del usuario
		private function __validarNombre(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioNombre']) || ($nombre=trim($_POST['usuarioNombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir el nombre del usuario.';
			} else {
				$form->getUsuario()->setNombre($nombre);
			}
		}
		
		// Obtener y validar el apellido del usuario
		private function __validarApellido(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioApellido']) || ($apellido=trim($_POST['usuarioApellido'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir el apellido del usuario.';
			} else {
				$form->getUsuario()->setApellido($apellido);
			}
		}
		
		// Obtener y validar el input es administrador de incidencias
		private function __validarEsAdministradorIncidencias(FormularioUsuario $form)
		{
			if( isset($_POST['esAdministradorIncidencias']) && ($_POST['esAdministradorIncidencias']) == 'true' )
			{
				$rol = new EntidadRol();
				$rol->setId(SIGECOST_USUARIO_ADMINISTRADOR_ONTOLOGIA);
				$form->getUsuario()->setRol($rol);
			}
		}
		
		// Obtener y validar el input es administrador de usuarios
		private function __validarEsAdministradorUsuarios(FormularioUsuario $form)
		{
			if( isset($_POST['esAdministradorUsuarios']) && ($_POST['esAdministradorUsuarios']) == 'true' )
			{
				$rol = new EntidadRol();
				$rol->setId(SIGECOST_USUARIO_ADMINISTRADOR_USUARIOS);
				$form->getUsuario()->setRol($rol);
			}
		}
	}
	
	new ControladorUsuario();
?>