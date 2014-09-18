<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionGDDD/desinstalacionAplicacion.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/aplicacionGDDD/aplicacionGDDD.php');

	class FormularioInstanciaSTAplicacionGDDDDesinstalacionAplicacion extends FormularioInstanciaSTAplicacionGDDD
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTAplicacionGDDDDesinstalacionAplicacion());
			$this->getSoporteTecnico()->setPatron(new EntidadPatron());
			$this->getSoporteTecnico()->setAplicacionPrograma(new EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno());
			$this->getSoporteTecnico()->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
	}
?>