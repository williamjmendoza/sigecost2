<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/instancia.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/patron.php' );

	class EntidadInstanciaSoporteTecnico extends EntidadInstancia
	{
		private $_patron;
		private $_urlSoporteTecnico;
		
		public function getPatron(){
			return $this->_patron;
		}
		public function setPatron(EntidadPatron $patron){
			$this->_patron = $patron;
		}
		public function getUrlSoporteTecnico(){
			return $this->_urlSoporteTecnico;
		}
		public function setUrlSoporteTecnico($urlSoporteTecnico){
			$this->_urlSoporteTecnico = $urlSoporteTecnico;
		}
	}
?>