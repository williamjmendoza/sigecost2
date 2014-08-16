<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionProduccionAudiovisualMusica.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/aplicacionPrograma.php');

	class FormularioInstanciaETAplicacionProduccionAudiovisualMusica extends FormularioInstanciaETAplicacionPrograma
	{
		public function __construct(){
			$this->setAplicacionPrograma(new EntidadInstanciaETAplicacionProduccionAudiovisualMusica());
		}
	}
?>