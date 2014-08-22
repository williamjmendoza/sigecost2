<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/computadorEscritorio.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/equipoComputacion.php');

	class FormularioInstanciaETComputadorEscritorio extends FormularioInstanciaETEquipoComputacion
	{
		public function __construct(){
			$this->setEquipoComputacion(new EntidadInstanciaETComputadorEscritorio());
		}
	}
?>