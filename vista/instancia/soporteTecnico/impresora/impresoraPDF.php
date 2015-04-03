<?php

	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$instanciaImpresora = $instancia->getEquipoReproduccion();

?>
				<tr>
					<td class="incidenciaLabel">En impresora:</td>
					<td class="incidenciaTexto"><?php echo $instanciaImpresora != null ? $instanciaImpresora->getMarca() . ' - ' .$instanciaImpresora->getModelo() : "" ?></td>
				</tr>