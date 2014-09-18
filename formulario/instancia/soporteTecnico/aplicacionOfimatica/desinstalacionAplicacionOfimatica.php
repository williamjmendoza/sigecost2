<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionOfimatica.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/desinstalacionAplicacionOfimatica.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php');

	class FormularioInstanciaSTAplicacionOfimaticaDesinstalacionAplicacionOfimatica extends FormularioInstanciaSTAplicacionOfimatica
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTAplicacionOfimaticaDesinstalacionAplicacionOfimatica());
			$this->getSoporteTecnico()->setPatron(new EntidadPatron());
			$this->getSoporteTecnico()->setAplicacionPrograma(new EntidadInstanciaETAplicacionOfimatica());
			$this->getSoporteTecnico()->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
	}
?>