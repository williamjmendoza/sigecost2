<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionReproduccionSonidoVideo.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/aplicacionPrograma.php');

	class FormularioInstanciaETAplicacionReproduccionSonidoVideo extends FormularioInstanciaETAplicacionPrograma
	{
		public function __construct(){
			$this->setAplicacionPrograma(new EntidadInstanciaETAplicacionReproduccionSonidoVideo());
		}
	}
?>