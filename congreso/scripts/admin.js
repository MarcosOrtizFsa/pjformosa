function cargar_inicial(url_content){
	if (url_content!=''){	
		url_content = url_content.replace("%","&");
		cargar_get(url_content,'content_menu');
	}
}

function cargar_modulo(url)
{
	if (url!=''){
		$.blockUI(); // abro precarga
		cargar_post('modulos/root/php/home.php','content_seccion','');
	}
}
function acceso_denegado()
{	
	alert('No tiene acceso!');
}
function sin_permisos()
{	
	alert('No tiene permisos para esto...');
}
function restringido()
{	
	alert('No puede hacer esto aqu\u00ed...');
}

function validar_login(usu,cla,cuir)
{
	if (usu=="" || cla=="" )
	{
		alert("Ingrese su Usuario y Clave");
	}
	else
	{
		var ajax=nuevo_ajax(); 
		//peticionamos los datos, le damos la url enviada desde el link
		ajax.open("POST", "php/valida.php",true);
		var vars="usu="+ usu + "&cla=" + cla + "&cuir=" + cuir;
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
				}
				else 
				if(ajax.readyState==4){
						if(ajax.status==200){
							var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
							var tip=respuesta_mostrar.substring(0,1);
							var respu=respuesta_mostrar.substring(2,22);
							//alert(ajax.responseText);
							if (respu=='desconocido')
							{
									alert('Usuario o clave desconocidos');
									document.getElementById("cla").value = '';
									document.getElementById("usu").value = '';
							}
							else if (respu=='errorlog')
							{
									alert('Usuario o clave inv\u00e1lido');
									document.getElementById("cla").value = '';
									document.getElementById("usu").value = '';
							}
							else if (respu=='suspendido')
							{
									alert('Su cuenta fu\u00e9 suspendida');
									document.getElementById("cla").value = '';
							}
							else if (respu=='restringido')
							{
									alert('\u00c1rea restringida!');
									document.getElementById("cla").value = '';
							}
							else if (respu=='rechazado')
							{
									alert('Su cuenta fu\u00e9 rechazada');
									document.getElementById("cla").value = '';
							}
							else if (respu=='espera')
							{
									alert('Su cuenta a\u00fan no fue activada...');
									document.getElementById("cla").value = '';
							}
							else if (respu=='vencido')
							{
									alert('El v\u00ednculo ha expirado...');
									document.getElementById("cla").value = '';
							}
							else if (respu=='incompleto')
							{
									alert('Tus datos estan incompletos...');
									document.getElementById("cla").value = '';
							}
							else
							{
								window.location = "../";
							}

						}else if(ajax.status==404){
							alert('Error: No se encuent\u00f3 la p\u00e1gina');
						}else{
							alert("Error:" + ajax.status); 
						}
				}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
	}
}


function logout()
{
	var answer = confirm ("Cerrar Sesi\u00f3n?");
	if (answer)
	{
		window.location = "php/logout.php";
	}
}

function crack()
{
	window.location = "php/logout.php";
}

function acceptNum_valida(evt,usu,cla,time)
{
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
 if (evt.keyCode == 13)
	{
	validar_login(usu,cla,time); 
	}
}

function solo_enter(vars)
{	
	document.getElementById("variable_buscar").value = ''+vars+'';
	$(variable_buscar).focus();
}

function pinchar_enter(evt,url,id,vars)
{   
	var ajax=nuevo_ajax(); 
	if (evt.keyCode == 13)
	{

	 $.blockUI(); // abro blockUI para ver la precarga mientras ejecuto el scrips. Y luego lo cierro en el scrips carga_interna con $.unblockUI
	
	 cargar_post(url,id,vars);
	}
}

function validar_mail(cadena) {
	var a = cadena;
	var filter=/^[A-Za-z0-9_.]*@[A-Za-z0-9_]+.[A-Za-z0-9_.]+[A-za-z]$/;
	id = document.getElementById('msg_mail');	
	if (filter.test(a))
	{
		id.innerHTML='';
	}
	else
	{
		id.innerHTML='* E-mail...';
	}
}

function validar_cel(telcel)
{
	var a = telcel;
	var filter=/^[0-9]{5,12}/;
	id = document.getElementById('msg_cel');	
	if (filter.test(a))
	{
		id.innerHTML='OK';
	}
	else
	{
		id.innerHTML='?';
	}
}

function cambiar_modo()
{
	window.location = "php/crack_modo.php";
}

function abrir_popup(popup) 
{
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=370, height=500, top=10, left=20";
	window.open(popup,"",opciones);
}

function abrir_popup_g(popup_g) 
{
	var opciones2="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=250, left=250";
	window.open(popup_g,"",opciones2);
}

function desplegar(solappas)
{
	var menu = document.getElementById(solappas);
    if(menu.className == "desplegado")
	{
      menu.className = "ocultar_desplegado";
    }
    else
	{
      menu.className = "desplegado";
    }
}

function CuentaLetras(leng)
{
	var numleg = leng - 1;
	if ( (document.abm.system_11_mensaje.value.length) < leng )
	{
		document.abm.caracteres.value = numleg - document.abm.system_11_mensaje.value.length;
	}
}

function printcontent()
{
var a = window. open('','','scrollbars=yes,width=1200,height=560');

	a.document.open("text/html");
	a.document.write('<html><head><link rel="stylesheet" href="../css/reset.css" media="screen"/></head><body>');
	a.document.write(document.getElementById('content_print').innerHTML);
	a.document.write('</body></html>');
	a.document.close();
	a.print();
}


function doPrint(){
document.all.item("noprint").style.visibility='hidden' 
window.print()
document.all.item("noprint").style.visibility='visible'
}


function reportar_error(vars,notif,url_error)
{
        var ajax=nuevo_ajax(); 
        ajax.open("POST", 'php/reportes.php',true);
		var vars="error=" + vars + "&notif=" + notif + "&url_error=" + url_error;
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1)
				{}
				else 
				if(ajax.readyState==4)
				{
					if(ajax.status==200){
						
						var respuesta=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
						if  (respuesta=='1')
						{
							alert('Notificaci\u00f3n enviada...');
						}	
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


function funcion_salvar_permiso(vars)
{
        var ajax=nuevo_ajax(); 
        ajax.open("POST", 'modulos/control/php/_interfaz.php',true);
		var vars="nombre_funcion=salvar_permiso&"+vars;
        ajax.onreadystatechange=function(){
                if(ajax.readyState==1)
				{}
				else 
				if(ajax.readyState==4)
				{
					if(ajax.status==200){
						
						var respuesta=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
						if  (respuesta=='1')
						{
							alert('Notificaci\u00f3n enviada...');
						}	
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


function guardar_solo(url,vars)
{
        var ajax=nuevo_ajax(); 
        ajax.open("POST", url,true);		
        ajax.onreadystatechange=function()
		{		
			if(ajax.status==200)
			{	
				var respuesta_mostrar=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
				var respuesta=respuesta_mostrar.substring(0,6);
				var respuesta_sinerror=respuesta_mostrar.substring(7,150);

				if (respuesta=='Fatal!')
				{								
					alert('Fatal! '+respuesta_mostrar);
				}
				else
				if  (respuesta=='Error:')
				{
		
					alert('Error: '+respuesta_mostrar);
				}
				else
				{
					
				}						
			}
			else
			if(ajax.status==404)
			{
				alert('Error: La p\u00e1gina no existe');
			}
			else
			{
				alert('Status: '+ajax.status);
			}               
        }
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars)
}
