<?php
	
	class EntidadUsuario
	{
		private $_apellido;
		private $_contraseña;
		private $_id;
		private $_login;		
		private $_nombre;
		
		public function getApellido(){
			return $this->_apellido;
		}
		public function setApellido($apellido){
			$this->_apellido = $apellido;
		}
		public function getContrasena(){
			return $this->_contraseña;
		}
		public function setContrasena($contrasena){
			$this->_contraseña = $contrasena;
		}
		public function getId(){
			return $this->_id;
		}
		public function setId($id){
			$this->_id = $id;
		}
		public function getLogin(){
			return $this->_login;
		}
		public function setLogin($login) {
			$this->_login = $login;
		}
		public function getNombre(){
			return $this->_nombre;
		}
		public function setNombre($nombre){
			$this->_nombre = $nombre;
		}
	}
?>