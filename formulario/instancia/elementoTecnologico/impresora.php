<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/formulario.php');

	class FormularioETImpresora extends Formulario
	{
		private $_impresora;
		
		public function __construct(){
			$this->_impresora = new EntidadInstanciaETImpresora();
		}
		
		public function getImpresora(){
			return $this->_impresora;
		}
		
		public function setImpresora(EntidadInstanciaETImpresora $impresora){
			$this->_impresora = $impresora;
		}
	}
?>