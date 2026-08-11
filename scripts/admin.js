/*VERIFICO ANCHO Y ALTO DE PANTALLA DEL CLIENTE PARA GURDARLO EN BD*/
//ejecutar_get('mipanel/php/contador_enter.php?ancho='+screen.width+'&alto='+screen.height+'');

function cargar_inicial(url_content){
	if (url_content!=''){	
		//url_content = url_content.replace("%","&");
		cargar_get(url_content,'content_menu');
	}
}
function cargar_content(url_content){
	if (url_content!=''){	
		cargar_get(url_content,'content');
	}
}
function cargar_modulo(path_home,id_system_01)
{
	if (path_home!=''){	
		cargar_post("php/home.php",'content','id_system_01='+id_system_01+'&path_home='+path_home);
	}
}

function acceso_denegado()
{	
	showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...','No tiene acceso!','error','1');
}
function acceso_paso1()
{	
	showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...','Primero debes completar el formulario...','warning','2');
}

function restringido()
{	
	showDialog('<img src="../image/iconos/ha.png"  border="0" align="absmiddle"> Cuidado...','No puede hacer esto aqu\u00ed...','error','3');
}
function un_pop(respuesta_mostrar)
{	
	showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Informe...',''+respuesta_mostrar+'','success','3');
}

function validar_login(usu,cla,time)
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
		var vars="usu="+ usu + "&cla=" + cla + "&time=" + time;
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
									alert('Usuario o clave desconocidos.');
									document.getElementById("cla").value = '';
									document.getElementById("usu").value = '';
							}
							else if (respu=='errorlog')
							{
									alert('Usuario o clave inv\u00e1lido.');
									document.getElementById("cla").value = '';
									document.getElementById("usu").value = '';
							}
							else if (respu=='suspendido')
							{
									alert('Su cuenta fu\u00e9 suspendida.');
									document.getElementById("cla").value = '';
							}
							else if (respu=='restringido')
							{
									alert('\u00c1rea restringida!');
									document.getElementById("cla").value = '';
							}
							else if (respu=='rechazado')
							{
									alert('Su cuenta fu\u00e9 rechazada.');
									document.getElementById("cla").value = '';
							}
							else if (respu=='espera')
							{
									alert('Su cuenta a\u00fan no fue activada...');
									document.getElementById("cla").value = '';
							}
							else
							{
								
								if (tip=='3') // completar (no obligatorio)
								{
								showDialog('<img src="../image/iconos/cool.png"  border="0" align="absmiddle"> Trámite de 1 min.','Hay datos que aún no has completado...','success');
								cargar_post('modulos/control/php/home_abm.php','content','id_system_03='+respu+'');
								}
								else
								if (tip=='4')// completar obligatorio
								{						
								showDialog('<img src="../image/iconos/cool.png"  border="0" align="absmiddle"> Bienvenido/a!','Debe completar los datos importantes para continuar...','success');
								cargar_post('modulos/control/php/home_abm.php','content','id_system_03='+respu+'');
								}
								else
								if (tip=='5')// actualizar datos
								{						
								showDialog('<img src="../image/iconos/cool.png"  border="0" align="absmiddle"> Bienvenido/a!','Debe completar el formulario para continuar...','success');
								cargar_post('modulos/control/php/home_abm.php','content','id_system_03='+respu+'');
								}
								else
								if (tip=='6')// COMPLETAR BLOG: PERFIL DE VENDEDOR O SOCIO
								{						
								//showDialog('<img src="../image/iconos/cool.png"  border="0" align="absmiddle"> Bienvenido/a!','Debe completar el formulario de tu blog de ventas','success');
								cargar_post('modulos/blog/php/home_abm.php','content','id_system_03='+respu+'');
								}
								else
								{	
								cargar_get('php/home.php','content');
								}
								login=1;
							}

						}else if(ajax.status==404){
							alert('Error: No se encuent\u00f3 la p\u00e1gina');
						}else{
							alert("Error:".ajax.status); 
						}
				}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
	}
}



