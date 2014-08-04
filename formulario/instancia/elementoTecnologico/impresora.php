<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/instancia/elementoTecnologico/equipoReproduccion.php');

	class FormularioInstanciaETImpresora extends FormularioInstanciaETEquipoReproduccion
	{
		public function __construct(){
			$this->setEquipoReproduccion(new EntidadInstanciaETImpresora());
		}
	}
?>