<?php

	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/patron.php' );

	class ModeloPatron
	{
		public static function guardarPatron(EntidadPatron $patron)
		{
			$preMsg = 'Error al guardar el patrón.';
			
			try
			{
				if ($patron === null)
					throw new Exception($preMsg . ' El parámetro \'$patron\' es nulo.');
				
				if($patron->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getNombre()\' está vacío.');
				
				if($patron->getSolucion() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getSolucion()\' está vacío.');
				
				if($patron->getUsuarioCreador() === null)
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioCreador()\' es nulo.');
				
				if($patron->getUsuarioCreador()->getId() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getUsuarioCreador()->getId()\' está vacío.');
				
				if($patron->getFechaCreacion() == "")
					throw new Exception($preMsg . ' El parámetro \'$patron->getFechaCreacion()\' está vacío.');
				
			} catch (Exception $e) {
				
			}
		}
	}
?>