<?php

	class ModeloClaseSoporteTecnico
	{
		// Función recursiva de Anibal para construir e árbol
		public static function consultar_clases($clase,$store)
		{
		
		
			$q ='
				PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX kb: <http://protege.stanford.edu/kb#>
				SELECT ?subclase ?label
				WHERE { 	?subclase rdfs:subClassOf kb:' . $clase . ' .
							?subclase rdfs:label ?label .
				}
			';
		
			$rows = $store->query($q, 'rows');
			$r = '';
			$h = '';
			if ($rows = $store->query($q, 'rows')) {
		
				$params = array();
		
				foreach ($rows as $row) {
		
					$x = consultar_clases($row['label'],$store);
					$params[$row['label']] = $x != false?  $x : array();
		
		
		
				}
		
				return $params;
			}
		
			else{
				return false;
			}
		
		}
	}
?>