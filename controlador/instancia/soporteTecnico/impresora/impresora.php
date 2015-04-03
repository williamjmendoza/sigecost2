<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/equipoReproduccion.php' );

	abstract class ControladorInstanciaSTImpresora extends ControladorInstanciaSTEquipoReproduccion
	{
		protected function __getElementosPDF()
		{
			ob_clean();
				
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/impresora/impresoraPDF.php' );
				
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