function recordar_clave(usu,email,codigo)
{
	if (usu=="" || email=="" || codigo=="")
	{
		alert("Ingrese su DNI, e-mail y clave de seguridad");
	}
	else
	{
		var ajax=nuevo_ajax(); 
		//peticionamos los datos, le damos la url enviada desde el link
		ajax.open("POST", "php/recordar.php",true);
		var vars="usu="+ usu + "&email=" + email + "&codigo=" + codigo;
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
							if (respu=='errorcodigo')
							{
									alert('El c\u00f3digo de seguiridad no es igual...');
									document.getElementById("usu").value = ''+cuil+'';
									document.getElementById("email").value = ''+email+'';
									document.getElementById("codigo").value = '';
							}
							else if (respu=='restringido')
							{
									alert('\u00c1rea restringida!');
									document.getElementById("usu").value = '';
									document.getElementById("email").value = '';
									document.getElementById("codigo").value = '';
							}
							else if (respu=='noemail')
							{
									alert('No tienes registrado un email a\u00fan...');
									document.getElementById("usu").value = '';
									document.getElementById("email").value = '';
									document.getElementById("codigo").value = '';
							}
							else if (respu=='estado0')
							{
									alert('Tu cuenta esta pendiente de activaci\u00f3n...');
									document.getElementById("usu").value = '';
									document.getElementById("email").value = '';
									document.getElementById("codigo").value = '';
							}
							else if (respu=='estado2')
							{
									alert('Tu cuenta esta suspendida!');
									document.getElementById("usu").value = '';
									document.getElementById("email").value = '';
									document.getElementById("codigo").value = '';
							}
							else if (respu=='desconocido')
							{
									alert('E-mail desconocido...');
									document.getElementById("usu").value = '';
									document.getElementById("email").value = '';
									document.getElementById("codigo").value = '';
							}
							else
							{	
								alert('Solicitud enviada correctamente! \nVerifique su correo, no olvide revisar en \"Correos no deseados\"...');
								document.getElementById("usu").value = '';
								document.getElementById("email").value = '';
								document.getElementById("codigo").value = '';
								login=0;
							}

						}else if(ajax.status==404){
							alert('Error: No se encuent\u00f3 la p\u00e1gina');
						}else{
							alert("Error:".ajax.status); 
						}
				}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
	}
}

function logout()
{
	var answer = confirm ("Cerrar sesi\u00f3n?");
	if (answer)
	{
		window.location = "php/romper.php";
	}
}

function logout_public()
{
	var answer = confirm ("Est\u00e1 seguro que desea cerrar la sesi\u00f3n?");
	if (answer)
	{
		ejecutar_get('php/break.php');
		login=0;
		ejecutar_get('php/logout.php');
		//window.location = "index.php"
		cargar_content('php/login.php');
		cargar_inicial('php/ver_nada.php'); 
			
	}
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
	showDialog('<img src="../image/iconos/loader.gif"  border="0" align="absmiddle"> Aguarde...','Presione ENTER','prompt','1');
	document.getElementById("variable_buscar").value = ''+vars+'';
	$(variable_buscar).focus();
	
}
function pinchar_enter(evt,url,id,vars)
{   
 if (evt.keyCode == 13)
	{
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
		id.innerHTML='<img src="image/icon-apprevent.gif"  border="0">';
	}
	else
	{
		id.innerHTML='<img src="image/icon-del.gif"  border="0">';
	}

}


function require_key_1(vars)
{
	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", "php/require_key.php",true);
	var vars="key_1=" + vars;
		ajax.onreadystatechange=function()
		{								
			id = document.getElementById('msg');
			var respu=ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
			var error=respu.substring(0,1);
			var msg=respu.substring(2,220);
			id.innerHTML=msg; 		
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(vars);
}

/*function temporalizador(url,id,vars,)
{
	var cont = 0;
	var rango = document.getElementById('cont');
	
	var id = setInterval(function(){
	rango.innerHTML = cont;
	cont++;
	if(cont == 3) 
	{
		clearInterval(id);
		if(cont > 2)
		{
		cargar_post(''+url+'',''+id+'','id_system_04='+vars+'');
		}							
	}						
	}, 1000);
}*/


var nav4 = window.Event ? true : false;
function acceptNum(evt)
{	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
var key = nav4 ? evt.which : evt.keyCode;
return (key <= 13 || (key >= 48 && key <= 57));
}


function acceptFloat(evt,val)
{	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
var key = nav4 ? evt.which : evt.keyCode;
var is_float=val.indexOf('.');

return (key <= 1 || (key >= 48 && key <= 57) || (key == 46 && is_float==-1));
alert(key);
}




function openNewWindow(URLtoOpen, windowName, windowFeatures) { 
  newWindow=window.open(URLtoOpen, windowName, windowFeatures);
}

function abrir_popup_mini(popup) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=400, height=200, top=30, left=30";
window.open(popup,"",opciones);
}

function abrir_popup (popup) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=450, height=580, top=10, left=10";
window.open(popup,"",opciones);
}

function abrir_popup_g (popup_g) {
var opciones2="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=250, left=250";
window.open(popup_g,"",opciones2);
}

function desplegar(solappas){
var menu = document.getElementById(solappas);
    if(menu.className == "desplegado"){
      menu.className = "ocultar_desplegado";
    }
    else{
      menu.className = "desplegado";
    }
}

function desplegar2(solappas){
var menu = document.getElementById(solappas);
    if(menu.className == "desplegado2"){
      menu.className = "ocultar_desplegado";
    }
    else{
      menu.className = "desplegado2";
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

function CuentaRespuesta(leng)
{
	var numleg = leng - 1;
	if ( (document.abm2.system_11b_responder.value.length) < leng )
	{
		document.abm2.caracteres.value = numleg - document.abm2.system_11b_responder.value.length;
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

function guardar_vars(url,vars){	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", url,true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}
function guardar_vars_refresh(url,vars){	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", url,true);
	
		
	window.location = "";
	
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars)
}