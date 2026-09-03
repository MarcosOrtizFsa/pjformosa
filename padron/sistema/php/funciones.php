<?php
// GENERAMOS UN NUMERO UNICO PARA IDENTIFICAR CUALQUIER DATO DE LA BD
date_default_timezone_set("America/Argentina/Buenos_Aires");
$system_fecha = date('Y-m-d');
$system_ano = date('Y');
$system_hora = date('H:i:s');
$hora_public = date('H:i');
$system_checked = time();		
$system_fecha_qr = date('Ymd');
$modo_login ="sistema";
$modulo ="";
$url_exito ="";
$id ="";
$vars_exito ="";
$where_control ="";
$id_system_101="";
$where_seccion="";
$system_total = '';
$total_filas='0';
$codigo_tabla='';
$pag='';
$sesion_system_03_mesa='';
$candado="";
$root_candado="";

$id_system_01 = isset($_GET['id_system_01']) ? $_GET['id_system_01'] : NULL;
if (isset($_POST['id_system_01'])!='')
{
$id_system_01 = isset($_POST['id_system_01']) ? $_POST['id_system_01'] : NULL;
}
$id_system_03=isset($_POST['id_system_03']) ? $_POST['id_system_03'] : NULL;
if ( isset($_GET['id_system_03']) !='' )
{
$id_system_03=isset($_GET['id_system_03']) ? $_GET['id_system_03'] : NULL;
}
$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
$rec = isset($_GET['rec']) ? $_GET['rec'] : NULL;
$mas = 	isset($_POST['mas']) ? $_POST['mas'] : NULL;
$system_01_modulo = isset($_GET['system_01_modulo']) ? $_GET['system_01_modulo'] : NULL;
$sesion_system_03 = isset($_SESSION['sesion_system_03']) ? $_SESSION['sesion_system_03'] : NULL;
$sesion_system_07= isset($_SESSION['sesion_system_07']) ? $_SESSION['sesion_system_07'] : NULL;
$sesion_system_06= isset($_SESSION['sesion_system_06']) ? $_SESSION['sesion_system_06'] : NULL;
$sesion_system_03_modo = isset($_SESSION['sesion_system_03_modo']) ? $_SESSION['sesion_system_03_modo'] : NULL;
$sesion_perfil = isset($_SESSION['sesion_perfil']) ? $_SESSION['sesion_perfil'] : NULL;
$sesion_vista = isset($_SESSION['sesion_vista']) ? $_SESSION['sesion_vista'] : NULL;
$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$reset = isset($_POST['reset']) ? $_POST['reset'] : NULL;

if ($reset == 'go')
{
$_SESSION['where_seccion']="";
$_SESSION['where_control']="";
}

$base = new Constructor_SQL(HOST,USU,CLA,BD);
$mysqli = new Abm($base);

$row = $mysqli -> system_08_configuracion();
if ($row == TRUE)
{
	$rela_system_06 = 				$row[0]['rela_system_06'];
	$system_08_titulo_site=			trim($row[0]['system_08_titulo_site']);
	$system_08_dominio = 			trim($row[0]['system_08_dominio']);
	$system_08_descripcion_site = 	$row[0]['system_08_descripcion_site'];
	$system_08_email_alerta = 		$row[0]['system_08_email_alerta'];		
	$system_08_path = 				$row[0]['system_08_path'];
	$system_08_contactos_visibles = $row[0]['system_08_contactos_visibles'];
	$system_08_limit_size_up_archivo = $row[0]['system_08_limit_size_up_archivo'];
	$system_08_ancho_max = 			$row[0]['system_08_ancho_max'];
	$system_08_titular = 			$row[0]['system_08_titular'];
	$system_08_email = 				$row[0]['system_08_email'];	
	$system_08_celular = 			$row[0]['system_08_celular'];
	$system_08_whatsapp = 			$row[0]['system_08_whatsapp'];
	$system_08_telefono = 			$row[0]['system_08_telefono'];
	$system_08_facebook = 			$row[0]['system_08_facebook'];
	$system_08_twitter = 			$row[0]['system_08_twitter'];
	$system_08_youtube = 			$row[0]['system_08_youtube'];
	$system_08_instagram = 			$row[0]['system_08_instagram'];
	$system_08_checked_1 = 			$row[0]['system_08_checked_1']; // opcion de tipo vista para ATENCION MESAS
	$system_08_tema = 				$row[0]['system_08_tema'];
	$system_08_total_objetivo = 	$row[0]['system_08_total_objetivo'];
}


