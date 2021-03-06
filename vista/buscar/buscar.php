<?php

	$form = FormularioManejador::getFormulario(FORM_BUSCAR);
	$subaccion = isset($GLOBALS['SigecostRequestVars']['subaccion']) ? $GLOBALS['SigecostRequestVars']['subaccion'] : null;
	$datos = $GLOBALS['SigecostRequestVars']['datos'];
	$clave = isset($GLOBALS['SigecostRequestVars']['clave']) ? $GLOBALS['SigecostRequestVars']['clave'] : null;
	$buscarEnClasesET = isset($GLOBALS['SigecostRequestVars']['buscarEnClasesET']) && trim($GLOBALS['SigecostRequestVars']['buscarEnClasesET']) == true ? true : false;
	$buscarEnClasesST = isset($GLOBALS['SigecostRequestVars']['buscarEnClasesST']) && trim($GLOBALS['SigecostRequestVars']['buscarEnClasesST']) == true ? true : false;
	$buscarEnPropiedades = isset($GLOBALS['SigecostRequestVars']['buscarEnPropiedades']) && trim($GLOBALS['SigecostRequestVars']['buscarEnPropiedades']) == true ? true : false;
	$buscarEnInstancias = isset($GLOBALS['SigecostRequestVars']['buscarEnInstancias']) && trim($GLOBALS['SigecostRequestVars']['buscarEnInstancias']) == true ? true : false;
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
				 /*cursor:pointer;*/
				 /*color: #357ebd;*/
				 /*text-decoration: underline;*/
			}
		
		</style>
		
		<script type="text/javascript">
		
			function verDetallesInstanciaSTenBusquedaClave(iriClaseST, iriInstanciaST)
			{
				$('#subaccion').val('verDetalles');
				$('#iriClaseSTVerDetalles').val(iriClaseST);
				$('#iriInstanciaSTVerDetalles').val(iriInstanciaST);

				reeestablecerCheckboxBusquedaEfectuada();
				
				$('#formBusqueda').submit();
			}

			function regresarBusqueda()
			{
				reeestablecerCheckboxBusquedaEfectuada();
				
				$('#formBusqueda').submit();
			}

			function establecerPrimeraPagina()
			{
				$('#pag').val(1);
			}

			function reeestablecerCheckboxBusquedaEfectuada()
			{
				$('#buscarEnClasesET').each(function () {
					this.checked = <?php echo $buscarEnClasesET ? 1 : 0; ?>
				});
				$('#buscarEnClasesST').each(function () {
					this.checked = <?php echo $buscarEnClasesST ? 1 : 0; ?>
				});
				$('#buscarEnPropiedades').each(function () {
					this.checked = <?php echo $buscarEnPropiedades ? 1 : 0; ?>
				});
				$('#buscarEnInstancias').each(function () {
					this.checked = <?php echo $buscarEnInstancias ? 1 : 0; ?>
				});
			}
		
		</script>
	
	</head>
	
	<body>
	
		<?php require_once ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
		
			<ol class="breadcrumb">
				<li><a href="<?php echo SIGECOST_PATH_URL_BASE; ?>">Inicio</a></li>
				<li class="active">B&uacute;squedas</li>
			</ol>
		
			<div class="page-header">
				<h1>B&uacute;squedas de soluciones de incidencias</h1>
			</div>
		
			<form id="formBusqueda" class="form-horizontal" role="form" method="get" action="buscar.php">
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
							<input id="buscarEnClasesET" name="buscarEnClasesET" type="checkbox" value="true"<?php echo $buscarEnClasesET ? ' checked="checked"' : '' ?>>
								Tipos de elementos tecnol&oacute;gicos
						</label>
						<label class="checkbox-inline">
							<input id="buscarEnClasesST" name="buscarEnClasesST" type="checkbox" value="true"<?php echo $buscarEnClasesST ? ' checked="checked"' : '' ?>>
								Tipos de incidencias de soporte t&eacute;cnico
						</label>
						<!-- 
						<label class="checkbox-inline">
							<input id="buscarEnPropiedades" name="buscarEnPropiedades" type="checkbox" value="true"<?php /*echo $buscarEnPropiedades ? ' checked="checked"' : ''*/ ?>>
							Propiedades
						</label>
						 -->
						<label class="checkbox-inline">
							<input id="buscarEnInstancias" name="buscarEnInstancias" type="checkbox" value="true"<?php echo $buscarEnInstancias ? ' checked="checked"' : '' ?>>Ejemplos
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
					
					require ( SIGECOST_PATH_VISTA . '/buscar/buscarDetalles.php' );
					
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
							
							require ( SIGECOST_PATH_VISTA . '/buscar/buscarListar.php' );
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
		
		<script type="text/javascript">

			$( document ).ready(function() {
				$('#clave').focus();
			});
			
		</script>
		
	</body>

</html>