<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionOfimatica/aplicacionOfimatica.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/sobreSistemaOperativo.php' );

	class EntidadInstanciaSTAplicacionOfimaticaDesinstalacionAplicacionOfimatica extends EntidadInstanciaSTAplicacionOfimatica
	{
		use EntidadTraitInstanciaSTSobreSistemaOperativo;

	}
?>