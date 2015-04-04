<?php
	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$instanciaSistemaOperativo = $instancia->getSistemaOperativo();
?>
				<tr>
					<td class="incidenciaLabel">Sobre sistema operativo:</td>
					<td class="incidenciaTexto">
						<?php echo $instanciaSistemaOperativo != null ? $instanciaSistemaOperativo->getNombre() . ' - ' . $instanciaSistemaOperativo->getVersion() : "" ?>
					</td>
				</tr>