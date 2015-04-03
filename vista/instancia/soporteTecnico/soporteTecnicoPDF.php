<?php
	$titulo = $GLOBALS['SigecostRequestVars']['titulo'];
	$htmlElementos = $GLOBALS['SigecostRequestVars']['htmlElementos'];
	$instancia = $GLOBALS['SigecostRequestVars']['instancia'];
	$patron = $instancia != null ? $instancia->getPatron() : null;
?>
<style type="text/css">
	<!--
	table.page_header {width: 100%; border: none; background-color: #428bca; border-bottom: solid 1mm #2a6496; padding: 2mm 10mm 2mm 14mm; color: #FFF; font-weight: bold;}
	table.page_footer {width: 100%; border: none; background-color: #428bca; border-top: solid 1mm #2a6496; padding: 2mm 10mm 2mm 14mm; color: #FFF; font-weight: bold;}
	table.incidencia{border: solid 1px #5544DD;font-size: 12pt; margin: 0mm; width: 100%;padding: 5mm 10mm 0mm 14mm;}
	table.soloIncidencia{width: 100%; border-collapse: collapse;}
	table.incidencia td {padding: 1mm 2mm 1mm 2mm; vertical-align: top;}
	table.incidencia th {width: 100%; text-align: center; border: solid 1px #6d8af6; background: #a8b9f8; padding: 1mm 1mm 1mm 1mm; color: #FFFFFF; font-size: 7mm;}
	td.incidenciaLabel {text-align: right; font-weight: bold; width: 33%;}
	td.incidenciaTexto {width: 67%;}
	div.titulo {padding: 5mm 10mm 0mm 14mm; width: 100%;}
	h1 {text-align: justify; font-size: 7mm; margin: 0mm;}
	h2 {text-align: right; font-size: 5mm; color: #888888; margin: 0mm;}
	div.descripcion {margin: 0mm 10mm 0mm 14mm; padding: 5mm 0mm 2mm 0mm; border-bottom: 1px solid #eee;}
	div.contenido {padding: 0mm; width: 100%;}
    -->
</style>

<page backtop="70mm" backbottom="14mm" backleft="14mm" backright="10mm" style="font-size: 12pt">
			 
	<page_header>
		<table class="page_header">
			<tr>
				<td style="width: 50%; text-align: left">
					Soluci&oacute;n de incidencia de soporte t&eacute;cnico
				</td>
				<td style="width: 50%; text-align: right">
							SigecoST
				</td>
			</tr>
		</table>
 
		<div class="titulo"><h1><?php echo $titulo?></h1></div>
				
		<table class="incidencia soloIncidencia" align="center">
			<tbody>
				<?php echo $htmlElementos; ?>
			</tbody>
		</table>
		 
		<div class="descripcion">
			<h2>
				Descripci&oacute;n de la soluci&oacute;n
			</h2>
		</div>

	</page_header>
	
	<page_footer>
		<table class="page_footer">
			<tr>
				<td style="width: 80%; text-align: left;">
					Creada por
					<?php
						echo $patron != null
							? 	(	$patron->getUsuarioCreador() != null
									? $patron->getUsuarioCreador()->getNombre() . " " . $patron->getUsuarioCreador()->getApellido() : ""
								)
							: ""
					?>
					<?php echo $patron != null ? " el " . $patron->getFechaCreacion() : "" ?>
				</td>
				<td style="width: 20%; text-align: right;">
					p&aacute;g. [[page_cu]]/[[page_nb]]
				</td>
				
			</tr>
		</table>
	</page_footer>
	
	<div class="contenido">
		<?php echo $patron != null ? $patron->getSolucion() : "" ?>
	</div>

</page>