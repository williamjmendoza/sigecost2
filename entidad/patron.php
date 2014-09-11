<?php

	class EntidadPatron
	{
		private $_id;
		private $_nombre;
		private $_solucion;
		
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
		public function getSolucion(){
			return $this->_solucion;
		}
		public function setSolucion($solucion){
			$this->_solucion = $solucion;
		}
		
	}
	
?>