<?php

	require_once( dirname(__FILE__) . '/../init.php' );

	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/controlador.php' );
	require_once ( SIGECOST_PATH_CONTROLADOR . '/paginacion.php' );

	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/buscar.php' );

	class ControladorBusqueda extends Controlador
	{
		use ControladorTraitPaginacion;
		
		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_BUSCAR);
			
			$clave = isset($_REQUEST['clave']) ? trim($_REQUEST['clave']) : '';
			$subAccion = !isset($_REQUEST['subaccion']) || trim($_REQUEST['subaccion']) == 'false' ? false : trim($_REQUEST['subaccion']);
			$iriClaseSTVerDetalles = $_REQUEST['iriClaseSTVerDetalles'];
			$iriInstanciaSTVerDetalles = $_REQUEST['iriInstanciaSTVerDetalles'];
			
			// Validar si se está solicitando mostrar los detalles de un patrón de solución
			if($subAccion == 'verDetalles' && $iriClaseSTVerDetalles != "" && $iriInstanciaSTVerDetalles != "")
			{
				$datosInstancia = ModeloBuscar::verDetalles($iriClaseSTVerDetalles, $iriInstanciaSTVerDetalles);
				
				$GLOBALS['SigecostRequestVars']['subaccion'] = 'verDetalles';
				$GLOBALS['SigecostRequestVars']['datosInstancia'] = $datosInstancia;
				$GLOBALS['SigecostRequestVars']['pag'] = $_REQUEST['pag'];
			}
			else if($clave != '')
			{
				// Búsquedas en clases de elemento tecnológico
				$filtroClaseET = ModeloBuscar::getFiltroClaseElementoTecnologico(array('clave' => $clave));
				// Búsquedas en clases de soporte técnico
				$filtroClaseST = ModeloBuscar::getFiltroClaseSoporteTecnico(array('clave' => $clave));
				
				// Realizar la consulta de la búsqueda estableciendo los parámetros para la navegación
				$parametros = array('clave' => $clave, 'filtroClaseET' => $filtroClaseET, 'filtroClaseST' => $filtroClaseST);
				
				// Obtener la cantidad total de elementos que se obtendrán en la búsqueda
				$totalElementos = ModeloBuscar::buscarTotalElementos($parametros);
				
				// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
				if($totalElementos !== false)
				{
					// Configurar el objeto de paginación
					$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Total elementos>)
					$this->__validarParametrosPaginacion($form);
					$form->getPaginacion()->setUrlObjetivo("buscar.php?accion=buscar&clave=". urlencode($clave));
				
					// Establecer los parámetros de la navegación para la consulta de la búsqueda
					$parametros['desplazamiento'] = $form->getPaginacion()->getDesplazamiento();
					$parametros['limite'] = $form->getPaginacion()->getTamanoPagina();
				}
				
				$datos = ModeloBuscar::buscar($parametros);
				
				$GLOBALS['SigecostRequestVars']['datos'] = $datos;
				$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
			}
			
			$GLOBALS['SigecostRequestVars']['clave'] = $clave;
			
			require ( SIGECOST_PATH_VISTA . '/buscar/buscar.php' );
		}
	}
	
	new ControladorBusqueda();
	
?>