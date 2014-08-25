<?php
	class EntidadPaginacion
	{
		private $_paginaActual = 0;
		private $_tamanoPagina = 0;
		private $_tamanoVentana = 0;
		private $_totalElementos = 0;
		private $_urlObjetivo = "#";
		
		public function __construct($totalElementos)
		{
			$this->setTamanoVentana(GetConfig("tamanoVentana"));
			$this->setTotalElementos($totalElementos);
		}
		
		// Getters & setters
		public function getDesplazamiento(){
			return (($this->getPaginaActual() - 1) * $this->getTamanoPagina());
		}
		public function getPaginaActual(){
			return $this->_paginaActual;
		}
		public function setPaginaActual($paginaActual){
			$this->_paginaActual = $paginaActual;
		}
		public function getTamanoPagina(){
			return $this->_tamanoPagina;
		}
		public function setTamanoPagina($tamanoPagina){
			$this->_tamanoPagina = $tamanoPagina;
		}
		public function getTamanoVentana(){
			return $this->_tamanoVentana;
		}
		public function setTamanoVentana($tamanoVentana){
			$this->_tamanoVentana = $tamanoVentana;
		}
		public function getTotalElementos(){
			return $this->_totalElementos;
		}
		private function setTotalElementos($totalElementos){
			$this->_totalElementos = $totalElementos;
		}
		public function getTotalPaginas(){
			return intval(ceil($this->getTotalElementos() / $this->getTamanoPagina()));
		}
		public function getUrlObjetivo(){
			return $this->_urlObjetivo;
		}
		public function setUrlObjetivo($urlObjetivo){
			$this->_urlObjetivo = $urlObjetivo;
		}
	}
?>