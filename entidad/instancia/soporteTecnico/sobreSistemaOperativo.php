<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php' );

	trait EntidadTraitInstanciaSTSobreSistemaOperativo
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