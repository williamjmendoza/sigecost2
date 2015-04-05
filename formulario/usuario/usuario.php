<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/usuario.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');
	
	class FormularioUsuario extends Formulario
	{
		protected $_usuario;
		
		public function __construct(){
			$this->setUsuario(new EntidadUsuario());
		}
		
		public function getUsuario(){
			return $this->_usuario;
		}
		
		public function setUsuario(EntidadUsuario $usuario){
			$this->_usuario = $usuario;
		}
	}
?>