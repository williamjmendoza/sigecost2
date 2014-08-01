<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/instancia.php' );

	class EntidadSoporteTecnico extends EntidadInstancia
	{
		private $_uRLSoporteTecnico;
		
		public function getURLSoporteTecnico(){
			return $this->_uRLSoporteTecnico;
		}
		public function setURLSoporteTecnico($uRLSoporteTecnico){
			$this->_uRLSoporteTecnico = $uRLSoporteTecnico;
		}
	}
?>