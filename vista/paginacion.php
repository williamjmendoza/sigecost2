<?php
	$formPaginacion = $GLOBALS['SigecostRequestVars']['formPaginacion'];
	$paginacion = $formPaginacion->getPaginacion();
	
	$paginaInicial = ($paginaInicial = $paginacion->getPaginaActual() - intval(floor($paginacion->getTamanoVentana()/2),10)) < 1 ? 1 : $paginaInicial;
	
	$paginaFinal = $paginaFinal = $paginaInicial + $paginacion->getTamanoVentana() - 1;
	$paginaFinal = ($paginaFinal > $paginacion->getTotalPaginas()) ? $paginacion->getTotalPaginas() : $paginaFinal;
	
	$paginaInicial = (($paginaFinal - $paginaInicial + 1) < $paginacion->getTamanoVentana())
		? $paginaFinal - $paginacion->getTamanoVentana() + 1 : $paginaInicial;
	
	$paginaInicial = ($paginaInicial < 1) ? 1 : $paginaInicial;
	
?>
<div class="text-center">
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
			if($paginaInicial > 1)
			{
		?>
		<li>
			<a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=1" ?>">1</a>
		</li>
		<?php
			}
			if($paginaInicial > 2)
			{
				if ($paginaInicial == 3){
		?>
		<li>
			<a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=2" ?>">2</a>
		</li>
		<?php
				} else {
		?>
		<li class="disabled"><a href="#">...</a></li>
		<?php			
				}
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
			
			if($paginaFinal < ($paginacion->getTotalPaginas()-1) )
			{
				if($paginaFinal == ($paginacion->getTotalPaginas()-2))
				{
		?>
		<li>
			<a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=" . ($paginacion->getTotalPaginas()-1) ?>">
				<?php echo ($paginacion->getTotalPaginas()-1) ?>
			</a>
		</li>
		<?php
				} else {
		?>
		<li class="disabled"><a href="#">...</a></li>
		<?php
				}
			}
			
			if($paginaFinal < $paginacion->getTotalPaginas())
			{
		?>
		<li>
			<a href="<?php echo $paginacion->getUrlObjetivo() . "&pag=" . $paginacion->getTotalPaginas() ?>"><?php echo $paginacion->getTotalPaginas() ?></a>
		</li>
		<?php
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
</div>