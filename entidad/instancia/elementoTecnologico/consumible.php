<?php

	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class EntidadInstanciaETConsumible extends EntidadInstanciaElementoTecnologico
	{
		private $_especificacion;
		private $_tipo;
		
		public function getEspecificacion(){
			return $this->_especificacion;
		}
		public function setEspecificacion($especificacion){
			$this->_especificacion = $especificacion;
		}
		public function getTipo(){
			return $this->_tipo;
		}
		public function setTipo($tipo){
			$this->_tipo = $tipo;
		}
	}
?>