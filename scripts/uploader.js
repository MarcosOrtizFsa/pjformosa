var session_archivo_name=0;

function lista_precios(url_exito,id_exito,vars_dow){	
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado_lista');
	id.innerHTML='Cargando...';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos3");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'php/up_lista.php?'+ vars_dow, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg

			var exito=msg.substring(0,1); //0 o 1
			var msghtml=msg.substring(1,999);	
			parent.lista_precios_respuesta(exito,msghtml,url_exito,id_exito,vars_dow)
		});
	}

function lista_precios_respuesta(exito,msghtml,url_exito,id_exito,vars_dow){
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado_lista');

	if (exito=='1')
	{
	alert(msghtml);	
	}
	cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
}



function multiple_archivos(url_exito,id_exito,vars_dow){	
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado');
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

			var exito=msg.substring(0,1); //0 o 1
			var msghtml=msg.substring(1,999);	
			parent.multiple_archivos_respuesta(exito,msghtml,url_exito,id_exito,vars_dow)
		});
	}

function multiple_archivos_respuesta(exito,msghtml,url_exito,id_exito,vars_dow){
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado');

	if (exito=='1')
	{
	alert(msghtml);	
	}
	cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
}


// CARGO CATALOGO MULTIPLE IMAGENES
function multiple_clasificado(url_exito,id_exito,vars_dow){	
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado_clasificados');
	id.innerHTML='Cargando...';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'modulos/clasificados/php/up_doc.php?'+ vars_dow, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
			}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg

			var exito=msg.substring(0,1); //0 o 1
			var msghtml=msg.substring(1,999);	
			parent.multiple_clasificado_respuesta(exito,msghtml,url_exito,id_exito,vars_dow)
		});
	}

function multiple_clasificado_respuesta(exito,msghtml,url_exito,id_exito,vars_dow){
	var browserName=navigator.appName;
	id = document.getElementById('multiple_archivos_estado_clasificados');

	if (exito=='1')
	{
	alert(msghtml);	
	}
	cargar_post(''+url_exito+'',''+id_exito+'',''+vars_dow+'');	
}







function SubirFotosMultiples(rela_system_09a,system_09_checked){	
	var browserName=navigator.appName;
	id = document.getElementById('estado_carga_multiple');
	id.innerHTML='<div class="loaderi" id="loaderi"></div> Cargando...';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		$.ajax({
			url:'modulos/ima/php/carga_multiple.php?rela_system_09a='+rela_system_09a+'&system_09_checked='+system_09_checked, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
		}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg

			var exito=msg.substring(0,1);
			var msghtml=msg.substring(2,999);	
			parent.SubirFotosMultiplesRespuesta(exito,msghtml,system_09_checked)
		});
	}

function SubirFotosMultiplesRespuesta(exito,msghtml,system_09_checked){
	//$('.estado_mensage').html(msghtml);//A el div con la clase msg, le insertamos el mensaje en formato  thml
	$('.estado_mensage').show('show');//Mostramos el div.
	id = document.getElementById('estado_carga_multiple');

	if (exito=='1')
	{
	alert(msghtml);	
	}
	cargar_post('modulos/ima/php/home_abm.php','content_seccion','nombre_funcion=crear_album&system_09_checked='+system_09_checked+'');	
}





// CARGO Camara
function SubirPerfil_i(id_system_01,id_system_03,rela_system_04,url_exito){	
	var browserName=navigator.appName;
	id = document.getElementById('estado_carga_camara');
	id.innerHTML='<div class="loaderi" id="loaderi"></div>';
	session_archivo_name=1;
	
		var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
		var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
		var archivos = new FormData();
		for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
		}

		
		$.ajax({
			url:'modulos/control/php/carga_multiple.php?rela_system_04='+rela_system_04, //Url a donde la enviaremos
			type:'POST', //Metodo que usaremos
			contentType:false, //Debe estar en false para que pase el objeto sin procesar
			data:archivos, //Le pasamos el objeto que creamos con los archivos
			processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
			cache:false //Para que el formulario no guarde cache
		}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg

			var checked=msg.substring(0,10); //1505759209
			var error=msg.substring(10,11); //1o0	
			var msghtml=msg.substring(11,999);	
			parent.MensajePerfil_i(checked,error,msghtml,url_exito,id_system_01,id_system_03)
		});
	}

function MensajePerfil_i(checked,error,msghtml,url_exito,id_system_01,id_system_03){
	//$('.estado_mensage').html(msghtml);//A el div con la clase msg, le insertamos el mensaje en formato  thml
	$('.estado_mensage').show('show');//Mostramos el div.
	id = document.getElementById('estado_carga_camara');

	if (error!='0')
	{
	alert(msghtml);	
	}
	else
	{
		cargar_post(''+url_exito+'','content_seccion','id_system_01='+id_system_01+'&id_system_03='+id_system_03+'');
	}
	
}

