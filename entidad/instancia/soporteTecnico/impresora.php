<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/soporteTecnico.php' );	
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php' );

	class EntidadSTImpresora extends EntidadSoporteTecnico
	{
		private $_impresora;
		
		public function getImpresora(){
			return $this->_impresora;
		}
		public function setImpresora(EntidadETImpresora $impresora){
			$this->_impresora = $impresora;
		}
		
	}
?>