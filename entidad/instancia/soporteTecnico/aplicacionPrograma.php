<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionPrograma.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/soporteTecnico.php' );
	
	class EntidadInstanciaSTAplicacionPrograma extends EntidadInstanciaSoporteTecnico
	{
		protected $_aplicacionPrograma;
		
		public function getAplicacionPrograma(){
			return $this->_aplicacionPrograma;
		}
		public function setAplicacionPrograma(EntidadInstanciaETAplicacionPrograma $aplicacionPrograma){
			$this->_aplicacionPrograma = $aplicacionPrograma;
		}
	}
	
?>