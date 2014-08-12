<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/soporteTecnico.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/formulario.php');
	
	class FormularioInstanciaSoporteTecnico extends Formulario
	{
		protected $_soporteTecnico;
		
		public function getSoporteTecnico(){
			return $this->_soporteTecnico;
		}
		public function setSoporteTecnico(EntidadInstanciaSoporteTecnico $soporteTecnico){
			$this->_soporteTecnico = $soporteTecnico;
		}
	}
	
?>