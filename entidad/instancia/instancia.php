<?php
	class EntidadInstancia
	{
		private $_iri;
		private $_label;
		
		public function getIri(){
			return $this->_iri;
		}
		public function setIri($iri){
			$this->_iri = $iri;
		}
		public function getLabel(){
			return $this->_label;
		}
		public function setLabel($label){
			$this->_label = $label;
		}	
	}
?>