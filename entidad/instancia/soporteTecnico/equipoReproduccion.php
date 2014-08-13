<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/equipoReproduccion.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/soporteTecnico.php' );
	
	class EntidadInstanciaSTEquipoReproduccion extends EntidadInstanciaSoporteTecnico
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