// JavaScript Document

/*
\u00e1 = á
\u00e9 = é
\u00ed = í
\u00f3 = ó
\u00fa = ú
\u00c1 = Á
\u00c9 = É
\u00cd = Í
\u00d3 = Ó
\u00da = Ú
\u00f1 = ñ
\u00d1 = Ñ 
*/


function nuevo_ajax(){
        var xmlhttp=false;
        try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");

        }catch(e){
                try{
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						
                }catch(E){
                        xmlhttp = false;
                }
        }

        if(!xmlhttp && typeof XMLHttpRequest!='undefined'){
                xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
}

function cargar_post(url,id,vars){

        id = document.getElementById(id);
        var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function()
		{
			if(ajax.readyState==1)
			{}
			else
			if(ajax.readyState==4)
			{
				$.unblockUI(); // cierro precraga
				if(ajax.status==200){	
					id.innerHTML=ajax.responseText; 	
				}
				else 
				if(ajax.status==404){
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
		ajax.send(vars)
}

function simple_post(vars){
        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", vars,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        //ajax.responseText; 
                	}
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars)
}


function guardar_mostrar(url,vars,url_exito,id,vars_exito)
{
        var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);
		$.blockUI(); // abro blockUI para ver la precarga mientras ejecuto el scrips. Y luego lo cierro en el scrips carga_interna con $.unblockUI

        ajax.onreadystatechange=function(){
                if(ajax.readyState==1)
				{}
				else 
				if(ajax.readyState==4)
				{
					if(ajax.status==200){
						
						var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
						var respuesta=respuesta_mostrar.substring(0,6);
						var respuesta_sinerror=respuesta_mostrar.substring(7,150);
						//alert(respuesta);
						if (respuesta=='Fatal!')
						{								
							$.unblockUI(); // cierro precraga
							var ok = confirm(respuesta_mostrar+'\nQuiere notificar esto ahora?')
							if (ok)
							{						
								reportar_error('Fatal!',1,url);
							}
							else
							{
								reportar_error('Fatal!',0,url);
							}
						}
						else
						if  (respuesta=='Error:')
						{
							$.unblockUI(); // cierro precraga
							alert(respuesta_mostrar);
						}
						else
						if  (respuesta=='Infor:')
						{
							cargar_post(url_exito,id,vars_exito);
							alert(respuesta_mostrar);
							
						}
						else
						{
							cargar_post(url_exito,id,vars_exito);
						}
						
					}
					else
					if(ajax.status==404)
					{
						$.unblockUI(); // cierro precraga
						alert('Error: La p\u00e1gina no existe');
					}
					else
					{
						$.unblockUI(); // cierro precraga
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
		ajax.send(vars)
}


function eliminar_mostrar(url,vars,url_exito,id,vars_exito,msj)
{
	var confirmar = confirm (msj);
	if (confirmar)
	{
		$.blockUI(); // abro precargador

		var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function()
		{
			if(ajax.readyState==1)
			{}
			else 
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{
					cargar_post(url_exito,id,vars_exito);
				}
				else
				if(ajax.status==404)
				{
					$.unblockUI(); // cierro precraga
					alert('Error: La p\u00e1gina no existe');
				}
				else
				{
					$.unblockUI(); // cierro precraga
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
}


function eliminar_mostrar_reflejar(url,vars,url_exito,id,vars_exito,url_ref,id_ref,vars_ref,msj,atx)
{}


function consultar_ejecutar(url,vars,url_exito,id,vars_exito,msj,atx)
{}


function on_off(url,vars,url_exito,id,vars_exito,msj)
{
//creamos el objeto XMLHttpRequest
	var answer = confirm (msj);
	if (answer)
	{
		    var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){ 
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
							
							
							var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
							var respuesta=respuesta_mostrar.substring(0,6);
							var respuesta_sinerror=respuesta_mostrar.substring(7,95);
							//alert(respuesta);
							if (respuesta=='Fatal!')
							{								
								//alert(respuesta_mostrar);
								showDialog('<img src="../image/iconos/dee.png"  border="0" align="absmiddle"> Contacte al Soporte t\u00e9cnico.',respuesta_sinerror,'error');
							}
							else
							if  (respuesta=='Error:')
							{
								//alert(respuesta_mostrar);
								showDialog('<img src="../image/iconos/ham.png"  border="0" align="absmiddle"> Cuidado...',respuesta_sinerror,'warning');
							}
							else
							if  (respuesta=='Listo!')
							{
								//alert(respuesta_mostrar);
								showDialog('<img src="../image/iconos/hapy.png"  border="0" align="absmiddle"> Listo!',respuesta_sinerror,'success');
							}
							else
							{
								//alert(respuesta_mostrar);
								//showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...',respuesta_mostrar,'success','1');
								cargar_post(url_exito,id,vars_exito);
							}
							
							
						}else if(ajax.status==404){
							alert('Error: La p\u00e1gina no existe');
    					}else{
                            alert("Error:".ajax.status); 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars)
	}
}



function guardar_vars(url,vars){	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", url,true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}

function guardar_vars_refresh(url,vars){	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", url,true);	
	window.location = "../";
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}