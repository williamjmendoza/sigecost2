<?php
	$form = FormularioManejador::getFormulario(FORM_BUSCAR);
	$instancias = $GLOBALS['SigecostRequestVars']['instancias'];
	$clave = isset($GLOBALS['SigecostRequestVars']['clave']) ? $GLOBALS['SigecostRequestVars']['clave'] : null;
?>

<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>B&uacute;squeda de patrones
				</h1>
			</div>
		
			<form class="form-horizontal" role="form" method="post" action="buscar.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="buscar">
				</div>
				<div class="form-group">
					<label class="sr-only" for="clave">B&uacute;squeda:</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" class="form-control" id="clave" name="clave" autocomplete="off" autofocus="autofocus"
								placeholder="Introduzca una o mas palabras claves" value="<?php echo $clave != null ? $clave : '' ?>"
							>
							<span class="input-group-btn">
								<button type="submit" class="btn btn-primary">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div>
					</div>
				</div>
			</form>
			
			<?php
				if (is_array($instancias) && count($instancias) > 0)
				{
					$contador = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
			?>
			<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
			<div class="table-responsive">
				<table class="table table table-hover">
					<thead>
						<tr>
							<th>Nº</th>
			<?php
					$headres = array_keys(current($instancias));
				
					foreach ($headres AS $header)
					{
			?>
							
							<th><?php echo $header?></th>
			<?php
					}
				
					reset($instancias);
			?>
						</tr>
					</thead>
					<tbody>
			<?php
					foreach ($instancias AS $datosInternos)
					{
			?>
						<tr>
							<td><?php echo ++$contador; ?></td>
			<?php
						foreach ($datosInternos AS $dato)
						{
			?>
							<td><?php echo $dato; ?></td>
			<?php
						}
			?>
						</tr>
			<?php
					}
			?>
					</tbody>
				</table>
			</div>
			<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
			<?php
				} else {
			?>
			<p>No existen elementos que coincidan con la b&uacute;squeda.</p>
			<?php
				}
			?>
			
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>