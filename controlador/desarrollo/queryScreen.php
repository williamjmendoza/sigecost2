<?php
	class QueryScreen
	{
		public function __construct()
		{
			if(isset($_REQUEST['accion'])){
				if(method_exists($this, $_REQUEST['accion'])){
					$this->$_REQUEST['accion']();
				} else {
					//echo "Accion no definida";
				}
			} else {
				//echo "Sin parametro de accion";
			}
		}
		
		public function execute($query = null)
		{
			$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
			
			// Manejo del error
			if($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors()){
				error_log("arc2sparql error:\n" . join("\n", $errors));
				echo "arc2sparql error:\n" . join("\n", $errors);
				exit;
			}
			
			// manejo del resultado
			
			if ($result["query_type"] == "construct" ||
					$result["query_type"] == "describe"
			) {
				$ser = ARC2::getTurtleSerializer();
				echo "<pre>";
				print htmlentities($ser->getSerializedIndex($result["result"]));
				print "\n";
				echo "</pre>";
			} else if ($result["query_type"] == "select") {
				
				$vars = $result["result"]["variables"];
				$rows = $result["result"]["rows"];
				$count = 0;
				
				if($rows)
				{
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
			} else {
				// Algo inesperado
				var_dump($result);
			}
		}
	}
?>