$row = $mysqli -> system_07_privilegios($sesion_system_07);
if ($row == TRUE)
{
	$system_07_admin= 	$row[0]['system_07_admin'];
	$system_07_a= 		$row[0]['system_07_a'];
	$system_07_b= 		$row[0]['system_07_b'];
	$system_07_m= 		$row[0]['system_07_m'];
	$system_07_v= 		$row[0]['system_07_v'];
	$system_07_ad= 		$row[0]['system_07_ad'];
	$system_07_sis= 	$row[0]['system_07_sis'];
	$system_07_r = 		$row[0]['system_07_r'];				

	if ($sesion_system_07=='1')
	{
		$candado="on";
		$root_candado="on";
	}
	else
	if ($sesion_system_07=='2')
	{
		$candado="on";
		$root_candado="off";
	}
	else
	{
		$candado="off";
		$root_candado="off";			
	}		
}


$row = $mysqli -> system_03_usuarios($sesion_system_03);
if ($row == TRUE)
{		
	$system_03_cuir = $row[0]['system_03_cuir'];
}

$row = $mysqli -> system_06_sala($id_system_06);
if ($row == TRUE)
{		
	$system_06_sala=	$row[0]['system_06_sala'];
}

$row = $mysqli -> system_04_perfil($sesion_system_03);
if ($row == TRUE)
{		
	$system_04_name_usuario=substr($row[0]['system_04_nombre'], 0, 1).'. '.$row[0]['system_04_apellido'];	
}

$PAGINAR="";			
$num_filas="10";	
if ( $mas=='' or $mas=='0' )
{
$LIMITE =" limit 0,$num_filas ";
}
else
{
$num_filas = $mas + $num_filas;
$LIMITE=" limit 0,$num_filas ";
}


function funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars)
{		
	if ($num_filas > 0 or $total_filas !='0' or $tSQL !='')
	{
		if ($total_filas =='0' or $total_filas == '')
		{
		$total_filas = 0;
		}
		if ($num_filas =='0' or $num_filas == '')
		{
		$num_filas = 1;
		}
		$tSQL = ceil($total_filas/$num_filas); 
		
		if ($tSQL)
		{
			if ($num_filas > $total_filas)
			{
			$PAGINAS=''.$total_filas.' #';	
			}
			else
			{
			$PAGINAS='<a href="javascript:cargar_post('.$url.','.$id.','.$vars.');" title="Desplegar '.$num_filas.' +"><i class="bi bi-sort-down-alt"></i></a>&nbsp;&nbsp; '.$num_filas.' de '.$total_filas;	
			}
		}	
	}
	else
	{
	$PAGINAS='';
	}
	
	$PAGINAS = isset($PAGINAS) ? $PAGINAS : NULL;
	return "$PAGINAS";
}		
	
	
function color_fila($bgcolor)
{	
	$color_1="#fff";
	$color_2="#f8f9fa";
	if ( $bgcolor!=$color_1 )
	{
	$bgcolor=$color_1;
	}
	else
	{
	$bgcolor=$color_2;
	}
	return $bgcolor;
}

function optengo_modulo($id_system_01,$mysqli)
{			
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_01_modulos where id_system_01='$id_system_01' ");
	return $row[0]['system_01_modulo'];	 
}

