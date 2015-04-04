<?php

	require_once( dirname(__FILE__) . '/../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/usuario.php' );

	class ControladorUsuario extends Controlador
	{
		
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
	}
	
	new ControladorUsuario();
?>