function api_json(url_api,id,vars)
{		
	id = document.getElementById(id);	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", url_api,true);
	var vars = vars;	
	
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1){}
		else
		if(ajax.readyState==4)
		{
			if(ajax.status==200)
			{					
				var respuesta = JSON.parse(ajax.responseText);
				const data = respuesta.data;
				
				var cadena = '';	
				cadena += `	<table width="100%" border="1px" cellpadding="0px" cellspacing="0">`
				cadena += `	<tr>`
				cadena += `		<td width="50%"> `   
				cadena += `		<b>${data[0].progreso}</b>`
				cadena += `		</td>`
				cadena += `		<td width="50%" class=" align-right">`
				cadena += `		<b>${data[0].resultado}</b>`
				cadena += `		</td>`
				cadena += `	</tr>`
				cadena += `</table>`
				id.innerHTML = cadena;			
				
			}
			else 
			if(ajax.status==404)
			{
				id.innerHTML = '';
				alert("La p\u00e1gina no existe");
			}
			else
			{
				id.innerHTML = '';
				alert('Error: ' + ajax.status);
			}
		}		
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}