function consulto_modulos_asignados($rela_system_03,$mysqli)
{			
	$cadena='';
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_02_permisos 
	where 
	rela_system_03 = '$rela_system_03'
	");
	if ($row == TRUE)
	{	
		for ( $i=0; $i < count($row); $i++)
		{
			$id_system_02 =	$row[$i]['id_system_02'];
/*			$system_02_A =	$row[$i]['system_02_A']; //Agrega 	
			$system_02_B  =	$row[$i]['system_02_B']; //Borra 	
			$system_02_M  =	$row[$i]['system_02_M']; //Modifica 	
			$system_02_E  =	$row[$i]['system_02_E']; //Estados 	
			$system_02_V  =	$row[$i]['system_02_V']; //Verifica 	
			$system_02_S  =	$row[$i]['system_02_S']; //Sube 	
			$system_02_D  =	$row[$i]['system_02_D']; //Descarga 	
			$system_02_I  =	$row[$i]['system_02_I']; //Imprime
			$system_02_C  =	$row[$i]['system_02_C']; //Chatea*/
			
			$permisos='';
			if ($row[$i]['system_02_A'] == 1)
			{$permisos.='A';}
			if ($row[$i]['system_02_B'] == 1)
			{$permisos.='B';}
			if ($row[$i]['system_02_M'] == 1)
			{$permisos.='M';}
			if ($row[$i]['system_02_E'] == 1)
			{$permisos.='E';}
			if ($row[$i]['system_02_V'] == 1)
			{$permisos.='V';}
			if ($row[$i]['system_02_S'] == 1)
			{$permisos.='S';}
			if ($row[$i]['system_02_D'] == 1)
			{$permisos.='D';}
			if ($row[$i]['system_02_I'] == 1)
			{$permisos.='I';}
			if ($row[$i]['system_02_C'] == 1)
			{$permisos.='C';}
	
			$cadena.= optengo_modulo($row[$i]['rela_system_01'],$mysqli).": ".$permisos." | ";	
				
		}	 
	}
	return $cadena;
}
	
