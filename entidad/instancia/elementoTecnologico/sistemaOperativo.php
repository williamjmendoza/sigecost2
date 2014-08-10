<?php

	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class EntidadInstanciaETSistemaOperativo extends EntidadInstanciaElementoTecnologico
	{
		private $_nombre;
		private $_version;
		
		public function getNombre(){
			return $this->_nombre;
		}
		public function setNombre($nombre){
			$this->_nombre = $nombre;
		}
		public function getVersion(){
			return $this->_version;
		}
		public function setVersion($version){
			$this->_version = $version;
		}
	}
?>