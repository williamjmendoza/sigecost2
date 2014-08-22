<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/barraFormato.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/barraHerramientas.php');

	class FormularioInstanciaETBarraFormato extends FormularioInstanciaETBarraHerramientas
	{
		public function __construct(){
			$this->setBarraHerramientas(new EntidadInstanciaETBarraFormato());
		}
	}
?>