function optengo_imagen($id_system_04,$modo,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_09_archivero where rela_system_04 = '$id_system_04' and system_09_tipo = '1' order by id_system_09 desc limit 1");
	if ($row == TRUE)
	{
		$system_09_path =		$row[0]['system_09_path'];
		$system_09_archivo =	$row[0]['system_09_archivo'];
		$retorno="1";
	}
	else
	{
		$system_09_path="../image";
		$system_09_archivo="no-imagen.jpg";
		$retorno="0";
	}
		
	if ($modo=='2')
	{
	return $retorno;	
	}
	else
	if ($modo=='1')
	{
	return '<div style="background: url('.$system_09_path.'/'.$system_09_archivo.') no-repeat 50% 50%; background-size:auto 50px ; width: 50px; height: 40px;"></div>';	
	}
	else
	{				
	return $system_09_path.'/'.$system_09_archivo;		
	}	
}

	
function consulto_perfil($rela_system_03,$mysqli)
{			
	$row = $mysqli -> consulta_SQL("SELECT system_03_usuarios.*, system_04_perfil.* FROM system_03_usuarios, system_04_perfil 
	where 
	system_04_perfil.rela_system_03=system_03_usuarios.id_system_03 
	and 
	system_03_usuarios.id_system_03='$rela_system_03'
	");
	if ($row == TRUE)
	{
		return substr($row[0]['system_04_nombre'], 0, 1).'. '.$row[0]['system_04_apellido'];	 
	}
}	

function consulto_contacto_perfil($rela_system_03,$mysqli)
{			
	$row = $mysqli -> consulta_SQL("SELECT system_03_usuarios.*, system_04_perfil.* FROM system_03_usuarios, system_04_perfil 
	where 
	system_04_perfil.rela_system_03 = system_03_usuarios.id_system_03 
	and 
	system_03_usuarios.id_system_03 = '$rela_system_03'
	");
	if ($row == TRUE)
	{
		return $row[0]['system_04_celular'];	 
	}
}

function consulto_nombre_apellido($rela_system_03,$mysqli)
{			
	$row = $mysqli -> consulta_SQL("SELECT system_03_usuarios.*, system_04_perfil.* FROM system_03_usuarios, system_04_perfil 
	where 
	system_04_perfil.rela_system_03=system_03_usuarios.id_system_03 
	and 
	system_03_usuarios.id_system_03='$rela_system_03'
	");
	if ($row == TRUE)
	{
		return $row[0]['system_04_nombre'].' '.$row[0]['system_04_apellido'];	 
	}
}

function consultar_localida($id_system_501,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_501_departamentos WHERE id_system_501 = '$id_system_501' ");
	if ($row == TRUE)
	{
		return $row[0]['system_501_departamento'];
	}		
}

function consultar_municipio($id_system_502,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_502_circuitos WHERE id_system_502 = '$id_system_502' ");
	if ($row == TRUE)
	{
		return $row[0]['system_502_localidades'];
	}		
}


function crear_capcha()
{			
// Generamos codigos para los campos que lo requieran
$codigo_tabla='';
$max_chars = round(rand(5,5));  // tendrá entre 7 y 9 caracteres
$chars = array();
	for ($i='a'; $i<'z'; $i++) $chars[] = $i;  // creamos vector de letras
	$chars[] = 'z';
	for ($i=0; $i<$max_chars; $i++)
	{
	  $letra = round(rand(0, 1));  // primero escogemos entre letra y número
	  if ($letra) // es letra
			$codigo_tabla .= $chars[round(rand(0, count($chars)-1))];
	  else 
			$codigo_tabla .= round(rand(0, 9));
	}

return $codigo_tabla;
}	


function formatear_fecha($fecha)
{	
	if ($fecha!='')
	{
		$v_fecha = explode('-',$fecha);
		$dia=$v_fecha[0];
		if (strlen($dia)==1)
			$dia="0".$dia;
	
		$mes=$v_fecha[1];
		if (strlen($mes)==1)
			$mes="0".$mes;
	
		$ano=trim($v_fecha[2]);
		
		$ret_fecha=$ano."-".$mes."-".$dia;
	}
	else
	{
	$ret_fecha="";
	}
	return $ret_fecha;
}
function formatear_hora($hora)
{
	$h = explode(':',$hora);
	$_hora=$h[0];

	$_min=$h[1];
	
	$ret_hora=$_hora .":". $_min;
	return $ret_hora;
}
function quito_0($vars)
{
	if ($vars=='0000-00-00'){$vars="";}
	if ($vars=='00-00-0000'){$vars="";}
	if ($vars=='--')		{$vars="";}
	if ($vars=='00:00:00')	{$vars="";}
	if ($vars=='00:00')		{$vars="";}
	if ($vars==':')			{$vars="";}
	if ($vars=='0.0')		{$vars="";}
	if ($vars=='0.00')		{$vars="";}
	if ($vars=='00')		{$vars="";}
	if ($vars=="0")			{$vars="";}
	return $vars;
}

function porcentual($totales,$sobre)
{
	$retorno = '0';
	if (quito_0($totales) != "" and quito_0($sobre) != "" )
	{
		$retorno = round(($totales * 100) / $sobre, 1);
	}
	return $retorno;	
}

function hora_min($hora)
{
	$h = explode(':',$hora);
	$_hora=$h[0];

	$_min=$h[1];
	
	$ret_hora=$_hora .":". $_min;
	return $ret_hora;
}

function numero_mesa($num)
{
	if (strlen($num)==1)
	{$num="0".$num;} 
	return $num;
}

function ano_fecha($fecha)
{	
	$v_fecha = explode('-',$fecha);
	$ano=$v_fecha[0];
	$mes=$v_fecha[1];
	$dia=$v_fecha[2];
	
	return $ano;
}

function formatear_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	$dni=str_replace(" ","",$dni); 
	return $dni;
}

function formatear_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit); 
	return $cuit;
}
function verifico_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	if (strlen($cuit)=='11') {
	$cuit=preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\1-\\2-\\3", $cuit);
	$row = explode('-',$cuit);
	if ($row[0] < 10){$cuit="";}
	}else{
	$cuit="";
	}
	return $cuit;
}

function verifico_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	if (strlen($dni)!='8'){
	$dni="";
	}
	return $dni;
}
function optener_solo_dni($vars)
{
	$vars=str_replace(" ","",$vars); 
	$vars=str_replace(".","",$vars); 
	$vars=str_replace("-","",$vars); 
	$vars= preg_replace('/[a-zA-Z]/','', $vars);
	$vars= preg_replace('/[^0-9]/','@', $vars);
	return trim($vars);
}
function convert_dni($dni)
{
	$dni = preg_replace('/([0-9]{1,2})+([0-9]{3})+([0-9]{3})/', "\\1.\\2.\\3", $dni); 
	return trim($dni);
}
function convert_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	$cuit = preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\1-\\2-\\3", $cuit); 
	$cuit = trim($cuit);	 
	return $cuit;
}
function convert_cuil_a_dni($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	$cuit = preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\2", $cuit); 
	$cuit = trim($cuit);	 
	return $cuit;
}


