<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/paginacion.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');
	require_once ( SIGECOST_PATH_FORMULARIO . '/paginacion.php');
	
	class FormularioInstanciaSTImpresoraInstalacionImpresoraBuscar extends Formulario
	{
		use FormularioTraitPaginacion;
		
		public function __construct()
		{
			$this->setPaginacion(new EntidadPaginacion());
		}
	}
?>