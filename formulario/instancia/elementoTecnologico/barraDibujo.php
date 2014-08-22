<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/barraDibujo.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/elementoTecnologico/barraHerramientas.php');

	class FormularioInstanciaETBarraDibujo extends FormularioInstanciaETBarraHerramientas
	{
		public function __construct(){
			$this->setBarraHerramientas(new EntidadInstanciaETBarraDibujo());
		}
	}
?>