function urls_amigables($url_amigo) {
	// Tranformamos todo a minusculas
	$url_amigo = strtolower($url_amigo);
	//Rememplazamos caracteres especiales latinos
	$finx = array('&','ñ','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú');
	$repx = array('-','n','N','A','E','I','O','U','a','e','i','o','u');
	$url_amigo = str_replace($finx, $repx, utf8_decode($url_amigo));	
	
	// Añaadimos los guiones
	$findv = array(' ', '&', '\r\n', '\n', '+');
	$url_amigo = str_replace ($findv, '-', $url_amigo);
	// Eliminamos y Reemplazamos demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url_amigo = preg_replace ($find, $repl, $url_amigo);
	return $url_amigo;
}

function prefijo_whatsapp($phone) 
{
	// AGREGO EL PREFIJO 9 AL NUMERO = +54 9 0000 000000
	// quito espacios si tubiera 
	$phone = str_replace(' ','',$phone);
	// Indico el 9 de la caracteristica arg manualmente si no lo ponen
	$phone = preg_replace('/^(\+54)+([0-9]{4})+([0-9]{6})/','\\1 9 \\2 \\3',$phone);
	// quito los espacios en blanco
	$phone = str_replace(' ','',$phone);
	return $phone;
}

function formatear_tel_cel($num)
{
	$num=str_replace("+","",$num); 
	$num=str_replace("-","",$num); 
	$num=str_replace("(","",$num);
	$num=str_replace(")","",$num);
	$num=str_replace(" ","",$num);
	return $num;
}

