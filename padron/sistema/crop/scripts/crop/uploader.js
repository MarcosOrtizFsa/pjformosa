var session_archivo_name=0;
function subir_imagen_modulo()
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_carga');
	id.innerHTML='<font color=#000000><img src="../mipanel/templates/media/ajax-loader-small.gif" border="0" align="absmiddle"> Aguarda! cargando la imagen...</font>';
	session_archivo_name=1;
	if (browserName=="Microsoft Internet Explorer")
 	{
		abm.submit();
	}
	else
	{
		abm = document.getElementById('abm');
		abm.submit();
	}
}

function resultado_subir_imagen_modulo(mensaje)
{
	id = document.getElementById('estado_carga');
	if (mensaje=="Ok")
	{
		//id.innerHTML='<input name="Guardar" hspace="40" type="button" class="btn btn-large btn-inverse" onClick="window.location.replace(\'\')"; value="Subir Foto">';
	location.reload(true)
	session_archivo_name=2;
	}
	else
	if (mensaje=="null_size")
	{
id.innerHTML='<b><font color=red><img src="../mipanel/templates/media/iconos/icon-del.gif" border="0" align="absmiddle"> ERROR!</b> Esta foto es muy pesada. Maximo: 2 MB.</font>';
		session_archivo_name=3;
	}
	else
	if (mensaje=="null_type")
	{
id.innerHTML='<b><font color=red><img src="../mipanel/templates/media/iconos/icon-del.gif" border="0" align="absmiddle"> ERROR!</b> Sólo archivos de tipo JPG.</font>';
		session_archivo_name=3;
	}
	else
	if (mensaje=="null_caracter")
	{
id.innerHTML='<b><font color=red><img src="../mipanel/templates/media/iconos/icon-del.gif" border="0" align="absmiddle"> ERROR!</b> Nombre de archivo invalido.</font>';
		session_archivo_name=3;
	}
	else
	{
		id.innerHTML='<b><font color=red><img src="../mipanel/templates/media/iconos/icon-del.gif" border="0" align="absmiddle"> ERROR! No se pudo cragar esta foto...</font></b>';
		session_archivo_name=3;
	}
}