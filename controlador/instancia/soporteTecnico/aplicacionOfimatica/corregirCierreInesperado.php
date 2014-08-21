<?php
	require_once( dirname(__FILE__) . '/../../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/instancia/elementoTecnologico/aplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_MODELO . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperado.php' );
	
	class ControladorInstanciaSTAplicacionOfimaticaCorregirCierreInesperado extends ControladorInstanciaSTAplicacionOfimatica
	{
		public function buscar()
		{
			$instancias = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::buscarInstancias();
		
			$GLOBALS['SigecostRequestVars']['instancias'] = $instancias;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoBuscar.php' );
		}
		
		public function desplegarDetalles()
		{
			if(!isset($_REQUEST['iri']) || ($iri=trim($_REQUEST['iri'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un iri.';
			} else {
				$this->__desplegarDetalles($iri);
			}
		}
		
		private function __desplegarDetalles($iriInstancia)
		{
			$instancia = ModeloInstanciaSTAplicacionOfimaticaCorregirCierreInesperado::obtenerInstanciaPorIri($iriInstancia);
		
			$GLOBALS['SigecostRequestVars']['instancia'] = $instancia;
		
			require ( SIGECOST_PATH_VISTA . '/instancia/soporteTecnico/aplicacionOfimatica/corregirCierreInesperadoDetalles.php' );
		}
		
	}
	
	new ControladorInstanciaSTAplicacionOfimaticaCorregirCierreInesperado();
?>