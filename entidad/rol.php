<?php

	class EntidadRol
	{
		private $_estatus;
		private $_id;
		private $_nombre;
		
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
	}
?>