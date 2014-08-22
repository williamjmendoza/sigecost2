<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/equipoComputacion.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');

	class FormularioInstanciaETEquipoComputacion extends Formulario
	{
		protected $_equipoComputacion;

		public function getEquipoComputacion(){
			return $this->_equipoComputacion;
		}

		public function setEquipoComputacion(EntidadInstanciaETEquipoComputacion $equipoComputacion){
			$this->_equipoComputacion = $equipoComputacion;
		}
	}
?>