<?php
	$sistemaOperativo = $GLOBALS['SigecostRequestVars']['sistemaOperativo'];
	$esAdministradorOntologia = $GLOBALS['SigecostRequestVars']['esAdministradorOntologia'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/head.php' ); ?>
	
	</head>
	
	<body>
	
		<?php require ( SIGECOST_PATH_VISTA . '/general/topMenu.php' ); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo SIGECOST_PATH_URL_BASE ?>">Inicio</a></li>
				<li><a href="<?php echo SIGECOST_PATH_URL_CONTROLADOR ?>/administracionOntologia.php?accion=administrarETLista"><?php
					if($esAdministradorOntologia) {
						echo "Administraci&oacute;n de los elementos tecnol&oacute;gicos";
					} else {
						echo "Consultas de los elementos tecnol&oacute;gicos";
					}
				?></a></li>
				<li class="active"><?php
					if($esAdministradorOntologia) {
						echo "Instancia de sistema operativo";
					} else {
						echo "Sistema operativo";
					}
				?></li>
			</ol>
			
			<ul class="nav nav-tabs" role="tablist">
				<li><a href="sistemaOperativo.php?accion=insertar">Insertar</a></li>
				<li><a href="sistemaOperativo.php?accion=Buscar">Consultar</a></li>
				<li class="active"><a href="#">Ver detalles</a></li>
			</ul>
		</div>
		
		<?php include( SIGECOST_PATH_VISTA . '/mensajes.php');?>
		
		<div class="container">
		
			<div class="page-header">
				<h1>Instancia de sistema operativo</h1>
			</div>
			
			<form id="formSistemaOperativo" class="form-horizontal" role="form" method="post" action="sistemaOperativo.php">
				<div style="display:none;">
					<input type="hidden" name="accion" value="">
					<input type="hidden" name="iri" value="<?php echo $sistemaOperativo->getIri() ?>">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Nombre del sistema operativo:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $sistemaOperativo != null ? $sistemaOperativo->getNombre() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="marca">Versi&oacute;n del sistema operativo:</label>
					<div class="col-sm-5">
						<p class="form-control-static"><?php echo $sistemaOperativo != null ? $sistemaOperativo->getVersion() : "" ?></p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-primary" onclick="setAccion('modificar');">Modificar</button>
						<button type="button" class="btn btn-primary" onclick="eliminarInstancia('formSistemaOperativo');">Eliminar</button>
					</div>
				</div>
			</form>
			
		</div>
		
		<?php require ( SIGECOST_PATH_VISTA . '/general/footer.php' ); ?>
			
	</body>

</html>