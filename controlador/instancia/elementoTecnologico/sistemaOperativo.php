<?php

	require_once( dirname(__FILE__) . '/../../../init.php' );
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/controlador.php' );
	
	// Modelos
	
	class ControladorInstanciaETSistemaOperativo extends Controlador
	{
		public function guardar()
		{
			$form = FormularioManejador::getFormulario(FORM_INSTANCIA_ET_SISTEMA_OPERATIVO_INSERTAR_MODIFICAR);
			
			// Validar, obtener y guardar todos los inputs desde el formulario
			$this->__validarNombre($form);
			$this->__validarversion($form);
			
			
			$this->__desplegarFormulario();
			echo "Guardando";
		}  
		
		public function insertar()
		{
			$this->__desplegarFormulario();
		}
		
		private function __desplegarFormulario()
		{
			require ( SIGECOST_VISTA_PATH . '/instancia/elementoTecnologico/sistemaOperativoInsertarModificar.php' );
		}
		
		private function __validarNombre(FormularioInstanciaETSistemaOperativo $form)
		{
			if(!isset($_POST['nombre']) || ($nombre=trim($_POST['nombre'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir un nombre.';
			} else {
				$form->getSistemaOperativo()->setNombre($nombre);
			}
		}
		
		private function __validarversion(FormularioInstanciaETSistemaOperativo $form)
		{
			if(!isset($_POST['version']) || ($nombre=trim($_POST['version'])) == ''){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe introducir una version.';
			} else {
				$form->getSistemaOperativo()->setVersion($version);
			}
		}
	}
	
	new ControladorInstanciaETSistemaOperativo();
?>