<?php
	
	// Controladores
	require_once ( SIGECOST_CONTROLADOR_PATH . '/instancia/soporteTecnico/soporteTecnico.php' );

	class ControladorInstanciaSTEquipoReproduccion extends ControladorInstanciaSoporteTecnico
	{
		// Obtener y validar el iri del equipo de reproducción
		protected function __validarIriEquipoReproduccion(FormularioInstanciaSTEquipoReproduccion $form)
		{
			if(!isset($_POST['iriEquipoReproduccion']) || ($iriEquipoReproduccion=trim($_POST['iriEquipoReproduccion'])) == ''
				|| $iriEquipoReproduccion == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar un equipo de reproducci&oacute;n (escaner, fotocopiadora o impresora).';
			} else {
				$form->getSoporteTecnico()->getEquipoReproduccion()->setIri($iriEquipoReproduccion);
			}
		}
	}
?>