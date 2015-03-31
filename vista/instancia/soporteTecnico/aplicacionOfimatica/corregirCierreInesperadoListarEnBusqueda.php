<?php

	$iriClaseST = $GLOBALS['SigecostRequestVars']['iriClaseST'];
	$instancias = $GLOBALS['SigecostRequestVars']['instanciasClaseST'];
	$truncamiento = $GLOBALS['SigecostRequestVars']['truncamiento'];

	if (is_array($instancias) && count($instancias) > 0)
	{		
?>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th rowspan="2">#</th>
					<th colspan="2">Aplicaci&oacute;n</th>
					<th rowspan="2">Soluci&oacute;n de incidencia de soporte t&eacute;cnico</th>
				</tr>
				<tr>
					<th>Nombre</th>
					<th>Versi&oacute;n</th>
				</tr>
			</thead>
			<tbody>
	<?php
			foreach ($instancias AS $instancia)
			{
				$patron = $instancia->getPatron();
	?>
				<tr class="datoST" onclick="verDetallesInstanciaSTenBusquedaClave('<?php echo $iriClaseST ?>', '<?php echo $instancia->getIri() ?>');">
					<td><?php echo (++$GLOBALS['SigecostRequestVars']['contador']) ?></td>
					<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
					<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
					<td><a href="javascript:void();"><?php echo $patron != null ? $patron->getSolucionTruncada($truncamiento) : '';?></a>
				</tr>
	<?php
			}
	?>
			</tbody>
		</table>
	</div>
<?php
	}
?>