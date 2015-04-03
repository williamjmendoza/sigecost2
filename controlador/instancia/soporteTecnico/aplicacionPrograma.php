<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/soporteTecnico.php' );

	abstract class ControladorInstanciaSTAplicacionPrograma extends ControladorInstanciaSoporteTecnico
	{
		// Obtener y validar el iri de la instancia de aplicación de programa
		protected function __validarIriAplicacionPrograma(FormularioInstanciaSTAplicacionPrograma $form)
		{
			if(!isset($_POST['iriAplicacionPrograma']) || ($iriAplicacionPrograma=trim($_POST['iriAplicacionPrograma'])) == ''
				|| $iriAplicacionPrograma == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar una aplicaci&oacute;n de programa';
			} else {
				$form->getSoporteTecnico()->getAplicacionPrograma()->setIri($iriAplicacionPrograma);
			}
		}
		
		protected function __getElementosPDF()
		{
			ob_clean();
			
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionProgramaPDF.php' );
			
			$htmlElementos = ob_get_contents();
			
			ob_clean();
			
			$GLOBALS['SigecostRequestVars']['htmlElementos'] = $htmlElementos;
		}
		
		protected function __generarContenidoPDF($instancia, $titulo)
		{
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
			$GLOBALS['SigecostRequestVars']['titulo'] = $titulo;
			
			ob_start();
			
			$this->__getElementosPDF();
			
			ob_clean();
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/soporteTecnicoPDF.php' );
		
			$contenido = ob_get_clean();
				
			$this->__crearyEnviarPDF('solucion_'.date('Ymd_His').'.pdf', $contenido);
		}
	}
?>