<?php
	include(dirname(__FILE__)."/../init.php");
	
	echo "Query";
	
	$query = '
	  		SELECT DISTINCT ?subject ?property ?object WHERE {
				?subject ?property ?object .
			}
		';
	

	$query = '
	  		SELECT DISTINCT ?subject ?property COUNT(?object) AS ?count WHERE {
				?subject ?property ?object .
			} GROUP BY ?subject ?property
		';
	
	$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
	
	if($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()){
		error_log("arc2sparql error:\n" . join("\n", $errors));
		echo "arc2sparql error:\n" . join("\n", $errors);
		exit;
	}
		
	$vars = $result["result"]["variables"];
	$rows = $result["result"]["rows"];
	$count = 0;
	
	if($rows){
		echo '
			<table border="1">
				<tr>
					<th>N</th>
		';
		
		foreach ($vars AS $var){
			echo '
					<th>'.$var.'</th>
			';
		}
		echo '
				</tr>
		';
		foreach ($rows AS $row){
			echo '
				<tr>
					<td>'.(++$count).'</td>
			';
			foreach ($vars AS $var){
				echo '
					<td>'.$row[$var].'</td>
				';
			}
			echo '
				</tr>
			';
		}
		
		echo '
			</table>	
		';
	} else {
		echo '<em>No data returned</em>';
	}
	
?>