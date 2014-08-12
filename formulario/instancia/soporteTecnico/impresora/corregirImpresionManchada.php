<?php

	// Entidades
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/elementoTecnologico/impresora.php');
	require_once ( SIGECOST_ENTIDAD_PATH . '/instancia/soporteTecnico/impresora/corregirImpresionManchada.php');

	// Formularios
	require_once ( SIGECOST_FORMULARIO_PATH . '/instancia/soporteTecnico/impresora/impresora.php');

	class FormularioInstanciaSTImpresoraCorregirImpresionManchada extends FormularioInstanciaSTImpresora
	{
		public function __construct()
		{
			$this->setSoporteTecnico(new EntidadInstanciaSTImpresoraCorregirImpresionManchada());
			$this->getSoporteTecnico()->setEquipoReproduccion(new EntidadInstanciaETImpresora());
		}
	}
?>