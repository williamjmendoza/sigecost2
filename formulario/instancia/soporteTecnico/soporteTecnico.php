<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/soporteTecnico.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');
	
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