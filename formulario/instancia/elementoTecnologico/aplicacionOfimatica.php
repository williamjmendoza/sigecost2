<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/aplicacionPrograma.php');

	class FormularioInstanciaETAplicacionOfimatica extends FormularioInstanciaETAplicacionPrograma
	{
		public function __construct(){
			$this->setAplicacionPrograma(new EntidadInstanciaETAplicacionOfimatica());
		}
	}
?>