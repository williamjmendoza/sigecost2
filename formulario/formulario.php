<?php

	include_once( SIGECOST_PATH_FORMULARIO . '/config.php');  // incluye el config del directorio de formularios

	class Formulario
	{
		const TIPO_OPERACION_INSERTAR = 1;
		const TIPO_OPERACION_MODIFICAR = 2;
		
		private $_tipoOperacion = self::TIPO_OPERACION_INSERTAR;
		
		public function getTipoOperacion(){
			return $this->_tipoOperacion;
		}
		public function setTipoOperacion($tipoOperacion){
			$this->_tipoOperacion = $tipoOperacion;
		}
	}
?>