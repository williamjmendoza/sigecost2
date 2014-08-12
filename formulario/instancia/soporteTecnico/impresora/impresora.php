<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php');
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/impresora/impresora.php');

	// Formulario
	require_once ( SIGECOST_FORMULARIO_PATH . '/instancia/soporteTecnico/equipoReproduccion.php');

	class FormularioInstanciaSTImpresora extends FormularioInstanciaSTEquipoReproduccion
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTImpresora());
			$this->getSoporteTecnico()->setEquipoReproduccion(new EntidadInstanciaETImpresora());
		}
	}
?>