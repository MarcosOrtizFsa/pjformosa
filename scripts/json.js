


function cargar_info_externo(vars)
{
	const resultado_dinamico = 	document.querySelector('#resultado_dinamico');
	var ajax = nuevo_ajax(); 
	ajax.open("POST", "https://equipoebersolis.com.ar/notif/php/json_api.php",true);
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
				var cadena = ``;			
				
				
				for(var i = 0; i < data.length; i++) 
				{		
					
					cadena += `	<div class="col-sm-4 col-md-4 col-xl-4" style="cursor:pointer;" onclick="location.href='info/${data[i].id_system_10}/${data[i].titulo_amigable}'">`
					cadena += `		<div class="card" style=" margin-bottom:10px;">`
					cadena += `			 ${data[i].galeria_fotos}`
					cadena += `			 <div class="card-body">`
					cadena += `					<h2 class="card-title">${data[i].system_10_titulo}</h2>`
					cadena += `					<h5>${data[i].system_10_bajada}</h5>`
					cadena += `			 </div>`
					cadena += `		</div>`
					cadena += `	</div>`
					
				}
						
				resultado_dinamico.innerHTML = cadena;			
				
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				var ok = confirm('Se encontr\u00f3 un ERROR ' + ajax.status + '!\nQuiere notificar esto ahora?')
				if (ok)
				{						
					reportar_error(ajax.status,1,url);
				}
				else
				{
					reportar_error(ajax.status,0,url);
				}
			}
		}	
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}



function cargar_info_externo_completo(rela_system_10)
{
	const resultado_dinamico = 	document.querySelector('#resultado_dinamico');
	var ajax = nuevo_ajax(); 
	ajax.open("POST", "https://equipoebersolis.com.ar/notif/php/json_api.php",true);
	var vars = "rela_system_10="+rela_system_10;	
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
				var cadena = ``;				
					
				cadena += `		<div class="col-sm-4 col-md-4 col-xl-4">`
				cadena += `			  <ul class="list-group list-group-flush">`
				cadena += `				<li class="list-group-item">`
				cadena += `					<i>${data[0].system_10_volanta}</i>`
				cadena += `				</li>`
				cadena += `			  </ul>`
				cadena += `		<div class="card" style=" margin-bottom:10px;">`
				cadena += `			 ${data[0].galeria_fotos}`
				cadena += `			 <div class="card-body">`
				cadena += `					<h2 class="card-title">${data[0].system_10_titulo}</h2>`
				cadena += `					<h5>${data[0].system_10_bajada}</h5><br>`
				cadena += `					<p>${data[0].system_10_texto}</p>`
				cadena += `			 </div>`
				cadena += `		</div>`
			
				resultado_dinamico.innerHTML = cadena;			
				
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				var ok = confirm('Se encontr\u00f3 un ERROR ' + ajax.status + '!\nQuiere notificar esto ahora?')
				if (ok)
				{						
					reportar_error(ajax.status,1,url);
				}
				else
				{
					reportar_error(ajax.status,0,url);
				}
			}
		}	
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}



