<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/sistemaOperativo.php');

	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/formulario.php');

	class FormularioInstanciaETSistemaOperativo extends Formulario
	{
		private $_sistemaOperativo;
		
		public function __construct()
		{
			$this->setSistemaOperativo(new EntidadInstanciaETSistemaOperativo());
		}
		public function getSistemaOperativo(){
			return $this->_sistemaOperativo;
		}
		public function setSistemaOperativo(EntidadInstanciaETSistemaOperativo $sistemaOperativo){
			$this->_sistemaOperativo = $sistemaOperativo;
		}
	}
?>