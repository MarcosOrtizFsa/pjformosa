
function down_multiple_archivos(url_exito,id_exito,vars_dow){	
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

function respuesta_down_multiple_archivos(exito,msghtml,url_exito,id_exito,vars_dow){
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
	if  (exito=='Exito:')
	{
	alert('Importación completada. '+msghtml);
	cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');
	}
	else
	{
		cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
	}

	
}

