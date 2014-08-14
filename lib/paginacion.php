<?php
	class LibPaginacion
	{
		private $_desplazamiento;
		private $_limite;
		private $_pagina = null;
		private $_totalElementos;
		
		public function __construct($limite, $totalElementos)
		{
			$this->setTotalElementos($totalElementos);
			//$this->setDesplazamiento(($pagina - 1) * $limite);
			$this->setLimite($limite);
		}
		
		// Getters & setters
		
		public function getDesplazamiento(){
			return $this->_desplazamiento;
		}
		private function setDesplazamiento($desplazamiento){
			$this->_desplazamiento = $desplazamiento;
		}
		public function getLimite(){
			return $this->_limite;
		}
		private function setLimite($limite){
			$this->_limite = $limite;
		}
		public function getPagina(){
			return $this->_pagina;
		}
		private function setPagina($pagina){
			$this->_pagina = $pagina;
			$this->setDesplazamiento(($pagina - 1) * $this->getLimite());
		}
		public function getTotalElementos(){
			return $this->_totalElementos;
		}
		private function setTotalElementos($totalElementos){
			$this->_totalElementos = $totalElementos;
		}
		
		// Otras funciones miembros 
		
		public function escribirParametrosUrl()
		{
			
		}
		public function escribirParametrosFormulario()
		{
			
		}
		public function leerParametrosRequest()
		{
			if( isset($_REQUEST['pag']) && ($pagina=$_REQUEST['pag']) != '' )
			{
				$this->setPagina($pagina);
			} else {
				$this->setPagina(1);
			}
		}
	}
?>