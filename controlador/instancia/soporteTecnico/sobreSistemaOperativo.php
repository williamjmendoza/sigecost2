<?php

	trait ControladorTraitInstanciaSTSobreSistemaOperativo
	{
		// Obtener y validar el iri de la instancia de sistema operativo
		protected function __validarIriSistemaOperativo($form)
		{
			if(!isset($_POST['iriSistemaOperativo']) || ($iriSistemaOperativo=trim($_POST['iriSistemaOperativo'])) == ''
					|| $iriSistemaOperativo == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar un sistema operativo.';
			} else {
				$form->getSoporteTecnico()->getSistemaOperativo()->setIri($iriSistemaOperativo);
			}
		}
		
		protected function __getElementosPDF()
		{
			parent::__getElementosPDF();
			
			ob_clean();
			ob_start();
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/sistemaOperativoPDF.php' );
				
			$htmlElementos = ob_get_contents();
			ob_clean();
				
			$GLOBALS['SigecostRequestVars']['htmlElementos'] .= $htmlElementos;
		}
		
	}
?>