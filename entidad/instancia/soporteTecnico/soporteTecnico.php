<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/instancia.php' );

	class EntidadInstanciaSoporteTecnico extends EntidadInstancia
	{
		private $_urlSoporteTecnico;
		
		public function getUrlSoporteTecnico(){
			return $this->_urlSoporteTecnico;
		}
		public function setUrlSoporteTecnico($urlSoporteTecnico){
			$this->_urlSoporteTecnico = $urlSoporteTecnico;
		}
	}
?>