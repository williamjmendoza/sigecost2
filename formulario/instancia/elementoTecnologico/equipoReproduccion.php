<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/equipoReproduccion.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');

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