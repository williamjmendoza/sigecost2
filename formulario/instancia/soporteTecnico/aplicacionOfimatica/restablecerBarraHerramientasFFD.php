<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/restablecerBarraHerramientasFFD.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php');

	class FormularioInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD extends FormularioInstanciaSTAplicacionOfimatica
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTAplicacionOfimaticaRestablecerBarraHerramientasFFD());
			$this->getSoporteTecnico()->setPatron(new EntidadPatron());
			$this->getSoporteTecnico()->setAplicacionPrograma(new EntidadInstanciaETAplicacionOfimatica());
		}
	}
?>