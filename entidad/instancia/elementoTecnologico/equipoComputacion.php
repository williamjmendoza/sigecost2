<?php

	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/elementoTecnologico.php' );

	class EntidadInstanciaETEquipoComputacion extends EntidadInstanciaElementoTecnologico
	{
		private $_marca;
		private $_modelo;

		public function getMarca(){
			return $this->_marca;
		}
		public function setMarca($marca){
			$this->_marca = $marca;
		}
		public function getModelo(){
			return $this->_modelo;
		}
		public function setModelo($modelo){
			$this->_modelo = $modelo;
		}
	}

?>