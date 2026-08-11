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
        var contenido, preloader;
        id = document.getElementById(id);
		$.blockUI(); // abro blockUI para ver la precarga mientras ejecuto el scrips. Y luego lo cierro en el scrips carga_interna con $.unblockUI

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
		//ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
						//document.getElementById("cargando").className="show";
						 $.unblockUI(); // cierro el $.unblockUI que lo abri en guardar_ver
                }else if(ajax.readyState==4){
					 $.unblockUI(); // cierro el $.unblockUI que lo abri en guardar_ver
                        if(ajax.status==200){
                                
								
                                id.innerHTML=ajax.responseText; 
                                $.unblockUI(); // cierro el $.unblockUI que lo abri en guardar_ver
								
                        }else if(ajax.status==404){
								//document.getElementById("cargando").className="hide";
                                id.innerHTML = "La p\u00e1gina no existe";
                        }else{
                                //mostramos el posible error
								//document.getElementById("cargando").className="hide";
                                id.innerHTML = "Error:".ajax.status; 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
/*		ajax.setRequestHeader("Content-length", vars.length);
      	ajax.setRequestHeader("Connection", "close");*/
		ajax.send(vars)
}


function cargar_solo(url,id){
        var contenido, preloader;
        id = document.getElementById(id);

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true); 
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
                                id.innerHTML = ajax.responseText; 
                        }else if(ajax.status==404){
                                id.innerHTML = "La p\u00e1gina no existe";
                        }else{
                                id.innerHTML = "Error:".ajax.status; 
                        }
                }
        }
        ajax.send(null);
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

function cargar_get(url,id){
        var contenido, preloader;
        id = document.getElementById(id);

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("GET", url,true); 
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
                                id.innerHTML = ajax.responseText; 
                        }else if(ajax.status==404){
                                id.innerHTML = "La p\u00e1gina no existe";
                        }else{
                                id.innerHTML = "Error:".ajax.status; 
                        }
                }
        }
        ajax.send(null);
}

function cargar_get_preloader(url,id){
        var contenido, preloader;
        id = document.getElementById(id);

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("GET", url,true); 
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
				id.innerHTML = '<img src="loading.gif" width="400" height="400">';
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
                                id.innerHTML = ajax.responseText; 
                        }else if(ajax.status==404){
                                id.innerHTML = "La p\u00e1gina no existe";
                        }else{
                                id.innerHTML = "Error:".ajax.status; 
                        }
                }
        }
        ajax.send(null);
}




function ejecutar_get(url){
        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("GET", url,true); 
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
                                //ajax.responseText; 
								//alert(ajax.responseText);
                        }else if(ajax.status==404){
                                //id.innerHTML = "La p\u00e1gina no existe";
								alert('Error: La p\u00e1gina no existe');
                        }else{
                                alert("Error:".ajax.status); 
                        }
                }
        }
        ajax.send(null);
}




function ejecutar_post(url,vars){

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
	                        //alert('ok:' + ajax.responseText);
						}else if(ajax.status==404){
							alert('Error: La p\u00e1gina no existe');
    					}else{
                            alert("Error:".ajax.status); 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
/*		ajax.setRequestHeader("Content-length", vars.length);
      	ajax.setRequestHeader("Connection", "close");*/
		ajax.send(vars)
}






function guardar_mostrar(url,vars,url_exito,id,vars_exito){

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
	                        //alert('ok:' + ajax.responseText);
							
							var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
							var respuesta=respuesta_mostrar.substring(0,6);
							var respuesta_sinerror=respuesta_mostrar.substring(7,150);
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
								showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...',respuesta_mostrar,'success','1');
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
/*		ajax.setRequestHeader("Content-length", vars.length);
      	ajax.setRequestHeader("Connection", "close");*/
		ajax.send(vars)
}



function guardar_callback(url,vars,url_exito,id,vars_exito){

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
	                        //alert('ok:' + ajax.responseText);
			
								cargar_post(url_exito,id,vars_exito);
						
						}else if(ajax.status==404){
							alert('Error: La p\u00e1gina no existe');
    					}else{
                            alert("Error:".ajax.status); 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
/*		ajax.setRequestHeader("Content-length", vars.length);
      	ajax.setRequestHeader("Connection", "close");*/
		ajax.send(vars)
}



function guardar_mostrar_reflejar(url,vars,url_exito,id,vars_exito,url_ref,id_ref,vars_ref){

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
	                        //alert('ok:' + ajax.responseText);
							
							var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
							var respuesta=respuesta_mostrar.substring(0,6);
							var respuesta_sinerror=respuesta_mostrar.substring(7,150);
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
								showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...',respuesta_mostrar,'success','1');
								cargar_post(url_exito,id,vars_exito);
								cargar_post(url_ref,id_ref,vars_ref);
								
							}
							
						}else if(ajax.status==404){
							alert('Error: La p\u00e1gina no existe');
    					}else{
                            alert("Error:".ajax.status);	
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
/*		ajax.setRequestHeader("Content-length", vars.length);
      	ajax.setRequestHeader("Connection", "close");*/
		ajax.send(vars)
}

