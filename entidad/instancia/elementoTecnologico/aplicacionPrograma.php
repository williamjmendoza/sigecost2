<?php
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class EntidadInstanciaETAplicacionPrograma extends EntidadInstanciaElementoTecnologico
	{
		protected $_nombre;
		protected $_version;
		
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