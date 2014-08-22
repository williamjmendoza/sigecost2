<?php

	trait ControladorTraitPaginacion
	{
		// Obtener y validar los parámetros de la paginación
		protected function __validarParametrosPaginacion($form)
		{
			$this->__validarPaginaActual($form);
			$this->__validarTamanoPagina($form);
		}
		
		// Obtener y validar la página actual
		protected function __validarPaginaActual($form)
		{
			if(!isset($_REQUEST['pag']) || ($paginaActual=trim($_REQUEST['pag'])) == '')
			{
				$form->getPaginacion()->setPaginaActual(1);
			} else {
				$form->getPaginacion()->setPaginaActual(intval($paginaActual, 10));
			}
		}
		
		// Obtener y validar el tamaño de la página
		protected function __validarTamanoPagina($form)
		{
			if(!isset($_REQUEST['tam']) || ($tamanoPagina=trim($_REQUEST['tam'])) == '')
			{
				$form->getPaginacion()->setTamanoPagina(GetConfig("tamanoPaginaPorDefecto"));
			} else {
				$form->getPaginacion()->setTamanoPagina(intval($tamanoPagina, 10));
			}
		}
	}
?>