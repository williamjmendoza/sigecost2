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
		
		public function __construct(){
			$this->setFechaCreacion(date("d/m/Y"));
			$this->setFechaUltimaModificacion(date("d/m/Y"));
		}
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
		public function getSolucionTruncada($truncamiento = null){
			//$strSolucion = trim(html_entity_decode(strip_tags($this->_solucion)), " \t\n\r\0\x0B\xC2\xA0");
			$strSolucion = html_entity_decode(strip_tags($this->_solucion));
			
			$strSolucion = trim(str_replace(array("\r\n", "\n", "\r", "\t", "\0", "\x0B", "\xC2\xA0", "\xA0", "\xC2"), " ", $strSolucion));
			
			$strSolucion = preg_replace('/\s\s+/', ' ', $strSolucion);
			
			if($truncamiento != null && $truncamiento > 0 && strlen($strSolucion) > $truncamiento)
			{
				$strSolucion = substr($strSolucion, 0, $truncamiento - 3) . '...';
			}
			
			return htmlentities($strSolucion);
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