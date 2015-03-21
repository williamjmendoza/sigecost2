<?php

	$instancias = $GLOBALS['SigecostRequestVars']['instanciasClaseST'];
	
	if (is_array($instancias) && count($instancias) > 0)
	{
?>
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-responsive">
			<thead>
				<tr>
					<th rowspan="2">#</th>
					<th colspan="2">Aplicaci&oacute;n</th>
					<th colspan="2">Patr&oacute;n soporte t&eacute;cnico</th>
				</tr>
				<tr>
					<th>Nombre</th>
					<th>Versi&oacute;n</th>
					<th>Fecha creaci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
	<?php
			foreach ($instancias AS $instancia)
			{
				$patron = $instancia->getPatron();
	?>
				<tr class="datoST" onclick="alert('Hola');">
					<td><?php echo (++$GLOBALS['SigecostRequestVars']['contador']) ?></td>
					<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
					<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
					<td><?php echo $patron != null ? $patron->getFechaCreacion() : "" ?></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="3">
						<samp><?php
						$strSolucion = "";
						$truncamiento = GetConfig("truncamientoSolucionPatronSTBusqueda");
						
						if($patron != null)
						{
							if(strlen($patron->getSolucion()) <= $truncamiento)
								$strSolucion = strip_tags($patron->getSolucion());
							else {
								$strSolucion = strip_tags(substr($patron->getSolucion(), 0, $truncamiento - 3)) . '...';
							}

						}
						echo trim($strSolucion);
					
					?></samp>
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