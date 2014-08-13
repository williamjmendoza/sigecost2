<?php
	include(dirname(__FILE__)."/../init.php");
	
	$query = '
	  		SELECT DISTINCT ?subject ?property ?object WHERE {
				?subject ?property ?object .
			}
		';
	
	$r = '';
	
	$rows = $GLOBALS['ONTOLOGIA_STORE']->query($query, 'rows');
	
	if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()) {
		error_log("arc2sparql error:\n" . join("\n", $errors));
		echo "arc2sparql error:\n" . join("\n", $errors);
	} else {
		if($rows){
			$r = '<table border=1> <th>N</th><th>Subject</th><th>Property</th><th>Object</th>'."\n";
			$count = 0;
			foreach ($rows as $row) {
				$r .= '<tr><td>'.(++$count).'</td><td>'.$row['subject'] .  '</td><td>'.$row['property'] . '</td><td>'.$row['object'] . '</td></tr>'."\n";
			}
			$r .='</table>'."\n";
		} else {
			$r = '<em>No data returned</em>';
		}
	}
	
	//echo $r;
?>
<html>
	<head>
		<style type="text/css">
			
			#searchSelects, #searchSelects ul {
				padding: 0px;
				margin: 0px;
			}
			
			#searchSelects {
				padding-bottom: 30px;
			}
		
			#searchSelects >  li {
				display: inline-block;
				padding: 30px 30px 0px 30px;
			}
			
			#searchSelects >  li > ul > li {
				list-style-type: none;
			}
		
		</style>
		<script type="text/javascript" src="<?php echo SIGECOST_PATH_URL_JAVASCRIPT ?>/lib/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			$().ready(function() {
				createSelectSubclasses("Soporte técnico", "http://www.owl-ontologies.com/OntologySoporteTecnico.owl#SoporteTecnico");
			});

			function createSelectSubclasses(label, iri)
			{

				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getSubclassesOf",
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0){
						
							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.iri);
								objOption.appendChild(document.createTextNode(arrDatos.label));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									createSelectSubclasses($(this.options[this.selectedIndex]).text(), this.value);
								}
							});
						}
					}
				});
				
			}
		</script>
	</head>
	<body>
		<div>
			<ul id="searchSelects"></ul>
		</div>
	</body>
</html>