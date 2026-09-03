
function down_multiple_archivos(url_exito,id_exito,vars_dow)
{	
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');
	id.innerHTML='Cargando...';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'php/up_doc.php?'+ vars_dow, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg
			
			var exito=msg.substring(0,6);
			var msghtml=msg.substring(7,150);
			parent.respuesta_down_multiple_archivos(exito,msghtml,url_exito,id_exito,vars_dow)
		});
}

function respuesta_down_multiple_archivos(exito,msghtml,url_exito,id_exito,vars_dow)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');

	if (exito=='Fatal!')
	{								
	alert('Fatal! '+msghtml);
	}
	else
	if  (exito=='Error:')
	{
	alert('Error: '+msghtml);
	}
	else
	{
		cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
	}
}



function down_files_padron(vars)
{	
	var browserName =	navigator.appName;
	id = document.getElementById('estado_de_carga');
	id.innerHTML =		'Cargando...';
	session_archivo_name=1;
	
		var archivos = 	document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = 	archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = 	new FormData();
		
		for(i=0; i<archivo.length; i++)
		{
			archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'php/up_extractores.php?'+vars, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg)
			{		
				var exito=msg.substring(0,6);
				var msghtml=msg.substring(7,150);
				parent.respuesta_down_padron(exito,msghtml)	
			});
}
function respuesta_down_padron(exito,msghtml)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');

	if (exito=='Fatal!')
	{								
		id.innerHTML =	'Fatal: '+msghtml+'';
	}
	else
	if  (exito=='Error:')
	{
		id.innerHTML =	'Error: '+msghtml+'';
	}
	else
	{
		id.innerHTML =	'Listo...';
		location.reload();
	}
}


function funcion_extraer_csv(tipo,id_system_09,desde)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');
	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", 'php/json_actualizacion_padron.php',true);
	var vars = "id_system_09="+id_system_09+"&system_09_tipo="+tipo+"&desde="+desde;
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1)
		{}
		else 
		if(ajax.readyState==4)
		{
			if(ajax.status==200)
			{
			

				var respuesta = JSON.parse(ajax.responseText);
				const data = respuesta.data;
				var cadena = '';
				if (`${data[0].resultado}` == 'go')
				{
					cadena += `		${data[0].progreso} `	
					funcion_extraer_csv(`${data[0].system_09_tipo}`,`${data[0].id_system_09}`,`${data[0].progreso}`);
				}
				else
				{
					alert('FIN...');
				}
				id.innerHTML =	cadena;
			
			
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Error: ' + ajax.status);
			}
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}



function funcion_extraer_csv_mesas_escuelas(tipo,id_system_09,desde)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');
	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", 'php/json_actualizacion_mesas_escuelas.php',true);
	var vars = "id_system_09="+id_system_09+"&system_09_tipo="+tipo+"&desde="+desde;
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1)
		{}
		else 
		if(ajax.readyState==4)
		{
			if(ajax.status==200)
			{
			

				var respuesta = JSON.parse(ajax.responseText);
				const data = respuesta.data;
				var cadena = '';
				if (`${data[0].resultado}` == 'go')
				{
					cadena += `		${data[0].progreso} `	
					funcion_extraer_csv_mesas_escuelas(`${data[0].system_09_tipo}`,`${data[0].id_system_09}`,`${data[0].progreso}`);
				}
				else
				{
					alert('FIN...');
				}
				id.innerHTML =	cadena;
			
			
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Error: ' + ajax.status);
			}
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}











function ejecutar_descarga_progreso(vars)
{
	const resultado_dinamico = 	document.querySelector('#resultado_dinamico');
	var ajax = nuevo_ajax(); 
	ajax.open("POST", "php/json_down.php",true);
	var vars = "progreso="+vars;	
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
				if (`${data[0].resultado}` != '0')
				{
					cadena += `		${data[0].resultado} `	
					ejecutar_descarga_progreso(`${data[0].progreso}`);
				}
				else
				{
					alert('FIN...');
				}
				resultado_dinamico.innerHTML = 	cadena;		
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Se encontr\u00f3 un ERROR ' + ajax.status + '!');
			}
		}	
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}




function down_tipo_clase(vars_dow)
{	
	var browserName =	navigator.appName;
	id = document.getElementById('estado_de_tipo');
	id.innerHTML =		'<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
	session_archivo_name=1;
	
		var archivos = 	document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = 	archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = 	new FormData();
		
		for(i=0; i<archivo.length; i++)
		{
			archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'php/up_extractores.php?'+ vars_dow, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg)
			{
				// traigo las respuestas
				var tipo =		msg.substring(0,1);
				var exito =		msg.substring(1,7);
				var name_archivo = 	msg.substring(8,150);
			
				
				if ( exito == 'Exito!' )
				{
					funcion_extraer_tipo_clase(name_archivo,0);
					//id.innerHTML =	'Listo. '+name_archivo;
				}
				else
				{
					id.innerHTML =	'Algo salio mal...';
				}
				
			});
}

function funcion_extraer_tipo_clase(name_archivo,progreso)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_tipo');
	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", 'php/json_tipo_clase.php',true);
	var vars = "name_archivo="+name_archivo+"&progreso="+progreso;
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1)
		{}
		else 
		if(ajax.readyState==4)
		{
			if(ajax.status==200)
			{
			

				var respuesta = JSON.parse(ajax.responseText);
				const data = respuesta.data;
				var cadena = '';
				if (`${data[0].resultado}` != '0')
				{
					cadena += `		${data[0].progreso} `	
					//funcion_extraer_tipo_clase(name_archivo,`${data[0].progreso}`);
				}
				else
				{
					alert('FIN...');
				}
				id.innerHTML =	cadena;
			
			
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Error: ' + ajax.status);
			}
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}



// ---------CARGA FLAYERS
function down_dni(url_exito,id_exito,vars_dow)
{	
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');
	id.innerHTML='Subiendo imagen...';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'php/up_dni.php?'+ vars_dow, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg
			
			var exito=msg.substring(0,6);
			var msghtml=msg.substring(7,150);
			parent.respuesta_down_dni(exito,msghtml,url_exito,id_exito,vars_dow)
		});
}

function respuesta_down_dni(exito,msghtml,url_exito,id_exito,vars_dow)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_de_carga');

	if (exito=='Fatal!')
	{								
	alert('Fatal! '+msghtml);
	}
	else
	if  (exito=='Error:')
	{
	alert('Error: '+msghtml);
	}
	else
	{
		cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
	}
}







function funcion_crear_tablas_fiscales(system_2002_mesa)
{
	var browserName=navigator.appName;
	id = document.getElementById('estado_ejecucion');
	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", 'php/json_crear_tabla_fiscales.php',true);
	var vars = "system_2002_mesa="+system_2002_mesa;
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1)
		{}
		else 
		if(ajax.readyState==4)
		{
			if(ajax.status==200)
			{
			

				var respuesta = JSON.parse(ajax.responseText);
				const data = respuesta.data;
				var cadena = '';
				if (`${data[0].system_2002_mesa}` != '')
				{
					cadena += `		${data[0].system_2002_mesa} `	
					funcion_crear_tablas_fiscales(`${data[0].system_2002_mesa}`);
				}
				else
				{
					alert('FIN...');
				}
				id.innerHTML =	cadena;
			
			
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Error: ' + ajax.status);
			}
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}



