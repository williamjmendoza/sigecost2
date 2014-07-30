<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/soporteTecnico.php' );	
	require_once ( SIGECOST_ENTIDAD_PATH . '/eTEquipoReproduccion.php' );

	class EntidadSTImpresora extends EntidadSoporteTecnico
	{
		private $_impresora;
		
		public function getImpresora(){
			return $this->_impresora;
		}
		public function setImpresora(EntidadETEquipoReproduccion $impresora){
			$this->_impresora = $impresora;
		}
		
	}
?>