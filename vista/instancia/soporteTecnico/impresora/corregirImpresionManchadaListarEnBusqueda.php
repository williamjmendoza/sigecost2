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
					<th colspan="2">Impresora</th>
					<th rowspan="2">Soluci&oacute;n de incidencia de soporte t&eacute;cnico</th>
				</tr>
				<tr>
					<th>Marca</th>
					<th>Modelo</th>
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
					<td><?php echo $instancia->getEquipoReproduccion()->getMarca() ?> </td>
					<td><?php echo $instancia->getEquipoReproduccion()->getModelo() ?></td>
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