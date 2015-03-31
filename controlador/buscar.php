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
		
		public function __construct()
		{
			$GLOBALS['SigecostRequestVars']['menuActivo'] = 'busqueda';
			
			parent::__construct();
		}
		
		public function buscar()
		{
			// Obtener el formulario
			$form = FormularioManejador::getFormulario(FORM_BUSCAR);
			
			if(isset($_REQUEST['clave']))
			{
				$buscarEnClasesET = isset($_REQUEST['buscarEnClasesET']) && trim($_REQUEST['buscarEnClasesET']) == 'true' ? true : false;
				$buscarEnClasesST = isset($_REQUEST['buscarEnClasesST']) && trim($_REQUEST['buscarEnClasesST']) == 'true' ? true : false;
				//$buscarEnPropiedades = isset($_REQUEST['buscarEnPropiedades']) && trim($_REQUEST['buscarEnPropiedades']) == 'true' ? true : false;
				$buscarEnInstancias = isset($_REQUEST['buscarEnInstancias']) && trim($_REQUEST['buscarEnInstancias']) == 'true' ? true : false;
			} else {
				// Especifíca cuales checkbox estará activos la primera vez que se muetre la vidta de bśuquedas
				$buscarEnClasesET = true;
				$buscarEnClasesST = true;
				//$buscarEnPropiedades = true;
				$buscarEnInstancias = true;
			}
			$buscarEnPropiedades = false;
			$clave = isset($_REQUEST['clave']) ? trim($_REQUEST['clave']) : '';
			$subAccion = !isset($_REQUEST['subaccion']) || trim($_REQUEST['subaccion']) == 'false' ? null : trim($_REQUEST['subaccion']);
			$iriClaseSTVerDetalles = $_REQUEST['iriClaseSTVerDetalles'];
			$iriInstanciaSTVerDetalles = $_REQUEST['iriInstanciaSTVerDetalles'];
			$filtroClaseET = '';
			$filtroClaseST = '';
			
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
				if($buscarEnClasesET)
					$filtroClaseET = ModeloBuscar::getFiltroClaseElementoTecnologico(array('clave' => $clave));
				
				// Búsquedas en clases de soporte técnico
				if($buscarEnClasesST)
					$filtroClaseST = ModeloBuscar::getFiltroClaseSoporteTecnico(array('clave' => $clave));
				
				// Realizar la consulta de la búsqueda estableciendo los parámetros para la navegación
				$parametros = array(
						'clave' => $clave,
						'buscarEnPropiedades' => $buscarEnPropiedades,
						'buscarEnInstancias' => $buscarEnInstancias,
						'filtroClaseET' => $filtroClaseET,
						'filtroClaseST' => $filtroClaseST
				);
				
				// Obtener la cantidad total de elementos que se obtendrán en la búsqueda
				$totalElementos = ModeloBuscar::buscarTotalElementos($parametros);
				
				// Verificar que no hubo errores consultando el número total de elementos para esta búsqueda
				if($totalElementos !== false)
				{
					// Configurar el objeto de paginación
					$form->setPaginacion(new EntidadPaginacion($totalElementos));  // EntidadPaginacion(<Total elementos>)
					$this->__validarParametrosPaginacion($form);
					$form->getPaginacion()->setUrlObjetivo("buscar.php?accion=buscar&clave=".urlencode($clave)
						.($buscarEnClasesET?'&buscarEnClasesET=true':'').($buscarEnClasesST?'&buscarEnClasesST=true':'').($buscarEnPropiedades?'&buscarEnPropiedades=true':'')
						.($buscarEnInstancias?'&buscarEnInstancias=true':''));
				
					// Establecer los parámetros de la navegación para la consulta de la búsqueda
					$parametros['desplazamiento'] = $form->getPaginacion()->getDesplazamiento();
					$parametros['limite'] = $form->getPaginacion()->getTamanoPagina();
				}
				
				$datos = ModeloBuscar::buscar($parametros);
				
				$GLOBALS['SigecostRequestVars']['datos'] = $datos;
				$GLOBALS['SigecostRequestVars']['formPaginacion'] = $form;
			}
			
			$truncamiento = (int)GetConfig("truncamientoSolucionPatronSTBusqueda");
			
			$GLOBALS['SigecostRequestVars']['clave'] = $clave;
			$GLOBALS['SigecostRequestVars']['buscarEnClasesET'] = $buscarEnClasesET;
			$GLOBALS['SigecostRequestVars']['buscarEnClasesST'] = $buscarEnClasesST;
			$GLOBALS['SigecostRequestVars']['buscarEnPropiedades'] = $buscarEnPropiedades;
			$GLOBALS['SigecostRequestVars']['buscarEnInstancias'] = $buscarEnInstancias;
			$GLOBALS['SigecostRequestVars']['truncamiento'] = $truncamiento; 
			
			require ( SIGECOST_PATH_VISTA . '/buscar/buscar.php' );
		}
	}
	
	new ControladorBusqueda();
	
?>