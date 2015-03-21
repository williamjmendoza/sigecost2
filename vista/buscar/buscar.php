<?php
	$form = FormularioManejador::getFormulario(FORM_BUSCAR);
	$datos = $GLOBALS['SigecostRequestVars']['datos'];
	$clave = isset($GLOBALS['SigecostRequestVars']['clave']) ? $GLOBALS['SigecostRequestVars']['clave'] : null;
?>

<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
		
		<style type="text/css">
		
			tr.datoST:hover
			{
				 cursor:pointer;
				 color: #357ebd;
			}
		
		</style>
	
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
			
			
				<?php
					if (is_array($datos) && count($datos) > 0)
					{
						$GLOBALS['SigecostRequestVars']['contador'] = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
				?>
				<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
				<div class="table-responsive">
				<?php
						foreach ($datos AS $iriClaseST => $datosClaseST)
						{
							$GLOBALS['SigecostRequestVars']['iriClaseST'] = $iriClaseST;
							$GLOBALS['SigecostRequestVars']['instanciasClaseST'] = $datosClaseST['instanciasClaseST'];
							
							require ( SIGECOST_PATH_VISTA . '/buscar/buscarDesplegar.php' );
						}
				?>
				</div>
				<?php require ( SIGECOST_PATH_VISTA . '/paginacion.php' ); ?>
				<?php
					} else {
				?>
				<p>No existen elementos que coincidan con la b&uacute;squeda.</p>
				<?php
					}
				?>
			</form>
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>