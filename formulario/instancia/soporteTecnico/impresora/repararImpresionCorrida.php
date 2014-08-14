<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/impresora.php');
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/impresora/repararImpresionCorrida.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/impresora/impresora.php');

	class FormularioInstanciaSTImpresoraRepararImpresionCorrida extends FormularioInstanciaSTImpresora
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTImpresoraRepararImpresionCorrida());
			$this->getSoporteTecnico()->setEquipoReproduccion(new EntidadInstanciaETImpresora());
		}
	}
?>