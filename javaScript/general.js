// Establece el input de acción, que indica que método del controlador será llamado 
function setAccion(accion) {
	$('input[type="hidden"][name="accion"]').val(accion);
}

function eliminarInstancia(formulario)
{
	if(confirm(pACUTE+"Est"+aACUTE+" seguro que desea eliminar la instancia?") == true) {
		setAccion('eliminar');
		$('#' + formulario).submit();
	}
}