function eliminar_mostrar(url,vars,url_exito,id,vars_exito,msj,atx)
{
	if (atx=="SI")
	{
		 var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){ 
                }else if(ajax.readyState==4){
                        if(ajax.status==200){

								cargar_post(url_exito,id,vars_exito);

						}else if(ajax.status==404){
							alert('Error: La página no existe');
    					}else{
                            alert("Error:".ajax.status); 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
		showDialog('Aguarde...','Eliminado...','error','0');		
	}
	else
	if (atx=="NO")
	{
		showDialog('Aguarde...','','error','0');
	}
	else
	{
		showDialog('<img src="../image/iconos/ham.png"  border="0" align="absmiddle"> Confirme solicitud!',''+msj+'<br><br><div align="center"><a  href="javascript:;" onClick="eliminar_mostrar(\''+url+'\',\''+vars+'\', \''+url_exito+'\',\''+id+'\',\''+vars_exito+'\',\'\',\'SI\');" class="confir"> SI </a> <a  href="javascript:;" onClick="eliminar_mostrar(\''+url+'\',\''+vars+'\', \''+url_exito+'\',\''+id+'\',\''+vars_exito+'\',\'\',\'NO\');" class="confir">NO</a></div>','error');
		
	}	
}


function eliminar_mostrar_reflejar(url,vars,url_exito,id,vars_exito,url_ref,id_ref,vars_ref,msj,atx)
{
	if (atx=="SI")
	{
		 var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
			if(ajax.readyState==1){ 
			}else if(ajax.readyState==4){
				if(ajax.status==200){

						cargar_post(url_exito,id,vars_exito);
						cargar_post(url_ref,id_ref,vars_ref);

				}else if(ajax.status==404){
					alert('Error: La página no existe');
				}else{
					alert("Error:".ajax.status); 
				}
			}
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
		showDialog('Aguarde...','Eliminado...','error','0');
	}
	else
	if (atx=="NO")
	{
		showDialog('Aguarde...','','error','0');
	}
	else
	{
		showDialog('<img src="../image/iconos/ham.png"  border="0" align="absmiddle"> Confirme solicitud!',''+msj+'<br><br><div align="center"><a  href="javascript:;" onClick="eliminar_mostrar_reflejar(\''+url+'\',\''+vars+'\', \''+url_exito+'\', \''+id+'\',\''+vars_exito+'\', \''+url_ref+'\', \''+id_ref+'\',\''+vars_ref+'\', \'\',\'SI\');" class="confir"> SI </a> <a  href="javascript:;" onClick="eliminar_mostrar(\''+url+'\',\''+vars+'\', \''+url_exito+'\',\''+id+'\',\''+vars_exito+'\',\'\',\'NO\');" class="confir">NO</a></div>','error');
	}
}


function consultar_ejecutar(url,vars,url_exito,id,vars_exito,msj,atx)
{
//creamos el objeto XMLHttpRequest
//var answer = confirm (msj);
	if (atx=="SI")
	{
		 var ajax=nuevo_ajax(); 
		 //$.blockUI(); // abro blockUI para ver la precarga mientras ejecuto el scrips. Y luego lo cierro en el scrips carga_interna con $.unblockUI

        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){ 
                }else if(ajax.readyState==4){
                        if(ajax.status==200){

								cargar_post(url_exito,id,vars_exito);

						}else if(ajax.status==404){
							alert('Error: La página no existe');
    					}else{
                            alert("Error:".ajax.status); 
                        }
                }
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
		showDialog('Aguarde...','Ejecutando...','success','0');	
	}
	else
	if (atx=="NO")
	{
		showDialog('Aguarde...','','success','0');
	}
	else
	{
		showDialog('<img src="../image/iconos/ham.png"  border="0" align="absmiddle"> Confirme solicitud!',''+msj+'<br><br><div align="center"><a  href="javascript:;" onClick="consultar_ejecutar(\''+url+'\', \''+vars+'\', \''+url_exito+'\', \''+id+'\', \''+vars_exito+'\', \'\', \'SI\');" class="confir"> SI </a> <a  href="javascript:;" onClick="consultar_ejecutar(\''+url+'\',\''+vars+'\', \''+url_exito+'\',\''+id+'\', \''+vars_exito+'\', \'\', \'NO\');" class="confir">NO</a></div>','success');
	}
}


function activar_desactivar_mostrar(url,vars,url_exito,id,vars_exito,msj)
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


function solo_aceptar(url,vars,url_exito,id,vars_exito,msj,montog,fechag,atx){
	if(montog=="" || fechag==""){
	showDialog('<img src="../image/iconos/ups.png"  border="0" align="absmiddle"> Cuidado...','Faltan datos...','warning');
	}
	else
	{
		if (atx=="NO")
		{
			showDialog('Aguarde...','','success','0');
		}
		else
		{
			showDialog('<img src="../image/iconos/mal.png"  border="0" align="absmiddle"> Acci\u00f3n denegada...',''+msj+'<br><br><div align="center"> <a  href="javascript:;" onClick="solo_aceptar(\''+url+'\',\''+vars+'\', \''+url_exito+'\',\''+id+'\', \''+vars_exito+'\', \'\', \''+montog+'\', \''+fechag+'\', \'NO\');" class="confir">ACEPTAR</a></div>','success');	
		}
	}
}





function guardar_call_home(url,vars){

        //creamos el objeto XMLHttpRequest
        var ajax=nuevo_ajax(); 
        //peticionamos los datos, le damos la url enviada desde el link
        ajax.open("POST", url,true);
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1){
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
	                        //alert('ok:' + ajax.responseText);
			
								
						cargar_content('php/home.php');
						
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

