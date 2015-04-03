<?php
	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$instanciaAplicacion = $instancia->getAplicacionPrograma();
?>
				<tr>
					<td class="incidenciaLabel">En apliaci&oacute;n de programa:</td>
					<td class="incidenciaTexto"><?php echo $instanciaAplicacion != null ? $instanciaAplicacion->getNombre() . ' - ' .$instanciaAplicacion->getVersion() : "" ?></td>
				</tr>