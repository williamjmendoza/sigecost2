<?php

	require_once( dirname(__FILE__) . '/../../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/instancia/soporteTecnico/impresora/impresora.php' );
	
	// Modelos
	require_once ( SIGECOST_MODELO_PATH . '/instancia/elementoTecnologico/impresora.php' );
	
	class ControladorInstanciaSTImpresoraCorregirImpresionManchada extends ControladorInstanciaSTImpresora
	{
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ST_IMPRESORA_CORREGIR_IMPRESION_MANCHADA_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarUrlSoporteTecnico($form);
			$this->__validarIriEquipoReproduccion($form);
			
			$this->__desplegarFormulario();
		}
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarFormulario()
		{
			$impresoras = ModeloInstanciaETImpresora::obtenerTodasImpresoras();
			
			$GLOBALS['SigecostRequestVars']['impresoras'] = $impresoras;
			
			require ( SIGECOST_VISTA_PATH . '/instancia/soporteTecnico/impresora/corregirImpresionManchadaInsertarModificar.php' );
		}
	}
	
	new ControladorInstanciaSTImpresoraCorregirImpresionManchada();
?>