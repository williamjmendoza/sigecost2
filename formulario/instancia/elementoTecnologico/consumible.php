<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/consumible.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');

	class FormularioInstanciaETConsumible extends Formulario
	{
		private $_consumible;
		
		public function __construct()
		{
			$this->setConsumible(new EntidadInstanciaETConsumible());
		}
		public function getConsumible(){
			return $this->_consumible;
		}
		public function setConsumible(EntidadInstanciaETConsumible $consumible){
			$this->_consumible = $consumible;
		}
	}
?>