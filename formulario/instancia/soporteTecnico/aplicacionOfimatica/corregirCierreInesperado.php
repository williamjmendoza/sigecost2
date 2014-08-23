<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php');

	class FormularioInstanciaSTAplicacionOfimaticaCorregirCierreInesperado extends FormularioInstanciaSTAplicacionOfimatica
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTAplicacionOfimaticaCorregirCierreInesperado());
			$this->getSoporteTecnico()->setAplicacionPrograma(new EntidadInstanciaETAplicacionOfimatica());
		}
	}
?>