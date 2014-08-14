<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionPrograma.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');

	class FormularioInstanciaETAplicacionPrograma extends Formulario
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