<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/aplicacionPrograma.php');

	class FormularioInstanciaETAplicacionGraficaDigitalDibujoDiseno extends FormularioInstanciaETAplicacionPrograma
	{
		public function __construct(){
			$this->setAplicacionPrograma(new EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno());
		}
	}
?>