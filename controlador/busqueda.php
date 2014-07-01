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
		<script type="text/javascript" src="../js/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			 $().ready(function() {
		
				 $.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					data: {
						accion: "getSoporteTecnicoSubclasses"
					},
					success: function(json){
	
						$.each(json.datos, function(id, arrDatos){
							var objOption = document.createElement("option");
							objOption.setAttribute('value', arrDatos.iri);
							
							objOption.appendChild(document.createTextNode(arrDatos.label));
							$('#nivel_0').append(objOption);
						});
					}
				});
		
			  });
		</script>
	</head>
	<body>
		<div>
			Soporte T&eacute;nico<br />
			<select id="nivel_0" name="nivel_0">
				<option value="0">Selecionar...</option>
			</select>
		</div>
	</body>
</html>