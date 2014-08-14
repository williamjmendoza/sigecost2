<?php
	// Entidades
	require_once( SIGECOST_PATH_ENTIDAD . '/instancia/elementoTecnologico/aplicacionPrograma.php' );
	
	// Lib
	require_once( SIGECOST_PATH_LIB . '/definiciones.php' );
	
	// Modelos
	require_once ( SIGECOST_PATH_MODELO . '/general.php' );
	
	class ModeloInstanciaETAplicacionGraficaDigitalDibujoDiseno
	{
		public static function existeImpresora(EntidadInstanciaETAplicacionGraficaDigitalDibujoDiseno $aplicacion)
		{
			$preMsg = 'Error al verificar la existencia de una instancia de aplicación gráfica digital, dibujo y diseño.';
			
			try
			{
				if($aplicacion === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora\' es nulo.');
				
				if($aplicacion->getNombre() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getNombre()\' es nulo.');
				
				if($aplicacion->getNombre() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getNombre()\' está vacío.');
				
				if($aplicacion->getVersion() === null)
					throw new Exception($preMsg . ' El parámetro \'$impresora->getVersion()\' es nulo.');
				
				if($aplicacion->getVersion() == "")
					throw new Exception($preMsg . ' El parámetro \'$impresora->getVersion()\' está vacío.');
				
				
				// Verificar si existe una instancia de la aplicación gráfica digital, dibujo y diseño con el mismo nombre y versión,
				// que la pasada por parámetros
				$query = '
						PREFIX : <'.SIGECOST_IRI_ONTOLOGIA_NUMERAL.'>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		
						ASK
						{
							_:instanciaAplicacion rdf:type :'.SIGECOST_FRAGMENTO_APLICACION_GRAFICA_DIGITAL_DIBUJO_DISENO.' .
							_:instanciaAplicacion :nombreAplicacionPrograma "'.$aplicacion->getNombre().'"^^xsd:string .
							_:instanciaAplicacion :versionAplicacionPrograma "'.$aplicacion->getVersion().'"^^xsd:string .
						}
				';
				
				$result = $GLOBALS['ONTOLOGIA_STORE']->query($query);
				
				if ($errors = $GLOBALS['ONTOLOGIA_STORE']->getErrors())
					throw new Exception("Error al consultar la existencia de la instancia de la aplicación gráfica digital, dibujo y diseño " .
							"(nombre = '".$aplicacion->getNombre()."', versión = '".$aplicacion->getVersion()."'). Detalles:\n" . join("\n", $errors));
				
					return $result['result'];
				
			} catch (Exception $e) {
				error_log($e, 0);
				return null;
			}
				
		}
	}
?>