<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/escaner.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/equipoReproduccion.php');

	class FormularioInstanciaETEscaner extends FormularioInstanciaETEquipoReproduccion
	{
		public function __construct(){
			$this->setEquipoReproduccion(new EntidadInstanciaETEscaner());
		}
	}
?>