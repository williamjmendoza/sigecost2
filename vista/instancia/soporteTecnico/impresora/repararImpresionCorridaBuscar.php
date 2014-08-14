<?php

	$instancias = $GLOBALS['SigecostRequestVars']['instancias'];
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
    	<script type="text/javascript">
    	
			function setAccion(accion) {
				$('input[type="hidden"][name="accion"]').val(accion);
			}
			
    	</script>
	
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="repararImpresionCorrida.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="repararImpresionCorrida.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de soporte t&eacute;cnico en impresora: <small>reparar impresi&oacute;n corrida</small></h1>
			</div>
			
			<?php
				if (is_array($instancias) && count($instancias) > 0)
				{
			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th rowspan="2">Url soporte t&eacute;cnico</th>
							<th colspan="2">Impresora</th>
							<th rowspan="2">Opciones</th>
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
			?>
						<tr>
							<td><?php echo $instancia->getUrlSoporteTecnico() ?></td>
							<td><?php echo $instancia->getEquipoReproduccion()->getMarca() ?> </td>
							<td><?php echo $instancia->getEquipoReproduccion()->getModelo() ?></td>
							<td>
								<form class="form-horizontal" role="form" action="repararImpresionCorrida.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $instancia->getIri() ?>">
									</div>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Detallar</button>
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
				} else {
			?>
			<p>No existen instancias que mostrar.</p>
			<?php
				}
			?>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
