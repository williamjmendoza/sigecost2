<?php

	$escaners = $GLOBALS['SigecostRequestVars']['escaners'];
	
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/head.php' ); ?>
		
    	<style type="text/css">
    	/*
    		div > div > div {
    			border-width: 1px; border-color: red; border-style: solid;
    		}
    		*/
    		
    		body { padding-top: 70px; }
    	</style>
    	
    	<script type="text/javascript">
    	
			function setAccion(accion) {
				$('input[type="hidden"][name="accion"]').val(accion);
			}
			
    	</script>
	
	</head>
	
	<body>
	
		<?php require ( SIGECOST_VISTA_PATH . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="escaner.php?accion=insertar">Insertar</a></li>
				<li class="active"><a href="escaner.php?accion=Buscar">Buscar</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_VISTA_PATH . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancias del elemento tecnol&oacute;gico escaner</h1>
			</div>
			
			<?php
				if (is_array($escaners) && count($escaners) > 0)
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
					foreach ($escaners AS $escaner)
					{
			?>
						<tr>
							<td><?php echo $escaner->getMarca() ?> </td>
							<td><?php echo $escaner->getModelo() ?></td>
							<td>
								<form class="form-horizontal" role="form" action="escaner.php" method="post">
									<div style="display:none;">
										<input type="hidden" name="accion" value="">
										<input type="hidden" name="iri" value="<?php echo $escaner->getIri() ?>">
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
			<p>No existen escaners que mostrar.</p>
			<?php
				}
			?>
		
		</div>
		
		<?php require ( SIGECOST_VISTA_PATH . '/general/footer.php' ); ?>
			
	</body>

</html>
