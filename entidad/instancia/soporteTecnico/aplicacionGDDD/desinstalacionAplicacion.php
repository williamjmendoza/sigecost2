<?php

	// Entidades
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/aplicacionGDDD/aplicacionGDDD.php' );
	require_once ( SIGECOST_PATH_ENTIDAD . '/instancia/soporteTecnico/sobreSistemaOperativo.php' );

	class EntidadInstanciaSTAplicacionGDDDDesinstalacionAplicacion extends EntidadInstanciaSTAplicacionGDDD
	{
		use EntidadTraitInstanciaSTSobreSistemaOperativo;
		
	}
?>