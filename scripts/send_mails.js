var session_archivo_name=0;


function ejecutando_modulo()
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_send');
	id.innerHTML='<img src="templates/modulos/tiramail/icons/send_mail.gif" border="0" align="absmiddle"><br>ENVIANDO... Puede demorar algunos minutos...';
	if (browserName=="Microsoft Internet Explorer")
 	{
		id = document.getElementById('estado_send');
		abm.submit();
	}
	else
	{
		id = document.getElementById('estado_send');
		abm = document.getElementById('abm');
		abm.submit();
	}
}




function resultado_send_mails(mensaje)
{
	id = document.getElementById('estado_send');
	if (mensaje=="Ok")
	{
		id.innerHTML='<img src="../image/iconos/hapy.png"> ENVIO FINALIZADO!<br><input name="Cancelar" type="button" hspace="40" class="button" onClick="cargar_post(\'templates/modulos/tiramail/php/ver_diagnostico.php\',\'content_seccion\',\'\')" value="DIAGNOSTICO DEL ENVIO"> ';
		alert('Envios Finalizado');
	}
	else
	if (mensaje=="Ojo1")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Faltan seleccionar una mascara.<br><input name="Guardar" hspace="40" type="button" class="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	if (mensaje=="Ojo2")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Falta seleccionar un grupo.<br><input name="Guardar" hspace="40" type="button" class="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	if (mensaje=="Ojo3")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Falta el asunto...<br><input name="Guardar" hspace="40" type="button" class="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	if (mensaje=="Ojo4")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Falta el mensaje...<br><input name="Guardar" hspace="40" type="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	if (mensaje=="Ojo5")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Faltan el newsletter...</font></b><br><input name="Guardar" hspace="40" type="button" class="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	if (mensaje=="Ojo6")
	{
		id.innerHTML='<img src="../image/iconos/ham.png"> ATENCION! Falta el mail...</font></b><br><input name="Guardar" hspace="40" type="button" class="button" onClick="ejecutando_modulo();" value="EJECUTAR ENVIOS" class="boton_send">';
	}
	else
	{
		id.innerHTML='<img src="../image/iconos/dee.png"> ERROR! No se pudo completar el envio...<br><input name="Cancelar" type="button" hspace="40" class="button" onClick="cargar_post(\'templates/modulos/tiramail/php/ver_mails.php\',\'content_seccion\',\'\')" value="Cancelar">';
	}
}