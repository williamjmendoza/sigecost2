<?php
	if(
		is_array($GLOBALS['SigecostErrors']['general']) && count($GLOBALS['SigecostErrors']['general']) > 0
		|| is_array($GLOBALS['SigecostInfo']['general']) && count($GLOBALS['SigecostInfo']['general']) > 0
	){
		echo '<a name="mensajes"></a>';
	}
	
	if(is_array($GLOBALS['SigecostErrors']['general']) && count($GLOBALS['SigecostErrors']['general']) > 0){
		echo '<ul class= "mensajeError">';
		foreach($GLOBALS['SigecostErrors']['general'] as $error){
			echo '<li>' .$error . '</li>';
		}
		echo '</ul>';
	}
	if(is_array($GLOBALS['SigecostInfo']['general']) && count($GLOBALS['SigecostInfo']['general']) > 0){
		echo '<ul class= "mensajeInformacion">';
		foreach($GLOBALS['SigecostInfo']['general'] as $error){
			echo '<li>' .$error . '</li>';
		}
		echo '</ul>';
	}
?>