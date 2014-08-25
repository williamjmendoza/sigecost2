<?php
	$formPaginacion = $GLOBALS['SigecostRequestVars']['formPaginacion'];
	$paginacion = $formPaginacion->getPaginacion();
	
	$paginaInicial = ($paginaInicial = $paginacion->getPaginaActual() - intval(floor($paginacion->getTamanoVentana()/2),10)) < 1 ? 1 : $paginaInicial;
	
	$paginaFinal = $paginaFinal = $paginaInicial + $paginacion->getTamanoVentana() - 1;
	$paginaFinal = ($paginaFinal > $paginacion->getTotalPaginas()) ? $paginacion->getTotalPaginas() : $paginaFinal;
	
	$paginaInicial = (($paginaFinal - $paginaInicial + 1) < $paginacion->getTamanoVentana())
		? $paginaFinal - $paginacion->getTamanoVentana() + 1 : $paginaInicial;
	
	$paginaInicial = ($paginaInicial < 1) ? 1 : $paginaInicial;
	
	// <li class="disabled"><a href="#">&laquo;</a></li>
?>
<ul class="pagination pagination-sm">

	<?php
		if ($paginacion->getPaginaActual() > 1) {
	?>
	<li><a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=" . ($paginacion->getPaginaActual() - 1) ?>">&laquo;</a></li>
	<?php
		}
		else {
	?>
	<li class="disabled"><a href="#">&laquo;</a></li>
	<?php	
		}
	?>
	
	<?php
		for($i = $paginaInicial; $i <= $paginaFinal; $i++)
		{
			if($i == $paginacion->getPaginaActual())
			{
	?>
	<li class="active"><a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=" . $i ?>">
		<?php echo $i ?> <span class="sr-only">(current)</span></a>
	</li>
	<?php

			} else {
	?>
	<li>
		<a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=" . $i ?>"><?php echo $i ?></a>
	</li>
	<?php
			}
		}
		
		if ($paginacion->getPaginaActual() < $paginacion->getTotalPaginas())
		{
	?>
		<li><a href="<?php echo $paginacion->getUrlObjetivo()  . "&pag=" . ($paginacion->getPaginaActual() + 1) ?>">&raquo;</a></li>
	<?php
		} else {
	?>
		<li class="disabled"><a href="#">&raquo;</a></li>
	<?php	
		}
	?>
</ul>