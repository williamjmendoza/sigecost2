<?php

	trait ControladorTraitPaginacion
	{
		// Obtener y validar la página de la entidad paginación
		protected function __validarPagina($form)
		{
			if(!isset($_REQUEST['pag']) || ($pagina=trim($_REQUEST['pag'])) == '')
			{
				$form->getPaginacion()->setPagina(1);
			} else {
				$form->getPaginacion()->setPagina(intval($pagina, 10));
			}
		}
	}
?>