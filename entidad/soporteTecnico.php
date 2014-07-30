<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/entidad.php' );

	class EntidadSoporteTecnico extends Entidad
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