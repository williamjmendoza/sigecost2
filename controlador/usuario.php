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
		
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_USUARIO_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarCedula($form);
			$this->__validarUsuario($form);
			$this->__validarNombre($form);
			$this->__validarApellido($form);
			$this->__validarEsAdministradorIncidencias($form);
			$this->__validarEsAdministradorUsuarios($form);
			
			// Verificar que no hubo nigún error con los datos suministrados en el formulario
			if(count($GLOBALS['SigecostErrors']['general']) == 0)
			{
				try
				{
					throw new Exception("Todo validado correctamente");
					
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
		
		public function desplegarDetalles()
		{
			if(!isset($_REQUEST['idUsuario']) || ($idUsuario=trim($_REQUEST['idUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un id de usuario.';
			} else {
				$this->__desplegarDetalles($idUsuario);
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
		
		// Obtener y validar la cédula
		protected function __validarCedula(FormularioUsuario $form)
		{
			if(!isset($_POST['cedulalUsuario']) || ($cedula=trim($_POST['cedulalUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un c&eacute;dula.';
			} else {
				$form->getUsuario()->setCedula($cedula);
			}
		}
		
		// Obtener y validar el identificador del usuario
		protected function __validarUsuario(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioUsuario']) || ($usuario=trim($_POST['usuarioUsuario'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un identificador de usuario.';
			} else {
				$form->getUsuario()->setUsuario($usuario);
			}
		}
		
		// Obtener y validar el nombre del usuario
		protected function __validarNombre(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioNombre']) || ($nombre=trim($_POST['usuarioNombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir el nombre del usuario.';
			} else {
				$form->getUsuario()->setNombre($nombre);
			}
		}
		
		// Obtener y validar el apellido del usuario
		protected function __validarApellido(FormularioUsuario $form)
		{
			if(!isset($_POST['usuarioApellido']) || ($apellido=trim($_POST['usuarioApellido'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir el apellido del usuario.';
			} else {
				$form->getUsuario()->setApellido($apellido);
			}
		}
		
		// Obtener y validar el input es administrador de incidencias
		protected function __validarEsAdministradorIncidencias(FormularioUsuario $form)
		{
			if( isset($_POST['esAdministradorIncidencias']) && ($_POST['esAdministradorIncidencias']) == 'true' )
			{
				$rol = new EntidadRol();
				$rol->setId(SIGECOST_USUARIO_ADMINISTRADOR_ONTOLOGIA);
				$form->getUsuario()->setRol($rol);
			}
		}
		
		// Obtener y validar el input es administrador de usuarios
		protected function __validarEsAdministradorUsuarios(FormularioUsuario $form)
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