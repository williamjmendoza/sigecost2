<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/paginacion.php' );

	trait FormularioTraitPaginacion
	{
		private $_paginacion;
		
		public function getPaginacion(){
			return $this->_paginacion;
		}
		public function setPaginacion(EntidadPaginacion $paginacion){
			$this->_paginacion = $paginacion;
		}
	}
?>