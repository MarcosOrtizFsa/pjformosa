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
function equipo_sin_definir()
{	
	alert('Faltan equipos por definir...');
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
									alert('El v\u00ednculo ha expirado...');
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
		id.innerHTML='OK';
	}
	else
	{
		id.innerHTML='?';
	}
}




function abrir_popup(popup) 
{
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=370, height=500, top=10, left=20";
	window.open(popup,"",opciones);
}

function abrir_popup_g(popup_g) 
{
	var opciones2="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1100, height=750, top=250, left=250";
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




// ----------------------------------------- AREA DE SISTEMA DE MOZOS Y COMANDA DE RESTAURAN





function cargar_post_home(url,id,vars){

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
					actividades();
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



// AUTO CARGADORES
var intervalId_Panel = null;
function iniciar_interval_panel() {
    intervalId_Panel = setInterval(refres_divid_panel, 5000); // Cada 5 segundo
}
function refres_divid_panel()
{		
	id = document.getElementById('div_id');				
	var ajax=nuevo_ajax(); 
	ajax.open("POST", "modulos/home/php/jason_refres.php",true);
	var vars="";	
	
	ajax.onreadystatechange=function()
	{					
		var respuesta = ajax.responseText.replace(/(^\s*)|(\s*$)/g,"");
		var accion = respuesta.substring(0,1);
		var num_coun = respuesta.substring(1,5);
		if  (accion == '1')
		{
			div_id.innerHTML = 'Actualizando...';
			actividades();			
		}
		else
		{
			div_id.innerHTML = ' ';
		}				
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}

function actividades()
{		
	//detengo el intervalo de tiempo
	clearInterval(intervalId_Panel);
	
	ref_auto_lista = document.getElementById('ref_auto_lista');	
	bot_stop = document.getElementById('bot_stop');	
	var ajax=nuevo_ajax(); 
	ajax.open("POST", "modulos/home/php/jason_actividad.php",true);
	var vars="";
	
	
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
				cadena += `	<table width="100%" border="0px" cellpadding="0px" cellspacing="0">`
				cadena += `	<tr>`
				cadena += `		<td width="50%"> `   
				cadena += `		<b>Votos Seguro / Total Votantes (Cir. 56, 56A)</b>`
				cadena += `		</td>`
				cadena += `		<td width="50%" class=" align-right">`
				cadena += `		<b>${data[0].total_voto_seguro} de ${data[0].TotalPadron}</b>`
				cadena += `		</td>`
				cadena += `	</tr>`
				cadena += `	<tr>`
				cadena += `		<td colspan="2" style="background:#cccccc; width:100%; height:40px;"> `   
				cadena += `		<div style="width:${data[0].barratotal}%; background: #0099CC;  height: 30px; margin:2px; text-align:right; color:#fff; font-size:14px; padding-top:10px;">${data[0].barratotal}% </div>`
				cadena += `		</td>`
				cadena += `	</tr>`
				
				cadena += `	<tr>`
				cadena += `		<td> `   
				cadena += `		<b>Votos Seguro / Votaron votos seguro</b>`
				cadena += `		</td>`
				cadena += `		<td class=" align-right">`
				cadena += `		<b>${data[0].total_voto_seguro} de ${data[0].votaron_voto_seguro}</b>`
				cadena += `		</td>`
				cadena += `	</tr>`
				cadena += `	<tr>`
				cadena += `		<td colspan="2" style="background:#cccccc; width:100%; height:40px;"> `   
				cadena += `		<div style="width:${data[0].barratotal2}%; background: #0099CC;  height: 30px; margin:2px; text-align:right; color:#fff; font-size:14px; padding-top:10px;">${data[0].barratotal2}% </div>`
				cadena += `		</td>`
				cadena += `	</tr>`

	/*			
<table width="100%" border="0" cellspacing="10px" cellpadding="0px">
<tr >
	<td><h4>POR SUBLEMA</h4></td>
</tr>
<tr >
		<td>
		<form name="abm" id="abm" method="post">
		<table width="100%" border="0" cellspacing="0px" cellpadding="0px">
		<tr>
			<td width="6%" class="">
			N&deg;
			</td>
			<td class="">
			AGRUPACION POLITICA 
			</td>
			<td class="" width="10%">
			DIP NAC
			</td>
			<td class="" width="10%">
			DIP PRO
			</td>
			<td class="" width="10%">
			CON MUN
			</td>
			<td class="" width="7%">&nbsp;</td>
		</tr>
		
		
		{LISTADO}
		
			<tr id="content_">
				<td class="" colspan="4">
					<a href="javascript:;" onClick="{funcion_agregar}" class="lin">Agregar Sublema</a>
				</td>
			</tr>
		</table>
		</form>	
<br />
<br />
		<form name="abm2" id="abm2" method="post">
		<table width="100%" border="0" cellspacing="0px" cellpadding="0px" style="border:1px solid #333;"  id="content_totales">
		<tr class="listado" >
			<td width="50%" class="align-100">&nbsp;
			
			</td>
			<td class="align-50">
			<a href="javascript:;" onClick="{funcion_editar_totales}"><img src="../image/iconos/edit.png"  class="ico"  border="0" align="absmiddle"/></a>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h3>VOTOS NULOS</h3>
			</td>
			<td class="align-50">
			<h3>{system_606_nulos}</h3>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h3>VOTOS RECURRIDOS</h3>
			</td>
			<td class="align-50">
			<h3>{system_606_recurridos}</h3>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h3>VOTOS DE IDENTIDAD IMPUGNADA</h3>
			</td>
			<td class="align-50">
			<h3>{system_606_impugnada}</h3>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h3>VOTOS DE COMANDO ELECTORAL</h3>
			</td>
			<td class="align-50">
			<h3>{system_606_comando}</h3>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h3>VOTOS EN BLANCO</h3>
			</td>
			<td class="align-50">
			<h3>{system_606_blanco}</h3>
			</td>
		</tr>
		<tr class="listado" >
			<td width="50%" class="align-100">
			<h1>TOTAL VOTOS</h1>
			</td>
			<td class="align-50">
			<h1>{system_606_total}</h1>
			</td>
		</tr>
		</table>
		
		</form>	*/
		
		
				for(var i = 0; i < data.length; i++) 
				{		
					cadena += ` 	<tr>`
					cadena += ` 		<td colspan="4">`
					cadena += ` 			TITULO ${data[i].barratotal2}`
					cadena += ` 		</td>`
					cadena += ` 	</tr>`
					cadena += ` 	<tr>`
					cadena += ` 		<td>`
					cadena += ` 			`
					cadena += ` 		</td>`
					cadena += `		<td>`	
					cadena += `			`
					cadena += `		</td>`
					cadena += `		<td>`	
					cadena += `			`
					cadena += `		</td>`
					cadena += ` 		<td>`
					cadena += ` 			`
					cadena += ` 		</td>`
					cadena += ` 	</tr>`
					cadena += ` 	<tr>`
					cadena += ` 		<td colspan="4">`
					cadena += ` 		`
					cadena += ` 		</td>`
					cadena += ` 	</tr>`
				}
				cadena += `</table>`
				ref_auto_lista.innerHTML = cadena;
				
				
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
	// inicio nuevamente el intervalo de tiempo
	iniciar_interval_panel();
	bot_stop.innerHTML = '';
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(vars);
}




function shouldset(passon){
if(document.colr.hexvalue.value.length == 7){setcolor(passon)}
}

function setcolor(elem){
document.colr.hexvalue.value=elem
     document.colr.selcolor.style.backgroundColor=elem
}



function nuevo_circuito_js(system_505_circuito,url_exito,id_exito,vars_exito)
{
	
	var system_504_pueblo = prompt('Localidad o Barrio:','')	
	if (system_504_pueblo)
	{
		var ajax=nuevo_ajax(); 
		ajax.open("POST", "modulos/localidades/php/_interfaz.php",true);
		var vars = "nombre_funcion=agregar_modificar_pueblo&system_504_pueblo="+system_504_pueblo+"&system_504_circuito=" + system_505_circuito;	
		ajax.onreadystatechange = function()
		{	
			if(ajax.readyState==1)
			{}
			else 
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{
				
				cargar_post(url_exito,id_exito,vars_exito);		
						
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
		ajax.send(vars);
	}
	
}





