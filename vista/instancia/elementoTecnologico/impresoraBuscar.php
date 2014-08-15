<?php

	$impresoras = $GLOBALS['SigecostRequestVars']['impresoras'];
	
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
				<li><a href="impresora.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="impresora.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancias del elemento tecnol&oacute;gico impresora</h1>
			</div>
			
			<?php
				if (is_array($impresoras) && count($impresoras) > 0)
				{
			?>
			<div class="table-responsive">
				<table class="table table table-hover table-responsive">
					<thead>
						<tr>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($impresoras AS $impresora)
					{
			?>
						<tr>
							<td><?php echo $impresora->getMarca() ?> </td>
							<td><?php echo $impresora->getModelo() ?></td>
							<td>
								<form class="form-horizontal" role="form" action="impresora.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $impresora->getIri() ?>">
									</div>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('modificar');">Modificar</button>
									<button type="submit" class="btn btn-primary btn-xs" onclick="setAccion('desplegarDetalles');">Ver detalles</button>
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
			<p>No existen impresoras que mostrar.</p>
			<?php
				}
			?>
		
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>
