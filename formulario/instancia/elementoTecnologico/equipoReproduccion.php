<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/equipoReproduccion.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/formulario.php');

	class FormularioInstanciaETEquipoReproduccion extends Formulario
	{
		protected $_equipoReproduccion;
		
		public function getEquipoReproduccion(){
			return $this->_equipoReproduccion;
		}
		
		public function setEquipoReproduccion(EntidadInstanciaETEquipoReproduccion $equipoReproduccion){
			$this->_equipoReproduccion = $equipoReproduccion;
		}
	}
?>