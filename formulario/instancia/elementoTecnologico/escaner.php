<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/escaner.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/instancia/elementoTecnologico/equipoReproduccion.php');

	class FormularioInstanciaETEscaner extends FormularioInstanciaETEquipoReproduccion
	{
		public function __construct(){
			$this->setEquipoReproduccion(new EntidadInstanciaETEscaner());
		}
	}
?>