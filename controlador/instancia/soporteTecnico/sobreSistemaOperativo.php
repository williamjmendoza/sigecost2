<?php

	trait ControladorTraitInstanciaSTSobreSistemaOperativo
	{
		// Obtener y validar el iri de la instancia de sistema operativo
		protected function __validarIriSistemaOperativo($form)
		{
			if(!isset($_POST['iriSistemaOperativo']) || ($iriSistemaOperativo=trim($_POST['iriSistemaOperativo'])) == ''
					|| $iriSistemaOperativo == "0"
			){
				$GLOBALS['SigecostErrors']['general'][] = 'Debe seleccionar un sistema operativo.';
			} else {
				$form->getSoporteTecnico()->getSistemaOperativo()->setIri($iriSistemaOperativo);
			}
		}
	}
?>