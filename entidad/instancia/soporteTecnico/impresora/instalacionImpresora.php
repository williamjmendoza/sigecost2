<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/sistemaOperativo.php' );
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/impresora/impresora.php' );	

	class EntidadInstanciaSTImpresoraInstalacionImpresora extends EntidadInstanciaSTImpresora
	{
		private $_sistemaOperativo;
		
		public function getSistemaOperativo(){
			return $this->_sistemaOperativo;
		}
		public function setSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo){
			$this->_sistemaOperativo = $sistemaOperativo;
		}
	}
?>