<?php
	include_once( SIGECOST_FORMULARIO_PATH . '/config.php' );
	
	class FormularioManejador
	{
		public static function getFormulario($formName)
		{
			$config = $GLOBALS['Sigecost']['__Forms']['__Config'];
			$file = $config[$formName]['File'];
			$className = $config[$formName]['ClassName'];
			$globalName = $config[$formName]['GlobalName'];
			$list = &$GLOBALS['Sigecost']['__Forms']['__List'];
			$form = null;
			
			if(!isset($list[$globalName]))
			{
				include_once(SIGECOST_FORMULARIO_PATH . '/' . $file);
				$list[$globalName] = new $className();
				$form = $list[$globalName];
			} else {
				$form =  $list[$globalName];
			}
			return $form;
		}
	}	
?>