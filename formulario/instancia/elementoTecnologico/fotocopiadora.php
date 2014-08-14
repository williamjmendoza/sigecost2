<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/fotocopiadora.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/equipoReproduccion.php');

	class FormularioInstanciaETFotocopiadora extends FormularioInstanciaETEquipoReproduccion
	{
		public function __construct(){
			$this->setEquipoReproduccion(new EntidadInstanciaETFotocopiadora());
		}
	}
?>