function fechaleible($FechaLeible) 
{
	$system_fechale=str_replace("-",".",$FechaLeible);
	$my_fecha=strtotime($system_fechale);
	$system_fecha_dia=date('l', $my_fecha);
	$system_fecha_nro=date('j', $my_fecha);
	$system_fecha_mes=date('F', $my_fecha);
	$system_fecha_ano=date('Y', $my_fecha);
	$system_fecha_dia=str_replace("Monday","Lunes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Tuesday","Martes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Wednesday","Mi&eacute;rcoles",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Thursday","Jueves",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Friday","Viernes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Saturday","S&aacute;bado",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Sunday","Domingo",$system_fecha_dia); 
	$system_fecha_mes=str_replace("January","Enero",$system_fecha_mes); 
	$system_fecha_mes=str_replace("February","Febrero",$system_fecha_mes); 
	$system_fecha_mes=str_replace("March","Marzo",$system_fecha_mes); 
	$system_fecha_mes=str_replace("April","Abril",$system_fecha_mes); 
	$system_fecha_mes=str_replace("May","Mayo",$system_fecha_mes); 
	$system_fecha_mes=str_replace("June","Junio",$system_fecha_mes); 
	$system_fecha_mes=str_replace("July","Julio",$system_fecha_mes); 
	$system_fecha_mes=str_replace("August","Agosto",$system_fecha_mes); 
	$system_fecha_mes=str_replace("September","Setiembre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("October","Octubre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("November","Noviembre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("December","Diciembre",$system_fecha_mes); 
	
	
	if ( $FechaLeible == date('Y-m-d') )
	{
	$FechaLeible="Hoy";	
	}
	else
	{
	$FechaLeible=$system_fecha_dia." ".$system_fecha_nro;	
	}
	
	return $FechaLeible;
}	

function extraer_tipo_clase($vars,$msj)
{	
	if ($msj=='')
	{
	$msj="tipo/clase...";
	}		
	return '<input type="file" id="archivos" onChange="down_tipo_clase(\''.$vars.'\');" style=" height:24px; width: 45%; font-size:10px; float:left;"><div style=" height:24px; width: 45%; font-size:10px; float:left;" id="estado_de_tipo">'.$msj.'</div>';
}


function funcion_cargar_elemento($url_exito,$id,$vars,$msj,$tipo_multiple)
{	
	if ($msj=='')
	{
	$msj="Examinar...";
	}
		
	return '<input type="file" '.$tipo_multiple.' 	id="archivos" class="form-control" onChange="down_multiple_archivos(\''.$url_exito.'\',\''.$id.'\',\''.$vars.'\');"  	style=" height:30px; width: 90px; font-size:12px; float:left;"><div id="estado_de_carga" style="padding-top:6px; font-size:12px; float:left;">&nbsp;&nbsp;'.$msj.'</div>';	
}

function recortar_texto($texto,$limite)
{			
	if ( strlen($texto) > $limite )
	{
		$texto = sprintf("%.".$limite."s\n",$texto)." ...+ ";
	}			
	return $texto;
}

//--------------------------------------------------



function optengo_localidad($id_system_501,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_501_localidad where id_system_501='$id_system_501'");
	return $row[0]['system_501_departamento'];	
}

function desformatear_barrios($system_502_localidades,$system_2000_barrio)
{
	$cadena='';
	$array = explode("\n", $system_502_localidades);
	foreach ($array as $value) 
	{
		if ($value!='')
		{
			if ( $system_2000_barrio == trim($value) )
			{
			$cadena.= '<option value="'.trim($value).'" selected >'.trim($value).'</option>';
			}
			else
			{
			$cadena.= '<option value="'.trim($value).'">'.trim($value).'</option>';
			}
			
		}		
	}	
	return $cadena;
}
function optengo_barrios($system_04_circuito,$system_2000_barrio,$mysqli)
{
	$cadena = '';
	$row = $mysqli -> consulta_SQL("Select * from system_502_circuitos WHERE system_502_circuito = '$system_04_circuito' order by rela_system_501, system_502_circuito asc");
	if ( $row == true )
	{
		for ( $i=0; $i < count($row); $i++)
		{
			$id_system_502 =		$row[$i]['id_system_502'];
			$rela_system_501 =		$row[$i]['rela_system_501'];
			$system_502_circuito =	$row[$i]['system_502_circuito'];
			$system_502_localidades =$row[$i]['system_502_localidades'];		

			
			$cadena.= desformatear_barrios($system_502_localidades,$system_2000_barrio);	
				
		}	

	}
	return $cadena;	
}


function funcion_traer_datos_padron($dni,$mysqli)
{	
	
	$digit_dni = substr("$dni", -1);
		
		if ($digit_dni == 1)
		{
		$where = "Select * from system_2000_padron_1 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 2)
		{
		$where = "Select * from system_2000_padron_2 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 3)
		{
		$where ="Select * from system_2000_padron_3 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 4)
		{
		$where = "Select * from system_2000_padron_4 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 5)
		{
		$where = "Select * from system_2000_padron_5 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 6)
		{
		$where = "Select * from system_2000_padron_6 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 7)
		{
		$where = "Select * from system_2000_padron_7 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 8)
		{
		$where = "Select * from system_2000_padron_8 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 9)
		{
		$where = "Select * from system_2000_padron_9 where system_2000_dni = '$dni' ";
		}
		else
		{
		$where = "Select * from system_2000_padron_0 where system_2000_dni = '$dni' ";
		}
		
	$row = $mysqli -> consulta_SQL("$where");
	if ($row == true)
	{			
		$datos_cadena = $row[0]['system_2000_apellido_nombre'].'@'.$row[0]['system_2000_crto'];
	}
	else
	{
		$datos_cadena = '@';
	}

	 return $datos_cadena;
}

function funcion_traer_localidad_por_circuito($system_506_circuito,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_506_localidad  where system_506_circuito = '$system_506_circuito'");				
	if($row == true)
	{		
		$datos_cadena = $row[0]['system_506_dpto'].'@'.$row[0]['system_506_localidad'];
	}
	else
	{
		$datos_cadena = 'No-hay-datos@S/N';
	}
	 return $datos_cadena;
}

function funcion_traer_circuito($system_04_dni,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select system_04_circuito from system_04_perfil where  system_04_dni = '$system_04_dni' limit 1");
	if ($row == true)
	{			
		return $row[0]['system_04_circuito'];
	}
	else
	{
		return "?...";
	}
}
function funcion_traer_datos_full($dni,$mysqli)
{	
	
	$digit_dni = substr("$dni", -1);
		
		if ($digit_dni == 1)
		{
		$where = "Select * from system_2000_padron_1 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 2)
		{
		$where = "Select * from system_2000_padron_2 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 3)
		{
		$where ="Select * from system_2000_padron_3 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 4)
		{
		$where = "Select * from system_2000_padron_4 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 5)
		{
		$where = "Select * from system_2000_padron_5 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 6)
		{
		$where = "Select * from system_2000_padron_6 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 7)
		{
		$where = "Select * from system_2000_padron_7 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 8)
		{
		$where = "Select * from system_2000_padron_8 where system_2000_dni = '$dni' ";
		}
		else
		if ($digit_dni == 9)
		{
		$where = "Select * from system_2000_padron_9 where system_2000_dni = '$dni' ";
		}
		else
		{
		$where = "Select * from system_2000_padron_0 where system_2000_dni = '$dni' ";
		}
		
	$row = $mysqli -> consulta_SQL("$where");
	if ($row == true)
	{			
		$datos_cadena = $row[0]['system_2000_apellido_nombre'].'@'.$row[0]['system_2000_crto'].'@'.$row[0]['system_2000_domicilio'];
	}
	else
	{
		$datos_cadena = '@@';
	}

	 return $datos_cadena;
}
function consulto_escuela($system_607_mesa,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_503_mesas	where 	system_503_mesa = '$system_607_mesa'	");						
	return $row[0]['system_503_escuela'];		
}

function format_imagen_name($simplifico_name)
{
	
	// Añaadimos los guiones
	$find2 = array('/ /', '/&/');
	$repl2 = array('-', '-');
	$simplifico_name = preg_replace($find2, $repl2, $simplifico_name);
	
	
	// Eliminamos y Reemplazamos demás caracteres especiales
	$find3 = array('/[^a-zA-Z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl3 = array('', '_', '');
	$simplifico_name = preg_replace($find3, $repl3, $simplifico_name);
	// Tranformamos todo a minusculas
	$simplifico_name = strtolower($simplifico_name);
	return $simplifico_name;
}

function funcion_ver_escuela($modo,$system_607_mesa,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_505_escuelas  where system_505_mesa = '$system_607_mesa'");				
	if($row == true)
	{		
			return	$row[0]['system_505_escuela'].' ('.$row[0]['system_505_direccion'].' - '.ver_localidad_por_circuito($row[0]['system_505_circuito'],$mysqli).')';
	}
}

function funcion_ver_escuela_circuito($system_607_mesa,$mysqli)
{}

function ver_localidad_por_circuito($system_506_circuito,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_506_localidad  where system_506_circuito = '$system_506_circuito'");				
	if($row == true)
	{		
		return	$row[0]['system_506_localidad'].', '.$row[0]['system_506_circuito'];
	}
}

function escuela_x_circuito($system_502_circuito,$mysqli)
{
	$cadena='';
	$row = $mysqli -> consulta_SQL("SELECT * FROM  system_506_localidad  where system_506_circuito = '$system_502_circuito' ");
	if ($row == TRUE)
	{
		$cadena.= '(Cto. '.$row[0]['system_506_circuito'].') '.$row[0]['system_506_localidad'].' - '.consultar_localida($row[0]['system_506_dpto'],$mysqli).'';
	} 	
	return $cadena;
}

function extraer_pueblos_por_circuito($system_502_circuito,$mysqli)
{}

function pueblo_por_id($modo,$id_system_504,$mysqli)
{}

function escuela_mesa($modo,$id_system_505,$mysqli)
{
	$cadena='';
	$row = $mysqli -> consulta_SQL("SELECT * FROM  system_505_escuelas where id_system_505 = '$id_system_505' ");
	if ($row == TRUE)
	{  	 	 	
		if ($modo == 3)
		{
		$cadena.= $row[0]['system_505_circuito'];	
		}
		else
		if ($modo == 2)
		{
			$cadena.= $row[0]['system_505_escuela'].' ('.$row[0]['system_505_direccion'].') ';	
			
			if ($row[0]['system_505_googlemaps'] != '' )
			{
				$cadena.= '<a href="'.$row[0]['system_505_googlemaps'].'" target="news"><i class="bi bi-geo-alt-fill"></i></a>';	
			}
		
		}
		else
		if ($modo == 1)
		{
			if ($row[0]['system_505_googlemaps'] != '' )
			{
				$cadena.= '<a href="'.$row[0]['system_505_googlemaps'].'" target="news"><i class="bi bi-geo-alt-fill"></i></a>';	
			}		
		}
		else
		{
		$cadena.= $row[0]['system_505_escuela'];	
		}				
	} 	
	return $cadena;
}




function funcion_cargar_dni($url_exito,$id,$vars,$msj,$style)
{	
	if ($msj=='')
	{
	$msj="Examinar...";
	}	
	return '<input type="file" id="archivos" class="examinar" onChange="down_dni(\''.$url_exito.'\',\''.$id.'\',\''.$vars.'\');" '.$style.'><div id="estado_de_carga" style="font-size:10px;">'.$msj.'</div>';
	
}


function funcion_consulto_extras($dni,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_2001_extras where system_2001_dni = '$dni' ");				
	if($row == true)
	{
		if ($row[0]['system_2001_estado'] == '0')
		{
			return '1';
		}
		else
		{
			return '2';
		}
		
		
	}
	else
	{
		return '0';
	}
}

function funcion_consulto_imagen_dni($dni,$mysqli)
{
	$file = '../dnis/';	
	$retorno = '';
	$row = $mysqli -> consulta_SQL("Select * from system_2001_extras where system_2001_dni = '$dni' ");				
	if($row == true)
	{
		$system_2001_ima_frente = 	$row[0]['system_2001_ima_frente'];
		$system_2001_ima_dorso = 	$row[0]['system_2001_ima_dorso'];
		
		if ( trim($system_2001_ima_frente) != '' )
		{	
			$retorno.= '<img src="'.$file.''.$system_2001_ima_frente.'" style=" height: 100px; margin:10px;" />';
		}
	
		if ( trim($system_2001_ima_dorso) != '' )
		{
			$retorno.= '<img src="'.$file.''.$system_2001_ima_dorso.'" style=" height: 100px; margin:10px; " />';
		}
		
	}
	return $retorno;
}




function localidad_por_circuito($system_506_circuito,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from system_506_localidad  where system_506_circuito = '$system_506_circuito'");				
	if($row == true)
	{		
		$datos_cadena = $row[0]['system_506_localidad'].' - '.consultar_localida($row[0]['system_506_dpto'],$mysqli).'';
	}
	else
	{
		$datos_cadena = 'No-hay-datos...';
	}
	 return $datos_cadena;
}

function mesa_escuela($system_504_mesa,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from   system_504_mesas  	where 	system_504_mesa = '$system_504_mesa'	");	
	if($row == true)
	{		
		return $row[0]['system_504_mesa'].'@'.$row[0]['system_504_escuela'].'@'.$row[0]['system_504_dpto'].'@'.$row[0]['system_504_localidad'];
	}
	else
	{
		return '@@@';
	}					
}


function mesa_circuito($system_circuito,$mysqli)
{
	$row = $mysqli -> consulta_SQL("Select * from  system_506_localidad 	where 	system_506_circuito = '$system_circuito'	");	
	if($row == true)
	{	
	 //system_506_dpto 	system_506_circuito 	system_506_localidad 					
		return $row[0]['system_506_localidad'];		
	}
}

function direcion_escuela($system_505_mesa,$mysqli)
{}

function consultar_departamento($id_system_501,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_501_departamentos WHERE id_system_501 = '$id_system_501' ");
	if ($row == TRUE)
	{
		return $row[0]['system_501_departamento'];
	}		
}

function traigo_escuela($id_system_503,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_503_escuelas  WHERE id_system_503 = '$id_system_503' ");
	if ($row == TRUE)
	{
		return $row[0]['system_503_escuela'];
	}		
}

?>