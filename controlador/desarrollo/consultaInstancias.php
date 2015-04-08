<?php
	require_once ( dirname(__FILE__) . '/../../init.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/desarrollo/queryScreen.php' );
	
	class ConsultaInstancias extends QueryScreen
	{
		public function getInstance()
		{
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				SELECT
					?subject ?property ?object
				WHERE
				{
					{ kb:ontologiasoportetecnicov1_Class46 ?property ?object  }
					UNION
					{ ?subject ?property kb:ontologiasoportetecnicov1_Class46 }
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class46 .
						?subject ?property ?object
						
					}
					UNION
					{ kb:ontologiasoportetecnicov1_Class47 ?property ?object  }
					UNION
					{ ?subject ?property kb:ontologiasoportetecnicov1_Class47 }
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class47 .
						?subject ?property ?object
						
					}
				}
			';
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				SELECT
					?subject ?property ?object
				WHERE
				{
					
					{ kb:ontologiasoportetecnicov1_Class46 ?property ?object  }
					UNION
					{ ?subject ?property kb:ontologiasoportetecnicov1_Class46 }
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class46 .
						?subject ?property ?object
			
					}
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class47 .
						?subject ?property ?object
			
					}
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class48 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class49 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class50 .
						?subject ?property ?object
			
					}
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class51 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class52 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class53 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class54 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class55 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class56 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class57 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class58 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class59 .
						?subject ?property ?object
			
					}
							UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class60 .
						?subject ?property ?object
			
					}
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class61 .
						?subject ?property ?object
			
					}
					UNION
					{
						?subject ?property2 kb:ontologiasoportetecnicov1_Class62 .
						?subject ?property ?object
			
					}
										
				}
			';
			
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?subject ?property ?object
				WHERE
				{
					{
						_:consumible rdf:type kb:Consumible .
						?subject ?dshhd _:consumible .
						?subject ?property ?object
					}
					
					#UNION
					#{
					#	_:consumible2 rdf:type kb:Consumible .
					#	?object ?dshhd _:consumible2 .
					#	?subject owl:distinctMembers ?object
					#	
					#}
					
				}
					
			';
			
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?subject ?property ?object
				WHERE
				{
					
					_:instancia rdf:type kb:AplicacionOfimatica .
					?subject ?dshhd _:instancia .
					?subject ?property ?object .
					
				#	FILTER (
				#		?property = rdf:first
				#		|| ?property = rdf:rest
				#	)
				}
					
				#ORDER BY
				#	?subject
			
			';
			

			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?nodo1 ?nodo2 ?instancia1 ?Clase1
				WHERE
				{
					?nodo1 rdf:type owl:AllDifferent .
					?nodo1 owl:distinctMembers ?nodo2 .
					?nodo2 rdf:first ?instancia1 .
					?instancia1 rdf:type ?Clase1 .
				}
			';


			
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?instancia
				WHERE
				{
					?instancia rdf:type :AplicacionOfimatica .
					?algo1 ?algo2 (?instancia)
				}
			';
			
			
			echo "Instancias Mienbros de AplicacionOfimatica";
			
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?nodo ?instancia
				WHERE
				{
					{
						?instancia rdf:type :AplicacionOfimatica .
						?nodo ?property ?instancia .
						FILTER (
							?property = owl:distinctMembers
							|| ?property = rdf:first
							|| ?property = rdf:rest
						) .
					}
				}
			';
			
			
			
			
			/*
			_:b4282073036_rcf27db198 	http://www.w3.org/1999/02/22-rdf-syntax-ns#type 	http://www.w3.org/2002/07/owl#AllDifferent
			_:b4282073036_rcf27db198 	http://www.w3.org/2002/07/owl#distinctMembers 	_:b2285637466_rcf27db199
			_:b2285637466_rcf27db199 	http://www.w3.org/1999/02/22-rdf-syntax-ns#first 	http://www.owl-ontologies.com/OntologySoporteTecnico.owl#ontologiasoportetecnicov1_Class30043
			_:b2285637466_rcf27db199 	http://www.w3.org/1999/02/22-rdf-syntax-ns#rest 	_:b577024750_rcf27db200
			 * 
			 * */
			
			
			$this->execute($query);
		}
		
		public function getInstanciaImpresora()
		{
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					
				SELECT
					?subject
				WHERE
				{
					?subject rdf:type kb:Impresora
				}
			';
			
			$this->execute($query);
			
			echo "<br> <br>";
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
			
				SELECT
					?subject ?property ?object
				WHERE
				{
					{ kb:ontologiasoportetecnicov1_Class37 ?property ?object }
					UNION
					{ ?subject ?property kb:ontologiasoportetecnicov1_Class37 }
				}
			';
			
			$this->execute($query);
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
		
				DESCRIBE kb:ontologiasoportetecnicov1_Class37
			';
				
			$this->execute($query);
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
			
				DESCRIBE kb:Impresora_numeroConsecutivo
			';
			
			$this->execute($query);
			
			$query = '
				PREFIX kb: <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX fn: <http://www.w3.org/2005/xpath-functions#>
				PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				PREFIX mysql: <http://web-semantics.org/ns/mysql/>
					
				SELECT
					?subject
				WHERE
				{
					{
						?subject rdf:type kb:Impresora .
						#?subject ?property ?object .
						#?subject kb:marcaEquipoReproduccion ?marca .
						#?subject kb:modeloEquipoReproduccion ?modelo .
						
						#FILTER regex(str(?subject), kb:Impresora_)
						#FILTER regex(xsd:string(fn:substring(?marca, 1)), "Laserjet P11")
						#FILTER (mysql:concat(?marca, " ", ?modelo) = "HP Laserjet P1102W") .
						#FILTER regex(mysql:substring(?subject, (mysql:length(kb:) + 1), (mysql:length(?subject) - mysql:length(kb:)) ), "Impresora_")
						FILTER regex(mysql:substring(?subject, (mysql:length(kb:) + 1), (mysql:length(?subject) - mysql:length(kb:)) ),
							"ontologiasoportetecnicov1_Class")
					}
				}
				ORDER BY
					DESC(mysql:substring(
						?subject,
						( mysql:length(kb:) + 1 + mysql:length("ontologiasoportetecnicov1_Class") ),
						( mysql:length(?subject) - mysql:length(kb:) - mysql:length("ontologiasoportetecnicov1_Class") )
					))
				LIMIT 1
			';
			
			$this->execute($query);
		}
		
		public function getInstanciaSO()
		{
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					
				SELECT
					?subject
				WHERE
				{
					?subject rdf:type :SistemaOperativo
				}
			';
			
			$this->execute($query);
			
			echo "<br> <br>";
			
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
			
				SELECT
					?subject ?property ?object
				WHERE
				{
					{ :ontologiasoportetecnicov1_Class9 ?property ?object }
					UNION
					{ ?subject ?property :ontologiasoportetecnicov1_Class9 }
				}
			';
			
			$this->execute($query);
			
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
			
				DESCRIBE :ontologiasoportetecnicov1_Class9
			';
			
			$this->execute($query);
				
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX fn: <http://www.w3.org/2005/xpath-functions#>
				PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				PREFIX mysql: <http://web-semantics.org/ns/mysql/>
			
				SELECT
					?subject
				WHERE
				{
					{
						?subject rdf:type :Impresora .
						#?subject ?property ?object .
						#?subject kb:marcaEquipoReproduccion ?marca .
						#?subject kb:modeloEquipoReproduccion ?modelo .
			
						#FILTER regex(str(?subject), kb:Impresora_)
						#FILTER regex(xsd:string(fn:substring(?marca, 1)), "Laserjet P11")
						#FILTER (mysql:concat(?marca, " ", ?modelo) = "HP Laserjet P1102W") .
						#FILTER regex(mysql:substring(?subject, (mysql:length(kb:) + 1), (mysql:length(?subject) - mysql:length(kb:)) ), "Impresora_")
						FILTER regex(mysql:substring(?subject, (mysql:length(:) + 1), (mysql:length(?subject) - mysql:length(:)) ),
							"ontologiasoportetecnicov1_Class")
					}
				}
				ORDER BY
					DESC(mysql:substring(
						?subject,
						( mysql:length(:) + 1 + mysql:length("ontologiasoportetecnicov1_Class") ),
						( mysql:length(?subject) - mysql:length(:) - mysql:length("ontologiasoportetecnicov1_Class") )
					))
				LIMIT 1
			';
				
			$this->execute($query);
		}
		
		public function getMiembrosColeccion()
		{
			//$miembrosclase = 'AplicacionOfimatica';
			//$miembrosclase = 'DesinstalacionAplicacionGraficaDigitalDibujoDiseno';
			$miembrosclase = 'Escaner';
			
			echo "Instancias Miembros de ".$miembrosclase."<br><br>";
				
			$query = '
				PREFIX : <http://www.owl-ontologies.com/OntologySoporteTecnico.owl#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX owl: <http://www.w3.org/2002/07/owl#>
				SELECT
					?nodo ?instancia
				WHERE
				{
					{
						?instancia rdf:type :'.$miembrosclase.' .
						?nodo ?property ?instancia .
						FILTER (
							?property = owl:distinctMembers
							|| ?property = rdf:first
							|| ?property = rdf:rest
						) .
					}
				}
			';
			
			/*
			_:b4282073036_rcf27db198 	http://www.w3.org/1999/02/22-rdf-syntax-ns#type 	http://www.w3.org/2002/07/owl#AllDifferent
			_:b4282073036_rcf27db198 	http://www.w3.org/2002/07/owl#distinctMembers 	_:b2285637466_rcf27db199
			_:b2285637466_rcf27db199 	http://www.w3.org/1999/02/22-rdf-syntax-ns#first 	http://www.owl-ontologies.com/OntologySoporteTecnico.owl#ontologiasoportetecnicov1_Class30043
			_:b2285637466_rcf27db199 	http://www.w3.org/1999/02/22-rdf-syntax-ns#rest 	_:b577024750_rcf27db200
			*
			* */
				
			$this->execute($query);
		}
	}
	
	new ConsultaInstancias();
?>