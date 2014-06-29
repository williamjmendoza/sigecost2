<?php

	/**
	 * Obtiene una variable de configuración almacenada desde el archivo de configuración
	 *
	 * @param string El nombre de la variable a obtener.
	 * @return mixed El valor de la variable.
	 */
	function GetConfig($config)
	{
		if (array_key_exists($config, $GLOBALS['SIGECOST_CFG'])) {
			return $GLOBALS['SIGECOST_CFG'][$config];
		}
		return '';
	}

?>