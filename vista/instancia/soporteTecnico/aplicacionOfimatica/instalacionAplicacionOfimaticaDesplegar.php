<?php

	$instancias = $GLOBALS['SigecostRequestVars']['instanciasClaseST'];
	
	if (is_array($instancias) && count($instancias) > 0)
	{
?>
	<div class="table-responsive">
		<table class="table table table-hover table-responsive">
			<thead>
				<tr>
					<th rowspan="2">#</th>
					<th colspan="2">Aplicaci&oacute;n</th>
					<th colspan="2">Sistema operativo</th>
					<th colspan="2">Patr&oacute;n soporte t&eacute;cnico</th>
					<th rowspan="2">Opciones</th>
				</tr>
				<tr>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Nombre</th>
					<th>Versi&oacute;n</th>
					<th>Fecha creaci&oacute;n</th>
					<th>Soluci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
	<?php
			foreach ($instancias AS $instancia)
			{
				$patron = $instancia->getPatron();
	?>
				<tr>
					<td><?php echo (++$GLOBALS['SigecostRequestVars']['contador']) ?></td>
					<td><?php echo $instancia->getAplicacionPrograma()->getNombre() ?> </td>
					<td><?php echo $instancia->getAplicacionPrograma()->getVersion() ?></td>
					<td><?php echo $instancia->getSistemaOperativo()->getNombre() ?></td>
					<td><?php echo $instancia->getSistemaOperativo()->getVersion() ?></td>
					<td><?php echo $patron != null ? $patron->getFechaCreacion() : "" ?></td>
					<td><samp><?php
						$strSolucion = "";
						$truncamiento = GetConfig("truncamientoSolucionPatronSoporteTecnico");
						
						if($patron != null)
						{
							if(strlen($patron->getSolucion()) <= $truncamiento)
								$strSolucion = htmlentities($patron->getSolucion());
							else {
								$strSolucion = htmlentities(substr($patron->getSolucion(), 0, $truncamiento - 3)) . '...';
							}

						}
						echo $strSolucion;
					
					?></samp></td>
					<td>
						<form class="form-horizontal buscarOpciones" role="form" action="instalacionAplicacionOfimatica.php" method="post">
							<div style="display:none;">
								<input type="hidden" name="accion" value="desplegarDetalles">
								<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-xs" role="button">Ver Detalles</button>
							</div>	
						</form>
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