<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionGraficaDigitalDibujoDiseno.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionGDDD/instalacionAplicacionGDDD.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/aplicacionGDDD/aplicacionGDDD.php');

	class FormularioInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD extends FormularioInstanciaSTAplicacionGDDD
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTAplicacionGDDDInstalacionAplicacionGDDD());
			$this->getSoporteTecnico()->setAplicacionPrograma(new EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno());
			$this->getSoporteTecnico()->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
	}
?>