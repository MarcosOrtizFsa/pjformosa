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
$rela_system_06=1;
$path_dominio='';
$variable_buscar='';
$rela_system_10 = 		isset($_GET['rela_system_10']) ? $_GET['rela_system_10'] : NULL;
$rela_system_03 = 		isset($_GET['rela_system_03']) ? $_GET['rela_system_03'] : NULL;
$re = 		isset($_GET['re']) ? $_GET['re'] : NULL;
$or = 		isset($_GET['or']) ? $_GET['or'] : NULL;
$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
$rec = isset($_GET['rec']) ? $_GET['rec'] : NULL;
$mas = 	isset($_POST['mas']) ? $_POST['mas'] : NULL;
$reset = isset($_POST['reset']) ? $_POST['reset'] : NULL;
$pag = 		isset($_GET['pag']) ? $_GET['pag'] : NULL;

$base = new Constructor_SQL(HOST,USU,CLA,BD);
$mysqli = new Abm($base);

$row = $mysqli -> system_08_configuracion();
if ($row == TRUE)
{

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
	$system_08_checked_1 = 			$row[0]['system_08_checked_1']; // opcion de tipo vista monitor publico
	//$system_08_checked_2 = 			$row[0]['system_08_checked_2'];
}


$PAGINAR="";			
$num_filas="100";	
if ( $mas=='' or $mas=='0' )
{
$LIMITE =" limit 0,$num_filas ";
}
else
{
$num_filas = $mas + $num_filas;
$LIMITE=" limit 0,$num_filas ";
}


function funcion_paginar_siguiente($id_system_01,$total_filas,$num_filas,$url,$id,$vars)
{		
	$PAGINAS='';
	$tSQL = ceil($total_filas/$num_filas); 
	if ($tSQL)
	{
		if ($num_filas > $total_filas)
		{
			$PAGINAS=''.$total_filas.' #';	
		}
		else
		{
		
			if ( $total_filas < '100' )
			{
				$vars2 = "'id_system_01=$id_system_01&mas=$total_filas'";
				$total_filas='<a href="javascript:cargar_paginar('.$url.','.$id.','.$vars2.');" title="Desplegar todos" class="link-dark">'.$total_filas.'</a>';	
			}
	
			$PAGINAS='<a href="javascript:cargar_paginar('.$url.','.$id.','.$vars.');" title="Desplegar '.$num_filas.' +" class="link-dark"><i class="bi bi-sort-down-alt"></i></a>&nbsp;&nbsp; '.$num_filas.' de '.$total_filas;	
		}
	}
	return "$PAGINAS";
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
	if ($vars==" ")			{$vars="";}
	return $vars;
}

function hora_min($hora)
{
	$h = explode(':',$hora);
	$_hora=$h[0];

	$_min=$h[1];
	
	$ret_hora=$_hora .":". $_min;
	return $ret_hora;
}

function formatear_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	return $dni;
}
function verifico_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	if (strlen($dni)!='8'){
	$dni="";
	}
	return $dni;
}


function texto_name_directorio($name) {
	// Tranformamos todo a mayuscula
	$name = strtolower($name);
	$name = str_replace(' ','_',$name);
	$name = str_replace('"','_',$name);
	return $name;
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
	$phone = str_replace(' ','',trim($phone));
	// Indico el 9 de la caracteristica arg manualmente si no lo ponen
	$phone = preg_replace('/^([0-9]{4})+([0-9]{6})/','549 \\1 \\2',$phone);
	// quito los espacios en blanco
	$phone = str_replace(' ','',$phone);
	return $phone;
}

/*function prefijo_whatsapp($phone) 
{
	$phone = str_replace(' ','',$phone);
	$phone = preg_replace('/^(\+54)+([0-9]{4})+([0-9]{6})/','\\1 9 \\2 \\3',$phone);
	$phone = str_replace(' ','',$phone);
	return $phone;
}*/
function prefijo_celular($phone) 
{
	// AGREGO EL PREFIJO 9 AL NUMERO = +54 9 0000 000000
	// quito espacios si tubiera 
	$phone = str_replace(' ','',$phone);
	// Indico el 9 de la caracteristica arg manualmente si no lo ponen
	$phone = preg_replace('/^([0-9]{4})+([0-9]{6})/','\\1 \\2',$phone);
	return $phone;
}
function convertir_celular_a_whatsapp($phone) 
{
	return "549".$phone;
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

function recortar_texto($texto,$limite)
{			
	if ( strlen($texto) > $limite )
	{
		$texto = sprintf("%.".$limite."s\n",$texto)." ...+ ";
	}			
	return $texto;
}
function dangerous_texts($vars)
{	
	$vars = preg_replace('/(\$)+([0-9\.\,]+)/','\\1 \\2 ',$vars);
	/*$vars = preg_replace('/>/','&#8250;',$vars);
	$vars = preg_replace('/</','&#8249;',$vars);*/
	$vars = str_replace("'","\"",$vars);
	/*$vars = str_replace("’","\"",$vars);
	$vars = str_replace("´","\"",$vars); 
	$vars = str_replace('“',"\"",$vars);
	$vars = str_replace("”","\"",$vars);*/
	
	return $vars;
}


function optengo_imagen($where,$mysqli)
{
	
	$system_09_path = 			"image";
	$system_09_archivo = 		"no-imagen.jpg";
	$row = $mysqli -> consulta_SQL("Select * from system_09_archivero $where order by id_system_09 desc limit 1");
	if ($row == TRUE)
	{
		$system_09_path =		$row[0]['system_09_path'];
		$system_09_archivo =	$row[0]['system_09_archivo'];
	}				
	return $system_09_path.'/'.$system_09_archivo;		
		
}

?>