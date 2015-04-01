<?php

	$iriClaseST = $GLOBALS['SigecostRequestVars']['iriClaseST'];
	$instancias = $GLOBALS['SigecostRequestVars']['instanciasClaseST'];
	$truncamiento = $GLOBALS['SigecostRequestVars']['truncamiento'];
	
	if (is_array($instancias) && count($instancias) > 0)
	{
?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th rowspan="2">#</th>
					<th colspan="2">Aplicaci&oacute;n</th>
					<th rowspan="2">Soluci&oacute;n de incidencia de soporte t&eacute;cnico</th>
					<th rowspan="2">Opciones</th>
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
				<tr class="datoST">
					<td><?php echo (++$GLOBALS['SigecostRequestVars']['contador']) ?></td>
					<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
					<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
					<td><p><?php echo $patron != null ? $patron->getSolucionTruncada($truncamiento) : '';?></p></td>
					<td>
						<div class="form-group">
							<button type="button" class="btn btn-primary btn-sm"
								onclick="verDetallesInstanciaSTenBusquedaClave('<?php echo $iriClaseST ?>', '<?php echo $instancia->getIri() ?>');"
							>Ver Detalles</button>
						</div>
					</td>
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