<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php');
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/sistemaOperativo.php');
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/impresora/instalacionImpresora.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/instancia/soporteTecnico/impresora/impresora.php');

	class FormularioInstanciaSTImpresoraInstalacionImpresora extends FormularioInstanciaSTImpresora
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTImpresoraInstalacionImpresora());
			$this->getSoporteTecnico()->setEquipoReproduccion(new EntidadInstanciaETImpresora());
			$this->getSoporteTecnico()->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
	}
?>