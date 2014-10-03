<?php

	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );

	class Modeloarchivo
	{
		public static function exportarOntologiaAOwl()
		{
			$preMsg = "Error al intentar exportar la ontología a owl.";
			
			try
			{
				$query = '
					CONSTRUCT   { ?subject ?property ?object }
					WHERE       { ?subject ?property ?object }
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception($preMsg . " Detalles:\n" . join("\n", $errors));
				
				$config = array(
					'serializer_default_ns' => SIGECOST_IRI_ONTOLOGIA_NUMERAL,
					'base' => SIGECOST_IRI_ONTOLOGIA
				);
				
				$graph = ARC2::getGraph($config);
				
				$graph->setPrefix('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
				$graph->setPrefix('protege', 'http://protege.stanford.edu/plugins/owl/protege#');
				$graph->setPrefix('xsp', 'http://www.owl-ontologies.com/2005/08/07/xsp.owl#');
				$graph->setPrefix('owl', 'http://www.w3.org/2002/07/owl#');
				$graph->setPrefix('xsd', 'http://www.w3.org/2001/XMLSchema#');
				$graph->setPrefix('swrl', 'http://www.w3.org/2003/11/swrl#');
				$graph->setPrefix('swrlb', 'http://www.w3.org/2003/11/swrlb#');
				$graph->setPrefix('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
				$graph->setPrefix('base', 'http://www.owl-ontologies.com/OntologySoporteTecnico.owl#');
				
				$graph->setIndex($result["result"]);
				
				return $graph->getRDFXML();
				
			} catch (Exception $e) {
				error_log($e, 0);
				return false;
			}
		}
	}
	
?>