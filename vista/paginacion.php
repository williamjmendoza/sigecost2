<?php
	$formPaginacion = $GLOBALS['SigecostRequestVars']['formPaginacion'];
	$paginacion = $formPaginacion->getPaginacion();
	
	// <li class="disabled"><a href="#">&laquo;</a></li>
?>
<ul class="pagination pagination-sm">
	<li class="disabled"><a href="#">&laquo;</a></li>
	<?php
		for($i = 1; $i <= $paginacion->getTotalPaginas(); $i++)
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
		if ($i <= $paginacion->getTotalPaginas())
		{
	?>
		<li><a href="<?php echo $paginacion->getUrlObjetivo()  . "&pag=" . $i ?>">&raquo;</a></li>
	<?php
		} else {
	?>
		<li class="disabled"><a href="#">&raquo;</a></li>
	<?php	
		}
	?>
</ul>
