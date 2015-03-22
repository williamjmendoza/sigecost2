<?php

	$form = FormularioManejador::getFormulario(FORM_BUSCAR);
	$subaccion = isset($GLOBALS['SigecostRequestVars']['subaccion']) ? $GLOBALS['SigecostRequestVars']['subaccion'] : false;
	$datos = $GLOBALS['SigecostRequestVars']['datos'];
	$clave = isset($GLOBALS['SigecostRequestVars']['clave']) ? $GLOBALS['SigecostRequestVars']['clave'] : null;
	$formPaginacion = $GLOBALS['SigecostRequestVars']['formPaginacion'];
	$paginacion = $formPaginacion != null ? $formPaginacion->getPaginacion() : null;
	
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
		
		<script type="text/javascript">

			function verDetallesInstanciaSTenBusquedaClave(iriClaseST, iriInstanciaST)
			{
				$('#subaccion').val('verDetalles');
				$('#iriClaseSTVerDetalles').val(iriClaseST);
				$('#iriInstanciaSTVerDetalles').val(iriInstanciaST);

				$('#formBusqueda').submit();
			}

			function establecerPrimeraPagina()
			{
				$('#pag').val(1);
			}
		
		</script>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>B&uacute;squeda de patrones</h1>
			</div>
		
			<form id="formBusqueda" class="form-horizontal" role="form" method="post" action="buscar.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="buscar">
					<input id="subaccion" type="hidden" name="subaccion" value="false">
					<input id="iriClaseSTVerDetalles" type="hidden" name="iriClaseSTVerDetalles" value="false">
					<input id="iriInstanciaSTVerDetalles" type="hidden" name="iriInstanciaSTVerDetalles" value="false">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="buscarEn">Buscar en:</label>
					<div class="col-sm-10">
						<label class="checkbox-inline">
							<input type="checkbox" value="">Clases de elemento tecnol&oacute;gico
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="">Clases se soporte t&eacute;cnico
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="">Instancias
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="sr-only" for="clave">B&uacute;squeda:</label>
					<div class="col-sm-4 col-sm-offset-2">
						<div class="input-group">
							<input type="text" class="form-control" id="clave" name="clave" autocomplete="off" autofocus="autofocus"
								placeholder="Introduzca una o mas palabras claves" value="<?php echo $clave != null ? $clave : '' ?>"
							>
							<span class="input-group-btn">
								<button type="submit" class="btn btn-primary" onclick="establecerPrimeraPagina();">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div>
					</div>
				</div>
			
			
				<?php
				
				if($subaccion == 'verDetalles')
				{
					
				?>
				<div style="display:none;">
					<input id="pag" type="hidden" name="pag" value="<?php echo $GLOBALS['SigecostRequestVars']['pag']; ?>">
				</div>
				<hr>
				<?php
					
					require ( SIGECOST_PATH_VISTA . '/buscar/buscarVerDetalles.php' );
					
				} else if($clave != null) {
					if($paginacion != null && $paginacion->getTotalPaginas() > 1)
					{
				?>
				<div style="display:none;">
					<input id="pag" type="hidden" name="pag" value="<?php echo $paginacion->getPaginaActual(); ?>">
				</div>
				<?php
					}

					if (is_array($datos) && count($datos) > 0)
					{
						$GLOBALS['SigecostRequestVars']['contador'] = ( $form->getPaginacion() != null) ? $form->getPaginacion()->getDesplazamiento() :  0;
				?>
				<hr>
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
				<hr>
				<p>No existen elementos que coincidan con la b&uacute;squeda.</p>
				<?php
					}
				}
				?>
			</form>
		</div>

		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
	
	</body>

</html>