<?php
	
	// Controladores
	require_once ( SIGECOST_PATH_CONTROLADOR . '/instancia/soporteTecnico/soporteTecnico.php' );

	abstract class ControladorInstanciaSTEquipoReproduccion extends ControladorInstanciaSoporteTecnico
	{
		// Obtener y validar el iri de la instancia de equipo de reproducción
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