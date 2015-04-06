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

function ingresar() {
	
	if ( $.trim($('#contrasena').val()) != '')
		$('#contrasenaCod').val(hex_md5($.trim($('#contrasena').val())));
	
	$('#formIngreso').submit();
	
}

function validarContrasenaUsuario()
{
	var contrasena = $.trim($('#contrasenaUsuario').val());
	var contrasenaConfirmacion = $.trim($('#contrasenaConfirmacionUsuario').val());
	
	if ( contrasena != '')
		$('#contrasenaCodUsuario').val(hex_md5(contrasena));
	
	if ( contrasenaConfirmacion != '')
		$('#contrasenaConfirmacionCodUsuario').val(hex_md5(contrasenaConfirmacion));
}

function guardarUsuario(idForm)
{
	validarContrasenaUsuario();
	$('#'+idForm+'').submit();
}