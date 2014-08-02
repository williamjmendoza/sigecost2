<?php
	class EntidadInstancia
	{
		private $_iRI;
		private $_label;
		
		public function getIRI(){
			return $this->_iRI;
		}
		public function setIRI($iRI){
			$this->_iRI = $iRI;
		}
		public function getLabel(){
			return $this->_label;
		}
		public function setLabel($label){
			$this->_label = $label;
		}	
	}
?>