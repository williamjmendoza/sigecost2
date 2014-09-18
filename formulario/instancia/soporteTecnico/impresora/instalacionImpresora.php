<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/impresora/instalacionImpresora.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/impresora/impresora.php');

	class FormularioInstanciaSTImpresoraInstalacionImpresora extends FormularioInstanciaSTImpresora
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTImpresoraInstalacionImpresora());
			$this->getSoporteTecnico()->setPatron(new EntidadPatron());
			$this->getSoporteTecnico()->setEquipoReproduccion(new EntidadInstanciaETImpresora());
			$this->getSoporteTecnico()->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
	}
?>