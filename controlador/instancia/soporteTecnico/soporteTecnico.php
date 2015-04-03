<?php

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	
	// Formularios
	require_once ( SIGECOST_PATH_FORMULARIO . '/instancia/soporteTecnico/soporteTecnico.php' );
	
	// Librerías
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	require_once( SIGECOST_PATH_LIB . '/html2pdf/html2pdf.class.php' );
	
	abstract class ControladorInstanciaSoporteTecnico extends Controlador
	{
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'administracionOntologia';
			
			parent::__construct();
		}
		
		protected function __validarSolucionSoporteTecnico(FormularioInstanciaSoporteTecnico $form)
		{
			if(!isset($_POST['solucionSoporteTecnico']) || ($solucion=trim($_POST['solucionSoporteTecnico'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una soluci&oacute;n de soporte t&eacute;cnico.';
			} else {
				
				if( $form->getSoporteTecnico()->getPatron() === null )
					$form->getSoporteTecnico()->setPatron(new EntidadPatron());
				
				$form->getSoporteTecnico()->getPatron()->setSolucion($solucion);
			}
		}
		
		protected function __obtenerSolucionSoporteTecnico(FormularioInstanciaSoporteTecnico $form)
		{
			if(isset($_POST['solucionSoporteTecnico']) && ($solucion=trim($_POST['solucionSoporteTecnico'])) != ''){
				$form->getSoporteTecnico()->getPatron()->setSolucion($solucion);
			}
		}
		
		protected function __crearyEnviarPDF($fileName = "", $contenido)
		{
			$html2pdf = new HTML2PDF('P', 'Letter', 'es', true, 'UTF-8', array(0, 0, 0, 0));
			
			$html2pdf->writeHTML($contenido);
			$html2pdf->Output('solucion_'.date('Ymd_His').'.pdf');
		}
		
		public function generarPDF()
		{
		
			if(!isset($_REQUEST['iri']) || ($iri=trim($_REQUEST['iri'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un iri.';
			} else {
				$this->__generarPDF($iri);
			}
		}
		
		abstract protected function __generarPDF($iri);
		
		abstract protected function __generarContenidoPDF($instancia, $titulo);
		
		abstract protected function __getElementosPDF();
		
	}

?>