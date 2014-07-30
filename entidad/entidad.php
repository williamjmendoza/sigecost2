<?php
	class Entidad
	{
		private $_comments = array();
		private $_iRI;
		private $_label;
		
		public function getComments(){
			return $this->_comments;
		}
		public function setComments(array $comments){
			$this->_comments = $comments;
		}
		public function setComment($comment){
			$this->_comments[] = $comment;
		}
		public function clearComments(){
			$this->_comments = array();
		}
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