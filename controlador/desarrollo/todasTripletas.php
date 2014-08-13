<?php
	require_once ( dirname(__FILE__) . '/../../init.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/desarrollo/queryScreen.php' );
	
	class TodasTripletas extends QueryScreen
	{
		public function __construct()
		{
			$query = '
		  		SELECT DISTINCT ?subject ?property ?object WHERE {
					?subject ?property ?object .
				}
			';
	
			$this->execute($query);
		}
		
	}
	
	new TodasTripletas();
?>