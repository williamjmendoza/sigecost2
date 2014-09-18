<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/usuario.php' );

	class EntidadPatron
	{
		private $_codigo;
		private $_fechaCreacion;
		private $_fechaUltimaModificacion;
		private $_nombre;
		private $_solucion;
		private $_usuarioCreador;
		private $_usuarioUltimaModificacion;
		
		public function getCodigo(){
			return $this->_codigo;
		}
		public function setCodigo($codigo){
			$this->_codigo = $codigo;
		}
		public function getFechaCreacion(){
			return $this->_fechaCreacion;
		}
		public function setFechaCreacion($fechaCreacion){
			$this->_fechaCreacion = $fechaCreacion;
		}
		public function getFechaUltimaModificacion(){
			return $this->_fechaUltimaModificacion;
		}
		public function setFechaUltimaModificacion($fechaUltimaModificacion){
			$this->_fechaUltimaModificacion = $fechaUltimaModificacion;
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
		public function getUsuarioCreador(){
			return $this->_usuarioCreador;
		}
		public function setUsuarioCreador(EntidadUsuario $usuarioCreador){
			$this->_usuarioCreador = $usuarioCreador;
		}
		public function getUsuarioUltimaModificacion(){
			return $this->_usuarioUltimaModificacion;
		}
		public function setUsuarioUltimaModificacion(EntidadUsuario $usuarioUltimaModificacion){
			$this->_usuarioUltimaModificacion = $usuarioUltimaModificacion;
		}
	}
	
?>