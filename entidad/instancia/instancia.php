<?php
	class EntidadInstancia
	{
		private $_iri;
		
		public function getIri(){
			return $this->_iri;
		}
		public function setIri($iri){
			$this->_iri = $iri;
		}
	}
?>