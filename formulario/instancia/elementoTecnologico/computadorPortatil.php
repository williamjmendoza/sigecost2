<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/computadorPortatil.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/equipoComputacion.php');

	class FormularioInstanciaETComputadorPortatil extends FormularioInstanciaETEquipoComputacion
	{
		public function __construct(){
			$this->setEquipoComputacion(new EntidadInstanciaETComputadorPortatil());
		}
	}
?>