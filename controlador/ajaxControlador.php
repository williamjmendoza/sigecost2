<?php

	class AjaxControlador
	{
		function __construct()
		{
			if(isset($_REQUEST['accion'])){
				if(method_exists($this, $_REQUEST['accion'])){
					$this->$_REQUEST['accion']();
				} else {
					//echo "Accion no definida";
				}
			} else {
				//echo "Sin parametro de accion";
			}
		}
	}
?>