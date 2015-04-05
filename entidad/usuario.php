<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/rol.php' );
	
	class EntidadUsuario
	{
		private $_apellido;
		private $_cedula;
		private $_usuario;
		private $_contraseña;
		private $_estatus;
		private $_id;
		private $_nombre;
		private $_roles;
		
		public function getApellido(){
			return $this->_apellido;
		}
		public function setApellido($apellido){
			$this->_apellido = $apellido;
		}
		public function getCedula(){
			return $this->_cedula;
		}
		public function setCedula($cedula){
			$this->_cedula = $cedula;
		}
		public function getContrasena(){
			return $this->_contraseña;
		}
		public function setContrasena($contrasena){
			$this->_contraseña = $contrasena;
		}
		public function getEstatus(){
			return $this->_estatus;
		}
		public function setEstatus($estatus){
			$this->_estatus = $estatus;
		}
		public function getId(){
			return $this->_id;
		}
		public function setId($id){
			$this->_id = $id;
		}
		public function getNombre(){
			return $this->_nombre;
		}
		public function setNombre($nombre){
			$this->_nombre = $nombre;
		}
		public function getRoles(){
			return $this->_roles;
		}
		public function setRoles($roles){
			$this->_roles = $roles;
		}
		public function setRol(EntidadRol $rol){
			if(!is_array($this->_roles))
				$this->_roles = array();
			
			$this->_roles[$rol->getId()] = $rol;
		}
		public function getUsuario(){
			return $this->_usuario;
		}
		public function setUsuario($usuario) {
			$this->_usuario = $usuario;
		}
	}
?>