<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/equipoReproduccion.php');

	class FormularioInstanciaETImpresora extends FormularioInstanciaETEquipoReproduccion
	{
		public function __construct(){
			$this->setEquipoReproduccion(new EntidadInstanciaETImpresora());
		}
	}
?>